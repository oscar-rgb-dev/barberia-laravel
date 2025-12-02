@extends('layouts.app')

@section('title', 'Productos - Barbería Premium')

@section('content')
<!-- Hero Section de Productos -->
<section class="hero-bg text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Nuestros Productos</h1>
                <p class="lead mb-4">Descubre nuestra línea exclusiva de productos para el cuidado masculino</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-shopping-bag fa-5x text-warning"></i>
            </div>
        </div>
    </div>
</section>

<!-- Filtros por Categoría -->
<section class="py-4 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-dark filter-btn active" data-filter="all">
                            Todos los Productos
                        </button>
                        <button type="button" class="btn btn-outline-dark filter-btn" data-filter="Cuidado">
                            Cuidado Personal
                        </button>
                        <button type="button" class="btn btn-outline-dark filter-btn" data-filter="Estilo">
                            Estilización
                        </button>
                        <button type="button" class="btn btn-outline-dark filter-btn" data-filter="Afeitado">
                            Afeitado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Grid de Productos -->
<section class="py-5">
    <div class="container">
        <div class="row" id="productos-grid">
            @forelse($productos as $producto)
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 producto-item" data-category="{{ $producto->categoria ?? 'Cuidado' }}">
                <div class="card producto-card h-100 border-0 shadow-hover">
                    <div class="card-img-container position-relative">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 class="card-img-top producto-imagen" 
                                 alt="{{ $producto->nombre }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-box fa-3x text-light"></i>
                            </div>
                        @endif
                        
                        <!-- Badge de stock -->
                        <div class="position-absolute top-0 start-0 m-2">
                            @if($producto->stock > 10)
                                <span class="badge bg-success">Disponible</span>
                            @elseif($producto->stock > 0)
                                <span class="badge bg-warning text-dark">Últimas unidades</span>
                            @else
                                <span class="badge bg-danger">Agotado</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark">{{ $producto->nombre }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($producto->descripcion, 100) }}</p>
                        
                        <div class="producto-info mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="precio-producto">
                                    <span class="h4 text-warning fw-bold mb-0">${{ number_format($producto->costo, 0, ',', '.') }}</span>
                                </div>
                                <div class="stock-info">
                                    <small class="text-muted">
                                        <i class="fas fa-boxes me-1"></i>
                                        {{ $producto->stock }} disponibles
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="d-grid gap-2">
                                @if($producto->stock > 0)
                                    <button class="btn btn-gold btn-comprar" data-producto-id="{{ $producto->id }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Agregar al Carrito
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-times me-2"></i>Producto Agotado
                                    </button>
                                @endif
                                
                                <button class="btn btn-outline-dark btn-detalles" data-bs-toggle="modal" data-bs-target="#productoModal{{ $producto->id }}">
                                    <i class="fas fa-eye me-2"></i>Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Detalles -->
            <div class="modal fade" id="productoModal{{ $producto->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $producto->nombre }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($producto->imagen)
                                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $producto->nombre }}">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center rounded" 
                                             style="height: 200px;">
                                            <i class="fas fa-box fa-3x text-light"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-warning">${{ number_format($producto->costo, 0, ',', '.') }}</h4>
                                    <p class="text-muted">{{ $producto->descripcion }}</p>
                                    
                                    <div class="producto-detalles">
                                        <div class="mb-3">
                                            <strong>Stock disponible:</strong>
                                            <span class="badge bg-{{ $producto->stock > 0 ? 'success' : 'danger' }}">
                                                {{ $producto->stock }} unidades
                                            </span>
                                        </div>
                                        
                                        @if($producto->stock > 0)
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-gold btn-comprar" data-producto-id="{{ $producto->id }}">
                                                    <i class="fas fa-shopping-cart me-2"></i>Agregar al Carrito
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No hay productos disponibles</h3>
                <p class="text-muted">Estamos trabajando en nuevos productos para ti</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Llamada a la acción -->
<section class="py-5 bg-dark text-white">
    <div class="container text-center">
        <h2 class="mb-3">¿Necesitas asesoría profesional?</h2>
        <p class="lead mb-4">Nuestros barberos te recomendarán los mejores productos</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('citas.create') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-calendar-plus me-2"></i>Agendar Cita
            </a>
            <a href="{{ route('contacto') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-envelope me-2"></i>Consultar
            </a>
        </div>
    </div>
</section>

<style>
    .shadow-hover {
        transition: all 0.3s ease;
    }
    
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    
    .producto-imagen {
        transition: transform 0.3s ease;
    }
    
    .producto-card:hover .producto-imagen {
        transform: scale(1.05);
    }
    
    .card-img-container {
        overflow: hidden;
    }
    
    .btn-comprar {
        transition: all 0.3s ease;
    }
    
    .btn-comprar:hover {
        transform: scale(1.02);
    }
    
    .filter-btn.active {
        background-color: #ffc107;
        color: #000;
        border-color: #ffc107;
    }
    
    .producto-info {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtrado de productos
        const filterBtns = document.querySelectorAll('.filter-btn');
        const productoItems = document.querySelectorAll('.producto-item');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remover active de todos los botones
                filterBtns.forEach(b => b.classList.remove('active'));
                // Agregar active al botón clickeado
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                
                productoItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Funcionalidad de compra (placeholder)
        document.querySelectorAll('.btn-comprar').forEach(btn => {
            btn.addEventListener('click', function() {
                const productoId = this.getAttribute('data-producto-id');
                
                // Aquí puedes implementar la lógica del carrito
                Swal.fire({
                    title: '¡Producto agregado!',
                    text: 'El producto ha sido agregado a tu carrito',
                    icon: 'success',
                    confirmButtonText: 'Continuar'
                });
                
                // Ejemplo: agregar al carrito
                // agregarAlCarrito(productoId);
            });
        });
    });
</script>
@endsection