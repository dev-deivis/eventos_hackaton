<?php
/**
 * Script de prueba para envío de correos con Brevo
 * Ejecutar: php test-brevo-email.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Evento;
use App\Models\User;
use App\Models\Equipo;
use App\Mail\NuevoEventoMail;
use App\Mail\SolicitudEquipoMail;

echo "╔════════════════════════════════════════╗\n";
echo "║   PRUEBA DE CORREOS CON BREVO          ║\n";
echo "╚════════════════════════════════════════╝\n\n";

// Verificar configuración
echo "📋 Verificando configuración...\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_FROM: " . config('mail.from.address') . "\n\n";

if (config('mail.default') === 'log') {
    echo "⚠️  ADVERTENCIA: MAIL_MAILER está en 'log'\n";
    echo "   Los correos NO se enviarán realmente\n";
    echo "   Cambia MAIL_MAILER=smtp en .env\n\n";
}

// Pedir email de prueba
echo "📧 Ingresa el email de destino para la prueba:\n";
echo "   (Ejemplo: tu_email@gmail.com)\n";
echo "   > ";
$emailDestino = trim(fgets(STDIN));

if (empty($emailDestino) || !filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
    echo "\n❌ Email inválido\n";
    exit(1);
}

echo "\n🔍 Buscando datos para prueba...\n";

// Obtener un evento
$evento = Evento::first();
if (!$evento) {
    echo "❌ No hay eventos en la base de datos\n";
    echo "   Crea al menos un evento para probar\n";
    exit(1);
}
echo "✅ Evento encontrado: {$evento->nombre}\n";

// Obtener un equipo
$equipo = Equipo::with('lider')->first();
if (!$equipo) {
    echo "⚠️  No hay equipos (opcional para esta prueba)\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 1: Correo de Nuevo Evento\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    Mail::to($emailDestino)->send(new NuevoEventoMail($evento));
    echo "✅ Correo enviado exitosamente\n";
    echo "   Asunto: 🎉 Nuevo Evento Disponible: {$evento->nombre}\n";
    echo "   Destinatario: {$emailDestino}\n";
} catch (\Exception $e) {
    echo "❌ Error al enviar correo:\n";
    echo "   {$e->getMessage()}\n";
    
    // Información adicional de debug
    if (str_contains($e->getMessage(), 'Authentication failed')) {
        echo "\n💡 Posible solución:\n";
        echo "   - Verifica MAIL_USERNAME en .env\n";
        echo "   - Verifica MAIL_PASSWORD (debe ser la clave SMTP, no tu contraseña)\n";
        echo "   - Regenera la clave SMTP en Brevo si es necesario\n";
    }
    
    if (str_contains($e->getMessage(), 'Connection refused')) {
        echo "\n💡 Posible solución:\n";
        echo "   - Verifica que el puerto 587 esté abierto\n";
        echo "   - Prueba con puerto 465 y MAIL_ENCRYPTION=ssl\n";
    }
    
    exit(1);
}

// Segunda prueba si hay equipo
if ($equipo && $equipo->lider) {
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "PRUEBA 2: Correo de Solicitud de Equipo\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    try {
        $solicitante = User::where('id', '!=', $equipo->lider->id)->first() ?? $equipo->lider;
        Mail::to($emailDestino)->send(new SolicitudEquipoMail($equipo, $solicitante));
        echo "✅ Correo enviado exitosamente\n";
        echo "   Asunto: 👥 Nueva solicitud para unirse a tu equipo\n";
        echo "   Destinatario: {$emailDestino}\n";
    } catch (\Exception $e) {
        echo "❌ Error al enviar correo:\n";
        echo "   {$e->getMessage()}\n";
    }
}

echo "\n╔════════════════════════════════════════╗\n";
echo "║   PRUEBA COMPLETADA                    ║\n";
echo "╚════════════════════════════════════════╝\n\n";

echo "📬 Revisa tu bandeja de entrada en: {$emailDestino}\n";
echo "   Si no aparece, revisa la carpeta de SPAM\n\n";

echo "💡 Consejos:\n";
echo "   1. Si no recibiste el correo, verifica los logs:\n";
echo "      storage/logs/laravel.log\n";
echo "   2. Revisa el dashboard de Brevo:\n";
echo "      https://app.brevo.com/\n";
echo "   3. Si va a SPAM, configura SPF/DKIM en tu dominio\n\n";

echo "🎉 Script finalizado\n";
