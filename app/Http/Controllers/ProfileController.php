<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use App\Models\Carrera;
use App\Models\Habilidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Mostrar perfil público del usuario
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $user->load(['participante.carrera', 'participante.habilidades', 'roles']);

        $stats = null;
        $juezStats = null;
        $adminStats = null;

        // ============================================
        // ESTADÍSTICAS PARA PARTICIPANTE
        // ============================================
        if ($user->isParticipante() && $user->participante) {
            try {
                $participante = $user->participante;
                
                // Obtener todos los equipos del participante
                $equipos = $participante->equipos()->with(['evento', 'proyecto'])->get();
                
                // Contar eventos únicos
                $eventosParticipados = $equipos->unique('evento_id')->count();
                
                // Contar veces como líder
                $vecesLider = $equipos->filter(function($equipo) use ($participante) {
                    return $equipo->lider_id === $participante->id;
                })->count();
                
                // Obtener proyectos con premios (1er, 2do, 3er lugar)
                $proyectosConPremio = $equipos->filter(function($equipo) {
                    if (!$equipo->proyecto) return false;
                    return in_array($equipo->proyecto->lugar_obtenido, [1, 2, 3]);
                });
                
                $premiosObtenidos = [
                    'primero' => $proyectosConPremio->where('proyecto.lugar_obtenido', 1)->count(),
                    'segundo' => $proyectosConPremio->where('proyecto.lugar_obtenido', 2)->count(),
                    'tercero' => $proyectosConPremio->where('proyecto.lugar_obtenido', 3)->count(),
                ];
                
                // Total de proyectos presentados
                $proyectosPresentados = $equipos->filter(function($equipo) {
                    return $equipo->proyecto !== null;
                })->count();
                
                // Constancias obtenidas
                $constancias = $participante->constancias()->count();
                
                $stats = [
                    'eventos_participados' => $eventosParticipados,
                    'total_equipos' => $equipos->count(),
                    'veces_lider' => $vecesLider,
                    'proyectos_presentados' => $proyectosPresentados,
                    'premios' => $premiosObtenidos,
                    'total_premios' => array_sum($premiosObtenidos),
                    'constancias' => $constancias,
                    'equipos_recientes' => $equipos->sortByDesc('created_at')->take(5),
                ];
            } catch (\Exception $e) {
                // Si hay error, usar valores por defecto
                $stats = [
                    'eventos_participados' => 0,
                    'total_equipos' => 0,
                    'veces_lider' => 0,
                    'proyectos_presentados' => 0,
                    'premios' => ['primero' => 0, 'segundo' => 0, 'tercero' => 0],
                    'total_premios' => 0,
                    'constancias' => 0,
                    'equipos_recientes' => collect(),
                ];
            }
        }

        // ============================================
        // ESTADÍSTICAS PARA JUEZ
        // ============================================
        if ($user->isJuez()) {
            try {
                // Obtener todas las evaluaciones del juez
                $evaluaciones = \App\Models\Evaluacion::where('juez_id', $user->id)
                    ->with(['equipo.evento', 'equipo.proyecto'])
                    ->get();
                
                // Eventos únicos en los que ha sido juez
                $eventosComoJuez = $evaluaciones->pluck('equipo.evento')
                    ->filter()
                    ->unique('id')
                    ->count();
                
                // Total de equipos evaluados (únicos)
                $equiposEvaluados = $evaluaciones->unique('equipo_id')->count();
                
                // Promedio de calificaciones otorgadas
                $promedioCalificaciones = $evaluaciones->avg('calificacion_total') ?? 0;
                
                // Evaluaciones por evento
                $evaluacionesPorEvento = $evaluaciones->groupBy(function($eval) {
                    return $eval->equipo->evento->nombre ?? 'Sin evento';
                })->map(function($evals) {
                    return $evals->count();
                });
                
                $juezStats = [
                    'eventos_como_juez' => $eventosComoJuez,
                    'equipos_evaluados' => $equiposEvaluados,
                    'total_evaluaciones' => $evaluaciones->count(),
                    'promedio_calificaciones' => round($promedioCalificaciones, 2),
                    'evaluaciones_por_evento' => $evaluacionesPorEvento,
                ];
            } catch (\Exception $e) {
                // Si hay error, usar valores por defecto
                $juezStats = [
                    'eventos_como_juez' => 0,
                    'equipos_evaluados' => 0,
                    'total_evaluaciones' => 0,
                    'promedio_calificaciones' => 0,
                    'evaluaciones_por_evento' => collect(),
                ];
            }
        }

        // ============================================
        // ESTADÍSTICAS PARA ADMINISTRADOR
        // ============================================
        if ($user->isAdmin()) {
            try {
                // Total de eventos creados por este admin
                $eventosCreados = \App\Models\Evento::where('created_by', $user->id)->count();
                
                // Total de usuarios en el sistema
                $totalUsuarios = \App\Models\User::count();
                
                // Total de eventos activos
                $eventosActivos = \App\Models\Evento::where('estado', 'abierto')->count();
                
                // Total de equipos en el sistema
                $totalEquipos = \App\Models\Equipo::count();
                
                // Total de proyectos presentados
                $totalProyectos = \App\Models\Proyecto::count();
                
                $adminStats = [
                    'eventos_creados' => $eventosCreados,
                    'total_usuarios' => $totalUsuarios,
                    'eventos_activos' => $eventosActivos,
                    'total_equipos' => $totalEquipos,
                    'total_proyectos' => $totalProyectos,
                ];
            } catch (\Exception $e) {
                // Si hay error, usar valores por defecto
                $adminStats = [
                    'eventos_creados' => 0,
                    'total_usuarios' => 0,
                    'eventos_activos' => 0,
                    'total_equipos' => 0,
                    'total_proyectos' => 0,
                ];
            }
        }

        return view('profile.show', compact('user', 'stats', 'juezStats', 'adminStats'));
    }

    /**
     * Formulario para editar perfil
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('participante.carrera');
        $carreras = Carrera::all();

        return view('profile.edit', compact('user', 'carreras'));
    }

    /**
     * Actualizar información del perfil
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validar datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'carrera_id' => 'nullable|exists:carreras,id',
            'no_control' => 'nullable|string|max:20|unique:participantes,no_control,' . ($user->participante?->id ?? 'NULL'),
            'semestre' => 'nullable|integer|min:1|max:12',
            'telefono' => 'nullable|string|max:15',
            'biografia' => 'nullable|string|max:300',
        ], [
            'biografia.max' => 'La biografía no puede tener más de 300 caracteres.',
        ]);

        // Actualizar usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Actualizar o crear participante si es necesario
        if ($user->isParticipante()) {
            if ($user->participante) {
                $user->participante->update([
                    'carrera_id' => $validated['carrera_id'] ?? $user->participante->carrera_id,
                    'no_control' => $validated['no_control'] ?? $user->participante->no_control,
                    'semestre' => $validated['semestre'] ?? $user->participante->semestre,
                    'telefono' => $validated['telefono'] ?? $user->participante->telefono,
                    'biografia' => $validated['biografia'] ?? $user->participante->biografia,
                ]);
            }
        }

        return redirect()->route('profile.show')
            ->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Actualizar contraseña
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // Al menos una minúscula
                'regex:/[A-Z]/',      // Al menos una mayúscula
                'regex:/[0-9]/',      // Al menos un número
                'regex:/[@$!%*#?&]/', // Al menos un carácter especial
            ],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial (!@#$%^&*).',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    /**
     * Eliminar cuenta
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada.');
    }

    /**
     * Mostrar formulario para completar perfil de participante
     */
    public function complete(): View
    {
        $carreras = Carrera::all();
        return view('profile.complete', compact('carreras'));
    }

    /**
     * Guardar perfil completo de participante
     */
    public function storeComplete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'carrera_id' => 'required|exists:carreras,id',
            'no_control' => 'required|string|max:20|unique:participantes,no_control',
            'semestre' => 'required|integer|min:1|max:12',
            'telefono' => 'nullable|string|max:15',
            'biografia' => 'nullable|string|max:500',
        ]);

        // Crear perfil de participante
        Participante::create([
            'user_id' => auth()->id(),
            'carrera_id' => $validated['carrera_id'],
            'no_control' => $validated['no_control'],
            'semestre' => $validated['semestre'],
            'telefono' => $validated['telefono'] ?? null,
            'biografia' => $validated['biografia'] ?? 'Estudiante apasionado por la tecnología.',
        ]);

        return redirect()->route('dashboard')
            ->with('success', '¡Perfil completado exitosamente! Ahora puedes participar en eventos.');
    }

    /**
     * Agregar nueva habilidad
     */
    public function storeHabilidad(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'nivel' => 'required|integer|min:0|max:100',
            'color' => 'required|string|max:50',
        ]);

        $participante = auth()->user()->participante;

        if (!$participante) {
            return back()->with('error', 'Debes completar tu perfil primero.');
        }

        // Obtener el último orden
        $ultimoOrden = $participante->habilidades()->max('orden') ?? 0;

        Habilidad::create([
            'participante_id' => $participante->id,
            'nombre' => $validated['nombre'],
            'nivel' => $validated['nivel'],
            'color' => $validated['color'],
            'orden' => $ultimoOrden + 1,
        ]);

        return back()->with('success', 'Habilidad agregada exitosamente.');
    }

    /**
     * Actualizar habilidad existente
     */
    public function updateHabilidad(Request $request, Habilidad $habilidad): RedirectResponse
    {
        // Verificar que la habilidad pertenece al usuario
        if ($habilidad->participante_id !== auth()->user()->participante->id) {
            abort(403, 'No puedes editar esta habilidad.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'nivel' => 'required|integer|min:0|max:100',
            'color' => 'required|string|max:50',
        ]);

        $habilidad->update($validated);

        return back()->with('success', 'Habilidad actualizada exitosamente.');
    }

    /**
     * Eliminar habilidad
     */
    public function destroyHabilidad(Habilidad $habilidad): RedirectResponse
    {
        // Verificar que la habilidad pertenece al usuario
        if ($habilidad->participante_id !== auth()->user()->participante->id) {
            abort(403, 'No puedes eliminar esta habilidad.');
        }

        $habilidad->delete();

        return back()->with('success', 'Habilidad eliminada exitosamente.');
    }
}
