<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario
     */
    public function index()
    {
        $notificaciones = auth()->user()
            ->notificaciones()
            ->recientes()
            ->paginate(20);

        return view('notificaciones.index', compact('notificaciones'));
    }

    /**
     * Obtener notificaciones no leídas del usuario (para polling)
     */
    public function obtenerNoLeidas()
    {
        $notificaciones = auth()->user()
            ->notificaciones()
            ->noLeidas()
            ->recientes()
            ->take(10)
            ->get()
            ->map(function ($notificacion) {
                return [
                    'id' => $notificacion->id,
                    'tipo' => $notificacion->tipo,
                    'titulo' => $notificacion->titulo,
                    'mensaje' => $notificacion->mensaje,
                    'url_accion' => $notificacion->url_accion,
                    'created_at' => $notificacion->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notificaciones' => $notificaciones,
            'count' => $notificaciones->count(),
        ]);
    }

    /**
     * Marcar notificación como leída y redirigir
     */
    public function marcarLeida($id)
    {
        $notificacion = auth()->user()->notificaciones()->findOrFail($id);
        $notificacion->marcarComoLeida();

        if ($notificacion->url_accion) {
            return redirect($notificacion->url_accion);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas()
    {
        auth()->user()->notificaciones()
            ->where('leida', false)
            ->update([
                'leida' => true,
                'leida_en' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas'
        ]);
    }
}
