<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProyectoController extends Controller
{
    /**
     * Mostrar formulario para crear/editar proyecto
     */
    public function create(Equipo $equipo)
    {
        // Verificar que el usuario sea miembro del equipo
        $participante = auth()->user()->participante;
        if (!$participante || !$equipo->participantes->contains('id', $participante->id)) {
            abort(403, 'No eres miembro de este equipo.');
        }

        // Si ya tiene proyecto, redirigir a editar
        if ($equipo->proyecto) {
            return redirect()->route('proyectos.edit', $equipo);
        }

        return view('proyectos.create', compact('equipo'));
    }

    /**
     * Guardar nuevo proyecto
     */
    public function store(Request $request, Equipo $equipo)
    {
        // Verificar que el usuario sea miembro del equipo
        $participante = auth()->user()->participante;
        if (!$participante || !$equipo->participantes->contains('id', $participante->id)) {
            abort(403, 'No eres miembro de este equipo.');
        }

        // Verificar que no tenga proyecto ya
        if ($equipo->proyecto) {
            return redirect()->route('equipos.show', $equipo)
                ->with('error', 'Este equipo ya tiene un proyecto registrado.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string|max:1000',
            'link_repositorio' => 'nullable|url|max:500',
            'link_demo' => 'nullable|url|max:500',
            'link_presentacion' => 'nullable|url|max:500',
            'tecnologias' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'descripcion.required' => 'La descripción del proyecto es obligatoria.',
            'link_repositorio.url' => 'El link del repositorio debe ser una URL válida.',
            'link_demo.url' => 'El link de la demo debe ser una URL válida.',
            'link_presentacion.url' => 'El link de la presentación debe ser una URL válida.',
        ]);

        try {
            $proyecto = Proyecto::create([
                'equipo_id' => $equipo->id,
                'evento_id' => $equipo->evento_id,
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'link_repositorio' => $validated['link_repositorio'] ?? null,
                'link_demo' => $validated['link_demo'] ?? null,
                'link_presentacion' => $validated['link_presentacion'] ?? null,
                'tecnologias' => $validated['tecnologias'] ?? null,
                'estado' => 'en_progreso', // Estado inicial
                'porcentaje_completado' => 0,
            ]);

            // Actualizar porcentaje inicial
            $proyecto->actualizarPorcentaje();

            Log::info('Proyecto creado', [
                'proyecto_id' => $proyecto->id,
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('equipos.show', $equipo)
                ->with('success', '¡Proyecto registrado exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al crear proyecto:', [
                'error' => $e->getMessage(),
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return back()->withInput()
                ->with('error', 'Error al registrar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar proyecto
     */
    public function edit(Equipo $equipo)
    {
        // Verificar que el usuario sea miembro del equipo
        $participante = auth()->user()->participante;
        if (!$participante || !$equipo->participantes->contains('id', $participante->id)) {
            abort(403, 'No eres miembro de este equipo.');
        }

        // Verificar que tenga proyecto
        if (!$equipo->proyecto) {
            return redirect()->route('proyectos.create', $equipo)
                ->with('warning', 'Primero debes registrar un proyecto.');
        }

        $proyecto = $equipo->proyecto;
        return view('proyectos.edit', compact('equipo', 'proyecto'));
    }

    /**
     * Actualizar proyecto
     */
    public function update(Request $request, Equipo $equipo)
    {
        // Verificar que el usuario sea miembro del equipo
        $participante = auth()->user()->participante;
        if (!$participante || !$equipo->participantes->contains('id', $participante->id)) {
            abort(403, 'No eres miembro de este equipo.');
        }

        // Verificar que tenga proyecto
        if (!$equipo->proyecto) {
            return redirect()->route('proyectos.create', $equipo)
                ->with('warning', 'Primero debes registrar un proyecto.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:200',
            'descripcion' => 'required|string|max:1000',
            'link_repositorio' => 'nullable|url|max:500',
            'link_demo' => 'nullable|url|max:500',
            'link_presentacion' => 'nullable|url|max:500',
            'tecnologias' => 'nullable|string|max:500',
        ]);

        try {
            $equipo->proyecto->update($validated);

            // Actualizar porcentaje
            $equipo->proyecto->actualizarPorcentaje();

            Log::info('Proyecto actualizado', [
                'proyecto_id' => $equipo->proyecto->id,
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('equipos.show', $equipo)
                ->with('success', 'Proyecto actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar proyecto:', [
                'error' => $e->getMessage(),
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return back()->withInput()
                ->with('error', 'Error al actualizar el proyecto: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar proyecto
     */
    public function destroy(Equipo $equipo)
    {
        // Solo el líder puede eliminar el proyecto
        if ($equipo->lider_id != auth()->user()->participante?->id) {
            abort(403, 'Solo el líder puede eliminar el proyecto.');
        }

        try {
            $equipo->proyecto->delete();

            return redirect()->route('equipos.show', $equipo)
                ->with('success', 'Proyecto eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar proyecto:', [
                'error' => $e->getMessage(),
                'equipo_id' => $equipo->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Error al eliminar el proyecto.');
        }
    }

    /**
     * Realizar entrega final del proyecto
     */
    public function entregar(Proyecto $proyecto)
    {
        // Verificar que el usuario sea miembro del equipo
        $participante = auth()->user()->participante;
        if (!$participante || !$proyecto->equipo->participantes->contains('id', $participante->id)) {
            abort(403, 'No eres miembro de este equipo.');
        }

        // Verificar que el proyecto cumple con los requisitos mínimos
        if (!$proyecto->cumpleRequisitosMinimos()) {
            return redirect()->back()
                ->with('error', 'El proyecto no cumple con todos los requisitos mínimos para ser entregado.');
        }

        // Verificar que no esté ya entregado
        if (in_array($proyecto->estado, ['entregado', 'listo_para_evaluar', 'evaluado', 'finalizado'])) {
            return redirect()->back()
                ->with('info', 'Este proyecto ya fue entregado anteriormente.');
        }

        try {
            // Realizar entrega
            if ($proyecto->entregarProyecto()) {
                Log::info('Proyecto entregado', [
                    'proyecto_id' => $proyecto->id,
                    'equipo_id' => $proyecto->equipo_id,
                    'user_id' => auth()->id()
                ]);

                return redirect()->route('equipos.show', $proyecto->equipo)
                    ->with('success', '¡Proyecto entregado exitosamente! Ahora esperará la aprobación del administrador para ser evaluado.');
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudo realizar la entrega. Verifica que todos los requisitos estén completos.');
            }

        } catch (\Exception $e) {
            Log::error('Error al entregar proyecto:', [
                'error' => $e->getMessage(),
                'proyecto_id' => $proyecto->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Error al entregar el proyecto: ' . $e->getMessage());
        }
    }
}
