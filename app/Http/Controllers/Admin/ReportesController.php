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

    public function test()
    {
        return response()->json([
            'success' => true,
            'message' => 'Controlador funcionando correctamente',
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $eventoId = $request->input('evento_id');
            
            \Log::info('ReportesController::getData iniciado', ['evento_id' => $eventoId]);

            // Probar cada método uno por uno
            \Log::info('Obteniendo total participantes...');
            $totalParticipantes = $this->getTotalParticipantes($eventoId);
            
            \Log::info('Obteniendo equipos formados...');
            $equiposFormados = $this->getEquiposFormados($eventoId);
            
            \Log::info('Obteniendo promedio miembros...');
            $promedioMiembros = $this->getPromedioMiembros($eventoId);
            
            \Log::info('Obteniendo tasa finalización...');
            $tasaFinalizacion = $this->getTasaFinalizacion($eventoId);
            
            \Log::info('Obteniendo equipos terminaron...');
            $equiposTerminaron = $this->getEquiposTerminaron($eventoId);
            
            \Log::info('Obteniendo puntuación promedio...');
            $puntuacionPromedio = $this->getPuntuacionPromedio($eventoId);
            
            \Log::info('Obteniendo puntuación máxima...');
            $puntuacionMaxima = $this->getPuntuacionMaxima($eventoId);

            // Estadísticas generales
            $stats = [
                'total_participantes' => $totalParticipantes,
                'equipos_formados' => $equiposFormados,
                'promedio_miembros' => $promedioMiembros,
                'tasa_finalizacion' => $tasaFinalizacion,
                'equipos_terminaron' => $equiposTerminaron,
                'puntuacion_promedio' => $puntuacionPromedio,
                'puntuacion_maxima' => $puntuacionMaxima,
            ];

            \Log::info('Obteniendo participación por carrera...');
            $participacionCarrera = $this->getParticipacionPorCarrera($eventoId);

            \Log::info('Obteniendo estadísticas de equipos...');
            // Estadísticas de equipos
            $estadisticasEquipos = [
                'completos' => $this->getEquiposCompletos($eventoId),
                'incompletos' => $this->getEquiposIncompletos($eventoId),
                'tamano_promedio' => $promedioMiembros,
            ];

            \Log::info('Obteniendo distribución de roles...');
            $distribucionRoles = $this->getDistribucionRoles($eventoId);

            \Log::info('Datos obtenidos exitosamente');

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'participacion_carrera' => $participacionCarrera,
                'estadisticas_equipos' => $estadisticasEquipos,
                'distribucion_roles' => $distribucionRoles,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en ReportesController::getData', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    private function getTotalParticipantes($eventoId = null)
    {
        if ($eventoId) {
            // Contar participantes únicos en equipos de este evento
            return Participante::whereHas('equipos', function($query) use ($eventoId) {
                $query->where('evento_id', $eventoId);
            })->count();
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

        $promedio = $query->avg('calificacion_total');
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

        $maxima = $query->max('calificacion_total');
        return $maxima ? round($maxima, 1) : 100;
    }

    private function getParticipacionPorCarrera($eventoId = null)
    {
        $query = Participante::select('carreras.nombre as carrera', DB::raw('count(participantes.id) as total'))
            ->join('carreras', 'participantes.carrera_id', '=', 'carreras.id')
            ->groupBy('carreras.nombre')
            ->orderBy('total', 'desc');

        if ($eventoId) {
            // Filtrar por participantes en equipos de este evento
            $query->whereHas('equipos', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            });
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
            // Filtrar por participantes en equipos de este evento
            $query->whereHas('equipos', function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            });
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
