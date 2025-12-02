@extends('layouts.app')

@section('title', 'Servicios - Barbería NiceAdmin')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Nuestros Servicios</h1>
        <p class="lead">Descubre todos los servicios que tenemos para ti</p>
    </div>
    
    <!-- Lista de Servicios -->
    <div class="row mb-5">
        @foreach($servicios as $servicio)
        <div class="col-lg-6 mb-4">
            <div class="card service-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px;">
                                <i class="fas fa-{{ $servicio['icono'] }} fa-lg text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $servicio['nombre'] }}</h5>
                                <span class="price-tag">${{ number_format($servicio['precio'], 0, ',', '.') }}</span>
                            </div>
                            <p class="card-text text-muted mb-2">{{ $servicio['descripcion'] }}</p>
                            <small class="text-muted"><i class="fas fa-clock me-1"></i>Duración: {{ $servicio['duracion'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Formulario de Reserva -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0 text-center"><i class="fas fa-calendar-check me-2"></i>Reserva tu Cita</h3>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('citas.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre completo *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono *</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="servicio" class="form-label">Servicio *</label>
                                <select class="form-select" id="servicio" name="servicio" required>
                                    <option value="">Selecciona un servicio</option>
                                    @foreach($servicios as $servicio)
                                    <option value="{{ $servicio['nombre'] }}">
                                        {{ $servicio['nombre'] }} - ${{ number_format($servicio['precio'], 0, ',', '.') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha" class="form-label">Fecha preferida *</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje adicional (opcional)</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="3" placeholder="Alguna preferencia especial o comentario..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-gold btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Reservar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fecha mínima para reservas (hoy)
        const fechaInput = document.getElementById('fecha');
        if (fechaInput) {
            const today = new Date().toISOString().split('T')[0];
            fechaInput.min = today;
        }

        // Validación de teléfono
        const telefonoInput = document.getElementById('telefono');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9+-\s()]/g, '');
            });
        }
    });
</script>
@endpush
@endsection