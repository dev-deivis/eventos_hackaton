<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna Izquierda (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Información Personal -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <div class="flex items-start justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Información personal
                            </h3>
                            <a href="{{ route('profile.edit') }}" 
                               class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 dark:text-indigo-300 font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
                                </svg>
                                Editar Perfil
                            </a>
                        </div>

                        <div class="flex items-start gap-6">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>

                            <!-- Información -->
                            <div class="flex-1 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Nombre Completo</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                </div>

                                @if(auth()->user()->participante)
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Número de Control</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->participante->no_control }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Carrera</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->participante->carrera->nombre }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Semestre</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->participante->semestre }}mo Semestre</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->participante && auth()->user()->participante->telefono)
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Teléfono</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->participante->telefono }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(auth()->user()->participante && auth()->user()->participante->biografia)
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mb-2">Biografía</p>
                                <p class="text-gray-700 dark:text-gray-300 dark:text-gray-600">{{ auth()->user()->participante->biografia }}</p>
                            </div>
                        @endif

                        <!-- Enlaces -->
                        <div class="mt-4 flex gap-2">
                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 dark:bg-gray-600 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-3.815 10.631C7.237 14.5 8 16.443 8 18v1h4v-1c0-1.557.763-3.5 1.815-5.369A6 6 0 0010 2z"/>
                                </svg>
                                GitHub
                            </button>
                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 dark:bg-gray-600 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                                </svg>
                                LinkedIn
                            </button>
                            <button class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 dark:bg-gray-600 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Portafolio
                            </button>
                        </div>
                    </div>

                    <!-- Habilidades y Experiencia -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            Habilidades y Experiencia
                        </h3>

                        <!-- Roles Preferidos -->
                        <div class="mb-6">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-3">Roles Preferidos</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-medium">Desarrollador Full Stack</span>
                                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full text-sm font-medium">Analista de Datos</span>
                            </div>
                        </div>

                        <!-- Habilidades Técnicas -->
                        <div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-3">Habilidades Técnicas</p>
                            
                            @if(auth()->user()->participante && auth()->user()->participante->habilidades->count() > 0)
                                <div class="space-y-3">
                                    @foreach(auth()->user()->participante->habilidades as $habilidad)
                                        <div>
                                            <div class="flex justify-between mb-1">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $habilidad->nombre }}
                                                </span>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $habilidad->nivel }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="{{ $habilidad->color }} h-2 rounded-full transition-all duration-500" 
                                                     style="width: {{ $habilidad->nivel }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <p class="text-sm">No has agregado habilidades aún</p>
                                    <a href="{{ route('profile.edit') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 dark:text-indigo-300 mt-2 inline-block">
                                        Agregar habilidades
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Historial de Participación -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Historial de Participación
                        </h3>

                        @php
                            $misEquipos = auth()->user()->equiposActivos ?? collect();
                        @endphp

                        <div class="space-y-4">
                            @forelse($misEquipos as $equipo)
                                <div class="flex items-start justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $equipo->evento->nombre }}</h4>
                                            @if($equipo->esLider(auth()->user()))
                                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded text-xs font-medium">1er Lugar</span>
                                            @else
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded text-xs font-medium">Sin Lugar</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">{{ $equipo->nombre }} • 
                                            @if($equipo->esLider(auth()->user()))
                                                Líder
                                            @else
                                                Miembro
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-gray-500">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"/>
                                            </svg>
                                            <span>{{ $equipo->created_at->format('M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($equipo->proyecto)
                                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded text-xs font-medium">Constancia</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm">No has participado en eventos aún</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Columna Derecha (1/3) -->
                <div class="space-y-6">
                    
                    <!-- ========================================== -->
                    <!-- ESTADÍSTICAS PARA PARTICIPANTE -->
                    <!-- ========================================== -->
                    @if(auth()->user()->isParticipante() && $stats)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            Estadísticas
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['eventos_participados'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Eventos</div>
                            </div>

                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['total_equipos'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Equipos</div>
                            </div>

                            <div class="text-center p-4 bg-pink-50 dark:bg-pink-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-pink-600 dark:text-pink-400">{{ $stats['veces_lider'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Veces Líder</div>
                            </div>

                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['constancias'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Constancias</div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Proyectos Presentados</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $stats['proyectos_presentados'] }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Total de Premios</span>
                                <span class="font-bold text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $stats['total_premios'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Logros de Participante -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Logros y Premios
                        </h3>

                        <div class="space-y-3">
                            @if($stats['premios']['primero'] > 0)
                                <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg border-l-4 border-yellow-500">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white text-xl">
                                            🥇
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $stats['premios']['primero'] }}x Primer Lugar</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Ganaste el primer lugar en {{ $stats['premios']['primero'] }} evento(s)</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($stats['premios']['segundo'] > 0)
                                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-l-4 border-gray-400">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center text-white text-xl">
                                            🥈
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $stats['premios']['segundo'] }}x Segundo Lugar</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Obtuviste el segundo lugar en {{ $stats['premios']['segundo'] }} evento(s)</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($stats['premios']['tercero'] > 0)
                                <div class="p-3 bg-orange-50 dark:bg-orange-900/30 rounded-lg border-l-4 border-orange-500">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white text-xl">
                                            🥉
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $stats['premios']['tercero'] }}x Tercer Lugar</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Lograste el tercer lugar en {{ $stats['premios']['tercero'] }} evento(s)</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($stats['veces_lider'] > 0)
                                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-lg border-l-4 border-purple-500">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white">Líder de Equipo</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Has liderado {{ $stats['veces_lider'] }} equipo(s)</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($stats['total_premios'] == 0 && $stats['veces_lider'] == 0)
                                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    <p class="text-sm">Sigue participando para ganar logros</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- ========================================== -->
                    <!-- ESTADÍSTICAS PARA JUEZ -->
                    <!-- ========================================== -->
                    @if(auth()->user()->isJuez() && $juezStats)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Estadísticas como Juez
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $juezStats['eventos_como_juez'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Eventos</div>
                            </div>

                            <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $juezStats['equipos_evaluados'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Equipos</div>
                            </div>

                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg col-span-2">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $juezStats['total_evaluaciones'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Total de Evaluaciones Realizadas</div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500">Promedio de Calificaciones</span>
                                <span class="font-bold text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $juezStats['promedio_calificaciones'] }}/10
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Evaluaciones por Evento -->
                    @if($juezStats['evaluaciones_por_evento']->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Evaluaciones por Evento</h3>
                        <div class="space-y-3">
                            @foreach($juezStats['evaluaciones_por_evento']->take(5) as $evento => $cantidad)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 flex-1 truncate">{{ $evento }}</span>
                                    <span class="ml-2 px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-bold">{{ $cantidad }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- ========================================== -->
                    <!-- ESTADÍSTICAS PARA ADMINISTRADOR -->
                    <!-- ========================================== -->
                    @if(auth()->user()->isAdmin() && $adminStats)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Panel de Administrador
                        </h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $adminStats['eventos_creados'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Eventos Creados</div>
                            </div>

                            <div class="text-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $adminStats['eventos_activos'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Eventos Activos</div>
                            </div>

                            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $adminStats['total_usuarios'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Usuarios</div>
                            </div>

                            <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $adminStats['total_equipos'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Equipos</div>
                            </div>

                            <div class="text-center p-4 bg-pink-50 dark:bg-pink-900/30 rounded-lg col-span-2">
                                <div class="text-3xl font-bold text-pink-600 dark:text-pink-400">{{ $adminStats['total_proyectos'] }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mt-1">Proyectos Presentados</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="block w-full text-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition">
                                Ir al Panel de Administrador
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Configuración -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            Configuración
                        </h3>

                        <div class="space-y-2">
                            <button class="w-full flex items-center gap-2 p-3 text-sm text-gray-700 dark:text-gray-300 dark:text-gray-600 hover:bg-gray-50 dark:bg-gray-700/50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                </svg>
                                Notificaciones
                            </button>

                            <button class="w-full flex items-center gap-2 p-3 text-sm text-gray-700 dark:text-gray-300 dark:text-gray-600 hover:bg-gray-50 dark:bg-gray-700/50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                                Privacidad
                            </button>

                            <button class="w-full flex items-center gap-2 p-3 text-sm text-gray-700 dark:text-gray-300 dark:text-gray-600 hover:bg-gray-50 dark:bg-gray-700/50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Preferencias
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
