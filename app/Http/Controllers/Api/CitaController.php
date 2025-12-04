<?php
// app/Http\Controllers\Api\CitaController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Empleado; // Para barberos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    /**
     * Obtener citas del usuario (COMPATIBLE ANDROID Y WEB)
     */
    public function index(Request $request)
    {
        // Validar que tenga user_id (Android) o user() (Web)
        $user_id = $request->user_id ?? ($request->user() ? $request->user()->id : null);
        
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Se requiere user_id'
            ], 422);
        }

        $citas = Cita::where('user_id', $user_id)
                    ->with(['servicio', 'barbero'])
                    ->orderBy('fecha_hora', 'desc')
                    ->get()
                    ->map(function($cita) {
                        return [
                            'id' => $cita->id,
                            'servicio_id' => $cita->servicio_id,
                            'servicio_nombre' => $cita->servicio ? $cita->servicio->nombre : 'N/A',
                            'barbero_id' => $cita->id_barbero, // CAMBIADO AQUÍ
                            'barbero_nombre' => $cita->barbero ? $cita->barbero->nombre : 'N/A',
                            'fecha_hora' => $cita->fecha_hora,
                            'fecha' => date('Y-m-d', strtotime($cita->fecha_hora)), // Para Android
                            'hora' => date('H:i', strtotime($cita->fecha_hora)), // Para Android
                            'estado' => $cita->estado,
                            'notas' => $cita->notas,
                            'total' => $cita->total,
                            'created_at' => $cita->created_at
                        ];
                    });

        return response()->json([
            'success' => true,
            'data' => $citas,
            'message' => 'Citas obtenidas exitosamente'
        ]);
    }

    /**
     * Crear nueva cita (COMPATIBLE ANDROID Y WEB)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'servicio_id' => 'required|exists:servicios,id',
            'barbero_id' => 'required|exists:empleados,id', // El parámetro sigue siendo barbero_id
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

        // Combinar fecha y hora
        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Verificar disponibilidad - CAMBIADO: barbero_id -> id_barbero
        $citaExistente = Cita::where('fecha_hora', $fechaHora)
                            ->where('id_barbero', $request->barbero_id) // CAMBIADO AQUÍ
                            ->where('estado', '!=', 'cancelada')
                            ->first();

        if ($citaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Este horario ya está reservado para este barbero.'
            ], 409);
        }

        $servicio = Servicio::find($request->servicio_id);

        $cita = Cita::create([
            'user_id' => $request->user_id,
            'servicio_id' => $request->servicio_id,
            'id_barbero' => $request->barbero_id, // CAMBIADO AQUÍ
            'fecha_hora' => $fechaHora,
            'estado' => 'pendiente',
            'notas' => $request->notas,
            'total' => $servicio->costo,
        ]);

        return response()->json([
            'success' => true,
            'data' => $cita,
            'message' => 'Cita creada exitosamente'
        ], 201);
    }
    /**
     * Obtener horarios disponibles (PARA ANDROID)
     */
    public function horariosDisponibles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barbero_id' => 'required|exists:empleados,id',
            'fecha' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $barbero = Empleado::with('jornada')->find($request->barbero_id);
        $fecha = $request->fecha;

        if (!$barbero->jornada) {
            return response()->json([
                'success' => false,
                'message' => 'El barbero no tiene jornada configurada'
            ]);
        }

        // Parsear horario de jornada
        $horario = $barbero->jornada->horario;
        $partesHorario = explode(' - ', $horario);
        
        if (count($partesHorario) !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'Formato de horario inválido'
            ]);
        }

        $horaInicio = trim($partesHorario[0]);
        $horaFin = trim($partesHorario[1]);

        // Asegurar formato
        if (strlen($horaInicio) === 4) $horaInicio .= ':00';
        if (strlen($horaFin) === 4) $horaFin .= ':00';

        // Generar horarios disponibles - CAMBIADO: barbero_id -> id_barbero
        $horariosDisponibles = $this->generarHorariosDisponibles($barbero->id, $fecha, $horaInicio, $horaFin);

        return response()->json([
            'success' => true,
            'data' => [
                'horarios' => $horariosDisponibles,
                'info' => "Horario: {$horario}",
                'barbero_nombre' => $barbero->nombre
            ],
            'message' => count($horariosDisponibles) . ' horarios disponibles'
        ]);
    }

    private function generarHorariosDisponibles($barberoId, $fecha, $horaInicio, $horaFin)
    {
        $horarios = [];
        $duracion = 30 * 60; // 30 minutos en segundos

        $horaInicioTs = strtotime($horaInicio);
        $horaFinTs = strtotime($horaFin);

        // Obtener citas existentes - CAMBIADO: barbero_id -> id_barbero
        $citasExistentes = Cita::where('id_barbero', $barberoId) // CAMBIADO AQUÍ
            ->whereDate('fecha_hora', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get()
            ->map(function($cita) {
                return strtotime($cita->fecha_hora);
            })
            ->toArray();

        // Generar horarios cada 30 minutos
        for ($hora = $horaInicioTs; $hora < $horaFinTs; $hora += $duracion) {
            $ocupado = false;
            
            foreach ($citasExistentes as $citaHora) {
                if ($hora >= $citaHora && $hora < ($citaHora + $duracion)) {
                    $ocupado = true;
                    break;
                }
            }

            if (!$ocupado) {
                $horarios[] = date('H:i', $hora);
            }
        }

        return $horarios;
    }
}