<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header del Evento -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $evento->nombre }}</h1>
                        <!-- Tipo de Evento -->
                        @php
                            $tipoColors = [
                                'hackathon' => 'bg-blue-100 text-blue-700',
                                'datathon' => 'bg-purple-100 text-purple-700',
                                'concurso' => 'bg-pink-100 text-pink-700',
                                'workshop' => 'bg-green-100 text-green-700',
                            ];
                            $tipoColor = $tipoColors[$evento->tipo] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-block px-3 py-1 {{ $tipoColor }} rounded-full text-sm font-semibold mb-4">
                                {{ ucfirst($evento->tipo) }}
                            </span>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $evento->fecha_inicio->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $evento->ubicacion }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ $evento->duracion_horas }} horas
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Badge de Estado -->
                        @php
                            $estadoColors = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'abierto' => 'bg-green-100 text-green-700',
                                'en_progreso' => 'bg-blue-100 text-blue-700',
                                'cerrado' => 'bg-red-100 text-red-700',
                                'completado' => 'bg-purple-100 text-purple-700',
                            ];
                            $colorClass = $estadoColors[$evento->estado] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-4 py-2 {{ $colorClass }} rounded-lg font-medium">
                            {{ $evento->estadoTexto }}
                        </span>

                        <!-- Botones de Admin (Solo Admin) -->
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <!-- Botón Editar Evento -->
                            <a href="{{ route('eventos.edit', $evento) }}" 
                               class="px-4 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg font-medium flex items-center gap-2 transition">
                               <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                               </svg>
                            Editar
                            </a>

                            <!-- Botón Cambiar Estado con Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="px-4 py-2 bg-pink-100 text-pink-700 hover:bg-pink-200 rounded-lg font-medium flex items-center gap-2 transition">
                                    Cambiar Estado
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    <div class="py-1">
                                        <form action="{{ route('eventos.cambiar-estado', $evento) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="draft">
                                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Borrador
                                            </button>
                                        </form>
                                        <form action="{{ route('eventos.cambiar-estado', $evento) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="abierto">
                                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:bg-gray-700 text-green-700 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Abierto (Permitir registros)
                                            </button>
                                        </form>
                                        <form action="{{ route('eventos.cambiar-estado', $evento) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="en_progreso">
                                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:bg-gray-700 text-blue-700 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                </svg>
                                                En Progreso
                                            </button>
                                        </form>
                                        <form action="{{ route('eventos.cambiar-estado', $evento) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="cerrado">
                                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:bg-gray-700 text-red-700 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Cerrado
                                            </button>
                                        </form>
                                        <form action="{{ route('eventos.cambiar-estado', $evento) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="completado">
                                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-50 dark:bg-gray-700 text-purple-700 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                Completado
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Botón de Inscripción (Solo para usuarios normales cuando está abierto) -->
                        @auth
                            @if(!auth()->user()->isAdmin())
                                @if($estaInscrito)
                                    <button class="px-6 py-2 bg-green-600 text-white rounded-lg font-semibold flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Registrado
                                    </button>
                                @else
                                    @if($evento->estaAbierto())
                                        <form action="{{ route('eventos.register', $evento) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold">
                                                Registrarse al Evento
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna Principal (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Descripción del Evento -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Descripción del Evento</h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $evento->descripcion }}</p>
                    </div>

                    <!-- Premios REALES con SVG -->
                    @if($evento->premios->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Premios
                            </h3>
                            <div class="space-y-3">
                                @foreach($evento->premios as $premio)
                                    <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                                        <!-- Ícono SVG según el orden -->
                                        @if($premio->orden == 1)
                                            <!-- Medalla de Oro -->
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L9 9H2l6 4.5L5.5 22 12 17l6.5 5-2.5-8.5L22 9h-7l-3-7z"/>
                                                </svg>
                                            </div>
                                        @elseif($premio->orden == 2)
                                            <!-- Medalla de Plata -->
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L9 9H2l6 4.5L5.5 22 12 17l6.5 5-2.5-8.5L22 9h-7l-3-7z"/>
                                                </svg>
                                            </div>
                                        @elseif($premio->orden == 3)
                                            <!-- Medalla de Bronce -->
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2L9 9H2l6 4.5L5.5 22 12 17l6.5 5-2.5-8.5L22 9h-7l-3-7z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <!-- Trofeo genérico -->
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <span class="font-bold text-gray-900">{{ $premio->lugar }}:</span>
                                            <span class="text-gray-700 dark:text-gray-300 ml-2">{{ $premio->descripcion }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Requisitos -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                            </svg>
                            Requisitos
                        </h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Estudiante Activo</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Conocimientos Básicos de Programación</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Laptop Personal</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Equipo de {{ $evento->min_miembros_equipo }} a {{ $evento->max_miembros_equipo }} miembros</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Equipos Participantes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                Equipos Participantes ({{ $evento->equipos->count() }})
                            </span>
                        </h3>

                        @forelse($evento->equipos as $equipo)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-3 hover:border-indigo-300 transition">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900">{{ $equipo->nombre }}</h4>
                                        <p class="text-sm text-gray-600">{{ $equipo->descripcion }}</p>
                                    </div>
                                    <a href="{{ route('equipos.show', $equipo) }}" 
                                       class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium">
                                        Ver Equipo
                                    </a>
                                </div>

                                <!-- Miembros Actuales -->
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-700">Miembros: {{ $equipo->totalMiembros() }}/{{ $equipo->max_miembros }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Aún no hay equipos registrados</p>
                                @auth
                                    @if($estaInscrito)
                                        <a href="{{ route('equipos.create', $evento) }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                                            Sé el primero en crear un equipo
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endforelse
                    </div>

                </div>

                <!-- Columna Lateral (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Cronograma -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Cronograma
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-indigo-600 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Registro</p>
                                    <p class="text-sm text-gray-600">Hasta {{ $evento->fecha_limite_registro->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-indigo-600 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Evento</p>
                                    <p class="text-sm text-gray-600">{{ $evento->fecha_inicio->format('d M') }} - {{ $evento->fecha_fin->format('d M Y') }}</p>
                                </div>
                            </div>
                            @if($evento->fecha_evaluacion)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Evaluación</p>
                                        <p class="text-sm text-gray-600">{{ $evento->fecha_evaluacion->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($evento->fecha_premiacion)
                                <div class="flex items-start gap-3">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">Premiación</p>
                                        <p class="text-sm text-gray-600">{{ $evento->fecha_premiacion->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones -->
                    @if(!auth()->check() || !auth()->user()->isAdmin())
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Acciones</h3>
                            <div class="space-y-3">
                                @auth
                                    @if($estaInscrito)
                                        <a href="{{ route('equipos.create', $evento) }}" 
                                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Crear Nuevo Equipo
                                        </a>

                                        <a href="{{ route('equipos.index', $evento) }}" 
                                           class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 rounded-lg font-medium">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                            </svg>
                                            Explorar Equipos
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endif

                    <!-- Información del Evento -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            Información del Evento
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Participantes</span>
                                <span class="font-bold text-gray-900">{{ $evento->totalParticipantes() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Equipos</span>
                                <span class="font-bold text-gray-900">{{ $evento->totalEquipos() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tamaño de Equipo</span>
                                <span class="font-bold text-gray-900">{{ $evento->min_miembros_equipo }}-{{ $evento->max_miembros_equipo }} Miembros</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Tipo</span>
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                                    {{ $evento->tipoTexto }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    @endpush
</x-app-layout>