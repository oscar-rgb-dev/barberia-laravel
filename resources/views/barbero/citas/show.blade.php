@extends('layouts.barbero')

@section('title', 'Detalles de Cita - Barbería')

@section('content')
<div class="container-fluid">
    <!-- Botón de regreso -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('barbero.citas.index') }}" class="btn btn-outline-warning mb-3">
                <i class="fas fa-arrow-left me-1"></i> Volver a Mis Citas
            </a>
        </div>
    </div>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-calendar-check me-2" style="color: var(--yellow);"></i>
                                Detalles de la Cita
                            </h4>
                            <p class="text-muted mb-0">
                                Información completa de la cita programada
                            </p>
                        </div>
                        <div class="text-end">
                            @if($cita->estado == 'pendiente')
                                <span class="badge bg-warning fs-6 p-2" style="background-color: var(--yellow) !important;">
                                    <i class="fas fa-clock me-1"></i> Pendiente
                                </span>
                            @elseif($cita->estado == 'confirmada')
                                <span class="badge bg-success fs-6 p-2">
                                    <i class="fas fa-check-circle me-1"></i> Confirmada
                                </span>
                            @elseif($cita->estado == 'completada')
                                <span class="badge bg-info fs-6 p-2">
                                    <i class="fas fa-check-double me-1"></i> Completada
                                </span>
                            @elseif($cita->estado == 'cancelada')
                                <span class="badge bg-danger fs-6 p-2">
                                    <i class="fas fa-times-circle me-1"></i> Cancelada
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información principal -->
        <div class="col-lg-8 mb-4">
            <div class="row">
                <!-- Información del cliente -->
                <div class="col-md-6 mb-4">
                    <div class="card admin-card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2" style="color: var(--yellow);"></i> Información del Cliente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px; border: 2px solid var(--yellow);">
                                    <i class="fas fa-user fa-2x" style="color: var(--yellow);"></i>
                                </div>
                                <h5 class="mt-3 mb-1">{{ $cita->cliente->name ?? 'Cliente' }}</h5>
                                <p class="text-muted">{{ $cita->cliente->email ?? 'N/A' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone me-2" style="color: var(--yellow);"></i>
                                    <div>
                                        <small class="text-muted d-block">Teléfono</small>
                                        <strong>{{ $cita->cliente->telefono ?? 'No disponible' }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar me-2" style="color: var(--yellow);"></i>
                                    <div>
                                        <small class="text-muted d-block">Citas previas</small>
                                        <strong>{{ $cita->cliente->citas->count() ?? 0 }}</strong>
                                    </div>
                                </div>
                            </div>
                            
                            @if($cita->comentarios_cliente)
                            <div class="card bg-light mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-comment me-2"></i> Comentarios del Cliente
                                    </h6>
                                    <p class="mb-0">{{ $cita->comentarios_cliente }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detalles de la cita -->
                <div class="col-md-6 mb-4">
                    <div class="card admin-card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2" style="color: var(--yellow);"></i> Detalles de la Cita
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-clock me-2" style="color: var(--yellow);"></i> Fecha y Hora
                                </h6>
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <div class="display-6 fw-bold">
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}
                                        </div>
                                        <div class="h5 mb-0">
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-cut me-2" style="color: var(--yellow);"></i> Servicio
                                </h6>
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <div>
                                        <h5 class="mb-1">{{ $cita->servicio->nombre ?? 'Servicio' }}</h5>
                                        <p class="text-muted mb-0">
                                            Duración: {{ $cita->servicio->duracion ?? 30 }} minutos
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="mb-0 text-warning">${{ number_format($cita->servicio->precio ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                            
                            @if($cita->productos->count() > 0)
                            <div class="mb-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-shopping-bag me-2" style="color: var(--yellow);"></i> Productos
                                </h6>
                                @foreach($cita->productos as $producto)
                                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                    <div>
                                        <h6 class="mb-0">{{ $producto->nombre }}</h6>
                                        <small class="text-muted">Cantidad: {{ $producto->pivot->cantidad ?? 1 }}</small>
                                    </div>
                                    <div class="text-end">
                                        <strong>${{ number_format($producto->precio, 2) }}</strong>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Formulario para cambiar estado -->
                <div class="col-12">
                    <div class="card admin-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-sync-alt me-2" style="color: var(--yellow);"></i> Gestionar Cita
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('barbero.citas.actualizar-estado', $cita->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="estado" class="form-label">Cambiar Estado</label>
                                        <select name="estado" id="estado" class="form-select" required>
                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="comentarios" class="form-label">Comentarios</label>
                                        <input type="text" name="comentarios" id="comentarios" class="form-control" 
                                               value="{{ $cita->comentarios_barbero }}" placeholder="Agregar comentarios...">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save me-1"></i> Actualizar Cita
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            @if($cita->comentarios_barbero)
                            <div class="mt-4">
                                <h6 class="fw-bold">
                                    <i class="fas fa-sticky-note me-2"></i> Notas anteriores
                                </h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $cita->comentarios_barbero }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen y acciones -->
        <div class="col-lg-4 mb-4">
            <!-- Resumen financiero -->
            <div class="card admin-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2" style="color: var(--yellow);"></i> Resumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Servicio:</span>
                            <strong>${{ number_format($cita->servicio->precio ?? 0, 2) }}</strong>
                        </div>
                        
                        @if($cita->productos->count() > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Productos:</span>
                            <strong>${{ number_format($cita->total_productos ?? 0, 2) }}</strong>
                        </div>
                        @endif
                        
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h5">Total:</span>
                            <span class="h4 text-warning">${{ number_format($cita->precio_total, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">Método de Pago</h6>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-credit-card fa-2x me-3" style="color: var(--yellow);"></i>
                            <div>
                                <div class="fw-bold">{{ ucfirst($cita->metodo_pago) }}</div>
                                <small class="text-muted">
                                    {{ $cita->pagado ? 'Pagado' : 'Pendiente de pago' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2" style="color: var(--yellow);"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="tel:{{ $cita->cliente->telefono ?? '' }}" class="btn btn-outline-warning">
                            <i class="fas fa-phone me-2"></i> Llamar al Cliente
                        </a>
                        
                        @if($cita->estado == 'pendiente')
                        <form action="{{ route('barbero.citas.actualizar-estado', $cita->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="estado" value="confirmada">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check-circle me-2"></i> Confirmar Cita
                            </button>
                        </form>
                        @endif
                        
                        @if($cita->estado == 'confirmada')
                        <form action="{{ route('barbero.citas.actualizar-estado', $cita->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="estado" value="completada">
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-check-double me-2"></i> Marcar como Completada
                            </button>
                        </form>
                        @endif
                        
                        @if(in_array($cita->estado, ['pendiente', 'confirmada']))
                        <form action="{{ route('barbero.citas.actualizar-estado', $cita->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="estado" value="cancelada">
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('¿Estás seguro de cancelar esta cita?')">
                                <i class="fas fa-times-circle me-2"></i> Cancelar Cita
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection