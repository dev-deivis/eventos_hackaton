<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Evento;
use App\Models\Participante;
use App\Models\Perfil;
use App\Models\MensajeEquipo;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipoController extends Controller
{
    /**
     * Mostrar equipos de un evento
     */
    public function index(Evento $evento)
    {
        $equipos = $evento->equipos()
            ->with(['lider.user', 'participantes.user', 'proyecto'])
            ->withCount('miembrosActivos')
            ->paginate(12);

        return view('equipos.index', compact('evento', 'equipos'));
    }

    /**
     * Seleccionar evento para crear equipo
     */
    public function seleccionarEvento()
    {
        // Obtener eventos abiertos donde el usuario NO tenga equipo
        $participante = auth()->user()->participante;
        
        if (!$participante) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Completa tu perfil para crear un equipo.');
        }

        // Eventos abiertos
        $eventosAbiertos = Evento::where('estado', 'abierto')
            ->where('fecha_inicio', '>=', now())
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        // Filtrar eventos donde NO tenga equipo
        $eventosDisponibles = $eventosAbiertos->filter(function($evento) use ($participante) {
            return !$participante->equipos()
                ->where('evento_id', $evento->id)
                ->exists();
        });

        return view('equipos.seleccionar-evento', compact('eventosDisponibles'));
    }

    /**
     * Mostrar detalle de un equipo
     */
    public function show(Equipo $equipo)
    {
        // Cargar relaciones necesarias
        $equipo->load([
            'evento', 
            'lider.user',
            'lider.carrera',
            'proyecto'
        ]);

        // Cargar participantes activos con perfil
        $equipo->load(['participantes' => function($query) {
            $query->with(['user', 'carrera'])
                  ->withPivot('perfil_id', 'estado');
        }]);

        // Cargar los perfiles en el pivot
        foreach($equipo->participantes as $participante) {
            if ($participante->pivot->perfil_id) {
                $participante->pivot->load('perfil');
            }
        }

        // Verificar si el usuario actual es miembro
        $esMiembro = false;
        $esLider = false;
        $miParticipante = null;

        if (auth()->check() && auth()->user()->participante) {
            $miParticipante = auth()->user()->participante;
            $esMiembro = $equipo->participantes->contains('id', $miParticipante->id);
            $esLider = $equipo->lider_id == $miParticipante->id;
        }

        // Variables para control de evaluación
        $fueEvaluado = $equipo->fueEvaluado();
        $calificacion = $equipo->calificacionPromedio();
        $puedeEditar = $equipo->puedeSerEditado();

        return view('equipos.show', compact('equipo', 'esMiembro', 'esLider', 'fueEvaluado', 'calificacion', 'puedeEditar'));
    }

    /**
     * Formulario para crear equipo
     */
    public function create(Evento $evento)
    {
        Log::info('Accediendo a crear equipo', [
            'evento_id' => $evento->id,
            'evento_estado' => $evento->estado,
            'user_id' => auth()->id()
        ]);

        // Verificar que el evento esté abierto
        if (!$evento->estaAbierto()) {
            Log::warning('Intento de crear equipo en evento cerrado', [
                'evento_id' => $evento->id,
                'estado' => $evento->estado
            ]);
            return redirect()->route('equipos.index', $evento)
                ->with('error', 'Las inscripciones para este evento están cerradas. Estado actual: ' . $evento->estado);
        }

        // Verificar que el usuario tenga perfil de participante
        if (!auth()->user()->participante) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Completa tu perfil para crear un equipo.');
        }

        // Verificar que no tenga ya un equipo en este evento
        $participante = auth()->user()->participante;
        if ($participante->equipos()->where('evento_id', $evento->id)->exists()) {
            return redirect()->route('equipos.index', $evento)
                ->with('error', 'Ya perteneces a un equipo en este evento.');
        }

        $perfiles = Perfil::all();
        
        Log::info('Mostrando formulario de crear equipo', [
            'perfiles_count' => $perfiles->count()
        ]);
        
        return view('equipos.create', compact('evento', 'perfiles'));
    }

    /**
     * Guardar nuevo equipo
     */
    public function store(Request $request, Evento $evento)
    {
        try {
            // Log para debug
            Log::info('Intentando crear equipo', [
                'evento_id' => $evento->id,
                'user_id' => auth()->id(),
                'tiene_participante' => auth()->user()->participante ? 'SI' : 'NO',
                'request' => $request->all()
            ]);

            // Validación
            $validated = $request->validate([
                'nombre' => 'required|string|max:100|unique:equipos,nombre,NULL,id,evento_id,' . $evento->id,
                'descripcion' => 'nullable|string|max:500',
                'perfil_id' => 'required|exists:perfiles,id',
            ], [
                'nombre.unique' => 'Ya existe un equipo con este nombre en el evento.',
                'perfil_id.required' => 'Debes seleccionar tu rol en el equipo.',
                'perfil_id.exists' => 'El rol seleccionado no es válido.',
            ]);

            // Verificar que el usuario tenga perfil de participante
            $participante = auth()->user()->participante;
            if (!$participante) {
                Log::warning('Usuario sin participante intentó crear equipo', ['user_id' => auth()->id()]);
                return redirect()->route('profile.complete')
                    ->with('warning', 'Completa tu perfil para crear un equipo.');
            }

            // Verificar que no tenga ya un equipo en este evento
            if ($participante->equipos()->where('evento_id', $evento->id)->exists()) {
                Log::warning('Usuario ya tiene equipo en este evento', [
                    'user_id' => auth()->id(),
                    'evento_id' => $evento->id
                ]);
                return back()->with('error', 'Ya perteneces a un equipo en este evento.');
            }

            DB::beginTransaction();
            
            // Crear equipo
            $equipo = Equipo::create([
                'evento_id' => $evento->id,
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'lider_id' => $participante->id,
                'max_miembros' => $evento->max_miembros_equipo,
                'estado' => 'activo',
            ]);

            Log::info('Equipo creado', ['equipo_id' => $equipo->id]);

            // Agregar al creador como miembro con su perfil
            $equipo->participantes()->attach($participante->id, [
                'perfil_id' => $validated['perfil_id'],
                'estado' => 'activo',
            ]);

            Log::info('Miembro agregado al equipo', [
                'equipo_id' => $equipo->id,
                'participante_id' => $participante->id,
                'perfil_id' => $validated['perfil_id']
            ]);

            DB::commit();

            // Notificar a administradores sobre nuevo equipo
            \App\Services\NotificationService::nuevoEquipoRegistrado($equipo);

            return redirect()->route('equipos.show', $equipo)
                ->with('success', '¡Equipo creado exitosamente! Ahora puedes invitar a más miembros.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al crear equipo:', [
                'errors' => $e->errors(),
                'user_id' => auth()->id()
            ]);
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear equipo:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);
            
            return back()->withInput()
                ->with('error', 'Error al crear el equipo: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar información del equipo (solo líder)
     */
    public function update(Request $request, Equipo $equipo)
    {
        // Verificar que el usuario sea el líder del equipo
        $participante = auth()->user()->participante;
        if (!$participante || $equipo->lider_id !== $participante->id) {
            abort(403, 'Solo el líder del equipo puede editar su información.');
        }

        // Verificar si el equipo puede ser editado
        if (!$equipo->puedeSerEditado()) {
            return back()->with('error', 'Este equipo ya no puede ser editado porque fue evaluado o el evento finalizó.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:equipos,nombre,' . $equipo->id . ',id,evento_id,' . $equipo->evento_id,
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre.unique' => 'Ya existe un equipo con este nombre en el evento.',
            'nombre.required' => 'El nombre del equipo es obligatorio.',
        ]);

        try {
            $equipo->update($validated);

            Log::info('Equipo actualizado', [
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('equipos.show', $equipo)
                ->with('success', 'Información del equipo actualizada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar equipo:', [
                'error' => $e->getMessage(),
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return back()->withInput()
                ->with('error', 'Error al actualizar el equipo: ' . $e->getMessage());
        }
    }

    /**
     * Solicitar unirse a un equipo
     */
    public function solicitarUnirse(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'perfil_id' => 'required|exists:perfiles,id',
        ]);

        $participante = auth()->user()->participante;

        // Verificar que el usuario tenga perfil de participante
        if (!$participante) {
            return redirect()->route('profile.complete')
                ->with('warning', 'Completa tu perfil para unirte a un equipo.');
        }

        // Verificar que el equipo pertenezca a un evento abierto
        if (!$equipo->evento->estaAbierto()) {
            return back()->with('error', 'Las inscripciones para este evento están cerradas.');
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return back()->with('error', 'No puedes unirte a este equipo porque ya fue evaluado.');
        }

        // Verificar que no esté ya en este equipo
        if ($equipo->participantes->contains('id', $participante->id)) {
            return back()->with('error', 'Ya eres miembro de este equipo.');
        }

        // Verificar que no tenga ya otro equipo en este evento
        if ($participante->equipos()->where('evento_id', $equipo->evento_id)->exists()) {
            return back()->with('error', 'Ya perteneces a otro equipo en este evento.');
        }

        // Verificar que el equipo no esté lleno
        if (!$equipo->puedeAceptarMiembros()) {
            return back()->with('error', 'El equipo está lleno.');
        }

        try {
            // Agregar como pendiente
            $equipo->participantes()->attach($participante->id, [
                'perfil_id' => $validated['perfil_id'],
                'estado' => 'pendiente',
            ]);

            // Notificar al líder del equipo
            NotificationService::solicitudEquipo(
                $equipo->lider->user_id,
                $participante,
                $equipo
            );

            return back()->with('success', 'Solicitud enviada. El líder del equipo la revisará.');

        } catch (\Exception $e) {
            Log::error('Error al solicitar unirse a equipo:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al enviar solicitud. Intenta de nuevo.');
        }
    }

    /**
     * Aceptar miembro (solo líder)
     */
    public function aceptarMiembro(Equipo $equipo, $participanteId)
    {
        // Verificar que el usuario actual sea el líder
        if ($equipo->lider_id != auth()->user()->participante?->id) {
            abort(403, 'Solo el líder puede aceptar miembros.');
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return back()->with('error', 'No puedes aceptar nuevos miembros porque el equipo ya fue evaluado.');
        }

        // Verificar que el equipo pueda aceptar más miembros
        if (!$equipo->puedeAceptarMiembros()) {
            return back()->with('error', 'El equipo está lleno.');
        }

        try {
            // Actualizar estado a activo
            $equipo->participantes()->updateExistingPivot($participanteId, [
                'estado' => 'activo',
            ]);

            // Obtener el participante
            $participante = Participante::findOrFail($participanteId);

            // Notificar al participante aceptado
            NotificationService::solicitudAceptada($participante->user_id, $equipo);

            // Notificar a todos los miembros del equipo sobre el nuevo integrante
            NotificationService::nuevoMiembro($equipo, $participante, auth()->id());

            return back()->with('success', 'Miembro aceptado en el equipo.');

        } catch (\Exception $e) {
            Log::error('Error al aceptar miembro:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al aceptar miembro.');
        }
    }

    /**
     * Rechazar miembro (solo líder)
     */
    public function rechazarMiembro(Equipo $equipo, $participanteId)
    {
        // Verificar que el usuario actual sea el líder
        if ($equipo->lider_id != auth()->user()->participante?->id) {
            abort(403, 'Solo el líder puede rechazar solicitudes.');
        }

        try {
            // Obtener el participante antes de eliminarlo
            $participante = Participante::findOrFail($participanteId);
            
            // Eliminar de la tabla pivote
            $equipo->participantes()->detach($participanteId);

            // Notificar al participante rechazado
            NotificationService::solicitudRechazada($participante->user_id, $equipo);

            return back()->with('success', 'Solicitud rechazada.');

        } catch (\Exception $e) {
            Log::error('Error al rechazar miembro:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al rechazar solicitud.');
        }
    }

    /**
     * Abandonar equipo
     */
    public function abandonar(Equipo $equipo)
{
    $participante = auth()->user()->participante;

    // Verificar que sea miembro del equipo
    if (!$equipo->participantes->contains('id', $participante->id)) {
        return back()->with('error', 'No eres miembro de este equipo.');
    }

    // Verificar que no sea el líder
    if ($equipo->lider_id == $participante->id) {
        return back()->with('error', 'El líder no puede abandonar el equipo. Transfiere el liderazgo o elimina el equipo.');
    }

    try {
        // Limpiar tareas asignadas antes de abandonar
        if ($equipo->proyecto) {
            // Obtener todas las tareas del proyecto
            $tareas = $equipo->proyecto->tareas;
            
            // Remover al participante de todas las tareas donde está asignado
            foreach ($tareas as $tarea) {
                $tarea->participantes()->detach($participante->id);
            }
            
            Log::info('Tareas limpiadas al abandonar equipo', [
                'participante_id' => $participante->id,
                'equipo_id' => $equipo->id,
                'tareas_limpiadas' => $tareas->count()
            ]);
        }
        
        // Remover del equipo
        $equipo->participantes()->detach($participante->id);

        // Notificar a los miembros restantes
        NotificationService::miembroAbandono($equipo, $participante);

        return redirect()->route('eventos.show', $equipo->evento)
            ->with('success', 'Has abandonado el equipo. Tus asignaciones de tareas han sido removidas.');

    } catch (\Exception $e) {
        Log::error('Error al abandonar equipo:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Error al abandonar el equipo.');
    }
}

    /**
     * Eliminar equipo (solo líder)
     */
    public function destroy(Equipo $equipo)
    {
        // Verificar que el usuario actual sea el líder
        if ($equipo->lider_id != auth()->user()->participante?->id) {
            abort(403, 'Solo el líder puede eliminar el equipo.');
        }

        try {
            $eventoId = $equipo->evento_id;
            $equipo->delete();

            return redirect()->route('equipos.index', $eventoId)
                ->with('success', 'Equipo eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar equipo:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al eliminar el equipo.');
        }
    }

    /**
     * Enviar mensaje al chat del equipo
     */
    public function enviarMensaje(Request $request, Equipo $equipo)
    {
        // Verificar que el usuario sea miembro del equipo
        $participante = auth()->user()->participante;
        if (!$participante || !$equipo->participantes->contains('id', $participante->id)) {
            abort(403, 'No eres miembro de este equipo.');
        }

        $validated = $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        MensajeEquipo::create([
            'equipo_id' => $equipo->id,
            'participante_id' => $participante->id,
            'mensaje' => $validated['mensaje'],
        ]);

        // Notificar a todos los miembros del equipo excepto el remitente
        NotificationService::mensajeEquipo($equipo, auth()->user());

        return back()->with('success', 'Mensaje enviado.');
    }

    /**
     * Enviar mensaje vía API (AJAX - Tiempo Real)
     */
    public function enviarMensajeApi(Request $request, Equipo $equipo)
    {
        // Verificar que el usuario sea miembro activo del equipo
        $participante = auth()->user()->participante;
        
        if (!$participante) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un perfil de participante'
            ], 403);
        }

        $esMiembro = $equipo->participantes()
            ->where('participantes.id', $participante->id)
            ->wherePivot('estado', 'activo')
            ->exists();

        if (!$esMiembro) {
            return response()->json([
                'success' => false,
                'message' => 'No eres miembro activo de este equipo'
            ], 403);
        }

        $validated = $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);

        // Crear mensaje
        $mensaje = MensajeEquipo::create([
            'equipo_id' => $equipo->id,
            'participante_id' => $participante->id,
            'mensaje' => $validated['mensaje'],
        ]);

        // Notificar a otros miembros
        NotificationService::mensajeEquipo($equipo, auth()->user());

        // Devolver mensaje con usuario
        $mensaje->load('participante.user');

        return response()->json([
            'success' => true,
            'mensaje' => [
                'id' => $mensaje->id,
                'mensaje' => $mensaje->mensaje,
                'user_name' => $mensaje->participante->user->name,
                'user_initial' => substr($mensaje->participante->user->name, 0, 1),
                'created_at' => $mensaje->created_at->diffForHumans(),
                'is_own' => $mensaje->participante->user_id === auth()->id(),
            ]
        ]);
    }

    /**
     * Solicitar unirse a un equipo vía API (AJAX - Tiempo Real)
     */
    public function solicitarApi(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'perfil_id' => 'required|exists:perfiles,id',
        ]);

        $participante = auth()->user()->participante;

        // Verificar que el usuario tenga perfil de participante
        if (!$participante) {
            return response()->json([
                'success' => false,
                'message' => 'Completa tu perfil para unirte a un equipo.'
            ], 400);
        }

        // Verificar que el equipo pertenezca a un evento abierto
        if (!$equipo->evento->estaAbierto()) {
            return response()->json([
                'success' => false,
                'message' => 'Las inscripciones para este evento están cerradas.'
            ], 400);
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes unirte a este equipo porque ya fue evaluado.'
            ], 400);
        }

        // Verificar que no esté ya en este equipo
        if ($equipo->participantes->contains('id', $participante->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ya eres miembro de este equipo.'
            ], 400);
        }

        // Verificar que no tenga ya otro equipo en este evento
        if ($participante->equipos()->where('evento_id', $equipo->evento_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ya perteneces a otro equipo en este evento.'
            ], 400);
        }

        // Verificar que el equipo no esté lleno
        if (!$equipo->puedeAceptarMiembros()) {
            return response()->json([
                'success' => false,
                'message' => 'El equipo está lleno.'
            ], 400);
        }

        try {
            // Agregar como pendiente
            $equipo->participantes()->attach($participante->id, [
                'perfil_id' => $validated['perfil_id'],
                'estado' => 'pendiente',
            ]);

            // Notificar al líder del equipo
            NotificationService::solicitudEquipo(
                $equipo->lider->user_id,
                $participante,
                $equipo
            );

            // Obtener perfil
            $perfil = \App\Models\Perfil::find($validated['perfil_id']);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud enviada. El líder del equipo la revisará.',
                'solicitud' => [
                    'id' => $participante->id,
                    'user_name' => auth()->user()->name,
                    'user_initial' => substr(auth()->user()->name, 0, 1),
                    'perfil_nombre' => $perfil->nombre,
                    'equipo_id' => $equipo->id,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al solicitar unirse a equipo:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar solicitud. Intenta de nuevo.'
            ], 500);
        }
    }

    /**
     * Obtener solicitudes pendientes vía API (polling para tiempo real)
     */
    public function obtenerSolicitudesPendientesApi(Equipo $equipo)
    {
        // Verificar que el usuario sea el líder
        $participante = auth()->user()->participante;
        
        if (!$participante || $equipo->lider_id !== $participante->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para ver las solicitudes.'
            ], 403);
        }

        // Obtener solicitudes pendientes
        $pendientes = $equipo->participantes()
            ->wherePivot('estado', 'pendiente')
            ->with('user')
            ->get();

        $solicitudes = $pendientes->map(function($solicitante) use ($equipo) {
            return [
                'id' => $solicitante->id,
                'user_name' => $solicitante->user->name,
                'user_initial' => substr($solicitante->user->name, 0, 1),
                'perfil_nombre' => $solicitante->pivot->perfil->nombre ?? 'N/A',
                'equipo_id' => $equipo->id,
            ];
        });

        return response()->json([
            'success' => true,
            'solicitudes' => $solicitudes,
            'count' => $solicitudes->count()
        ]);
    }

    /**
     * Mostrar equipos del usuario autenticado
     */
    public function misEquipos()
    {
        $participante = auth()->user()->participante;
        
        if (!$participante) {
            return redirect()->route('profile.complete')
                ->with('error', 'Debes completar tu perfil primero.');
        }

        // Obtener equipos donde el usuario es miembro activo
        $misEquipos = $participante->equiposActivos()
            ->with([
                'evento',
                'lider.user',
                'proyecto.tareas',
                'participantes'
            ])
            ->get();

        return view('equipos.mis-equipos', compact('misEquipos'));
    }
}