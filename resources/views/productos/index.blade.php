@extends('layouts.app')

@section('title', 'Gestión de Productos - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Gestión de Productos</h3>
                    <a href="{{ route('admin.productos.create') }}" class="btn btn-gold">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                </div>

                <div class="card-body">

                    {{-- Mensaje de éxito --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                   

                    {{-- TABLA PRINCIPAL --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Costo</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($productos as $producto)
                                <tr>
                                    <td>
                                        @if($producto->imagen)
                                            @php
                                                $imageSrc = App\Helpers\ImageHelper::getImageSrc($producto->imagen);
                                            @endphp
                                            
                                            <img src="{{ $imageSrc }}"
                                                class="rounded"
                                                alt="{{ $producto->nombre }}"
                                                style="height: 50px; width: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-box text-white"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <td>{{ $producto->nombre }}</td>

                                    <td>
                                        <span class="text-muted small">
                                            {{ Str::limit($producto->descripcion, 50) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-success">
                                            ${{ number_format($producto->costo, 2) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge {{ $producto->stock > 0 ? 'bg-info' : 'bg-danger' }}">
                                            {{ $producto->stock }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.productos.edit', $producto->id) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.productos.destroy', $producto->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                        <h5>No hay productos registrados</h5>
                                        <p class="text-muted">Comienza agregando el primer producto</p>
                                        <a href="{{ route('admin.productos.create') }}" class="btn btn-gold">
                                            Agregar Primer Producto
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
