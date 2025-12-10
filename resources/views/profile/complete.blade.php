<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-white">
                    
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Completa tu perfil</h2>
                        <p class="text-gray-600 dark:text-gray-400 dark:text-gray-500">
                            Para participar en eventos, necesitamos algunos datos adicionales.
                        </p>
                    </div>

                    <!-- Formulario -->
                    <form method="POST" action="{{ route('profile.store-complete') }}" class="space-y-6">
                        @csrf

                        <!-- Carrera -->
                        <div>
                            <label for="carrera_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <select id="carrera_id" 
                                    name="carrera_id" 
                                    required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('carrera_id') border-red-500 @enderror">
                                <option value="">Selecciona tu carrera</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}" {{ old('carrera_id') == $carrera->id ? 'selected' : '' }}>
                                        {{ $carrera->nombre }} ({{ $carrera->clave }})
                                    </option>
                                @endforeach
                            </select>
                            @error('carrera_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Número de Control -->
                        <div>
                            <label for="no_control" class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">
                                Número de Control <span class="text-red-500">*</span>
                            </label>
                            <input id="no_control" 
                                   type="text" 
                                   name="no_control" 
                                   value="{{ old('no_control') }}"
                                   required
                                   placeholder="21310001"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('no_control') border-red-500 @enderror">
                            @error('no_control')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="semestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">
                                Semestre Actual <span class="text-red-500">*</span>
                            </label>
                            <select id="semestre" 
                                    name="semestre" 
                                    required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('semestre') border-red-500 @enderror">
                                <option value="">Selecciona tu semestre</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('semestre') == $i ? 'selected' : '' }}>
                                        {{ $i }}º Semestre
                                    </option>
                                @endfor
                            </select>
                            @error('semestre')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">
                                Teléfono <span class="text-gray-400 dark:text-gray-500 text-xs">(Opcional)</span>
                            </label>
                            <input id="telefono" 
                                   type="tel" 
                                   name="telefono" 
                                   value="{{ old('telefono') }}"
                                   placeholder="951 123 4567"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('telefono') border-red-500 @enderror">
                            @error('telefono')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Biografía -->
                        <div>
                            <label for="biografia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 dark:text-gray-600 mb-2">
                                Cuéntanos sobre ti <span class="text-gray-400 dark:text-gray-500 text-xs">(Opcional)</span>
                            </label>
                            <textarea id="biografia" 
                                      name="biografia" 
                                      rows="4"
                                      placeholder="Ejemplo: Apasionado por el desarrollo web, me gusta trabajar en equipo y resolver problemas complejos..."
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('biografia') border-red-500 @enderror">{{ old('biografia') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-500">Máximo 500 caracteres</p>
                            @error('biografia')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" 
                                    class="bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-200 shadow-lg hover:shadow-xl">
                                Completar Perfil
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
