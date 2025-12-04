<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Vista PÚBLICA - Tienda para clientes
     */
    public function index()
    {
        $servicios = Servicio::all();
        return view('servicios.tienda', compact('servicios'));
    }

    /**
     * Vista ADMINISTRATIVA - Para administradores (CRUD)
     */
    public function indexAdmin()
    {
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_servicio' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024' // Reducido a 1MB
        ]);

        $data = $request->all();

        // CONVERTIR IMAGEN A BASE64 Y GUARDAR EN BD (igual que productos)
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            
            // Validar tamaño
            if ($imagen->getSize() > 1048576) { // 1MB en bytes
                return back()->withInput()->withErrors([
                    'imagen' => 'La imagen no debe superar 1MB'
                ]);
            }
            
            // Convertir a Base64
            $base64 = base64_encode(file_get_contents($imagen));
            $mime = $imagen->getMimeType();
            
            // Guardar como Base64 en la BD
            $data['imagen_url'] = "data:$mime;base64,$base64";
            // También puedes guardarlo en otro campo si prefieres
            $data['imagen'] = "data:$mime;base64,$base64";
        }

        // Crear servicio
        Servicio::create($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado correctamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_servicio' => 'required|string|max:255',
            'costo' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        $data = $request->only(['nombre', 'tipo_servicio', 'costo', 'descripcion']);

        // Manejar la subida de la nueva imagen (convertir a Base64)
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            
            // Validar tamaño
            if ($imagen->getSize() > 1048576) { // 1MB en bytes
                return back()->withInput()->withErrors([
                    'imagen' => 'La imagen no debe superar 1MB'
                ]);
            }
            
            // Convertir a Base64
            $base64 = base64_encode(file_get_contents($imagen));
            $mime = $imagen->getMimeType();
            
            // Guardar como Base64 en la BD
            $data['imagen_url'] = "data:$mime;base64,$base64";
            $data['imagen'] = "data:$mime;base64,$base64";
        }

        $servicio->update($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        
        // Con Base64 NO necesitamos eliminar archivos físicos
        // La imagen está dentro de la BD como texto
        
        $servicio->delete();

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado correctamente');
    }
}