@extends('layouts.barbero')

@section('title', 'Dashboard - Barbería')

@section('content')
<div class="container-fluid">
    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-user-check me-2" style="color: var(--yellow);"></i>
                                ¡Bienvenido, {{ Auth::user()->name }}!
                            </h4>
                            <p class="text-muted mb-0">
                                Panel de control para barberos - {{ now()->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning fs-6 p-2" style="background-color: var(--yellow) !important;">
                                <i class="fas fa-cut me-1"></i> Barbero
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
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
                                    <i class="fas fa-clock"></i> {{ now()->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x" style="color: var(--yellow);"></i>
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
                                Citas Completadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $citasCompletadas }}</div>
                            <div class="mt-2">
                                <span class="text-sm">
                                    <i class="fas fa-check-circle"></i> Total histórico
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
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
                                Citas Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $citasPendientes }}</div>
                            <div class="mt-2">
                                <span class="text-sm">
                                    <i class="fas fa-clock"></i> Por atender
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x" style="color: var(--yellow);"></i>
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
                                Próximos 7 Días
                            </div>
                            <div class="h5 mb-0 font-weight-bold">{{ $citasSemana }}</div>
                            <div class="mt-2">
                                <span class="text-sm">
                                    <i class="fas fa-calendar-week"></i> Esta semana
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del barbero y próximas citas -->
    <div class="row">
        <!-- Información del barbero -->
        <div class="col-lg-4 mb-4">
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2" style="color: var(--yellow);"></i> Mi Información
                    </h5>
                </div>
                <div class="card-body">
                    @if($empleado)
                    <div class="text-center mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; border: 3px solid var(--yellow);">
                            <i class="fas fa-user-tie fa-3x" style="color: var(--yellow);"></i>
                        </div>
                        <h5 class="mt-3 mb-1">{{ $empleado->nombre }}</h5>
                        <p class="text-muted">{{ $empleado->email }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone me-2" style="color: var(--yellow);"></i>
                            <div>
                                <small class="text-muted d-block">Teléfono</small>
                                <strong>{{ $empleado->telefono }}</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-building me-2" style="color: var(--yellow);"></i>
                            <div>
                                <small class="text-muted d-block">Departamento</small>
                                <strong>{{ $empleado->departamento->nombre ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-business-time me-2" style="color: var(--yellow);"></i>
                            <div>
                                <small class="text-muted d-block">Jornada</small>
                                <strong>{{ $empleado->jornada->tipo ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card" style="background-color: rgba(255, 215, 0, 0.1); border: 1px solid var(--yellow);">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-clock me-2" style="color: var(--yellow);"></i> Mi Horario
                            </h6>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block">Inicio</small>
                                    <strong>{{ $empleado->hora_inicio }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Fin</small>
                                    <strong>{{ $empleado->hora_fin }}</strong>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted d-block">Duración cita</small>
                                <strong>{{ $empleado->duracion_cita }} minutos</strong>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No se encontró información completa del empleado.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Próximas citas -->
        <div class="col-lg-8 mb-4">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: var(--yellow);"></i> Citas de Hoy
                    </h5>
                    <a href="{{ route('barbero.citas.index') }}" class="btn btn-sm btn-outline-warning">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($proximasCitasHoy->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proximasCitasHoy as $cita)
                                <tr>
                                    <td>
                                        <i class="fas fa-clock me-1" style="color: var(--yellow);"></i>
                                        {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}
                                    </td>
                                    <td>
                                        <strong>{{ $cita->cliente->name ?? 'Cliente' }}</strong>
                                    </td>
                                    <td>
                                        {{ $cita->servicio->nombre ?? 'Servicio' }}
                                    </td>
                                    <td>
                                        @if($cita->estado == 'confirmada')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Confirmada
                                            </span>
                                        @elseif($cita->estado == 'pendiente')
                                            <span class="badge bg-warning" style="background-color: var(--yellow) !important;">
                                                <i class="fas fa-clock me-1"></i> Pendiente
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $cita->estado }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('barbero.citas.show', $cita->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay citas programadas para hoy</h5>
                        <p class="text-muted mb-0">¡Disfruta de tu día!</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card admin-card text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background-color: var(--yellow);">
                                    <i class="fas fa-calendar-plus fa-2x text-white"></i>
                                </div>
                            </div>
                            <h5 class="card-title">Gestionar Citas</h5>
                            <p class="card-text">Revisa y gestiona todas tus citas programadas.</p>
                            <a href="{{ route('barbero.citas.index') }}" class="btn btn-warning">
                                <i class="fas fa-list me-1"></i> Ver Todas las Citas
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card admin-card text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background-color: #17a2b8;">
                                    <i class="fas fa-clock fa-2x text-white"></i>
                                </div>
                            </div>
                            <h5 class="card-title">Ver Horarios</h5>
                            <p class="card-text">Consulta tus horarios disponibles y agenda.</p>
                            <a href="{{ route('barbero.citas.horarios-disponibles') }}" class="btn btn-info text-white">
                                <i class="fas fa-calendar-check me-1"></i> Ver Horarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario rápido -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar me-2" style="color: var(--yellow);"></i> Calendario de la Semana
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @php
                            $diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
                            $fechaInicio = now()->startOfWeek();
                        @endphp
                        
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $fecha = $fechaInicio->copy()->addDays($i);
                                $esHoy = $fecha->isToday();
                                $citasDia = 0; // Simplificado por ahora
                            @endphp
                            <div class="col">
                                <div class="card border-0 {{ $esHoy ? 'bg-warning text-white' : 'bg-light' }} mb-2" 
                                     style="{{ $esHoy ? 'background-color: var(--yellow) !important; color: var(--black) !important;' : '' }}">
                                    <div class="card-body p-2">
                                        <small class="d-block fw-bold">{{ $diasSemana[$i] }}</small>
                                        <h5 class="mb-0">{{ $fecha->format('d') }}</h5>
                                        <small class="badge {{ $esHoy ? 'bg-white text-warning' : 'bg-warning text-white' }}"
                                               style="{{ !$esHoy ? 'background-color: var(--yellow) !important;' : '' }}">
                                            0 citas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection