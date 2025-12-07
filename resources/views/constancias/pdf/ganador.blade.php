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
            padding: 50px 70px;
            position: relative;
        }

        /* Línea superior dorada para ganadores */
        .top-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(90deg, #d97706 0%, #f59e0b 100%);
        }

        /* Header simple */
        .header {
            margin-bottom: 30px;
        }

        .tec-label {
            font-size: 13px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
        }

        .institution {
            font-size: 9px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.5;
            margin-bottom: 1px;
        }

        .divider-line {
            width: 100%;
            height: 1px;
            background: #d0d0d0;
            margin: 25px 0 40px 0;
        }

        /* Award badge */
        .award-badge {
            display: inline-block;
            background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            color: white;
            padding: 6px 20px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Content */
        .certifica-text {
            font-size: 11px;
            color: #666;
            margin-bottom: 25px;
        }

        .recipient-name {
            font-size: 50px;
            color: #d97706;
            font-weight: bold;
            margin: 15px 0;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: 0px;
        }

        .body-text {
            font-size: 12px;
            line-height: 1.9;
            color: #333;
            margin: 35px 0;
            max-width: 650px;
        }

        .event-name {
            font-weight: bold;
            color: #000;
        }

        .project-info {
            color: #d97706;
            font-weight: bold;
        }

        .role-name {
            font-weight: bold;
            color: #000;
        }

        /* Signatures - Estilo lista vertical centrada */
        .signatures {
            margin-top: 100px;
        }

        .signature {
            margin-bottom: 25px;
            text-align: center;
        }

        .signature-line {
            width: 280px;
            height: 1px;
            background: #000;
            margin: 0 auto 8px auto;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .signature-title {
            font-size: 9px;
            color: #666;
            line-height: 1.3;
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
        @if($proyecto)
            con el proyecto <span class="project-info">"{{ $proyecto->titulo }}"</span>
        @endif
        @if($perfilEquipo)
            con <span class="role-name">{{ $perfilEquipo->nombre }}</span>.
        @endif
    </div>

    <!-- Firmas - estilo vertical centrado -->
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
