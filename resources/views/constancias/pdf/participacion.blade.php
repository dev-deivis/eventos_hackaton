<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Participación</title>
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
            color: #333;
            position: relative;
            width: 210mm;
            height: 297mm;
            padding: 40px 50px;
        }

        /* Gradiente decorativo en la esquina */
        .gradient-decoration {
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #6366f1 100%);
            border-radius: 0 0 0 100%;
            opacity: 0.15;
            z-index: 0;
        }

        .gradient-shape {
            position: absolute;
            top: 120px;
            right: 60px;
            width: 280px;
            height: 280px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(118, 75, 162, 0.3) 100%);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 10;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Header con logos */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        .logo-left {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header-center {
            flex: 1;
            text-align: left;
            padding: 0 20px;
        }

        .institution {
            font-size: 11px;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        .logo-right {
            width: 180px;
            height: auto;
            object-fit: contain;
        }

        /* Content */
        .content {
            flex: 1;
            padding-top: 20px;
        }

        .certifica-text {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .recipient-name {
            font-size: 52px;
            color: #4f46e5;
            font-weight: bold;
            margin: 20px 0;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .divider {
            width: 350px;
            height: 2px;
            background: #e5e7eb;
            margin: 15px 0;
        }

        .body-text {
            font-size: 13px;
            line-height: 1.8;
            color: #4b5563;
            margin: 25px 0;
            max-width: 600px;
        }

        .event-name {
            font-weight: bold;
            color: #1f2937;
        }

        .team-name {
            font-style: italic;
            color: #4f46e5;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: auto;
            padding-top: 60px;
            gap: 15px;
        }

        .signature {
            text-align: center;
            flex: 1;
            max-width: 200px;
        }

        .signature-line {
            width: 100%;
            height: 1.5px;
            background: #9ca3af;
            margin-bottom: 5px;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
            color: #1f2937;
            line-height: 1.3;
            margin-bottom: 2px;
        }

        .signature-title {
            font-size: 8px;
            color: #6b7280;
            line-height: 1.2;
        }
    </style>
</head>
<body>
    <!-- Gradientes decorativos -->
    <div class="gradient-decoration"></div>
    <div class="gradient-shape"></div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <!-- Logo TecNM (izquierda) -->
            <div>
                <svg class="logo-left" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" fill="#1e3a8a" stroke="#1e40af" stroke-width="2"/>
                    <text x="50" y="60" font-family="Arial" font-size="35" font-weight="bold" fill="white" text-anchor="middle">TEC</text>
                </svg>
            </div>

            <!-- Texto central -->
            <div class="header-center">
                <div class="institution">Tecnológico Nacional de México</div>
                <div class="institution" style="margin-top: 3px;">Campus Oaxaca otorgan el presente</div>
            </div>

            <!-- Logo SEP (derecha) -->
            <div>
                <svg class="logo-right" viewBox="0 0 200 50" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="25" cy="25" r="20" fill="#8b4513"/>
                    <text x="55" y="20" font-family="Arial" font-size="18" font-weight="bold" fill="#8b4513">EDUCACIÓN</text>
                    <text x="55" y="35" font-family="Arial" font-size="10" fill="#666">SECRETARÍA DE EDUCACIÓN PÚBLICA</text>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="certifica-text">Certificado de participación a</div>

            <!-- Nombre del participante -->
            <div class="recipient-name">
                {{ strtoupper($user->name) }}
            </div>

            <div class="divider"></div>

            <!-- Texto del cuerpo -->
            <div class="body-text">
                por haber participado en el evento <span class="event-name">{{ $evento->nombre }}</span>
                @if($equipo)
                    con el proyecto <span class="team-name">"{{ $proyecto->titulo ?? $equipo->nombre }}"</span>
                @endif
                @if($participante && $participante->carrera)
                    con <strong>{{ $perfilEquipo->nombre ?? 'Participante' }}</strong>.
                @endif
            </div>
        </div>

        <!-- Firmas -->
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
                <div class="signature-title">Subdirectora de Planeación y<br>Vinculación</div>
            </div>

            <div class="signature">
                <div class="signature-line"></div>
                <div class="signature-name">Ing. Huitziil Díaz Jaimes</div>
                <div class="signature-title">Jefa del Depto. de Servicios<br>Escolares</div>
            </div>
        </div>
    </div>
</body>
</html>
