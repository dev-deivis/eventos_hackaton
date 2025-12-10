<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Mis Equipos</h2>
                <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Selecciona un equipo para ver su progreso</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($misEquipos as $equipo)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <!-- Header del Card -->
                        <div class="p-6 border-b">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $equipo->nombre }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">{{ $equipo->evento->nombre }}</p>
                                </div>
                                @if($equipo->esLider(auth()->user()))
                                    <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 rounded text-xs font-medium">
                                        LÍDER
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 rounded text-xs font-medium">
                                        MIEMBRO
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Descripción -->
                            @if($equipo->descripcion)
                                <p class="text-sm text-gray-500 dark:text-gray-500 line-clamp-2">{{ $equipo->descripcion }}</p>
                            @endif
                        </div>

                        <!-- Estadísticas -->
                        <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 dark:text-gray-500 mb-1">Miembros</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $equipo->totalMiembros() }}/{{ $equipo->max_miembros }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 dark:text-gray-500 mb-1">Progreso</p>
                                    @php
                                        $totalTareas = $equipo->proyecto?->tareas->count() ?? 0;
                                        $tareasCompletadas = $equipo->proyecto?->tareas->where('estado', 'completada')->count() ?? 0;
                                        $progreso = $totalTareas > 0 ? round(($tareasCompletadas / $totalTareas) * 100) : 0;
                                    @endphp
                                    <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">{{ $progreso }}%</p>
                                </div>
                            </div>

                            <!-- Barra de Progreso -->
                            <div class="mb-4">
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                    <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $progreso }}%"></div>
                                </div>
                            </div>

                            <!-- Estado del Proyecto -->
                            <div class="flex items-center justify-between mb-4">
                                @if($equipo->proyecto)
                                    <span class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Proyecto registrado
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Sin proyecto
                                    </span>
                                @endif

                                <span class="text-xs text-gray-500 dark:text-gray-500">
                                    {{ $totalTareas }} tareas
                                </span>
                            </div>

                            <!-- Botón Ver Equipo -->
                            <a href="{{ route('equipos.show', $equipo) }}" 
                               class="block w-full text-center bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                Ver Equipo
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No tienes equipos</h3>
                            <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500 mb-6">Aún no formas parte de ningún equipo. Explora eventos y únete a uno.</p>
                            <a href="{{ route('eventos.index') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 font-medium">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Explorar Eventos
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
