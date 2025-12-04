<?php
// app/Http\Controllers\Api\ProductoController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Obtener todos los productos
     */
    public function index()
    {
        $productos = Producto::where('stock', '>', 0)->get();

        return response()->json([
            'success' => true,
            'data' => $productos,
            'message' => 'Productos obtenidos exitosamente'
        ]);
    }

    /**
     * Obtener un producto específico
     */
    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $producto,
            'message' => 'Producto obtenido exitosamente'
        ]);
    }
}