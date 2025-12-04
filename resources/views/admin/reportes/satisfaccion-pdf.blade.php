<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Satisfacción - Barbería</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ffc107;
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
            background: #fff8e1;
            border: 1px solid #ffeaa7;
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
            color: #e65100;
        }
        
        .info-value {
            color: #212529;
        }
        
        .section-title {
            background: linear-gradient(45deg, #ff9800, #ffc107);
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
            background: #f57c00;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ff9800;
        }
        
        .table td {
            padding: 8px;
            border: 1px solid #ffeaa7;
        }
        
        .table tr:nth-child(even) {
            background: #fff8e1;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: white;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card-primary {
            background: linear-gradient(45deg, #ff9800, #ffc107);
            color: white;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
        }
        
        .stat-card-primary .stat-label {
            color: rgba(255,255,255,0.9);
        }
        
        .rating-stars {
            color: #ffc107;
            font-size: 14px;
            letter-spacing: 2px;
        }
        
        .rating-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .rating-5 { background: #4caf50; color: white; }
        .rating-4 { background: #8bc34a; color: white; }
        .rating-3 { background: #ffc107; color: #212529; }
        .rating-2 { background: #ff9800; color: white; }
        .rating-1 { background: #f44336; color: white; }
        
        .distribution-bar {
            height: 20px;
            background: #f5f5f5;
            border-radius: 10px;
            margin: 5px 0;
            overflow: hidden;
        }
        
        .distribution-fill {
            height: 100%;
            border-radius: 10px;
        }
        
        .distribution-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-top: 2px;
        }
        
        .comment-box {
            background: #f9f9f9;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
            font-size: 11px;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            color: #666;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6c757d;
            font-size: 10px;
            border-top: 1px solid #ffeaa7;
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
        <h1>Reporte de Satisfacción del Cliente</h1>
        <div class="subtitle">
            Generado el: {{ $fechaGeneracion->format('d/m/Y H:i') }}
        </div>
    </div>
    
    <!-- Información del Reporte -->
    <div class="info-box">
        <div class="info-row">
            <span class="info-label">Periodo Analizado:</span>
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
            <span class="info-label">Barbero Analizado:</span>
            <span class="info-value">{{ $barberoSeleccionado->nombre }}</span>
        </div>
        @endif
    </div>
    
    <!-- Estadísticas Generales -->
    <div class="section-title">Resumen General</div>
    
    <div class="stats-grid">
        <div class="stat-card stat-card-primary">
            <div class="stat-value">{{ number_format($estadisticas['promedio'], 1) }}/5.0</div>
            <div class="stat-label">Calificación Promedio</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $estadisticas['total_citas'] }}</div>
            <div class="stat-label">Citas Calificadas</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">{{ $estadisticas['citas_con_comentarios'] }}</div>
            <div class="stat-label">Con Comentarios</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-value">
                <span class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= round($estadisticas['promedio']))
                            ★
                        @else
                            ☆
                        @endif
                    @endfor
                </span>
            </div>
            <div class="stat-label">Nivel de Satisfacción</div>
        </div>
    </div>
    
    <!-- Distribución de Calificaciones -->
    <div class="section-title">Distribución de Calificaciones</div>
    
    <table class="table">
        <thead>
            <tr>
                <th>Calificación</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Porcentaje</th>
                <th>Distribución</th>
            </tr>
        </thead>
        <tbody>
            @php
                $descripciones = [
                    1 => 'Muy Malo',
                    2 => 'Malo',
                    3 => 'Regular',
                    4 => 'Bueno',
                    5 => 'Excelente'
                ];
            @endphp
            
            @for($i = 5; $i >= 1; $i--)
            <tr>
                <td>
                    <span class="rating-badge rating-{{ $i }}">
                        {{ $i }} Estrella(s)
                    </span>
                </td>
                <td>{{ $descripciones[$i] }}</td>
                <td>{{ $estadisticas['distribucion'][$i] }}</td>
                <td>{{ number_format($estadisticas['porcentajes'][$i], 1) }}%</td>
                <td style="width: 40%;">
                    <div class="distribution-bar">
                        <div class="distribution-fill" style="width: {{ $estadisticas['porcentajes'][$i] }}%; 
                             background: @switch($i)
                                @case(5) #4caf50 @break
                                @case(4) #8bc34a @break
                                @case(3) #ffc107 @break
                                @case(2) #ff9800 @break
                                @case(1) #f44336 @break
                             @endswitch;">
                        </div>
                    </div>
                    <div class="distribution-label">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                </td>
            </tr>
            @endfor
        </tbody>
    </table>
    
    <!-- Estadísticas por Barbero -->
    <div class="section-title">Rendimiento por Barbero</div>
    
    @if(count($estadisticasBarberos) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Barbero</th>
                    <th>Citas Calificadas</th>
                    <th>Calificación Promedio</th>
                    <th>Distribución (5★ → 1★)</th>
                    <th>Comentarios</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estadisticasBarberos as $barbero)
                <tr>
                    <td><strong>{{ $barbero['nombre'] }}</strong></td>
                    <td>{{ $barbero['total_calificaciones'] }}</td>
                    <td>
                        <div class="rating-stars">
                            {{ str_repeat('★', round($barbero['promedio'])) }}{{ str_repeat('☆', 5 - round($barbero['promedio'])) }}
                        </div>
                        <div>{{ number_format($barbero['promedio'], 1) }}/5.0</div>
                    </td>
                    <td>
                        @for($i = 5; $i >= 1; $i--)
                            <div style="display: flex; align-items: center; margin: 2px 0;">
                                <span style="width: 20px; text-align: center;">{{ $i }}★</span>
                                <div style="flex-grow: 1; height: 10px; background: #f0f0f0; margin: 0 10px; border-radius: 5px;">
                                    <div style="height: 100%; width: {{ ($barbero['distribucion'][$i] / $barbero['total_calificaciones']) * 100 }}%; 
                                         background: @switch($i)
                                            @case(5) #4caf50 @break
                                            @case(4) #8bc34a @break
                                            @case(3) #ffc107 @break
                                            @case(2) #ff9800 @break
                                            @case(1) #f44336 @break
                                         @endswitch;
                                         border-radius: 5px;">
                                    </div>
                                </div>
                                <span style="width: 30px; text-align: right; font-size: 10px;">
                                    {{ $barbero['distribucion'][$i] }}
                                </span>
                            </div>
                        @endfor
                    </td>
                    <td>{{ count($barbero['comentarios']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 20px; color: #6c757d;">
            No hay datos de calificaciones en el periodo seleccionado
        </div>
    @endif
    
    <!-- Comentarios Destacados -->
    @if($citas->whereNotNull('comentario')->count() > 0)
        <div class="section-title">Comentarios de Clientes</div>
        
        @foreach($citas->whereNotNull('comentario')->take(10) as $cita)
            <div class="comment-box">
                <div class="comment-header">
                    <span>
                        <strong>{{ $cita->user->name ?? 'Cliente' }}</strong> 
                        • {{ $cita->barbero->nombre ?? 'N/A' }}
                    </span>
                    <span>
                        {{ $cita->calificado_en->format('d/m/Y') }}
                        • 
                        <span class="rating-stars">
                            {{ str_repeat('★', $cita->calificacion) }}{{ str_repeat('☆', 5 - $cita->calificacion) }}
                        </span>
                    </span>
                </div>
                <div>{{ $cita->comentario }}</div>
            </div>
        @endforeach
        
        @if($citas->whereNotNull('comentario')->count() > 10)
            <div style="text-align: center; padding: 10px; color: #666; font-style: italic;">
                ... y {{ $citas->whereNotNull('comentario')->count() - 10 }} comentarios más
            </div>
        @endif
    @endif
    
    <!-- Recomendaciones -->
    @if($estadisticas['total_citas'] > 0)
        <div class="section-title">Análisis y Recomendaciones</div>
        
        <div style="padding: 15px; background: #f8f9fa; border-radius: 5px; margin-bottom: 20px;">
            <h4 style="margin-top: 0; color: #333;">Resumen Ejecutivo</h4>
            
            @if($estadisticas['promedio'] >= 4.5)
                <p><strong>✅ Excelente desempeño:</strong> Los clientes están muy satisfechos con el servicio.</p>
                <p>Recomendación: Mantener el nivel de calidad y considerar programas de fidelización.</p>
            @elseif($estadisticas['promedio'] >= 4.0)
                <p><strong>✅ Buen desempeño:</strong> Satisfacción general positiva.</p>
                <p>Recomendación: Identificar áreas de mejora en los servicios con calificación menor a 4.</p>
            @elseif($estadisticas['promedio'] >= 3.0)
                <p><strong>⚠️ Oportunidad de mejora:</strong> Satisfacción aceptable pero con espacio para mejorar.</p>
                <p>Recomendación: Revisar procesos y capacitación del personal.</p>
            @else
                <p><strong>❌ Atención requerida:</strong> Necesita mejora significativa.</p>
                <p>Recomendación: Revisión completa de servicios, procesos y capacitación del personal.</p>
            @endif
            
            @if($estadisticas['distribucion'][1] + $estadisticas['distribucion'][2] > 0)
                <p><strong>Área de atención:</strong> {{ $estadisticas['distribucion'][1] + $estadisticas['distribucion'][2] }} calificaciones bajas requieren investigación.</p>
            @endif
            
            <p><strong>Tasa de comentarios:</strong> {{ number_format(($estadisticas['citas_con_comentarios'] / $estadisticas['total_citas']) * 100, 1) }}% de los clientes dejaron comentarios.</p>
        </div>
    @endif
    
    <!-- Pie de página -->
    <div class="footer">
        <div>Reporte de Satisfacción del Cliente - Sistema Barbería</div>
        <div>Generado automáticamente • Página 1 de 1</div>
    </div>
</body>
</html>