<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .success-box { background: #d1fae5; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .team-info { background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .info-row { margin: 10px 0; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: 600; color: #495057; display: inline-block; width: 120px; }
        .value { color: #212529; }
        .button { display: inline-block; padding: 12px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ ¡Felicitaciones!</h1>
        </div>
        
        <div class="content">
            <p>¡Hola <strong>{{ $participante->name }}</strong>!</p>
            
            <div class="success-box">
                <h2 style="margin-top: 0; color: #059669;">¡Tu solicitud ha sido aceptada!</h2>
                <p style="margin-bottom: 0;">Ahora eres parte del equipo <strong>{{ $equipo->nombre }}</strong></p>
            </div>
            
            <p>El líder del equipo ha aprobado tu solicitud. Ahora puedes acceder a todas las funcionalidades del equipo.</p>
            
            <div class="team-info">
                <h3 style="margin-top: 0; color: #10b981;">Información del Equipo</h3>
                
                <div class="info-row">
                    <span class="label">🏷️ Equipo:</span>
                    <span class="value">{{ $equipo->nombre }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">👥 Líder:</span>
                    <span class="value">{{ $equipo->lider->name }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">🎯 Evento:</span>
                    <span class="value">{{ $equipo->evento->nombre }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">📅 Fecha del evento:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($equipo->evento->fecha_inicio)->format('d/m/Y') }}</span>
                </div>
            </div>
            
            <p><strong>¿Qué puedes hacer ahora?</strong></p>
            <ul>
                <li>✅ Participar en el chat del equipo</li>
                <li>✅ Colaborar en el proyecto</li>
                <li>✅ Ver y crear tareas</li>
                <li>✅ Compartir ideas con tus compañeros</li>
            </ul>
            
            <center>
                <a href="{{ url('/equipos/' . $equipo->id) }}" class="button">
                    Ir a Mi Equipo
                </a>
            </center>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>¡Buena suerte!</strong> Esperamos que tengas una excelente experiencia colaborando con tu equipo.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">Sistema de Gestión de Hackathons</p>
            <p style="margin: 5px 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
