<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Rankings de Equipos</h1>
                <p class="text-gray-600 mt-1">Clasificación actual basada en evaluaciones consolidadas</p>
            </div>

            <!-- Filtro por Evento -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
                <form method="GET" action="{{ route('admin.rankings') }}" class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Selector de Evento -->
                        <div class="flex-1">
                            <label for="evento_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Filtrar por Evento
                            </label>
                            <select name="evento_id" id="evento_id"
                                class="block w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="todos" {{ request('evento_id', 'todos') == 'todos' ? 'selected' : '' }}>
                                    Todos los eventos
                                </option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}"
                                        {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                        {{ $evento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-end gap-3">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h6a1 1 0 001-1v-6a1 1 0 00-1-1h-6z" />
                                </svg>
                                Filtrar
                            </button>
                            <a href="{{ route('admin.rankings') }}"
                                class="inline-flex items-center gap-2 px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Limpiar
                            </a>
                        </div>
                    </div>

                    <!-- Info del filtro activo -->
                    @if (request('evento_id') && request('evento_id') !== 'todos')
                        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">📌 Filtrando por:</span>
                                {{ $eventos->find(request('evento_id'))->nombre ?? 'Evento' }}
                            </p>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Clasificación General -->
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Clasificación General</h2>
                    <p class="text-sm text-gray-600 mt-1">Rankings actualizados en tiempo real basados en las
                        evaluaciones de todos los jueces</p>
                </div>

                <div class="space-y-6">
                    @forelse($equipos as $index => $equipo)
                        @php
                            $posicion = ($equipos->currentPage() - 1) * $equipos->perPage() + $index + 1;
                            $badgeColors = [
                                1 => ['bg' => 'bg-purple-600', 'text' => 'text-white', 'label' => '1er Lugar'],
                                2 => ['bg' => 'bg-pink-500', 'text' => 'text-white', 'label' => '2do Lugar'],
                            ];
                            $badge = $badgeColors[$posicion] ?? null;
                        @endphp

                        <!-- Card de Equipo ghjgjgjgjg fghdfhfdffhfh -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $equipo->nombre }}</h3>
                                        @if ($badge)
                                            <span
                                                class="px-3 py-1 {{ $badge['bg'] }} {{ $badge['text'] }} rounded-full text-xs font-bold flex items-center gap-1">
                                                @if ($posicion == 1)
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endif
                                                {{ $badge['label'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1">{{ $equipo->evento->nombre }}</p>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                            </svg>
                                            {{ $equipo->participantes->count() }} miembros
                                        </span>
                                        <span>{{ $equipo->num_evaluaciones }} evaluaciones • Promedio:
                                            {{ number_format($equipo->calificacion_promedio, 1) }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div
                                        class="text-5xl font-bold text-{{ $posicion == 1 ? 'purple' : ($posicion == 2 ? 'pink' : 'gray') }}-600">
                                        {{ number_format($equipo->calificacion_promedio, 1) }}
                                    </div>
                                    <div class="text-xs text-gray-500">Puntuación</div>
                                </div>
                            </div>

                            <!-- Barras de Progreso de Criterios -->
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">

                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-700">Innovación</span>
                                        <span
                                            class="font-bold text-blue-600">{{ number_format($equipo->innovacion_promedio, 1) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $equipo->innovacion_promedio }}%"></div>
                                    </div>
                                </div>

                                <!-- Implementación Técnica -->
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-700">Implementación Técnica</span>
                                        <span
                                            class="font-bold text-purple-600">{{ number_format($equipo->implementacion_promedio, 1) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $equipo->implementacion_promedio }}%"></div>
                                    </div>
                                </div>

                                <!-- Presentación -->
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-700">Presentación</span>
                                        <span
                                            class="font-bold text-green-600">{{ number_format($equipo->presentacion_promedio, 1) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $equipo->presentacion_promedio }}%"></div>
                                    </div>
                                </div>

                                <!-- Trabajo en Equipo -->
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-700">Trabajo en Equipo</span>
                                        <span
                                            class="font-bold text-pink-600">{{ number_format($equipo->trabajo_equipo_promedio, 1) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-pink-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $equipo->trabajo_equipo_promedio }}%"></div>
                                    </div>
                                </div>

                                <!-- Viabilidad (No visible en tu diseño pero lo incluyo) -->
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-700">Viabilidad</span>
                                        <span
                                            class="font-bold text-indigo-600">{{ number_format($equipo->viabilidad_promedio, 1) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $equipo->viabilidad_promedio }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Estado Vacío -->
                        <div class="bg-white rounded-xl p-12 text-center">
                            <div
                                class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">No hay equipos evaluados aún</h3>
                            <p class="text-gray-600 mb-6">Los rankings se generarán automáticamente cuando se completen
                                las evaluaciones</p>
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                Ir al Dashboard
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                @if ($equipos->hasPages())
                    <div class="mt-6">
                        {{ $equipos->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
