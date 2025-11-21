<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perfil;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'num_control' => ['required', 'string', 'max:20', 'unique:perfiles,num_control'],
            'carrera_id' => ['required', 'exists:carreras,id'],
            'rol_preferido' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Crear usuario
        $nombreCompleto = $request->nombre . ' ' . $request->apellidos;
        
        $user = User::create([
            'name' => $nombreCompleto,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar rol de estudiante por defecto
        $rolEstudiante = Role::where('name', 'estudiante')->first();
        if ($rolEstudiante) {
            $user->roles()->attach($rolEstudiante->id);
        }

        // Crear perfil
        Perfil::create([
            'user_id' => $user->id,
            'carrera_id' => $request->carrera_id,
            'num_control' => $request->num_control,
            'biografia' => $request->rol_preferido ? 'Especialidad: ' . $request->rol_preferido : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
