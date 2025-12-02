@extends('layouts.app')

@section('title', 'Servicios - Barbería NiceAdmin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="display-4 fw-bold">Servicios</h1>
            <p class="lead">Gestiona los servicios de tu barbería</p>
        </div>
        <a href="{{ route('servicios.create') }}" class="btn btn-gold btn-lg">
            <i class="fas fa-plus me-2"></i>Nuevo Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h3 class="card-title mb-0"><i class="fas fa-list me-2"></i>Lista de Servicios</h3>
        </div>
        <div class="card-body p-0">
            @if($servicios->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Costo</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($servicios as $servicio)
                                <tr>
                                    <td class="fw-bold">{{ $servicio->nombre }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $servicio->tipo_servicio }}</span>
                                    </td>
                                    <td>${{ number_format($servicio->costo, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                            {{ $servicio->descripcion }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('servicios.edit', $servicio->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-cut fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay servicios registrados</h4>
                    <p class="text-muted">Comienza agregando tu primer servicio.</p>
                    <a href="{{ route('servicios.create') }}" class="btn btn-gold">
                        <i class="fas fa-plus me-2"></i>Crear Primer Servicio
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection