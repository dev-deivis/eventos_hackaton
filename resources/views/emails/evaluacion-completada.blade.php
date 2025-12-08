<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .eval-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .score { font-size: 48px; font-weight: bold; color: #d97706; text-align: center; margin: 20px 0; }
        .team-info { background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #f59e0b; color: white; text-decoration: none; border-radius: 6px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⭐ Evaluación Completada</h1>
        </div>
        
        <div class="content">
            <p>¡Hola equipo <strong>{{ $equipo->nombre }}</strong>!</p>
            
            <div class="eval-box">
                <h2 style="margin-top: 0; color: #d97706;">Tu proyecto ha sido evaluado</h2>
                <p style="margin-bottom: 0;">Un juez ha completado la evaluación de su proyecto.</p>
            </div>
            
            @if(isset($evaluacion->calificacion_promedio))
            <div class="score">
                {{ number_format($evaluacion->calificacion_promedio, 1) }}/10
            </div>
            @endif
            
            <div class="team-info">
                <h3 style="margin-top: 0;">Detalles de la Evaluación</h3>
                
                @if(isset($evaluacion->comentarios))
                <p><strong>Comentarios del juez:</strong></p>
                <p style="font-style: italic; color: #495057;">{{ $evaluacion->comentarios }}</p>
                @endif
                
                <p><strong>Evento:</strong> {{ $equipo->evento->nombre }}</p>
                <p><strong>Equipo:</strong> {{ $equipo->nombre }}</p>
                <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            
            <p>Puedes ver los detalles completos de la evaluación accediendo a tu equipo.</p>
            
            <center>
                <a href="{{ url('/equipos/' . $equipo->id) }}" class="button">
                    Ver Detalles Completos
                </a>
            </center>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>¡Sigan así!</strong> Continúen trabajando en equipo para lograr grandes resultados.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">Sistema de Gestión de Hackathons</p>
            <p style="margin: 5px 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
