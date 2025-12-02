@extends('layouts.barbero')

@section('title', 'Horarios Disponibles - Barbería')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-clock me-2" style="color: var(--yellow);"></i>
                                Mis Horarios Disponibles
                            </h4>
                            <p class="text-muted mb-0">
                                Gestiona y visualiza tus horarios de trabajo
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning fs-6 p-2" style="background-color: var(--yellow) !important;">
                                <i class="fas fa-user-clock me-1"></i> {{ $barbero->nombre }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro de fecha -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <form action="{{ route('barbero.citas.horarios-disponibles') }}" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label for="fecha" class="form-label">Seleccionar Fecha</label>
                            <div class="input-group">
                                <input type="date" name="fecha" id="fecha" class="form-control" 
                                       value="{{ $fecha->format('Y-m-d') }}">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-search me-1"></i> Ver Horarios
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('barbero.citas.horarios-disponibles', ['fecha' => $fecha->copy()->subDay()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-warning">
                                    <i class="fas fa-chevron-left me-1"></i> Ayer
                                </a>
                                <a href="{{ route('barbero.citas.horarios-disponibles', ['fecha' => now()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-warning">
                                    Hoy
                                </a>
                                <a href="{{ route('barbero.citas.horarios-disponibles', ['fecha' => $fecha->copy()->addDay()->format('Y-m-d')]) }}" 
                                   class="btn btn-outline-warning">
                                    Mañana <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del barbero -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card admin-card h-100">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-user-tie me-2" style="color: var(--yellow);"></i> Mi Horario
                    </h6>
                    <div class="text-center py-3">
                        <div class="display-6 fw-bold mb-2">{{ $barbero->hora_inicio }} - {{ $barbero->hora_fin }}</div>
                        <small class="text-muted">Horario de trabajo</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card admin-card h-100">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-stopwatch me-2" style="color: var(--yellow);"></i> Duración por Cita
                    </h6>
                    <div class="text-center py-3">
                        <div class="display-6 fw-bold mb-2">{{ $barbero->duracion_cita }} min</div>
                        <small class="text-muted">Duración promedio por servicio</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card admin-card h-100">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-calendar-day me-2" style="color: var(--yellow);"></i> Citas del Día
                    </h6>
                    <div class="text-center py-3">
                        <div class="display-6 fw-bold mb-2">{{ $citasDelDia->count() }}</div>
                        <small class="text-muted">{{ $fecha->format('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Horarios del día -->
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: var(--yellow);"></i> 
                        Agenda del {{ $fecha->format('d/m/Y') }}
                    </h5>
                    <div class="text-muted">
                        {{ $fecha->translatedFormat('l') }}
                    </div>
                </div>
                <div class="card-body">
                    @if($citasDelDia->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Duración</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasDelDia as $cita)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $cita->cliente->name ?? 'Cliente' }}</div>
                                        <small class="text-muted">{{ $cita->cliente->telefono ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $cita->servicio->nombre ?? 'Servicio' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($cita->estado == 'pendiente')
                                            <span class="badge bg-warning" style="background-color: var(--yellow) !important;">
                                                Pendiente
                                            </span>
                                        @elseif($cita->estado == 'confirmada')
                                            <span class="badge bg-success">
                                                Confirmada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $cita->estado }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $cita->servicio->duracion ?? 30 }} min
                                    </td>
                                    <td>
                                        <a href="{{ route('barbero.citas.show', $cita->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
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
                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay citas programadas para hoy</h5>
                        <p class="text-muted mb-4">¡Es un buen día para organizar tu agenda!</p>
                        
                        <!-- Horarios disponibles teóricos -->
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Horarios disponibles teóricos</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($horarios as $hora)
                                            <div class="col-md-3 col-6 mb-2">
                                                <span class="badge bg-success w-100 py-2">{{ $hora }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos días -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-week me-2" style="color: var(--yellow);"></i> Próximos Días
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        @for($i = 1; $i <= 7; $i++)
                            @php
                                $dia = $fecha->copy()->addDays($i);
                                $citasDia = $citasDelDia->where('fecha_hora', '>=', $dia->copy()->startOfDay())
                                                        ->where('fecha_hora', '<=', $dia->copy()->endOfDay());
                                $count = $citasDia->count();
                            @endphp
                            <div class="col">
                                <a href="{{ route('barbero.citas.horarios-disponibles', ['fecha' => $dia->format('Y-m-d')]) }}" 
                                   class="text-decoration-none">
                                    <div class="card border-0 bg-light mb-2">
                                        <div class="card-body p-2">
                                            <small class="d-block fw-bold">{{ $dia->translatedFormat('D') }}</small>
                                            <h5 class="mb-0">{{ $dia->format('d') }}</h5>
                                            <small class="badge {{ $count > 0 ? 'bg-warning' : 'bg-secondary' }} mt-1">
                                                {{ $count }} citas
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection