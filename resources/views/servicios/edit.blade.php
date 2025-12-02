@extends('layouts.app')

@section('title', 'Editar Servicio - Barbería NiceAdmin')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Editar Servicio</h1>
        <p class="lead">Modifica la información del servicio seleccionado</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Información del Servicio
                    </h3>
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

                    <form action="{{ route('servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nombre del Servicio -->
                        <div class="mb-4">
                            <label for="nombre" class="form-label">Nombre del Servicio *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $servicio->nombre) }}" 
                                   required>
                            <div class="form-text">Nombre descriptivo del servicio</div>
                        </div>

                        <!-- Tipo de Servicio -->
                        <div class="mb-4">
                            <label for="tipo_servicio" class="form-label">Tipo de Servicio *</label>
                            <select class="form-select" id="tipo_servicio" name="tipo_servicio" required>
                                <option value="Corte"      {{ $servicio->tipo_servicio == 'Corte' ? 'selected' : '' }}>Corte</option>
                                <option value="Barba"      {{ $servicio->tipo_servicio == 'Barba' ? 'selected' : '' }}>Barba</option>
                                <option value="Combo"      {{ $servicio->tipo_servicio == 'Combo' ? 'selected' : '' }}>Combo (Corte + Barba)</option>
                                <option value="Tratamiento"{{ $servicio->tipo_servicio == 'Tratamiento' ? 'selected' : '' }}>Tratamiento</option>
                                <option value="Otro"       {{ $servicio->tipo_servicio == 'Otro' ? 'selected' : '' }}>Otro</option>
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
                                       value="{{ old('costo', $servicio->costo) }}" 
                                       min="0" 
                                       step="0.01"
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
                                      required>{{ old('descripcion', $servicio->descripcion) }}</textarea>
                            <div class="form-text">Descripción detallada del servicio</div>
                        </div>

                        <!-- Imagen -->
                        <div class="form-group mb-4">
                            <label class="form-label">Imagen Actual</label>
                            <div class="mb-3">
                                @if($servicio->imagen)
                                    <img src="{{ asset('storage/' . $servicio->imagen) }}" 
                                         alt="Imagen del Servicio" 
                                         class="img-fluid rounded shadow"
                                         style="max-height: 200px;">
                                @else
                                    <p class="text-muted">No hay imagen registrada.</p>
                                @endif
                            </div>

                            <label for="imagen" class="form-label">Actualizar Imagen</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror"
                                   id="imagen" name="imagen" accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Formatos permitidos: JPG, PNG, JPEG, GIF. Máx: 2MB</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-gold btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>

                            <a href="/servicios" class="btn btn-outline-secondary btn-lg px-5 ms-2">
                                <i class="fas fa-arrow-left me-2"></i>Volver a Servicios
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
