<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    // Vista para ADMINISTRADOR (CRUD completo)
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    // Vista para CLIENTES (catálogo público)
    public function catalogo()
    {
        $productos = Producto::where('stock', '>', 0)->get();
        return view('productos.catalogo', compact('productos'));
    }

    public function create()
    {
        return view('productos.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Guardar imagen de forma simple
        $imagePath = $request->file('imagen')->store('productos', 'public');

        // Crear producto
        Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'costo' => $request->costo,
            'stock' => $request->stock,
            'imagen' => $imagePath,
        ]);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }
    
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'costo' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'costo' => $request->costo,
            'stock' => $request->stock,
        ];

        // Manejar la carga de nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            
            $imagePath = $request->file('imagen')->store('productos', 'public');
            $data['imagen'] = $imagePath;
        }

        $producto->update($data);

        // CAMBIO IMPORTANTE: Usar la ruta correcta con 'admin.'
        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }
        
        $producto->delete();

        // CAMBIO IMPORTANTE: Usar la ruta correcta con 'admin.'
        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}