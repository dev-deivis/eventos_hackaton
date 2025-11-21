<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    /**
     * Mostrar lista de eventos
     */
    public function index()
    {
        $eventos = Evento::with(['equipos', 'registros'])
            ->activos()
            ->orderBy('fecha_inicio', 'asc')
            ->paginate(12);

        return view('eventos.index', compact('eventos'));
    }

    /**
     * Mostrar detalles de un evento
     */
    public function show(Evento $evento)
    {
        // Cargar relaciones necesarias
        $evento->load([
            'equipos.miembrosActivos',
            'equipos.lider',
            'registros'
        ]);

        // Verificar si el usuario actual está registrado
        $estaInscrito = false;
        $tieneEquipo = false;
        
        if (Auth::check()) {
            $user = Auth::user();
            $estaInscrito = $user->estaInscritoEn($evento);
            $tieneEquipo = $user->tieneEquipoEnEvento($evento);
        }

        return view('eventos.show', compact('evento', 'estaInscrito', 'tieneEquipo'));
    }

    /**
     * Registrar usuario a un evento
     */
    public function register(Request $request, Evento $evento)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para registrarte.');
        }

        $user = Auth::user();

        // Verificar que el evento esté abierto
        if (!$evento->estaAbierto()) {
            return redirect()->back()
                ->with('error', 'Este evento no está abierto para registro.');
        }

        // Verificar si ya está registrado
        if ($user->estaInscritoEn($evento)) {
            return redirect()->back()
                ->with('error', 'Ya estás registrado en este evento.');
        }

        // Verificar que haya cupo
        if (!$evento->puedeRegistrarse()) {
            return redirect()->back()
                ->with('error', 'Este evento ya alcanzó el máximo de participantes.');
        }

        // Inscribir usuario
        $user->inscribirseEn($evento);

        return redirect()->back()
            ->with('success', '¡Te has registrado exitosamente al evento!');
    }

    /**
     * Cancelar registro a un evento
     */
    public function cancelarRegistro(Evento $evento)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $registro = $user->inscripciones()
                         ->where('evento_id', $evento->id)
                         ->first();

        if (!$registro) {
            return redirect()->back()
                ->with('error', 'No estás registrado en este evento.');
        }

        $registro->update(['estado' => 'cancelado']);

        return redirect()->back()
            ->with('success', 'Has cancelado tu registro al evento.');
    }

    /**
     * Mostrar formulario de creación (para administradores)
     */
    public function create()
    {
        // Verificar que sea admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No tienes permisos para crear eventos.');
        }

        return view('eventos.create');
    }

    /**
     * Guardar nuevo evento
     */
    public function store(Request $request)
    {
        // Verificar que sea admin
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Validar datos
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:hackathon,datathon,concurso,workshop',
            'fecha_inicio' => 'required|date|after:now',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_registro' => 'required|date|before:fecha_inicio',
            'ubicacion' => 'required|string|max:255',
            'es_virtual' => 'boolean',
            'duracion_horas' => 'nullable|integer|min:1',
            'max_participantes' => 'nullable|integer|min:1',
            'min_miembros_equipo' => 'required|integer|min:1|max:10',
            'max_miembros_equipo' => 'required|integer|min:1|max:10',
            'imagen_portada' => 'nullable|image|max:2048',
        ]);

        // Subir imagen si existe
        if ($request->hasFile('imagen_portada')) {
            $validated['imagen_portada'] = $request->file('imagen_portada')
                ->store('eventos', 'public');
        }

        // Crear evento
        $validated['created_by'] = Auth::id();
        $validated['estado'] = 'draft';
        $validated['es_virtual'] = $request->has('es_virtual');

        $evento = Evento::create($validated);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Evento $evento)
    {
        // Verificar que sea admin
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('eventos.edit', compact('evento'));
    }

    /**
     * Actualizar evento
     */
    public function update(Request $request, Evento $evento)
    {
        // Verificar que sea admin
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:hackathon,datathon,concurso,workshop',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'fecha_limite_registro' => 'required|date|before:fecha_inicio',
            'ubicacion' => 'required|string|max:255',
            'es_virtual' => 'boolean',
            'estado' => 'required|in:draft,abierto,en_progreso,cerrado,completado',
            'imagen_portada' => 'nullable|image|max:2048',
        ]);

        // Actualizar imagen si hay una nueva
        if ($request->hasFile('imagen_portada')) {
            // Eliminar imagen anterior si existe
            if ($evento->imagen_portada) {
                Storage::disk('public')->delete($evento->imagen_portada);
            }
            $validated['imagen_portada'] = $request->file('imagen_portada')
                ->store('eventos', 'public');
        }

        $validated['es_virtual'] = $request->has('es_virtual');

        $evento->update($validated);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Eliminar evento
     */
    public function destroy(Evento $evento)
    {
        // Verificar que sea admin
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Eliminar imagen si existe
        if ($evento->imagen_portada) {
            Storage::disk('public')->delete($evento->imagen_portada);
        }

        $evento->delete();

        return redirect()->route('eventos.index')
            ->with('success', 'Evento eliminado exitosamente.');
    }

    /**
     * Dashboard del evento (para admins)
     */
    public function dashboard(Evento $evento)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $evento->load([
            'equipos.miembrosActivos',
            'registros.user.perfil',
            'proyectos'
        ]);

        $estadisticas = [
            'total_participantes' => $evento->totalParticipantes(),
            'total_equipos' => $evento->totalEquipos(),
            'equipos_completos' => $evento->equipos()->where('estado', 'completo')->count(),
            'participantes_sin_equipo' => $evento->registros()
                ->whereNull('equipo_id')
                ->count(),
        ];

        return view('eventos.dashboard', compact('evento', 'estadisticas'));
    }
}