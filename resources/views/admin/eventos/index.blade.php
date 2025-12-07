<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📅 Gestión de Eventos
            </h2>
            <a href="{{ route('eventos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Evento
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Estadísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $estadisticas['total'] }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Próximos</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $estadisticas['proximo'] }}</p>
                        </div>
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">En Curso</p>
                            <p class="text-3xl font-bold text-green-600">{{ $estadisticas['en_curso'] }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Finalizados</p>
                            <p class="text-3xl font-bold text-gray-600">{{ $estadisticas['finalizado'] }}</p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Búsqueda y Filtros --}}
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <form method="GET" action="{{ route('eventos.admin.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Buscador --}}
                        <div class="md:col-span-2">
                            <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">
                                🔍 Buscar Evento
                            </label>
                            <input 
                                type="text" 
                                name="buscar" 
                                id="buscar" 
                                value="{{ request('buscar') }}"
                                placeholder="Buscar por nombre o descripción..."
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>

                        {{-- Filtro por Estado --}}
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                🏷️ Estado
                            </label>
                            <select 
                                name="estado" 
                                id="estado"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="todos" {{ request('estado') == 'todos' ? 'selected' : '' }}>
                                    Todos los estados
                                </option>
                                <option value="proximo" {{ request('estado') == 'proximo' ? 'selected' : '' }}>
                                    ⏳ Próximos
                                </option>
                                <option value="en_curso" {{ request('estado') == 'en_curso' ? 'selected' : '' }}>
                                    ⚡ En Curso
                                </option>
                                <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>
                                    ✅ Finalizados
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex gap-3">
                        <button 
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                        <a 
                            href="{{ route('eventos.admin.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Resultados --}}
            @if($eventos->total() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            📋 Eventos Encontrados ({{ $eventos->total() }})
                        </h3>
                        @if(request('buscar') || request('estado'))
                            <span class="text-sm text-gray-500">
                                Filtrando resultados
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @foreach($eventos as $evento)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="text-lg font-semibold text-gray-800">
                                                {{ $evento->nombre }}
                                            </h4>
                                            
                                            {{-- Badge de Estado --}}
                                            @php
                                                $badges = [
                                                    'proximo' => ['bg' => 'bg-yellow-100 text-yellow-800', 'text' => '⏳ Próximo'],
                                                    'en_curso' => ['bg' => 'bg-green-100 text-green-800', 'text' => '⚡ En Curso'],
                                                    'finalizado' => ['bg' => 'bg-gray-100 text-gray-800', 'text' => '✅ Finalizado'],
                                                ];
                                                $badge = $badges[$evento->estado] ?? ['bg' => 'bg-gray-100 text-gray-800', 'text' => $evento->estado];
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $badge['bg'] }}">
                                                {{ $badge['text'] }}
                                            </span>

                                            {{-- Badge de Tipo --}}
                                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($evento->tipo) }}
                                            </span>
                                        </div>

                                        <p class="text-gray-600 text-sm mb-3">
                                            {{ Str::limit($evento->descripcion, 150) }}
                                        </p>

                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm text-gray-500">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                {{ $evento->equipos_count ?? $evento->equipos->count() }} Equipos
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $evento->duracion_horas }}h
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                {{ $evento->ubicacion }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Acciones --}}
                                    <div class="flex flex-col gap-2 ml-4">
                                        <a 
                                            href="{{ route('eventos.dashboard', $evento) }}"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm text-center transition"
                                        >
                                            Ver Dashboard
                                        </a>
                                        <a 
                                            href="{{ route('eventos.edit', $evento) }}"
                                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm text-center transition"
                                        >
                                            Editar
                                        </a>
                                        <form 
                                            action="{{ route('eventos.destroy', $evento) }}" 
                                            method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este evento?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-6">
                        {{ $eventos->links() }}
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        No se encontraron eventos
                    </h3>
                    <p class="text-gray-600 mb-4">
                        @if(request('buscar') || request('estado'))
                            No hay eventos que coincidan con los filtros aplicados
                        @else
                            No hay eventos creados aún
                        @endif
                    </p>
                    @if(request('buscar') || request('estado'))
                        <a 
                            href="{{ route('eventos.admin.index') }}"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Limpiar Filtros
                        </a>
                    @else
                        <a 
                            href="{{ route('eventos.create') }}"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear Primer Evento
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
