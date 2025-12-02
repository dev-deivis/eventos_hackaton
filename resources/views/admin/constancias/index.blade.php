<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Generador de Constancias</h1>
                        <p class="text-gray-600 mt-1">Crear y gestionar certificados digitales verificables</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.constancias.generar-nuevas') }}" 
                           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Generar Constancias
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabs de Navegación -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <a href="{{ route('admin.constancias.index') }}" 
                       class="border-b-2 border-indigo-600 text-indigo-600 py-4 px-1 font-semibold text-sm">
                        Constancias Emitidas
                    </a>
                    <a href="{{ route('admin.constancias.plantillas') }}" 
                       class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Plantillas
                    </a>
                    <a href="{{ route('admin.constancias.generar-nuevas') }}" 
                       class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Generar Nuevas
                    </a>
                </nav>
            </div>

            <!-- Buscador y Filtros -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               placeholder="Buscar por nombre o evento"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos los tipos</option>
                        <option value="participacion">Participación</option>
                        <option value="ganador">Ganador</option>
                    </select>
                </div>
            </div>

            <!-- Grid de Constancias -->
            @if($constancias->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay constancias emitidas</h3>
                    <p class="text-gray-500 mb-6">Comienza generando constancias para los participantes</p>
                    <a href="{{ route('admin.constancias.generar-nuevas') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Generar Primera Constancia
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($constancias as $constancia)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <!-- Header con badge -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L11 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c-.25.78.409 1.569 1.239 1.569.49 0 .945-.177 1.301-.464l1.792-1.446-1.41-4.39-2.104.841z"/>
                                            </svg>
                                            <h3 class="font-bold text-gray-900">{{ $constancia->participante->user->name }}</h3>
                                        </div>
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                            {{ $constancia->tipo_constancia === 'ganador' ? 'bg-pink-100 text-pink-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($constancia->tipo_constancia) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="p-4 space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500">Evento:</p>
                                    <p class="font-medium text-gray-900">{{ $constancia->evento->nombre }}</p>
                                </div>

                                @if($constancia->participante->equipos()->where('evento_id', $constancia->evento_id)->exists())
                                    @php
                                        $equipo = $constancia->participante->equipos()->where('evento_id', $constancia->evento_id)->first();
                                    @endphp
                                    <div>
                                        <p class="text-xs text-gray-500">Equipo:</p>
                                        <p class="font-medium text-gray-900">{{ $equipo->nombre }}</p>
                                    </div>

                                    @if($equipo->proyecto)
                                        <div>
                                            <p class="text-xs text-gray-500">Proyecto:</p>
                                            <p class="font-medium text-gray-900 truncate">{{ $equipo->proyecto->nombre }}</p>
                                        </div>
                                    @endif
                                @endif

                                <div>
                                    <p class="text-xs text-gray-500">Emitido:</p>
                                    <p class="font-medium text-gray-900">{{ $constancia->fecha_emision->format('d/m/Y') }}</p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">Código:</p>
                                    <p class="font-mono text-sm font-semibold text-indigo-600">{{ $constancia->codigo_verificacion }}</p>
                                </div>
                            </div>

                            <!-- Footer con acciones -->
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                                <a href="{{ route('admin.constancias.descargar', $constancia) }}" 
                                   class="flex-1 text-center px-3 py-2 text-sm font-semibold text-white bg-pink-500 hover:bg-pink-600 rounded-lg transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Descargar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-8">
                    {{ $constancias->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
