<?php

namespace App\Http\Controllers\Barbero;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Buscar información del empleado
        $empleado = Empleado::where('email', $user->email)->first();
        
        if (!$empleado) {
            // Si no encuentra empleado, mostrar datos básicos
            return view('barbero.dashboard', [
                'empleado' => null,
                'citasHoy' => 0,
                'citasCompletadas' => 0,
                'citasPendientes' => 0,
                'citasProximas' => 0,
                'proximasCitasHoy' => collect(),
                'citasSemana' => 0,
            ]);
        }
        
        try {
            // Obtener estadísticas de citas - USAR fecha_hora
            $citasHoy = Cita::whereDate('fecha_hora', Carbon::today()) // CAMBIADO: fecha_hora
                ->where('id_barbero', $empleado->id)
                ->whereIn('estado', ['confirmada', 'pendiente'])
                ->count();
                
            $citasCompletadas = Cita::where('id_barbero', $empleado->id)
                ->where('estado', 'completada')
                ->count();
                
            $citasPendientes = Cita::where('id_barbero', $empleado->id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->count();
                
            $citasProximas = Cita::where('id_barbero', $empleado->id)
                ->whereDate('fecha_hora', '>=', Carbon::today()) // CAMBIADO: fecha_hora
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->count();
                
            // Próximas citas para hoy
            $proximasCitasHoy = Cita::whereDate('fecha_hora', Carbon::today()) // CAMBIADO: fecha_hora
                ->where('id_barbero', $empleado->id)
                ->whereIn('estado', ['confirmada', 'pendiente'])
                ->with(['servicio', 'cliente'])
                ->orderBy('fecha_hora') // CAMBIADO: fecha_hora
                ->take(5)
                ->get();
                
            // Citas para la semana
            $citasSemana = Cita::whereBetween('fecha_hora', // CAMBIADO: fecha_hora
                    [Carbon::today(), Carbon::today()->addDays(7)])
                ->where('id_barbero', $empleado->id)
                ->whereIn('estado', ['confirmada', 'pendiente'])
                ->count();
                
            return view('barbero.dashboard', compact(
                'empleado',
                'citasHoy',
                'citasCompletadas',
                'citasPendientes',
                'citasProximas',
                'proximasCitasHoy',
                'citasSemana'
            ));
            
        } catch (\Exception $e) {
            // Si hay error, mostrar datos vacíos
            \Log::error('Error en DashboardController: ' . $e->getMessage());
            
            return view('barbero.dashboard', [
                'empleado' => $empleado,
                'citasHoy' => 0,
                'citasCompletadas' => 0,
                'citasPendientes' => 0,
                'citasProximas' => 0,
                'proximasCitasHoy' => collect(),
                'citasSemana' => 0,
            ]);
        }
    }
}