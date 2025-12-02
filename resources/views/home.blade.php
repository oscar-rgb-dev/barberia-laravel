@extends('layouts.app')

@section('title', 'Inicio - Barbería Premium')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">La mejor barbería de la ciudad</h1>
                    <p class="lead mb-4">Estilo, tradición y calidad en cada corte. Experiencia única en cuidado masculino.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('citas.create') }}" class="btn btn-gold btn-lg">
                            <i class="fas fa-calendar-check"></i> Reservar Cita
                        </a>
                        <a href="{{ route('servicios.index') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-concierge-bell"></i> Ver Servicios
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image-container">
                        <img src="{{ asset('images/barberia-hero.jpg') }}" alt="Barbería Premium" class="hero-image rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre Nosotros -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                    <h2 class="mb-4">Sobre Nosotros</h2>
                    <p class="lead text-muted mb-4">
                        En <strong>Barbería Premium</strong> no solo cortamos cabello, creamos experiencias. 
                        Somos un espacio dedicado al hombre moderno que valora la tradición, la calidad y el estilo.
                    </p>
                    <p class="text-muted mb-4">
                        Con más de 10 años de experiencia, hemos perfeccionado el arte de la barbería clásica 
                        combinándola con las últimas tendencias y técnicas modernas.
                    </p>
                    
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <img src="{{ asset('images/barberia-interior-1.jpg') }}" alt="Interior Barbería" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-6">
                            <img src="{{ asset('images/barberia-interior-2.jpg') }}" alt="Trabajo Barbería" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-6 mt-4">
                            <img src="{{ asset('images/barberia-interior-3.jpg') }}" alt="Ambiente Barbería" class="img-fluid rounded shadow">
                        </div>
                        <div class="col-6 mt-4">
                            <img src="{{ asset('images/barberia-interior-4.jpg') }}" alt="Productos Barbería" class="img-fluid rounded shadow">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Por Qué Elegirnos -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">¿Por Qué Elegirnos?</h2>
            <div class="row">
                @foreach($nosotros as $item)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="icon-container mb-3">
                                <i class="fas fa-{{ $item['icono'] }} fa-2x text-warning"></i>
                            </div>
                            <h4 class="card-title h5">{{ $item['titulo'] }}</h4>
                            <p class="card-text text-muted">{{ $item['descripcion'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
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
@endsection

<style>
    .hero-bg {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    }
    
    .btn-gold {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
        font-weight: 600;
    }
    
    .btn-gold:hover {
        background-color: #e0a800;
        border-color: #e0a800;
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .hero-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    
    .icon-container {
        width: 60px;
        height: 60px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .shadow-sm {
        transition: transform 0.3s ease;
    }
    
    .shadow-sm:hover {
        transform: translateY(-5px);
    }
</style>