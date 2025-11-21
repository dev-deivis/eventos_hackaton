<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos Disponibles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($eventos as $evento)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                @if($evento->imagen_portada)
                                    <img src="{{ asset('storage/' . $evento->imagen_portada) }}" 
                                         alt="{{ $evento->titulo }}"
                                         class="w-full h-48 object-cover rounded mb-4">
                                @endif
                                
                                <h3 class="text-xl font-bold mb-2">{{ $evento->titulo }}</h3>
                                
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $evento->tipoTexto }}
                                    </span>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        {{ $evento->estadoTexto }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit($evento->descripcion, 100) }}
                                </p>
                                
                                <div class="text-sm text-gray-500 mb-4">
                                    <p>📅 {{ $evento->fecha_inicio->format('d/m/Y') }}</p>
                                    <p>📍 {{ $evento->ubicacion }}</p>
                                    <p>👥 {{ $evento->totalParticipantes() }} participantes</p>
                                    <p>🏆 {{ $evento->totalEquipos() }} equipos</p>
                                </div>
                                
                                <a href="{{ route('eventos.show', $evento) }}" 
                                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Ver Detalles
                                </a>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-12">
                                <p class="text-gray-500 text-lg">No hay eventos disponibles</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-6">
                        {{ $eventos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>