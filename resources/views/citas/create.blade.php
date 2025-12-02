@extends('layouts.app')

@section('title', 'Agendar Cita - Barbería')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Agendar Nueva Cita</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('citas.store') }}" method="POST" id="citaForm">
                        @csrf
                        
                        <!-- Servicio y Barbero -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_servicio" class="form-label">Servicio *</label>
                                    <select class="form-control" id="id_servicio" name="id_servicio" required>
                                        <option value="">Seleccionar servicio</option>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" data-precio="{{ $servicio->costo }}">
                                                {{ $servicio->nombre }} - ${{ number_format($servicio->costo, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="id_barbero" class="form-label">Barbero *</label>
                                    <select class="form-control" id="id_barbero" name="id_barbero" required>
                                        <option value="">Seleccionar barbero</option>
                                        @foreach($empleados as $empleado)
                                            <option value="{{ $empleado->id }}" 
                                                    data-jornada-id="{{ $empleado->jornada_id }}">
                                                {{ $empleado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fecha" class="form-label">Fecha *</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" 
                                           min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="hora" class="form-label">Hora *</label>
                                    <select class="form-control" id="hora" name="hora" required disabled>
                                        <option value="">Primero selecciona barbero y fecha</option>
                                    </select>
                                    <small class="form-text text-muted" id="horario-info">
                                        Los horarios disponibles se mostrarán después de seleccionar barbero y fecha
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Resto del formulario se mantiene igual -->
                        <!-- Productos -->
                        <div class="form-group mb-3">
                            <label class="form-label">Productos (Opcional)</label>
                            <div class="productos-list">
                                @foreach($productos as $producto)
                                    <div class="producto-item mb-2 p-3 border rounded">
                                        <div class="form-check">
                                            <input class="form-check-input producto-checkbox" 
                                                   type="checkbox" 
                                                   value="{{ $producto->id }}"
                                                   id="producto{{ $producto->id }}"
                                                   data-precio="{{ $producto->costo }}"
                                                   data-nombre="{{ $producto->nombre }}"
                                                   data-stock="{{ $producto->stock }}">
                                            <label class="form-check-label" for="producto{{ $producto->id }}">
                                                <strong>{{ $producto->nombre }}</strong> - 
                                                ${{ number_format($producto->costo, 2) }}
                                                <small class="text-muted">(Stock: {{ $producto->stock }})</small>
                                            </label>
                                        </div>
                                        <div class="cantidad-container mt-2" style="display: none;">
                                            <label class="form-label">Cantidad:</label>
                                            <input type="number" 
                                                   class="form-control cantidad-input" 
                                                   value="1" 
                                                   min="1" 
                                                   max="{{ $producto->stock }}"
                                                   style="width: 100px;"
                                                   disabled>
                                        </div>
                                    </div>
                                @endforeach>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Resumen de la Cita</h5>
                            </div>
                            <div class="card-body">
                                <div id="resumen-servicio" class="mb-2">
                                    <strong>Servicio:</strong> <span id="servicio-seleccionado">-</span>
                                </div>
                                <div id="resumen-productos" class="mb-2">
                                    <strong>Productos:</strong> 
                                    <ul id="lista-productos" class="mb-0"></ul>
                                </div>
                                <div class="total-container">
                                    <strong>Total: $<span id="total-cita">0.00</span></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="form-group mb-3">
                            <label for="notas" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3" 
                                      placeholder="Especificaciones adicionales..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-gold">
                                <i class="fas fa-calendar-plus"></i> Agendar Cita
                            </button>
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
    const barberoSelect = document.getElementById('id_barbero');
    const fechaInput = document.getElementById('fecha');
    const horaSelect = document.getElementById('hora');
    const horarioInfo = document.getElementById('horario-info');
    const productoCheckboxes = document.querySelectorAll('.producto-checkbox');
    const totalElement = document.getElementById('total-cita');
    const servicioSeleccionado = document.getElementById('servicio-seleccionado');
    const listaProductos = document.getElementById('lista-productos');
    const form = document.getElementById('citaForm');

    let productosSeleccionados = [];
    let barberoActual = null;

    // Event listener para cambio de barbero
    barberoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            barberoActual = {
                id: selectedOption.value,
                jornadaId: selectedOption.dataset.jornadaId
            };
            habilitarFecha();
        } else {
            barberoActual = null;
            horaSelect.disabled = true;
            horaSelect.innerHTML = '<option value="">Primero selecciona barbero y fecha</option>';
            horarioInfo.textContent = 'Los horarios disponibles se mostrarán después de seleccionar barbero y fecha';
        }
    });

    // Event listener para cambio de fecha
    fechaInput.addEventListener('change', function() {
        if (barberoActual && this.value) {
            cargarHorariosDisponibles();
        }
    });

    function habilitarFecha() {
        fechaInput.disabled = !barberoActual;
        if (!barberoActual) {
            fechaInput.value = '';
            horaSelect.disabled = true;
            horaSelect.innerHTML = '<option value="">Primero selecciona barbero y fecha</option>';
        } else {
            // Obtener información del horario del barbero
            fetch(`/citas/info-jornada?barbero_id=${barberoActual.id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        horarioInfo.textContent = `Horario: ${data.hora_inicio} - ${data.hora_fin} (Citas cada ${data.duracion_cita} min)`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    horarioInfo.textContent = 'Horario no disponible';
                });
        }
    }

    function cargarHorariosDisponibles() {
        if (!barberoActual || !fechaInput.value) return;

        horaSelect.disabled = true;
        horaSelect.innerHTML = '<option value="">Cargando horarios disponibles...</option>';

        // Hacer petición AJAX para obtener horarios disponibles
        fetch(`/citas/horarios-disponibles?barbero_id=${barberoActual.id}&fecha=${fechaInput.value}`)
            .then(response => response.json())
            .then(data => {
                horaSelect.innerHTML = '';
                
                if (data.success && data.horarios && data.horarios.length > 0) {
                    data.horarios.forEach(horario => {
                        const option = document.createElement('option');
                        option.value = horario;
                        option.textContent = horario;
                        horaSelect.appendChild(option);
                    });
                    horaSelect.disabled = false;
                    horarioInfo.textContent = `Horarios disponibles: ${data.horarios.length} opciones | ${data.info}`;
                } else {
                    horaSelect.innerHTML = '<option value="">No hay horarios disponibles para esta fecha</option>';
                    horarioInfo.textContent = data.message || 'No hay horarios disponibles para esta fecha. Por favor selecciona otra fecha.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                horarioInfo.textContent = 'Error al cargar horarios disponibles';
            });
    }

    function actualizarResumen() {
        let total = 0;
        
        // Servicio
        if (servicioSelect.value) {
            const precioServicio = parseFloat(servicioSelect.selectedOptions[0].dataset.precio);
            total += precioServicio;
            servicioSeleccionado.textContent = servicioSelect.selectedOptions[0].textContent.split(' - ')[0];
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

    // Event listeners para servicio y productos (se mantienen igual)
    servicioSelect.addEventListener('change', function() {
        actualizarResumen();
        actualizarFormulario();
    });
    
    productoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const cantidadContainer = this.parentNode.parentNode.querySelector('.cantidad-container');
            const cantidadInput = cantidadContainer.querySelector('.cantidad-input');
            
            if (this.checked) {
                cantidadContainer.style.display = 'block';
                cantidadInput.disabled = false;
            } else {
                cantidadContainer.style.display = 'none';
                cantidadInput.disabled = true;
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
    font-size: 1.2em;
    color: #28a745;
    font-weight: bold;
    border-top: 1px solid #dee2e6;
    padding-top: 10px;
    margin-top: 10px;
}
</style>
@endsection