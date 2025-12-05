<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Eventos Académicos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Indicador de fortaleza de contraseña */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .password-strength.weak { background-color: #ef4444; width: 33%; }
        .password-strength.medium { background-color: #f59e0b; width: 66%; }
        .password-strength.strong { background-color: #10b981; width: 100%; }
        
        /* Botón de mostrar/ocultar contraseña */
        .toggle-password {
            cursor: pointer;
            transition: color 0.2s;
        }
        .toggle-password:hover {
            color: #4f46e5;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex">
    
    <!-- Sección Izquierda - Información -->
    <div class="hidden lg:flex lg:w-1/2 bg-white p-12 flex-col justify-between">
        <!-- Logo y Título -->
        <div>
            <div class="flex items-center gap-4 mb-12">
                <div class="bg-indigo-600 p-4 rounded-2xl">
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Eventos Académicos</h1>
                    <p class="text-gray-600">Plataforma de Competencias Universitarias</p>
                </div>
            </div>

            <!-- Features -->
            <div class="space-y-8">
                <div class="flex items-start gap-4">
                    <div class="bg-pink-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Forma tu equipo</h3>
                        <p class="text-gray-600 text-sm">Conecta con estudiantes de diferentes carreras y crea equipos multidisciplinarios</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Compite y aprende</h3>
                        <p class="text-gray-600 text-sm">Participa en eventos académicos y desarrolla proyectos innovadores</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="bg-pink-100 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Obtén reconocimientos</h3>
                        <p class="text-gray-600 text-sm">Recibe constancias digitales por tu participación y logros</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tags de Roles -->
        <div class="flex flex-wrap gap-3">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                Programador
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                </svg>
                Diseñador
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                Analista de Negocios
            </span>
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-pink-500 text-white rounded-full text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/>
                    <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/>
                    <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/>
                </svg>
                Analista de Datos
            </span>
        </div>
    </div>

    <!-- Sección Derecha - Formulario -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            
            <!-- Tarjeta de Registro -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Crear Cuenta</h2>
                    <p class="text-gray-600">Únete a la comunidad académica y forma tu equipo</p>
                </div>

                <!-- Tabs -->
                <div class="flex gap-2 mb-6 bg-gray-100 p-1 rounded-lg">
                    <a href="{{ route('login') }}" 
                       class="flex-1 py-2 px-4 rounded-md text-sm font-medium text-gray-600 hover:text-gray-900 text-center">
                        Iniciar Sesión
                    </a>
                    <button type="button" 
                            class="flex-1 py-2 px-4 rounded-md text-sm font-medium bg-white text-gray-900 shadow-sm">
                        Registrarse
                    </button>
                </div>

                <!-- Formulario -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4" id="registerForm">
                    @csrf

                    <!-- Nombre y Apellidos en dos columnas -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input id="nombre" 
                                   type="text" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}"
                                   required 
                                   autofocus
                                   maxlength="20"
                                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                   placeholder="Ángel"
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('nombre') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500"><span id="nombreCount">0</span>/20 caracteres</p>
                            @error('nombre')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input id="apellidos" 
                                   type="text" 
                                   name="apellidos" 
                                   value="{{ old('apellidos') }}"
                                   required
                                   maxlength="20"
                                   pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                   placeholder="Matus Cruz"
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('apellidos') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500"><span id="apellidosCount">0</span>/20 caracteres</p>
                            @error('apellidos')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Número de Control -->
                    <div>
                        <label for="no_control" class="block text-sm font-medium text-gray-700 mb-1">
                            Número de control <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input id="no_control" 
                                   type="text" 
                                   name="no_control" 
                                   value="{{ old('no_control') }}"
                                   required
                                   maxlength="8"
                                   pattern="[0-9]{8}"
                                   placeholder="22161154"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('no_control') border-red-500 @enderror">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">8 dígitos numéricos</p>
                        @error('no_control')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Carrera y Semestre en dos columnas -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Carrera -->
                        <div>
                            <label for="carrera_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <select id="carrera_id" 
                                    name="carrera_id" 
                                    required
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('carrera_id') border-red-500 @enderror">
                                <option value="">Selecciona</option>
                                @foreach(\App\Models\Carrera::all() as $carrera)
                                    <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                        {{ $carrera->clave }}
                                    </option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="semestre" class="block text-sm font-medium text-gray-700 mb-1">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <select id="semestre" 
                                    name="semestre" 
                                    required
                                    class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('semestre') border-red-500 @enderror">
                                <option value="">Selecciona</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('semestre') == $i ? 'selected' : '' }}>{{ $i }}º</option>
                                @endfor
                            </select>
                            @error('semestre')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                            Teléfono <span class="text-gray-400">(Opcional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                            <input id="telefono" 
                                   type="tel" 
                                   name="telefono" 
                                   value="{{ old('telefono') }}"
                                   maxlength="10"
                                   pattern="[0-9]{10}"
                                   placeholder="9511234567"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('telefono') border-red-500 @enderror">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">10 dígitos sin espacios</p>
                        @error('telefono')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   autocomplete="username"
                                   placeholder="tucorreo@itoaxaca.edu.mx"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required
                                   minlength="8"
                                   autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="block w-full pl-9 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('password') border-red-500 @enderror">
                            <!-- Botón mostrar/ocultar contraseña -->
                            <button type="button" 
                                    onclick="togglePassword('password', 'eyeIcon')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                <svg id="eyeIcon" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Indicador de fortaleza -->
                        <div class="mt-2 bg-gray-200 rounded-full h-1 overflow-hidden">
                            <div id="passwordStrength" class="password-strength"></div>
                        </div>
                        <div id="passwordMessage" class="mt-1 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-500">Mínimo 8 caracteres, 1 letra y 1 número</span>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   required
                                   minlength="8"
                                   autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="block w-full pl-9 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <!-- Botón mostrar/ocultar contraseña -->
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation', 'eyeIconConfirm')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center toggle-password">
                                <svg id="eyeIconConfirm" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="passwordMatch" class="mt-1 text-xs"></p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="submitBtn"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-xl mt-6">
                        Crear cuenta
                    </button>

                    <!-- Terms -->
                    <p class="text-center text-xs text-gray-500 mt-4">
                        Al registrarte, aceptas nuestros 
                        <a href="#" class="text-indigo-600 hover:underline">términos de servicio</a> y 
                        <a href="#" class="text-indigo-600 hover:underline">política de privacidad</a>
                    </p>
                </form>
            </div>

            <!-- Link para móvil -->
            <div class="lg:hidden mt-6 text-center">
                <p class="text-gray-600">
                    ¿Ya tienes cuenta? 
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar contraseña
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                // Cambiar a ícono de ojo cerrado
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                `;
            } else {
                input.type = 'password';
                // Cambiar a ícono de ojo abierto
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        // Validaciones en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const nombreInput = document.getElementById('nombre');
            const apellidosInput = document.getElementById('apellidos');
            const noControlInput = document.getElementById('no_control');
            const telefonoInput = document.getElementById('telefono');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');

            // Contador de caracteres para nombre
            nombreInput.addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById('nombreCount').textContent = count;
                
                // Solo permitir letras y acentos
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                
                // Limitar a 20 caracteres
                if (this.value.length > 20) {
                    this.value = this.value.substring(0, 20);
                }
            });

            // Contador de caracteres para apellidos
            apellidosInput.addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById('apellidosCount').textContent = count;
                
                // Solo permitir letras y acentos
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                
                // Limitar a 20 caracteres
                if (this.value.length > 20) {
                    this.value = this.value.substring(0, 20);
                }
            });

            // Validación número de control (solo números, 8 dígitos)
            noControlInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 8) {
                    this.value = this.value.substring(0, 8);
                }
            });

            // Validación teléfono (solo números, 10 dígitos)
            telefonoInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length > 10) {
                    this.value = this.value.substring(0, 10);
                }
            });

            // Validación de fortaleza de contraseña
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strengthBar = document.getElementById('passwordStrength');
                const messageDiv = document.getElementById('passwordMessage');
                
                // Criterios de validación
                const minLength = password.length >= 8;
                const hasLetter = /[a-zA-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                
                let strength = 0;
                let strengthText = '';
                let strengthClass = '';
                
                if (minLength) strength++;
                if (hasLetter) strength++;
                if (hasNumber) strength++;
                if (hasSpecial) strength++;
                
                if (password.length === 0) {
                    strengthBar.className = 'password-strength';
                    messageDiv.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-500">Mínimo 8 caracteres, 1 letra y 1 número</span>
                    `;
                    messageDiv.className = 'mt-1 text-xs flex items-center gap-1';
                } else if (strength <= 2) {
                    strengthClass = 'weak';
                    messageDiv.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-600">Contraseña débil</span>
                    `;
                    messageDiv.className = 'mt-1 text-xs flex items-center gap-1';
                } else if (strength === 3) {
                    strengthClass = 'medium';
                    messageDiv.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-yellow-600">Contraseña media</span>
                    `;
                    messageDiv.className = 'mt-1 text-xs flex items-center gap-1';
                } else {
                    strengthClass = 'strong';
                    messageDiv.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-600">Contraseña fuerte</span>
                    `;
                    messageDiv.className = 'mt-1 text-xs flex items-center gap-1';
                }
                
                strengthBar.className = `password-strength ${strengthClass}`;
                
                // Validar si cumple requisitos mínimos
                if (!minLength || !hasLetter || !hasNumber) {
                    messageDiv.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-600">Mínimo 8 caracteres, 1 letra y 1 número</span>
                    `;
                    messageDiv.className = 'mt-1 text-xs flex items-center gap-1';
                }
            });

            // Validar que las contraseñas coincidan
            passwordConfirmInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirm = this.value;
                const matchMessage = document.getElementById('passwordMatch');
                
                if (confirm.length === 0) {
                    matchMessage.innerHTML = '';
                } else if (password === confirm) {
                    matchMessage.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-600">Las contraseñas coinciden</span>
                    `;
                    matchMessage.className = 'mt-1 text-xs flex items-center gap-1';
                } else {
                    matchMessage.innerHTML = `
                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-600">Las contraseñas no coinciden</span>
                    `;
                    matchMessage.className = 'mt-1 text-xs flex items-center gap-1';
                }
            });

            // Validación final antes de enviar
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirm = passwordConfirmInput.value;
                
                // Validar contraseña
                const minLength = password.length >= 8;
                const hasLetter = /[a-zA-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                
                if (!minLength || !hasLetter || !hasNumber) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres, 1 letra y 1 número');
                    return false;
                }
                
                // Validar que coincidan
                if (password !== confirm) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    return false;
                }
                
                return true;
            });
        });
    </script>

</body>
</html>
