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

    /**
     * Mostrar reportes y análisis
     */
    public function reportes()
    {
        return view('admin.reportes.index');
    }

    /**
     * Obtener datos para reportes (API)
     */
    public function datosReportes(Request $request)
    {
        $eventoId = $request->get('evento_id');
        
        // Query base
        $participantesQuery = \App\Models\Participante::query();
        $equiposQuery = \App\Models\Equipo::query();
        
        if ($eventoId) {
            $participantesQuery->whereHas('equipos', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            });
            $equiposQuery->where('evento_id', $eventoId);
        }
        
        // KPIs
        $totalParticipantes = $participantesQuery->count();
        $totalEquipos = $equiposQuery->count();
        
        $equiposConProyecto = $equiposQuery->has('proyecto')->count();
        $tasaFinalizacion = $totalEquipos > 0 ? round(($equiposConProyecto / $totalEquipos) * 100, 1) : 0;
        
        $evaluacionesQuery = \App\Models\Evaluacion::query();
        if ($eventoId) {
            $evaluacionesQuery->whereHas('equipo', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            });
        }
        
        $promedioCalificacion = round($evaluacionesQuery->avg('calificacion_total') ?? 0, 1);
        $maximaCalificacion = round($evaluacionesQuery->max('calificacion_total') ?? 100, 1);
        
        // Promedio de miembros por equipo
        $promedioMiembros = \DB::table('equipo_participante')
            ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
            ->where('equipo_participante.estado', 'activo')
            ->when($eventoId, function($q) use ($eventoId) {
                $q->where('equipos.evento_id', $eventoId);
            })
            ->groupBy('equipo_participante.equipo_id')
            ->selectRaw('COUNT(*) as total')
            ->get()
            ->avg('total');
        
        // Participantes por carrera
        $participantesPorCarrera = \DB::table('participantes')
            ->join('carreras', 'participantes.carrera_id', '=', 'carreras.id')
            ->when($eventoId, function($q) use ($eventoId) {
                $q->join('equipo_participante', 'participantes.id', '=', 'equipo_participante.participante_id')
                  ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                  ->where('equipos.evento_id', $eventoId);
            })
            ->select('carreras.nombre', \DB::raw('COUNT(DISTINCT participantes.id) as total'))
            ->groupBy('carreras.nombre')
            ->orderByDesc('total')
            ->get();
        
        // Distribución de roles
        $rolesPorParticipante = \DB::table('equipo_participante')
            ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
            ->where('equipo_participante.estado', 'activo')
            ->when($eventoId, function($q) use ($eventoId) {
                $q->where('equipos.evento_id', $eventoId);
            })
            ->select('equipo_participante.rol_equipo as rol', \DB::raw('COUNT(*) as total'))
            ->whereNotNull('equipo_participante.rol_equipo')
            ->groupBy('equipo_participante.rol_equipo')
            ->orderByDesc('total')
            ->get();
        
        // Estadísticas de equipos
        $equiposCompletos = $equiposQuery->clone()
            ->whereRaw('(SELECT COUNT(*) FROM equipo_participante WHERE equipo_id = equipos.id AND estado = "activo") >= 3')
            ->count();
        
        $equiposIncompletos = $totalEquipos - $equiposCompletos;
        
        return response()->json([
            'kpis' => [
                'participantes' => $totalParticipantes,
                'equipos' => $totalEquipos,
                'tasa_finalizacion' => $tasaFinalizacion,
                'equipos_terminados' => $equiposConProyecto,
                'puntuacion_promedio' => $promedioCalificacion,
                'puntuacion_maxima' => $maximaCalificacion,
                'promedio_miembros' => number_format($promedioMiembros ?? 0, 1)
            ],
            'carreras' => $participantesPorCarrera,
            'roles' => $rolesPorParticipante,
            'estadisticas_equipos' => [
                'completos' => $equiposCompletos,
                'incompletos' => $equiposIncompletos,
                'promedio' => number_format($promedioMiembros ?? 0, 1)
            ]
        ]);
    }
}
