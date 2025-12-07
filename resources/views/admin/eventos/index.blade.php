<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Gestión de Eventos
                        </h1>
                        <p class="text-gray-600 mt-2">Administra todos los eventos del sistema</p>
                    </div>
                    
                    <a href="{{ route('eventos.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Crear Evento
                    </a>
                </div>
            </div>

            <!-- Buscador y Filtros -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                <form method="GET" action="{{ route('eventos.admin.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <!-- Buscador -->
                        <div class="md:col-span-2">
                            <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">
                                Buscar Evento
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="buscar" 
                                    id="buscar" 
                                    value="{{ request('buscar') }}"
                                    placeholder="Buscar por nombre o descripción..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                >
                            </div>
                        </div>

                        <!-- Filtro por Estado -->
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                Filtrar por Estado
                            </label>
                            <select 
                                name="estado" 
                                id="estado"
                                class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="todos" {{ request('estado', 'todos') == 'todos' ? 'selected' : '' }}>
                                    Todos los estados
                                </option>
                                <option value="proximo" {{ request('estado') == 'proximo' ? 'selected' : '' }}>
                                    Próximos
                                </option>
                                <option value="en_curso" {{ request('estado') == 'en_curso' ? 'selected' : '' }}>
                                    En Curso
                                </option>
                                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>
                                    Finalizados
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3">
                        <button 
                            type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                        <a 
                            href="{{ route('eventos.admin.index') }}"
                            class="inline-flex items-center gap-2 px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Grid de Eventos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($eventos as $evento)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 group">
                        
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
                                        'hackathon' => 'bg-blue-100 text-blue-700',
                                        'datathon' => 'bg-purple-100 text-purple-700',
                                        'concurso' => 'bg-pink-100 text-pink-700',
                                        'workshop' => 'bg-green-100 text-green-700',
                                    ];
                                    $tipoColor = $tipoColors[$evento->tipo] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-3 py-1 {{ $tipoColor }} rounded-full text-xs font-semibold">
                                    {{ ucfirst($evento->tipo) }}
                                </span>
                                
                                <!-- Estado -->
                                @php
                                    $estadoColors = [
                                        'proximo' => 'bg-yellow-100 text-yellow-700',
                                        'en_curso' => 'bg-green-100 text-green-700',
                                        'finalizado' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $estadoTextos = [
                                        'proximo' => 'Próximo',
                                        'en_curso' => 'En Curso',
                                        'finalizado' => 'Finalizado',
                                    ];
                                    $estadoColor = $estadoColors[$evento->estado] ?? 'bg-gray-100 text-gray-700';
                                    $estadoTexto = $estadoTextos[$evento->estado] ?? ucfirst($evento->estado);
                                @endphp
                                <span class="px-3 py-1 {{ $estadoColor }} rounded-full text-xs font-semibold">
                                    {{ $estadoTexto }}
                                </span>
                            </div>
                            
                            <!-- Título -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition">
                                {{ $evento->nombre }}
                            </h3>
                            
                            <!-- Descripción -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $evento->descripcion }}
                            </p>
                            
                            <!-- Información -->
                            <div class="space-y-2 mb-4">
                                <!-- Fecha -->
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}</span>
                                </div>
                                
                                <!-- Ubicación -->
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $evento->ubicacion }}</span>
                                </div>
                                
                                <!-- Estadísticas -->
                                <div class="flex items-center gap-4 text-sm">
                                    <div class="flex items-center gap-1 text-gray-700">
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                        </svg>
                                        <span>{{ $evento->equipos->count() }} equipos</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones Admin -->
                            <div class="space-y-2">
                                <a href="{{ route('eventos.show', $evento) }}" 
                                   class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                    Ver Detalles
                                </a>
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('eventos.edit', $evento) }}" 
                                       class="block text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                        Editar
                                    </a>
                                    <form action="{{ route('eventos.destroy', $evento) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Eliminar este evento?')"
                                          class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado Vacío -->
                    <div class="col-span-full">
                        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                @if(request('buscar') || request('estado') != 'todos')
                                    No se encontraron eventos
                                @else
                                    No hay eventos creados
                                @endif
                            </h3>
                            <p class="text-gray-600 mb-6">
                                @if(request('buscar') || request('estado') != 'todos')
                                    No hay eventos que coincidan con los filtros aplicados
                                @else
                                    Aún no se han creado eventos. ¡Crea el primero!
                                @endif
                            </p>
                            
                            @if(request('buscar') || request('estado') != 'todos')
                                <a href="{{ route('eventos.admin.index') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Limpiar Filtros
                                </a>
                            @else
                                <a href="{{ route('eventos.create') }}" 
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
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
