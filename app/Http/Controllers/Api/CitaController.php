<?php
// app/Http\Controllers\Api\CitaController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitaController extends Controller
{
    /**
     * Obtener citas del usuario
     */
    public function index(Request $request)
    {
        $citas = Cita::where('user_id', $request->user()->id)
                    ->with('servicio')
                    ->orderBy('fecha', 'desc')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $citas,
            'message' => 'Citas obtenidas exitosamente'
        ]);
    }

    /**
     * Crear nueva cita
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'notas' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar si ya existe una cita en ese horario
        $citaExistente = Cita::where('fecha', $request->fecha)
                            ->where('hora', $request->hora)
                            ->where('estado', '!=', 'cancelada')
                            ->first();

        if ($citaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Este horario ya está reservado. Por favor, elige otro.'
            ], 409);
        }

        $servicio = Servicio::find($request->servicio_id);

        $cita = Cita::create([
            'user_id' => $request->user()->id,
            'servicio_id' => $request->servicio_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'costo' => $servicio->costo,
            'estado' => 'pendiente',
            'notas' => $request->notas,
        ]);

        return response()->json([
            'success' => true,
            'data' => $cita->load('servicio'),
            'message' => 'Cita creada exitosamente'
        ], 201);
    }

    /**
     * Mostrar una cita específica
     */
    public function show(Request $request, $id)
    {
        $cita = Cita::where('user_id', $request->user()->id)
                    ->where('id', $id)
                    ->with('servicio')
                    ->first();

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cita,
            'message' => 'Cita obtenida exitosamente'
        ]);
    }

    /**
     * Actualizar una cita
     */
    public function update(Request $request, $id)
    {
        $cita = Cita::where('user_id', $request->user()->id)
                    ->where('id', $id)
                    ->first();

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        // Solo permitir modificar citas pendientes
        if ($cita->estado != 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden modificar citas pendientes'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'fecha' => 'sometimes|required|date',
            'hora' => 'sometimes|required|date_format:H:i',
            'notas' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $cita->update($request->only(['fecha', 'hora', 'notas']));

        return response()->json([
            'success' => true,
            'data' => $cita->load('servicio'),
            'message' => 'Cita actualizada exitosamente'
        ]);
    }

    /**
     * Cancelar una cita
     */
    public function destroy(Request $request, $id)
    {
        $cita = Cita::where('user_id', $request->user()->id)
                    ->where('id', $id)
                    ->first();

        if (!$cita) {
            return response()->json([
                'success' => false,
                'message' => 'Cita no encontrada'
            ], 404);
        }

        // Cambiar estado a cancelada en lugar de eliminar
        $cita->estado = 'cancelada';
        $cita->save();

        return response()->json([
            'success' => true,
            'message' => 'Cita cancelada exitosamente'
        ]);
    }
}