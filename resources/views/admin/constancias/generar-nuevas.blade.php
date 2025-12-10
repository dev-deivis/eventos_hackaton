<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Generar Constancias</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Selecciona un evento y genera constancias para participantes o
                            ganadores</p>
                    </div>
                    <a href="{{ route('admin.constancias.index') }}"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-700 dark:text-white rounded-lg font-medium transition">
                        ← Volver
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 text-yellow-800 dark:text-yellow-300 px-4 py-3 rounded-lg">
                    {{ session('warning') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-8">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-8">
                        <button onclick="switchTab('individual')" id="tab-individual"
                            class="tab-button border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 py-4 px-1 font-semibold text-sm flex items-center gap-2">
                            <x-icons.document class="w-5 h-5" />
                            Constancia Individual
                        </button>
                        <button onclick="switchTab('lote')" id="tab-lote"
                            class="tab-button border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-4 px-1 font-semibold text-sm transition flex items-center gap-2">
                            <x-icons.document class="w-5 h-5" />
                            Generar en Lote
                        </button>
                        <button onclick="switchTab('ganadores')" id="tab-ganadores"
                            class="tab-button border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 py-4 px-1 font-semibold text-sm transition flex items-center gap-2">
                            <x-icons.trophy class="w-5 h-5" />
                            Ganadores Automático
                        </button>
                    </nav>
                </div>
            </div>

            <div id="content-individual" class="tab-content">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Generar Constancia Individual</h2>

                    <form action="{{ route('admin.constancias.generar-individual') }}" method="POST" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Evento *</label>
                            <select name="evento_id" id="evento_individual" required
                                onchange="cargarParticipantes(this.value, 'individual')"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un evento</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }}
                                        ({{ $evento->fecha_inicio->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipo de Constancia *</label>
                            <select name="tipo" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona el tipo</option>
                                <option value="participacion">📜 Participación</option>
                                <option value="primer_lugar">🥇 Primer Lugar</option>
                                <option value="segundo_lugar">🥈 Segundo Lugar</option>
                                <option value="tercer_lugar">🥉 Tercer Lugar</option>
                                <option value="mencion_honorifica">⭐ Mención Honorífica</option>
                            </select>
                        </div>

                        <div id="participante-container-individual" style="display: none;">
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Participante *</label>
                            <select name="participante_id" id="participante_individual" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un participante</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Solo se muestran participantes activos del evento</p>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.constancias.index') }}"
                                class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg">
                                Generar Constancia
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="content-lote" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Generar Constancias en Lote</h2>

                    <form action="{{ route('admin.constancias.generar-lote') }}" method="POST" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Evento *</label>
                            <select name="evento_id" id="evento_lote" required onchange="cargarDatosLote(this.value)"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un evento</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }}
                                        ({{ $evento->fecha_inicio->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tipo de Constancia *</label>
                            <select name="tipo" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona el tipo</option>
                                <option value="participacion">📜 Participación (Todos)</option>
                                <option value="primer_lugar">🥇 Primer Lugar</option>
                                <option value="segundo_lugar">🥈 Segundo Lugar</option>
                                <option value="tercer_lugar">🥉 Tercer Lugar</option>
                                <option value="mencion_honorifica">⭐ Mención Honorífica</option>
                            </select>
                        </div>

                        <div id="equipo-container-lote" style="display: none;">
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Filtrar por Equipo (Opcional)
                            </label>
                            <select name="equipo_id" id="equipo_lote"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Todos los equipos</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Deja en blanco para generar para todos los
                                participantes</p>
                        </div>

                        <div id="stats-container" class="hidden bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-2.5">
                            <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2 flex items-center gap-1.5 text-xs">
                                <x-icons.chart class="w-4 h-4" />
                                Vista Previa
                            </h3>
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div>
                                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400" id="stat-total">0</p>
                                    <p class="text-[10px] text-blue-700 dark:text-blue-300">Total</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-green-600 dark:text-green-400" id="stat-pendientes">0</p>
                                    <p class="text-[10px] text-green-700 dark:text-green-300">Sin Constancia</p>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-purple-600 dark:text-purple-400" id="stat-generadas">0</p>
                                    <p class="text-[10px] text-purple-700 dark:text-purple-300">Ya Generadas</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 dark:border-yellow-600 p-2.5 rounded">
                            <div class="flex gap-2">
                                <svg class="h-4 w-4 text-yellow-400 dark:text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs text-yellow-700 dark:text-yellow-300 leading-tight">
                                    <strong>Importante:</strong> Solo se generarán constancias para participantes activos que aún no tengan una de este tipo.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.constancias.index') }}"
                                class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg">
                                Generar Constancias en Lote
                            </button>
                        </div>
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

            <div id="content-ganadores" class="tab-content hidden">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">🏆 Declarar Ganadores Automático</h2>

                    <div class="mb-3 bg-purple-50 dark:bg-purple-900/30 border border-purple-200 dark:border-purple-700 rounded-lg p-2.5">
                        <div class="flex gap-2">
                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <div>
                                <p class="text-xs text-purple-900 dark:text-purple-300 font-medium mb-0.5">¿Cómo funciona?</p>
                                <p class="text-xs text-purple-700 dark:text-purple-400 leading-tight">
                                    El sistema automáticamente seleccionará los 3 equipos con mayor calificación promedio y generará constancias de 1er, 2do y 3er lugar.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.constancias.generar-ganadores-automatico') }}" method="POST"
                        class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">Evento *</label>
                            <select name="evento_id" id="evento_ganadores" required
                                onchange="cargarGanadores(this.value)"
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Selecciona un evento</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre }}
                                        ({{ $evento->fecha_inicio->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="ganadores-preview" class="hidden space-y-2">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-xs">Vista Previa de Ganadores:</h3>

                            <div id="ganadores-list"></div>

                            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg p-2">
                                <p class="text-xs text-green-800 dark:text-green-300">
                                    <strong>Total:</strong> Se generarán <span
                                        id="total-constancias-ganadores">0</span> constancias
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.constancias.index') }}"
                                class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition">
                                Cancelar
                            </a>
                            <button type="submit" id="btn-generar-ganadores" disabled
                                class="px-4 py-2 text-sm bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold transition shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                <x-icons.trophy class="w-4 h-4" />
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

            async function cargarDatosLote(eventoId) {
                if (!eventoId) {
                    document.getElementById('stats-container').classList.add('hidden');
                    document.getElementById('equipo-container-lote').style.display = 'none';
                    return;
                }

                try {
                    const statsResponse = await fetch(`/admin/constancias/estadisticas/${eventoId}`);
                    const stats = await statsResponse.json();

                    document.getElementById('stat-total').textContent = stats.total;
                    document.getElementById('stat-pendientes').textContent = stats.sinConstancia;
                    document.getElementById('stat-generadas').textContent = stats.conConstancia;
                    document.getElementById('stats-container').classList.remove('hidden');

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
