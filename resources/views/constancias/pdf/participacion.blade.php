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
            color: #000;
            width: 210mm;
            height: 297mm;
            padding: 40px 50px;
            position: relative;
            overflow: hidden;
        }

        /* Gradiente decorativo grande en la esquina derecha */
        .gradient-decoration {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            opacity: 0.15;
            z-index: 0;
        }

        /* Estrella decorativa grande */
        .star-decoration {
            position: absolute;
            top: 150px;
            right: 80px;
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.25) 0%, rgba(118, 75, 162, 0.25) 100%);
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 10;
        }

        /* Header con logos */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .logo-left {
            width: 70px;
            height: auto;
        }

        .header-text {
            flex: 1;
            text-align: left;
            padding: 0 30px;
        }

        .institution {
            font-size: 10px;
            color: #374151;
            line-height: 1.5;
        }

        .logo-right {
            width: 150px;
            height: auto;
        }

        .divider-line {
            width: 100%;
            height: 1px;
            background: #d1d5db;
            margin: 15px 0 40px 0;
        }

        /* Content */
        .certifica-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .recipient-name {
            font-size: 56px;
            color: #5b21b6;
            font-weight: bold;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin-bottom: 30px;
        }

        .body-text {
            font-size: 12px;
            line-height: 1.8;
            color: #374151;
            max-width: 500px;
            margin-bottom: 60px;
        }

        .event-name {
            font-weight: bold;
            color: #1f2937;
        }

        .project-info {
            color: #5b21b6;
        }

        .role-name {
            font-weight: bold;
        }

        /* Signatures - Grid 2x2 */
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px 60px;
            margin-top: 80px;
            max-width: 700px;
        }

        .signature {
            text-align: center;
        }

        .signature-line {
            width: 100%;
            height: 1px;
            background: #000;
            margin-bottom: 8px;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            line-height: 1.3;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 9px;
            color: #6b7280;
            line-height: 1.3;
        }
    </style>
</head>
<body>
    <!-- Decoraciones de fondo -->
    <div class="gradient-decoration"></div>
    <div class="star-decoration"></div>

    <div class="container">
        <!-- Header con logos -->
        <div class="header">
            <!-- Logo TecNM -->
            <div>
                <svg class="logo-left" viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg">
                    <!-- Escudo TecNM simplificado -->
                    <circle cx="50" cy="50" r="45" fill="#1e3a8a"/>
                    <circle cx="50" cy="50" r="35" fill="#2563eb"/>
                    <text x="50" y="60" font-family="Arial" font-size="20" font-weight="bold" fill="white" text-anchor="middle">TEC</text>
                    <text x="50" y="95" font-family="Arial" font-size="8" fill="#1e3a8a" text-anchor="middle">TECNOLÓGICO</text>
                    <text x="50" y="105" font-family="Arial" font-size="8" fill="#1e3a8a" text-anchor="middle">NACIONAL DE</text>
                    <text x="50" y="115" font-family="Arial" font-size="8" fill="#1e3a8a" text-anchor="middle">MÉXICO</text>
                </svg>
            </div>

            <!-- Texto central -->
            <div class="header-text">
                <div class="institution">Instituto Tecnológico Nacional de México</div>
                <div class="institution">Campus Oaxaca otorgan el presente</div>
            </div>

            <!-- Logo SEP -->
            <div>
                <svg class="logo-right" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                    <!-- Escudo SEP simplificado -->
                    <circle cx="30" cy="30" r="25" fill="#8b4513"/>
                    <circle cx="30" cy="30" r="20" fill="#a0522d"/>
                    <text x="70" y="25" font-family="Arial" font-size="18" font-weight="bold" fill="#8b4513">EDUCACIÓN</text>
                    <text x="70" y="40" font-family="Arial" font-size="8" fill="#666">SECRETARÍA DE EDUCACIÓN PÚBLICA</text>
                </svg>
            </div>
        </div>

        <div class="divider-line"></div>

        <!-- Content -->
        <div class="certifica-text">Certificado de participación a</div>

        <!-- Nombre del participante -->
        <div class="recipient-name">
            {{ strtoupper($user->name) }}
        </div>

        <!-- Texto del cuerpo -->
        <div class="body-text">
            por haber participado en el evento <span class="event-name">{{ $evento->nombre }}</span>
            @if($proyecto)
                con el proyecto <span class="project-info">"{{ $proyecto->titulo }}"</span>
            @endif
            @if($perfilEquipo)
                con <span class="role-name">{{ $perfilEquipo->nombre }}</span>.
            @endif
        </div>

        <!-- Firmas 2x2 -->
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
