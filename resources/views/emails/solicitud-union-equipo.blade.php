<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Unión</title>
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
            border-bottom: 3px solid #8b5cf6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #8b5cf6;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .user-card {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #8b5cf6;
        }
        .user-card h3 {
            margin: 0 0 10px 0;
            color: #8b5cf6;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #059669;
        }
        .button-secondary {
            background-color: #6b7280;
        }
        .button-secondary:hover {
            background-color: #4b5563;
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
            <h1>👥 Nueva Solicitud de Unión</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $lider->name }}</strong>,</p>
            
            <p>Tienes una nueva solicitud para unirse a tu equipo <strong>{{ $equipo->nombre }}</strong>.</p>
            
            <div class="user-card">
                <h3>{{ $solicitante->name }}</h3>
                <p><strong>📧 Email:</strong> {{ $solicitante->email }}</p>
                @if($solicitante->perfil)
                    <p><strong>🎓 Carrera:</strong> {{ $solicitante->perfil->carrera->nombre ?? 'No especificada' }}</p>
                    <p><strong>📚 Semestre:</strong> {{ $solicitante->perfil->semestre ?? 'No especificado' }}</p>
                @endif
            </div>
            
            <p><strong>Evento:</strong> {{ $equipo->evento->nombre }}</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ config('app.url') }}/equipo/{{ $equipo->id }}" class="button">
                    ✅ Ver Solicitud y Decidir
                </a>
            </div>
            
            <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
                Puedes aceptar o rechazar esta solicitud desde el panel de gestión de tu equipo.
            </p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} TecNM - Instituto Tecnológico de Oaxaca</p>
        </div>
    </div>
</body>
</html>
