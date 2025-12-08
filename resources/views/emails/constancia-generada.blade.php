<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 40px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .award-box { background: #fef3c7; border-left: 4px solid #fbbf24; padding: 20px; margin: 20px 0; border-radius: 4px; text-align: center; }
        .award-icon { font-size: 64px; margin: 10px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #fbbf24; color: #78350f; text-decoration: none; border-radius: 6px; margin: 20px 0; font-weight: bold; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏆 Tu Constancia está Lista</h1>
        </div>
        
        <div class="content">
            <p>¡Hola <strong>{{ $constancia->participante->user->name }}</strong>!</p>
            
            <div class="award-box">
                <div class="award-icon">🎓</div>
                <h2 style="margin: 10px 0; color: #f59e0b;">¡Felicitaciones!</h2>
                <p style="margin-bottom: 0;">Tu constancia de participación ya está disponible</p>
            </div>
            
            <p>Has completado exitosamente tu participación en:</p>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; margin: 20px 0;">
                <h3 style="margin-top: 0; color: #fbbf24;">{{ $constancia->evento->nombre }}</h3>
                
                <p><strong>Tipo de constancia:</strong> 
                    @if($constancia->tipo === 'participacion')
                        Participación
                    @elseif($constancia->tipo === 'primer_lugar')
                        🥇 Primer Lugar
                    @elseif($constancia->tipo === 'segundo_lugar')
                        🥈 Segundo Lugar
                    @elseif($constancia->tipo === 'tercer_lugar')
                        🥉 Tercer Lugar
                    @else
                        Mención Especial
                    @endif
                </p>
                
                <p><strong>Código de verificación:</strong> {{ $constancia->codigo_verificacion }}</p>
                <p><strong>Fecha de emisión:</strong> {{ $constancia->fecha_emision->format('d/m/Y') }}</p>
            </div>
            
            <p>Puedes descargar tu constancia en formato PDF desde nuestro sistema.</p>
            
            <center>
                <a href="{{ url('/admin/constancias/' . $constancia->id . '/descargar') }}" class="button">
                    📥 Descargar Constancia
                </a>
            </center>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                <strong>Nota:</strong> Guarda este correo para futuras referencias. El código de verificación puede ser usado para validar la autenticidad de tu constancia.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">Sistema de Gestión de Hackathons</p>
            <p style="margin: 5px 0;">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
