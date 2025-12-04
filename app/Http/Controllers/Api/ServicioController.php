<?php
// app/Http\Controllers\Api\ServicioController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Obtener todos los servicios
     */
    public function index()
    {
        $servicios = Servicio::all();

        return response()->json([
            'success' => true,
            'data' => $servicios,
            'message' => 'Servicios obtenidos exitosamente'
        ]);
    }

    /**
     * Obtener un servicio específico
     */
    public function show($id)
    {
        $servicio = Servicio::find($id);

        if (!$servicio) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $servicio,
            'message' => 'Servicio obtenido exitosamente'
        ]);
    }
}