<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de {{ ucfirst(str_replace('_', ' ', $constancia->tipo)) }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: white;
            color: #000;
            width: 210mm;
            height: 297mm;
            padding: 50px 60px;
            position: relative;
        }

        /* Línea superior dorada para ganadores */
        .top-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: #d97706;
        }

        /* Header simple */
        .header {
            margin-bottom: 40px;
        }

        .tec-label {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .institution {
            font-size: 10px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .divider-line {
            width: 100%;
            height: 1px;
            background: #ccc;
            margin: 20px 0;
        }

        /* Award badge */
        .award-badge {
            display: inline-block;
            background: #d97706;
            color: white;
            padding: 8px 25px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Content */
        .certifica-text {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
            margin-top: 40px;
        }

        .recipient-name {
            font-size: 48px;
            color: #d97706;
            font-weight: bold;
            margin: 20px 0;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .body-text {
            font-size: 13px;
            line-height: 1.8;
            color: #333;
            margin: 30px 0;
        }

        .event-name {
            font-weight: bold;
            color: #000;
        }

        .project-name {
            color: #d97706;
            font-style: italic;
            font-weight: bold;
        }

        .role-name {
            font-weight: bold;
            color: #000;
        }

        /* Signatures - Estilo lista vertical */
        .signatures {
            margin-top: 80px;
        }

        .signature {
            margin-bottom: 20px;
            text-align: center;
        }

        .signature-line {
            width: 250px;
            height: 1px;
            background: #000;
            margin: 0 auto 5px auto;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            line-height: 1.3;
        }

        .signature-title {
            font-size: 9px;
            color: #666;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <!-- Línea superior dorada -->
    <div class="top-border"></div>

    <!-- Header -->
    <div class="header">
        <div class="tec-label">TEC</div>
        <div class="institution">TECNOLÓGICO NACIONAL DE MÉXICO</div>
        <div class="institution">CAMPUS OAXACA OTORGAN EL PRESENTE</div>
        <div class="institution">EDUCACIÓN SECRETARÍA DE EDUCACIÓN PÚBLICA</div>
    </div>

    <div class="divider-line"></div>

    <!-- Award badge -->
    <div class="award-badge">
        @if($constancia->tipo == 'primer_lugar')
            🥇 PRIMER LUGAR
        @elseif($constancia->tipo == 'segundo_lugar')
            🥈 SEGUNDO LUGAR
        @elseif($constancia->tipo == 'tercer_lugar')
            🥉 TERCER LUGAR
        @else
            {{ strtoupper(str_replace('_', ' ', $constancia->tipo)) }}
        @endif
    </div>

    <!-- Content -->
    <div class="certifica-text">Certificado de reconocimiento a</div>

    <!-- Nombre del participante -->
    <div class="recipient-name">
        {{ strtoupper($user->name) }}
    </div>

    <!-- Texto del cuerpo -->
    <div class="body-text">
        por haber obtenido el
        <strong>
            @if($constancia->tipo == 'primer_lugar')
                PRIMER LUGAR
            @elseif($constancia->tipo == 'segundo_lugar')
                SEGUNDO LUGAR
            @elseif($constancia->tipo == 'tercer_lugar')
                TERCER LUGAR
            @else
                {{ strtoupper(str_replace('_', ' ', $constancia->tipo)) }}
            @endif
        </strong>
        en el evento <span class="event-name">{{ $evento->nombre }}</span>
        @if($equipo && $proyecto)
            con el proyecto <span class="project-name">"{{ $proyecto->titulo }}"</span> con
        @endif
        @if($perfilEquipo)
            <span class="role-name">{{ $perfilEquipo->nombre }}</span>.
        @endif
    </div>

    <!-- Firmas - estilo vertical -->
    <div class="signatures">
        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">M.C. Silvia Santiago Cruz</div>
            <div class="signature-title">Directora</div>
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">Dra. Alma Dolores Pérez Santiago</div>
            <div class="signature-title">Subdirectora Académica</div>
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">Dra. Marisol Altamirano Cabrera</div>
            <div class="signature-title">Subdirectora de Planeación y Vinculación</div>
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <div class="signature-name">Ing. Huitziil Díaz Jaimes</div>
            <div class="signature-title">Jefa del Depto. de Servicios Escolares</div>
        </div>
    </div>
</body>
</html>
