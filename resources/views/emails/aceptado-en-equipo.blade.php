<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aceptado en Equipo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #10b981;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .success-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }
        .success-banner h2 {
            margin: 0;
            font-size: 32px;
        }
        .team-info {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .team-info p {
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #059669;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 ¡Felicidades!</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <div class="success-banner">
                <h2>✅ ¡Fuiste aceptado!</h2>
            </div>
            
            <p>Tu solicitud para unirte al equipo ha sido <strong>aceptada</strong>.</p>
            
            <div class="team-info">
                <p><strong>👥 Equipo:</strong> {{ $equipo->nombre }}</p>
                <p><strong>🎯 Evento:</strong> {{ $equipo->evento->nombre }}</p>
                <p><strong>👤 Líder:</strong> {{ $equipo->lider->user->name }}</p>
                <p><strong>📅 Fecha del evento:</strong> {{ $equipo->evento->fecha_inicio->format('d/m/Y') }}</p>
            </div>
            
            <p>Ahora puedes:</p>
            <ul>
                <li>✅ Chatear con tu equipo</li>
                <li>💻 Trabajar en el proyecto</li>
                <li>📋 Gestionar tareas</li>
                <li>🏆 Competir en el evento</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/equipo/{{ $equipo->id }}" class="button">
                    Ver Mi Equipo
                </a>
            </div>
            
            <p><strong>¡Mucho éxito en el evento!</strong> 🚀</p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} TecNM - Instituto Tecnológico de Oaxaca</p>
        </div>
    </div>
</body>
</html>
