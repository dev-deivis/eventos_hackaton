<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        Crear Equipo
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400">
                        Selecciona el evento para el cual quieres crear tu equipo
                    </p>
                </div>
            </div>

            <!-- Eventos Disponibles -->
            @if($eventosDisponibles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($eventosDisponibles as $evento)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200 border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                
                                <!-- Header del evento -->
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                            {{ ucfirst($evento->tipo) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                            {{ ucfirst($evento->estado) }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $evento->nombre }}
                                    </h3>
                                    
                                    @if($evento->descripcion)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400 line-clamp-2">
                                            {{ Str::limit($evento->descripcion, 100) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Info del evento -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        <span>{{ $evento->min_miembros_equipo }} - {{ $evento->max_miembros_equipo }} miembros por equipo</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                        </svg>
                                        <span>{{ $evento->equipos()->count() }} equipos registrados</span>
                                    </div>
                                </div>

                                <!-- Botón -->
                                <a href="{{ route('equipos.create', $evento) }}" 
                                   class="block w-full text-center bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                                    Crear Equipo para este Evento
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- No hay eventos disponibles -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 dark:text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay eventos disponibles</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-500 dark:text-gray-400 dark:text-gray-500">
                            No hay eventos abiertos en los que puedas crear un equipo en este momento.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('eventos.index') }}" 
                               class="inline-flex items-center gap-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                                Ver Todos los Eventos
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
