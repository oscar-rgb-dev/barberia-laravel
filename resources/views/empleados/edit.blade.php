@extends('layouts.app')

@section('title', 'Editar Empleado - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Editar Empleado: {{ $empleado->nombre }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.empleados.update', $empleado->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nombre" class="form-label">Nombre Completo *</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                           id="telefono" name="telefono" value="{{ old('telefono', $empleado->telefono) }}" required>
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
                                           id="email" name="email" value="{{ old('email', $empleado->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="contraseña" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control @error('contraseña') is-invalid @enderror" 
                                           id="contraseña" name="contraseña" placeholder="Dejar en blanco para no cambiar">
                                    @error('contraseña')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Deja este campo en blanco si no deseas cambiar la contraseña</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_depto" class="form-label">Departamento *</label>
                                    <select class="form-control @error('id_depto') is-invalid @enderror" 
                                            id="id_depto" name="id_depto" required>
                                        <option value="">Selecciona un departamento</option>
                                        @foreach($departamentos as $departamento)
                                            <option value="{{ $departamento->id }}" 
                                                {{ old('id_depto', $empleado->id_depto) == $departamento->id ? 'selected' : '' }}>
                                                {{ $departamento->nombre_depto }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_depto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_jornada" class="form-label">Jornada *</label>
                                    <select class="form-control @error('id_jornada') is-invalid @enderror" 
                                            id="id_jornada" name="id_jornada" required>
                                        <option value="">Selecciona una jornada</option>
                                        @foreach($jornadas as $jornada)
                                            <option value="{{ $jornada->id }}" 
                                                {{ old('id_jornada', $empleado->id_jornada) == $jornada->id ? 'selected' : '' }}>
                                                {{ $jornada->descripcion }} ({{ $jornada->horario }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_jornada')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.empleados.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-save"></i> Actualizar Empleado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection