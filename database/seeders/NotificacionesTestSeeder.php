<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notificacion;
use App\Models\User;

class NotificacionesTestSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el primer usuario participante
        $user = User::whereHas('roles', function($query) {
            $query->where('nombre', 'participante');
        })->first();

        if (!$user) {
            $this->command->info('No hay usuarios participantes para crear notificaciones de prueba');
            return;
        }

        // Crear notificaciones de prueba
        Notificacion::create([
            'user_id' => $user->id,
            'tipo' => 'nuevo_evento',
            'titulo' => '🎯 Nuevo evento disponible',
            'mensaje' => '¡Hackathon de Inteligencia Artificial ya está abierto para inscripciones!',
            'url_accion' => route('eventos.index'),
            'leida' => false,
        ]);

        Notificacion::create([
            'user_id' => $user->id,
            'tipo' => 'solicitud_aceptada',
            'titulo' => '🎉 ¡Te aceptaron en el equipo!',
            'mensaje' => 'Ahora eres miembro de CodeMasters',
            'url_accion' => route('dashboard'),
            'leida' => false,
        ]);

        Notificacion::create([
            'user_id' => $user->id,
            'tipo' => 'mensaje_equipo',
            'titulo' => '💬 Nuevo mensaje en el equipo',
            'mensaje' => 'Juan escribió en CodeMasters',
            'url_accion' => route('dashboard'),
            'leida' => false,
        ]);

        Notificacion::create([
            'user_id' => $user->id,
            'tipo' => 'tarea_asignada',
            'titulo' => '📋 Nueva tarea asignada',
            'mensaje' => 'Te asignaron: Implementar sistema de autenticación',
            'url_accion' => route('dashboard'),
            'leida' => false,
        ]);

        $this->command->info('✅ Notificaciones de prueba creadas para ' . $user->name);
    }
}
