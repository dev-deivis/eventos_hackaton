<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Generador de Constancias</h1>
                        <p class="text-gray-600 mt-1">Crear y gestionar certificados digitales verificables</p>
                    </div>
                </div>
            </div>

            <!-- Tabs de Navegación -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <a href="{{ route('admin.constancias.index') }}" 
                       class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Constancias Emitidas
                    </a>
                    <a href="{{ route('admin.constancias.plantillas') }}" 
                       class="border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Plantillas
                    </a>
                    <a href="{{ route('admin.constancias.generar-nuevas') }}" 
                       class="border-b-2 border-indigo-600 text-indigo-600 py-4 px-1 font-semibold text-sm">
                        Generar Nuevas
                    </a>
                </nav>
            </div>

            <!-- Formulario Principal -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                
                <!-- Título del formulario -->
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Generar Nueva Constancia</h3>
                    <p class="text-sm text-gray-600 mt-1">Crea una constancia individual personalizada</p>
                </div>

                <!-- Formulario -->
                <form action="{{ route('admin.constancias.generar-individual') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Destinatario -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Destinatario
                            </label>
                            <input type="text" 
                                   name="participante_nombre"
                                   placeholder="Nombre del destinatario"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('participante_nombre') border-red-500 @enderror">
                            @error('participante_nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Correo electrónico -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Correo electrónico
                            </label>
                            <input type="email" 
                                   name="participante_email"
                                   placeholder="correo@tecnm.mx"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('participante_email') border-red-500 @enderror">
                            @error('participante_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Evento -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Evento
                            </label>
                            <select name="evento_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('evento_id') border-red-500 @enderror">
                                <option value="">Seleccionar evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }} - {{ $evento->fecha_inicio->format('Y') }}</option>
                                @endforeach
                            </select>
                            @error('evento_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo de constancia -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tipo de constancia
                            </label>
                            <select name="tipo_constancia" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tipo_constancia') border-red-500 @enderror">
                                <option value="">Seleccionar tipo</option>
                                <option value="participacion">Participación</option>
                                <option value="ganador">Ganador</option>
                            </select>
                            @error('tipo_constancia')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notas adicionales -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Notas adicionales
                            </label>
                            <textarea name="notas" 
                                      rows="3"
                                      placeholder="Información adicional para incluir en la constancia..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('notas') border-red-500 @enderror"></textarea>
                            @error('notas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botón de generar -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                            </svg>
                            Generar Constancia
                        </button>
                    </div>
                </form>
            </div>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-gray-50 text-gray-500 font-semibold">O genera en lote</span>
                </div>
            </div>

            <!-- Generar en Lote -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                
                <!-- Título -->
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Generar Constancias en Lote</h3>
                    <p class="text-sm text-gray-600 mt-1">Genera constancias automáticamente para todos los participantes de un evento</p>
                </div>

                <!-- Formulario de lote -->
                <form action="{{ route('admin.constancias.generar-lote') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Evento -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Evento
                            </label>
                            <select name="evento_id" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Seleccionar evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">
                                        {{ $evento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de constancia -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tipo de constancia
                            </label>
                            <select name="tipo_constancia" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Seleccionar tipo</option>
                                <option value="participacion">Participación (todos los participantes)</option>
                                <option value="ganador">Ganador (solo equipos ganadores)</option>
                            </select>
                        </div>

                        <!-- Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900">Información importante</p>
                                    <p class="text-sm text-blue-700 mt-1">Se generarán constancias solo para participantes que no tengan ya una constancia del tipo seleccionado para este evento.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de generar en lote -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            Generar Constancias en Lote
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                Hubo un error al generar la constancia
            </div>
        </div>
    @endif
</x-app-layout>
