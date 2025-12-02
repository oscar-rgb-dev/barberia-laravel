@extends('layouts.app')

@section('title', 'Galería - Barbería NiceAdmin')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Nuestra Galería</h1>
        <p class="lead">Conoce nuestro trabajo y ambiente</p>
    </div>

    <div class="row">
        @foreach($imagenes as $index => $imagen)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card service-card h-100 text-center">
                <div class="card-body py-4">
                    <div class="mb-3">
                        <i class="fas fa-{{ $imagen['icono'] }} fa-4x text-warning"></i>
                    </div>
                    <h5 class="card-title">{{ $imagen['titulo'] }}</h5>
                    <p class="card-text text-muted">{{ $imagen['descripcion'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Información adicional -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-light">
                <div class="card-body text-center p-5">
                    <h3 class="card-title mb-4">¿Te gusta nuestro trabajo?</h3>
                    <p class="card-text mb-4">Agenda una cita y experimenta la calidad de nuestro servicio</p>
                    <a href="{{ route('contacto') }}" class="btn btn-gold btn-lg">Agendar Cita Ahora</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection