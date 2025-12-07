<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Equipo;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index()
    {
        return view('admin.reportes.index');
    }

    public function getData(Request $request)
    {
        try {
            $eventoId = $request->input('evento_id');

            // Estadísticas generales
            $stats = [
                'total_participantes' => $this->getTotalParticipantes($eventoId),
                'equipos_formados' => $this->getEquiposFormados($eventoId),
                'promedio_miembros' => $this->getPromedioMiembros($eventoId),
                'tasa_finalizacion' => $this->getTasaFinalizacion($eventoId),
                'equipos_terminaron' => $this->getEquiposTerminaron($eventoId),
                'puntuacion_promedio' => $this->getPuntuacionPromedio($eventoId),
                'puntuacion_maxima' => $this->getPuntuacionMaxima($eventoId),
            ];

            // Participación por carrera
            $participacionCarrera = $this->getParticipacionPorCarrera($eventoId);

            // Estadísticas de equipos
            $estadisticasEquipos = [
                'completos' => $this->getEquiposCompletos($eventoId),
                'incompletos' => $this->getEquiposIncompletos($eventoId),
                'tamano_promedio' => $this->getPromedioMiembros($eventoId),
            ];

            // Distribución de roles
            $distribucionRoles = $this->getDistribucionRoles($eventoId);

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'participacion_carrera' => $participacionCarrera,
                'estadisticas_equipos' => $estadisticasEquipos,
                'distribucion_roles' => $distribucionRoles,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en ReportesController::getData: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    private function getTotalParticipantes($eventoId = null)
    {
        if ($eventoId) {
            $evento = Evento::find($eventoId);
            return $evento ? $evento->participantes()->count() : 0;
        }
        return Participante::count();
    }

    private function getEquiposFormados($eventoId = null)
    {
        if ($eventoId) {
            return Equipo::where('evento_id', $eventoId)->count();
        }
        return Equipo::count();
    }

    private function getPromedioMiembros($eventoId = null)
    {
        $query = Equipo::query();
        
        if ($eventoId) {
            $query->where('evento_id', $eventoId);
        }

        $equipos = $query->withCount('participantes')->get();
        
        if ($equipos->isEmpty()) {
            return 0;
        }

        $promedio = $equipos->avg('participantes_count');
        return round($promedio, 1);
    }

    private function getTasaFinalizacion($eventoId = null)
    {
        $query = Equipo::query();
        
        if ($eventoId) {
            $query->where('evento_id', $eventoId);
        }

        $total = $query->count();
        
        if ($total == 0) {
            return 0;
        }

        $conProyecto = (clone $query)->has('proyecto')->count();
        
        return round(($conProyecto / $total) * 100, 1);
    }

    private function getEquiposTerminaron($eventoId = null)
    {
        $query = Equipo::query();
        
        if ($eventoId) {
            $query->where('evento_id', $eventoId);
        }

        return $query->has('proyecto')->count();
    }

    private function getPuntuacionPromedio($eventoId = null)
    {
        $query = Evaluacion::query();
        
        if ($eventoId) {
            $query->whereHas('equipo', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            });
        }

        $promedio = $query->avg('puntuacion_total');
        return $promedio ? round($promedio, 1) : 0;
    }

    private function getPuntuacionMaxima($eventoId = null)
    {
        $query = Evaluacion::query();
        
        if ($eventoId) {
            $query->whereHas('equipo', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            });
        }

        $maxima = $query->max('puntuacion_total');
        return $maxima ? round($maxima, 1) : 100;
    }

    private function getParticipacionPorCarrera($eventoId = null)
    {
        $query = Participante::select('carreras.nombre as carrera', DB::raw('count(participantes.id) as total'))
            ->join('carreras', 'participantes.carrera_id', '=', 'carreras.id')
            ->groupBy('carreras.nombre')
            ->orderBy('total', 'desc');

        if ($eventoId) {
            $evento = Evento::find($eventoId);
            if ($evento) {
                $participantesIds = $evento->participantes()->pluck('participantes.id');
                $query->whereIn('participantes.id', $participantesIds);
            }
        }

        $resultados = $query->get();

        $total = $resultados->sum('total');

        return $resultados->map(function($item) use ($total) {
            return [
                'carrera' => $item->carrera,
                'total' => $item->total,
                'porcentaje' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0
            ];
        });
    }

    private function getEquiposCompletos($eventoId = null)
    {
        $query = Equipo::query();
        
        if ($eventoId) {
            $query->where('evento_id', $eventoId);
        }

        return $query->has('participantes', '>=', 5)->count();
    }

    private function getEquiposIncompletos($eventoId = null)
    {
        $query = Equipo::query();
        
        if ($eventoId) {
            $query->where('evento_id', $eventoId);
        }

        $total = $query->count();
        $completos = $this->getEquiposCompletos($eventoId);
        
        return $total - $completos;
    }

    private function getDistribucionRoles($eventoId = null)
    {
        $query = Participante::select('rol', DB::raw('count(*) as total'))
            ->whereNotNull('rol')
            ->groupBy('rol')
            ->orderBy('total', 'desc');

        if ($eventoId) {
            $evento = Evento::find($eventoId);
            if ($evento) {
                $participantesIds = $evento->participantes()->pluck('id');
                $query->whereIn('id', $participantesIds);
            }
        }

        $resultados = $query->get();

        $total = $resultados->sum('total');

        return $resultados->map(function($item) use ($total) {
            return [
                'rol' => $item->rol,
                'total' => $item->total,
                'porcentaje' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0
            ];
        });
    }
}
