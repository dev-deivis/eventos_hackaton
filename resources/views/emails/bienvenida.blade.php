<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
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
            border-bottom: 3px solid #6366f1;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #6366f1;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #6366f1;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #4f46e5;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .highlight {
            background-color: #f0f9ff;
            border-left: 4px solid #6366f1;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 ¡Bienvenido a Eventos Hackathon!</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>¡Tu cuenta ha sido creada exitosamente!</p>
            
            <div class="highlight">
                <p><strong>📧 Email:</strong> {{ $user->email }}</p>
                <p><strong>📅 Fecha de registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <p>Ahora puedes:</p>
            <ul>
                <li>✅ Completar tu perfil académico</li>
                <li>🎯 Inscribirte a eventos</li>
                <li>👥 Crear o unirte a equipos</li>
                <li>💻 Registrar proyectos</li>
                <li>🏆 Participar en hackathons</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/perfil/completar" class="button">
                    Completar mi Perfil
                </a>
            </div>
            
            <p>Si no creaste esta cuenta, puedes ignorar este mensaje.</p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} TecNM - Instituto Tecnológico de Oaxaca</p>
        </div>
    </div>
</body>
</html>
