<?php

namespace App\Http\Middleware;

use App\Models\Evento;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActualizarEstadoEventosMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Actualizar estados de eventos automáticamente
        // Solo se ejecuta en requests de admin para no afectar performance
        if ($request->user() && $request->user()->isAdmin()) {
            Evento::actualizarEstadosAutomaticamente();
        }

        return $next($request);
    }
}
