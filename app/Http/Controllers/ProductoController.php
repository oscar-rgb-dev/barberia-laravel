<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\File;

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

        $data = $request->all();

        // GUARDAR IMAGEN EN /tmp
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            
            // Ruta en /tmp
            $rutaTemporal = '/tmp/images/productos/';
            
            // Crear carpeta si no existe
            if (!file_exists($rutaTemporal)) {
                mkdir($rutaTemporal, 0755, true);
            }
            
            // Mover a /tmp
            $imagen->move($rutaTemporal, $nombreImagen);
            
            // Guardar ruta completa en BD
            $data['imagen'] = $rutaTemporal . $nombreImagen;
        }

        // Crear producto
        Producto::create($data);

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
            if ($producto->imagen && file_exists($producto->imagen)) {
                unlink($producto->imagen);
            }
            
            // Guardar nueva imagen en /tmp
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $rutaTemporal = '/tmp/images/productos/';
            
            if (!file_exists($rutaTemporal)) {
                mkdir($rutaTemporal, 0755, true);
            }
            
            $imagen->move($rutaTemporal, $nombreImagen);
            $data['imagen'] = $rutaTemporal . $nombreImagen;
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Eliminar imagen si existe
        if ($producto->imagen && file_exists($producto->imagen)) {
            unlink($producto->imagen);
        }
        
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}