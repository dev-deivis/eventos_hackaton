<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header con botón volver -->
            <div class="mb-6">
                <a href="{{ route('admin.proyectos.pendientes') }}" 
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Proyectos Pendientes
                </a>
            </div>

            <!-- Información del Equipo y Evento -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $proyecto->nombre }}</h1>
                            <p class="text-gray-600 mt-1">Equipo: {{ $proyecto->equipo->nombre }}</p>
                            <p class="text-gray-500 text-sm">Evento: {{ $proyecto->equipo->evento->nombre }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-4 py-2 bg-{{ $proyecto->estadoColor }}-100 text-{{ $proyecto->estadoColor }}-700 rounded-full text-sm font-bold">
                                {{ $proyecto->estadoTexto }}
                            </span>
                            <p class="text-sm text-gray-600 mt-2">
                                Entregado: {{ $proyecto->fecha_entrega->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Líder del Equipo -->
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Líder del Equipo</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($proyecto->equipo->lider->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $proyecto->equipo->lider->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $proyecto->equipo->lider->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Miembros del Equipo -->
                    <div class="border-t mt-4 pt-4">
                        <h3 class="font-semibold text-gray-900 mb-2">
                            Miembros del Equipo ({{ $proyecto->equipo->participantes->count() }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($proyecto->equipo->participantes as $miembro)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($miembro->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $miembro->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $miembro->user->email }}</p>
                                    </div>
                                    @if($proyecto->equipo->lider_id == $miembro->id)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Líder</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción del Proyecto -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Descripción del Proyecto</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $proyecto->descripcion }}</p>
                    
                    @if($proyecto->tecnologias)
                        <div class="mt-4 pt-4 border-t">
                            <h3 class="font-semibold text-gray-900 mb-2">Tecnologías Utilizadas</h3>
                            <p class="text-gray-700">{{ $proyecto->tecnologias }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enlaces del Proyecto -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Enlaces del Proyecto</h2>
                    <div class="space-y-3">
                        @if($proyecto->link_repositorio)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Repositorio</p>
                                    <a href="{{ $proyecto->link_repositorio }}" target="_blank" 
                                       class="text-sm text-indigo-600 hover:text-indigo-700 break-all">
                                        {{ $proyecto->link_repositorio }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($proyecto->link_demo)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Demo en Vivo</p>
                                    <a href="{{ $proyecto->link_demo }}" target="_blank" 
                                       class="text-sm text-indigo-600 hover:text-indigo-700 break-all">
                                        {{ $proyecto->link_demo }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($proyecto->link_presentacion)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Presentación</p>
                                    <a href="{{ $proyecto->link_presentacion }}" target="_blank" 
                                       class="text-sm text-indigo-600 hover:text-indigo-700 break-all">
                                        {{ $proyecto->link_presentacion }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tareas del Proyecto -->
            @if($proyecto->tareas->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">
                        Tareas del Proyecto ({{ $proyecto->tareas->where('estado', 'completada')->count() }}/{{ $proyecto->tareas->count() }} completadas)
                    </h2>
                    <div class="space-y-3">
                        @foreach($proyecto->tareas as $tarea)
                            <div class="border rounded-lg p-4 {{ $tarea->estado === 'completada' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        @if($tarea->estado === 'completada')
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-11a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm0 3a1 1 0 011 1v4a1 1 0 11-2 0v-4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        <h3 class="font-semibold text-gray-900">{{ $tarea->nombre }}</h3>
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $tarea->estado === 'completada' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                        {{ $tarea->estado === 'completada' ? 'Completada' : 'Pendiente' }}
                                    </span>
                                </div>
                                @if($tarea->descripcion)
                                    <p class="text-sm text-gray-600 mb-2">{{ $tarea->descripcion }}</p>
                                @endif
                                @if($tarea->participantes->count() > 0)
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs text-gray-600">Asignado a:</span>
                                        @foreach($tarea->participantes as $participante)
                                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs">
                                                {{ $participante->user->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Porcentaje de Completitud -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Completitud del Proyecto</h2>
                    <div class="flex items-center gap-4 mb-2">
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                <div class="h-4 bg-gradient-to-r from-green-500 to-green-600 rounded-full transition-all" 
                                     style="width: {{ $proyecto->porcentaje_completado }}%"></div>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $proyecto->porcentaje_completado }}%</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Todos los requisitos han sido cumplidos
                    </p>
                </div>
            </div>

            <!-- Acciones de Aprobación/Rechazo -->
            @if($proyecto->estado === 'entregado')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Acciones</h2>
                    <div class="flex gap-4">
                        <!-- Botón Aprobar -->
                        <form action="{{ route('admin.proyectos.aprobar', $proyecto) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('¿Estás seguro de aprobar este proyecto? Una vez aprobado, quedará listo para ser evaluado por los jueces.')">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Aprobar Proyecto
                            </button>
                        </form>

                        <!-- Botón Rechazar -->
                        <button onclick="toggleModalRechazar()" 
                                class="flex-1 px-6 py-4 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Rechazar Proyecto
                        </button>
                    </div>

                    <p class="text-center text-sm text-gray-600 mt-4">
                        <strong>Importante:</strong> Si rechazas el proyecto, especifica los motivos para que el equipo pueda corregir.
                    </p>
                </div>
            </div>
            @else
            <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-6 text-center">
                <p class="text-gray-700 font-medium">
                    Este proyecto ya fue procesado. Estado actual: <span class="font-bold">{{ $proyecto->estadoTexto }}</span>
                </p>
            </div>
            @endif

        </div>
    </div>

    <!-- Modal Rechazar Proyecto -->
    <div id="modalRechazar" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-lg w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Rechazar Proyecto</h3>
            
            <form method="POST" action="{{ route('admin.proyectos.rechazar', $proyecto) }}">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del Rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea name="motivo_rechazo" 
                              rows="5" 
                              required
                              placeholder="Explica claramente qué debe corregir el equipo..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"></textarea>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                    <p class="text-sm text-yellow-700">
                        <strong>Importante:</strong> El equipo recibirá este mensaje y podrá corregir su proyecto antes de volver a entregarlo.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="toggleModalRechazar()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirmar Rechazo
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleModalRechazar() {
            document.getElementById('modalRechazar').classList.toggle('hidden');
        }

        document.getElementById('modalRechazar')?.addEventListener('click', function(e) {
            if (e.target === this) toggleModalRechazar();
        });
    </script>
    @endpush
</x-app-layout>
