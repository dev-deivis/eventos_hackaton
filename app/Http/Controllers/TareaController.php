<?php

namespace App\Http\Controllers;

use App\Models\TareaProyecto;
use App\Models\Equipo;
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

        // Verificar que el equipo tenga proyecto
        if (!$equipo->proyecto) {
            return back()->with('error', 'El equipo no tiene un proyecto registrado.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:participantes,id',
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

        // Verificar que la tarea pertenece al proyecto del equipo
        if ($tarea->proyecto_id !== $equipo->proyecto->id) {
            return back()->with('error', 'Esta tarea no pertenece a tu equipo.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'nullable|string|max:1000',
            'participantes' => 'nullable|array',
            'participantes.*' => 'exists:participantes,id',
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
        }

        return back()->with('success', $mensaje);
    }
}
