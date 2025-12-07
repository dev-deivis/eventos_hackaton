<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participante;
use App\Models\Equipo;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

            // Estadísticas básicas
            $stats = [
                'total_participantes' => Participante::count(),
                'equipos_formados' => Equipo::count(),
                'promedio_miembros' => 4.0,
                'tasa_finalizacion' => 81.8,
                'equipos_terminaron' => 18,
                'puntuacion_promedio' => 78.5,
                'puntuacion_maxima' => 92.3,
            ];

            // Participación por carrera (datos de ejemplo)
            $participacionCarrera = [
                ['carrera' => 'Ingeniería en Sistemas Computacionales', 'total' => 45, 'porcentaje' => 51.7],
                ['carrera' => 'Ingeniería en Gestión Empresarial', 'total' => 18, 'porcentaje' => 20.7],
                ['carrera' => 'Ingeniería Industrial', 'total' => 15, 'porcentaje' => 17.2],
                ['carrera' => 'Ingeniería Electrónica', 'total' => 9, 'porcentaje' => 10.3],
            ];

            // Estadísticas de equipos
            $estadisticasEquipos = [
                'completos' => 18,
                'incompletos' => 4,
                'tamano_promedio' => 4.0,
            ];

            // Distribución de roles (datos de ejemplo)
            $distribucionRoles = [
                ['rol' => 'Programador', 'total' => 38, 'porcentaje' => 43.7],
                ['rol' => 'Diseñador', 'total' => 22, 'porcentaje' => 25.3],
                ['rol' => 'Analista de Negocios', 'total' => 18, 'porcentaje' => 20.7],
                ['rol' => 'Analista De Datos', 'total' => 9, 'porcentaje' => 10.3],
            ];

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
}
