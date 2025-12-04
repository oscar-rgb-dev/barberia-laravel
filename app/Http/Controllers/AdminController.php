<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Departamento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Solo permitir acceso a administradores
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder al panel administrativo.');
        }

        $citasHoy = Cita::whereDate('fecha_hora', today())->count();
        $citasPendientes = Cita::where('estado', 'pendiente')->count();
        $totalEmpleados = Empleado::count();
        $totalServicios = Servicio::count();
        
        // Calcular ingresos mensuales (citas completadas este mes + productos vendidos)
        $citasCompletadasMes = Cita::where('estado', 'completada')
            ->whereMonth('fecha_hora', now()->month)
            ->whereYear('fecha_hora', now()->year)
            ->with(['servicio', 'productos'])
            ->get();

        // Ingresos por servicios
        $ingresosServicios = $citasCompletadasMes->sum(function($cita) {
            return $cita->servicio->costo;
        });

        // Ingresos por productos
        $ingresosProductos = $citasCompletadasMes->sum(function($cita) {
            return $cita->productos->sum(function($producto) {
                return $producto->costo * $producto->pivot->cantidad;
            });
        });

        $ingresosMensuales = $ingresosServicios + $ingresosProductos;

        // Estadísticas adicionales de productos vendidos
        $totalProductosVendidos = $citasCompletadasMes->sum(function($cita) {
            return $cita->productos->sum('pivot.cantidad');
        });

        $citasRecientes = Cita::with(['servicio', 'barbero', 'user'])
            ->orderBy('fecha_hora', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'citasHoy',
            'citasPendientes',
            'totalEmpleados',
            'totalServicios',
            'ingresosMensuales',
            'ingresosServicios',
            'ingresosProductos',
            'totalProductosVendidos',
            'citasRecientes'
        ));
    }

    /**
     * Mostrar formulario para generar reporte
     */
    public function mostrarReporteForm()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder al panel administrativo.');
        }

        $barberos = Empleado::all();
        
        return view('admin.reportes.generar', compact('barberos'));
    }

    /**
     * Generar reporte en PDF
     */
    public function generarReportePDF(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder al panel administrativo.');
        }

        // Validar los parámetros
        $request->validate([
            'tipo' => 'required|in:dia,semana,mes,año,personalizado',
            'fecha_inicio' => 'required_if:tipo,personalizado|date',
            'fecha_fin' => 'required_if:tipo,personalizado|date|after_or_equal:fecha_inicio',
            'barbero_id' => 'nullable|exists:empleados,id',
        ]);

        // Determinar las fechas según el tipo
        $fechas = $this->determinarRangoFechas($request);
        $fechaInicio = $fechas['inicio'];
        $fechaFin = $fechas['fin'];

        // Consultar las citas
        $query = Cita::with(['servicio', 'barbero', 'user', 'productos'])
            ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);

        // Filtrar por barbero si se seleccionó
        if ($request->filled('barbero_id')) {
            $query->where('id_barbero', $request->barbero_id);
        }

        $citas = $query->orderBy('fecha_hora', 'desc')->get();
        
        // Obtener estadísticas por barbero
        $estadisticasBarberos = $this->generarEstadisticasBarberos($citas);
        
        // Totales generales
        $totalCitas = $citas->count();
        $totalIngresos = $citas->where('estado', 'completada')->sum('total');
        $citasCompletadas = $citas->where('estado', 'completada')->count();
        $citasCanceladas = $citas->where('estado', 'cancelada')->count();
        
        // Información para el PDF
        $reporteData = [
            'citas' => $citas,
            'estadisticasBarberos' => $estadisticasBarberos,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'tipoReporte' => $request->tipo,
            'barberoSeleccionado' => $request->filled('barbero_id') ? Empleado::find($request->barbero_id) : null,
            'totalCitas' => $totalCitas,
            'totalIngresos' => $totalIngresos,
            'citasCompletadas' => $citasCompletadas,
            'citasCanceladas' => $citasCanceladas,
            'fechaGeneracion' => now(),
        ];

        // Generar el PDF
        $pdf = PDF::loadView('admin.reportes.pdf', $reporteData)
            ->setPaper('a4', 'landscape');

        // Nombre del archivo
        $nombreArchivo = 'reporte_citas_' . Carbon::now()->format('Y_m_d_His') . '.pdf';

        // Descargar el PDF
        return $pdf->download($nombreArchivo);
    }

    /**
     * Determinar el rango de fechas según el tipo seleccionado
     */
    private function determinarRangoFechas(Request $request)
    {
        $now = Carbon::now();

        switch ($request->tipo) {
            case 'dia':
                return [
                    'inicio' => $now->copy()->startOfDay(),
                    'fin' => $now->copy()->endOfDay()
                ];

            case 'semana':
                return [
                    'inicio' => $now->copy()->startOfWeek(),
                    'fin' => $now->copy()->endOfWeek()
                ];

            case 'mes':
                return [
                    'inicio' => $now->copy()->startOfMonth(),
                    'fin' => $now->copy()->endOfMonth()
                ];

            case 'año':
                return [
                    'inicio' => $now->copy()->startOfYear(),
                    'fin' => $now->copy()->endOfYear()
                ];

            case 'personalizado':
                return [
                    'inicio' => Carbon::parse($request->fecha_inicio)->startOfDay(),
                    'fin' => Carbon::parse($request->fecha_fin)->endOfDay()
                ];

            default:
                return [
                    'inicio' => $now->copy()->startOfMonth(),
                    'fin' => $now->copy()->endOfMonth()
                ];
        }
    }

    /**
     * Generar estadísticas por barbero
     */
    private function generarEstadisticasBarberos($citas)
    {
        $barberos = [];
        
        foreach ($citas as $cita) {
            $barberoId = $cita->id_barbero;
            $barberoNombre = $cita->barbero->nombre ?? 'No asignado';
            
            if (!isset($barberos[$barberoId])) {
                $barberos[$barberoId] = [
                    'nombre' => $barberoNombre,
                    'total_citas' => 0,
                    'citas_completadas' => 0,
                    'citas_canceladas' => 0,
                    'citas_pendientes' => 0,
                    'ingresos' => 0,
                ];
            }
            
            $barberos[$barberoId]['total_citas']++;
            
            if ($cita->estado === 'completada') {
                $barberos[$barberoId]['citas_completadas']++;
                $barberos[$barberoId]['ingresos'] += $cita->total;
            } elseif ($cita->estado === 'cancelada') {
                $barberos[$barberoId]['citas_canceladas']++;
            } elseif ($cita->estado === 'pendiente') {
                $barberos[$barberoId]['citas_pendientes']++;
            }
        }
        
        // Ordenar por total de citas (descendente)
        usort($barberos, function($a, $b) {
            return $b['total_citas'] <=> $a['total_citas'];
        });
        
        return $barberos;
    }
}