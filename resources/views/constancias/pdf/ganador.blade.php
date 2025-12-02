<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de {{ ucfirst(str_replace('_', ' ', $constancia->tipo)) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px;
            color: #333;
        }

        .container {
            background: white;
            padding: 60px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #f5576c;
            padding-bottom: 20px;
        }

        .header-text {
            text-align: center;
            flex: 1;
        }

        .institution {
            font-size: 14px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .award-badge {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            padding: 20px 40px;
            border-radius: 50px;
            font-size: 28px;
            font-weight: bold;
            display: inline-block;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(255, 165, 0, 0.4);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title {
            font-size: 36px;
            color: #f5576c;
            margin: 30px 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .recipient-name {
            font-size: 42px;
            color: #333;
            font-weight: bold;
            margin: 30px 0;
            text-transform: uppercase;
            border-bottom: 3px solid #f5576c;
            padding-bottom: 10px;
            display: inline-block;
        }

        .body-text {
            font-size: 16px;
            line-height: 2;
            color: #555;
            margin: 30px 0;
            text-align: justify;
        }

        .event-name {
            font-weight: bold;
            color: #f5576c;
            font-size: 20px;
        }

        .team-info {
            background: #fff5f7;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 4px solid #f5576c;
        }

        .team-info p {
            margin: 8px 0;
            font-size: 14px;
            color: #555;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
            padding-top: 40px;
        }

        .signature {
            text-align: center;
            flex: 1;
        }

        .signature-line {
            width: 200px;
            height: 2px;
            background: #333;
            margin: 10px auto 5px;
        }

        .signature-name {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            font-size: 10px;
            color: #999;
        }

        .verification-code {
            font-size: 12px;
            color: #f5576c;
            font-weight: bold;
            margin-top: 10px;
        }

        .date {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-text">
                <div class="institution">Instituto Tecnológico Nacional de México</div>
                <div class="institution" style="font-size: 12px; margin-top: 5px;">Campus Oaxaca</div>
            </div>
        </div>

        <!-- Award Badge -->
        <div class="award-badge">
            @if($constancia->tipo === 'primer_lugar')
                🥇 PRIMER LUGAR
            @elseif($constancia->tipo === 'segundo_lugar')
                🥈 SEGUNDO LUGAR
            @elseif($constancia->tipo === 'tercer_lugar')
                🥉 TERCER LUGAR
            @endif
        </div>

        <!-- Title -->
        <div class="title">Constancia de Reconocimiento</div>
        
        <div class="subtitle">El Instituto Tecnológico Nacional de México otorga la presente</div>

        <!-- Recipient Name -->
        <div class="recipient-name">{{ strtoupper($user->name) }}</div>

        <!-- Body Text -->
        <div class="body-text">
            Por haber obtenido el 
            <strong style="color: #f5576c;">
                @if($constancia->tipo === 'primer_lugar')
                    PRIMER LUGAR
                @elseif($constancia->tipo === 'segundo_lugar')
                    SEGUNDO LUGAR
                @elseif($constancia->tipo === 'tercer_lugar')
                    TERCER LUGAR
                @endif
            </strong>
            en el evento
            <span class="event-name">"{{ $evento->nombre }}"</span>
            @if($participante && $participante->carrera)
                como estudiante de la carrera de <strong>{{ $participante->carrera->nombre }}</strong>
            @endif
            realizado el {{ $evento->fecha_inicio->format('d') }} de {{ $evento->fecha_inicio->translatedFormat('F') }} de {{ $evento->fecha_inicio->format('Y') }}.
        </div>

        <!-- Team Info (if exists) -->
        @if($equipo)
        <div class="team-info">
            <p><strong>Equipo:</strong> {{ $equipo->nombre }}</p>
            @if($proyecto)
                <p><strong>Proyecto Ganador:</strong> {{ $proyecto->titulo }}</p>
                @if($proyecto->descripcion)
                    <p style="font-size: 12px; margin-top: 10px;"><em>{{ $proyecto->descripcion }}</em></p>
                @endif
            @endif
            @if($perfilEquipo)
                <p><strong>Rol en el equipo:</strong> {{ $perfilEquipo->nombre }}</p>
            @endif
        </div>
        @endif

        <!-- Date -->
        <div class="date">
            Oaxaca de Juárez, Oaxaca a {{ $constancia->fecha_emision->format('d') }} de {{ $constancia->fecha_emision->translatedFormat('F') }} de {{ $constancia->fecha_emision->format('Y') }}
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">Director del Plantel</div>
                <div class="signature-title">Instituto Tecnológico de Oaxaca</div>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">Coordinador del Evento</div>
                <div class="signature-title">{{ $evento->nombre }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="verification-code">
                Código de verificación: {{ $constancia->codigo_verificacion ?? $constancia->codigo_qr ?? 'N/A' }}
            </div>
            <p style="margin-top: 10px;">Este documento es válido y puede ser verificado en línea</p>
        </div>
    </div>
</body>
</html>
