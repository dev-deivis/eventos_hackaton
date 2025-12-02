<?php

namespace App\Http\Controllers;

use App\Models\Constancia;
use App\Models\Evento;
use App\Models\Participante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ConstanciaController extends Controller
{
    /**
     * Mostrar el generador de constancias (admin)
     */
    public function index(Request $request)
    {
        // Query base
        $query = Constancia::with(['participante.user', 'evento']);

        // Filtro por búsqueda (nombre o código)
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('codigo_verificacion', 'like', "%{$buscar}%")
                  ->orWhereHas('participante.user', function($q) use ($buscar) {
                      $q->where('name', 'like', "%{$buscar}%");
                  })
                  ->orWhereHas('evento', function($q) use ($buscar) {
                      $q->where('nombre', 'like', "%{$buscar}%");
                  });
            });
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por evento
        if ($request->filled('evento_id')) {
            $query->where('evento_id', $request->evento_id);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', $request->fecha_hasta);
        }

        // Ordenar y paginar
        $constancias = $query->latest()->paginate(12)->withQueryString();

        // Obtener eventos para el filtro
        $eventos = \App\Models\Evento::orderBy('nombre')->get();

        return view('admin.constancias.index', compact('constancias', 'eventos'));
    }

    /**
     * Mostrar las plantillas disponibles
     */
    public function plantillas()
    {
        return view('admin.constancias.plantillas');
    }

    /**
     * Mostrar formulario para generar nuevas constancias
     */
    public function generarNuevas()
    {
        // Obtener TODOS los eventos
        $eventos = Evento::orderBy('created_at', 'desc')->get();

        return view('admin.constancias.generar-nuevas', compact('eventos'));
    }

    /**
     * API: Obtener participantes de un evento que no tienen constancia
     */
    public function obtenerParticipantes($eventoId, Request $request)
    {
        $tipo = $request->get('tipo'); // Opcional: filtrar por tipo de constancia

        $participantes = Participante::with('user', 'carrera')
            ->whereHas('equipos', function($query) use ($eventoId) {
                $query->where('evento_id', $eventoId)
                      ->where('equipo_participante.estado', 'activo'); // Solo miembros activos
            })
            ->when($tipo, function($query) use ($eventoId, $tipo) {
                // Excluir los que ya tienen constancia de este tipo
                $query->whereDoesntHave('constancias', function($q) use ($eventoId, $tipo) {
                    $q->where('evento_id', $eventoId)
                      ->where('tipo', $tipo);
                });
            })
            ->get();

        return response()->json($participantes);
    }

    /**
     * API: Obtener estadísticas de constancias de un evento
     */
    public function obtenerEstadisticas($eventoId)
    {
        // Total de participantes activos en el evento
        $totalParticipantes = Participante::whereHas('equipos', function($query) use ($eventoId) {
            $query->where('evento_id', $eventoId)
                  ->where('equipo_participante.estado', 'activo');
        })->distinct()->count();

        // Participantes únicos con al menos una constancia en este evento
        $conConstancia = Participante::whereHas('constancias', function($query) use ($eventoId) {
            $query->where('evento_id', $eventoId);
        })->whereHas('equipos', function($query) use ($eventoId) {
            $query->where('evento_id', $eventoId)
                  ->where('equipo_participante.estado', 'activo');
        })->distinct()->count();

        $sinConstancia = $totalParticipantes - $conConstancia;

        return response()->json([
            'total' => $totalParticipantes,
            'conConstancia' => $conConstancia,
            'sinConstancia' => max(0, $sinConstancia)
        ]);
    }

    /**
     * Generar constancia individual
     */
    public function generarIndividual(Request $request)
    {
        $validated = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'participante_id' => 'required|exists:participantes,id',
            'tipo' => 'required|in:participacion,primer_lugar,segundo_lugar,tercer_lugar,mencion_honorifica',
        ]);

        try {
            // Verificar que el participante esté en el evento
            $participante = Participante::findOrFail($validated['participante_id']);
            $enEvento = $participante->equipos()
                ->where('evento_id', $validated['evento_id'])
                ->where('equipo_participante.estado', 'activo')
                ->exists();

            if (!$enEvento) {
                return back()->with('error', 'Este participante no está registrado en el evento seleccionado.');
            }

            // Verificar si ya existe constancia
            $existe = Constancia::where('evento_id', $validated['evento_id'])
                ->where('participante_id', $validated['participante_id'])
                ->where('tipo', $validated['tipo'])
                ->exists();

            if ($existe) {
                return back()->with('error', 'Este participante ya tiene una constancia de este tipo para este evento.');
            }

            // Generar código único para verificación
            $codigo = Constancia::generarCodigoUnico();

            // Crear constancia
            $constancia = Constancia::create([
                'participante_id' => $validated['participante_id'],
                'evento_id' => $validated['evento_id'],
                'tipo' => $validated['tipo'],
                'codigo_verificacion' => $codigo,
                'fecha_emision' => now(),
            ]);

            return redirect()->route('admin.constancias.index')
                ->with('success', 'Constancia generada exitosamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar constancia: ' . $e->getMessage());
        }
    }

    /**
     * Generar constancias en lote
     */
    public function generarEnLote(Request $request)
    {
        $validated = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'tipo' => 'required|in:participacion,primer_lugar,segundo_lugar,tercer_lugar,mencion_honorifica',
            'equipo_id' => 'nullable|exists:equipos,id', // Nuevo: filtrar por equipo
        ]);

        $evento = Evento::findOrFail($validated['evento_id']);
        
        // Construir query base
        $query = Participante::whereHas('equipos', function($q) use ($evento, $validated) {
            $q->where('evento_id', $evento->id)
              ->where('equipo_participante.estado', 'activo');
            
            // Si se especificó un equipo, filtrar por él
            if (isset($validated['equipo_id'])) {
                $q->where('equipos.id', $validated['equipo_id']);
            }
        });

        // Excluir participantes que ya tienen esta constancia
        $query->whereDoesntHave('constancias', function($q) use ($evento, $validated) {
            $q->where('evento_id', $evento->id)
              ->where('tipo', $validated['tipo']);
        });

        $participantes = $query->get();
        $constanciasGeneradas = 0;

        foreach ($participantes as $participante) {
            try {
                Constancia::create([
                    'participante_id' => $participante->id,
                    'evento_id' => $evento->id,
                    'tipo' => $validated['tipo'],
                    'codigo_verificacion' => Constancia::generarCodigoUnico(),
                    'fecha_emision' => now(),
                ]);
                $constanciasGeneradas++;
            } catch (\Exception $e) {
                // Continuar con el siguiente si hay error
                continue;
            }
        }

        if ($constanciasGeneradas === 0) {
            return redirect()->route('admin.constancias.index')
                ->with('warning', 'No se generaron constancias. Todos los participantes ya tienen este tipo de constancia.');
        }

        return redirect()->route('admin.constancias.index')
            ->with('success', "Se generaron {$constanciasGeneradas} constancias exitosamente.");
    }

    /**
     * Crear constancia
     */
    private function crearConstancia($participante, $evento, $tipo, $notas = null)
    {
        $codigo = $this->generarCodigoUnico();

        $constancia = Constancia::create([
            'participante_id' => $participante->id,
            'evento_id' => $evento->id,
            'tipo' => $tipo,
            'codigo_qr' => $codigo,
            'fecha_emision' => now(),
        ]);

        return $constancia;
    }

    /**
     * Generar código único de verificación
     */
    private function generarCodigoUnico()
    {
        do {
            $codigo = 'HACK' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(3)) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while (Constancia::where('codigo_verificacion', $codigo)->exists());

        return $codigo;
    }

    /**
     * Vista previa de constancia
     */
    public function vistaPrevia($id)
    {
        // Cargar constancia con relaciones correctas
        $constancia = Constancia::with([
            'participante.user',
            'participante.carrera',
            'evento'
        ])->findOrFail($id);

        return view('admin.constancias.preview', compact('constancia'));
    }

    /**
     * Descargar constancia en PDF
     */
    public function descargar($id)
    {
        // Cargar constancia con relaciones correctas
        $constancia = Constancia::with([
            'participante.user',
            'participante.carrera',
            'evento'
        ])->findOrFail($id);

        // Obtener información adicional
        $user = $constancia->participante->user;
        $participante = $constancia->participante;
        $evento = $constancia->evento;

        // Obtener equipo y proyecto si existe
        $equipo = $constancia->participante->equipos()
            ->where('evento_id', $evento->id)
            ->with('proyecto')
            ->first();

        $proyecto = $equipo ? $equipo->proyecto : null;

        // Obtener el perfil/rol del participante en el equipo (si tiene)
        $perfilEquipo = null;
        if ($equipo) {
            $pivotData = \DB::table('equipo_participante')
                ->where('equipo_id', $equipo->id)
                ->where('participante_id', $participante->id)
                ->first();
            
            if ($pivotData && $pivotData->perfil_id) {
                $perfilEquipo = \App\Models\Perfil::find($pivotData->perfil_id);
            }
        }

        // Generar PDF según el tipo de constancia
        $esGanador = in_array($constancia->tipo, ['primer_lugar', 'segundo_lugar', 'tercer_lugar']);
        
        if ($esGanador) {
            $pdf = PDF::loadView('constancias.pdf.ganador', compact('constancia', 'user', 'participante', 'evento', 'equipo', 'proyecto', 'perfilEquipo'));
        } else {
            $pdf = PDF::loadView('constancias.pdf.participacion', compact('constancia', 'user', 'participante', 'evento', 'equipo', 'proyecto', 'perfilEquipo'));
        }

        // Configurar PDF
        $pdf->setPaper('letter', 'landscape');

        // Nombre del archivo
        $filename = 'constancia-' . $constancia->codigo_verificacion . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Eliminar constancia
     */
    public function destroy($id)
    {
        $constancia = Constancia::findOrFail($id);
        $constancia->delete();

        return redirect()->route('admin.constancias.index')
            ->with('success', 'Constancia eliminada exitosamente.');
    }

    /**
     * Verificar constancia por código
     */
    public function verificar(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $constancia = Constancia::where('codigo_verificacion', $request->codigo)
            ->with(['participante.user', 'evento'])
            ->first();

        if (!$constancia) {
            return redirect()->back()
                ->withErrors(['codigo' => 'Código de verificación no válido.']);
        }

        return view('constancias.verificar', compact('constancia'));
    }

    /**
     * API: Obtener equipos de un evento
     */
    public function obtenerEquipos($eventoId)
    {
        $equipos = \App\Models\Equipo::where('evento_id', $eventoId)
            ->withCount('participantes')
            ->orderBy('nombre')
            ->get()
            ->map(function($equipo) {
                return [
                    'id' => $equipo->id,
                    'nombre' => $equipo->nombre,
                    'participantes_count' => $equipo->participantes_count,
                ];
            });

        return response()->json($equipos);
    }

    /**
     * Generar constancias automáticas para ganadores según evaluaciones
     */
    public function generarGanadoresAutomatico(Request $request)
    {
        $validated = $request->validate([
            'evento_id' => 'required|exists:eventos,id',
        ]);

        $evento = \App\Models\Evento::findOrFail($validated['evento_id']);

        // Obtener los 3 equipos con mejor calificación promedio
        $equiposGanadores = \App\Models\Equipo::where('evento_id', $evento->id)
            ->whereHas('evaluaciones') // Solo equipos que han sido evaluados
            ->withAvg('evaluaciones', 'calificacion_total')
            ->orderByDesc('evaluaciones_avg_calificacion_total')
            ->take(3)
            ->get();

        if ($equiposGanadores->count() < 3) {
            return back()->with('warning', 'No hay suficientes equipos evaluados para declarar los 3 ganadores.');
        }

        $constanciasGeneradas = 0;
        $tipos = [
            Constancia::TIPO_PRIMER_LUGAR,
            Constancia::TIPO_SEGUNDO_LUGAR,
            Constancia::TIPO_TERCER_LUGAR,
        ];

        foreach ($equiposGanadores as $index => $equipo) {
            $tipo = $tipos[$index];
            
            // Obtener participantes activos del equipo
            $participantes = $equipo->participantes()
                ->wherePivot('estado', 'activo')
                ->get();

            foreach ($participantes as $participante) {
                // Verificar si ya tiene constancia de este tipo
                $existe = Constancia::where('participante_id', $participante->id)
                    ->where('evento_id', $evento->id)
                    ->where('tipo', $tipo)
                    ->exists();

                if (!$existe) {
                    Constancia::create([
                        'participante_id' => $participante->id,
                        'evento_id' => $evento->id,
                        'tipo' => $tipo,
                        'codigo_verificacion' => Constancia::generarCodigoUnico(),
                        'fecha_emision' => now(),
                    ]);
                    $constanciasGeneradas++;
                }
            }
        }

        return redirect()->route('admin.constancias.index')
            ->with('success', "Se generaron {$constanciasGeneradas} constancias de ganadores automáticamente.");
    }
}
