<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $usuarios = User::with(['roles', 'participante.carrera'])
            ->latest()
            ->paginate(15);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para crear usuario
     */
    public function create()
    {
        $roles = Rol::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rol_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar rol (solo uno)
        $user->roles()->attach($validated['rol_id']);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function edit(User $usuario)
    {
        $usuario->load('roles', 'participante', 'equiposAsignados');
        $roles = Rol::all();

        // Obtener todos los equipos disponibles para asignar
        $equiposDisponibles = \App\Models\Equipo::with(['evento', 'participantes'])
            ->whereHas('evento', function ($query) {
                $query->where('estado', '!=', 'completado');
            })
            ->get();

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'equiposDisponibles'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'rol_id' => ['required', 'exists:roles,id'],
            'equipos' => ['nullable', 'array'],
            'equipos.*' => ['exists:equipos,id'],
        ]);

        $usuario->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Actualizar rol (reemplazar el actual)
        $usuario->roles()->sync([$validated['rol_id']]);

        // Si el rol es juez, asignar equipos
        $rolJuez = \App\Models\Rol::where('nombre', 'juez')->first();
        if ($validated['rol_id'] == $rolJuez->id) {
            // Obtener equipos actuales antes de sincronizar
            $equiposAnteriores = $usuario->equiposAsignados()->pluck('equipos.id')->toArray();
            
            // Sincronizar equipos asignados
            $usuario->equiposAsignados()->sync($validated['equipos'] ?? []);
            
            // Notificar sobre nuevos equipos asignados
            $equiposNuevos = array_diff($validated['equipos'] ?? [], $equiposAnteriores);
            foreach ($equiposNuevos as $equipoId) {
                $equipo = \App\Models\Equipo::find($equipoId);
                if ($equipo) {
                    \App\Services\NotificationService::equipoAsignadoAJuez($usuario, $equipo);
                }
            }
        } else {
            // Si no es juez, quitar todas las asignaciones
            $usuario->equiposAsignados()->detach();
        }

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Cambiar contraseña de un usuario
     */
    public function updatePassword(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usuario->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $usuario)
    {
        // No permitir eliminar el propio usuario
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Eliminar relaciones
        $usuario->roles()->detach();

        // Si tiene participante, eliminarlo también
        if ($usuario->participante) {
            $usuario->participante->delete();
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus(User $usuario)
    {
        // Esta funcionalidad requiere agregar campo 'activo' a la tabla users
        // Por ahora, solo retornamos
        return back()->with('info', 'Funcionalidad en desarrollo.');
    }
}
