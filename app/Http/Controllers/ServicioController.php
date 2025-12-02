<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    /**
     * Vista PÚBLICA - Tienda para clientes
     */
    public function index()
    {
        // Quita el where('activo', true) ya que no existe esa columna
        $servicios = Servicio::all(); // Muestra TODOS los servicios
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
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Cambié a required
        ]);

        $data = $request->all();

        // Manejar la subida de la imagen
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('servicios', 'public');
            $data['imagen_url'] = $imagePath;
        }

        Servicio::create($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado correctamente');
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }


    // ... otros métodos del CRUD ...

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
            if ($servicio->imagen_url) {
                Storage::disk('public')->delete($servicio->imagen_url);
            }
            $imagePath = $request->file('imagen')->store('servicios', 'public');
            $data['imagen_url'] = $imagePath;
        }

        $servicio->update($data);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente');
    }
    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente');
    }
}