<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

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
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024', // Reducido a 1MB
        ]);

        $data = $request->all();

        // CONVERTIR IMAGEN A BASE64 Y GUARDAR EN BD
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
            $data['imagen'] = "data:$mime;base64,$base64";
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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'costo' => $request->costo,
            'stock' => $request->stock,
        ];

        // Manejar la carga de nueva imagen
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
            $data['imagen'] = "data:$mime;base64,$base64";
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Con Base64 NO necesitamos eliminar archivos físicos
        // La imagen está dentro de la BD como texto
        
        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}