<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Editar Usuario: {{ $usuario->name }}
                </h1>
                <p class="text-gray-600 mt-1">Modifica la información y roles del usuario</p>
            </div>

            <!-- Formulario -->
            <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Información Básica -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900">Información Básica</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $usuario->name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $usuario->email) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Roles del Usuario -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100" 
                     x-data="{ 
                         rolJuezId: {{ $roles->where('nombre', 'juez')->first()->id ?? 0 }},
                         rolSeleccionado: {{ old('rol_id', $usuario->roles->first()->id ?? 0) }}
                     }">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900">Roles del Usuario</h2>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">Selecciona el rol del usuario. Solo puede tener un rol activo a la vez.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($roles as $rol)
                            @php
                                $descripcionesRoles = [
                                    'admin' => 'Acceso total al sistema, puede gestionar eventos, usuarios y toda la plataforma',
                                    'juez' => 'Puede calificar proyectos de los equipos participantes',
                                    'participante' => 'Puede crear equipos, unirse a eventos y participar en hackathons',
                                ];
                                $iconosRoles = [
                                    'admin' => '<path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
                                    'juez' => '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>',
                                    'participante' => '<path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>',
                                ];
                                $coloresRoles = [
                                    'admin' => 'border-red-300 bg-red-50',
                                    'juez' => 'border-purple-300 bg-purple-50',
                                    'participante' => 'border-blue-300 bg-blue-50',
                                ];
                                $descripcion = $descripcionesRoles[$rol->nombre] ?? '';
                                $icono = $iconosRoles[$rol->nombre] ?? '';
                                $colorBase = $coloresRoles[$rol->nombre] ?? 'border-gray-300 bg-gray-50';
                                $tieneRol = $usuario->roles->contains('id', $rol->id);
                            @endphp
                            
                            <label class="relative flex flex-col p-4 border-2 {{ $tieneRol ? $colorBase : 'border-gray-200 bg-white' }} rounded-lg cursor-pointer hover:border-indigo-300 transition">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-6 h-6 {{ $tieneRol ? 'text-'.$rol->nombre.'-600' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            {!! $icono !!}
                                        </svg>
                                        <span class="font-bold text-gray-900">{{ ucfirst($rol->nombre) }}</span>
                                    </div>
                                    <input type="radio" 
                                           name="rol_id" 
                                           value="{{ $rol->id }}" 
                                           {{ $tieneRol ? 'checked' : '' }}
                                           required
                                           @change="rolSeleccionado = parseInt($event.target.value)"
                                           class="w-5 h-5 text-indigo-600">
                                </div>
                                <p class="text-xs text-gray-600">{{ $descripcion }}</p>
                            </label>
                        @endforeach
                    </div>

                    @error('rol_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Asignación de Equipos (Solo para Jueces) -->
                    <div x-show="rolSeleccionado == rolJuezId"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="mt-6 p-6 bg-purple-50 rounded-xl border-2 border-purple-200">
                        
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-900">Equipos Asignados para Evaluación</h3>
                        </div>

                        <p class="text-sm text-gray-600 mb-4">Selecciona los equipos que este juez deberá evaluar.</p>

                        @if($equiposDisponibles->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto p-2">
                                @foreach($equiposDisponibles as $equipo)
                                    <label class="flex items-start p-4 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition">
                                        <input type="checkbox" 
                                               name="equipos[]" 
                                               value="{{ $equipo->id }}"
                                               {{ $usuario->equiposAsignados->contains('id', $equipo->id) ? 'checked' : '' }}
                                               class="mt-1 w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                                        <div class="ml-3 flex-1">
                                            <div class="font-bold text-gray-900">{{ $equipo->nombre }}</div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                                                    </svg>
                                                    {{ $equipo->evento->nombre }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $equipo->participantes->count() }} miembros
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-white rounded-lg border border-gray-200">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-600">No hay equipos disponibles para asignar</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información del Perfil (si es participante) -->
                @if($usuario->participante)
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-bold text-blue-900 mb-1">Perfil de Participante Activo</h3>
                                <p class="text-sm text-blue-700">
                                    Este usuario tiene un perfil de participante completo con la carrera: <strong>{{ $usuario->participante->carrera->nombre ?? 'No especificada' }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Botones de Acción -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-lg transition shadow-lg hover:shadow-xl">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" 
                       class="px-6 py-4 bg-white hover:bg-gray-50 border-2 border-gray-300 text-gray-700 rounded-lg font-medium transition">
                        Cancelar
                    </a>
                </div>
            </form>

            <!-- Cambiar Contraseña (Sección Separada) -->
            <div class="mt-8 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <h2 class="text-xl font-bold text-gray-900">Cambiar Contraseña</h2>
                </div>

                <form action="{{ route('admin.usuarios.update-password', $usuario) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <button type="submit" 
                            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                        Actualizar Contraseña
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
