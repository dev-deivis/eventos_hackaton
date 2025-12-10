<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                Equipos - {{ $evento->nombre }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500">
                                {{ $equipos->total() }} equipos registrados
                            </p>
                        </div>
                        
                        @if(auth()->user()->participante && $evento->estaAbierto())
                            @php
                                $yaEstaEnEquipo = auth()->user()->participante->equipos()
                                    ->where('evento_id', $evento->id)
                                    ->exists();
                                    
                                $miEquipo = auth()->user()->participante->equipos()
                                    ->where('evento_id', $evento->id)
                                    ->first();
                            @endphp
                            
                            @if($yaEstaEnEquipo && $miEquipo)
                                <!-- Ya tiene equipo - Mostrar link a su equipo -->
                                <div class="flex flex-col items-end gap-2">
                                    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 rounded-lg px-4 py-2">
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            Ya eres parte de: <span class="font-semibold">{{ $miEquipo->nombre }}</span>
                                        </p>
                                    </div>
                                    <a href="{{ route('equipos.show', $miEquipo) }}" 
                                       class="inline-flex items-center gap-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Ver Mi Equipo
                                    </a>
                                </div>
                            @else
                                <!-- No tiene equipo - Mostrar botón crear -->
                                <a href="{{ route('equipos.create', $evento) }}" 
                                   class="inline-flex items-center gap-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear Equipo
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lista de equipos -->
            @if($equipos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($equipos as $equipo)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                            <div class="p-6">
                                
                                <!-- Nombre del equipo -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                            {{ $equipo->nombre }}
                                        </h3>
                                        @if($equipo->descripcion)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 line-clamp-2">
                                                {{ $equipo->descripcion }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <!-- Badge de estado -->
                                    @if($equipo->estaCompleto())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                            Completo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300">
                                            Abierto
                                        </span>
                                    @endif
                                </div>

                                <!-- Líder -->
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mb-3">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">Líder:</span>
                                    {{ $equipo->lider->user->name }}
                                </div>

                                <!-- Miembros -->
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 mb-4">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    <span>
                                        {{ $equipo->miembros_activos_count }}/{{ $equipo->max_miembros }} miembros
                                    </span>
                                </div>

                                <!-- Proyecto -->
                                @if($equipo->proyecto)
                                    <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400 mb-4">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-medium">Proyecto registrado</span>
                                    </div>
                                @endif

                                <!-- Botón Ver Detalle -->
                                <a href="{{ route('equipos.show', $equipo) }}" 
                                   class="block w-full text-center bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition duration-200">
                                    Ver Detalle
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $equipos->links() }}
                </div>
            @else
                <!-- Estado vacío -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay equipos aún</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">
                            Sé el primero en crear un equipo para este evento.
                        </p>
                        @if(auth()->user()->participante && $evento->estaAbierto())
                            <div class="mt-6">
                                <a href="{{ route('equipos.create', $evento) }}" 
                                   class="inline-flex items-center gap-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Crear Equipo
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
