<?php

namespace App\Helpers;

use App\Models\Notificacion;
use App\Models\User;

class NotificacionHelper
{
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
    public static function solicitudEquipo($lider, $solicitante, $equipo)
    {
        return self::crear(
            $lider,
            'solicitud_equipo',
            '🤝 Nueva solicitud para unirse',
            "{$solicitante->name} quiere unirse a tu equipo '{$equipo->nombre}'",
            route('equipos.show', $equipo)
        );
    }

    /**
     * Notificar al solicitante que fue aceptado en el equipo
     */
    public static function solicitudAceptada($usuario, $equipo)
    {
        return self::crear(
            $usuario,
            'solicitud_aceptada',
            '✅ ¡Bienvenido al equipo!',
            "Has sido aceptado en el equipo '{$equipo->nombre}'",
            route('equipos.show', $equipo)
        );
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
            self::crear(
                $user,
                'nuevo_evento',
                '🎉 Nuevo evento disponible',
                "'{$evento->nombre}' ya está abierto para inscripciones",
                route('eventos.show', $evento)
            );
        }
    }

    /**
     * Notificar a los miembros del equipo sobre una evaluación recibida
     */
    public static function evaluacionRecibida($equipo, $juez)
    {
        $miembros = $equipo->miembrosActivos()->get();

        foreach ($miembros as $participante) {
            self::crear(
                $participante->user,
                'evaluacion_recibida',
                '⭐ Tu equipo fue evaluado',
                "El juez {$juez->name} evaluó a '{$equipo->nombre}'",
                route('equipos.show', $equipo)
            );
        }
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
    public static function proyectoAprobado($proyecto)
    {
        $miembros = $proyecto->equipo->miembrosActivos()->get();

        foreach ($miembros as $participante) {
            self::crear(
                $participante->user,
                'proyecto_aprobado',
                '✅ Proyecto aprobado',
                "¡El proyecto de '{$proyecto->equipo->nombre}' fue aprobado!",
                route('equipos.show', $proyecto->equipo)
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
    public static function constanciaGenerada($usuario, $constancia)
    {
        return self::crear(
            $usuario,
            'constancia_generada',
            '🏆 Constancia disponible',
            "Tu constancia de '{$constancia->evento->nombre}' está lista",
            route('admin.constancias.descargar', $constancia)
        );
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
