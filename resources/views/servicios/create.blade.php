@extends('layouts.app')

@section('title', 'Crear Servicio - Barbería NiceAdmin')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Crear Nuevo Servicio</h1>
        <p class="lead">Agrega un nuevo servicio a tu barbería</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0"><i class="fas fa-plus-circle me-2"></i>Información del Servicio</h3>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
                         @csrf
                        
                        <!-- Nombre del Servicio -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label">Nombre del Servicio *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}" 
                                   placeholder="Ej: Corte Clásico, Afeitado Premium, etc."
                                   required>
                            <div class="form-text">Nombre descriptivo del servicio</div>
                        </div>

                        <!-- Tipo de Servicio -->
                        <div class="mb-4">
                            <label for="tipo_servicio" class="form-label">Tipo de Servicio *</label>
                            <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="Corte" {{ old('tipo_servicio') == 'Corte' ? 'selected' : '' }}>Corte</option>
                                <option value="Barba" {{ old('tipo_servicio') == 'Barba' ? 'selected' : '' }}>Barba</option>
                                <option value="Combo" {{ old('tipo_servicio') == 'Combo' ? 'selected' : '' }}>Combo (Corte + Barba)</option>
                                <option value="Tratamiento" {{ old('tipo_servicio') == 'Tratamiento' ? 'selected' : '' }}>Tratamiento</option>
                                <option value="Otro" {{ old('tipo_servicio') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            <div class="form-text">Categoría del servicio</div>
                        </div>

                        <!-- Costo -->
                        <div class="mb-4">
                            <label for="costo" class="form-label">Costo *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control" 
                                       id="costo" 
                                       name="costo" 
                                       value="{{ old('costo') }}" 
                                       min="0" 
                                       step="0.01"
                                       placeholder="0.00"
                                       required>
                            </div>
                            <div class="form-text">Precio del servicio</div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label">Descripción *</label>
                            <textarea class="form-control" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="4" 
                                      placeholder="Describe detalladamente el servicio, qué incluye, duración aproximada, etc."
                                      required>{{ old('descripcion') }}</textarea>
                            <div class="form-text">Descripción detallada del servicio</div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="imagen" class="form-label">Imagen del Servicio</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                id="imagen" name="imagen" accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Formatos: JPG, PNG, JPEG, GIF. Máx: 2MB</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-gold btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Crear Servicio
                            </button>
                            <a href="/servicios" class="btn btn-outline-secondary btn-lg px-5 ms-2">                                <i class="fas fa-arrow-left me-2"></i>Volver a Servicios
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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

.form-control:focus, .form-select:focus {
    border-color: #FFD700;
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
}
</style>
@endsection