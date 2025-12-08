<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   DIAGNOSTICO COMPLETO DEL SISTEMA DE CORREOS             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// 1. Verificar configuración
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "1️⃣  CONFIGURACIÓN\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

$mailEnabled = env('MAIL_ENABLED');
$mailMailer = config('mail.default');
$mailHost = config('mail.mailers.smtp.host');
$mailPort = config('mail.mailers.smtp.port');
$mailUsername = config('mail.mailers.smtp.username');
$mailPassword = config('mail.mailers.smtp.password');
$mailEncryption = config('mail.mailers.smtp.encryption');
$mailFrom = config('mail.from.address');

echo "MAIL_ENABLED: " . ($mailEnabled ? '✅ true' : '❌ false') . "\n";
echo "MAIL_MAILER: " . ($mailMailer ?: '❌ NO CONFIGURADO') . "\n";
echo "MAIL_HOST: " . ($mailHost ?: '❌ NO CONFIGURADO') . "\n";
echo "MAIL_PORT: " . ($mailPort ?: '❌ NO CONFIGURADO') . "\n";
echo "MAIL_USERNAME: " . ($mailUsername ?: '❌ NO CONFIGURADO') . "\n";
echo "MAIL_PASSWORD: " . ($mailPassword ? '✅ Configurado (longitud: ' . strlen($mailPassword) . ')' : '❌ NO CONFIGURADO') . "\n";
echo "MAIL_ENCRYPTION: " . ($mailEncryption ?: '❌ NO CONFIGURADO') . "\n";
echo "MAIL_FROM: " . ($mailFrom ?: '❌ NO CONFIGURADO') . "\n";
echo "\n";

$errores = [];

if (!$mailEnabled) {
    $errores[] = "❌ MAIL_ENABLED está en false o no existe";
}

if ($mailMailer !== 'smtp') {
    $errores[] = "❌ MAIL_MAILER debe ser 'smtp' pero es: " . $mailMailer;
}

if ($mailHost !== 'smtp-relay.brevo.com') {
    $errores[] = "❌ MAIL_HOST debe ser 'smtp-relay.brevo.com' pero es: " . $mailHost;
}

if ($mailPort != 587) {
    $errores[] = "⚠️  MAIL_PORT es {$mailPort} (debería ser 587)";
}

if (!$mailPassword || strlen($mailPassword) < 50) {
    $errores[] = "❌ MAIL_PASSWORD parece incorrecta (muy corta o vacía)";
}

if (count($errores) > 0) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "⚠️  ERRORES DE CONFIGURACIÓN DETECTADOS:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n";
    foreach ($errores as $error) {
        echo $error . "\n";
    }
    echo "\n";
    echo "💡 SOLUCIÓN:\n";
    echo "   1. Verifica tu archivo .env\n";
    echo "   2. Ejecuta: php artisan config:clear\n";
    echo "   3. Vuelve a ejecutar este script\n";
    echo "\n";
    exit(1);
}

echo "✅ Configuración básica correcta\n";
echo "\n";

// 2. Verificar clases Mailable
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "2️⃣  CLASES MAILABLE\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

$mailables = [
    'NuevoEventoMail' => 'App\\Mail\\NuevoEventoMail',
    'SolicitudEquipoMail' => 'App\\Mail\\SolicitudEquipoMail',
    'SolicitudAceptadaMail' => 'App\\Mail\\SolicitudAceptadaMail',
    'EvaluacionCompletadaMail' => 'App\\Mail\\EvaluacionCompletadaMail',
    'ProyectoAprobadoMail' => 'App\\Mail\\ProyectoAprobadoMail',
    'ConstanciaGeneradaMail' => 'App\\Mail\\ConstanciaGeneradaMail',
];

$mailablesFaltantes = [];

foreach ($mailables as $nombre => $clase) {
    if (class_exists($clase)) {
        echo "✅ {$nombre}\n";
    } else {
        echo "❌ {$nombre} - NO EXISTE\n";
        $mailablesFaltantes[] = $nombre;
    }
}

if (count($mailablesFaltantes) > 0) {
    echo "\n";
    echo "❌ Faltan clases Mailable. El sistema no puede enviar correos.\n";
    echo "\n";
    exit(1);
}

echo "\n";
echo "✅ Todas las clases Mailable existen\n";
echo "\n";

// 3. Verificar plantillas
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "3️⃣  PLANTILLAS DE CORREO\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

$plantillas = [
    'nuevo-evento.blade.php',
    'solicitud-equipo.blade.php',
    'solicitud-aceptada.blade.php',
    'evaluacion-completada.blade.php',
    'proyecto-aprobado.blade.php',
    'constancia-generada.blade.php',
];

$plantillasFaltantes = [];

foreach ($plantillas as $plantilla) {
    $ruta = resource_path('views/emails/' . $plantilla);
    if (file_exists($ruta)) {
        echo "✅ {$plantilla}\n";
    } else {
        echo "❌ {$plantilla} - NO EXISTE\n";
        $plantillasFaltantes[] = $plantilla;
    }
}

if (count($plantillasFaltantes) > 0) {
    echo "\n";
    echo "❌ Faltan plantillas. El sistema no puede renderizar correos.\n";
    echo "\n";
    exit(1);
}

echo "\n";
echo "✅ Todas las plantillas existen\n";
echo "\n";

// 4. Probar conexión SMTP
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "4️⃣  PRUEBA DE CONEXIÓN SMTP\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

echo "Intentando conectar a {$mailHost}:{$mailPort}...\n";

try {
    $transport = new \Symfony\Component\Mailer\Transport\Smtp\SmtpTransport(
        $mailHost,
        $mailPort,
        $mailEncryption === 'tls'
    );
    
    $transport->setUsername($mailUsername);
    $transport->setPassword($mailPassword);
    
    $transport->start();
    
    echo "✅ Conexión SMTP exitosa\n";
    echo "✅ Autenticación exitosa\n";
} catch (\Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    echo "\n";
    echo "💡 POSIBLES CAUSAS:\n";
    echo "   - Credenciales incorrectas (regenera API Key en Brevo)\n";
    echo "   - Puerto bloqueado por firewall\n";
    echo "   - Host incorrecto\n";
    echo "\n";
    exit(1);
}

echo "\n";

// 5. Enviar correo de prueba
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "5️⃣  ENVÍO DE CORREO DE PRUEBA\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

echo "📧 Ingresa el email de destino:\n";
echo "   > ";
$emailDestino = trim(fgets(STDIN));

if (empty($emailDestino) || !filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Email inválido\n";
    exit(1);
}

echo "\n";
echo "Enviando correo de prueba a: {$emailDestino}...\n";

try {
    $evento = App\Models\Evento::first();
    
    if (!$evento) {
        echo "⚠️  No hay eventos en la BD, creando uno de prueba...\n";
        $evento = new App\Models\Evento();
        $evento->nombre = 'Evento de Prueba - Diagnóstico';
        $evento->descripcion = 'Este es un evento de prueba';
        $evento->fecha_inicio = now()->addDays(7);
        $evento->fecha_fin = now()->addDays(9);
        $evento->estado = 'proximo';
    }
    
    $mailable = new App\Mail\NuevoEventoMail($evento);
    Illuminate\Support\Facades\Mail::to($emailDestino)->send($mailable);
    
    echo "✅ ¡Correo enviado exitosamente!\n";
    echo "\n";
    echo "📬 Revisa tu bandeja: {$emailDestino}\n";
    echo "   (También revisa SPAM)\n";
    
} catch (\Exception $e) {
    echo "❌ Error al enviar: " . $e->getMessage() . "\n";
    echo "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ DIAGNÓSTICO COMPLETO\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
echo "Todo está funcionando correctamente:\n";
echo "✅ Configuración correcta\n";
echo "✅ Clases Mailable presentes\n";
echo "✅ Plantillas presentes\n";
echo "✅ Conexión SMTP exitosa\n";
echo "✅ Correo enviado exitosamente\n";
echo "\n";
echo "🎉 El sistema está listo para usar\n";
echo "\n";
