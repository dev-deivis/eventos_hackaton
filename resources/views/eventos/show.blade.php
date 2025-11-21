<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $evento->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Mensajes de éxito/error --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Información del Evento --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($evento->imagen_portada)
                        <img src="{{ asset('storage/' . $evento->imagen_portada) }}" 
                             alt="{{ $evento->titulo }}"
                             class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif

                    <div class="mb-6">
                        <h1 class="text-3xl font-bold mb-4">{{ $evento->titulo }}</h1>
                        
                        <div class="flex gap-2 mb-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                {{ $evento->tipoTexto }}
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full">
                                {{ $evento->estadoTexto }}
                            </span>
                        </div>

                        <p class="text-gray-700 text-lg mb-6">{{ $evento->descripcion }}</p>
                    </div>

                    {{-- Información Clave --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-bold text-lg mb-2">📅 Fechas</h3>
                            <p><strong>Inicio:</strong> {{ $evento->fecha_inicio->format('d/m/Y H:i') }}</p>
                            <p><strong>Fin:</strong> {{ $evento->fecha_fin->format('d/m/Y H:i') }}</p>
                            <p><strong>Registro hasta:</strong> {{ $evento->fecha_limite_registro->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-lg mb-2">📍 Ubicación</h3>
                            <p>{{ $evento->ubicacion }}</p>
                            @if($evento->es_virtual)
                                <p class="text-blue-600">💻 Evento Virtual</p>
                            @endif
                        </div>

                        <div>
                            <h3 class="font-bold text-lg mb-2">👥 Equipos</h3>
                            <p><strong>Miembros por equipo:</strong> {{ $evento->min_miembros_equipo }} - {{ $evento->max_miembros_equipo }}</p>
                            <p><strong>Equipos registrados:</strong> {{ $evento->totalEquipos() }}</p>
                        </div>

                        <div>
                            <h3 class="font-bold text-lg mb-2">📊 Participantes</h3>
                            <p><strong>Total:</strong> {{ $evento->totalParticipantes() }}</p>
                            @if($evento->max_participantes)
                                <p><strong>Máximo:</strong> {{ $evento->max_participantes }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex gap-4">
                        @auth
                            @if($estaInscrito)
                                <span class="bg-green-100 text-green-800 px-6 py-3 rounded-lg font-bold">
                                    ✓ Ya estás inscrito
                                </span>
                                
                                @if(!$tieneEquipo)
                                    <a href="{{ route('equipos.create', $evento) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold">
                                        Crear Equipo
                                    </a>
                                    <a href="{{ route('equipos.index', $evento) }}" 
                                       class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-bold">
                                        Unirme a un Equipo
                                    </a>
                                @else
                                    <span class="bg-purple-100 text-purple-800 px-6 py-3 rounded-lg font-bold">
                                        ✓ Ya tienes equipo
                                    </span>
                                @endif
                            @else
                                @if($evento->estaAbierto() && $evento->puedeRegistrarse())
                                    <form action="{{ route('eventos.register', $evento) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold">
                                            Inscribirme al Evento
                                        </button>
                                    </form>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-6 py-3 rounded-lg font-bold">
                                        Inscripciones Cerradas
                                    </span>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold">
                                Inicia sesión para inscribirte
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Lista de Equipos --}}
            @if($evento->equipos->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-4">Equipos Participantes</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($evento->equipos as $equipo)
                                <div class="border rounded-lg p-4">
                                    <h3 class="font-bold text-lg mb-2">{{ $equipo->nombre }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $equipo->descripcion }}</p>
                                    <p class="text-sm mb-2">
                                        <strong>Líder:</strong> {{ $equipo->lider->name }}
                                    </p>
                                    <p class="text-sm mb-4">
                                        <strong>Miembros:</strong> {{ $equipo->totalMiembros() }} / {{ $equipo->max_miembros }}
                                    </p>
                                    <a href="{{ route('equipos.show', $equipo) }}" 
                                       class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                                        Ver Equipo
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>