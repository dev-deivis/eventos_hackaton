<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Participante;

class AdminController extends Controller
{
    /**
     * Dashboard del administrador
     */
    public function dashboard()
    {
        // Estadísticas generales
        $totalUsuarios = User::count();
        $totalEventos = Evento::count();
        $totalEquipos = Equipo::count();
        $totalEvaluaciones = Evaluacion::count();
        
        // Eventos recientes
        $eventosRecientes = Evento::with('equipos')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsuarios',
            'totalEventos',
            'totalEquipos',
            'totalEvaluaciones',
            'eventosRecientes'
        ));
    }
    
    /**
     * Ver rankings consolidados de todos los equipos
     */
    public function rankings()
    {
        // Obtener equipos con sus promedios de evaluación y número de evaluaciones
        $equipos = Equipo::select('equipos.*')
            ->selectRaw('AVG(evaluaciones.calificacion_total) as calificacion_promedio')
            ->selectRaw('COUNT(evaluaciones.id) as num_evaluaciones')
            ->selectRaw('AVG(evaluaciones.implementacion) as implementacion_promedio')
            ->selectRaw('AVG(evaluaciones.innovacion) as innovacion_promedio')
            ->selectRaw('AVG(evaluaciones.presentacion) as presentacion_promedio')
            ->selectRaw('AVG(evaluaciones.trabajo_equipo) as trabajo_equipo_promedio')
            ->selectRaw('AVG(evaluaciones.viabilidad) as viabilidad_promedio')
            ->join('evaluaciones', 'equipos.id', '=', 'evaluaciones.equipo_id')
            ->with(['evento', 'participantes', 'proyecto'])
            ->groupBy('equipos.id')
            ->orderByDesc('calificacion_promedio')
            ->paginate(20);
        
        return view('admin.rankings', compact('equipos'));
    }
}
