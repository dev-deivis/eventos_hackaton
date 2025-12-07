<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #4F46E5;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .kpi-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .kpi-row {
            display: table-row;
        }
        .kpi-cell {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .kpi-label {
            color: #666;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .kpi-value {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #F3F4F6;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .chart-container {
            margin: 20px 0;
        }
        .chart-bar {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .chart-label {
            font-size: 11px;
            color: #1F2937;
            margin-bottom: 6px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chart-label-name {
            flex: 1;
        }
        .chart-label-value {
            font-size: 10px;
            color: #6B7280;
            font-weight: 500;
            margin-left: 10px;
        }
        .chart-bar-wrapper {
            position: relative;
            height: 32px;
            background: linear-gradient(to right, #F3F4F6 0%, #E5E7EB 100%);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .chart-bar-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 12px;
            color: white;
            font-size: 12px;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            transition: width 0.3s ease;
        }
        .chart-bar-fill.indigo {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #A855F7 100%);
        }
        .chart-bar-fill.pink {
            background: linear-gradient(135deg, #EC4899 0%, #DB2777 50%, #BE185D 100%);
        }
        .chart-bar-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(255,255,255,0.1) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.1) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.1) 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Reporte de Análisis</h1>
        @if($evento)
            <p><strong>Evento:</strong> {{ $evento->nombre }}</p>
        @else
            <p><strong>Reporte Global</strong> - Todos los eventos</p>
        @endif
        <p><strong>Fecha de generación:</strong> {{ $fecha }}</p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="section">
        <div class="section-title">Estadísticas Generales</div>
        <div class="kpi-grid">
            <div class="kpi-row">
                <div class="kpi-cell">
                    <div class="kpi-label">Total Participantes</div>
                    <div class="kpi-value">{{ $stats['total_participantes'] }}</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-label">Equipos Formados</div>
                    <div class="kpi-value">{{ $stats['equipos_formados'] }}</div>
                </div>
            </div>
            <div class="kpi-row">
                <div class="kpi-cell">
                    <div class="kpi-label">Tasa de Finalización</div>
                    <div class="kpi-value">{{ $stats['tasa_finalizacion'] }}%</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-label">Puntuación Promedio</div>
                    <div class="kpi-value">{{ $stats['puntuacion_promedio'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Participación por Carrera -->
    <div class="section">
        <div class="section-title">Participación por Carrera</div>
        <div class="chart-container">
            @forelse($participacion_carrera as $item)
                <div class="chart-bar">
                    <div class="chart-label">
                        <span class="chart-label-name">{{ $item['carrera'] }}</span>
                        <span class="chart-label-value">{{ $item['total'] }} estudiantes</span>
                    </div>
                    <div class="chart-bar-wrapper">
                        <div class="chart-bar-fill indigo" style="width: {{ $item['porcentaje'] }}%">
                            <div class="chart-bar-pattern"></div>
                            <span style="position: relative; z-index: 1;">{{ $item['porcentaje'] }}%</span>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #999; padding: 20px;">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>

    <!-- Distribución de Roles -->
    <div class="section">
        <div class="section-title">Distribución de Roles</div>
        <div class="chart-container">
            @forelse($distribucion_roles as $item)
                <div class="chart-bar">
                    <div class="chart-label">
                        <span class="chart-label-name">{{ $item['rol'] }}</span>
                        <span class="chart-label-value">{{ $item['total'] }} asignaciones</span>
                    </div>
                    <div class="chart-bar-wrapper">
                        <div class="chart-bar-fill pink" style="width: {{ $item['porcentaje'] }}%">
                            <div class="chart-bar-pattern"></div>
                            <span style="position: relative; z-index: 1;">{{ $item['porcentaje'] }}%</span>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #999; padding: 20px;">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema de Gestión de Hackathones</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
