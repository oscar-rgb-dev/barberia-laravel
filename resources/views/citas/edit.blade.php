@extends('layouts.app')

@section('title', 'Editar Cita - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Editar Cita #{{ $cita->id }}</h3>
                    <small class="text-muted">Estado actual: 
                        <span class="badge bg-warning text-dark">{{ ucfirst($cita->estado) }}</span>
                    </small>
                </div>
                <div class="card-body">
                    <form action="{{ route('citas.update', $cita->id) }}" method="POST" id="citaForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_servicio" class="form-label">Servicio *</label>
                                    <select class="form-control @error('id_servicio') is-invalid @enderror" 
                                            id="id_servicio" name="id_servicio" required
                                            data-precio="{{ $cita->servicio->costo }}">
                                        <option value="">Selecciona un servicio</option>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" 
                                                {{ old('id_servicio', $cita->id_servicio) == $servicio->id ? 'selected' : '' }}
                                                data-precio="{{ $servicio->costo }}">
                                                {{ $servicio->nombre }} - ${{ number_format($servicio->costo, 0) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_servicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_barbero" class="form-label">Barbero *</label>
                                    <select class="form-control @error('id_barbero') is-invalid @enderror" 
                                            id="id_barbero" name="id_barbero" required>
                                        <option value="">Selecciona un barbero</option>
                                        @foreach($empleados as $empleado)
                                            <option value="{{ $empleado->id }}" 
                                                {{ old('id_barbero', $cita->id_barbero) == $empleado->id ? 'selected' : '' }}>
                                                {{ $empleado->nombre }} - {{ $empleado->especialidad }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_barbero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fecha" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                           id="fecha" name="fecha" 
                                           value="{{ old('fecha', \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d')) }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('fecha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="hora" class="form-label">Hora *</label>
                                    <input type="time" class="form-control @error('hora') is-invalid @enderror" 
                                           id="hora" name="hora" 
                                           value="{{ old('hora', \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i')) }}" 
                                           required>
                                    @error('hora')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="form-group mb-4">
                            <label class="form-label">Productos (Opcional)</label>
                            <div class="productos-list">
                                @foreach($productos as $producto)
                                    @php
                                        $productoCita = $cita->productos->where('id', $producto->id)->first();
                                        $cantidad = $productoCita ? $productoCita->pivot->cantidad : 0;
                                        $checked = $cantidad > 0;
                                    @endphp
                                    <div class="producto-item mb-2 p-3 border rounded">
                                        <div class="form-check">
                                            <input class="form-check-input producto-checkbox" 
                                                   type="checkbox" 
                                                   value="{{ $producto->id }}"
                                                   id="producto{{ $producto->id }}"
                                                   data-precio="{{ $producto->costo }}"
                                                   data-nombre="{{ $producto->nombre }}"
                                                   data-stock="{{ $producto->stock }}"
                                                   {{ $checked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="producto{{ $producto->id }}">
                                                <strong>{{ $producto->nombre }}</strong> - 
                                                ${{ number_format($producto->costo, 2) }}
                                                <small class="text-muted">(Stock: {{ $producto->stock }})</small>
                                            </label>
                                        </div>
                                        <div class="cantidad-container mt-2 {{ $checked ? '' : 'd-none' }}">
                                            <label class="form-label">Cantidad:</label>
                                            <input type="number" 
                                                   class="form-control cantidad-input" 
                                                   value="{{ $cantidad ?: 1 }}" 
                                                   min="1" 
                                                   max="{{ $producto->stock }}"
                                                   style="width: 100px;"
                                                   {{ $checked ? '' : 'disabled' }}>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Resumen de la Cita -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Resumen de la Cita</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="resumen-servicio" class="mb-2">
                                            <strong>Servicio:</strong> 
                                            <span id="servicio-seleccionado">{{ $cita->servicio->nombre }}</span>
                                        </div>
                                        <div id="resumen-productos" class="mb-2">
                                            <strong>Productos:</strong> 
                                            <ul id="lista-productos" class="mb-0">
                                                @foreach($cita->productos as $producto)
                                                    <li>{{ $producto->nombre }} (x{{ $producto->pivot->cantidad }}) - ${{ number_format($producto->costo * $producto->pivot->cantidad, 2) }}</li>
                                                @endforeach
                                                @if($cita->productos->count() === 0)
                                                    <li class="text-muted">No hay productos seleccionados</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="total-container text-end">
                                            <h4 class="text-success mb-0">
                                                Total: $<span id="total-cita">{{ number_format($cita->total, 2) }}</span>
                                            </h4>
                                            <small class="text-muted">Servicio + Productos</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="notas" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control @error('notas') is-invalid @enderror" 
                                      id="notas" name="notas" rows="3" 
                                      placeholder="Especificaciones del corte, preferencias...">{{ old('notas', $cita->notas) }}</textarea>
                            @error('notas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->isAdmin())
                        <div class="form-group mb-4">
                            <label for="estado" class="form-label">Estado de la Cita</label>
                            <select class="form-control @error('estado') is-invalid @enderror" 
                                    id="estado" name="estado">
                                <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="no_asistio" {{ old('estado', $cita->estado) == 'no_asistio' ? 'selected' : '' }}>No Asistió</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <div>
                                @if(in_array($cita->estado, ['pendiente', 'confirmada']))
                                    <button type="submit" class="btn btn-gold">
                                        <i class="fas fa-save"></i> Actualizar Cita
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban"></i> No se puede modificar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const servicioSelect = document.getElementById('id_servicio');
    const productoCheckboxes = document.querySelectorAll('.producto-checkbox');
    const totalElement = document.getElementById('total-cita');
    const servicioSeleccionado = document.getElementById('servicio-seleccionado');
    const listaProductos = document.getElementById('lista-productos');
    const form = document.getElementById('citaForm');

    let productosSeleccionados = [];

    // Inicializar productos seleccionados desde los checkboxes marcados
    function inicializarProductos() {
        productoCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const cantidadInput = checkbox.parentNode.parentNode.querySelector('.cantidad-input');
                const cantidad = parseInt(cantidadInput.value);
                productosSeleccionados.push({
                    id: checkbox.value,
                    cantidad: cantidad
                });
            }
        });
        actualizarFormulario();
    }

    function actualizarResumen() {
        let total = 0;
        
        // Servicio
        if (servicioSelect.value) {
            const selectedOption = servicioSelect.selectedOptions[0];
            const precioServicio = parseFloat(selectedOption.dataset.precio);
            total += precioServicio;
            servicioSeleccionado.textContent = selectedOption.textContent.split(' - ')[0];
        } else {
            servicioSeleccionado.textContent = '-';
        }
        
        // Productos
        listaProductos.innerHTML = '';
        productosSeleccionados = [];
        
        productoCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const cantidadInput = checkbox.parentNode.parentNode.querySelector('.cantidad-input');
                const precio = parseFloat(checkbox.dataset.precio);
                const cantidad = parseInt(cantidadInput.value);
                const nombre = checkbox.dataset.nombre;
                
                total += precio * cantidad;
                
                // Agregar a la lista
                const li = document.createElement('li');
                li.textContent = `${nombre} (x${cantidad}) - $${(precio * cantidad).toFixed(2)}`;
                listaProductos.appendChild(li);
                
                // Guardar para el formulario
                productosSeleccionados.push({
                    id: checkbox.value,
                    cantidad: cantidad
                });
            }
        });
        
        if (productosSeleccionados.length === 0) {
            listaProductos.innerHTML = '<li class="text-muted">No hay productos seleccionados</li>';
        }
        
        totalElement.textContent = total.toFixed(2);
    }

    function actualizarFormulario() {
        // Limpiar productos anteriores
        document.querySelectorAll('[name^="productos"]').forEach(input => input.remove());
        
        // Agregar productos seleccionados al formulario
        productosSeleccionados.forEach((producto, index) => {
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `productos[${index}][id]`;
            inputId.value = producto.id;
            form.appendChild(inputId);
            
            const inputCantidad = document.createElement('input');
            inputCantidad.type = 'hidden';
            inputCantidad.name = `productos[${index}][cantidad]`;
            inputCantidad.value = producto.cantidad;
            form.appendChild(inputCantidad);
        });
    }

    // Event listeners
    servicioSelect.addEventListener('change', function() {
        actualizarResumen();
        actualizarFormulario();
    });
    
    productoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const cantidadContainer = this.parentNode.parentNode.querySelector('.cantidad-container');
            const cantidadInput = cantidadContainer.querySelector('.cantidad-input');
            
            if (this.checked) {
                cantidadContainer.classList.remove('d-none');
                cantidadInput.disabled = false;
            } else {
                cantidadContainer.classList.add('d-none');
                cantidadInput.disabled = true;
                cantidadInput.value = 1; // Reset a 1 si se desmarca
            }
            actualizarResumen();
            actualizarFormulario();
        });
        
        const cantidadInput = checkbox.parentNode.parentNode.querySelector('.cantidad-input');
        cantidadInput.addEventListener('input', function() {
            const maxStock = parseInt(checkbox.dataset.stock);
            if (this.value > maxStock) {
                this.value = maxStock;
                alert(`Solo hay ${maxStock} unidades disponibles`);
            }
            actualizarResumen();
            actualizarFormulario();
        });
    });

    // Inicializar
    inicializarProductos();
    actualizarResumen();
});
</script>

<style>
.producto-item {
    transition: all 0.3s ease;
}
.producto-item:hover {
    background-color: #f8f9fa;
}
.cantidad-container {
    transition: all 0.3s ease;
}
.total-container {
    border-top: 1px solid #dee2e6;
    padding-top: 10px;
}
.productos-list {
    max-height: 400px;
    overflow-y: auto;
}
</style>
@endsection