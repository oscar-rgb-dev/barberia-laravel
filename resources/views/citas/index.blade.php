@extends('layouts.app')

@section('title', 'Mis Citas - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Mis Citas Agendadas</h3>
                    <a href="{{ route('citas.create') }}" class="btn btn-gold">
                        <i class="fas fa-plus"></i> Nueva Cita
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Servicio</th>
                                    <th>Barbero</th>
                                    <th>Fecha y Hora</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Notas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($citas as $cita)
                                <tr>
                                    <td>#{{ $cita->id }}</td>
                                    <td>
                                        <strong>{{ $cita->servicio->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">${{ number_format($cita->servicio->costo, 0) }}</small>
                                    </td>
                                    <td>
                                        {{ $cita->barbero->nombre }}
                                        <br>
                                        <small class="text-muted">{{ $cita->barbero->especialidad }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'pendiente' => 'bg-warning text-dark',
                                                'confirmada' => 'bg-success',
                                                'completada' => 'bg-info',
                                                'cancelada' => 'bg-danger',
                                                'no_asistio' => 'bg-secondary'
                                            ][$cita->estado] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ number_format($cita->total, 2) }}</strong>
                                        @if($cita->productos->count() > 0)
                                            <br>
                                            <small class="text-muted">
                                                +{{ $cita->productos->count() }} producto(s)
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($cita->notas)
                                            <small>{{ Str::limit($cita->notas, 50) }}</small>
                                        @else
                                            <span class="text-muted">Sin notas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Botón Ver Detalle -->
                                            <button type="button" class="btn btn-info btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detalleCitaModal"
                                                    onclick="cargarDetalleCita({{ $cita->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if(in_array($cita->estado, ['pendiente', 'confirmada']))
                                                <a href="{{ route('citas.edit', $cita->id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            
                                            <!-- Botón Cancelar para usuarios normales -->
                                            @if(!auth()->user()->isAdmin() && in_array($cita->estado, ['pendiente', 'confirmada']))
                                                <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('¿Estás seguro de cancelar esta cita?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->isAdmin())
                                                <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-dark btn-sm" 
                                                            onclick="return confirm('¿Estás seguro de eliminar esta cita?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5>No tienes citas agendadas</h5>
                                        <p class="text-muted">Agenda tu primera cita con nosotros</p>
                                        <a href="{{ route('citas.create') }}" class="btn btn-gold">
                                            Agendar Primera Cita
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles de la Cita -->
<div class="modal fade" id="detalleCitaModal" tabindex="-1" aria-labelledby="detalleCitaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detalleCitaContent">
                <!-- Aquí se cargarán los detalles dinámicamente -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando detalles de la cita...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="button" class="btn btn-gold" onclick="imprimirDetalleCita()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.btn-gold {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    border: none;
    color: #000;
    font-weight: bold;
}
.btn-gold:hover {
    background: linear-gradient(45deg, #FFA500, #FF8C00);
    color: #000;
}
.cita-detalle-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}
.cita-detalle-item:last-child {
    border-bottom: none;
}
</style>
@endpush

@push('scripts')
<script>
// Función para cargar los detalles de la cita via AJAX
function cargarDetalleCita(citaId) {
    // Mostrar spinner de carga
    document.getElementById('detalleCitaContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando detalles de la cita...</p>
        </div>
    `;

    // Hacer petición AJAX para obtener los detalles
    fetch(`/citas/${citaId}/detalle`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar los detalles');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Construir el HTML con los detalles
            const detallesHTML = `
                <div class="row">
                    <div class="col-md-12">
                        <div class="cita-detalle-item">
                            <h4 class="text-warning mb-3">Cita #${data.id}</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Estado:</strong>
                                        <span class="badge ${obtenerClaseEstado(data.estado)} ms-2">
                                            ${data.estado.charAt(0).toUpperCase() + data.estado.slice(1)}
                                        </span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Fecha:</strong>
                                        <span class="ms-2">${formatearFecha(data.fecha_hora)}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Hora:</strong>
                                        <span class="ms-2">${formatearHora(data.fecha_hora)}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Servicio:</strong>
                                        <span class="ms-2">${data.servicio.nombre}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Barbero:</strong>
                                        <span class="ms-2">${data.barbero.nombre}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Especialidad:</strong>
                                        <span class="ms-2">${data.barbero.especialidad || 'No especificada'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${data.productos && data.productos.length > 0 ? `
                        <div class="cita-detalle-item">
                            <h6 class="text-primary mb-3">Productos Incluidos</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${data.productos.map(producto => `
                                            <tr>
                                                <td>${producto.nombre}</td>
                                                <td>${producto.pivot.cantidad}</td>
                                                <td>$${parseFloat(producto.costo).toFixed(2)}</td>
                                                <td>$${(parseFloat(producto.costo) * parseInt(producto.pivot.cantidad)).toFixed(2)}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        ` : '<div class="cita-detalle-item"><p class="text-muted">No se incluyeron productos en esta cita.</p></div>'}

                        ${data.notas ? `
                        <div class="cita-detalle-item">
                            <h6 class="text-primary mb-2">Notas Adicionales</h6>
                            <div class="alert alert-light border rounded p-3">
                                <p class="mb-0"><i class="fas fa-sticky-note text-muted me-2"></i>${data.notas}</p>
                            </div>
                        </div>
                        ` : ''}

                        <div class="cita-detalle-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Costo del Servicio:</strong>
                                        <span class="float-end">$${parseFloat(data.servicio.costo).toFixed(2)}</span>
                                    </div>
                                    ${data.productos && data.productos.length > 0 ? `
                                    <div class="mb-2">
                                        <strong>Productos:</strong>
                                        <span class="float-end">$${(parseFloat(data.total) - parseFloat(data.servicio.costo)).toFixed(2)}</span>
                                    </div>
                                    ` : ''}
                                    <hr>
                                    <div class="mb-2">
                                        <strong>Total General:</strong>
                                        <span class="float-end text-success fw-bold">$${parseFloat(data.total).toFixed(2)}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="total-container text-end">
                                        <h4 class="text-success mb-0">
                                            <i class="fas fa-receipt me-2"></i>Total: $${parseFloat(data.total).toFixed(2)}
                                        </h4>
                                        <small class="text-muted">Incluye servicio y productos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('detalleCitaContent').innerHTML = detallesHTML;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detalleCitaContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Error:</strong> ${error.message}
                </div>
            `;
        });
}

// Funciones auxiliares
function obtenerClaseEstado(estado) {
    const clases = {
        'pendiente': 'bg-warning text-dark',
        'confirmada': 'bg-success',
        'completada': 'bg-info',
        'cancelada': 'bg-danger',
        'no_asistio': 'bg-secondary'
    };
    return clases[estado] || 'bg-secondary';
}

function formatearFecha(fechaHora) {
    const fecha = new Date(fechaHora);
    return fecha.toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatearHora(fechaHora) {
    const fecha = new Date(fechaHora);
    return fecha.toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Función para imprimir los detalles
function imprimirDetalleCita() {
    const contenido = document.getElementById('detalleCitaContent').innerHTML;
    const ventana = window.open('', '_blank');
    ventana.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Detalle de Cita - Barbería</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .text-primary { color: #0d6efd !important; }
                .text-success { color: #198754 !important; }
                .text-warning { color: #ffc107 !important; }
                .badge { padding: 0.35em 0.65em; font-size: 0.75em; }
                .bg-warning { background-color: #ffc107 !important; }
                .bg-info { background-color: #0dcaf0 !important; }
                .bg-success { background-color: #198754 !important; }
                .bg-danger { background-color: #dc3545 !important; }
                .bg-secondary { background-color: #6c757d !important; }
                .cita-detalle-item { border-bottom: 1px solid #dee2e6; padding: 15px 0; }
                .cita-detalle-item:last-child { border-bottom: none; }
            </style>
        </head>
        <body>
            <div class="container">
                <h3 class="text-center mb-4 text-primary">Detalle de Cita - Barbería</h3>
                ${contenido}
                <div class="mt-4 text-center text-muted">
                    <small>Generado el ${new Date().toLocaleDateString('es-ES')} a las ${new Date().toLocaleTimeString('es-ES')}</small>
                </div>
            </div>
        </body>
        </html>
    `);
    ventana.document.close();
    ventana.print();
}
</script>
@endpush