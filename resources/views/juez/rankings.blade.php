<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Rankings de Equipos</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Clasificación actual basada en evaluaciones completadas</p>
            </div>

            <!-- Cards de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Equipos Evaluados -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Equipos Evaluados</h3>
                        <div class="bg-indigo-100 dark:bg-indigo-900/30 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ $equiposEvaluados }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">De {{ $totalEquipos }} equipos totales</p>
                    </div>
                </div>

                <!-- Puntuación Promedio -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Puntuación Promedio</h3>
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ number_format($promedioGeneral, 0) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Puntos promedio</p>
                    </div>
                </div>

                <!-- Mejor Puntuación -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Mejor Puntuación</h3>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ $mejorPuntuacion ? number_format($mejorPuntuacion->calificacion_promedio, 0) : '-' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $mejorPuntuacion ? $mejorPuntuacion->nombre : 'Sin datos' }}</p>
                    </div>
                </div>
            </div>

            <!-- Clasificación General -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Clasificación General</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Rankings actualizados en tiempo real basados en las evaluaciones de todos los jueces</p>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($equipos as $index => $equipo)
                        @php
                            $posicion = ($equipos->currentPage() - 1) * $equipos->perPage() + $index + 1;
                            $badgeColors = [
                                1 => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-700 dark:text-yellow-300', 'icon' => '🥇', 'label' => '1er Lugar'],
                                2 => ['bg' => 'bg-gray-200 dark:bg-gray-600', 'text' => 'text-gray-700 dark:text-gray-300 dark:text-gray-600', 'icon' => '🥈', 'label' => '2do Lugar'],
                                3 => ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-700 dark:text-orange-300', 'icon' => '🥉', 'label' => '3er Lugar'],
                            ];
                            $badge = $badgeColors[$posicion] ?? null;
                        @endphp

                        <!-- Item de Equipo -->
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-start gap-6">
                                <!-- Posición -->
                                <div class="flex-shrink-0 w-12 text-center">
                                    @if($badge)
                                        <div class="w-12 h-12 {{ $badge['bg'] }} rounded-full flex items-center justify-center">
                                            <span class="text-2xl">{{ $badge['icon'] }}</span>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <span class="text-xl font-bold text-gray-600 dark:text-gray-400">{{ $posicion }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Información del Equipo -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-xl font-bold text-gray-900">{{ $equipo->nombre }}</h3>
                                                @if($badge)
                                                    <span class="px-3 py-1 {{ $badge['bg'] }} {{ $badge['text'] }} rounded-full text-xs font-bold">
                                                        {{ $badge['label'] }}
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-xs font-medium">
                                                        {{ $posicion }}° Lugar
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $equipo->proyecto->nombre ?? 'Sin proyecto' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $equipo->evento->nombre }}</p>
                                        </div>
                                        <div class="text-right ml-6">
                                            <div class="text-4xl font-bold text-gray-900">{{ number_format($equipo->calificacion_promedio, 0) }}</div>
                                            <div class="text-xs text-gray-500">Puntuación Final</div>
                                        </div>
                                    </div>

                                    <!-- Detalles del Equipo -->
                                    <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400 mt-3">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                            </svg>
                                            <span>{{ $equipo->participantes_count ?? $equipo->participantes->count() }} miembros</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="font-medium">Técnico:</span>
                                            <span class="text-purple-600 dark:text-purple-400 font-bold">{{ number_format($equipo->implementacion_promedio, 0) }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="font-medium">Innovación:</span>
                                            <span class="text-blue-600 font-bold">{{ number_format($equipo->innovacion_promedio, 0) }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="font-medium">Presentación:</span>
                                            <span class="text-green-600 font-bold">{{ number_format($equipo->presentacion_promedio, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Estado Vacío -->
                        <div class="p-12 text-center">
                            <div class="bg-gray-100 dark:bg-gray-700 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">No hay equipos evaluados aún</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Los rankings se generarán automáticamente cuando se completen las evaluaciones</p>
                            <a href="{{ route('juez.dashboard') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Ir al Dashboard
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                @if($equipos->hasPages())
                    <div class="p-6 border-t border-gray-100">
                        {{ $equipos->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
