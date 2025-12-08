<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   PRUEBA SIMPLE DE CORREOS - ENVIO DIRECTO                ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Verificar configuración básica
echo "📋 Configuración:\n";
echo "   MAIL_ENABLED: " . (env('MAIL_ENABLED') ? '✅ true' : '❌ false') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "\n";

if (!env('MAIL_ENABLED')) {
    echo "❌ ERROR: MAIL_ENABLED está en false\n";
    echo "   Cambia en .env: MAIL_ENABLED=true\n\n";
    exit(1);
}

// Pedir email de destino
echo "📧 Ingresa el email de destino para la prueba:\n";
echo "   > ";
$emailDestino = trim(fgets(STDIN));

if (empty($emailDestino) || !filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Email inválido\n\n";
    exit(1);
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🧪 ENVIANDO CORREO DE PRUEBA...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

try {
    // Buscar o crear evento de prueba
    $evento = App\Models\Evento::first();
    
    if (!$evento) {
        echo "⚠️  No hay eventos en la BD, creando uno de prueba...\n";
        $evento = App\Models\Evento::create([
            'nombre' => 'Hackathon Prueba Correos',
            'descripcion' => 'Evento de prueba para sistema de correos',
            'tipo' => 'hackathon',
            'fecha_inicio' => now()->addDays(7),
            'fecha_fin' => now()->addDays(9),
            'fecha_limite_registro' => now()->addDays(5),
            'ubicacion' => 'Virtual',
            'es_virtual' => true,
            'duracion_horas' => 48,
            'min_miembros_equipo' => 5,
            'max_miembros_equipo' => 6,
            'estado' => 'proximo',
            'created_by' => 1
        ]);
        echo "✅ Evento creado: {$evento->nombre}\n\n";
    } else {
        echo "✅ Usando evento existente: {$evento->nombre}\n\n";
    }
    
    echo "📤 Enviando correo a: {$emailDestino}...\n";
    
    // Crear y enviar el correo
    $mailable = new App\Mail\NuevoEventoMail($evento);
    Illuminate\Support\Facades\Mail::to($emailDestino)->send($mailable);
    
    echo "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ ¡CORREO ENVIADO EXITOSAMENTE!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n";
    echo "📬 Revisa tu bandeja de entrada: {$emailDestino}\n";
    echo "   (También revisa la carpeta de SPAM)\n";
    echo "\n";
    echo "🎉 El sistema de correos está funcionando correctamente\n";
    echo "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 SIGUIENTE PASO: Configurar en Railway\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n";
    echo "1. Ve a: https://railway.app/\n";
    echo "2. Tu proyecto → Variables\n";
    echo "3. Agregar variables MAIL_*\n";
    echo "4. Esperar redeploy (2-3 min)\n";
    echo "5. ¡Listo para producción!\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "❌ ERROR AL ENVIAR CORREO\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\n";
    echo "💡 POSIBLES CAUSAS:\n";
    echo "\n";
    
    if (str_contains($e->getMessage(), 'Authentication failed') || str_contains($e->getMessage(), '535')) {
        echo "❌ CREDENCIALES INCORRECTAS\n";
        echo "   → Tu API Key de Brevo está mal o expirada\n";
        echo "   → Solución:\n";
        echo "     1. Ve a https://app.brevo.com/\n";
        echo "     2. SMTP & API → Generate new SMTP key\n";
        echo "     3. Copia la clave nueva\n";
        echo "     4. Actualiza MAIL_PASSWORD en .env\n";
        echo "     5. php artisan config:clear\n";
        echo "     6. Vuelve a ejecutar este script\n";
    } elseif (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'timed out')) {
        echo "❌ PROBLEMA DE CONEXIÓN\n";
        echo "   → Puerto 587 bloqueado por firewall\n";
        echo "   → Solución:\n";
        echo "     1. Ejecuta: .\\cambiar-puerto-465.bat\n";
        echo "     2. O cambia manualmente:\n";
        echo "        MAIL_PORT=465\n";
        echo "        MAIL_ENCRYPTION=ssl\n";
        echo "     3. php artisan config:clear\n";
        echo "     4. Vuelve a ejecutar este script\n";
    } else {
        echo "❌ ERROR DESCONOCIDO\n";
        echo "   → Revisa el stack trace arriba\n";
        echo "   → Verifica tu .env\n";
        echo "   → Contacta a soporte de Brevo\n";
    }
    
    echo "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n";
    exit(1);
}
