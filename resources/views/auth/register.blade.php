<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Eventos Académicos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nombre y Apellidos en dos columnas -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre
                            </label>
                            <input id="nombre" 
                                   type="text" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}"
                                   required 
                                   autofocus
                                   placeholder="Ángel"
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellidos
                            </label>
                            <input id="apellidos" 
                                   type="text" 
                                   name="apellidos" 
                                   value="{{ old('apellidos') }}"
                                   required
                                   placeholder="Matus"
                                   class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('apellidos') border-red-500 @enderror">
                            @error('apellidos')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Número de Control -->
                    <div>
                        <label for="num_control" class="block text-sm font-medium text-gray-700 mb-1">
                            Número de control
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <input id="num_control" 
                                   type="text" 
                                   name="num_control" 
                                   value="{{ old('num_control') }}"
                                   required
                                   placeholder="22161154"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('num_control') border-red-500 @enderror">
                        </div>
                        @error('num_control')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Carrera -->
                    <div>
                        <label for="carrera_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Carrera
                        </label>
                        <select id="carrera_id" 
                                name="carrera_id" 
                                required
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('carrera_id') border-red-500 @enderror">
                            <option value="">Selecciona tu carrera</option>
                            @foreach(\App\Models\Carrera::all() as $carrera)
                                <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('carrera_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rol Preferido -->
                    <div>
                        <label for="rol_preferido" class="block text-sm font-medium text-gray-700 mb-1">
                            Rol preferido
                        </label>
                        <input id="rol_preferido" 
                               type="text" 
                               name="rol_preferido" 
                               value="{{ old('rol_preferido') }}"
                               placeholder="¿En qué te especializas?"
                               class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        <p class="mt-1 text-xs text-gray-500">Ejemplo: Programador, Diseñador, Analista...</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Correo electrónico
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
                            Contraseña
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
                                   autocomplete="new-password"
                                   placeholder="••••••••••••"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm @error('password') border-red-500 @enderror">
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar contraseña
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
                                   autocomplete="new-password"
                                   placeholder="••••••••••••"
                                   class="block w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
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

</body>
</html>
