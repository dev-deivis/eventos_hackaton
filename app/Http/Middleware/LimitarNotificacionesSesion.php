<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LimitarNotificacionesSesion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado
        if (auth()->check()) {
            // Auto-marcar notificaciones antiguas como leídas si hay demasiadas
            $countNoLeidas = auth()->user()
                ->notificaciones()
                ->where('leida', false)
                ->count();

            // Si hay más de 50 notificaciones no leídas, marcar las más antiguas
            if ($countNoLeidas > 50) {
                auth()->user()
                    ->notificaciones()
                    ->where('leida', false)
                    ->orderBy('created_at', 'asc')
                    ->take($countNoLeidas - 20) // Dejar solo las 20 más recientes
                    ->update([
                        'leida' => true,
                        'leida_en' => now()
                    ]);
            }
        }

        return $next($request);
    }
}
