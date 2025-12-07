<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Evento</title>
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
            border-bottom: 3px solid #ec4899;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #ec4899;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .event-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .event-card h2 {
            margin: 0 0 15px 0;
            font-size: 24px;
        }
        .event-info {
            background-color: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .event-info p {
            margin: 8px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #ec4899;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #db2777;
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
            <h1>🎯 ¡Nuevo Evento Disponible!</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            
            <p>¡Tenemos un nuevo evento que podría interesarte!</p>
            
            <div class="event-card">
                <h2>{{ $evento->nombre }}</h2>
                <p>{{ $evento->descripcion }}</p>
                
                <div class="event-info">
                    <p><strong>📅 Inicio:</strong> {{ $evento->fecha_inicio->format('d/m/Y H:i') }}</p>
                    <p><strong>🏁 Fin:</strong> {{ $evento->fecha_fin->format('d/m/Y H:i') }}</p>
                    <p><strong>📍 Ubicación:</strong> {{ $evento->ubicacion ?? ($evento->es_virtual ? '💻 Virtual' : 'Por definir') }}</p>
                    <p><strong>👥 Cupo:</strong> {{ $evento->max_participantes ?? 'Ilimitado' }} participantes</p>
                    <p><strong>⏰ Límite de registro:</strong> {{ $evento->fecha_limite_registro->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/eventos/{{ $evento->id }}" class="button">
                    Ver Detalles del Evento
                </a>
            </div>
            
            <p><strong>¡No te lo pierdas!</strong> Inscríbete lo antes posible para asegurar tu lugar.</p>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
            <p>&copy; {{ date('Y') }} TecNM - Instituto Tecnológico de Oaxaca</p>
        </div>
    </div>
</body>
</html>
