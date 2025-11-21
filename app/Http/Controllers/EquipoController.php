<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Equipo;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipoController extends Controller
{
    /**
     * Constructor - requiere autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar equipos de un evento
     */
    public function index(Evento $evento)
    {
        $equipos = $evento->equipos()
            ->with(['lider', 'miembrosActivos'])
            ->activos()
            ->paginate(12);

        return view('equipos.index', compact('evento', 'equipos'));
    }

    /**
     * Mostrar detalles de un equipo
     */
    public function show(Equipo $equipo)
    {
        $equipo->load([
            'evento',
            'lider.perfil',
            'miembrosActivos.perfil',
            'solicitudesPendientes.perfil',
            'proyecto'
        ]);

        $user = Auth::user();
        $esLider = $equipo->esLider($user);
        $esMiembro = $equipo->esMiembro($user);
        $tieneSolicitudPendiente = $equipo->tieneSolicitudPendiente($user);

        return view('equipos.show', compact(
            'equipo',
            'esLider',
            'esMiembro',
            'tieneSolicitudPendiente'
        ));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(Evento $evento)
    {
        // Verificar que esté inscrito
        $user = Auth::user();
        if (!$user->estaInscritoEn($evento)) {
            return redirect()->route('eventos.show', $evento)
                ->with('error', 'Debes estar inscrito en el evento para crear un equipo.');
        }

        // Verificar que no tenga equipo ya
        if ($user->tieneEquipoEnEvento($evento)) {
            return redirect()->route('eventos.show', $evento)
                ->with('error', 'Ya tienes un equipo en este evento.');
        }

        return view('equipos.create', compact('evento'));
    }

    /**
     * Guardar nuevo equipo
     */
    public function store(Request $request, Evento $evento)
    {
        $user = Auth::user();

        // Validaciones previas
        if (!$user->estaInscritoEn($evento)) {
            return redirect()->back()
                ->with('error', 'Debes estar inscrito en el evento.');
        }

        if ($user->tieneEquipoEnEvento($evento)) {
            return redirect()->back()
                ->with('error', 'Ya tienes un equipo en este evento.');
        }

        // Validar datos
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:equipos,nombre,NULL,id,evento_id,' . $evento->id
            ],
            'descripcion' => 'required|string|max:500',
            'max_miembros' => 'nullable|integer|min:' . $evento->min_miembros_equipo . '|max:' . $evento->max_miembros_equipo,
            'avatar' => 'nullable|image|max:1024',
        ]);

        // Subir avatar si existe
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')
                ->store('equipos/avatars', 'public');
        }

        // Crear equipo
        $validated['evento_id'] = $evento->id;
        $validated['lider_id'] = $user->id;
        $validated['max_miembros'] = $validated['max_miembros'] ?? $evento->max_miembros_equipo;
        $validated['estado'] = 'reclutando';

        $equipo = Equipo::create($validated);

        // Agregar al líder como miembro activo
        $equipo->agregarMiembro($user, 'Líder', 'aceptado');

        // Crear proyecto vacío
        Proyecto::create([
            'equipo_id' => $equipo->id,
            'evento_id' => $evento->id,
            'nombre' => 'Proyecto de ' . $equipo->nombre,
            'estado' => 'en_desarrollo'
        ]);

        return redirect()->route('equipos.show', $equipo)
            ->with('success', '¡Equipo creado exitosamente!');
    }

    /**
     * Solicitar unirse a un equipo
     */
    public function solicitarUnirse(Request $request, Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que esté inscrito en el evento
        if (!$user->estaInscritoEn($equipo->evento)) {
            return redirect()->back()
                ->with('error', 'Debes estar inscrito en el evento.');
        }

        // Verificar que no tenga equipo
        if ($user->tieneEquipoEnEvento($equipo->evento)) {
            return redirect()->back()
                ->with('error', 'Ya tienes un equipo en este evento.');
        }

        // Verificar que el equipo pueda aceptar miembros
        if (!$equipo->puedeAceptarMiembros()) {
            return redirect()->back()
                ->with('error', 'Este equipo ya está completo.');
        }

        // Verificar que no tenga solicitud pendiente
        if ($equipo->tieneSolicitudPendiente($user)) {
            return redirect()->back()
                ->with('error', 'Ya tienes una solicitud pendiente en este equipo.');
        }

        // Validar rol
        $validated = $request->validate([
            'rol_en_equipo' => 'required|string|max:100',
            'especializacion' => 'nullable|string|max:100',
        ]);

        // Agregar solicitud
        $equipo->agregarMiembro(
            $user,
            $validated['rol_en_equipo'],
            $validated['especializacion'] ?? null
        );

        // Notificar al líder
        Notificacion::create([
            'user_id' => $equipo->lider_id,
            'tipo' => 'solicitud_equipo',
            'titulo' => 'Nueva solicitud para ' . $equipo->nombre,
            'mensaje' => $user->name . ' quiere unirse a tu equipo como ' . $validated['rol_en_equipo'],
            'datos_adicionales' => [
                'equipo_id' => $equipo->id,
                'solicitante_id' => $user->id,
            ],
            'url_accion' => route('equipos.show', $equipo),
        ]);

        return redirect()->back()
            ->with('success', 'Solicitud enviada. El líder revisará tu solicitud.');
    }

    /**
     * Aceptar solicitud de miembro (solo líder)
     */
    public function aceptarMiembro(Equipo $equipo, $userId)
    {
        $user = Auth::user();

        // Verificar que sea el líder
        if (!$equipo->esLider($user)) {
            abort(403, 'Solo el líder puede aceptar miembros.');
        }

        $solicitante = User::findOrFail($userId);

        // Aceptar miembro
        $equipo->aceptarMiembro($solicitante);

        // Notificar al solicitante
        Notificacion::create([
            'user_id' => $solicitante->id,
            'tipo' => 'solicitud_aceptada',
            'titulo' => 'Solicitud aceptada',
            'mensaje' => 'Tu solicitud para unirte a ' . $equipo->nombre . ' ha sido aceptada',
            'datos_adicionales' => ['equipo_id' => $equipo->id],
            'url_accion' => route('equipos.show', $equipo),
        ]);

        return redirect()->back()
            ->with('success', 'Miembro aceptado exitosamente.');
    }

    /**
     * Rechazar solicitud de miembro (solo líder)
     */
    public function rechazarMiembro(Equipo $equipo, $userId)
    {
        $user = Auth::user();

        if (!$equipo->esLider($user)) {
            abort(403);
        }

        $solicitante = User::findOrFail($userId);
        $equipo->rechazarMiembro($solicitante);

        return redirect()->back()
            ->with('success', 'Solicitud rechazada.');
    }

    /**
     * Abandonar equipo
     */
    public function abandonar(Equipo $equipo)
    {
        $user = Auth::user();

        // Verificar que sea miembro
        if (!$equipo->esMiembro($user)) {
            return redirect()->back()
                ->with('error', 'No eres miembro de este equipo.');
        }

        // Verificar que no sea el líder
        if ($equipo->esLider($user)) {
            return redirect()->back()
                ->with('error', 'El líder no puede abandonar el equipo. Debes transferir el liderazgo o eliminar el equipo.');
        }

        // Remover miembro
        $equipo->removerMiembro($user);

        return redirect()->route('eventos.show', $equipo->evento)
            ->with('success', 'Has abandonado el equipo.');
    }

    /**
     * Actualizar equipo (solo líder)
     */
    public function update(Request $request, Equipo $equipo)
    {
        $user = Auth::user();

        if (!$equipo->esLider($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'descripcion' => 'required|string|max:500',
            'avatar' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('avatar')) {
            if ($equipo->avatar) {
                Storage::disk('public')->delete($equipo->avatar);
            }
            $validated['avatar'] = $request->file('avatar')
                ->store('equipos/avatars', 'public');
        }

        $equipo->update($validated);

        return redirect()->back()
            ->with('success', 'Equipo actualizado exitosamente.');
    }

    /**
     * Eliminar equipo (solo líder, solo si está vacío)
     */
    public function destroy(Equipo $equipo)
    {
        $user = Auth::user();

        if (!$equipo->esLider($user)) {
            abort(403);
        }

        // Verificar que solo tenga al líder
        if ($equipo->totalMiembros() > 1) {
            return redirect()->back()
                ->with('error', 'No puedes eliminar un equipo con miembros. Primero deben abandonar o puedes removerlos.');
        }

        $evento = $equipo->evento;
        $equipo->delete();

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Equipo eliminado exitosamente.');
    }
}