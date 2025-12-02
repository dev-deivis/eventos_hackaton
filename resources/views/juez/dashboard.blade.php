<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Panel de Juez</h1>
                <p class="text-gray-600 mt-1">Bienvenido Dr. {{ auth()->user()->name }}, evalúa proyectos y realiza seguimiento de equipos</p>
            </div>

            <!-- Estadísticas Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Equipos Asignados -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Equipos Asignados</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-purple-600">{{ $totalAsignados }}</p>
                            <p class="text-xs text-gray-500 mt-1">Para evaluar</p>
                        </div>
                    </div>
                </div>

                <!-- Evaluaciones Completadas -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Evaluaciones Completadas</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-indigo-600">{{ $evaluacionesCompletadas }}</p>
                            <p class="text-xs text-gray-500 mt-1">De {{ $totalAsignados }} asignadas</p>
                        </div>
                    </div>
                </div>

                <!-- Promedio de Calificación -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Promedio de Calificación</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-pink-600">{{ number_format($promedioCalificacion, 1) }}</p>
                            <p class="text-xs text-gray-500 mt-1">Puntuación promedio</p>
                        </div>
                    </div>
                </div>

                <!-- Tiempo Promedio -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Tiempo Promedio</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-4xl font-bold text-purple-600">{{ $tiempoPromedio }}</p>
                            <p class="text-xs text-gray-500 mt-1">Minutos por evaluación</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Izquierda (1/3) - Acciones -->
                <div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones de Evaluación</h3>
                        
                        <div class="space-y-3">
                            @if($equiposPendientes->count() > 0)
                                <a href="{{ route('juez.evaluar', $equiposPendientes->first()) }}" 
                                   class="flex items-center gap-3 p-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition group w-full">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-semibold">Evaluar Equipo</span>
                                </a>
                            @else
                                <div class="flex items-center gap-3 p-4 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-semibold">Sin equipos pendientes</span>
                                </div>
                            @endif

                            <a href="{{ route('juez.rankings') }}" 
                               class="flex items-center gap-3 p-4 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Ver Rankings</span>
                            </a>

                            <a href="{{ route('juez.mis-evaluaciones') }}" 
                               class="w-full flex items-center gap-3 p-4 bg-white hover:bg-gray-50 border-2 border-gray-200 rounded-lg transition text-left">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-gray-700">Mis Evaluaciones</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha (2/3) - Equipos Pendientes -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                Equipos Pendientes
                            </h3>
                        </div>

                        <div class="space-y-4">
                            @forelse($equiposPendientes as $equipo)
                                @php
                                    $proyecto = $equipo->proyecto;
                                    $puedeEvaluar = $proyecto && $proyecto->estaListoParaEvaluar();
                                @endphp
                                
                                <!-- Equipo {{ $loop->iteration }} -->
                                <div class="border-l-4 border-{{ $proyecto && $proyecto->estadoColor ? $proyecto->estadoColor : 'gray' }}-500 bg-white rounded-lg p-5 hover:shadow-md transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="font-bold text-gray-900 text-lg">{{ $equipo->nombre }}</h4>
                                                
                                                @if($proyecto)
                                                    <span class="px-3 py-1 bg-{{ $proyecto->estadoColor }}-100 text-{{ $proyecto->estadoColor }}-700 rounded-full text-xs font-bold">
                                                        {{ $proyecto->estadoTexto }}
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">
                                                        Sin Proyecto
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <p class="text-sm text-gray-600">{{ $equipo->evento->nombre }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $equipo->participantes->count() }} miembros</p>
                                            
                                            @if($proyecto)
                                                <!-- Barra de progreso -->
                                                <div class="mt-3 flex items-center gap-3">
                                                    <div class="flex-1">
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-{{ $proyecto->estadoColor }}-600 h-2 rounded-full transition-all duration-300" 
                                                                 style="width: {{ $proyecto->porcentaje_completado }}%"></div>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs font-bold text-{{ $proyecto->estadoColor }}-600">
                                                        {{ $proyecto->porcentaje_completado }}%
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-4">
                                            @if($puedeEvaluar)
                                                <a href="{{ route('juez.evaluar', $equipo) }}" 
                                                   class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg text-sm font-bold transition shadow-md hover:shadow-lg flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Evaluar Ahora
                                                </a>
                                            @else
                                                <div class="text-center">
                                                    <button disabled 
                                                            class="px-5 py-2.5 bg-gray-300 text-gray-500 rounded-lg text-sm font-bold cursor-not-allowed flex items-center gap-2"
                                                            title="{{ $proyecto ? 'Estado: ' . $proyecto->estadoTexto : 'Sin proyecto' }}">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        No Disponible
                                                    </button>
                                                    @if($proyecto)
                                                        <p class="text-xs text-gray-500 mt-2 max-w-[200px]">
                                                            @if($proyecto->estado === 'entregado')
                                                                Esperando aprobación del admin
                                                            @elseif($proyecto->estado === 'en_progreso' || $proyecto->estado === 'pendiente_revision')
                                                                Proyecto en progreso ({{ $proyecto->porcentaje_completado }}%)
                                                            @elseif($proyecto->estado === 'evaluado')
                                                                Ya evaluado
                                                            @else
                                                                {{ $proyecto->estadoTexto }}
                                                            @endif
                                                        </p>
                                                    @else
                                                        <p class="text-xs text-gray-500 mt-2">
                                                            Equipo sin proyecto
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-gray-50 rounded-lg">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-600">¡Excelente trabajo!</p>
                                    <p class="text-sm text-gray-500 mt-1">Has completado todas tus evaluaciones asignadas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
