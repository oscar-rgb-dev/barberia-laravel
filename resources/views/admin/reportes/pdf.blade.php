<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Citas - Barbería</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #333;
            font-size: 24px;
            margin: 0;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        
        .info-value {
            color: #212529;
        }
        
        .section-title {
            background: #343a40;
            color: white;
            padding: 8px 15px;
            font-size: 16px;
            margin: 25px 0 15px 0;
            border-radius: 3px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th {
            background: #495057;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        
        .table td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }
        
        .table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .badge-pendiente { background: #ffc107; color: #212529; }
        .badge-completada { background: #28a745; color: white; }
        .badge-confirmada { background: #17a2b8; color: white; }
        .badge-cancelada { background: #dc3545; color: white; }
        
        .stats-box {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            text-align: center;
        }
        
        .stat-item {
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            min-width: 150px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <div class="header">
        <h1>Reporte de Citas - Barbería</h1>
        <div class="subtitle">
            Generado el: {{ $fechaGeneracion->format('d/m/Y H:i') }}
        </div>
    </div>
    
    <!-- Información del Reporte -->
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Periodo:</span>
            <span class="info-value">
                {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Tipo de Reporte:</span>
            <span class="info-value">
                @switch($tipoReporte)
                    @case('dia')
                        Diario
                        @break
                    @case('semana')
                        Semanal
                        @break
                    @case('mes')
                        Mensual
                        @break
                    @case('año')
                        Anual
                        @break
                    @case('personalizado')
                        Personalizado
                        @break
                @endswitch
            </span>
        </div>
        @if($barberoSeleccionado)
        <div class="info-row">
            <span class="info-label">Barbero:</span>
            <span class="info-value">{{ $barberoSeleccionado->nombre }}</span>
        </div>
        @endif
    </div>
    
    <!-- Estadísticas Generales -->
    <div class="section-title">Estadísticas Generales</div>
    
    <div class="stats-box">
        <div class="stat-item">
            <div class="stat-value">{{ $totalCitas }}</div>
            <div class="stat-label">Total Citas</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $citasCompletadas }}</div>
            <div class="stat-label">Citas Completadas</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">${{ number_format($totalIngresos, 2) }}</div>
            <div class="stat-label">Ingresos Totales</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $citasCanceladas }}</div>
            <div class="stat-label">Citas Canceladas</div>
        </div>
    </div>
    
    <!-- Estadísticas por Barbero -->
    <div class="section-title">Estadísticas por Barbero</div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Barbero</th>
                <th>Total Citas</th>
                <th>Completadas</th>
                <th>Pendientes</th>
                <th>Canceladas</th>
                <th>Ingresos ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estadisticasBarberos as $estadistica)
            <tr>
                <td>{{ $estadistica['nombre'] }}</td>
                <td>{{ $estadistica['total_citas'] }}</td>
                <td>{{ $estadistica['citas_completadas'] }}</td>
                <td>{{ $estadistica['citas_pendientes'] }}</td>
                <td>{{ $estadistica['citas_canceladas'] }}</td>
                <td>${{ number_format($estadistica['ingresos'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Detalles de Citas -->
    <div class="section-title">Detalle de Citas ({{ $citas->count() }})</div>
    
    @if($citas->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha/Hora</th>
                    <th>Cliente</th>
                    <th>Barbero</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Total ($)</th>
                    <th>Productos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $cita)
                <tr>
                    <td>{{ $cita->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</td>
                    <td>{{ $cita->user->name ?? 'N/A' }}</td>
                    <td>{{ $cita->barbero->nombre ?? 'N/A' }}</td>
                    <td>{{ $cita->servicio->nombre ?? 'N/A' }}</td>
                    <td>
                        <span class="badge badge-{{ $cita->estado }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </td>
                    <td>${{ number_format($cita->total, 2) }}</td>
                    <td>
                        @if($cita->productos->count() > 0)
                            {{ $cita->productos->count() }} producto(s)
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #6c757d;">
            No hay citas en el periodo seleccionado
        </div>
    @endif
    
    <!-- Pie de página -->
    <div class="footer">
        <div>Reporte generado automáticamente por el sistema de Barbería</div>
        <div>Página 1 de 1</div>
    </div>
</body>
</html>