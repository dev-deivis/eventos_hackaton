<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Panel de Administrador</h1>
                <p class="text-gray-600 mt-1">Bienvenido Dr. {{ auth()->user()->name }}, gestiona eventos, equipos y genera reportes</p>
            </div>

            <!-- Estadísticas Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Eventos Activos -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Eventos Activos</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-3xl font-bold text-pink-600">{{ \App\Models\Evento::activos()->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">+1 desde el mes pasado</p>
                        </div>
                    </div>
                </div>

                <!-- Participantes -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Participantes</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-3xl font-bold text-indigo-600">{{ \App\Models\EventRegistration::count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">+24% vs mes anterior</p>
                        </div>
                    </div>
                </div>

                <!-- Equipos Formados -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Equipos Formados</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Equipo::count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Promedio 4 miembros</p>
                        </div>
                    </div>
                </div>

                <!-- Tasa de Finalización -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-gray-600">Tasa de Finalización</h3>
                    </div>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-3xl font-bold text-pink-600">87%</p>
                            <p class="text-xs text-gray-500 mt-1">+5% vs cuatri anterior</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Izquierda (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('eventos.create') }}" 
                               class="flex items-center gap-3 p-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition group">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Crear Nuevo Evento</span>
                            </a>

                            <a href="#" 
                               class="flex items-center gap-3 p-4 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Ver Rankings</span>
                            </a>

                            <button class="w-full flex items-center gap-3 p-4 bg-white hover:bg-gray-50 border-2 border-gray-200 rounded-lg transition text-left">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                                <span class="font-semibold text-gray-700">Reportes y Análisis</span>
                            </button>

                            <button class="w-full flex items-center gap-3 p-4 bg-white hover:bg-gray-50 border-2 border-gray-200 rounded-lg transition text-left">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-gray-700">Generar Constancias</span>
                            </button>
                        </div>
                    </div>

                    <!-- Eventos Recientes -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">📅 Eventos Recientes</h3>
                        </div>

                        <div class="space-y-4">
                            @forelse(\App\Models\Evento::latest()->take(3)->get() as $evento)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $evento->titulo }}</h4>
                                        <p class="text-sm text-gray-600">{{ $evento->totalEquipos() }} Equipos</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-medium">
                                            {{ $evento->estadoTexto }}
                                        </span>
                                        <a href="{{ route('eventos.show', $evento) }}" 
                                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <p>No hay eventos recientes</p>
                                    <a href="{{ route('eventos.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                                        Crear el primer evento
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Columna Derecha (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Actividad Reciente -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Estadísticas Rápidas</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Usuarios</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\User::count() }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Eventos Completados</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Evento::where('estado', 'completado')->count() }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Proyectos Presentados</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Proyecto::where('estado', 'presentado')->count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Equipos Activos</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Equipo::activos()->count() }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t">
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                Ver estadísticas completas →
                            </a>
                        </div>
                    </div>

                    <!-- Acceso Rápido -->
                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                        <h3 class="text-lg font-bold mb-4"> Acceso Rápido</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('eventos.index') }}" class="block p-3 bg-white/10 hover:bg-white/20 rounded-lg transition">
                                <p class="font-medium">Ver todos los eventos</p>
                            </a>
                            
                            <a href="#" class="block p-3 bg-white/10 hover:bg-white/20 rounded-lg transition">
                                <p class="font-medium">Gestionar usuarios</p>
                            </a>
                            
                            <a href="#" class="block p-3 bg-white/10 hover:bg-white/20 rounded-lg transition">
                                <p class="font-medium">Configuración</p>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
