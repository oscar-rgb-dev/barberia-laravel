@extends('layouts.app')

@section('title', 'Agregar Empleado - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Agregar Nuevo Empleado/Barbero</h3>
                </div>
                <div class="card-body">
                    
                  

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <h6>Errores de validación:</h6>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    
                    <form action="{{ route('admin.empleados.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nombre" class="form-label">Nombre Completo *</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" name="nombre" value="{{ old('nombre') }}" 
                                           placeholder="Ej: Juan Pérez García" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                           id="telefono" name="telefono" value="{{ old('telefono') }}" 
                                           placeholder="Ej: 3321234567" required>
                                    @error('telefono')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="Ej: empleado@barberia.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_depto" class="form-label">Departamento *</label>
                                    <select class="form-control @error('id_depto') is-invalid @enderror" 
                                            id="id_depto" name="id_depto" required>
                                        <option value="">Selecciona un departamento</option>
                                        @foreach($departamentos as $depto)
                                            <option value="{{ $depto->id }}" {{ old('id_depto') == $depto->id ? 'selected' : '' }}>
                                                {{ $depto->nombre_depto }} {{-- CAMBIADO: nombre -> nombre_depto --}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_depto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_jornada" class="form-label">Jornada *</label>
                                    <select class="form-control @error('id_jornada') is-invalid @enderror" 
                                            id="id_jornada" name="id_jornada" required>
                                        <option value="">Selecciona una jornada</option>
                                        @foreach($jornadas as $jornada)
                                            <option value="{{ $jornada->id }}" {{ old('id_jornada') == $jornada->id ? 'selected' : '' }}>
                                                {{ $jornada->descripcion }} - {{ $jornada->horario }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_jornada')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contraseña" class="form-label">Contraseña *</label>
                                    <input type="password" class="form-control @error('contraseña') is-invalid @enderror" 
                                           id="contraseña" name="contraseña" required>
                                    @error('contraseña')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="contraseña_confirmation" class="form-label">Confirmar Contraseña *</label>
                                    <input type="password" class="form-control" 
                                           id="contraseña_confirmation" name="contraseña_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.empleados.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-save"></i> Guardar Empleado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection