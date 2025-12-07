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
        <table>
            <thead>
                <tr>
                    <th>Carrera</th>
                    <th style="text-align: center; width: 100px;">Total</th>
                    <th style="text-align: center; width: 100px;">Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participacion_carrera as $item)
                    <tr>
                        <td>{{ $item['carrera'] }}</td>
                        <td style="text-align: center;">{{ $item['total'] }}</td>
                        <td style="text-align: center;">{{ $item['porcentaje'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999;">No hay datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Distribución de Roles -->
    <div class="section">
        <div class="section-title">Distribución de Roles</div>
        <table>
            <thead>
                <tr>
                    <th>Rol</th>
                    <th style="text-align: center; width: 100px;">Total</th>
                    <th style="text-align: center; width: 100px;">Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distribucion_roles as $item)
                    <tr>
                        <td>{{ $item['rol'] }}</td>
                        <td style="text-align: center;">{{ $item['total'] }}</td>
                        <td style="text-align: center;">{{ $item['porcentaje'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999;">No hay datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema de Gestión de Hackathones</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
