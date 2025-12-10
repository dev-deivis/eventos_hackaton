<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mis Evaluaciones</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Historial completo de evaluaciones realizadas</p>
            </div>

            <!-- Cards de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Evaluaciones -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Evaluaciones</h3>
                        <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ $totalEvaluaciones }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Evaluaciones completadas</p>
                    </div>
                </div>

                <!-- Puntuación Promedio -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Puntuación Promedio</h3>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ number_format($promedioCalificacion, 0) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Promedio otorgado</p>
                    </div>
                </div>

                <!-- Última Evaluación -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Última Evaluación</h3>
                        <div class="bg-indigo-100 dark:bg-indigo-900/30 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-pink-600 dark:text-pink-400">{{ $ultimaEvaluacion ? number_format($ultimaEvaluacion->calificacion_total, 0) : '-' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $ultimaEvaluacion ? $ultimaEvaluacion->fecha_evaluacion->format('d/m/Y, H:i') : 'Sin evaluaciones' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Historial de Evaluaciones -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Historial de Evaluaciones</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Todas las evaluaciones realizadas ordenadas por fecha</p>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($evaluaciones as $evaluacion)
                        <!-- Evaluación Item -->
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $evaluacion->equipo->nombre }}</h3>
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 rounded-full text-xs font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Completada
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $evaluacion->equipo->proyecto->nombre ?? 'Sin nombre de proyecto' }}</p>
                                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        Evaluado el {{ $evaluacion->fecha_evaluacion->format('d/m/Y, H:i') }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900">{{ number_format($evaluacion->calificacion_total, 0) }}</div>
                                    <div class="text-xs text-gray-500">Puntuación Final</div>
                                </div>
                            </div>

                            <!-- Criterios de Evaluación -->
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4">
                                <!-- Implementación Técnica -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($evaluacion->implementacion, 0) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Técnico</div>
                                </div>

                                <!-- Innovación -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ number_format($evaluacion->innovacion, 0) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Innovación</div>
                                </div>

                                <!-- Presentación -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($evaluacion->presentacion, 0) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Presentación</div>
                                </div>

                                <!-- Trabajo en Equipo -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($evaluacion->trabajo_equipo, 0) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Equipo</div>
                                </div>

                                <!-- Viabilidad -->
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($evaluacion->viabilidad, 0) }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Negocio</div>
                                </div>
                            </div>

                            <!-- Comentarios -->
                            @if($evaluacion->comentarios)
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-1">Comentarios</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $evaluacion->comentarios }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <!-- Estado Vacío -->
                        <div class="p-12 text-center">
                            <div class="bg-gray-100 dark:bg-gray-700 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">No hay evaluaciones aún</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Comienza evaluando equipos asignados desde tu dashboard</p>
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
                @if($evaluaciones->hasPages())
                    <div class="p-6 border-t border-gray-100">
                        {{ $evaluaciones->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
