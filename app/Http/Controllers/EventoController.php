<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventPremio;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
    /**
     * Mostrar lista de eventos
     */
    public function index()
    {
        $eventos = Evento::with(['equipos'])
                        ->activos()
                        ->latest()
                        ->paginate(12);

        return view('eventos.index', compact('eventos'));
    }

    /**
     * Mostrar detalle de un evento
     */
    public function show(Evento $evento)
    {
        $evento->load(['equipos.participantes', 'premios']);
        
        $estaInscrito = false;
        $tieneEquipo = false;

        if (auth()->check() && auth()->user()->participante) {
            // Verificar si el usuario (como participante) tiene un equipo en este evento
            $tieneEquipo = auth()->user()->participante->equipos()
                                        ->where('evento_id', $evento->id)
                                        ->exists();
            
            $estaInscrito = $tieneEquipo; // En la nueva estructura, estar inscrito = tener equipo
        }

        return view('eventos.show', compact('evento', 'estaInscrito', 'tieneEquipo'));
    }

    /**
     * Formulario de crear evento (solo admin)
     */
    public function create()
    {
        return view('eventos.create');
    }

    /**
     * Guardar nuevo evento (solo admin)
     */
    public function store(Request $request)
    {
        // Log para debug
        Log::info('Datos recibidos para crear evento:', $request->all());

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:hackathon,datathon,concurso,workshop',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_registro' => 'required|date',
            'fecha_evaluacion' => 'nullable|date',
            'fecha_premiacion' => 'nullable|date',
            'ubicacion' => 'required|string|max:255',
            'es_virtual' => 'nullable|boolean',
            'duracion_horas' => 'required|integer|min:1',
            'max_participantes' => 'nullable|integer|min:10',
            'min_miembros_equipo' => 'required|integer|min:1|max:10',
            'max_miembros_equipo' => 'required|integer|min:1|max:10|gte:min_miembros_equipo',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'premios' => 'nullable|array',
            'premios.*.lugar' => 'nullable|string|max:100',
            'premios.*.descripcion' => 'nullable|string|max:500',
            'roles' => 'nullable|array',
            'roles.*' => 'string|max:100',
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'max_miembros_equipo.gte' => 'El tamaño máximo del equipo debe ser mayor o igual al mínimo.',
        ]);

        DB::beginTransaction();
        
        try {
            // Manejar imagen de portada
            if ($request->hasFile('imagen_portada')) {
                $validated['imagen_portada'] = $request->file('imagen_portada')->store('eventos', 'public');
            }

            // Preparar datos del evento
            $validated['created_by'] = auth()->id();
            $validated['estado'] = 'abierto'; // Crear directamente como abierto
            $validated['es_virtual'] = $request->input('es_virtual', 0);
            $validated['roles_requeridos'] = $request->input('roles', []);
            
            // Remover imagen_portada si no se subió archivo
            if (!isset($validated['imagen_portada'])) {
                unset($validated['imagen_portada']);
            }
            
            // Crear evento
            $evento = Evento::create($validated);

            // Guardar premios (solo si hay datos)
            if ($request->has('premios') && is_array($request->premios)) {
                $orden = 1;
                foreach ($request->premios as $premioData) {
                    // Solo guardar si tiene lugar Y descripción
                    if (
                        isset($premioData['lugar']) && 
                        isset($premioData['descripcion']) && 
                        !empty(trim($premioData['lugar'])) && 
                        !empty(trim($premioData['descripcion']))
                    ) {
                        EventPremio::create([
                            'evento_id' => $evento->id,
                            'lugar' => trim($premioData['lugar']),
                            'descripcion' => trim($premioData['descripcion']),
                            'orden' => $orden,
                        ]);
                        $orden++;
                    }
                }
            }

            DB::commit();

            // Notificar a todos los participantes sobre el nuevo evento
            NotificationService::nuevoEvento($evento);

            return redirect()
                ->route('eventos.show', $evento)
                ->with('success', '¡Evento creado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log del error para debug
            Log::error('Error al crear evento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Error al crear el evento: ' . $e->getMessage());
        }
    }

    /**
     * Registrarse a un evento (Ya no se usa - ahora se hace a través de equipos)
     */
    public function register(Request $request, Evento $evento)
    {
        return redirect()->route('eventos.show', $evento)
            ->with('info', 'Para participar en este evento, crea o únete a un equipo.');
    }

    /**
     * Cancelar registro de un evento (Ya no se usa)
     */
    public function cancelarRegistro(Evento $evento)
    {
        return redirect()->route('eventos.show', $evento)
            ->with('info', 'Para dejar de participar, sal de tu equipo.');
    }

    /**
     * Cambiar estado del evento (solo admin)
     */
    public function cambiarEstado(Request $request, Evento $evento)
    {
        $request->validate([
            'estado' => 'required|in:draft,abierto,en_progreso,cerrado,completado'
        ]);

        $evento->update([
            'estado' => $request->estado
        ]);

        $mensajes = [
            'draft' => 'Evento marcado como borrador',
            'abierto' => '¡Evento abierto! Los estudiantes ya pueden registrarse',
            'en_progreso' => 'Evento marcado como en progreso',
            'cerrado' => 'Evento cerrado',
            'completado' => 'Evento completado'
        ];

        return back()->with('success', $mensajes[$request->estado]);
    }

    /**
     * Formulario de editar evento (solo admin)
     */
    public function edit(Evento $evento)
    {
        $evento->load('premios');
        return view('eventos.edit', compact('evento'));
    }

    /**
     * Actualizar evento (solo admin)
     */
    public function update(Request $request, Evento $evento)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:hackathon,datathon,concurso,workshop',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_registro' => 'required|date',
            'fecha_evaluacion' => 'nullable|date',
            'fecha_premiacion' => 'nullable|date',
            'ubicacion' => 'required|string|max:255',
            'es_virtual' => 'nullable|boolean',
            'duracion_horas' => 'required|integer|min:1',
            'max_participantes' => 'nullable|integer|min:10',
            'min_miembros_equipo' => 'required|integer|min:1|max:10',
            'max_miembros_equipo' => 'required|integer|min:1|max:10',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'premios' => 'nullable|array',
            'premios.*.lugar' => 'nullable|string|max:100',
            'premios.*.descripcion' => 'nullable|string|max:500',
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'max_miembros_equipo.gte' => 'El tamaño máximo del equipo debe ser mayor o igual al mínimo.',
        ]);

        DB::beginTransaction();
        
        try {
            // Manejar imagen de portada
            if ($request->hasFile('imagen_portada')) {
                // Eliminar imagen anterior si existe
                if ($evento->imagen_portada) {
                    Storage::disk('public')->delete($evento->imagen_portada);
                }
                $validated['imagen_portada'] = $request->file('imagen_portada')->store('eventos', 'public');
            }

            // Preparar datos del evento
            $validated['es_virtual'] = $request->input('es_virtual', 0);
            $validated['roles_requeridos'] = $request->input('roles', []);
            
            // Actualizar evento
            $evento->update($validated);

            // Actualizar premios
            if ($request->has('premios')) {
                // Eliminar premios antiguos
                $evento->premios()->delete();
                
                // Crear nuevos premios
                $orden = 1;
                foreach ($request->premios as $premioData) {
                    // Solo guardar si tiene lugar Y descripción
                    if (
                        isset($premioData['lugar']) && 
                        isset($premioData['descripcion']) && 
                        !empty(trim($premioData['lugar'])) && 
                        !empty(trim($premioData['descripcion']))
                    ) {
                        EventPremio::create([
                            'evento_id' => $evento->id,
                            'lugar' => trim($premioData['lugar']),
                            'descripcion' => trim($premioData['descripcion']),
                            'orden' => $orden,
                        ]);
                        $orden++;
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('eventos.show', $evento)
                ->with('success', 'Evento actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al actualizar evento:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el evento: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar evento (solo admin)
     */
    public function destroy(Evento $evento)
    {
        // Eliminar imagen si existe
        if ($evento->imagen_portada) {
            Storage::disk('public')->delete($evento->imagen_portada);
        }

        $evento->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Evento eliminado exitosamente.');
    }

    /**
     * Dashboard del evento (estadísticas) (solo admin)
     */
    public function dashboard(Evento $evento)
    {
        $stats = [
            'total_participantes' => $evento->totalParticipantes(),
            'total_equipos' => $evento->equipos()->count(),
            'total_proyectos' => $evento->proyectos()->count(),
            'equipos_completos' => $evento->equipos()
                ->whereRaw('(SELECT COUNT(*) FROM equipo_participante WHERE equipo_id = equipos.id AND estado = "activo") >= ?', 
                    [$evento->min_miembros_equipo])
                ->count(),
        ];

        return view('eventos.dashboard', compact('evento', 'stats'));
    }
}
