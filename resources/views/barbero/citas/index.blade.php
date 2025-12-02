@extends('layouts.barbero')

@section('title', 'Mis Citas - Barbería')

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
                                <i class="fas fa-calendar-alt me-2" style="color: var(--yellow);"></i>
                                Mis Citas
                            </h4>
                            <p class="text-muted mb-0">
                                Gestiona todas tus citas programadas
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-warning fs-6 p-2" style="background-color: var(--yellow) !important;">
                                <i class="fas fa-cut me-1"></i> Total: {{ $estadisticas['total'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <form action="{{ route('barbero.citas.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                @foreach($estados as $key => $value)
                                    <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" 
                                   value="{{ request('fecha') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="busqueda" class="form-label">Buscar</label>
                            <input type="text" name="busqueda" id="busqueda" class="form-control" 
                                   placeholder="Buscar cliente o servicio..." value="{{ request('busqueda') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-search me-1"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card admin-card stats-card h-100">
                <div class="card-body text-center">
                    <div class="h5 mb-1 font-weight-bold">{{ $estadisticas['hoy'] }}</div>
                    <small class="text-muted">Citas Hoy</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card admin-card stats-card warning h-100">
                <div class="card-body text-center">
                    <div class="h5 mb-1 font-weight-bold">{{ $estadisticas['pendientes'] }}</div>
                    <small class="text-muted">Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card admin-card stats-card success h-100">
                <div class="card-body text-center">
                    <div class="h5 mb-1 font-weight-bold">{{ $estadisticas['confirmadas'] }}</div>
                    <small class="text-muted">Confirmadas</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card admin-card stats-card info h-100">
                <div class="card-body text-center">
                    <div class="h5 mb-1 font-weight-bold">{{ $estadisticas['completadas'] }}</div>
                    <small class="text-muted">Completadas</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card admin-card stats-card h-100" style="border-left-color: #dc3545;">
                <div class="card-body text-center">
                    <div class="h5 mb-1 font-weight-bold">{{ $estadisticas['canceladas'] }}</div>
                    <small class="text-muted">Canceladas</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card admin-card stats-card h-100" style="border-left-color: #6f42c1;">
                <div class="card-body text-center">
                    <div class="h5 mb-1 font-weight-bold">{{ $estadisticas['semana'] }}</div>
                    <small class="text-muted">Próxima semana</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de citas -->
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2" style="color: var(--yellow);"></i> 
                        Lista de Citas
                    </h5>
                    <a href="{{ route('barbero.citas.horarios-disponibles') }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-clock me-1"></i> Ver Horarios
                    </a>
                </div>
                <div class="card-body">
                    @if($citas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha y Hora</th>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citas as $cita)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        {{-- CAMBIO: $cita->cliente->name por $cita->user->name --}}
                                        <div class="fw-bold">{{ $cita->user->name ?? 'Cliente' }}</div>
                                        <small class="text-muted">{{ $cita->user->email ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $cita->servicio->nombre ?? 'Servicio' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($cita->estado == 'pendiente')
                                            <span class="badge bg-warning" style="background-color: var(--yellow) !important;">
                                                <i class="fas fa-clock me-1"></i> Pendiente
                                            </span>
                                        @elseif($cita->estado == 'confirmada')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Confirmada
                                            </span>
                                        @elseif($cita->estado == 'completada')
                                            <span class="badge bg-info">
                                                <i class="fas fa-check-double me-1"></i> Completada
                                            </span>
                                        @elseif($cita->estado == 'cancelada')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i> Cancelada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ $cita->estado }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- CAMBIO: $cita->precio_total por $cita->total --}}
                                        <strong>${{ number_format($cita->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('barbero.citas.show', $cita->id) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning dropdown-toggle dropdown-toggle-split" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Acciones</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                                       data-bs-target="#modalEstado{{ $cita->id }}">
                                                        <i class="fas fa-sync-alt me-2"></i> Cambiar Estado
                                                    </a>
                                                </li>
                                                @if($cita->estado == 'pendiente')
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-phone me-2"></i> Llamar Cliente
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                        
                                        <!-- Modal para cambiar estado -->
                                        <div class="modal fade" id="modalEstado{{ $cita->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('barbero.citas.actualizar-estado', $cita->id) }}" method="POST">
                                                        @csrf
                                                        @method('POST')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Cambiar Estado de Cita</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="estado" class="form-label">Nuevo Estado</label>
                                                                <select name="estado" id="estado" class="form-select" required>
                                                                    @foreach($estados as $key => $value)
                                                                        <option value="{{ $key }}" {{ $cita->estado == $key ? 'selected' : '' }}>
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="comentarios" class="form-label">Comentarios (opcional)</label>
                                                                <textarea name="comentarios" id="comentarios" class="form-control" 
                                                                          rows="3" placeholder="Agregar comentarios...">{{ $cita->notas }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-warning">Actualizar Estado</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $citas->links() }}
                    </div>
                    
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay citas encontradas</h5>
                        <p class="text-muted mb-3">No se encontraron citas con los filtros aplicados</p>
                        <a href="{{ route('barbero.citas.index') }}" class="btn btn-warning">
                            <i class="fas fa-undo me-1"></i> Limpiar Filtros
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-enfocar campo de búsqueda
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('busqueda');
        if (searchInput) {
            searchInput.focus();
        }
    });
</script>
@endpush