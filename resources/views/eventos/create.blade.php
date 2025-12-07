<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Crear Nuevo Evento
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Configura todos los detalles del evento de desarrollo de software</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-lg">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <span class="px-2 py-0.5 bg-indigo-200 dark:bg-indigo-800 text-indigo-800 dark:text-indigo-200 rounded text-xs font-bold">Administrador</span>
                </div>
            </div>

            <!-- Formulario -->
            <form action="{{ route('eventos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                
                <!-- Información Básica -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Información Básica</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre del Evento -->
                        <div class="md:col-span-2">
                            <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre del Evento
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre') }}"
                                   maxlength="35"
                                   placeholder="Ej: Hackathon 2025"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nombre') border-red-500 @enderror">
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Solo letras, números y guiones (-)</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span id="nombreCount">0</span>/35
                                </p>
                            </div>
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo de Evento -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Evento
                            </label>
                            <select id="tipo" 
                                    name="tipo" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('tipo') border-red-500 @enderror">
                                <option value="">Selecciona un tipo</option>
                                <option value="hackathon" {{ old('tipo') == 'hackathon' ? 'selected' : '' }}>Hackathon</option>
                                <option value="datathon" {{ old('tipo') == 'datathon' ? 'selected' : '' }}>Datathon</option>
                                <option value="concurso" {{ old('tipo') == 'concurso' ? 'selected' : '' }}>Concurso</option>
                                <option value="workshop" {{ old('tipo') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duración -->
                        <div>
                            <label for="duracion_horas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Duración (horas)
                            </label>
                            <input type="number" 
                                   id="duracion_horas" 
                                   name="duracion_horas" 
                                   value="{{ old('duracion_horas', 48) }}"
                                   min="1"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <!-- Descripción -->
                        <div class="md:col-span-2">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descripción
                            </label>
                            <textarea id="descripcion" 
                                      name="descripcion" 
                                      rows="4"
                                      maxlength="150" 
                                      required
                                      placeholder="Describe el evento, objetivos y qué pueden esperar los participantes..."
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Descripción del evento</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span id="descripcionCount">0</span>/150
                                </p>
                            </div>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fechas y Cronograma -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Fechas y Cronograma</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Límite de Registro -->
                        <div>
                            <label for="fecha_limite_registro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full text-xs font-bold">1</span>
                                Límite de Registro
                            </label>
                            <input type="datetime-local" 
                                   id="fecha_limite_registro" 
                                   name="fecha_limite_registro" 
                                   value="{{ old('fecha_limite_registro') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('fecha_limite_registro') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Fecha límite para que los usuarios se registren</p>
                        </div>

                        <!-- Fecha de Inicio -->
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 rounded-full text-xs font-bold">2</span>
                                Fecha de Inicio del Evento
                            </label>
                            <input type="datetime-local" 
                                   id="fecha_inicio" 
                                   name="fecha_inicio" 
                                   value="{{ old('fecha_inicio') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('fecha_inicio') border-red-500 @enderror">
                        </div>

                        <!-- Fecha de Fin -->
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold">3</span>
                                Fecha de Fin del Evento
                            </label>
                            <input type="datetime-local" 
                                   id="fecha_fin" 
                                   name="fecha_fin" 
                                   value="{{ old('fecha_fin') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('fecha_fin') border-red-500 @enderror">
                        </div>

                        <!-- Fecha de Evaluación -->
                        <div>
                            <label for="fecha_evaluacion" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold">4</span>
                                Fecha de Evaluación
                            </label>
                            <input type="datetime-local" 
                                   id="fecha_evaluacion" 
                                   name="fecha_evaluacion" 
                                   value="{{ old('fecha_evaluacion') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Fecha en que los jueces evaluarán los proyectos</p>
                        </div>

                        <!-- Fecha de Premiación -->
                        <div class="md:col-span-2">
                            <label for="fecha_premiacion" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full text-xs font-bold">5</span>
                                Fecha de Premiación
                            </label>
                            <input type="datetime-local" 
                                   id="fecha_premiacion" 
                                   name="fecha_premiacion" 
                                   value="{{ old('fecha_premiacion') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Fecha de la ceremonia de premiación</p>
                        </div>
                    </div>
                </div>

                <!-- Participantes y Equipos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Participantes y Equipos</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Máximo de Participantes -->
                        <div>
                            <label for="max_participantes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Máximo de Participantes
                            </label>
                            <input type="number" 
                                   id="max_participantes" 
                                   name="max_participantes" 
                                   value="{{ old('max_participantes', 100) }}"
                                   min="10"
                                   max="1000"
                                   placeholder="100"
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mínimo 10, máximo 1000</p>
                        </div>

                        <!-- Tamaño Mínimo de Equipo -->
                        <div>
                            <label for="min_miembros_equipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tamaño Mínimo de Equipo
                            </label>
                            <input type="number" 
                                   id="min_miembros_equipo" 
                                   name="min_miembros_equipo" 
                                   value="{{ old('min_miembros_equipo', 5) }}"
                                   min="5" 
                                   max="5"
                                   required
                                   readonly
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-300 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Obligatorio: 5 miembros</p>
                        </div>

                        <!-- Tamaño Máximo de Equipo -->
                        <div>
                            <label for="max_miembros_equipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tamaño Máximo de Equipo
                            </label>
                            <input type="number" 
                                   id="max_miembros_equipo" 
                                   name="max_miembros_equipo" 
                                   value="{{ old('max_miembros_equipo', 6) }}"
                                   min="6" 
                                   max="6"
                                   required
                                   readonly
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-gray-300 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Obligatorio: 6 miembros</p>
                        </div>
                    </div>
                </div>

                <!-- Roles Requeridos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Roles Requeridos</h2>
                        </div>
                        <button type="button" 
                                onclick="agregarRolPersonalizado()" 
                                class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-lg text-sm font-medium hover:bg-indigo-200 dark:hover:bg-indigo-800 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Agregar Rol
                        </button>
                    </div>
                    
                    <div id="roles-container" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $rolesBase = ['Programador', 'Diseñador', 'Analista de Negocios', 'Analista de Datos', 'Asesor'];
                            $rolesSeleccionados = old('roles', ['Asesor']);
                        @endphp
                        
                        @foreach($rolesBase as $rol)
                            @php
                                $esAsesor = $rol === 'Asesor';
                            @endphp
                            <label class="flex items-center gap-3 p-4 border-2 {{ $esAsesor ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }} rounded-lg hover:border-indigo-300 cursor-pointer transition {{ $esAsesor ? 'ring-2 ring-indigo-200' : '' }}">
                                <input type="checkbox" 
                                       name="roles[]" 
                                       value="{{ $rol }}" 
                                       {{ in_array($rol, $rolesSeleccionados) ? 'checked' : '' }}
                                       {{ $esAsesor ? 'disabled' : '' }}
                                       class="w-5 h-5 text-indigo-600 rounded {{ $esAsesor ? 'cursor-not-allowed' : '' }}">
                                <!-- Hidden input para Asesor siempre seleccionado -->
                                @if($esAsesor)
                                    <input type="hidden" name="roles[]" value="Asesor">
                                @endif
                                <span class="font-medium {{ $esAsesor ? 'text-indigo-700' : '' }}">
                                    {{ $rol }}
                                    @if($esAsesor)
                                        <span class="ml-1 px-2 py-0.5 bg-indigo-200 text-indigo-800 rounded text-xs">Obligatorio</span>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Ubicación -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Ubicación</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Tipo de Evento -->
                        <div class="flex gap-4">
                            <label class="flex items-center gap-3 p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-indigo-300 dark:hover:border-indigo-500 cursor-pointer flex-1 transition bg-white dark:bg-gray-700">
                                <input type="radio" name="es_virtual" value="1" class="w-5 h-5 text-indigo-600">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Evento Virtual</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Se llevará a cabo en línea</p>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-4 border-2 border-indigo-500 dark:border-indigo-400 rounded-lg cursor-pointer flex-1 bg-indigo-50 dark:bg-indigo-900/30">
                                <input type="radio" name="es_virtual" value="0" checked class="w-5 h-5 text-indigo-600">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Ubicación Física</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Lugar físico especificado</p>
                                </div>
                            </label>
                        </div>

                        <!-- Ubicación Física -->
                        <div id="ubicacion-fisica">
                            <label for="ubicacion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ubicación Física
                            </label>
                            <input type="text" 
                                   id="ubicacion" 
                                   name="ubicacion" 
                                   value="{{ old('ubicacion') }}"
                                   maxlength="50"
                                   placeholder="Ej: Instituto Tecnológico de Oaxaca, Centro de Cómputo"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('ubicacion') border-red-500 @enderror">
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Letras, números, comas y puntos</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span id="ubicacionCount">0</span>/50
                                </p>
                            </div>
                            @error('ubicacion')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Premios -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Premios</h2>
                        </div>
                        <button type="button" 
                                onclick="agregarPremio()" 
                                class="px-4 py-2 bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300 rounded-lg text-sm font-medium hover:bg-pink-200 dark:hover:bg-pink-800 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Agregar Premio
                        </button>
                    </div>

                    <div id="premios-container" class="space-y-3">
                        
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 px-6 py-4 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-bold text-lg transition shadow-lg hover:shadow-xl">
                        Crear Evento
                    </button>
                    <a href="{{ route('dashboard') }}" 
                       class="px-6 py-4 bg-white hover:bg-gray-50 border-2 border-gray-300 text-gray-700 rounded-lg font-medium transition">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

    <!-- Script de validaciones -->
    <script src="{{ asset('js/eventos-validaciones.js') }}"></script>
    
    <!-- Inicializar contadores con valores existentes -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar contador de nombre
            const nombre = document.getElementById('nombre');
            const nombreCount = document.getElementById('nombreCount');
            if (nombre && nombreCount) {
                nombreCount.textContent = nombre.value.length;
            }
            
            // Inicializar contador de descripción
            const descripcion = document.getElementById('descripcion');
            const descripcionCount = document.getElementById('descripcionCount');
            if (descripcion && descripcionCount) {
                descripcionCount.textContent = descripcion.value.length;
            }
            
            // Inicializar contador de ubicación
            const ubicacion = document.getElementById('ubicacion');
            const ubicacionCount = document.getElementById('ubicacionCount');
            if (ubicacion && ubicacionCount) {
                ubicacionCount.textContent = ubicacion.value.length;
            }
        });
    </script>
</x-app-layout>