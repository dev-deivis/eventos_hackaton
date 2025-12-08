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
        .button { display: inline-block; padding: 12px 30px; background: #10b981; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Proyecto Aprobado</h1>
        </div>
        
        <div class="content">
            <p>¡Hola equipo <strong>{{ $equipo->nombre }}</strong>!</p>
            
            <div class="success-box">
                <h2 style="margin-top: 0; color: #059669;">¡Excelentes noticias!</h2>
                <p style="margin-bottom: 0;">Su proyecto ha sido <strong>aprobado</strong> por los organizadores.</p>
            </div>
            
            <p>El proyecto <strong>{{ $proyecto->nombre }}</strong> cumple con todos los requisitos y ha sido validado exitosamente.</p>
            
            <p><strong>Próximos pasos:</strong></p>
            <ul>
                <li>✅ Su proyecto está listo para ser evaluado por los jueces</li>
                <li>✅ Asegúrense de que todos los enlaces funcionen correctamente</li>
                <li>✅ Preparen su presentación final</li>
            </ul>
            
            <center>
                <a href="{{ url('/equipos/' . $equipo->id) }}" class="button">
                    Ver Proyecto
                </a>
            </center>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>¡Mucha suerte!</strong> Esperamos que tengan éxito en la evaluación final.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">Sistema de Gestión de Hackathons</p>
            <p style="margin: 5px 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
