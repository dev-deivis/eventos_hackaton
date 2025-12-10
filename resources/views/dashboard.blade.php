<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Bienvenido {{ explode(' ', auth()->user()->name)[0] }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Explora eventos, forma equipos y desarrolla proyectos innovadores</p>
            </div>

            <!-- Contenido Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Izquierda (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Eventos Disponibles -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Eventos Disponibles
                            </h3>
                            <a href="{{ route('eventos.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                                Buscar eventos
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse(\App\Models\Evento::abiertos()->take(3)->get() as $evento)
                                <div class="border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg p-4 hover:border-indigo-300 dark:hover:border-indigo-500 hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white mb-1">{{ $evento->nombre }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $evento->tipoTexto }} • {{ $evento->fecha_inicio->format('d M Y') }} • {{ $evento->totalParticipantes() }} participantes
                                            </p>
                                        </div>
                                        <div class="flex gap-2">
                                            <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-xs font-medium">
                                                Activo
                                            </span>
                                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full text-xs font-medium">
                                                {{ $evento->estadoTexto }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('eventos.show', $evento) }}" 
                                       class="inline-block px-4 py-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg text-sm font-medium transition">
                                        Ver Detalles
                                    </a>
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="font-medium">No hay eventos disponibles en este momento</p>
                                    <p class="text-sm mt-1">Vuelve pronto para ver nuevos eventos</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Mis Equipos -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                Mis equipos
                            </h3>
                        </div>

                        @php
                            $misEquipos = auth()->user()->equiposActivos;
                        @endphp

                        @forelse($misEquipos as $equipo)
                            <div class="border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $equipo->nombre }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $equipo->evento->nombre }}</p>
                                    </div>
                                    @if($equipo->esLider(auth()->user()))
                                        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300 rounded-full text-xs font-medium">
                                            Líder
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-medium">
                                            Miembro
                                        </span>
                                    @endif
                                </div>

                                <!-- Barra de progreso miembros -->
                                <div class="mb-3">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">{{ $equipo->totalMiembros() }} miembros</span>
                                        <span class="text-gray-500 dark:text-gray-500">{{ $equipo->max_miembros }} máx</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full" style="width: {{ ($equipo->totalMiembros() / $equipo->max_miembros) * 100 }}%"></div>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('equipos.show', $equipo) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">
                                        Ver Equipo
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-600">•</span>
                                    <a href="{{ route('eventos.show', $equipo->evento) }}" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-medium">
                                        Ver Evento
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 font-medium">No estás en ningún equipo</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Únete o crea un equipo para participar</p>
                            </div>
                        @endforelse
                    </div>

                </div>

                <!-- Columna Derecha (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Acciones Rápidas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Acciones Rápidas</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('equipos.seleccionar-evento') }}" 
                               class="flex items-center gap-3 p-3 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-lg transition group">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Crear Equipo</span>
                            </a>

                            <a href="{{ route('eventos.index') }}" 
                               class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Unirse a Equipo</span>
                            </a>

                            <a href="{{ route('equipos.mis-equipos') }}" 
                               class="flex items-center gap-3 p-3 bg-pink-50 dark:bg-pink-900/30 hover:bg-pink-100 dark:hover:bg-pink-900/50 rounded-lg transition">
                                <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Mis Equipos</span>
                            </a>
                        </div>
                    </div>

                    <!-- Mis Estadísticas -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Mis Estadísticas</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Eventos Participados</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->eventos->count() }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Equipos Formados</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->equiposActivos->count() }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Proyectos Completados</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->proyectosCompletados }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Constancias Obtenidas</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ auth()->user()->constancias->count() }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
