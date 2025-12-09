<?php

namespace App\Helpers;

use App\Models\Notificacion;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Participante;
use App\Models\Constancia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NuevoEventoMail;
use App\Mail\SolicitudEquipoMail;
use App\Mail\SolicitudAceptadaMail;
use App\Mail\EvaluacionCompletadaMail;
use App\Mail\ProyectoAprobadoMail;
use App\Mail\ConstanciaGeneradaMail;

class NotificacionHelper
{
    /**
     * Configuración para habilitar/deshabilitar correos
     */
    private static function correosHabilitados(): bool
    {
        // Cambiar a true para habilitar el envío de correos
        // Asegúrate de tener configurado Brevo en .env
        return env('MAIL_ENABLED', false);
    }

    /**
     * Enviar correo de forma segura (con manejo de errores)
     * IMPORTANTE: Usa Brevo API HTTP en producción (Railway bloquea SMTP)
     *             Usa SMTP en local (funciona normal)
     */
    private static function enviarCorreo($mailable, $destinatario)
    {
        if (!self::correosHabilitados()) {
            Log::info('Correo NO enviado (sistema deshabilitado)', [
                'destinatario' => $destinatario,
                'mailable' => get_class($mailable)
            ]);
            return false;
        }

        try {
            // En producción (Railway), usar API HTTP para evitar bloqueo de SMTP
            if (env('APP_ENV') === 'production' && env('BREVO_API_KEY')) {
                // Renderizar el contenido del correo
                $mailableHtml = $mailable->render();
                $asunto = $mailable->subject ?? 'Notificación - Hackathon Events';
                
                // Usar servicio de Brevo API (HTTP, no SMTP)
                $brevoService = app(\App\Services\BrevoEmailService::class);
                $resultado = $brevoService->enviar($destinatario, $asunto, $mailableHtml);
                
                if ($resultado) {
                    Log::info('Correo enviado exitosamente via Brevo API (producción)', [
                        'destinatario' => $destinatario,
                        'mailable' => get_class($mailable)
                    ]);
                }
                
                return $resultado;
            } else {
                // En local, usar SMTP normal (funciona bien)
                Mail::to($destinatario)->send($mailable);
                
                Log::info('Correo enviado exitosamente via SMTP (local)', [
                    'destinatario' => $destinatario,
                    'mailable' => get_class($mailable)
                ]);
                
                return true;
            }
            
        } catch (\Exception $e) {
            Log::error('Error al enviar correo', [
                'destinatario' => $destinatario,
                'error' => $e->getMessage(),
                'mailable' => get_class($mailable)
            ]);
            return false;
        }
    }

    /**
     * Crear notificación para un usuario
     */
    public static function crear(
        User $usuario,
        string $tipo,
        string $titulo,
        string $mensaje,
        ?string $urlAccion = null
    ): Notificacion {
        return Notificacion::create([
            'user_id' => $usuario->id,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'url_accion' => $urlAccion,
        ]);
    }

    /**
     * Notificar al líder que alguien solicitó unirse al equipo
     */
    public static function solicitudEquipo(Equipo $equipo, Participante $solicitante)
    {
        Log::info('🔍 [DEBUG] solicitudEquipo llamado', [
            'equipo_id' => $equipo->id,
            'equipo_nombre' => $equipo->nombre,
            'solicitante_id' => $solicitante->id,
            'solicitante_nombre' => $solicitante->user->name
        ]);
        
        $lider = $equipo->lider->user;
        
        Log::info('🔍 [DEBUG] Líder obtenido', [
            'lider_id' => $lider->id,
            'lider_nombre' => $lider->name,
            'lider_email' => $lider->email
        ]);
        
        // Crear notificación
        $notificacion = self::crear(
            $lider,
            'solicitud_equipo',
            '🤝 Nueva solicitud para unirse',
            "{$solicitante->user->name} quiere unirse a tu equipo '{$equipo->nombre}'",
            route('equipos.show', $equipo)
        );
        
        Log::info('🔍 [DEBUG] Notificación creada', [
            'notificacion_id' => $notificacion->id
        ]);

        // Enviar correo
        Log::info('🔍 [DEBUG] Intentando enviar correo', [
            'destinatario' => $lider->email,
            'mail_enabled' => env('MAIL_ENABLED')
        ]);
        
        self::enviarCorreo(new SolicitudEquipoMail($equipo, $solicitante), $lider->email);
        
        Log::info('🔍 [DEBUG] solicitudEquipo completado');

        return $notificacion;
    }

    /**
     * Notificar al solicitante que fue aceptado en el equipo
     */
    public static function solicitudAceptada(Equipo $equipo, Participante $participante)
    {
        $usuario = $participante->user;
        
        // Crear notificación
        $notificacion = self::crear(
            $usuario,
            'solicitud_aceptada',
            '✅ ¡Bienvenido al equipo!',
            "Has sido aceptado en el equipo '{$equipo->nombre}'",
            route('equipos.show', $equipo)
        );

        // Enviar correo
        self::enviarCorreo(new SolicitudAceptadaMail($equipo, $participante), $usuario->email);

        return $notificacion;
    }

    /**
     * Notificar al solicitante que fue rechazado
     */
    public static function solicitudRechazada($usuario, $equipo)
    {
        return self::crear(
            $usuario,
            'solicitud_rechazada',
            '❌ Solicitud rechazada',
            "Tu solicitud para unirse a '{$equipo->nombre}' fue rechazada",
            route('equipos.index', $equipo->evento)
        );
    }

    /**
     * Notificar a los miembros del equipo sobre un nuevo mensaje
     */
    public static function nuevoMensajeEquipo($equipo, $autorMensaje)
    {
        $miembros = $equipo->miembrosActivos()
            ->where('participante_id', '!=', $autorMensaje->participante->id)
            ->get();

        foreach ($miembros as $participante) {
            self::crear(
                $participante->user,
                'mensaje_equipo',
                '💬 Nuevo mensaje en tu equipo',
                "{$autorMensaje->name} escribió en '{$equipo->nombre}'",
                route('equipos.show', $equipo) . '#chat'
            );
        }
    }

    /**
     * Notificar sobre un nuevo evento
     */
    public static function nuevoEvento($evento)
    {
        // Notificar a todos los participantes
        $participantes = User::whereHas('roles', function($q) {
            $q->where('nombre', 'participante');
        })->get();

        foreach ($participantes as $user) {
            // Crear notificación
            self::crear(
                $user,
                'nuevo_evento',
                '🎉 Nuevo evento disponible',
                "'{$evento->nombre}' ya está abierto para inscripciones",
                route('eventos.show', $evento)
            );

            // Enviar correo
            self::enviarCorreo(new NuevoEventoMail($evento), $user->email);
        }
    }

    /**
     * Notificar a los miembros del equipo sobre una evaluación recibida
     */
    public static function evaluacionRecibida($equipo, $evaluacion)
    {
        $miembros = $equipo->miembrosActivos()->get();

        foreach ($miembros as $participante) {
            // Crear notificación
            self::crear(
                $participante->user,
                'evaluacion_recibida',
                '⭐ Tu equipo fue evaluado',
                "Tu equipo '{$equipo->nombre}' recibió una nueva evaluación",
                route('equipos.show', $equipo)
            );

            // Enviar correo
            self::enviarCorreo(
                new EvaluacionCompletadaMail($equipo, $evaluacion), 
                $participante->user->email
            );
        }
    }

    /**
     * Alias para evaluacionRecibida (usado por JuezController)
     */
    public static function evaluacionCompletada(Equipo $equipo, float $calificacion)
    {
        return self::evaluacionRecibida($equipo, $calificacion);
    }

    /**
     * Notificar sobre una nueva tarea asignada
     */
    public static function tareaAsignada($tarea, $asignados)
    {
        foreach ($asignados as $participante) {
            self::crear(
                $participante->user,
                'tarea_asignada',
                '📋 Nueva tarea asignada',
                "Te asignaron: '{$tarea->titulo}' en {$tarea->equipo->nombre}",
                route('equipos.show', $tarea->equipo) . '#tareas'
            );
        }
    }

    /**
     * Notificar sobre un proyecto aprobado
     */
    public static function proyectoAprobado(Equipo $equipo)
    {
        $proyecto = $equipo->proyecto;
        $miembros = $equipo->miembrosActivos()->get();

        foreach ($miembros as $participante) {
            // Crear notificación
            self::crear(
                $participante->user,
                'proyecto_aprobado',
                '✅ Proyecto aprobado',
                "¡El proyecto de '{$equipo->nombre}' fue aprobado!",
                route('equipos.show', $equipo)
            );

            // Enviar correo
            self::enviarCorreo(
                new ProyectoAprobadoMail($equipo, $proyecto), 
                $participante->user->email
            );
        }
    }

    /**
     * Notificar sobre un proyecto rechazado
     */
    public static function proyectoRechazado($proyecto, $razon = null)
    {
        $miembros = $proyecto->equipo->miembrosActivos()->get();
        $mensaje = $razon 
            ? "El proyecto fue rechazado. Razón: {$razon}" 
            : "El proyecto de '{$proyecto->equipo->nombre}' fue rechazado";

        foreach ($miembros as $participante) {
            self::crear(
                $participante->user,
                'proyecto_rechazado',
                '❌ Proyecto rechazado',
                $mensaje,
                route('proyectos.edit', $proyecto->equipo)
            );
        }
    }

    /**
     * Notificar sobre constancia generada
     */
    public static function constanciaGenerada(Constancia $constancia)
    {
        $usuario = $constancia->participante->user;
        
        // Crear notificación
        $notificacion = self::crear(
            $usuario,
            'constancia_generada',
            '🏆 Constancia disponible',
            "Tu constancia de '{$constancia->evento->nombre}' está lista",
            route('admin.constancias.descargar', $constancia)
        );

        // Enviar correo
        self::enviarCorreo(new ConstanciaGeneradaMail($constancia), $usuario->email);

        return $notificacion;
    }

    /**
     * Notificar cuando un miembro abandona el equipo
     */
    public static function miembroAbandonoEquipo($equipo, $miembroQueAbandon, $lider)
    {
        return self::crear(
            $lider,
            'miembro_abandono',
            '👋 Miembro abandonó el equipo',
            "{$miembroQueAbandon->name} abandonó '{$equipo->nombre}'",
            route('equipos.show', $equipo)
        );
    }
}
