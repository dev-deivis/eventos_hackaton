<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Generar Constancias</h1>
                        <p class="text-gray-600 mt-1">Selecciona un evento y genera constancias para participantes o ganadores</p>
                    </div>
                    <a href="{{ route('admin.constancias.index') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        ← Volver
                    </a>
                </div>
            </div>

            <!-- Mensajes -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8">
                        <button onclick="switchTab('individual')" 
                                id="tab-individual"
                                class="tab-button border-b-2 border-indigo-600 text-indigo-600 py-4 px-1 font-semibold text-sm flex items-center gap-2">
                            <x-icons.document class="w-5 h-5" />
                            Constancia Individual
                        </button>
                        <button onclick="switchTab('lote')" 
                                id="tab-lote"
                                class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition flex items-center gap-2">
                            <x-icons.document class="w-5 h-5" />
                            Generar en Lote
                        </button>
                        <button onclick="switchTab('ganadores')" 
                                id="tab-ganadores"
                                class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition flex items-center gap-2">
                            <x-icons.trophy class="w-5 h-5" />
                            Ganadores Automático
                        </button>
                    </nav>
                </div>
            </div>

            <!-- TAB 1: Constancia Individual -->
            <div id="content-individual" class="tab-content">
                <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Generar Constancia Individual</h2>
                    
                    <form action="{{ route('admin.constancias.generar-individual') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Seleccionar Evento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evento *</label>
                            <select name="evento_id" 
                                    id="evento_individual"
                                    required
                                    onchange="cargarParticipantes(this.value, 'individual')"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }} ({{ $evento->fecha_inicio->format('d/m/Y') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Constancia (UN SOLO SELECT) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Constancia *</label>
                            <select name="tipo" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona el tipo</option>
                                <option value="participacion">📜 Participación</option>
                                <option value="primer_lugar">🥇 Primer Lugar</option>
                                <option value="segundo_lugar">🥈 Segundo Lugar</option>
                                <option value="tercer_lugar">🥉 Tercer Lugar</option>
                                <option value="mencion_honorifica">⭐ Mención Honorífica</option>
                            </select>
                        </div>

                        <!-- Seleccionar Participante -->
                        <div id="participante-container-individual" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Participante *</label>
                            <select name="participante_id" 
                                    id="participante_individual"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un participante</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Solo se muestran participantes activos del evento</p>
                        </div>

                        <!-- Botón Generar -->
                        <div class="flex justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('admin.constancias.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg">
                                Generar Constancia
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 2: Constancias en Lote -->
            <div id="content-lote" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Generar Constancias en Lote</h2>
                    
                    <form action="{{ route('admin.constancias.generar-lote') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Seleccionar Evento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evento *</label>
                            <select name="evento_id" 
                                    id="evento_lote"
                                    required
                                    onchange="cargarDatosLote(this.value)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }} ({{ $evento->fecha_inicio->format('d/m/Y') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Constancia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Constancia *</label>
                            <select name="tipo" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona el tipo</option>
                                <option value="participacion">📜 Participación (Todos)</option>
                                <option value="primer_lugar">🥇 Primer Lugar</option>
                                <option value="segundo_lugar">🥈 Segundo Lugar</option>
                                <option value="tercer_lugar">🥉 Tercer Lugar</option>
                                <option value="mencion_honorifica">⭐ Mención Honorífica</option>
                            </select>
                        </div>

                        <!-- Filtrar por Equipo (NUEVO) -->
                        <div id="equipo-container-lote" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Filtrar por Equipo (Opcional)
                            </label>
                            <select name="equipo_id" 
                                    id="equipo_lote"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Todos los equipos</option>
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Deja en blanco para generar para todos los participantes</p>
                        </div>

                        <!-- Estadísticas del Evento -->
                        <div id="stats-container" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                <x-icons.chart class="w-5 h-5" />
                                Vista Previa
                            </h3>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-3xl font-bold text-blue-600" id="stat-total">0</p>
                                    <p class="text-sm text-blue-700">Total Participantes</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-bold text-green-600" id="stat-pendientes">0</p>
                                    <p class="text-sm text-green-700">Sin Constancia</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-bold text-purple-600" id="stat-generadas">0</p>
                                    <p class="text-sm text-purple-700">Ya Generadas</p>
                                </div>
                            </div>
                        </div>

                        <!-- Warning -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Importante:</strong> Solo se generarán constancias para participantes activos que aún no tengan una de este tipo.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Generar -->
                        <div class="flex justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('admin.constancias.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg">
                                Generar Constancias en Lote
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TAB 3: Ganadores Automático (NUEVO) -->
            <div id="content-ganadores" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">🏆 Declarar Ganadores Automático</h2>
                    
                    <div class="mb-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-6 w-6 text-purple-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-purple-900 font-medium">¿Cómo funciona?</p>
                                <p class="text-sm text-purple-700 mt-1">
                                    El sistema automáticamente seleccionará los 3 equipos con mayor calificación promedio de las evaluaciones
                                    y generará constancias de 1er, 2do y 3er lugar para todos sus miembros activos.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.constancias.generar-ganadores-automatico') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Seleccionar Evento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Evento *</label>
                            <select name="evento_id" 
                                    id="evento_ganadores"
                                    required
                                    onchange="cargarGanadores(this.value)"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un evento</option>
                                @foreach($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }} ({{ $evento->fecha_inicio->format('d/m/Y') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Preview de Ganadores -->
                        <div id="ganadores-preview" class="hidden space-y-4">
                            <h3 class="font-semibold text-gray-900">Vista Previa de Ganadores:</h3>
                            
                            <!-- Se llenará dinámicamente con JavaScript -->
                            <div id="ganadores-list"></div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-sm text-green-800">
                                    <strong>Total:</strong> Se generarán <span id="total-constancias-ganadores">0</span> constancias
                                </p>
                            </div>
                        </div>

                        <!-- Botón Generar -->
                        <div class="flex justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('admin.constancias.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    id="btn-generar-ganadores"
                                    disabled
                                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <x-icons.trophy class="w-5 h-5" />
                                Generar Constancias de Ganadores
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Switch entre tabs
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-indigo-600', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tab).classList.add('border-indigo-600', 'text-indigo-600');
        }

        // Cargar participantes por evento (para individual)
        async function cargarParticipantes(eventoId, context) {
            if (!eventoId) {
                document.getElementById('participante-container-' + context).style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`/admin/constancias/participantes/${eventoId}`);
                const data = await response.json();
                
                const select = document.getElementById('participante_' + context);
                select.innerHTML = '<option value="">Selecciona un participante</option>';
                
                data.forEach(participante => {
                    const option = document.createElement('option');
                    option.value = participante.id;
                    option.textContent = `${participante.user.name} - ${participante.user.email}`;
                    select.appendChild(option);
                });
                
                document.getElementById('participante-container-' + context).style.display = 'block';
            } catch (error) {
                console.error('Error cargando participantes:', error);
            }
        }

        // Cargar datos para lote (estadísticas + equipos)
        async function cargarDatosLote(eventoId) {
            if (!eventoId) {
                document.getElementById('stats-container').classList.add('hidden');
                document.getElementById('equipo-container-lote').style.display = 'none';
                return;
            }

            try {
                // Cargar estadísticas
                const statsResponse = await fetch(`/admin/constancias/estadisticas/${eventoId}`);
                const stats = await statsResponse.json();
                
                document.getElementById('stat-total').textContent = stats.total;
                document.getElementById('stat-pendientes').textContent = stats.sinConstancia;
                document.getElementById('stat-generadas').textContent = stats.conConstancia;
                document.getElementById('stats-container').classList.remove('hidden');

                // Cargar equipos
                const equiposResponse = await fetch(`/admin/constancias/equipos/${eventoId}`);
                const equipos = await equiposResponse.json();
                
                const selectEquipo = document.getElementById('equipo_lote');
                selectEquipo.innerHTML = '<option value="">Todos los equipos</option>';
                
                equipos.forEach(equipo => {
                    const option = document.createElement('option');
                    option.value = equipo.id;
                    option.textContent = `${equipo.nombre} (${equipo.participantes_count} miembros)`;
                    selectEquipo.appendChild(option);
                });
                
                document.getElementById('equipo-container-lote').style.display = 'block';
            } catch (error) {
                console.error('Error cargando datos:', error);
            }
        }

        // Cargar ganadores automáticos (NUEVO)
        async function cargarGanadores(eventoId) {
            const preview = document.getElementById('ganadores-preview');
            const list = document.getElementById('ganadores-list');
            const btnGenerar = document.getElementById('btn-generar-ganadores');

            if (!eventoId) {
                preview.classList.add('hidden');
                btnGenerar.disabled = true;
                return;
            }

            try {
                // Nota: Este endpoint necesitaría crearse para preview
                // Por ahora, simplemente habilitamos el botón
                preview.classList.remove('hidden');
                btnGenerar.disabled = false;

                list.innerHTML = `
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="text-gray-600">Se generarán constancias para los 3 equipos con mejor calificación.</p>
                        <p class="text-sm text-gray-500 mt-2">Haz clic en "Generar" para proceder.</p>
                    </div>
                `;

                document.getElementById('total-constancias-ganadores').textContent = '?';
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
    @endpush
</x-app-layout>
