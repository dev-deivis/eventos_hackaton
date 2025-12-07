<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventPremio;
use App\Models\User;
use App\Models\Rol;
use App\Mail\NuevoEventoMail;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'nombre' => [
                'required',
                'string',
                'max:35',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/'
            ],
            'descripcion' => 'required|string|max:150',
            'tipo' => 'required|in:hackathon,datathon,concurso,workshop',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_registro' => 'required|date|before:fecha_inicio|different:fecha_fin',
            'fecha_evaluacion' => 'nullable|date|after_or_equal:fecha_fin',
            'fecha_premiacion' => 'nullable|date|after_or_equal:fecha_fin',
            'ubicacion' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s,\.]+$/'
            ],
            'es_virtual' => 'nullable|boolean',
            'duracion_horas' => 'required|integer|min:1',
            'max_participantes' => 'nullable|integer|min:10|max:1000',
            'min_miembros_equipo' => 'required|integer|in:5',
            'max_miembros_equipo' => 'required|integer|in:6',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'premios' => 'nullable|array',
            'premios.*.lugar' => 'nullable|string|max:100',
            'premios.*.descripcion' => [
                'nullable',
                'string',
                'max:40',
                'regex:/^[$a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\+\.]+$/'
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'string|max:100',
        ], [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.max' => 'El nombre del evento no puede tener más de 35 caracteres.',
            'nombre.regex' => 'El nombre del evento solo puede contener letras, números y guiones.',
            'descripcion.required' => 'La descripción del evento es obligatoria.',
            'descripcion.max' => 'La descripción no puede tener más de 150 caracteres.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'fecha_limite_registro.before' => 'La fecha límite de registro debe ser anterior a la fecha de inicio.',
            'fecha_limite_registro.different' => 'La fecha de registro no puede ser igual a la fecha de finalización.',
            'fecha_evaluacion.after_or_equal' => 'La fecha de evaluación debe ser posterior o igual a la fecha de finalización.',
            'fecha_premiacion.after_or_equal' => 'La fecha de premiación debe ser posterior o igual a la fecha de finalización.',
            'ubicacion.max' => 'La ubicación no puede tener más de 50 caracteres.',
            'ubicacion.regex' => 'La ubicación solo puede contener letras, números, comas y puntos.',
            'max_participantes.min' => 'El máximo de participantes debe ser al menos 10.',
            'max_participantes.max' => 'El máximo de participantes no puede exceder 1000.',
            'min_miembros_equipo.in' => 'El tamaño mínimo de equipo debe ser 5.',
            'max_miembros_equipo.in' => 'El tamaño máximo de equipo debe ser 6.',
            'premios.*.descripcion.max' => 'La descripción del premio no puede tener más de 40 caracteres.',
            'premios.*.descripcion.regex' => 'La descripción del premio solo puede contener $, letras, números, + y puntos.',
        ]);
        
        // Validación adicional: Verificar que la duración coincida con las fechas
        $fechaInicio = new \DateTime($request->fecha_inicio);
        $fechaFin = new \DateTime($request->fecha_fin);
        $diferenciaHoras = ($fechaFin->getTimestamp() - $fechaInicio->getTimestamp()) / 3600;
        
        if ($diferenciaHoras != $request->duracion_horas) {
            return back()->withErrors([
                'duracion_horas' => "La duración debe coincidir con la diferencia entre fecha de inicio y fin ({$diferenciaHoras} horas)."
            ])->withInput();
        }
        
        // Validación adicional: Verificar que el rol Asesor esté incluido
        if (!$request->has('roles') || !in_array('Asesor', $request->roles)) {
            return back()->withErrors([
                'roles' => 'El rol de Asesor es obligatorio.'
            ])->withInput();
        }

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

            // ⚠️ CORREOS DESHABILITADOS TEMPORALMENTE PARA DESARROLLO
            // TODO: Reactivar cuando se configure Brevo correctamente
            /*
            // Enviar correo a todos los participantes usando Resend API
            try {
                Log::info('Iniciando envío de correos para evento: ' . $evento->nombre);
                
                $rolParticipante = Rol::where('nombre', 'participante')->first();
                
                if ($rolParticipante) {
                    $participantes = User::whereHas('roles', function($query) use ($rolParticipante) {
                        $query->where('rol_id', $rolParticipante->id);
                    })->get();

                    Log::info("Total de participantes: {$participantes->count()}");

                    foreach ($participantes as $participante) {
                        try {
                            Mail::to($participante->email)->send(new NuevoEventoMail($evento));
                            Log::info("Correo enviado a: {$participante->email}");
                        } catch (\Exception $e) {
                            Log::error("Error enviando a {$participante->email}: " . $e->getMessage());
                        }
                    }
                }
            } catch (\Exception $mailException) {
                Log::error('Error al enviar correos:', [
                    'evento_id' => $evento->id,
                    'error' => $mailException->getMessage()
                ]);
            }
            */

            Log::info('📧 Envío de correos deshabilitado temporalmente');

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
            'nombre' => [
                'required',
                'string',
                'max:35',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/'
            ],
            'descripcion' => 'required|string|max:150',
            'tipo' => 'required|in:hackathon,datathon,concurso,workshop',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_registro' => 'required|date|before:fecha_inicio|different:fecha_fin',
            'fecha_evaluacion' => 'nullable|date|after_or_equal:fecha_fin',
            'fecha_premiacion' => 'nullable|date|after_or_equal:fecha_fin',
            'ubicacion' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s,\.]+$/'
            ],
            'es_virtual' => 'nullable|boolean',
            'duracion_horas' => 'required|integer|min:1',
            'max_participantes' => 'nullable|integer|min:10|max:1000',
            'min_miembros_equipo' => 'required|integer|in:5',
            'max_miembros_equipo' => 'required|integer|in:6',
            'imagen_portada' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'premios' => 'nullable|array',
            'premios.*.lugar' => 'nullable|string|max:100',
            'premios.*.descripcion' => [
                'nullable',
                'string',
                'max:40',
                'regex:/^[$a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\+\.]+$/'
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'string|max:100',
        ], [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.max' => 'El nombre del evento no puede tener más de 35 caracteres.',
            'nombre.regex' => 'El nombre del evento solo puede contener letras, números y guiones.',
            'descripcion.required' => 'La descripción del evento es obligatoria.',
            'descripcion.max' => 'La descripción no puede tener más de 150 caracteres.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'fecha_limite_registro.before' => 'La fecha límite de registro debe ser anterior a la fecha de inicio.',
            'fecha_limite_registro.different' => 'La fecha de registro no puede ser igual a la fecha de finalización.',
            'fecha_evaluacion.after_or_equal' => 'La fecha de evaluación debe ser posterior o igual a la fecha de finalización.',
            'fecha_premiacion.after_or_equal' => 'La fecha de premiación debe ser posterior o igual a la fecha de finalización.',
            'ubicacion.max' => 'La ubicación no puede tener más de 50 caracteres.',
            'ubicacion.regex' => 'La ubicación solo puede contener letras, números, comas y puntos.',
            'max_participantes.min' => 'El máximo de participantes debe ser al menos 10.',
            'max_participantes.max' => 'El máximo de participantes no puede exceder 1000.',
            'min_miembros_equipo.in' => 'El tamaño mínimo de equipo debe ser 5.',
            'max_miembros_equipo.in' => 'El tamaño máximo de equipo debe ser 6.',
            'premios.*.descripcion.max' => 'La descripción del premio no puede tener más de 40 caracteres.',
            'premios.*.descripcion.regex' => 'La descripción del premio solo puede contener $, letras, números, + y puntos.',
        ]);
        
        // Validación adicional: Verificar que la duración coincida con las fechas
        $fechaInicio = new \DateTime($request->fecha_inicio);
        $fechaFin = new \DateTime($request->fecha_fin);
        $diferenciaHoras = ($fechaFin->getTimestamp() - $fechaInicio->getTimestamp()) / 3600;
        
        if ($diferenciaHoras != $request->duracion_horas) {
            return back()->withErrors([
                'duracion_horas' => "La duración debe coincidir con la diferencia entre fecha de inicio y fin ({$diferenciaHoras} horas)."
            ])->withInput();
        }
        
        // Validación adicional: Verificar que el rol Asesor esté incluido
        if (!$request->has('roles') || !in_array('Asesor', $request->roles)) {
            return back()->withErrors([
                'roles' => 'El rol de Asesor es obligatorio.'
            ])->withInput();
        }

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
