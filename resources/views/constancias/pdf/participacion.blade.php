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

        /* Gradiente decorativo más grande y más visible */
        .gradient-decoration {
            position: absolute;
            top: 80px;
            right: -100px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle at 30% 30%, rgba(139, 92, 246, 0.4) 0%, rgba(99, 102, 241, 0.35) 40%, rgba(59, 130, 246, 0.25) 70%, transparent 100%);
            border-radius: 50%;
            z-index: 0;
        }

        /* Estrella/flor decorativa más grande y prominente */
        .star-decoration {
            position: absolute;
            top: 120px;
            right: 30px;
            width: 380px;
            height: 380px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.45) 0%, rgba(59, 130, 246, 0.40) 100%);
            clip-path: polygon(
                50% 0%, 
                58% 20%, 61% 35%, 
                75% 38%, 98% 35%, 
                80% 50%, 68% 57%, 
                75% 72%, 79% 91%, 
                60% 80%, 50% 70%, 
                40% 80%, 21% 91%, 
                25% 72%, 32% 57%, 
                20% 50%, 2% 35%, 
                25% 38%, 39% 35%, 
                42% 20%
            );
            z-index: 1;
            opacity: 0.85;
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
            margin-bottom: 10px;
        }

        .logo-left {
            width: 60px;
            height: auto;
        }

        .header-text {
            flex: 1;
            text-align: left;
            padding: 0 30px;
            padding-top: 8px;
        }

        .institution {
            font-size: 9px;
            color: #374151;
            line-height: 1.4;
        }

        .logo-right {
            width: 140px;
            height: auto;
        }

        .divider-line {
            width: 100%;
            height: 1px;
            background: #d1d5db;
            margin: 8px 0 30px 0;
        }

        /* Content */
        .certifica-text {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        /* Nombre en líneas separadas como en la imagen */
        .recipient-name {
            font-size: 54px;
            color: #5b21b6;
            font-weight: bold;
            line-height: 1.0;
            text-transform: uppercase;
            letter-spacing: -0.5px;
            margin-bottom: 25px;
        }

        .name-line {
            display: block;
        }

        .body-text {
            font-size: 11px;
            line-height: 1.8;
            color: #374151;
            max-width: 500px;
            margin-bottom: 50px;
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

        /* Firmas - Grid 2x2 */
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px 70px;
            margin-top: 70px;
            max-width: 750px;
        }

        .signature {
            text-align: center;
        }

        /* Simulación de firma manuscrita */
        .signature-image {
            height: 40px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-image svg {
            height: 35px;
            width: auto;
        }

        .signature-line {
            width: 100%;
            height: 1px;
            background: #000;
            margin-bottom: 8px;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
            color: #000;
            line-height: 1.3;
            margin-bottom: 3px;
        }

        .signature-title {
            font-size: 8px;
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
                    <text x="50" y="95" font-family="Arial" font-size="7" fill="#1e3a8a" text-anchor="middle">TECNOLÓGICO</text>
                    <text x="50" y="105" font-family="Arial" font-size="7" fill="#1e3a8a" text-anchor="middle">NACIONAL DE</text>
                    <text x="50" y="115" font-family="Arial" font-size="7" fill="#1e3a8a" text-anchor="middle">MÉXICO</text>
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

        <!-- Nombre del participante en líneas separadas -->
        <div class="recipient-name">
            @php
                $nombres = explode(' ', $user->name);
            @endphp
            @foreach($nombres as $nombre)
                <span class="name-line">{{ strtoupper($nombre) }}</span>
            @endforeach
        </div>

        <!-- Texto del cuerpo -->
        <div class="body-text">
            por haber participado en el evento <span class="event-name">{{ $evento->nombre }}</span>
            @if($equipo)
                @if($proyecto && $proyecto->nombre)
                    con el equipo <span class="project-info">"{{ $equipo->nombre }}"</span> 
                    en el proyecto <span class="project-info">"{{ $proyecto->nombre }}"</span>
                @else
                    con el equipo <span class="project-info">"{{ $equipo->nombre }}"</span>
                @endif
                @if($perfilEquipo)
                    como <span class="role-name">{{ $perfilEquipo->nombre }}</span>
                @endif
            @elseif($participante && $participante->carrera)
                como estudiante de <span class="role-name">{{ $participante->carrera->nombre }}</span>
            @endif.
        </div>

        <!-- Firmas 2x2 con simulación de firma manuscrita -->
        <div class="signatures">
            <div class="signature">
                <!-- Firma manuscrita simulada -->
                <div class="signature-image">
                    <svg viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10,25 Q20,15 30,25 T50,25 Q60,20 70,25 T90,25 L110,20" 
                              stroke="#000" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">M.C. Silvia Santiago Cruz</div>
                <div class="signature-title">Directora</div>
            </div>

            <div class="signature">
                <div class="signature-image">
                    <svg viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10,20 Q25,28 40,20 T70,20 Q85,15 100,22 L115,18" 
                              stroke="#000" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">Dra. Alma Dolores Pérez Santiago</div>
                <div class="signature-title">Subdirectora Académica</div>
            </div>

            <div class="signature">
                <div class="signature-image">
                    <svg viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15,22 Q30,18 45,25 T75,22 Q90,25 105,20" 
                              stroke="#000" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">Dra. Marisol Altamirano Cabrera</div>
                <div class="signature-title">Subdirectora de Planeación y<br>Vinculación</div>
            </div>

            <div class="signature">
                <div class="signature-image">
                    <svg viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10,24 Q28,20 45,26 T80,23 Q95,20 110,25" 
                              stroke="#000" stroke-width="2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">Ing. Huitziil Díaz Jaimes</div>
                <div class="signature-title">Jefa del Depto. de Servicios<br>Escolares</div>
            </div>
        </div>
    </div>
</body>
</html>
