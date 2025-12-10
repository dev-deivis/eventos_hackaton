<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Eventos Disponibles
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400 mt-2">Descubre y participa en competencias de desarrollo de software</p>
                    </div>
                    
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('eventos.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Crear Evento
                        </a>
                    @endif
                </div>
            </div>

            <!-- Grid de Eventos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($eventos as $evento)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 group">
                        
                        <!-- Imagen o Gradiente -->
                        @if($evento->imagen_portada)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $evento->imagen_portada) }}" 
                                     alt="{{ $evento->nombre }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            @php
                                $gradientes = [
                                    'from-purple-500 to-pink-500',
                                    'from-blue-500 to-cyan-500',
                                    'from-green-500 to-teal-500',
                                    'from-orange-500 to-red-500',
                                    'from-indigo-500 to-purple-500',
                                ];
                                $gradiente = $gradientes[$evento->id % count($gradientes)];
                            @endphp
                            <div class="h-48 bg-gradient-to-br {{ $gradiente }} flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            
                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <!-- Tipo de Evento -->
                                @php
                                    $tipoColors = [
                                        'hackathon' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300',
                                        'datathon' => 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300',
                                        'concurso' => 'bg-pink-100 dark:bg-pink-900 text-pink-700 dark:text-pink-300',
                                        'workshop' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300',
                                    ];
                                    $tipoColor = $tipoColors[$evento->tipo] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-3 py-1 {{ $tipoColor }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($evento->tipo) }}
                                </span>
                                
                                <!-- Estado -->
                                @php
                                    $estadoColors = [
                                        'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                                        'abierto' => 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300',
                                        'en_progreso' => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300',
                                        'cerrado' => 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300',
                                        'completado' => 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300',
                                    ];
                                    $estadoColor = $estadoColors[$evento->estado] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                @endphp
                                <span class="px-3 py-1 {{ $estadoColor }} rounded-full text-xs font-semibold">
                                    {{ $evento->estadoTexto }}
                                </span>
                            </div>
                            
                            <!-- Título -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 dark:text-indigo-400 dark:group-hover:text-indigo-400 transition">
                                {{ $evento->nombre }}
                            </h3>
                            
                            <!-- Descripción -->
                            <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ $evento->descripcion }}
                            </p>
                            
                            <!-- Información -->
                            <div class="space-y-2 mb-4">
                                <!-- Fecha -->
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 dark:text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $evento->fecha_inicio->format('d/m/Y') }}</span>
                                </div>
                                
                                <!-- Ubicación -->
                                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 dark:text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $evento->ubicacion }}</span>
                                </div>
                                
                                <!-- Estadísticas -->
                                <div class="flex items-center gap-4 text-sm">
                                    <div class="flex items-center gap-1 text-gray-700 dark:text-gray-300 dark:text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        <span>{{ $evento->totalParticipantes() }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-gray-700 dark:text-gray-300 dark:text-gray-600 dark:text-gray-300">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                        </svg>
                                        <span>{{ $evento->totalEquipos() }} equipos</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón -->
                            <a href="{{ route('eventos.show', $evento) }}" 
                               class="block w-full text-center bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="col-span-full">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center border border-gray-100 dark:border-gray-700">
                            <svg class="w-24 h-24 text-gray-300 dark:text-gray-600 dark:text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No hay eventos disponibles</h3>
                            <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500 dark:text-gray-400 mb-6">Aún no se han creado eventos. ¡Vuelve pronto para ver las próximas competencias!</p>
                            
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('eventos.create') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white rounded-lg font-semibold transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Crear Primer Evento
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Paginación -->
            @if($eventos->hasPages())
                <div class="mt-8">
                    {{ $eventos->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
