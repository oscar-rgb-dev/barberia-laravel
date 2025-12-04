@extends('layouts.admin')

@section('title', 'Dashboard - Barbería')

@section('content')
<div class="container-fluid">
    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card admin-card stats-card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Citas Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $citasHoy }}</div>
                            <div class="mt-2">
                                <span class="text-sm">
                                    <i class="fas fa-calendar-day"></i> {{ $citasPendientes }} pendientes
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card admin-card stats-card warning h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Empleados Activos
                            </div>
                            <div class="mt-2">
                                <span class="text-sm">
                                    <i class="fas fa-users"></i> Total: {{ $totalEmpleados }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card admin-card stats-card success h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Servicios
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalServicios }}</div>
                            <div class="mt-2">
                                <span class="text-sm">
                                    <i class="fas fa-list"></i> Disponibles
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-concierge-bell fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card admin-card stats-card info h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Ingresos Mensuales
                            </div>
                            <div class="h5 mb-0 font-weight-bold">${{ number_format($ingresosMensuales, 0) }}</div>
                            <div class="mt-2">
                                <div class="text-sm">
                                    <small class="d-block">
                                        <i class="fas fa-scissors"></i> Servicios: ${{ number_format($ingresosServicios, 0) }}
                                    </small>
                                    <small class="d-block">
                                        <i class="fas fa-box"></i> Productos: ${{ number_format($ingresosProductos, 0) }}
                                    </small>
                                    @if($totalProductosVendidos > 0)
                                    <small class="d-block">
                                        <i class="fas fa-shopping-cart"></i> {{ $totalProductosVendidos }} productos vendidos
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Citas Recientes -->
        <div class="col-lg-8 mb-4">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Citas Recientes</h5>
                    <a href="{{ route('citas.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Barbero</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($citasRecientes as $cita)
                                <tr>
                                    <td>{{ $cita->user->name }}</td>
                                    <td>
                                        {{ $cita->servicio->nombre }}
                                        @if($cita->productos->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                +{{ $cita->productos->count() }} producto(s)
                                            </small>
                                        @endif
                                    </td>
                                    <td>{{ $cita->barbero->nombre }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $cita->estado == 'pendiente' ? 'warning' : ($cita->estado == 'confirmada' ? 'success' : ($cita->estado == 'completada' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($cita->total, 2) }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        No hay citas recientes
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Adicionales y Acciones Rápidas -->
        <div class="col-lg-4 mb-4">
            <!-- Estadísticas de Ventas -->
            <div class="card admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Resumen de Ventas</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Servicios:</span>
                            <strong class="text-success">${{ number_format($ingresosServicios, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Productos:</span>
                            <strong class="text-info">${{ number_format($ingresosProductos, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-sm"><strong>Total:</strong></span>
                            <strong class="text-primary">${{ number_format($ingresosMensuales, 2) }}</strong>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="mb-0">Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('citas.create') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-plus-circle"></i> Nueva Cita
                        </a>
                        <a href="{{ route('admin.empleados.create') }}" class="btn btn-outline-dark">
                            <i class="fas fa-user-plus"></i> Agregar Empleado
                        </a>
                        <a href="{{ route('servicios.create') }}" class="btn btn-outline-dark">
                            <i class="fas fa-plus"></i> Nuevo Servicio
                        </a>
                        <a href="{{ route('admin.departamentos.create') }}" class="btn btn-outline-dark">
                            <i class="fas fa-building"></i> Nuevo Departamento
                        </a>
                        <!-- Después de las otras acciones rápidas -->
                        <a href="{{ route('admin.reportes.form') }}" class="btn btn-outline-danger">
                            <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                        </a>
                        <a href="{{ route('admin.reportes.satisfaccion.form') }}" class="btn btn-outline-warning">
                            <i class="fas fa-chart-bar"></i> Reporte de Satisfacción
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection