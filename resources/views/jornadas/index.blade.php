@extends('layouts.app')

@section('title', 'Gestión de Jornadas - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestión de Jornadas Laborales</h3>
                    <a href="{{ route('admin.jornadas.create') }}" class="btn btn-gold">
                        <i class="fas fa-plus"></i> Nueva Jornada
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Descripción</th>
                                    <th>Horario</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jornadas as $jornada)
                                <tr>
                                    <td>{{ $jornada->id }}</td>
                                    <td>
                                        <strong>{{ $jornada->descripcion }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $jornada->horario }}</span>
                                    </td>
                                    <td>
                                        {{ $jornada->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.jornadas.edit', $jornada->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <form action="{{ route('admin.jornadas.destroy', $jornada->id) }}" method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('¿Estás seguro de eliminar esta jornada?')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                        <h5>No hay jornadas registradas</h5>
                                        <p class="text-muted">Agregando jornada laboral</p>
                                        <a href="{{ route('admin.jornadas.create') }}" class="btn btn-gold">
                                            Agregar Jornada
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