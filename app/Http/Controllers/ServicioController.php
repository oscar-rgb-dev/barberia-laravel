<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ServicioController extends Controller
{
    /**
     * Asegurar que las carpetas existan
     */
    public function __construct()
    {
        // Crear carpeta si no existe
        $carpetaServicios = public_path('images/servicios');
        if (!File::exists($carpetaServicios)) {
            File::makeDirectory($carpetaServicios, 0755, true);
        }
    }

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
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            
            // USAR /tmp EN LUGAR DE /app/public
            $rutaTemporal = '/tmp/images/servicios/';
            
            // Crear carpeta si no existe
            if (!file_exists($rutaTemporal)) {
                mkdir($rutaTemporal, 0755, true);
            }
            
            // Mover a /tmp
            $imagen->move($rutaTemporal, $nombreImagen);
            
            // Guardar en BD como ruta temporal
            $data['imagen_url'] = '/tmp/images/servicios/' . $nombreImagen;
            
            // OPCIÓN: Guardar como base64 desde /tmp
            $data['imagen_base64'] = base64_encode(file_get_contents($rutaTemporal . $nombreImagen));
        }

        Servicio::create($data);
        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado correctamente');
    }
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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Manejar la subida de la nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($servicio->imagen_url && file_exists(public_path($servicio->imagen_url))) {
                unlink(public_path($servicio->imagen_url));
            }
            
            // Guardar nueva imagen
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('images/servicios'), $nombreImagen);
            $data['imagen_url'] = 'images/servicios/' . $nombreImagen;
        }

        $servicio->update($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($servicio->imagen_url && file_exists(public_path($servicio->imagen_url))) {
            unlink(public_path($servicio->imagen_url));
        }
        
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente');
    }
}