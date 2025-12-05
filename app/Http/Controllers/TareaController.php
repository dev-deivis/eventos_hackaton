<?php

namespace App\Http\Controllers;

use App\Models\TareaProyecto;
use App\Models\Equipo;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TareaController extends Controller
{
    /**
     * Crear una nueva tarea (solo líder)
     */
    public function store(Request $request, Equipo $equipo)
    {
        // Verificar que el usuario sea líder del equipo
        $participante = auth()->user()->participante;

        if (!$participante || $equipo->lider_id !== $participante->id) {
            return back()->with('error', 'Solo el líder puede crear tareas.');
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return back()->with('error', 'No puedes crear tareas porque el proyecto ya fue evaluado.');
        }

        // Verificar que el equipo tenga proyecto
        if (!$equipo->proyecto) {
            return back()->with('error', 'El equipo no tiene un proyecto registrado.');
        }

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:40',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,;:¿?¡!()\-]+$/'
            ],
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:participantes,id',
        ], [
            'nombre.required' => 'El nombre de la tarea es obligatorio.',
            'nombre.max' => 'El nombre de la tarea no puede tener más de 40 caracteres.',
            'nombre.regex' => 'El nombre de la tarea solo puede contener letras y números.',
            'descripcion.max' => 'La descripción no puede tener más de 50 caracteres.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números y signos de puntuación básicos.',
        ]);

        // Obtener el último orden
        $ultimoOrden = $equipo->proyecto->tareas()->max('orden') ?? 0;

        // Crear la tarea
        $tarea = TareaProyecto::create([
            'proyecto_id' => $equipo->proyecto->id,
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'estado' => 'pendiente',
            'orden' => $ultimoOrden + 1,
        ]);

        // Asignar participantes si se proporcionaron
        if (!empty($validated['participantes'])) {
            // Verificar que los participantes sean miembros del equipo
            $miembrosIds = $equipo->participantes->pluck('id')->toArray();
            $participantesValidos = array_intersect($validated['participantes'], $miembrosIds);

            $tarea->participantes()->attach($participantesValidos);
            
            // Notificar a los participantes asignados
            $userIds = $equipo->participantes()
                ->whereIn('participantes.id', $participantesValidos)
                ->pluck('user_id')
                ->toArray();
            
            NotificationService::tareaAsignada($tarea, $userIds);
        }

        Log::info('Tarea creada', [
            'tarea_id' => $tarea->id,
            'equipo_id' => $equipo->id,
            'creador_id' => $participante->id
        ]);

        return back()->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Actualizar una tarea (solo líder)
     */
    public function update(Request $request, Equipo $equipo, TareaProyecto $tarea)
    {
        // Verificar que el usuario sea líder del equipo
        $participante = auth()->user()->participante;

        if (!$participante || $equipo->lider_id !== $participante->id) {
            return back()->with('error', 'Solo el líder puede editar tareas.');
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return back()->with('error', 'No puedes editar tareas porque el proyecto ya fue evaluado.');
        }

        // Verificar que la tarea pertenece al proyecto del equipo
        if ($tarea->proyecto_id !== $equipo->proyecto->id) {
            return back()->with('error', 'Esta tarea no pertenece a tu equipo.');
        }

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:40',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,;:¿?¡!()\-]+$/'
            ],
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:participantes,id',
        ], [
            'nombre.required' => 'El nombre de la tarea es obligatorio.',
            'nombre.max' => 'El nombre de la tarea no puede tener más de 40 caracteres.',
            'nombre.regex' => 'El nombre de la tarea solo puede contener letras y números.',
            'descripcion.max' => 'La descripción no puede tener más de 50 caracteres.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números y signos de puntuación básicos.',
        ]);

        // Actualizar la tarea
        $tarea->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        // Actualizar participantes asignados
        if (isset($validated['participantes'])) {
            // Verificar que los participantes sean miembros del equipo
            $miembrosIds = $equipo->participantes->pluck('id')->toArray();
            $participantesValidos = array_intersect($validated['participantes'], $miembrosIds);

            $tarea->participantes()->sync($participantesValidos);
        } else {
            // Si no se enviaron participantes, limpiar asignaciones
            $tarea->participantes()->detach();
        }

        return back()->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Eliminar una tarea (solo líder)
     */
    public function destroy(Equipo $equipo, TareaProyecto $tarea)
    {
        // Verificar que el usuario sea líder del equipo
        $participante = auth()->user()->participante;

        if (!$participante || $equipo->lider_id !== $participante->id) {
            return back()->with('error', 'Solo el líder puede eliminar tareas.');
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return back()->with('error', 'No puedes eliminar tareas porque el proyecto ya fue evaluado.');
        }

        // Verificar que la tarea pertenece al proyecto del equipo
        if ($tarea->proyecto_id !== $equipo->proyecto->id) {
            return back()->with('error', 'Esta tarea no pertenece a tu equipo.');
        }

        $tarea->delete();

        return back()->with('success', 'Tarea eliminada exitosamente.');
    }

    /**
     * Marcar tarea como completada/pendiente (cualquier miembro asignado)
     */
    public function toggleEstado(Equipo $equipo, TareaProyecto $tarea)
    {
        $participante = auth()->user()->participante;

        // Verificar que el usuario sea miembro ACTIVO del equipo
        $miembroActivo = $equipo->participantes()
            ->where('participantes.id', $participante->id)
            ->wherePivot('estado', 'activo')
            ->exists();

        if (!$participante || !$miembroActivo) {
            return back()->with('error', 'No eres miembro activo de este equipo. Debes ser aceptado por el líder primero.');
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return back()->with('error', 'No puedes cambiar el estado de tareas porque el proyecto ya fue evaluado.');
        }

        // Verificar que la tarea pertenece al proyecto del equipo
        if ($tarea->proyecto_id !== $equipo->proyecto->id) {
            return back()->with('error', 'Esta tarea no pertenece a este equipo.');
        }

        // Verificar que el participante está asignado a la tarea O es el líder
        $esLider = $equipo->lider_id === $participante->id;
        $estaAsignado = $tarea->participantes->contains('id', $participante->id);

        if (!$esLider && !$estaAsignado) {
            return back()->with('error', 'No estás asignado a esta tarea. Solo los participantes asignados y el líder pueden marcarla.');
        }

        // Toggle del estado
        if ($tarea->estado === 'completada') {
            $tarea->update(['estado' => 'pendiente']);
            $mensaje = 'Tarea marcada como pendiente.';
        } else {
            $tarea->update(['estado' => 'completada']);
            $mensaje = '¡Excelente! Tarea marcada como completada.';
            
            // Notificar a los demás miembros que la tarea fue completada
            NotificationService::tareaCompletada($tarea, auth()->user());
        }

        return back()->with('success', $mensaje);
    }

    /**
     * Crear tarea vía API (AJAX - Tiempo Real)
     */
    public function storeApi(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'participantes' => 'nullable|array|max:2',
            'participantes.*' => 'exists:participantes,id',
        ]);

        if (!$equipo->proyecto) {
            return response()->json([
                'success' => false,
                'message' => 'El equipo no tiene proyecto registrado'
            ], 400);
        }

        $tarea = TareaProyecto::create([
            'proyecto_id' => $equipo->proyecto->id,
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'estado' => 'pendiente',
        ]);

        if (!empty($validated['participantes'])) {
            $tarea->participantes()->attach($validated['participantes']);
        }

        $tarea->load('participantes.user');

        return response()->json([
            'success' => true,
            'tarea' => [
                'id' => $tarea->id,
                'nombre' => $tarea->nombre,
                'descripcion' => $tarea->descripcion,
                'estado' => $tarea->estado,
                'completada' => $tarea->estaCompletada(),
                'porcentaje' => $tarea->valorPorcentual(),
                'equipo_id' => $equipo->id,
                'participantes' => $tarea->participantes->map(function($p) {
                    return [
                        'id' => $p->id,
                        'nombre' => explode(' ', $p->user->name)[0],
                    ];
                }),
            ]
        ]);
    }

    /**
     * Actualizar tarea vía API (AJAX - Tiempo Real)
     */
    public function updateApi(Request $request, Equipo $equipo, TareaProyecto $tarea)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'participantes' => 'nullable|array|max:2',
            'participantes.*' => 'exists:participantes,id',
        ]);

        $tarea->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
        ]);

        if (isset($validated['participantes'])) {
            $tarea->participantes()->sync($validated['participantes']);
        } else {
            $tarea->participantes()->detach();
        }

        $tarea->load('participantes.user');

        return response()->json([
            'success' => true,
            'tarea' => [
                'id' => $tarea->id,
                'nombre' => $tarea->nombre,
                'descripcion' => $tarea->descripcion,
                'participantes' => $tarea->participantes->map(function($p) {
                    return [
                        'id' => $p->id,
                        'nombre' => explode(' ', $p->user->name)[0],
                    ];
                }),
            ]
        ]);
    }

    /**
     * Toggle estado de tarea vía API (AJAX - Tiempo Real)
     */
    public function toggleApi(Request $request, Equipo $equipo, TareaProyecto $tarea)
    {
        $participante = auth()->user()->participante;

        // Verificar que el usuario sea miembro ACTIVO del equipo
        $miembroActivo = $equipo->participantes()
            ->where('participantes.id', $participante->id)
            ->wherePivot('estado', 'activo')
            ->exists();

        if (!$participante || !$miembroActivo) {
            return response()->json([
                'success' => false,
                'message' => 'No eres miembro activo de este equipo.'
            ], 403);
        }

        // Verificar si el equipo fue evaluado
        if ($equipo->fueEvaluado()) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes cambiar el estado de tareas porque el proyecto ya fue evaluado.'
            ], 403);
        }

        // Verificar que la tarea pertenece al proyecto del equipo
        if ($tarea->proyecto_id !== $equipo->proyecto->id) {
            return response()->json([
                'success' => false,
                'message' => 'Esta tarea no pertenece a este equipo.'
            ], 403);
        }

        // Verificar que el participante está asignado a la tarea O es el líder
        $esLider = $equipo->lider_id === $participante->id;
        $estaAsignado = $tarea->participantes->contains('id', $participante->id);

        if (!$esLider && !$estaAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'No estás asignado a esta tarea. Solo los participantes asignados y el líder pueden marcarla.'
            ], 403);
        }

        // Toggle del estado
        $nuevoEstado = $tarea->estaCompletada() ? 'pendiente' : 'completada';
        $tarea->update(['estado' => $nuevoEstado]);

        if ($nuevoEstado === 'completada') {
            NotificationService::tareaCompletada($tarea, auth()->user());
        }

        return response()->json([
            'success' => true,
            'tarea' => [
                'id' => $tarea->id,
                'completada' => $tarea->estaCompletada(),
                'estado' => $nuevoEstado,
            ]
        ]);
    }
}
