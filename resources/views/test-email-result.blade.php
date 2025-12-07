<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - Resend</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #22c55e; }
        .error { color: #ef4444; }
        .info { 
            background: #f0f9ff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #3b82f6;
        }
        pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .steps {
            background: #fef3c7;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🧪 Test de Correo - Resend API</h1>
        
        <div class="{{ str_contains($message, '✅') ? 'success' : 'error' }}">
            <h2>{{ $message }}</h2>
        </div>

        <div class="info">
            <h3>📋 Configuración Actual:</h3>
            <pre>{{ json_encode($config, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <div class="info">
            <h3>📧 Email de Destino:</h3>
            <p><strong>{{ $email }}</strong></p>
        </div>

        <div class="steps">
            <h3>🔍 Pasos de Verificación:</h3>
            <ol>
                <li>Ve a <a href="https://resend.com/emails" target="_blank">Resend Dashboard → Emails</a></li>
                <li>Busca el correo enviado a <strong>{{ $email }}</strong></li>
                <li>Verifica el estado (Delivered, Bounced, etc.)</li>
                <li>Si está en Sandbox mode, solo recibirás correos en emails verificados</li>
                <li>Revisa los logs de Railway para más detalles</li>
            </ol>
        </div>

        <div class="info">
            <h3>⚠️ Nota sobre Resend Sandbox:</h3>
            <p>Si tu cuenta está en <strong>Sandbox mode</strong>, solo puedes enviar correos a:</p>
            <ul>
                <li>El email con el que te registraste en Resend</li>
                <li>Emails que hayas verificado manualmente</li>
            </ul>
            <p><strong>Solución:</strong> Verifica tu dominio en Resend o usa el email registrado para pruebas.</p>
        </div>

        <a href="/admin/dashboard" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 4px;">
            ← Volver al Dashboard
        </a>
    </div>
</body>
</html>
