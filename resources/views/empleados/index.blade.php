@extends('layouts.app')

@section('title', 'Gestión de Empleados - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestión de Empleados/Barberos</h3>
                    <a href="{{ route('admin.empleados.create') }}" class="btn btn-gold">
                        <i class="fas fa-plus"></i> Nuevo Empleado
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Departamento</th>
                                    <th>Jornada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empleados as $empleado)
                                <tr>
                                    <td>{{ $empleado->nombre }}</td>
                                    <td>{{ $empleado->telefono }}</td>
                                    <td>{{ $empleado->email }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $empleado->departamento->nombre_depto ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $empleado->jornada->descripcion ?? 'N/A' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $empleado->jornada->horario ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.empleados.edit', $empleado->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.empleados.destroy', $empleado->id) }}" method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('¿Estás seguro de eliminar este empleado?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5>No hay empleados registrados</h5>
                                        <p class="text-muted">Comienza agregando el primer empleado/barbero</p>
                                        <a href="{{ route('admin.empleados.create') }}" class="btn btn-gold">
                                            Agregar Primer Empleado
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection