@extends('layouts.app')

@section('title', 'Editar Jornada - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Editar Jornada: {{ $jornada->descripcion }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jornadas.update', $jornada->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label for="descripcion" class="form-label">Descripción de la Jornada *</label>
                            <input type="text" class="form-control @error('descripcion') is-invalid @enderror" 
                                   id="descripcion" name="descripcion" 
                                   value="{{ old('descripcion', $jornada->descripcion) }}" 
                                   placeholder="Ej: Jornada Matutina, Jornada Vespertina, Tiempo Completo..." 
                                   required>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="horario" class="form-label">Horario *</label>
                            <input type="text" class="form-control @error('horario') is-invalid @enderror" 
                                   id="horario" name="horario" 
                                   value="{{ old('horario', $jornada->horario) }}" 
                                   placeholder="Ej: 08:00 - 17:00, 09:00 - 18:00, 07:00 - 16:00, etc." 
                                   required>
                            <small class="form-text text-muted">
                                Formato recomendado: HH:MM - HH:MM (ejemplo: 08:00 - 17:00)
                            </small>
                            @error('horario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.jornadas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-save"></i> Actualizar Jornada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection