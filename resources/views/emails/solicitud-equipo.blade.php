<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .team-info { background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .info-row { margin: 10px 0; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #495057; display: inline-block; width: 120px; }
        .value { color: #212529; }
        .button { display: inline-block; padding: 12px 30px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
        .alert { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Nueva Solicitud de Equipo</h1>
        </div>
        
        <div class="content">
            <p>¡Hola!</p>
            
            <p><strong>{{ $solicitante->name }}</strong> ha solicitado unirse a tu equipo.</p>
            
            <div class="team-info">
                <h2 style="margin-top: 0; color: #3b82f6;">{{ $equipo->nombre }}</h2>
                
                <div class="info-row">
                    <span class="label">👤 Solicitante:</span>
                    <span class="value">{{ $solicitante->name }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">📧 Email:</span>
                    <span class="value">{{ $solicitante->email }}</span>
                </div>
                
                @if($solicitante->participante && $solicitante->participante->perfil)
                <div class="info-row">
                    <span class="label">🎓 Carrera:</span>
                    <span class="value">{{ $solicitante->participante->perfil->carrera->nombre ?? 'No especificada' }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="label">🎯 Evento:</span>
                    <span class="value">{{ $equipo->evento->nombre }}</span>
                </div>
            </div>
            
            <div class="alert">
                <strong>⏰ Acción requerida:</strong> Como líder del equipo, necesitas revisar y aprobar o rechazar esta solicitud.
            </div>
            
            <center>
                <a href="{{ url('/equipos/' . $equipo->id) }}" class="button">
                    Ver Solicitud y Responder
                </a>
            </center>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                Puedes ver el perfil completo del solicitante antes de tomar una decisión.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">Sistema de Gestión de Hackathons</p>
            <p style="margin: 5px 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
