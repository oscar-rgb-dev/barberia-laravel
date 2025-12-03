@extends('layouts.app')

@section('title', 'Servicios - Barbería Premium')

@section('content')
<!-- Hero Section de Servicios -->
<section class="hero-bg text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Nuestros Cortes y Servicios</h1>
                <p class="lead mb-4">Descubre nuestra gama completa de servicios de barbería profesional</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-cut fa-5x text-warning"></i>
            </div>
        </div>
    </div>
</section>

<!-- Grid de Servicios -->
<section class="py-5">
    <div class="container">
        <div class="row" id="servicios-grid">
            @forelse($servicios as $servicio)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 servicio-item" data-category="{{ $servicio->tipo_servicio }}">
                <div class="card servicio-card h-100 border-0 shadow-hover">
                    <div class="card-img-container position-relative">
                        <!-- En la tabla o donde muestres los servicios -->
                          @if($servicio->imagen_url && str_starts_with($servicio->imagen_url, '/tmp/'))
                            <!-- Imagen desde /tmp -->
                            @php
                                try {
                                    $imageData = file_exists($servicio->imagen_url) 
                                        ? base64_encode(file_get_contents($servicio->imagen_url))
                                        : null;
                                } catch (\Exception $e) {
                                    $imageData = null;
                                }
                            @endphp
                            
                            @if($imageData)
                                <img src="data:image/jpeg;base64,{{ $imageData }}" 
                                    alt="{{ $servicio->nombre }}"
                                    class="servicio-imagen"
                                    style="height: 200px; width: 100%; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            @else
                                <img src="{{ asset('images/default-service.jpg') }}" 
                                    alt="{{ $servicio->nombre }}"
                                    class="servicio-imagen"
                                    style="height: 200px; width: 100%; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            @endif
                            
                        @elseif($servicio->imagen_url && str_starts_with($servicio->imagen_url, 'data:image'))
                            <!-- Imagen Base64 directa -->
                            <img src="{{ $servicio->imagen_url }}" 
                                alt="{{ $servicio->nombre }}"
                                class="servicio-imagen"
                                style="height: 200px; width: 100%; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            
                        @elseif($servicio->imagen_url)
                            <!-- URL normal -->
                            <img src="{{ asset($servicio->imagen_url) }}" 
                                alt="{{ $servicio->nombre }}"
                                class="servicio-imagen"
                                style="height: 200px; width: 100%; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                            
                        @else
                            <!-- Sin imagen -->
                            <img src="{{ asset('images/default-service.jpg') }}" 
                                alt="Sin imagen"
                                class="servicio-imagen"
                                style="height: 200px; width: 100%; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                        @endif
                        
                        <!-- Badge de tipo -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-dark">{{ $servicio->tipo_servicio }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark">{{ $servicio->nombre }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ $servicio->descripcion }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div class="precio-servicio">
                                <span class="h4 text-warning fw-bold mb-0">${{ number_format($servicio->costo, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            @auth
                                <a href="{{ route('citas.create') }}?servicio={{ $servicio->id }}" 
                                   class="btn btn-gold w-100 btn-agendar">
                                    <i class="fas fa-calendar-plus me-2"></i>Agendar Cita
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-dark w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-concierge-bell fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No hay servicios disponibles</h3>
                <p class="text-muted">Estamos trabajando en nuevos servicios para ti</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Llamada a la Acción -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <h2 class="mb-3">¿Listo para una nueva experiencia?</h2>
        <p class="lead mb-4">Agenda tu cita hoy y descubre por qué somos la barbería preferida</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('citas.create') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-calendar-plus me-2"></i>Agendar Cita
            </a>
            <a href="{{ route('servicios.index') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-cut me-2"></i>Ver Servicios
            </a>
        </div>
    </div>
</section>

<style>
    .shadow-hover {
        transition: all 0.3s ease;
    }
    
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    
    .servicio-imagen {
        transition: transform 0.3s ease;
    }
    
    .servicio-card:hover .servicio-imagen {
        transform: scale(1.05);
    }
    
    .card-img-container {
        overflow: hidden;
    }
    
    .btn-agendar {
        transition: all 0.3s ease;
    }
    
    .btn-agendar:hover {
        transform: scale(1.02);
    }
    
    .filter-btn.active {
        background-color: #ffc107;
        color: #000;
        border-color: #ffc107;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrado de servicios
        const filterBtns = document.querySelectorAll('.filter-btn');
        const servicioItems = document.querySelectorAll('.servicio-item');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remover active de todos los botones
                filterBtns.forEach(b => b.classList.remove('active'));
                // Agregar active al botón clickeado
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                
                servicioItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endsection