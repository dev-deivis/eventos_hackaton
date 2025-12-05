<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Participante;
use App\Models\Rol;
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
        // Validaciones mejoradas con mensajes personalizados
        $request->validate([
            'nombre' => [
                'required', 
                'string', 
                'max:20',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/' // Solo letras y acentos
            ],
            'apellidos' => [
                'required', 
                'string', 
                'max:20',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/' // Solo letras y acentos
            ],
            'no_control' => [
                'required', 
                'string', 
                'size:8', // Exactamente 8 caracteres
                'regex:/^[0-9]{8}$/', // Solo números
                'unique:participantes,no_control'
            ],
            'carrera_id' => [
                'required', 
                'exists:carreras,id'
            ],
            'semestre' => [
                'required', 
                'integer', 
                'min:1', 
                'max:12'
            ],
            'telefono' => [
                'nullable', 
                'string', 
                'size:10', // Exactamente 10 dígitos
                'regex:/^[0-9]{10}$/' // Solo números
            ],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class
            ],
            'password' => [
                'required', 
                'confirmed',
                'min:8', // Mínimo 8 caracteres
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/' // Al menos 1 letra y 1 número
            ],
        ], [
            // Mensajes personalizados para nombre
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 20 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y acentos.',
            
            // Mensajes personalizados para apellidos
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.max' => 'Los apellidos no pueden tener más de 20 caracteres.',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras y acentos.',
            
            // Mensajes personalizados para número de control
            'no_control.required' => 'El número de control es obligatorio.',
            'no_control.size' => 'El número de control debe tener exactamente 8 dígitos.',
            'no_control.regex' => 'El número de control solo puede contener números.',
            'no_control.unique' => 'Este número de control ya está registrado.',
            
            // Mensajes personalizados para teléfono
            'telefono.size' => 'El teléfono debe tener exactamente 10 dígitos.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            
            // Mensajes personalizados para contraseña
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe contener al menos 1 letra y 1 número.',
            
            // Mensajes personalizados para email
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            
            // Mensajes personalizados para carrera y semestre
            'carrera_id.required' => 'Debes seleccionar una carrera.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            'semestre.required' => 'Debes seleccionar un semestre.',
            'semestre.min' => 'El semestre debe ser al menos 1.',
            'semestre.max' => 'El semestre no puede ser mayor a 12.',
        ]);

        // Crear usuario
        $nombreCompleto = $request->nombre . ' ' . $request->apellidos;
        
        $user = User::create([
            'name' => $nombreCompleto,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar rol de participante por defecto
        $rolParticipante = Rol::where('nombre', 'participante')->first();
        if ($rolParticipante) {
            $user->roles()->attach($rolParticipante->id);
        }

        // Crear perfil de participante
        Participante::create([
            'user_id' => $user->id,
            'carrera_id' => $request->carrera_id,
            'no_control' => $request->no_control,
            'semestre' => $request->semestre,
            'telefono' => $request->telefono,
            'biografia' => 'Estudiante apasionado por la tecnología y la innovación.',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
