<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('equipos.show', $equipo) }}" 
                           class="text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                Editar Proyecto
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Equipo: {{ $equipo->nombre }} | Evento: {{ $equipo->evento->nombre }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <form method="POST" action="{{ route('proyectos.update', $equipo) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nombre del Proyecto -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Proyecto <span class="text-red-500">*</span>
                            </label>
                            <input id="nombre" 
                                   type="text" 
                                   name="nombre" 
                                   value="{{ old('nombre', $proyecto->nombre) }}"
                                   required
                                   placeholder="EduAI - Tutor Virtual Inteligente"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción del Proyecto <span class="text-red-500">*</span>
                            </label>
                            <textarea id="descripcion" 
                                      name="descripcion" 
                                      rows="6"
                                      required
                                      placeholder="Describe tu proyecto: ¿Qué problema resuelve? ¿Qué tecnologías usa? ¿Qué lo hace innovador?"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Máximo 1000 caracteres
                            </p>
                        </div>

                        <!-- Tecnologías -->
                        <div>
                            <label for="tecnologias" class="block text-sm font-medium text-gray-700 mb-2">
                                Tecnologías Utilizadas <span class="text-gray-400 text-xs">(Opcional)</span>
                            </label>
                            <input id="tecnologias" 
                                   type="text" 
                                   name="tecnologias" 
                                   value="{{ old('tecnologias', $proyecto->tecnologias) }}"
                                   placeholder="React, Node.js, MongoDB, Python, TensorFlow..."
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('tecnologias') border-red-500 @enderror">
                            @error('tecnologias')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Links -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Enlaces del Proyecto
                            </h3>

                            <!-- Repositorio -->
                            <div class="mb-4">
                                <label for="link_repositorio" class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Repositorio (GitHub, GitLab, etc.)
                                    </div>
                                </label>
                                <input id="link_repositorio" 
                                       type="url" 
                                       name="link_repositorio" 
                                       value="{{ old('link_repositorio', $proyecto->link_repositorio) }}"
                                       placeholder="https://github.com/usuario/proyecto"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('link_repositorio') border-red-500 @enderror">
                                @error('link_repositorio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Demo -->
                            <div class="mb-4">
                                <label for="link_demo" class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                        </svg>
                                        Demo en Vivo
                                    </div>
                                </label>
                                <input id="link_demo" 
                                       type="url" 
                                       name="link_demo" 
                                       value="{{ old('link_demo', $proyecto->link_demo) }}"
                                       placeholder="https://mi-proyecto-demo.com"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('link_demo') border-red-500 @enderror">
                                @error('link_demo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Presentación -->
                            <div>
                                <label for="link_presentacion" class="block text-sm font-medium text-gray-700 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                        Presentación / Pitch Deck
                                    </div>
                                </label>
                                <input id="link_presentacion" 
                                       type="url" 
                                       name="link_presentacion" 
                                       value="{{ old('link_presentacion', $proyecto->link_presentacion) }}"
                                       placeholder="https://docs.google.com/presentation/..."
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('link_presentacion') border-red-500 @enderror">
                                @error('link_presentacion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <span class="font-medium">Importante:</span>
                                    </p>
                                    <ul class="mt-2 text-sm text-yellow-600 list-disc list-inside space-y-1">
                                        <li>Los cambios se actualizarán automáticamente en el porcentaje de completitud</li>
                                        <li>Si tu proyecto está entregado, no podrás editarlo</li>
                                        <li>Asegúrate de completar todos los requisitos antes de entregar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('equipos.show', $equipo) }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200 shadow-lg hover:shadow-xl">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
