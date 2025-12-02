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
                            <p class="text-3xl font-bold text-indigo-600">{{ \App\Models\Participante::count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Estudiantes registrados</p>
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

            <!-- Contenido Principal: Acciones y Estadísticas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                <!-- Columna Izquierda (2/3) - Acciones Rápidas -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones Rápidas</h3>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('eventos.create') }}" 
                               class="flex items-center gap-3 p-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition group">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Crear Evento</span>
                            </a>

                            <a href="{{ route('eventos.index') }}" 
                               class="flex items-center gap-3 p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition group">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Ver Eventos</span>
                            </a>

                            <a href="{{ route('admin.usuarios.index') }}" 
                               class="flex items-center gap-3 p-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                <span class="font-semibold">Usuarios</span>
                            </a>

                            <a href="{{ route('admin.rankings') }}" 
                               class="flex items-center gap-3 p-4 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Rankings</span>
                            </a>

                            <a href="{{ route('admin.reportes.index') }}" 
                               class="flex items-center gap-3 p-4 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                                <span class="font-semibold">Reportes y Análisis</span>
                            </a>

                            <a href="{{ route('admin.proyectos.pendientes') }}" 
                               class="relative flex items-center gap-3 p-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Proyectos Pendientes</span>
                                @php
                                    $pendientes = \App\Models\Proyecto::where('estado', 'entregado')->count();
                                @endphp
                                @if($pendientes > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center animate-pulse">
                                        {{ $pendientes }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('admin.constancias.index') }}" class="flex items-center gap-3 p-4 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Constancias</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha (1/3) - Estadísticas -->
                <div>
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
                                <span class="font-bold text-gray-900">{{ \App\Models\Proyecto::count() }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Equipos Activos</span>
                                <span class="font-bold text-gray-900">{{ \App\Models\Equipo::where('estado', 'activo')->count() }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t">
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                Ver estadísticas completas →
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Eventos Recientes (Ancho Completo) -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Eventos Recientes
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse(\App\Models\Evento::latest()->take(4)->get() as $evento)
                        <div class="flex items-center justify-between p-5 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 text-lg">{{ $evento->nombre }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $evento->totalEquipos() }} Equipos registrados</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-4 py-2 bg-pink-100 text-pink-700 rounded-full text-sm font-medium">
                                    {{ $evento->estadoTexto }}
                                </span>
                                <a href="{{ route('eventos.show', $evento) }}" 
                                   class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-lg font-medium">No hay eventos recientes</p>
                            <a href="{{ route('eventos.create') }}" class="inline-block mt-3 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                Crear el primer evento
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
