@extends('layouts.admin')

@section('title', 'Reporte de Satisfacción - Barbería')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header">
                    <h5 class="mb-0">Generar Reporte de Satisfacción</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reportes.satisfaccion.generar') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tipo" class="form-label">Periodo de Análisis</label>
                                    <select name="tipo" id="tipo" class="form-select" required>
                                        <option value="">Seleccionar periodo</option>
                                        <option value="dia">Hoy</option>
                                        <option value="semana">Esta Semana</option>
                                        <option value="mes">Este Mes</option>
                                        <option value="año">Este Año</option>
                                        <option value="personalizado">Rango Personalizado</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="barbero_id" class="form-label">Filtrar por Barbero (Opcional)</label>
                                    <select name="barbero_id" id="barbero_id" class="form-select">
                                        <option value="">Todos los barberos</option>
                                        @foreach($barberos as $barbero)
                                            <option value="{{ $barbero->id }}">{{ $barbero->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos para rango personalizado -->
                        <div class="row" id="rango-personalizado" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                                           value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                                           value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    Este reporte incluye únicamente citas <strong>completadas y calificadas</strong> dentro del periodo seleccionado.
                                    Se mostrará el promedio de satisfacción, distribución de calificaciones y comentarios de clientes.
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver al Dashboard
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-chart-bar"></i> Generar Reporte de Satisfacción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo');
    const rangoPersonalizado = document.getElementById('rango-personalizado');
    
    tipoSelect.addEventListener('change', function() {
        if (this.value === 'personalizado') {
            rangoPersonalizado.style.display = 'flex';
        } else {
            rangoPersonalizado.style.display = 'none';
        }
    });
    
    // Setear fechas por defecto
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    
    if (fechaInicio && fechaFin) {
        // Fecha inicio: hace 1 mes
        const hoy = new Date();
        const haceUnMes = new Date();
        haceUnMes.setMonth(hoy.getMonth() - 1);
        
        fechaInicio.valueAsDate = haceUnMes;
        fechaFin.valueAsDate = hoy;
    }
});
</script>
@endpush