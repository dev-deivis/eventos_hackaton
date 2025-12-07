<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Editar Perfil</h2>
                <p class="text-gray-600 mt-1">Actualiza tu información personal y habilidades</p>
            </div>

            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6">
                
                <!-- Información Personal -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Información Personal</h3>
                    
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', auth()->user()->email) }}" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(auth()->user()->participante)
                                <!-- Carrera -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Carrera</label>
                                    <select name="carrera_id" 
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->id }}" 
                                                    {{ old('carrera_id', auth()->user()->participante->carrera_id) == $carrera->id ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('carrera_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No. Control -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Control</label>
                                    <input type="text" 
                                           name="no_control" 
                                           value="{{ old('no_control', auth()->user()->participante->no_control) }}" 
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    @error('no_control')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Semestre -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Semestre</label>
                                    <select name="semestre" 
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" 
                                                    {{ old('semestre', auth()->user()->participante->semestre) == $i ? 'selected' : '' }}>
                                                {{ $i }}° Semestre
                                            </option>
                                        @endfor
                                    </select>
                                    @error('semestre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <input type="text" 
                                           name="telefono" 
                                           value="{{ old('telefono', auth()->user()->participante->telefono) }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    @error('telefono')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Biografía -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Biografía</label>
                                    <textarea name="biografia" 
                                              id="biografia"
                                              rows="4"
                                              maxlength="300"
                                              placeholder="Cuéntanos sobre ti, tus intereses y experiencia..."
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ old('biografia', auth()->user()->participante->biografia) }}</textarea>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="text-xs text-gray-500">Cuéntanos sobre ti y tus intereses</p>
                                        <p class="text-xs text-gray-500">
                                            <span id="biografiaCount">{{ strlen(old('biografia', auth()->user()->participante->biografia ?? '')) }}</span>/300
                                        </p>
                                    </div>
                                    @error('biografia')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('profile.show') }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Habilidades -->
                @if(auth()->user()->participante)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Mis Habilidades</h3>
                                <p class="text-sm text-gray-600 mt-1">Agrega las tecnologías y herramientas que dominas</p>
                            </div>
                            <button onclick="toggleModalAgregar()" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Agregar Habilidad
                            </button>
                        </div>

                        <!-- Lista de habilidades -->
                        <div class="space-y-4">
                            @forelse(auth()->user()->participante->habilidades as $habilidad)
                                <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:border-indigo-300 transition-colors">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-gray-900 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $habilidad->nombre }}
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $habilidad->nivel }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="{{ $habilidad->color }} h-2 rounded-full transition-all duration-500" 
                                                 style="width: {{ $habilidad->nivel }}%"></div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="editarHabilidad({{ $habilidad->id }}, '{{ $habilidad->nombre }}', {{ $habilidad->nivel }}, '{{ $habilidad->color }}')" 
                                                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('profile.habilidad.destroy', $habilidad) }}" 
                                              onsubmit="return confirm('¿Eliminar esta habilidad?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <p class="text-sm">No has agregado habilidades aún</p>
                                    <p class="text-xs mt-1">Haz click en "Agregar Habilidad" para comenzar</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Cambiar Contraseña -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Cambiar Contraseña</h3>
                    
                    <form method="POST" action="{{ route('profile.password') }}" id="formPassword">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="current_password"
                                           name="current_password" 
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <button type="button" 
                                            onclick="togglePassword('current_password')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password"
                                           name="password" 
                                           required
                                           minlength="8"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <button type="button" 
                                            onclick="togglePassword('password')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Requisitos de contraseña -->
                                <div class="mt-2 space-y-1">
                                    <p class="text-xs font-medium text-gray-700">La contraseña debe contener:</p>
                                    <div class="space-y-1 text-xs">
                                        <div id="req-length" class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Mínimo 8 caracteres</span>
                                        </div>
                                        <div id="req-upper" class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Una letra mayúscula</span>
                                        </div>
                                        <div id="req-lower" class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Una letra minúscula</span>
                                        </div>
                                        <div id="req-number" class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Un número</span>
                                        </div>
                                        <div id="req-special" class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Un carácter especial (!@#$%^&*)</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation"
                                           name="password_confirmation" 
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <button type="button" 
                                            onclick="togglePassword('password_confirmation')"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <p id="match-message" class="mt-1 text-xs hidden"></p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    id="btnSubmitPassword"
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Agregar Habilidad -->
    <div id="modalAgregar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Agregar Nueva Habilidad</h3>
            
            <form method="POST" action="{{ route('profile.habilidad.store') }}">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Habilidad</label>
                        <input type="text" 
                               name="nombre" 
                               placeholder="Ej: JavaScript, Python, React..."
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de Dominio (%)</label>
                        <input type="range" 
                               name="nivel" 
                               min="0" 
                               max="100" 
                               value="50" 
                               step="5"
                               oninput="this.nextElementSibling.textContent = this.value + '%'"
                               class="w-full">
                        <div class="text-center text-lg font-semibold text-indigo-600 mt-2">50%</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach(\App\Models\Habilidad::coloresDisponibles() as $colorClass => $colorNombre)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $colorClass }}" class="hidden peer" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="w-10 h-10 {{ $colorClass }} rounded-lg border-2 border-transparent peer-checked:border-gray-900 hover:scale-110 transition-transform"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" 
                            onclick="toggleModalAgregar()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Habilidad -->
    <div id="modalEditar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Editar Habilidad</h3>
            
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Habilidad</label>
                        <input type="text" 
                               id="editNombre"
                               name="nombre" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de Dominio (%)</label>
                        <input type="range" 
                               id="editNivel"
                               name="nivel" 
                               min="0" 
                               max="100" 
                               step="5"
                               oninput="this.nextElementSibling.textContent = this.value + '%'"
                               class="w-full">
                        <div id="editNivelDisplay" class="text-center text-lg font-semibold text-indigo-600 mt-2">50%</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <div class="grid grid-cols-5 gap-2" id="editColores">
                            @foreach(\App\Models\Habilidad::coloresDisponibles() as $colorClass => $colorNombre)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $colorClass }}" class="hidden peer">
                                    <div class="w-10 h-10 {{ $colorClass }} rounded-lg border-2 border-transparent peer-checked:border-gray-900 hover:scale-110 transition-transform"></div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" 
                            onclick="toggleModalEditar()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModalAgregar() {
            document.getElementById('modalAgregar').classList.toggle('hidden');
        }

        function toggleModalEditar() {
            document.getElementById('modalEditar').classList.toggle('hidden');
        }

        function editarHabilidad(id, nombre, nivel, color) {
            // Configurar form action
            document.getElementById('formEditar').action = `/perfil/habilidades/${id}`;
            
            // Llenar campos
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editNivel').value = nivel;
            document.getElementById('editNivelDisplay').textContent = nivel + '%';
            
            // Seleccionar color
            const colorInputs = document.querySelectorAll('#editColores input[type="radio"]');
            colorInputs.forEach(input => {
                if (input.value === color) {
                    input.checked = true;
                }
            });
            
            // Mostrar modal
            toggleModalEditar();
        }

        // Cerrar modales al hacer click fuera
        document.getElementById('modalAgregar').addEventListener('click', function(e) {
            if (e.target === this) toggleModalAgregar();
        });
        
        document.getElementById('modalEditar').addEventListener('click', function(e) {
            if (e.target === this) toggleModalEditar();
        });

        // ============================================
        // VALIDACIÓN DE BIOGRAFÍA
        // ============================================
        const biografia = document.getElementById('biografia');
        const biografiaCount = document.getElementById('biografiaCount');
        
        if (biografia && biografiaCount) {
            biografia.addEventListener('input', function() {
                const length = this.value.length;
                biografiaCount.textContent = length;
                
                // Código de colores
                if (length >= 280) {
                    biografiaCount.classList.remove('text-gray-500', 'text-yellow-600');
                    biografiaCount.classList.add('text-red-600', 'font-bold');
                } else if (length >= 250) {
                    biografiaCount.classList.remove('text-gray-500', 'text-red-600');
                    biografiaCount.classList.add('text-yellow-600', 'font-semibold');
                } else {
                    biografiaCount.classList.remove('text-yellow-600', 'text-red-600', 'font-bold', 'font-semibold');
                    biografiaCount.classList.add('text-gray-500');
                }
                
                // Limitar a 300 caracteres
                if (length > 300) {
                    this.value = this.value.substring(0, 300);
                    biografiaCount.textContent = 300;
                }
            });
        }

        // ============================================
        // VALIDACIÓN DE CONTRASEÑA
        // ============================================
        const passwordInput = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const btnSubmit = document.getElementById('btnSubmitPassword');
        const matchMessage = document.getElementById('match-message');
        
        // Requisitos
        const requirements = {
            length: false,
            upper: false,
            lower: false,
            number: false,
            special: false
        };
        
        // Función para alternar visibilidad de contraseña
        window.togglePassword = function(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        };
        
        // Validar requisitos de contraseña
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Validar longitud
                requirements.length = password.length >= 8;
                updateRequirement('req-length', requirements.length);
                
                // Validar mayúscula
                requirements.upper = /[A-Z]/.test(password);
                updateRequirement('req-upper', requirements.upper);
                
                // Validar minúscula
                requirements.lower = /[a-z]/.test(password);
                updateRequirement('req-lower', requirements.lower);
                
                // Validar número
                requirements.number = /[0-9]/.test(password);
                updateRequirement('req-number', requirements.number);
                
                // Validar carácter especial
                requirements.special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                updateRequirement('req-special', requirements.special);
                
                // Validar coincidencia
                checkPasswordMatch();
                
                // Habilitar/deshabilitar botón
                updateSubmitButton();
            });
        }
        
        // Validar que las contraseñas coincidan
        if (passwordConfirmation) {
            passwordConfirmation.addEventListener('input', checkPasswordMatch);
        }
        
        function updateRequirement(id, isValid) {
            const element = document.getElementById(id);
            if (isValid) {
                element.classList.remove('text-gray-500');
                element.classList.add('text-green-600');
            } else {
                element.classList.remove('text-green-600');
                element.classList.add('text-gray-500');
            }
        }
        
        function checkPasswordMatch() {
            if (!passwordInput || !passwordConfirmation || !matchMessage) return;
            
            const password = passwordInput.value;
            const confirmation = passwordConfirmation.value;
            
            if (confirmation.length === 0) {
                matchMessage.classList.add('hidden');
                return;
            }
            
            matchMessage.classList.remove('hidden');
            
            if (password === confirmation) {
                matchMessage.textContent = '✓ Las contraseñas coinciden';
                matchMessage.classList.remove('text-red-600');
                matchMessage.classList.add('text-green-600');
            } else {
                matchMessage.textContent = '✗ Las contraseñas no coinciden';
                matchMessage.classList.remove('text-green-600');
                matchMessage.classList.add('text-red-600');
            }
            
            updateSubmitButton();
        }
        
        function updateSubmitButton() {
            if (!btnSubmit || !passwordInput || !passwordConfirmation) return;
            
            const allRequirementsMet = Object.values(requirements).every(req => req === true);
            const passwordsMatch = passwordInput.value === passwordConfirmation.value;
            const confirmationFilled = passwordConfirmation.value.length > 0;
            
            if (allRequirementsMet && passwordsMatch && confirmationFilled) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('bg-gray-400', 'cursor-not-allowed');
                btnSubmit.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            } else {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('bg-gray-400', 'cursor-not-allowed');
                btnSubmit.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            }
        }
        
        // Inicializar estado del botón
        if (btnSubmit) {
            updateSubmitButton();
        }
    </script>
    @endpush
</x-app-layout>
