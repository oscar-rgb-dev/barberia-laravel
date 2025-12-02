<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\Jornada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    // PARA USUARIOS NORMALES - solo sus citas
    public function index()
    {
        // Solo las citas del usuario autenticado
        $citas = Cita::with(['user', 'barbero', 'servicio'])
                    ->where('user_id', Auth::id()) // ← FILTRO POR USUARIO
                    ->orderBy('fecha_hora', 'desc')
                    ->get();
        
        return view('citas.mis-citas', compact('citas')); // ← VISTA DIFERENTE
    }

    // PARA ADMINISTRADORES - todas las citas
    public function indexAdmin()
    {
        // Todas las citas para admin
        $citas = Cita::with(['servicio', 'barbero', 'user'])
                    ->orderBy('fecha_hora', 'desc')
                    ->get();
        
        return view('citas.index', compact('citas')); // ← VISTA ADMIN
    }

    public function create()
    {
        $servicios = Servicio::all();
        $empleados = Empleado::with('jornada')->get();
        $productos = Producto::where('stock', '>', 0)->get();

        return view('citas.create', compact('servicios', 'empleados', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_servicio' => 'required|exists:servicios,id',
            'id_barbero' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
            'productos' => 'nullable|array',
            'productos.*.id' => 'exists:productos,id',
            'productos.*.cantidad' => 'integer|min:1'
        ]);

        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Calcular total
        $servicio = Servicio::find($request->id_servicio);
        $total = $servicio->costo;

        // Crear la cita
        $cita = Cita::create([
            'user_id' => Auth::id(),
            'id_servicio' => $request->id_servicio,
            'id_barbero' => $request->id_barbero,
            'fecha_hora' => $fechaHora,
            'estado' => 'pendiente',
            'notas' => $request->notas,
            'total' => $total
        ]);

        // Agregar productos si existen
        if ($request->has('productos')) {
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['id']);
                
                // Agregar producto a la cita con cantidad
                $cita->productos()->attach($productoData['id'], [
                    'cantidad' => $productoData['cantidad']
                ]);

                // Actualizar total
                $total += $producto->costo * $productoData['cantidad'];
                
                // Reducir stock del producto
                $producto->decrement('stock', $productoData['cantidad']);
            }

            // Actualizar el total final
            $cita->update(['total' => $total]);
        }

        return redirect()->route('citas.index')->with('success', 'Cita agendada correctamente.');
    }

    public function edit($id)
    {
        $cita = Cita::with(['productos'])->findOrFail($id);
        $servicios = Servicio::all();
        $empleados = Empleado::all();
        $productos = Producto::where('stock', '>', 0)->get();

        return view('citas.edit', compact('cita', 'servicios', 'empleados', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::with(['productos'])->findOrFail($id);
        
        $request->validate([
            'id_servicio' => 'required|exists:servicios,id',
            'id_barbero' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'notas' => 'nullable|string',
            'estado' => 'sometimes|required|in:pendiente,confirmada,completada,cancelada,no_asistio',
            'productos' => 'nullable|array',
            'productos.*.id' => 'exists:productos,id',
            'productos.*.cantidad' => 'integer|min:1'
        ]);

        $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

        // Calcular total
        $servicio = Servicio::find($request->id_servicio);
        $total = $servicio->costo;

        // **RESTAURAR STOCK DE PRODUCTOS ANTERIORES**
        foreach ($cita->productos as $producto) {
            $cantidadAnterior = $producto->pivot->cantidad;
            $producto->increment('stock', $cantidadAnterior);
        }

        // Actualizar datos básicos de la cita
        $cita->update([
            'id_servicio' => $request->id_servicio,
            'id_barbero' => $request->id_barbero,
            'fecha_hora' => $fechaHora,
            'notas' => $request->notas,
            'estado' => $request->estado ?? $cita->estado,
            'total' => $total
        ]);

        // Sincronizar productos y actualizar stock
        $productosData = [];
        if ($request->has('productos')) {
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['id']);
                $productosData[$productoData['id']] = ['cantidad' => $productoData['cantidad']];
                $total += $producto->costo * $productoData['cantidad'];
                
                // **ACTUALIZAR STOCK CON NUEVAS CANTIDADES**
                $producto->decrement('stock', $productoData['cantidad']);
            }
        }

        $cita->productos()->sync($productosData);
        $cita->update(['total' => $total]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function detalle($id)
    {
        try {
            $cita = Cita::with(['servicio', 'barbero', 'productos', 'user'])
                        ->findOrFail($id);

            // Verificar permisos
            if (auth()->user()->id !== $cita->user_id && !auth()->user()->isAdmin()) {
                return response()->json(['error' => 'No tienes permiso para ver esta cita'], 403);
            }

            return response()->json([
                'id' => $cita->id,
                'estado' => $cita->estado,
                'fecha_hora' => $cita->fecha_hora,
                'notas' => $cita->notas,
                'total' => $cita->total,
                'servicio' => [
                    'nombre' => $cita->servicio->nombre,
                    'costo' => $cita->servicio->costo
                ],
                'barbero' => [
                    'nombre' => $cita->barbero->nombre,
                    'especialidad' => $cita->barbero->especialidad
                ],
                'productos' => $cita->productos->map(function($producto) {
                    return [
                        'nombre' => $producto->nombre,
                        'costo' => $producto->costo,
                        'pivot' => [
                            'cantidad' => $producto->pivot->cantidad
                        ]
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al cargar detalle de cita: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar los detalles'], 500);
        }
    }

    public function infoJornada(Request $request)
    {
        $request->validate([
            'barbero_id' => 'required|exists:empleados,id'
        ]);

        $barbero = Empleado::with('jornada')->find($request->barbero_id);

        \Log::info('Consultando jornada para barbero:', [
            'barbero_id' => $barbero->id,
            'barbero_nombre' => $barbero->nombre,
            'id_jornada' => $barbero->id_jornada,
            'tiene_jornada' => !is_null($barbero->jornada),
            'horario_jornada' => $barbero->jornada ? $barbero->jornada->horario : 'NO TIENE'
        ]);

        if (!$barbero->jornada) {
            return response()->json([
                'success' => false,
                'message' => 'El barbero no tiene jornada configurada'
            ]);
        }

        // Parsear el horario (formato: "14:30 - 20:30")
        $horario = $barbero->jornada->horario;
        $partesHorario = explode(' - ', $horario);
        
        if (count($partesHorario) !== 2) {
            \Log::error('Formato de horario inválido:', ['horario' => $horario]);
            return response()->json([
                'success' => false,
                'message' => 'Formato de horario inválido en la jornada'
            ]);
        }

        $horaInicio = trim($partesHorario[0]);
        $horaFin = trim($partesHorario[1]);

        // Asegurar formato de 24 horas (agregar :00 si falta)
        if (strlen($horaInicio) === 4) $horaInicio .= ':00';
        if (strlen($horaFin) === 4) $horaFin .= ':00';

        return response()->json([
            'success' => true,
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'duracion_cita' => 30, // Valor por defecto
            'horario_original' => $horario
        ]);
    }

    public function horariosDisponibles(Request $request)
    {
        $request->validate([
            'barbero_id' => 'required|exists:empleados,id',
            'fecha' => 'required|date'
        ]);

        $barbero = Empleado::with('jornada')->find($request->barbero_id);
        $fecha = $request->fecha;

        \Log::info('Consultando horarios para barbero:', [
            'barbero_id' => $barbero->id,
            'barbero_nombre' => $barbero->nombre,
            'id_jornada' => $barbero->id_jornada,
            'fecha' => $fecha
        ]);

        if (!$barbero->jornada) {
            return response()->json([
                'success' => false,
                'message' => 'El barbero no tiene jornada configurada'
            ]);
        }

        // Parsear el horario
        $horario = $barbero->jornada->horario;
        $partesHorario = explode(' - ', $horario);
        
        if (count($partesHorario) !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'Formato de horario inválido en la jornada'
            ]);
        }

        $horaInicio = trim($partesHorario[0]);
        $horaFin = trim($partesHorario[1]);

        // Asegurar formato de 24 horas
        if (strlen($horaInicio) === 4) $horaInicio .= ':00';
        if (strlen($horaFin) === 4) $horaFin .= ':00';

        // Por ahora, asumimos que todos los días trabaja
        // (ya que no tenemos campos de días en la tabla jornadas)

        // Generar horarios disponibles
        $horariosDisponibles = $this->generarHorariosDisponibles($barbero, $fecha, $horaInicio, $horaFin);

        return response()->json([
            'success' => true,
            'horarios' => $horariosDisponibles,
            'info' => "Horario: {$horario}",
            'message' => count($horariosDisponibles) . ' horarios disponibles'
        ]);
    }

    private function generarHorariosDisponibles($barbero, $fecha, $horaInicio, $horaFin)
    {
        $horarios = [];
        
        $duracion = 30 * 60; // 30 minutos en segundos

        // Convertir horas a timestamp
        $horaInicioTs = strtotime($horaInicio);
        $horaFinTs = strtotime($horaFin);

        \Log::info('Generando horarios:', [
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'hora_inicio_ts' => $horaInicioTs,
            'hora_fin_ts' => $horaFinTs,
            'duracion' => $duracion
        ]);

        // Obtener citas existentes para este barbero en esta fecha
        $citasExistentes = Cita::where('id_barbero', $barbero->id)
            ->whereDate('fecha_hora', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->get()
            ->map(function($cita) {
                return strtotime($cita->fecha_hora);
            })
            ->toArray();

        \Log::info('Citas existentes:', [
            'total_citas' => count($citasExistentes),
            'citas' => $citasExistentes
        ]);

        // Generar horarios cada 30 minutos
        for ($hora = $horaInicioTs; $hora < $horaFinTs; $hora += $duracion) {
            // Verificar si el horario no está ocupado
            $ocupado = false;
            foreach ($citasExistentes as $citaHora) {
                // Considerar que una cita ocupa su hora + duración
                if ($hora >= $citaHora && $hora < ($citaHora + $duracion)) {
                    $ocupado = true;
                    break;
                }
            }

            if (!$ocupado) {
                $horarios[] = date('H:i', $hora);
            }
        }

        \Log::info('Horarios disponibles generados:', [
            'total_horarios' => count($horarios),
            'horarios' => $horarios
        ]);

        return $horarios;
    }
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente.');
    }
}