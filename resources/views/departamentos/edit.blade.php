@extends('layouts.app')

@section('title', 'Editar Departamento - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Editar Departamento: {{ $departamento->nombre_depto }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.departamentos.update', $departamento->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-4">
                            <label for="nombre_depto" class="form-label">Nombre del Departamento *</label>
                            <input type="text" class="form-control @error('nombre_depto') is-invalid @enderror" 
                                   id="nombre_depto" name="nombre_depto" 
                                   value="{{ old('nombre_depto', $departamento->nombre_depto) }}" 
                                   placeholder="Ej: Barbería, Recepción, Administración..." 
                                   required>
                            @error('nombre_depto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Actualiza el nombre del departamento o área de trabajo.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.departamentos.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-save"></i> Actualizar Departamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection