<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participante;
use App\Models\Equipo;
use App\Models\Evaluacion;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\ReportesExport;

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
            
            Log::info('ReportesController::getData iniciado', ['evento_id' => $eventoId]);

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
                'tamano_promedio' => $stats['promedio_miembros'],
            ];

            // Distribución de roles
            $distribucionRoles = $this->getDistribucionRoles($eventoId);

            Log::info('Datos obtenidos exitosamente');

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'participacion_carrera' => $participacionCarrera,
                'estadisticas_equipos' => $estadisticasEquipos,
                'distribucion_roles' => $distribucionRoles,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en ReportesController::getData', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    private function getTotalParticipantes($eventoId = null)
    {
        if ($eventoId) {
            // Contar participantes únicos en equipos de este evento
            return DB::table('participantes')
                ->join('equipo_participante', 'participantes.id', '=', 'equipo_participante.participante_id')
                ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $eventoId)
                ->distinct()
                ->count('participantes.id');
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
        $query = DB::table('equipos')
            ->leftJoin('equipo_participante', 'equipos.id', '=', 'equipo_participante.equipo_id')
            ->select('equipos.id', DB::raw('COUNT(equipo_participante.participante_id) as miembros_count'))
            ->groupBy('equipos.id');

        if ($eventoId) {
            $query->where('equipos.evento_id', $eventoId);
        }

        $resultados = $query->get();
        
        if ($resultados->isEmpty()) {
            return 0;
        }

        $promedio = $resultados->avg('miembros_count');
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

        $conProyecto = Equipo::query()
            ->when($eventoId, function($q) use ($eventoId) {
                $q->where('evento_id', $eventoId);
            })
            ->whereHas('proyecto')
            ->count();
        
        return round(($conProyecto / $total) * 100, 1);
    }

    private function getEquiposTerminaron($eventoId = null)
    {
        $query = Equipo::query();
        
        if ($eventoId) {
            $query->where('evento_id', $eventoId);
        }

        return $query->whereHas('proyecto')->count();
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
        $query = DB::table('participantes')
            ->join('carreras', 'participantes.carrera_id', '=', 'carreras.id')
            ->select('carreras.nombre as carrera', DB::raw('COUNT(participantes.id) as total'));

        if ($eventoId) {
            $query->join('equipo_participante', 'participantes.id', '=', 'equipo_participante.participante_id')
                ->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $eventoId);
        }

        $resultados = $query
            ->groupBy('carreras.nombre')
            ->orderByDesc('total')
            ->get();

        $total = $resultados->sum('total');

        return $resultados->map(function($item) use ($total) {
            return [
                'carrera' => $item->carrera,
                'total' => $item->total,
                'porcentaje' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0
            ];
        })->toArray();
    }

    private function getEquiposCompletos($eventoId = null)
    {
        $query = DB::table('equipos')
            ->leftJoin('equipo_participante', 'equipos.id', '=', 'equipo_participante.equipo_id')
            ->select('equipos.id', DB::raw('COUNT(equipo_participante.participante_id) as miembros_count'))
            ->groupBy('equipos.id')
            ->havingRaw('COUNT(equipo_participante.participante_id) >= 5');

        if ($eventoId) {
            $query->where('equipos.evento_id', $eventoId);
        }

        return $query->count();
    }

    private function getEquiposIncompletos($eventoId = null)
    {
        $total = $this->getEquiposFormados($eventoId);
        $completos = $this->getEquiposCompletos($eventoId);
        
        return $total - $completos;
    }

    private function getDistribucionRoles($eventoId = null)
    {
        $query = DB::table('equipo_participante')
            ->join('perfiles', 'equipo_participante.perfil_id', '=', 'perfiles.id')
            ->select('perfiles.nombre as rol', DB::raw('COUNT(*) as total'));

        if ($eventoId) {
            $query->join('equipos', 'equipo_participante.equipo_id', '=', 'equipos.id')
                ->where('equipos.evento_id', $eventoId);
        }

        $resultados = $query
            ->groupBy('perfiles.nombre')
            ->orderByDesc('total')
            ->get();

        $total = $resultados->sum('total');

        return $resultados->map(function($item) use ($total) {
            return [
                'rol' => $item->rol,
                'total' => $item->total,
                'porcentaje' => $total > 0 ? round(($item->total / $total) * 100, 1) : 0
            ];
        })->toArray();
    }

    /* EXPORTACIONES DESHABILITADAS TEMPORALMENTE
     * Requieren librerías incompatibles con PHP 8.2 en Railway
     * 
    public function exportarPDF(Request $request)
    {
        $eventoId = $request->input('evento_id');
        
        $data = [
            'stats' => [
                'total_participantes' => $this->getTotalParticipantes($eventoId),
                'equipos_formados' => $this->getEquiposFormados($eventoId),
                'tasa_finalizacion' => $this->getTasaFinalizacion($eventoId),
                'puntuacion_promedio' => $this->getPuntuacionPromedio($eventoId),
            ],
            'participacion_carrera' => $this->getParticipacionPorCarrera($eventoId),
            'distribucion_roles' => $this->getDistribucionRoles($eventoId),
            'evento' => $eventoId ? Evento::find($eventoId) : null,
            'fecha' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.reportes.pdf', $data);
        $pdf->setPaper('letter', 'portrait');
        
        $filename = 'reporte-' . ($eventoId ? 'evento-' . $eventoId : 'general') . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportarExcel(Request $request)
    {
        $eventoId = $request->input('evento_id');
        
        $filename = 'reporte-' . ($eventoId ? 'evento-' . $eventoId : 'general') . '-' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new ReportesExport($eventoId), $filename);
    }
    */
}
