<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <svg class="w-8 h-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                        Notificaciones
                    </h1>
                    <p class="text-gray-600 mt-1">Todas tus notificaciones en un solo lugar</p>
                </div>
                
                @if($notificaciones->where('leida', false)->count() > 0)
                    <form action="{{ route('notificaciones.marcar-todas-leidas') }}" method="POST" 
                          onsubmit="return confirm('¿Marcar todas las notificaciones como leídas?')">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-medium">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Marcar todas como leídas
                        </button>
                    </form>
                @endif
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $notificaciones->total() }}</p>
                            <p class="text-sm text-gray-600">Total notificaciones</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $notificaciones->where('leida', false)->count() }}</p>
                            <p class="text-sm text-gray-600">No leídas</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $notificaciones->where('leida', true)->count() }}</p>
                            <p class="text-sm text-gray-600">Leídas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Notificaciones -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @forelse($notificaciones as $notificacion)
                    @php
                        $colorClasses = [
                            'solicitud_equipo' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-500', 'icon' => 'text-blue-600'],
                            'solicitud_aceptada' => ['bg' => 'bg-green-50', 'border' => 'border-green-500', 'icon' => 'text-green-600'],
                            'solicitud_rechazada' => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'icon' => 'text-red-600'],
                            'nuevo_miembro_equipo' => ['bg' => 'bg-indigo-50', 'border' => 'border-indigo-500', 'icon' => 'text-indigo-600'],
                            'mensaje_equipo' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-500', 'icon' => 'text-purple-600'],
                            'tarea_asignada' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-500', 'icon' => 'text-yellow-600'],
                            'tarea_completada' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-500', 'icon' => 'text-emerald-600'],
                            'evaluacion_recibida' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-500', 'icon' => 'text-orange-600'],
                            'proyecto_aprobado' => ['bg' => 'bg-green-50', 'border' => 'border-green-500', 'icon' => 'text-green-600'],
                            'proyecto_rechazado' => ['bg' => 'bg-red-50', 'border' => 'border-red-500', 'icon' => 'text-red-600'],
                            'nuevo_evento' => ['bg' => 'bg-pink-50', 'border' => 'border-pink-500', 'icon' => 'text-pink-600'],
                            'evento_proximo' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-500', 'icon' => 'text-yellow-600'],
                            'constancia_generada' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-500', 'icon' => 'text-amber-600'],
                            'miembro_abandono' => ['bg' => 'bg-gray-50', 'border' => 'border-gray-500', 'icon' => 'text-gray-600'],
                            'proyecto_entregado' => ['bg' => 'bg-indigo-50', 'border' => 'border-indigo-500', 'icon' => 'text-indigo-600'],
                            'nuevo_equipo' => ['bg' => 'bg-cyan-50', 'border' => 'border-cyan-500', 'icon' => 'text-cyan-600'],
                            'equipo_asignado' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-500', 'icon' => 'text-blue-600'],
                            'proyecto_listo' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-500', 'icon' => 'text-emerald-600'],
                        ];
                        
                        $colors = $colorClasses[$notificacion->tipo] ?? ['bg' => 'bg-gray-50', 'border' => 'border-gray-500', 'icon' => 'text-gray-600'];
                    @endphp
                    
                    <a href="{{ route('notificaciones.marcar-leida', $notificacion->id) }}" 
                       class="block border-l-4 {{ $colors['border'] }} {{ $notificacion->leida ? 'bg-white' : $colors['bg'] }} p-5 hover:shadow-md transition {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Icono -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 {{ $colors['bg'] }} rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 {{ $colors['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                                        @if(in_array($notificacion->tipo, ['solicitud_equipo', 'solicitud_aceptada', 'nuevo_miembro_equipo', 'nuevo_equipo']))
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                        @elseif(in_array($notificacion->tipo, ['tarea_asignada', 'tarea_completada']))
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        @elseif(in_array($notificacion->tipo, ['evaluacion_recibida']))
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        @elseif(in_array($notificacion->tipo, ['mensaje_equipo']))
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        @elseif(in_array($notificacion->tipo, ['constancia_generada']))
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                        @else
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Contenido -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-1">
                                    <p class="text-base font-bold text-gray-900">{{ $notificacion->titulo }}</p>
                                    @if(!$notificacion->leida)
                                        <span class="flex-shrink-0 w-3 h-3 bg-red-500 rounded-full"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-700 mb-2">{{ $notificacion->mensaje }}</p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $notificacion->created_at->diffForHumans() }}
                                    </span>
                                    @if($notificacion->leida)
                                        <span class="flex items-center gap-1 text-green-600">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Leída {{ $notificacion->leida_en->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Flecha -->
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-lg text-gray-500 font-medium">No tienes notificaciones</p>
                        <p class="text-sm text-gray-400 mt-1">Te avisaremos cuando haya algo nuevo</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            @if($notificaciones->hasPages())
                <div class="mt-6">
                    {{ $notificaciones->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
