<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\Departamento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
}