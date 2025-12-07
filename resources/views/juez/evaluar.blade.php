<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('juez.dashboard') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Volver al Dashboard
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Evaluación de Equipo</h1>
                <p class="text-gray-600 mt-1">Califica cada criterio según el desempeño del equipo</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Izquierda - Info del Equipo -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-indigo-100 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $equipo->nombre }}</h2>
                                <p class="text-sm text-gray-600">{{ $equipo->proyecto->nombre ?? 'Sin proyecto' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 mt-6">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Evento</span>
                                <span class="text-sm text-gray-900">{{ $equipo->evento->nombre }}</span>
                            </div>
                        </div>

                        <!-- Links del Proyecto -->
                        @if($equipo->proyecto)
                            <div class="mt-6">
                                <h3 class="text-sm font-bold text-gray-900 mb-3">Enlaces del Proyecto</h3>
                                <div class="space-y-2">
                                    @if($equipo->proyecto->link_repositorio)
                                        <a href="{{ $equipo->proyecto->link_repositorio }}" 
                                           target="_blank"
                                           class="flex items-center gap-3 p-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1">
                                                <div class="text-sm font-semibold">Repositorio GitHub</div>
                                                <div class="text-xs opacity-75">Ver código fuente</div>
                                            </div>
                                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if($equipo->proyecto->link_demo)
                                        <a href="{{ $equipo->proyecto->link_demo }}" 
                                           target="_blank"
                                           class="flex items-center gap-3 p-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                            </svg>
                                            <div class="flex-1">
                                                <div class="text-sm font-semibold">Video Demo</div>
                                                <div class="text-xs opacity-75">Ver demostración</div>
                                            </div>
                                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if($equipo->proyecto->link_presentacion)
                                        <a href="{{ $equipo->proyecto->link_presentacion }}" 
                                           target="_blank"
                                           class="flex items-center gap-3 p-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition group">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                            </svg>
                                            <div class="flex-1">
                                                <div class="text-sm font-semibold">Presentación</div>
                                                <div class="text-xs opacity-75">Ver diapositivas</div>
                                            </div>
                                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(!$equipo->proyecto->link_repositorio && !$equipo->proyecto->link_demo && !$equipo->proyecto->link_presentacion)
                                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                            <p class="text-sm text-yellow-800">
                                                <span class="font-semibold">⚠️ Sin enlaces:</span> El equipo no ha agregado links del proyecto.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mt-6">
                            <h3 class="text-sm font-bold text-gray-900 mb-3">Miembros del Equipo</h3>
                            <div class="space-y-2">
                                @foreach($equipo->participantes as $participante)
                                    <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-bold text-indigo-600">
                                                {{ strtoupper(substr($participante->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $participante->user->name }}</div>
                                            @if($participante->pivot && $participante->pivot->perfil_id)
                                                <div class="text-xs text-gray-500">
                                                    {{ \App\Models\Perfil::find($participante->pivot->perfil_id)->nombre ?? 'Sin perfil' }}
                                                </div>
                                            @endif
                                        </div>
                                        @if($equipo->lider_id == $participante->id)
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded">
                                                Líder
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Puntuación Final -->
                        <div class="mt-6 p-4 bg-indigo-50 rounded-xl border-2 border-indigo-200">
                            <div class="text-center">
                                <div class="text-sm font-medium text-indigo-600 mb-1">Puntuación Final</div>
                                <div class="text-5xl font-bold text-indigo-700" id="puntuacion-final">0</div>
                                <div class="text-xs text-indigo-600 mt-1">Puntos</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha - Formulario de Evaluación -->
                <div class="lg:col-span-2">
                    <form action="{{ route('juez.guardar-evaluacion', $equipo) }}" method="POST" id="form-evaluacion" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        @csrf

                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">Criterios de Evaluación</h2>
                            <p class="text-sm text-gray-600">Califica cada criterio del 0 al 100. La puntuación final se calcula automáticamente.</p>
                        </div>

                        <div class="space-y-8">
                            <!-- Implementación Técnica -->
                            <div class="criterion-group">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="bg-purple-100 p-2 rounded-lg mt-1">
                                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">Implementación Técnica</h3>
                                        <p class="text-xs text-gray-600 mt-1">30% • Calidad del código, arquitectura y funcionalidad</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-pink-600" data-score="implementacion">75</div>
                                        <div class="text-xs text-gray-500">Puntos</div>
                                    </div>
                                </div>
                                <input type="range" 
                                       name="implementacion" 
                                       min="0" 
                                       max="100" 
                                       value="75" 
                                       class="criterion-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-purple"
                                       data-target="implementacion">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0</span>
                                    <span>50</span>
                                    <span>100</span>
                                </div>
                                @error('implementacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Innovación -->
                            <div class="criterion-group">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="bg-blue-100 p-2 rounded-lg mt-1">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">Innovación</h3>
                                        <p class="text-xs text-gray-600 mt-1">25% • Originalidad y creatividad de la solución</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-pink-600" data-score="innovacion">80</div>
                                        <div class="text-xs text-gray-500">Puntos</div>
                                    </div>
                                </div>
                                <input type="range" 
                                       name="innovacion" 
                                       min="0" 
                                       max="100" 
                                       value="80" 
                                       class="criterion-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-blue"
                                       data-target="innovacion">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0</span>
                                    <span>50</span>
                                    <span>100</span>
                                </div>
                                @error('innovacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Presentación -->                            <!-- Presentación -->
                            <div class="criterion-group">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="bg-green-100 p-2 rounded-lg mt-1">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">Presentación</h3>
                                        <p class="text-xs text-gray-600 mt-1">20% • Claridad y efectividad de la presentación</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-pink-600" data-score="presentacion">70</div>
                                        <div class="text-xs text-gray-500">Puntos</div>
                                    </div>
                                </div>
                                <input type="range" 
                                       name="presentacion" 
                                       min="0" 
                                       max="100" 
                                       value="70" 
                                       class="criterion-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-green"
                                       data-target="presentacion">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0</span>
                                    <span>50</span>
                                    <span>100</span>
                                </div>
                                @error('presentacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Trabajo en Equipo -->
                            <div class="criterion-group">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="bg-yellow-100 p-2 rounded-lg mt-1">
                                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">Trabajo en Equipo</h3>
                                        <p class="text-xs text-gray-600 mt-1">15% • Colaboración y distribución de roles</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-pink-600" data-score="trabajo_equipo">85</div>
                                        <div class="text-xs text-gray-500">Puntos</div>
                                    </div>
                                </div>
                                <input type="range" 
                                       name="trabajo_equipo" 
                                       min="0" 
                                       max="100" 
                                       value="85" 
                                       class="criterion-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-yellow"
                                       data-target="trabajo_equipo">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0</span>
                                    <span>50</span>
                                    <span>100</span>
                                </div>
                                @error('trabajo_equipo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Viabilidad de Negocio -->
                            <div class="criterion-group">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg mt-1">
                                        <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900">Viabilidad de Negocio</h3>
                                        <p class="text-xs text-gray-600 mt-1">10% • Potencial comercial y análisis de mercado</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-pink-600" data-score="viabilidad">65</div>
                                        <div class="text-xs text-gray-500">Puntos</div>
                                    </div>
                                </div>
                                <input type="range" 
                                       name="viabilidad" 
                                       min="0" 
                                       max="100" 
                                       value="65" 
                                       class="criterion-slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-indigo"
                                       data-target="viabilidad">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0</span>
                                    <span>50</span>
                                    <span>100</span>
                                </div>
                                @error('viabilidad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Comentarios y retroalimentación -->
                        <div class="mt-8">
                            <label for="comentarios" class="block text-sm font-bold text-gray-900 mb-2">
                                Comentarios y retroalimentación
                            </label>
                            <textarea name="comentarios" 
                                      id="comentarios" 
                                      rows="4" 
                                      placeholder="Proporciona comentarios constructivos sobre el proyecto, fortalezas identificadas, áreas de mejora y recomendaciones..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ old('comentarios') }}</textarea>
                            @error('comentarios')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="mt-8 flex gap-4">
                            <a href="{{ route('juez.dashboard') }}" 
                               class="px-6 py-3 bg-white hover:bg-gray-50 border-2 border-gray-300 text-gray-700 rounded-lg font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-lg transition shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Enviar Evaluación
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.criterion-slider');
            const puntuacionFinal = document.getElementById('puntuacion-final');
            
            // Pesos de cada criterio (deben sumar 1.0)
            const pesos = {
                'implementacion': 0.30,
                'innovacion': 0.25,
                'presentacion': 0.20,
                'trabajo_equipo': 0.15,
                'viabilidad': 0.10
            };
            
            function calcularPuntuacionFinal() {
                let total = 0;
                
                sliders.forEach(slider => {
                    const criterio = slider.getAttribute('data-target');
                    const valor = parseInt(slider.value);
                    const peso = pesos[criterio] || 0;
                    total += valor * peso;
                });
                
                puntuacionFinal.textContent = Math.round(total);
            }
            
            // Actualizar display y calcular total cuando cambia un slider
            sliders.forEach(slider => {
                const target = slider.getAttribute('data-target');
                const display = document.querySelector(`[data-score="${target}"]`);
                
                slider.addEventListener('input', function() {
                    display.textContent = this.value;
                    calcularPuntuacionFinal();
                });
            });
            
            // Calcular puntuación inicial
            calcularPuntuacionFinal();
        });
    </script>
    
    <style>
        /* Estilos para los sliders */
        .criterion-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ec4899;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .criterion-slider::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ec4899;
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .slider-purple::-webkit-slider-thumb {
            background: #9333ea;
        }
        .slider-blue::-webkit-slider-thumb {
            background: #3b82f6;
        }
        .slider-green::-webkit-slider-thumb {
            background: #10b981;
        }
        .slider-yellow::-webkit-slider-thumb {
            background: #f59e0b;
        }
        .slider-indigo::-webkit-slider-thumb {
            background: #6366f1;
        }
        
        .slider-purple::-moz-range-thumb {
            background: #9333ea;
        }
        .slider-blue::-moz-range-thumb {
            background: #3b82f6;
        }
        .slider-green::-moz-range-thumb {
            background: #10b981;
        }
        .slider-yellow::-moz-range-thumb {
            background: #f59e0b;
        }
        .slider-indigo::-moz-range-thumb {
            background: #6366f1;
        }
    </style>
    @endpush
</x-app-layout>
