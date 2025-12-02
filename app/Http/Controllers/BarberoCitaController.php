<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BarberoCitaController extends Controller
{
    /**
     * Mostrar todas las citas del barbero
     */
    public function index(Request $request)
    {
        // Obtener el barbero actual por su email
        $barbero = Empleado::where('email', Auth::user()->email)->firstOrFail();
        
        // Construir la consulta - USAR 'user' en lugar de 'cliente'
        $query = Cita::with(['user', 'servicio']) // Cambiado: 'user' en lugar de 'cliente'
            ->where('id_barbero', $barbero->id);
        
        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }
        
        if ($request->filled('busqueda')) {
            $search = $request->busqueda;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) { // Cambiado: 'user' en lugar de 'cliente'
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('servicio', function($q2) use ($search) {
                    $q2->where('nombre', 'like', "%{$search}%");
                });
            });
        }
        
        // Ordenar por fecha más reciente
        $citas = $query->orderBy('fecha_hora', 'desc')->paginate(15);
        
        // Estadísticas usando id_barbero
        $baseQuery = Cita::where('id_barbero', $barbero->id);
        
        $estadisticas = [
            'total' => $baseQuery->count(),
            'hoy' => (clone $baseQuery)->whereDate('fecha_hora', today())->count(),
            'pendientes' => (clone $baseQuery)->where('estado', 'pendiente')->count(),
            'confirmadas' => (clone $baseQuery)->where('estado', 'confirmada')->count(),
            'completadas' => (clone $baseQuery)->where('estado', 'completada')->count(),
            'canceladas' => (clone $baseQuery)->where('estado', 'cancelada')->count(),
            'semana' => (clone $baseQuery)->whereBetween('fecha_hora', [now(), now()->addDays(7)])->count(),
        ];
        
        // Estados disponibles para filtro
        $estados = [
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
            'no_show' => 'No Show',
        ];
        
        return view('barbero.citas.index', compact('citas', 'barbero', 'estadisticas', 'estados'));
    }
    
    /**
     * Mostrar detalles de una cita específica
     */
    public function show(Cita $cita)
    {
        // Verificar que la cita pertenezca al barbero actual
        $barbero = Empleado::where('email', Auth::user()->email)->firstOrFail();
        
        if ($cita->id_barbero !== $barbero->id) {
            abort(403, 'No tienes permiso para ver esta cita');
        }
        
        // Cargar relaciones - USAR 'user' en lugar de 'cliente'
        $cita->load(['user', 'servicio', 'productos', 'barbero']);
        
        return view('barbero.citas.show', compact('cita'));
    }
    
    /**
     * Mostrar horarios disponibles del barbero
     */
    public function horariosDisponibles(Request $request)
    {
        $barbero = Empleado::where('email', Auth::user()->email)->firstOrFail();
        
        // Obtener la fecha solicitada o usar hoy
        $fecha = $request->filled('fecha') 
            ? Carbon::parse($request->fecha)
            : Carbon::today();
        
        // Mostrar las citas del día usando id_barbero
        $citasDelDia = Cita::where('id_barbero', $barbero->id)
            ->whereDate('fecha_hora', $fecha)
            ->with(['user', 'servicio']) // Cambiado: 'user' en lugar de 'cliente'
            ->orderBy('fecha_hora', 'asc')
            ->get();
        
        // Generar horarios teóricos (ejemplo: de 9am a 7pm)
        $horarios = [];
        $horaInicio = Carbon::parse('09:00');
        $horaFin = Carbon::parse('19:00');
        $duracionCita = $barbero->duracion_cita ?? 30; // minutos
        
        for ($hora = $horaInicio->copy(); $hora < $horaFin; $hora->addMinutes($duracionCita)) {
            $horarios[] = $hora->format('H:i');
        }
        
        return view('barbero.citas.horarios', compact('barbero', 'fecha', 'citasDelDia', 'horarios'));
    }
    
    /**
     * Actualizar el estado de una cita
     */
    public function actualizarEstado(Request $request, Cita $cita)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada',
            'comentarios' => 'nullable|string|max:500',
        ]);
        
        $barbero = Empleado::where('email', Auth::user()->email)->firstOrFail();
        
        if ($cita->id_barbero !== $barbero->id) {
            abort(403, 'No tienes permiso para actualizar esta cita');
        }
        
        $cita->update([
            'estado' => $request->estado,
            'notas' => $request->comentarios ?? $cita->notas,
        ]);
        
        return back()->with('success', 'Estado de la cita actualizado correctamente');
    }
    
    /**
     * Ver clientes del barbero
     */
    public function clientes()
    {
        $barbero = Empleado::where('email', Auth::user()->email)->firstOrFail();
        
        // Obtener usuarios que han tenido citas con este barbero usando id_barbero
        // NOTA: Aquí usamos User::class en lugar de Cliente::class
        $clientes = User::whereHas('citas', function ($query) use ($barbero) {
            $query->where('id_barbero', $barbero->id);
        })
        ->withCount(['citas' => function ($query) use ($barbero) {
            $query->where('id_barbero', $barbero->id);
        }])
        ->with(['citas' => function ($query) use ($barbero) {
            $query->where('id_barbero', $barbero->id)
                  ->orderBy('fecha_hora', 'desc')
                  ->take(3);
        }])
        ->orderBy('name')
        ->paginate(20);
        
        return view('barbero.clientes.index', compact('barbero', 'clientes'));
    }
    
    /**
     * Ver reportes del barbero
     */
    public function reportes(Request $request)
    {
        $barbero = Empleado::where('email', Auth::user()->email)->firstOrFail();
        
        // Fechas por defecto: último mes
        $fechaInicio = $request->filled('fecha_inicio') 
            ? Carbon::parse($request->fecha_inicio)
            : Carbon::now()->subMonth();
            
        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)
            : Carbon::now();
        
        // Obtener citas en el rango de fechas usando id_barbero
        $citas = Cita::where('id_barbero', $barbero->id)
            ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
            ->with(['servicio', 'productos', 'user']) // Cambiado: agregar 'user'
            ->orderBy('fecha_hora', 'desc')
            ->get();
        
        // Calcular estadísticas
        $estadisticas = [
            'total_citas' => $citas->count(),
            'citas_completadas' => $citas->where('estado', 'completada')->count(),
            'citas_canceladas' => $citas->where('estado', 'cancelada')->count(),
            'ingresos_servicios' => $citas->where('estado', 'completada')->sum('total'),
            'ingresos_productos' => 0, // Ajusta según tu lógica
            'servicio_popular' => $citas->groupBy('id_servicio')->map->count()->sortDesc()->keys()->first(),
        ];
        
        // Gráfico por días
        $citasPorDia = $citas->groupBy(function ($cita) {
            return Carbon::parse($cita->fecha_hora)->format('Y-m-d');
        })->map->count();
        
        return view('barbero.reportes.index', compact('barbero', 'citas', 'estadisticas', 'citasPorDia', 'fechaInicio', 'fechaFin'));
    }
}