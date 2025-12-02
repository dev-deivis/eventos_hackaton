<?php

namespace App\Http\Controllers;

use App\Models\Constancia;
use App\Models\Evento;
use App\Models\Participante;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ConstanciaController extends Controller
{
    /**
     * Mostrar el generador de constancias (admin)
     */
    public function index()
    {
        // Obtener todas las constancias emitidas
        $constancias = Constancia::with(['participante.user', 'evento'])
            ->latest()
            ->paginate(12);

        return view('admin.constancias.index', compact('constancias'));
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
     * API: Obtener participantes de un evento (sin constancia del tipo especificado)
     */
    public function obtenerParticipantes($eventoId)
    {
        $participantes = Participante::with('user')
            ->whereHas('equipos', function($query) use ($eventoId) {
                $query->where('evento_id', $eventoId);
            })
            ->get();

        return response()->json($participantes);
    }

    /**
     * API: Obtener estadísticas de constancias de un evento
     */
    public function obtenerEstadisticas($eventoId)
    {
        $totalParticipantes = Participante::whereHas('equipos', function($query) use ($eventoId) {
            $query->where('evento_id', $eventoId);
        })->count();

        $conConstancia = Constancia::where('evento_id', $eventoId)->count();
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
            'tipo' => 'required|in:participacion,ganador',
            'posicion' => 'required_if:tipo,ganador|nullable|integer|between:1,3',
        ]);

        try {
            // Determinar el tipo final según posición
            $tipoFinal = $validated['tipo'];
            if ($validated['tipo'] === 'ganador') {
                $tipoFinal = match($validated['posicion']) {
                    1 => 'primer_lugar',
                    2 => 'segundo_lugar',
                    3 => 'tercer_lugar',
                    default => 'participacion'
                };
            }

            // Verificar si ya existe constancia
            $existe = Constancia::where('evento_id', $validated['evento_id'])
                ->where('participante_id', $validated['participante_id'])
                ->where('tipo', $tipoFinal)
                ->exists();

            if ($existe) {
                return back()->with('error', 'Este participante ya tiene una constancia de este tipo para este evento.');
            }

            // Generar código único para QR
            $codigo = 'CONST-' . strtoupper(Str::random(8));

            // Crear constancia
            $constancia = Constancia::create([
                'participante_id' => $validated['participante_id'],
                'evento_id' => $validated['evento_id'],
                'tipo' => $tipoFinal,
                'codigo_qr' => $codigo,
                'fecha_emision' => now(),
            ]);

            // Generar PDF (si existe el método)
            if (method_exists($this, 'generarPDF')) {
                $this->generarPDF($constancia);
            }

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
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'tipo' => 'required|in:participacion,ganador',
        ]);

        $evento = Evento::findOrFail($request->evento_id);
        
        // Obtener participantes del evento
        $participantes = Participante::whereHas('equipos', function($query) use ($evento) {
            $query->where('evento_id', $evento->id);
        })->get();

        $constanciasGeneradas = 0;

        foreach ($participantes as $participante) {
            // Verificar si ya tiene constancia de este tipo para este evento
            $existe = Constancia::where('participante_id', $participante->id)
                ->where('evento_id', $evento->id)
                ->where('tipo', $request->tipo)
                ->exists();

            if (!$existe) {
                $this->crearConstancia($participante, $evento, $request->tipo);
                $constanciasGeneradas++;
            }
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
        $constancia = Constancia::with(['participante.user.perfil', 'evento'])->findOrFail($id);

        return view('admin.constancias.preview', compact('constancia'));
    }

    /**
     * Descargar constancia en PDF
     */
    public function descargar($id)
    {
        $constancia = Constancia::with(['participante.user.perfil', 'evento'])->findOrFail($id);

        // Obtener información adicional
        $user = $constancia->participante->user;
        $perfil = $user->perfil;
        $evento = $constancia->evento;

        // Obtener equipo y proyecto si existe
        $equipo = $constancia->participante->equipos()
            ->where('evento_id', $evento->id)
            ->first();

        $proyecto = null;
        if ($equipo) {
            $proyecto = $equipo->proyecto;
        }

        // Generar PDF según el tipo de constancia
        if ($constancia->tipo_constancia === 'ganador') {
            $pdf = PDF::loadView('constancias.pdf.ganador', compact('constancia', 'user', 'perfil', 'evento', 'equipo', 'proyecto'));
        } else {
            $pdf = PDF::loadView('constancias.pdf.participacion', compact('constancia', 'user', 'perfil', 'evento', 'equipo', 'proyecto'));
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
}
