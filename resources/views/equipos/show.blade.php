<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
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
            
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $equipo->nombre }}</h1>
                            
                            <!-- Botón Editar Equipo (Solo Líder) -->
                            @if($esLider)
                                <button onclick="toggleModalEditarEquipo()" 
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition"
                                        title="Editar equipo">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Editar Equipo
                                </button>
                            @endif
                        </div>
                        <p class="text-gray-600 mb-2">
                            <a href="{{ route('eventos.show', $equipo->evento) }}" class="hover:text-indigo-600">
                                {{ $equipo->evento->nombre }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">Líder: {{ $equipo->lider->user->name }} • {{ $equipo->totalMiembros() }}/{{ $equipo->max_miembros }} miembros</p>
                        
                        @if($equipo->descripcion)
                            <p class="text-gray-600 mt-3">{{ $equipo->descripcion }}</p>
                        @endif

                        <!-- Enlaces del Proyecto (Solo para miembros, jueces y admin) -->
                        @if($equipo->proyecto && ($esMiembro || auth()->user()->tieneRol('juez') || auth()->user()->tieneRol('admin')))
                            <div class="mt-4 flex flex-wrap gap-3">
                                @if($equipo->proyecto->link_repositorio)
                                    <a href="{{ $equipo->proyecto->link_repositorio }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                        </svg>
                                        Ver Repositorio
                                    </a>
                                @endif

                                @if($equipo->proyecto->link_demo)
                                    <a href="{{ $equipo->proyecto->link_demo }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                        </svg>
                                        Ver Demo
                                    </a>
                                @endif

                                @if($equipo->proyecto->link_presentacion)
                                    <a href="{{ $equipo->proyecto->link_presentacion }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm11-1a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1V8a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                                        </svg>
                                        Ver Presentación
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <!-- Acciones según rol del usuario -->
                    <div class="flex gap-2">
                        @if($esMiembro && !$esLider)
                            <!-- Abandonar equipo (solo miembros no líderes) -->
                            <form method="POST" action="{{ route('equipos.abandonar', $equipo) }}" 
                                  onsubmit="return confirm('¿Estás seguro de abandonar este equipo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4a1 1 0 10-2 0v6a1 1 0 102 0V7zm-3 0a1 1 0 10-2 0v6a1 1 0 102 0V7zm-3 0a1 1 0 10-2 0v6a1 1 0 102 0V7z" clip-rule="evenodd"/>
                                    </svg>
                                    Abandonar Equipo
                                </button>
                            </form>
                        @elseif(!$esMiembro && $equipo->puedeAceptarMiembros() && $equipo->evento->estaAbierto())
                            <!-- Solicitar unirse (solo si NO es miembro y hay cupo) -->
                            <button onclick="toggleModalUnirse()" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                                Solicitar Unirse
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- NUEVO: Progress Bar del Proyecto -->
            @if($equipo->proyecto && $esMiembro)
                @php
                    $proyecto = $equipo->proyecto;
                    $proyecto->actualizarPorcentaje(); // Actualizar porcentaje en tiempo real
                @endphp

                <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border-l-4 border-{{ $proyecto->estadoColor }}-500">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $proyecto->nombre }}</h2>
                                <span class="px-4 py-1.5 bg-{{ $proyecto->estadoColor }}-100 text-{{ $proyecto->estadoColor }}-700 rounded-full text-sm font-bold">
                                    {{ $proyecto->estadoTexto }}
                                </span>
                                
                                <!-- Botón Editar Proyecto (Solo Líder y si no está entregado) -->
                                @if($esLider && !in_array($proyecto->estado, ['entregado', 'listo_para_evaluar', 'evaluado', 'finalizado']))
                                    <a href="{{ route('proyectos.edit', $equipo) }}" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition"
                                       title="Editar proyecto">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Editar Proyecto
                                    </a>
                                @endif
                            </div>
                            <p class="text-gray-600">{{ $proyecto->descripcion }}</p>
                        </div>
                    </div>

                    <!-- Barra de Progreso Principal -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-gray-700">Completitud del Proyecto</span>
                            <span class="text-3xl font-bold text-{{ $proyecto->porcentaje_completado == 100 ? 'green' : 'indigo' }}-600">
                                {{ $proyecto->porcentaje_completado }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div class="h-4 rounded-full transition-all duration-500 
                                {{ $proyecto->porcentaje_completado == 100 ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-indigo-500 to-purple-600' }}" 
                                style="width: {{ $proyecto->porcentaje_completado }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $proyecto->porcentaje_completado == 100 ? '¡Proyecto completo!' : 'Sigue trabajando para completar el proyecto' }}
                        </p>
                    </div>

                    <!-- Checklist de Requisitos -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            Requisitos para Entregar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <!-- Nombre -->
                            <div class="flex items-center gap-2">
                                @if($proyecto->nombre && strlen($proyecto->nombre) >= 5)
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm">Nombre del proyecto (5+ caracteres)</span>
                            </div>

                            <!-- Descripción -->
                            <div class="flex items-center gap-2">
                                @if($proyecto->descripcion && strlen($proyecto->descripcion) >= 50)
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm">Descripción (50+ caracteres)</span>
                            </div>

                            <!-- Repositorio -->
                            @if($proyecto->evento->requiere_repositorio)
                            <div class="flex items-center gap-2">
                                @if($proyecto->link_repositorio)
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm">Link del repositorio</span>
                            </div>
                            @endif

                            <!-- Demo -->
                            @if($proyecto->evento->requiere_demo)
                            <div class="flex items-center gap-2">
                                @if($proyecto->link_demo)
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm">Link de la demo</span>
                            </div>
                            @endif

                            <!-- Presentación -->
                            @if($proyecto->evento->requiere_presentacion)
                            <div class="flex items-center gap-2">
                                @if($proyecto->link_presentacion)
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm">Link de la presentación</span>
                            </div>
                            @endif

                            <!-- Tareas -->
                            @php
                                $totalTareas = $proyecto->tareas()->count();
                                $tareasCompletadas = $proyecto->tareas()->where('estado', 'completada')->count();
                                $tareasOk = $totalTareas >= $proyecto->evento->min_tareas_proyecto && $totalTareas > 0 && $totalTareas === $tareasCompletadas;
                            @endphp
                            <div class="flex items-center gap-2 md:col-span-2">
                                @if($tareasOk)
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="text-sm">
                                    Tareas: {{ $tareasCompletadas }}/{{ $totalTareas }} completadas 
                                    (mínimo {{ $proyecto->evento->min_tareas_proyecto }})
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Entrega Final o Estado -->
                    @if($proyecto->estado === 'entregado')
                        <div class="bg-purple-50 border-2 border-purple-500 rounded-xl p-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-purple-700 font-bold mb-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Proyecto Entregado
                            </div>
                            <p class="text-sm text-purple-600">
                                Entregado el {{ $proyecto->fecha_entrega->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm text-purple-600 mt-1">
                                Esperando aprobación del administrador para evaluación
                            </p>
                        </div>
                    @elseif($proyecto->estado === 'listo_para_evaluar')
                        <div class="bg-green-50 border-2 border-green-500 rounded-xl p-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-green-700 font-bold mb-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Proyecto Aprobado - Listo para Evaluar
                            </div>
                            <p class="text-sm text-green-600">
                                Tu proyecto fue aprobado y está listo para ser evaluado por los jueces
                            </p>
                        </div>
                    @elseif($proyecto->estado === 'evaluado')
                        <div class="bg-indigo-50 border-2 border-indigo-500 rounded-xl p-4 text-center">
                            <div class="flex items-center justify-center gap-2 text-indigo-700 font-bold mb-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Proyecto Evaluado
                            </div>
                            <p class="text-sm text-indigo-600">
                                Tu proyecto ya fue evaluado. Pronto conocerán los resultados
                            </p>
                        </div>
                    @elseif($proyecto->cumpleRequisitosMinimos() && in_array($proyecto->estado, ['borrador', 'pendiente_revision', 'en_progreso']))
                        <form action="{{ route('proyectos.entregar', $proyecto) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de realizar la entrega final? Una vez entregado, no podrás hacer más cambios hasta que sea revisado.')">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Realizar Entrega Final
                            </button>
                        </form>
                        <p class="text-center text-sm text-gray-600 mt-2">
                            Al entregar, tu proyecto será revisado por el administrador antes de ser evaluado
                        </p>
                    @else
                        <div class="bg-yellow-50 border-2 border-yellow-400 rounded-xl p-4">
                            <p class="text-yellow-800 font-bold mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Faltan requisitos para entregar:
                            </p>
                            <ul class="text-sm text-yellow-700 space-y-1 ml-7">
                                @foreach($proyecto->requisitosFaltantes() as $faltante)
                                    <li>• {{ $faltante }}</li>
                                @endforeach
                            </ul>
                            <p class="text-xs text-yellow-600 mt-3">
                                Completa todos los requisitos para poder realizar la entrega final
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna Izquierda (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Miembros del Equipo -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                Miembros del Equipo
                            </h3>
                            <span class="text-sm text-gray-500">{{ $equipo->totalMiembros() }}/{{ $equipo->max_miembros }}</span>
                        </div>

                        <div class="space-y-3">
                            @foreach($equipo->miembrosActivos()->get() as $miembro)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($miembro->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-900">{{ $miembro->user->name }}</span>
                                                @if($equipo->lider_id == $miembro->id)
                                                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">LÍDER</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-600">{{ $miembro->carrera->nombre }}</div>
                                            <div class="text-sm text-indigo-600 font-medium">{{ $miembro->pivot->perfil->nombre ?? 'Sin perfil' }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">Se unió: {{ $miembro->pivot->created_at->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Roles Disponibles -->
                        @if($equipo->puedeAceptarMiembros())
                            <div class="mt-4 pt-4 border-t">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Roles Disponibles</h4>
                                <div class="text-sm text-gray-600">
                                    Quedan {{ $equipo->max_miembros - $equipo->totalMiembros() }} espacios disponibles
                                </div>
                            </div>
                        @else
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-sm text-gray-500">Equipo completo</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tareas del Proyecto -->
                    @if($equipo->proyecto)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Tareas del Proyecto
                                </h3>
                                @if($esLider)
                                    <button onclick="toggleModalCrearTarea()" 
                                            class="text-sm text-indigo-600 hover:text-indigo-700 flex items-center gap-1 font-medium">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Nueva Tarea
                                    </button>
                                @endif
                            </div>

                            @php
                                $tareas = $equipo->proyecto->tareas()->with('participantes.user')->orderBy('orden')->get();
                                $totalTareas = $tareas->count();
                                $tareasCompletadas = $tareas->where('estado', 'completada')->count();
                                $progreso = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100) : 0;
                            @endphp

                            @if($tareas->count() > 0)
                                <div class="space-y-3">
                                    @foreach($tareas as $tarea)
                                        <div class="border rounded-lg p-4 @if($tarea->estaCompletada()) bg-green-50 border-green-200 @else bg-white @endif">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-start gap-3 flex-1">
                                                    <!-- Checkbox para marcar como completada -->
                                                    @if($esMiembro)
                                                        <form method="POST" action="{{ route('equipos.tareas.toggle', [$equipo, $tarea]) }}">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="mt-1 w-6 h-6 rounded flex items-center justify-center border-2 transition
                                                                    @if($tarea->estaCompletada()) 
                                                                        bg-green-500 border-green-500 text-white 
                                                                    @else 
                                                                        bg-white border-gray-300 hover:border-indigo-500
                                                                    @endif">
                                                                @if($tarea->estaCompletada())
                                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                @endif
                                                            </button>
                                                        </form>
                                                    @else
                                                        <div class="mt-1 w-6 h-6 rounded flex items-center justify-center border-2
                                                            @if($tarea->estaCompletada()) 
                                                                bg-green-500 border-green-500 text-white 
                                                            @else 
                                                                bg-white border-gray-300
                                                            @endif">
                                                            @if($tarea->estaCompletada())
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    <div class="flex-1">
                                                        <div class="flex items-start justify-between">
                                                            <div class="flex-1">
                                                                <h4 class="font-semibold text-gray-900 @if($tarea->estaCompletada()) line-through @endif">
                                                                    {{ $tarea->nombre }}
                                                                </h4>
                                                                @if($tarea->descripcion)
                                                                    <p class="text-sm text-gray-600 mt-1">{{ $tarea->descripcion }}</p>
                                                                @endif
                                                                
                                                                <!-- Asignados -->
                                                                <div class="flex items-center gap-2 mt-2">
                                                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                                    </svg>
                                                                    @if($tarea->participantes->count() > 0)
                                                                        <div class="flex gap-1">
                                                                            @foreach($tarea->participantes as $asignado)
                                                                                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-xs">
                                                                                    {{ explode(' ', $asignado->user->name)[0] }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="text-xs text-gray-400">Sin asignar</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Valor de la tarea -->
                                                            <div class="text-right ml-4">
                                                                <span class="text-sm font-semibold text-indigo-600">
                                                                    {{ $tarea->valorPorcentual() }}%
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Acciones del líder -->
                                                @if($esLider)
                                                    <div class="flex gap-2 ml-4">
                                                        <button onclick="abrirModalEditarTarea({{ json_encode($tarea) }})" 
                                                                class="text-indigo-600 hover:text-indigo-700">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </button>
                                                        <form method="POST" action="{{ route('equipos.tareas.destroy', [$equipo, $tarea]) }}" 
                                                              onsubmit="return confirm('¿Eliminar esta tarea?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-700">
                                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Sin tareas -->
                                <div class="text-center py-8 bg-gray-50 rounded-lg">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-gray-600 font-medium">No hay tareas registradas</p>
                                    @if($esLider)
                                        <p class="text-sm text-gray-500 mt-1">Crea la primera tarea para organizar el trabajo del equipo</p>
                                        <button onclick="toggleModalCrearTarea()" 
                                                class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 inline-flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Crear Primera Tarea
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif($esMiembro)
                        <!-- Si no hay proyecto y es miembro, mostrar botón para crear -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Proyecto</h3>
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600 mb-4">Aún no han registrado su proyecto</p>
                                <p class="text-sm text-gray-500 mb-4">Cualquier miembro del equipo puede registrarlo</p>
                                <a href="{{ route('proyectos.create', $equipo) }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Registrar Proyecto
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Columna Derecha (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Estadísticas del Proyecto -->
                    @if($equipo->proyecto)
                        <div class="bg-white rounded-xl shadow-sm p-4">
                            <h3 class="font-bold mb-3">Progreso del Proyecto</h3>
                            
                            @php
                                $totalTareas = $equipo->proyecto->tareas()->count();
                                $tareasCompletadas = $equipo->proyecto->tareas()->where('estado', 'completada')->count();
                                $progreso = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100) : 0;
                            @endphp

                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Progreso General</span>
                                        <span class="font-semibold text-indigo-600">{{ $progreso }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" 
                                             style="width: {{ $progreso }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tareas Completadas</span>
                                        <span class="font-semibold">{{ $tareasCompletadas }}/{{ $totalTareas }}</span>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tareas Pendientes</span>
                                        <span class="font-semibold text-orange-600">{{ $totalTareas - $tareasCompletadas }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Chat del Equipo (SOLO PARA MIEMBROS) -->
                    @if($esMiembro)
                        <div class="bg-white rounded-xl shadow-sm">
                            <div class="p-4 border-b">
                                <h3 class="font-bold flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                    </svg>
                                    Chat del Equipo
                                </h3>
                            </div>
                            
                            <div class="h-64 overflow-y-auto p-4 space-y-3" id="chat-messages">
                                @php
                                    $mensajes = $equipo->mensajes()->with('participante.user')->latest()->take(20)->get()->reverse();
                                @endphp

                                @forelse($mensajes as $mensaje)
                                    <div class="flex gap-2">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                            {{ substr($mensaje->participante->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-xs font-semibold">{{ explode(' ', $mensaje->participante->user->name)[0] }}</div>
                                            <div class="text-sm text-gray-700">{{ $mensaje->mensaje }}</div>
                                            <div class="text-xs text-gray-400">{{ $mensaje->created_at->format('g:i A') }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-gray-400 py-8">
                                        <p class="text-sm">No hay mensajes aún</p>
                                        <p class="text-xs">Sé el primero en enviar un mensaje</p>
                                    </div>
                                @endforelse
                            </div>

                            <form method="POST" action="{{ route('equipos.enviar-mensaje', $equipo) }}" class="p-4 border-t">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" 
                                           name="mensaje" 
                                           placeholder="Escribe un mensaje..." 
                                           required
                                           class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Mensaje para no miembros -->
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Chat del Equipo
                            </h3>
                            <div class="text-center py-8 text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <p class="text-sm">Solo los miembros del equipo pueden ver el chat</p>
                            </div>
                        </div>
                    @endif

                    <!-- Invitaciones Pendientes (SOLO PARA LÍDER) -->
                    @if($esLider)
                        @php
                            $pendientes = $equipo->participantes()->wherePivot('estado', 'pendiente')->get();
                        @endphp

                        @if($pendientes->count() > 0)
                            <div class="bg-white rounded-xl shadow-sm p-4">
                                <h3 class="font-bold mb-3">Invitaciones Pendientes</h3>
                                <div class="space-y-3">
                                    @foreach($pendientes as $solicitante)
                                        <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ substr($solicitante->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="font-semibold text-sm">{{ $solicitante->user->name }}</div>
                                                    <div class="text-xs text-gray-600">{{ $solicitante->pivot->perfil->nombre ?? 'N/A' }}</div>
                                                </div>
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">Pendiente</span>
                                            </div>
                                            <div class="flex gap-2 mt-2">
                                                <form method="POST" action="{{ route('equipos.aceptar-miembro', [$equipo, $solicitante->id]) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-1.5 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700">
                                                        Aceptar
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('equipos.rechazar-miembro', [$equipo, $solicitante->id]) }}" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-1.5 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700">
                                                        Rechazar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            </div>

        </div>
    </div>

    <!-- Modal Solicitar Unirse -->
    <div id="modalUnirse" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Solicitar Unirse al Equipo</h3>
            
            <form method="POST" action="{{ route('equipos.solicitar', $equipo) }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona tu rol en el equipo</label>
                    <select name="perfil_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Selecciona un rol --</option>
                        @foreach(\App\Models\Perfil::all() as $perfil)
                            <option value="{{ $perfil->id }}">{{ $perfil->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">¿Por qué quieres unirte?</label>
                    <textarea rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                              placeholder="Opcional: Cuéntale al líder por qué eres un buen candidato..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="toggleModalUnirse()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Crear Tarea -->
    <div id="modalCrearTarea" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Crear Nueva Tarea</h3>
            
            <form method="POST" action="{{ route('equipos.tareas.store', $equipo) }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Tarea *</label>
                    <input type="text" 
                           name="nombre" 
                           required 
                           maxlength="200"
                           placeholder="Ej: Diseñar interfaz de usuario"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" 
                              rows="3" 
                              maxlength="1000"
                              placeholder="Detalles de la tarea..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asignar a (máximo 2 personas)</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border rounded-lg p-3">
                        @foreach($equipo->participantes as $miembro)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       name="participantes[]" 
                                       value="{{ $miembro->id }}"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($miembro->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm">{{ $miembro->user->name }}</span>
                                    @if($equipo->lider_id == $miembro->id)
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs">Líder</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Puedes seleccionar hasta 2 participantes</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="toggleModalCrearTarea()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Crear Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Tarea -->
    <div id="modalEditarTarea" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Editar Tarea</h3>
            
            <form id="formEditarTarea" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Tarea *</label>
                    <input type="text" 
                           id="edit_nombre"
                           name="nombre" 
                           required 
                           maxlength="200"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="edit_descripcion"
                              name="descripcion" 
                              rows="3" 
                              maxlength="1000"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asignar a (máximo 2 personas)</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border rounded-lg p-3">
                        @foreach($equipo->participantes as $miembro)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       name="participantes[]" 
                                       value="{{ $miembro->id }}"
                                       class="edit-participante rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($miembro->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm">{{ $miembro->user->name }}</span>
                                    @if($equipo->lider_id == $miembro->id)
                                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs">Líder</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="toggleModalEditarTarea()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Equipo -->
    <div id="modalEditarEquipo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Editar Información del Equipo</h3>
            
            <form method="POST" action="{{ route('equipos.update', $equipo) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Equipo *</label>
                    <input type="text" 
                           name="nombre" 
                           value="{{ $equipo->nombre }}"
                           required 
                           maxlength="100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del Equipo</label>
                    <textarea name="descripcion" 
                              rows="3" 
                              maxlength="500"
                              placeholder="Describe tu equipo y sus objetivos..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ $equipo->descripcion }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="toggleModalEditarEquipo()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Modal Unirse
        function toggleModalUnirse() {
            document.getElementById('modalUnirse').classList.toggle('hidden');
        }

        document.getElementById('modalUnirse')?.addEventListener('click', function(e) {
            if (e.target === this) toggleModalUnirse();
        });

        // Modal Crear Tarea
        function toggleModalCrearTarea() {
            document.getElementById('modalCrearTarea').classList.toggle('hidden');
        }

        document.getElementById('modalCrearTarea')?.addEventListener('click', function(e) {
            if (e.target === this) toggleModalCrearTarea();
        });

        // Limitar selección a 2 participantes
        document.querySelectorAll('input[name="participantes[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checked = document.querySelectorAll('input[name="participantes[]"]:checked');
                if (checked.length > 2) {
                    this.checked = false;
                    alert('Solo puedes asignar máximo 2 participantes por tarea');
                }
            });
        });

        // Modal Editar Tarea
        function toggleModalEditarTarea() {
            document.getElementById('modalEditarTarea').classList.toggle('hidden');
        }

        document.getElementById('modalEditarTarea')?.addEventListener('click', function(e) {
            if (e.target === this) toggleModalEditarTarea();
        });

        function abrirModalEditarTarea(tarea) {
            // Actualizar action del form
            const form = document.getElementById('formEditarTarea');
            form.action = `/equipos/{{ $equipo->id }}/tareas/${tarea.id}`;
            
            // Llenar datos
            document.getElementById('edit_nombre').value = tarea.nombre;
            document.getElementById('edit_descripcion').value = tarea.descripcion || '';
            
            // Marcar participantes asignados
            document.querySelectorAll('.edit-participante').forEach(checkbox => {
                checkbox.checked = false;
                if (tarea.participantes) {
                    tarea.participantes.forEach(p => {
                        if (parseInt(checkbox.value) === p.id) {
                            checkbox.checked = true;
                        }
                    });
                }
            });
            
            // Mostrar modal
            toggleModalEditarTarea();
        }

        // Limitar selección en editar también
        document.querySelectorAll('.edit-participante').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checked = document.querySelectorAll('.edit-participante:checked');
                if (checked.length > 2) {
                    this.checked = false;
                    alert('Solo puedes asignar máximo 2 participantes por tarea');
                }
            });
        });

        // Modal Editar Equipo
        function toggleModalEditarEquipo() {
            document.getElementById('modalEditarEquipo').classList.toggle('hidden');
        }

        document.getElementById('modalEditarEquipo')?.addEventListener('click', function(e) {
            if (e.target === this) toggleModalEditarEquipo();
        });
    </script>
    @endpush
</x-app-layout>
