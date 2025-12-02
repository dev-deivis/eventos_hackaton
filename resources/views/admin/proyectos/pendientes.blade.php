<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Proyectos Pendientes de Aprobación</h1>
                        <p class="text-gray-600 mt-1">Revisa y aprueba proyectos para que puedan ser evaluados por los jueces</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        ← Volver al Dashboard
                    </a>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contador -->
            <div class="mb-6 bg-purple-50 border-l-4 border-purple-500 p-4 rounded-lg">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-purple-900 font-bold">{{ $proyectos->count() }} proyectos esperando aprobación</p>
                        <p class="text-sm text-purple-700">Revisa cada proyecto antes de aprobar para evaluación</p>
                    </div>
                </div>
            </div>

            <!-- Lista de Proyectos -->
            @forelse($proyectos as $proyecto)
                <div class="bg-white rounded-xl shadow-sm mb-6 border-l-4 border-purple-500 overflow-hidden">
                    <div class="p-6">
                        <!-- Header del Proyecto -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $proyecto->nombre }}</h3>
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-bold">
                                        {{ $proyecto->estadoTexto }}
                                    </span>
                                </div>
                                <p class="text-gray-700 mb-2">{{ $proyecto->descripcion }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                        </svg>
                                        Equipo: <strong>{{ $proyecto->equipo->nombre }}</strong>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        Entregado: <strong>{{ $proyecto->fecha_entrega->diffForHumans() }}</strong>
                                        ({{ $proyecto->fecha_entrega->format('d/m/Y H:i') }})
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/>
                                        </svg>
                                        Evento: <strong>{{ $proyecto->equipo->evento->nombre }}</strong>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Porcentaje Completado -->
                            <div class="text-center ml-6">
                                <div class="text-5xl font-bold text-purple-600">{{ $proyecto->porcentaje_completado }}%</div>
                                <p class="text-xs text-gray-500 mt-1">Completitud</p>
                            </div>
                        </div>

                        <!-- Links del Proyecto -->
                        <div class="flex flex-wrap gap-3 mb-4">
                            @if($proyecto->link_repositorio)
                                <a href="{{ $proyecto->link_repositorio }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    Repositorio
                                </a>
                            @endif

                            @if($proyecto->link_demo)
                                <a href="{{ $proyecto->link_demo }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Demo
                                </a>
                            @endif

                            @if($proyecto->link_presentacion)
                                <a href="{{ $proyecto->link_presentacion }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm11-1a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1V8a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Presentación
                                </a>
                            @endif
                        </div>

                        <!-- Estadísticas del Proyecto -->
                        <div class="grid grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-indigo-600">{{ $proyecto->equipo->participantes->count() }}</p>
                                <p class="text-xs text-gray-600">Miembros</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-pink-600">{{ $proyecto->tareas->count() }}</p>
                                <p class="text-xs text-gray-600">Tareas Totales</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $proyecto->tareas->where('estado', 'completada')->count() }}</p>
                                <p class="text-xs text-gray-600">Completadas</p>
                            </div>
                            <div class="text-center">
                                @php
                                    $porcentajeTareas = $proyecto->tareas->count() > 0 
                                        ? round(($proyecto->tareas->where('estado', 'completada')->count() / $proyecto->tareas->count()) * 100) 
                                        : 0;
                                @endphp
                                <p class="text-2xl font-bold text-purple-600">{{ $porcentajeTareas }}%</p>
                                <p class="text-xs text-gray-600">Progreso Tareas</p>
                            </div>
                        </div>

                        <!-- Validaciones -->
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Estado de Requisitos
                            </h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                @if($proyecto->cumpleRequisitosMinimos())
                                    <div class="col-span-2 flex items-center gap-2 text-green-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <strong>Todos los requisitos cumplidos</strong>
                                    </div>
                                @else
                                    <div class="col-span-2 mb-2">
                                        <p class="text-yellow-700 font-semibold mb-1">⚠️ Faltan requisitos:</p>
                                        <ul class="ml-6 space-y-1 text-yellow-600">
                                            @foreach($proyecto->requisitosFaltantes() as $faltante)
                                                <li>• {{ $faltante }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex gap-3">
                            @if($proyecto->cumpleRequisitosMinimos())
                                <form action="{{ route('admin.proyectos.aprobar', $proyecto) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('¿Aprobar este proyecto para evaluación?\n\nUna vez aprobado, los jueces podrán evaluarlo.')">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg">
                                        ✓ Aprobar para Evaluación
                                    </button>
                                </form>

                                <button onclick="toggleModalRechazar('{{ $proyecto->id }}')" 
                                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition">
                                    ✗ Rechazar
                                </button>
                            @else
                                <button onclick="toggleModalRechazar('{{ $proyecto->id }}')" 
                                        class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition">
                                    ✗ Rechazar (Requisitos Incompletos)
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Rechazar -->
                <div id="modalRechazar{{ $proyecto->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Rechazar Proyecto</h3>
                        
                        <form action="{{ route('admin.proyectos.rechazar', $proyecto) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del rechazo *</label>
                                <textarea name="motivo" 
                                          rows="4" 
                                          required
                                          maxlength="500"
                                          placeholder="Explica qué debe completar el equipo..."
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                                <p class="text-xs text-gray-500 mt-1">El equipo recibirá este mensaje y deberá completar lo que falta</p>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" 
                                        onclick="toggleModalRechazar('{{ $proyecto->id }}')"
                                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold">
                                    Rechazar Proyecto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <!-- Sin Proyectos Pendientes -->
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-600 mb-2">No hay proyectos pendientes</h3>
                    <p class="text-gray-500">Todos los proyectos entregados han sido revisados</p>
                </div>
            @endforelse

        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModalRechazar(proyectoId) {
            document.getElementById('modalRechazar' + proyectoId).classList.toggle('hidden');
        }

        // Cerrar modal al hacer click fuera
        document.querySelectorAll('[id^="modalRechazar"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
