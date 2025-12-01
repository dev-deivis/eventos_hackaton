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

        return view('admin.constancias.generar', compact('eventos'));
    }

    /**
     * Generar constancias en lote
     */
    public function generarEnLote(Request $request)
    {
        $request->validate([
            'evento_id' => 'required|exists:eventos,id',
            'tipo_constancia' => 'required|in:participacion,ganador',
        ]);

        $evento = Evento::findOrFail($request->evento_id);
        
        // Obtener participantes del evento
        $participantes = $evento->participantes;

        $constanciasGeneradas = 0;

        foreach ($participantes as $participante) {
            // Verificar si ya tiene constancia de este tipo para este evento
            $existe = Constancia::where('participante_id', $participante->id)
                ->where('evento_id', $evento->id)
                ->where('tipo_constancia', $request->tipo_constancia)
                ->exists();

            if (!$existe) {
                $this->crearConstancia($participante, $evento, $request->tipo_constancia);
                $constanciasGeneradas++;
            }
        }

        return redirect()->route('admin.constancias.index')
            ->with('success', "Se generaron {$constanciasGeneradas} constancias exitosamente.");
    }

    /**
     * Generar constancia individual
     */
    public function generarIndividual(Request $request)
    {
        $request->validate([
            'participante_nombre' => 'required|string',
            'participante_email' => 'required|email',
            'evento_id' => 'required|exists:eventos,id',
            'tipo_constancia' => 'required|in:participacion,ganador',
            'notas' => 'nullable|string',
        ]);

        $evento = Evento::findOrFail($request->evento_id);
        
        // Buscar participante por email
        $participante = Participante::whereHas('user', function($query) use ($request) {
            $query->where('email', $request->participante_email);
        })->first();

        if (!$participante) {
            return redirect()->back()
                ->withErrors(['participante_email' => 'No se encontró un participante con ese email.'])
                ->withInput();
        }

        // Verificar si ya tiene constancia
        $existe = Constancia::where('participante_id', $participante->id)
            ->where('evento_id', $evento->id)
            ->where('tipo_constancia', $request->tipo_constancia)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['participante_email' => 'Este participante ya tiene una constancia de este tipo para este evento.'])
                ->withInput();
        }

        $constancia = $this->crearConstancia($participante, $evento, $request->tipo_constancia, $request->notas);

        return redirect()->route('admin.constancias.index')
            ->with('success', 'Constancia generada exitosamente.');
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
            'tipo_constancia' => $tipo,
            'codigo_verificacion' => $codigo,
            'fecha_emision' => now(),
            'notas' => $notas,
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
