<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Evento;

class JuezController extends Controller
{
    /**
     * Mostrar el dashboard del juez
     */
    public function dashboard()
    {
        $juez = auth()->user();
        
        // Obtener equipos asignados al juez que aún no ha evaluado
        $equiposPendientes = $juez->equiposAsignados()
            ->with(['evento', 'participantes', 'proyecto'])
            ->whereDoesntHave('evaluaciones', function($query) use ($juez) {
                $query->where('juez_id', $juez->id);
            })
            ->get();
        
        // Estadísticas del juez
        $totalAsignados = $juez->equiposAsignados()->count();
        $evaluacionesCompletadas = Evaluacion::where('juez_id', $juez->id)->count();
        $promedioCalificacion = Evaluacion::where('juez_id', $juez->id)
            ->avg('calificacion_total') ?? 0;
        
        // Calcular tiempo promedio (simplificado por ahora)
        $tiempoPromedio = 25;
        
        return view('juez.dashboard', compact(
            'equiposPendientes',
            'totalAsignados',
            'evaluacionesCompletadas',
            'promedioCalificacion',
            'tiempoPromedio'
        ));
    }
    
    /**
     * Mostrar formulario de evaluación
     */
    public function evaluar(Equipo $equipo)
    {
        $juez = auth()->user();
        
        // Verificar que el equipo está asignado a este juez
        if (!$juez->equiposAsignados()->where('equipos.id', $equipo->id)->exists()) {
            return redirect()->route('juez.dashboard')
                ->with('error', 'Este equipo no está asignado a ti para evaluación.');
        }
        
        // Verificar que el juez no haya evaluado ya este equipo
        $evaluacionExistente = Evaluacion::where('equipo_id', $equipo->id)
            ->where('juez_id', $juez->id)
            ->first();
            
        if ($evaluacionExistente) {
            return redirect()->route('juez.dashboard')
                ->with('info', 'Ya has evaluado este equipo anteriormente.');
        }
        
        // Verificar que el equipo tiene proyecto
        if (!$equipo->proyecto) {
            return redirect()->route('juez.dashboard')
                ->with('warning', 'Este equipo aún no ha presentado su proyecto.');
        }
        
        // 🆕 VALIDACIÓN CRÍTICA: Verificar que el proyecto está listo para evaluar
        if (!$equipo->proyecto->estaListoParaEvaluar()) {
            $estado = $equipo->proyecto->estadoTexto;
            $porcentaje = $equipo->proyecto->porcentaje_completado;
            
            return redirect()->route('juez.dashboard')
                ->with('warning', "Este proyecto no está listo para evaluar. Estado actual: {$estado} ({$porcentaje}% completo). Debe estar en estado 'Listo para Evaluar'.");
        }
        
        // Cargar relaciones necesarias
        $equipo->load(['evento', 'participantes.user', 'proyecto.tareas']);
        
        return view('juez.evaluar', compact('equipo'));
    }
    
    /**
     * Guardar evaluación
     */
    public function guardarEvaluacion(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'implementacion' => ['required', 'numeric', 'min:0', 'max:100'],
            'innovacion' => ['required', 'numeric', 'min:0', 'max:100'],
            'presentacion' => ['required', 'numeric', 'min:0', 'max:100'],
            'trabajo_equipo' => ['required', 'numeric', 'min:0', 'max:100'],
            'viabilidad' => ['required', 'numeric', 'min:0', 'max:100'],
            'comentarios' => ['nullable', 'string', 'max:1000'],
        ]);
        
        // Calcular calificación total con pesos específicos
        $calificacionTotal = (
            ($validated['implementacion'] * 0.30) +
            ($validated['innovacion'] * 0.25) +
            ($validated['presentacion'] * 0.20) +
            ($validated['trabajo_equipo'] * 0.15) +
            ($validated['viabilidad'] * 0.10)
        );
        
        // Crear evaluación
        $evaluacion = Evaluacion::create([
            'equipo_id' => $equipo->id,
            'juez_id' => auth()->id(),
            'implementacion' => $validated['implementacion'],
            'innovacion' => $validated['innovacion'],
            'presentacion' => $validated['presentacion'],
            'trabajo_equipo' => $validated['trabajo_equipo'],
            'viabilidad' => $validated['viabilidad'],
            'calificacion_total' => $calificacionTotal,
            'comentarios' => $validated['comentarios'],
            'fecha_evaluacion' => now(),
        ]);
        
        // 🆕 Marcar proyecto como evaluado si es la primera evaluación
        if ($equipo->proyecto && $equipo->proyecto->estado === 'listo_para_evaluar') {
            $equipo->proyecto->marcarComoEvaluado();
        }
        
        return redirect()->route('juez.dashboard')
            ->with('success', 'Evaluación guardada exitosamente. Calificación final: ' . round($calificacionTotal, 2) . ' puntos.');
    }
    
    /**
     * Ver mis evaluaciones
     */
    public function misEvaluaciones()
    {
        $juez = auth()->user();
        
        // Obtener evaluaciones con relaciones
        $evaluaciones = Evaluacion::where('juez_id', $juez->id)
            ->with(['equipo.proyecto', 'equipo.evento'])
            ->orderBy('fecha_evaluacion', 'desc')
            ->paginate(10);
        
        // Estadísticas
        $totalEvaluaciones = Evaluacion::where('juez_id', $juez->id)->count();
        $promedioCalificacion = Evaluacion::where('juez_id', $juez->id)
            ->avg('calificacion_total') ?? 0;
        $ultimaEvaluacion = Evaluacion::where('juez_id', $juez->id)
            ->orderBy('fecha_evaluacion', 'desc')
            ->first();
        
        return view('juez.evaluaciones', compact(
            'evaluaciones',
            'totalEvaluaciones',
            'promedioCalificacion',
            'ultimaEvaluacion'
        ));
    }
    
    /**
     * Ver rankings
     */
    public function rankings()
    {
        // Obtener equipos con sus promedios de evaluación
        $equipos = Equipo::select('equipos.*')
            ->selectRaw('AVG(evaluaciones.calificacion_total) as calificacion_promedio')
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
        
        // Estadísticas generales
        $totalEquipos = Equipo::count();
        $equiposEvaluados = Equipo::has('evaluaciones')->count();
        $promedioGeneral = Evaluacion::avg('calificacion_total') ?? 0;
        $mejorPuntuacion = Equipo::select('equipos.*')
            ->selectRaw('AVG(evaluaciones.calificacion_total) as calificacion_promedio')
            ->join('evaluaciones', 'equipos.id', '=', 'evaluaciones.equipo_id')
            ->groupBy('equipos.id')
            ->orderByDesc('calificacion_promedio')
            ->first();
        
        return view('juez.rankings', compact(
            'equipos',
            'totalEquipos',
            'equiposEvaluados',
            'promedioGeneral',
            'mejorPuntuacion'
        ));
    }
}
