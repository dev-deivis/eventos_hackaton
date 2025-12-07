<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .event-info { background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .info-row { margin: 10px 0; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #495057; display: inline-block; width: 120px; }
        .value { color: #212529; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Nuevo Evento Disponible</h1>
        </div>
        
        <div class="content">
            <p>¡Hola!</p>
            
            <p>Te informamos que hay un nuevo evento disponible que podría interesarte:</p>
            
            <div class="event-info">
                <h2 style="margin-top: 0; color: #667eea;">{{ $evento->nombre }}</h2>
                
                @if($evento->descripcion)
                <p style="color: #495057; line-height: 1.6;">{{ $evento->descripcion }}</p>
                @endif
                
                <div class="info-row">
                    <span class="label">📅 Fecha inicio:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">📅 Fecha fin:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}</span>
                </div>
                
                @if($evento->ubicacion)
                <div class="info-row">
                    <span class="label">📍 Ubicación:</span>
                    <span class="value">{{ $evento->ubicacion }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="label">👥 Tamaño equipo:</span>
                    <span class="value">{{ $evento->max_participantes_por_equipo }} participantes</span>
                </div>
                
                @if($evento->premios && count($evento->premios) > 0)
                <div class="info-row">
                    <span class="label">🏆 Premios:</span>
                    <span class="value">{{ count($evento->premios) }} premios disponibles</span>
                </div>
                @endif
            </div>
            
            <p>No pierdas la oportunidad de participar. ¡Forma tu equipo y regístrate!</p>
            
            <center>
                <a href="{{ url('/eventos/' . $evento->id) }}" class="button">
                    Ver Detalles del Evento
                </a>
            </center>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>Nota:</strong> Asegúrate de leer todos los requisitos y condiciones del evento antes de registrarte.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">Sistema de Gestión de Hackathons</p>
            <p style="margin: 5px 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
