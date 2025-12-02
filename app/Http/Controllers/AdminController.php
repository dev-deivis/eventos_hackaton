<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Participante;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Log;

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

    /**
     * Mostrar proyectos pendientes de aprobación
     */
    public function proyectosPendientes()
    {
        // Obtener proyectos entregados esperando aprobación
        $proyectos = Proyecto::where('estado', 'entregado')
            ->with(['equipo.participantes.user', 'equipo.evento', 'tareas'])
            ->orderBy('fecha_entrega', 'asc')
            ->get();
        
        return view('admin.proyectos.pendientes', compact('proyectos'));
    }

    /**
     * Ver detalles de un proyecto para revisión
     */
    public function revisarProyecto(Proyecto $proyecto)
    {
        // Cargar relaciones
        $proyecto->load([
            'equipo.participantes.user',
            'equipo.evento',
            'tareas.participantes.user'
        ]);
        
        return view('admin.proyectos.revisar', compact('proyecto'));
    }

    /**
     * Aprobar proyecto para evaluación
     */
    public function aprobarProyecto(Proyecto $proyecto)
    {
        // Verificar que esté en estado entregado
        if ($proyecto->estado !== 'entregado') {
            return redirect()->back()
                ->with('error', 'Este proyecto no está en estado de entregado.');
        }

        try {
            // Aprobar proyecto
            $proyecto->aprobarParaEvaluacion();

            Log::info('Proyecto aprobado para evaluación', [
                'proyecto_id' => $proyecto->id,
                'equipo_id' => $proyecto->equipo_id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.proyectos.pendientes')
                ->with('success', "Proyecto '{$proyecto->nombre}' aprobado exitosamente. Ahora puede ser evaluado por los jueces.");

        } catch (\Exception $e) {
            Log::error('Error al aprobar proyecto:', [
                'error' => $e->getMessage(),
                'proyecto_id' => $proyecto->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Error al aprobar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar proyecto
     */
    public function rechazarProyecto(Request $request, Proyecto $proyecto)
    {
        // Verificar que esté en estado entregado
        if ($proyecto->estado !== 'entregado') {
            return redirect()->back()
                ->with('error', 'Este proyecto no está en estado de entregado.');
        }

        $validated = $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        try {
            // Rechazar proyecto
            $proyecto->rechazarProyecto($validated['motivo']);

            Log::info('Proyecto rechazado', [
                'proyecto_id' => $proyecto->id,
                'equipo_id' => $proyecto->equipo_id,
                'motivo' => $validated['motivo'],
                'admin_id' => auth()->id()
            ]);

            return redirect()->route('admin.proyectos.pendientes')
                ->with('success', "Proyecto '{$proyecto->nombre}' rechazado. El equipo debe completar los requisitos faltantes.");

        } catch (\Exception $e) {
            Log::error('Error al rechazar proyecto:', [
                'error' => $e->getMessage(),
                'proyecto_id' => $proyecto->id,
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()
                ->with('error', 'Error al rechazar el proyecto: ' . $e->getMessage());
        }
    }
}
