<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;

class NotificationService
{
    /**
     * Tipos de notificaciones
     */
    const SOLICITUD_EQUIPO = 'solicitud_equipo';
    const SOLICITUD_ACEPTADA = 'solicitud_aceptada';
    const SOLICITUD_RECHAZADA = 'solicitud_rechazada';
    const NUEVO_MIEMBRO_EQUIPO = 'nuevo_miembro_equipo';
    const MENSAJE_EQUIPO = 'mensaje_equipo';
    const TAREA_ASIGNADA = 'tarea_asignada';
    const TAREA_COMPLETADA = 'tarea_completada';
    const EVALUACION_RECIBIDA = 'evaluacion_recibida';
    const PROYECTO_APROBADO = 'proyecto_aprobado';
    const PROYECTO_RECHAZADO = 'proyecto_rechazado';
    const NUEVO_EVENTO = 'nuevo_evento';
    const EVENTO_PROXIMO = 'evento_proximo';
    const CONSTANCIA_GENERADA = 'constancia_generada';
    const MIEMBRO_ABANDONO = 'miembro_abandono';

    /**
     * Crear notificación para solicitud de unión a equipo
     */
    public static function solicitudEquipo($liderUserId, $solicitante, $equipo)
    {
        return self::crear(
            userId: $liderUserId,
            tipo: self::SOLICITUD_EQUIPO,
            titulo: '🙋 Nueva solicitud para unirse a tu equipo',
            mensaje: "{$solicitante->user->name} quiere unirse a {$equipo->nombre}",
            urlAccion: route('equipos.show', $equipo)
        );
    }

    /**
     * Notificar solicitud aceptada
     */
    public static function solicitudAceptada($participanteUserId, $equipo)
    {
        return self::crear(
            userId: $participanteUserId,
            tipo: self::SOLICITUD_ACEPTADA,
            titulo: '🎉 ¡Te aceptaron en el equipo!',
            mensaje: "Ahora eres miembro de {$equipo->nombre}",
            urlAccion: route('equipos.show', $equipo)
        );
    }

    /**
     * Notificar solicitud rechazada
     */
    public static function solicitudRechazada($participanteUserId, $equipo)
    {
        return self::crear(
            userId: $participanteUserId,
            tipo: self::SOLICITUD_RECHAZADA,
            titulo: '❌ Solicitud rechazada',
            mensaje: "Tu solicitud para unirte a {$equipo->nombre} fue rechazada",
            urlAccion: route('equipos.index', $equipo->evento)
        );
    }

    /**
     * Notificar a todos los miembros del equipo sobre nuevo integrante
     */
    public static function nuevoMiembro($equipo, $nuevoMiembro, $exceptoUserId = null)
    {
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->get();

        foreach ($miembros as $miembro) {
            // No notificar al nuevo miembro ni al líder que lo aceptó
            if ($miembro->user_id == $nuevoMiembro->user_id || $miembro->user_id == $exceptoUserId) {
                continue;
            }

            self::crear(
                userId: $miembro->user_id,
                tipo: self::NUEVO_MIEMBRO_EQUIPO,
                titulo: '👥 Nuevo miembro en el equipo',
                mensaje: "{$nuevoMiembro->user->name} se unió a {$equipo->nombre}",
                urlAccion: route('equipos.show', $equipo)
            );
        }
    }

    /**
     * Notificar nuevo mensaje en el chat del equipo
     */
    public static function mensajeEquipo($equipo, $remitente)
    {
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->where('user_id', '!=', $remitente->id)
            ->get();

        foreach ($miembros as $miembro) {
            self::crear(
                userId: $miembro->user_id,
                tipo: self::MENSAJE_EQUIPO,
                titulo: '💬 Nuevo mensaje en el equipo',
                mensaje: "{$remitente->name} escribió en {$equipo->nombre}",
                urlAccion: route('equipos.show', $equipo) . '#chat'
            );
        }
    }

    /**
     * Notificar tarea asignada
     */
    public static function tareaAsignada($tarea, $asignadosUserIds)
    {
        foreach ($asignadosUserIds as $userId) {
            self::crear(
                userId: $userId,
                tipo: self::TAREA_ASIGNADA,
                titulo: '📋 Nueva tarea asignada',
                mensaje: "Te asignaron: {$tarea->titulo}",
                urlAccion: route('equipos.show', $tarea->proyecto->equipo) . '#tareas'
            );
        }
    }

    /**
     * Notificar tarea completada
     */
    public static function tareaCompletada($tarea, $completadoPor)
    {
        $equipo = $tarea->proyecto->equipo;
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->where('user_id', '!=', $completadoPor->id)
            ->get();

        foreach ($miembros as $miembro) {
            self::crear(
                userId: $miembro->user_id,
                tipo: self::TAREA_COMPLETADA,
                titulo: '✅ Tarea completada',
                mensaje: "{$completadoPor->name} completó: {$tarea->titulo}",
                urlAccion: route('equipos.show', $equipo) . '#tareas'
            );
        }
    }

    /**
     * Notificar evaluación recibida
     */
    public static function evaluacionRecibida($equipo, $juez, $calificacion)
    {
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->get();

        foreach ($miembros as $miembro) {
            self::crear(
                userId: $miembro->user_id,
                tipo: self::EVALUACION_RECIBIDA,
                titulo: '⭐ Tu equipo fue evaluado',
                mensaje: "Calificación recibida: {$calificacion}/100 puntos",
                urlAccion: route('equipos.show', $equipo)
            );
        }
    }

    /**
     * Notificar proyecto aprobado
     */
    public static function proyectoAprobado($proyecto)
    {
        $equipo = $proyecto->equipo;
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->get();

        foreach ($miembros as $miembro) {
            self::crear(
                userId: $miembro->user_id,
                tipo: self::PROYECTO_APROBADO,
                titulo: '🎉 ¡Proyecto aprobado!',
                mensaje: "El proyecto de {$equipo->nombre} fue aprobado",
                urlAccion: route('equipos.show', $equipo)
            );
        }
    }

    /**
     * Notificar proyecto rechazado
     */
    public static function proyectoRechazado($proyecto, $motivo = null)
    {
        $equipo = $proyecto->equipo;
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->get();

        $mensaje = $motivo 
            ? "Proyecto rechazado. Motivo: {$motivo}"
            : "El proyecto de {$equipo->nombre} necesita revisión";

        foreach ($miembros as $miembro) {
            self::crear(
                userId: $miembro->user_id,
                tipo: self::PROYECTO_RECHAZADO,
                titulo: '⚠️ Proyecto requiere cambios',
                mensaje: $mensaje,
                urlAccion: route('proyectos.edit', $equipo)
            );
        }
    }

    /**
     * Notificar nuevo evento abierto
     */
    public static function nuevoEvento($evento)
    {
        // Notificar a todos los usuarios con rol participante
        $participantes = User::whereHas('roles', function($query) {
            $query->where('nombre', 'participante');
        })->get();

        foreach ($participantes as $user) {
            self::crear(
                userId: $user->id,
                tipo: self::NUEVO_EVENTO,
                titulo: '🎯 Nuevo evento disponible',
                mensaje: "¡{$evento->nombre} ya está abierto para inscripciones!",
                urlAccion: route('eventos.show', $evento)
            );
        }
    }

    /**
     * Notificar evento próximo a iniciar
     */
    public static function eventoProximo($evento, $participantesUserIds)
    {
        foreach ($participantesUserIds as $userId) {
            self::crear(
                userId: $userId,
                tipo: self::EVENTO_PROXIMO,
                titulo: '⏰ Evento próximo a iniciar',
                mensaje: "{$evento->nombre} inicia en menos de 24 horas",
                urlAccion: route('eventos.show', $evento)
            );
        }
    }

    /**
     * Notificar constancia generada
     */
    public static function constanciaGenerada($constancia)
    {
        $participante = $constancia->participante;
        
        self::crear(
            userId: $participante->user_id,
            tipo: self::CONSTANCIA_GENERADA,
            titulo: '🏆 Constancia disponible',
            mensaje: "Tu constancia de {$constancia->evento->nombre} está lista",
            urlAccion: route('profile.show') . '#constancias'
        );
    }

    /**
     * Notificar cuando un miembro abandona el equipo
     */
    public static function miembroAbandono($equipo, $miembroQueAbandona)
    {
        $miembros = $equipo->participantes()
            ->where('equipo_participante.estado', 'activo')
            ->where('user_id', '!=', $miembroQueAbandona->user_id)
            ->get();

        foreach ($miembros as $miembro) {
            self::crear(
                userId: $miembro->user_id,
                tipo: self::MIEMBRO_ABANDONO,
                titulo: '👋 Miembro abandonó el equipo',
                mensaje: "{$miembroQueAbandona->user->name} abandonó {$equipo->nombre}",
                urlAccion: route('equipos.show', $equipo)
            );
        }
    }

    /**
     * Método base para crear notificación
     */
    private static function crear($userId, $tipo, $titulo, $mensaje, $urlAccion = null)
    {
        return Notificacion::create([
            'user_id' => $userId,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'url_accion' => $urlAccion,
            'leida' => false,
        ]);
    }

    // ==========================================
    // NOTIFICACIONES PARA ADMIN
    // ==========================================

    /**
     * Notificar a admins sobre proyecto entregado (esperando aprobación)
     */
    public static function proyectoEntregado($proyecto)
    {
        // Obtener usuarios con rol admin
        $admins = User::whereHas('roles', function($query) {
            $query->where('nombre', 'admin');
        })->get();
        
        foreach ($admins as $admin) {
            self::crear(
                userId: $admin->id,
                tipo: 'proyecto_entregado',
                titulo: '📋 Proyecto esperando aprobación',
                mensaje: "El equipo {$proyecto->equipo->nombre} entregó su proyecto '{$proyecto->nombre}'",
                urlAccion: route('admin.proyectos.revisar', $proyecto)
            );
        }
    }

    /**
     * Notificar a admins sobre nuevo equipo registrado
     */
    public static function nuevoEquipoRegistrado($equipo)
    {
        // Obtener usuarios con rol admin
        $admins = User::whereHas('roles', function($query) {
            $query->where('nombre', 'admin');
        })->get();
        
        foreach ($admins as $admin) {
            self::crear(
                userId: $admin->id,
                tipo: 'nuevo_equipo',
                titulo: '👥 Nuevo equipo registrado',
                mensaje: "El equipo '{$equipo->nombre}' se registró en {$equipo->evento->nombre}",
                urlAccion: route('eventos.show', $equipo->evento)
            );
        }
    }

    // ==========================================
    // NOTIFICACIONES PARA JUEZ
    // ==========================================

    /**
     * Notificar a juez sobre nuevo equipo asignado
     */
    public static function equipoAsignadoAJuez($juez, $equipo)
    {
        self::crear(
            userId: $juez->id,
            tipo: 'equipo_asignado',
            titulo: '📝 Nuevo equipo asignado',
            mensaje: "Se te asignó el equipo '{$equipo->nombre}' para evaluar",
            urlAccion: route('juez.evaluar', $equipo)
        );
    }

    /**
     * Notificar a jueces sobre proyecto listo para evaluar
     */
    public static function proyectoListoParaEvaluar($proyecto)
    {
        $equipo = $proyecto->equipo;
        
        // Obtener jueces asignados al equipo
        $jueces = $equipo->jueces;
        
        foreach ($jueces as $juez) {
            // Verificar que no haya evaluado ya
            $yaEvaluo = \App\Models\Evaluacion::where('equipo_id', $equipo->id)
                ->where('juez_id', $juez->id)
                ->exists();
                
            if (!$yaEvaluo) {
                self::crear(
                    userId: $juez->id,
                    tipo: 'proyecto_listo',
                    titulo: '✅ Proyecto listo para evaluar',
                    mensaje: "El proyecto '{$proyecto->nombre}' del equipo {$equipo->nombre} está listo",
                    urlAccion: route('juez.evaluar', $equipo)
                );
            }
        }
    }
}
