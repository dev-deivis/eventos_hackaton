<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Evento;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Proyecto;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   PRUEBA COMPLETA DEL SISTEMA DE CORREOS                  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Verificar configuración
echo "📋 Verificando configuración...\n";
echo "   MAIL_ENABLED: " . (env('MAIL_ENABLED') ? '✅ true' : '❌ false') . "\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "   MAIL_FROM: " . config('mail.from.address') . "\n";
echo "\n";

if (!env('MAIL_ENABLED')) {
    echo "❌ ERROR: MAIL_ENABLED está en false\n";
    echo "   Cambia en .env: MAIL_ENABLED=true\n";
    exit(1);
}

// Pedir email de destino
echo "📧 Ingresa el email de destino para TODAS las pruebas:\n";
echo "   (Ejemplo: tu_email@gmail.com)\n";
echo "   > ";
$emailDestino = trim(fgets(STDIN));

if (empty($emailDestino) || !filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Email inválido\n";
    exit(1);
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🧪 INICIANDO PRUEBAS DE CORREOS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

$resultados = [];

// ========================================
// PRUEBA 1: Nuevo Evento
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 1/6: Correo de Nuevo Evento 🎉\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $evento = Evento::first();
    if (!$evento) {
        echo "⚠️  No hay eventos en la BD, creando uno de prueba...\n";
        $evento = Evento::create([
            'nombre' => 'Hackathon Prueba Correos',
            'descripcion' => 'Evento de prueba para correos',
            'fecha_inicio' => now()->addDays(7),
            'fecha_fin' => now()->addDays(9),
            'estado' => 'proximo'
        ]);
    }
    
    $mailable = new \App\Mail\NuevoEventoMail($evento);
    Mail::to($emailDestino)->send($mailable);
    
    echo "✅ Correo enviado exitosamente\n";
    echo "   Evento: {$evento->nombre}\n";
    $resultados['nuevo_evento'] = true;
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $resultados['nuevo_evento'] = false;
}
echo "\n";
sleep(2);

// ========================================
// PRUEBA 2: Solicitud a Equipo
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 2/6: Solicitud para unirse a Equipo 👥\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $equipo = Equipo::first();
    $solicitante = User::where('rol', 'participante')->first();
    
    if (!$equipo || !$solicitante) {
        echo "⚠️  No hay datos suficientes (equipo o usuario)\n";
        $resultados['solicitud_equipo'] = false;
    } else {
        $mailable = new \App\Mail\SolicitudEquipoMail($equipo, $solicitante);
        Mail::to($emailDestino)->send($mailable);
        
        echo "✅ Correo enviado exitosamente\n";
        echo "   Equipo: {$equipo->nombre}\n";
        echo "   Solicitante: {$solicitante->name}\n";
        $resultados['solicitud_equipo'] = true;
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $resultados['solicitud_equipo'] = false;
}
echo "\n";
sleep(2);

// ========================================
// PRUEBA 3: Solicitud Aceptada
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 3/6: Solicitud Aceptada ✅\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $equipo = Equipo::first();
    
    if (!$equipo) {
        echo "⚠️  No hay equipos en la BD\n";
        $resultados['solicitud_aceptada'] = false;
    } else {
        $mailable = new \App\Mail\SolicitudAceptadaMail($equipo);
        Mail::to($emailDestino)->send($mailable);
        
        echo "✅ Correo enviado exitosamente\n";
        echo "   Equipo: {$equipo->nombre}\n";
        $resultados['solicitud_aceptada'] = true;
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $resultados['solicitud_aceptada'] = false;
}
echo "\n";
sleep(2);

// ========================================
// PRUEBA 4: Evaluación Completada
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 4/6: Evaluación Completada ⭐\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $evaluacion = Evaluacion::with(['proyecto.equipo', 'juez'])->first();
    
    if (!$evaluacion) {
        echo "⚠️  No hay evaluaciones en la BD\n";
        $resultados['evaluacion_completada'] = false;
    } else {
        $mailable = new \App\Mail\EvaluacionCompletadaMail($evaluacion);
        Mail::to($emailDestino)->send($mailable);
        
        echo "✅ Correo enviado exitosamente\n";
        echo "   Proyecto: {$evaluacion->proyecto->nombre}\n";
        echo "   Puntaje: {$evaluacion->puntaje_total}/100\n";
        $resultados['evaluacion_completada'] = true;
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $resultados['evaluacion_completada'] = false;
}
echo "\n";
sleep(2);

// ========================================
// PRUEBA 5: Proyecto Aprobado
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 5/6: Proyecto Aprobado ✅\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $proyecto = Proyecto::with('equipo')->first();
    
    if (!$proyecto) {
        echo "⚠️  No hay proyectos en la BD\n";
        $resultados['proyecto_aprobado'] = false;
    } else {
        $mailable = new \App\Mail\ProyectoAprobadoMail($proyecto);
        Mail::to($emailDestino)->send($mailable);
        
        echo "✅ Correo enviado exitosamente\n";
        echo "   Proyecto: {$proyecto->nombre}\n";
        $resultados['proyecto_aprobado'] = true;
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $resultados['proyecto_aprobado'] = false;
}
echo "\n";
sleep(2);

// ========================================
// PRUEBA 6: Constancia Generada
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "PRUEBA 6/6: Constancia Generada 🏆\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

try {
    $user = User::where('rol', 'participante')->first();
    $evento = Evento::first();
    
    if (!$user || !$evento) {
        echo "⚠️  No hay datos suficientes\n";
        $resultados['constancia_generada'] = false;
    } else {
        $constanciaUrl = "https://web-production-ef44a.up.railway.app/constancia/ver/123";
        
        $mailable = new \App\Mail\ConstanciaGeneradaMail($user, $evento, $constanciaUrl);
        Mail::to($emailDestino)->send($mailable);
        
        echo "✅ Correo enviado exitosamente\n";
        echo "   Usuario: {$user->name}\n";
        echo "   Evento: {$evento->nombre}\n";
        $resultados['constancia_generada'] = true;
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    $resultados['constancia_generada'] = false;
}
echo "\n";

// ========================================
// RESUMEN FINAL
// ========================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 RESUMEN DE PRUEBAS\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";

$exitosos = 0;
$fallidos = 0;

foreach ($resultados as $nombre => $resultado) {
    $icono = $resultado ? '✅' : '❌';
    $estado = $resultado ? 'EXITOSO' : 'FALLIDO';
    echo "{$icono} " . str_replace('_', ' ', ucfirst($nombre)) . ": {$estado}\n";
    
    if ($resultado) {
        $exitosos++;
    } else {
        $fallidos++;
    }
}

echo "\n";
echo "Total: {$exitosos} exitosos, {$fallidos} fallidos\n";
echo "\n";

if ($fallidos === 0) {
    echo "🎉 ¡TODOS LOS CORREOS FUNCIONAN PERFECTAMENTE!\n";
    echo "\n";
    echo "✅ El sistema está listo para producción\n";
    echo "✅ Revisa tu bandeja: {$emailDestino}\n";
    echo "✅ Deberías tener 6 correos\n";
    echo "\n";
    echo "🚀 SIGUIENTE PASO: Configurar en Railway\n";
    echo "   Ejecuta: php configurar-railway.php\n";
} else {
    echo "⚠️  Algunos correos fallaron\n";
    echo "   Revisa los errores arriba\n";
    echo "   Puede ser falta de datos en la BD\n";
}

echo "\n";
