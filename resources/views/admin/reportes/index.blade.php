<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reportes y Análisis</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Análisis detallado de participación y rendimiento</p>
                </div>
                
                <!-- Botones de Exportación -->
                <div class="flex gap-3">
                    <button onclick="exportarPDF()" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                        </svg>
                        Exportar PDF
                    </button>
                    <button onclick="exportarExcel()" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Exportar Excel
                    </button>
                </div>
            </div>

            <!-- Selector de Evento -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccionar Evento</label>
                <select id="evento-select" onchange="cargarDatos()" class="w-full md:w-1/2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                    <option value="">Todos los eventos</option>
                    @foreach(\App\Models\Evento::orderBy('created_at', 'desc')->get() as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tabs de Navegación -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <nav class="flex space-x-8">
                    <button id="tab-evento" class="border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 py-4 px-1 font-semibold text-sm">
                        Reporte del Evento
                    </button>
                </nav>
            </div>

            <!-- Loading -->
            <div id="loading" class="hidden text-center py-12">
                <svg class="animate-spin h-12 w-12 text-indigo-600 dark:text-indigo-400 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Cargando datos...</p>
            </div>

            <!-- Tab Content: Reporte del Evento -->
            <div id="content-evento" class="tab-content">
                
                <!-- KPIs Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Participantes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Participantes</h3>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-purple-600 dark:text-purple-400" id="kpi-participantes">0</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Registrados en el Evento</p>
                        </div>
                    </div>

                    <!-- Equipos Formados -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                <svg class="w-6 h-6 text-pink-600 dark:text-pink-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Equipos Formados</h3>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-pink-600 dark:text-pink-400" id="kpi-equipos">0</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="kpi-promedio-miembros">Promedio 0 miembros</p>
                        </div>
                    </div>

                    <!-- Tasa de Finalización -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Tasa de Finalización</h3>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-green-600 dark:text-green-400" id="kpi-tasa">0%</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="kpi-equipos-terminaron">0 equipos terminaron</p>
                        </div>
                    </div>

                    <!-- Puntuación Promedio -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Puntuación Promedio</h3>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400" id="kpi-puntuacion">0</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" id="kpi-puntuacion-max">Máximo: 0</p>
                        </div>
                    </div>
                </div>

                <!-- Gráficas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Participación por Carrera -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Participación por Carrera</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Distribución de participantes según su carrera</p>
                        <div id="chart-carreras" class="mt-4">
                            <!-- Se llenará con JavaScript -->
                        </div>
                    </div>

                    <!-- Distribución de Roles -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Distribución de Roles</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Roles más populares en los equipos</p>
                        <div id="chart-roles" class="mt-4">
                            <!-- Se llenará con JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de Equipos -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Estadísticas de Equipos</h3>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Formación y composición de equipos</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center justify-between p-4 bg-pink-50 dark:bg-pink-900/20 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Equipos Completos</p>
                                <p class="text-2xl font-bold text-pink-600 dark:text-pink-400" id="equipos-completos">0</p>
                            </div>
                            <div class="p-3 bg-pink-100 dark:bg-pink-900 rounded-lg">
                                <span class="text-3xl" id="badge-completos">🎯</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Equipos Incompletos</p>
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="equipos-incompletos">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <span class="text-3xl" id="badge-incompletos">⏳</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Tamaño Promedio</p>
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="tamano-promedio">0</p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <span class="text-3xl">👥</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        let eventoSeleccionado = '';

        // Cargar datos al inicio
        document.addEventListener('DOMContentLoaded', function() {
            cargarDatos();
        });

        // Cargar datos desde el servidor
        async function cargarDatos() {
            const select = document.getElementById('evento-select');
            eventoSeleccionado = select.value;
            
            // Mostrar loading
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('content-evento').classList.add('hidden');
            
            try {
                const response = await fetch(`/admin/reportes/datos?evento_id=${eventoSeleccionado}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success && data.error) {
                    console.error('Error del servidor:', data.error);
                    if (data.trace) {
                        console.error('Trace:', data.trace);
                    }
                    alert(`Error del servidor: ${data.error}`);
                    return;
                }
                
                // Actualizar KPIs
                actualizarKPIs(data.stats);
                
                // Actualizar gráficas
                actualizarGraficaCarreras(data.participacion_carrera);
                actualizarGraficaRoles(data.distribucion_roles);
                
                // Actualizar estadísticas de equipos
                actualizarEstadisticasEquipos(data.estadisticas_equipos);
                
            } catch (error) {
                console.error('Error completo:', error);
                alert(`Error al cargar los datos: ${error.message}`);
            } finally {
                // Ocultar loading
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('content-evento').classList.remove('hidden');
            }
        }

        // Actualizar KPIs
        function actualizarKPIs(stats) {
            document.getElementById('kpi-participantes').textContent = stats.total_participantes;
            document.getElementById('kpi-equipos').textContent = stats.equipos_formados;
            document.getElementById('kpi-promedio-miembros').textContent = `Promedio ${stats.promedio_miembros} miembros`;
            document.getElementById('kpi-tasa').textContent = `${stats.tasa_finalizacion}%`;
            document.getElementById('kpi-equipos-terminaron').textContent = `${stats.equipos_terminaron} equipos terminaron`;
            document.getElementById('kpi-puntuacion').textContent = stats.puntuacion_promedio;
            document.getElementById('kpi-puntuacion-max').textContent = `Máximo: ${stats.puntuacion_maxima}`;
        }

        // Actualizar gráfica de carreras
        function actualizarGraficaCarreras(carreras) {
            const container = document.getElementById('chart-carreras');
            
            if (!carreras || carreras.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay datos disponibles</p>';
                return;
            }
            
            let html = '<div class="space-y-4">';
            
            carreras.forEach(carrera => {
                html += `
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">${carrera.carrera}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">${carrera.total} estudiantes</span>
                                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">${carrera.porcentaje}%</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-500" style="width: ${carrera.porcentaje}%"></div>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        }

        // Actualizar gráfica de roles
        function actualizarGraficaRoles(roles) {
            const container = document.getElementById('chart-roles');
            
            if (!roles || roles.length === 0) {
                container.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay datos disponibles</p>';
                return;
            }
            
            let html = '<div class="space-y-3">';
            
            roles.forEach(rol => {
                html += `
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700 dark:text-gray-300">${rol.rol}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">${rol.total}</span>
                            <span class="text-sm font-bold text-purple-600 dark:text-purple-400">${rol.porcentaje}%</span>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        }

        // Actualizar estadísticas de equipos
        function actualizarEstadisticasEquipos(stats) {
            document.getElementById('equipos-completos').textContent = stats.completos;
            document.getElementById('equipos-incompletos').textContent = stats.incompletos;
            document.getElementById('tamano-promedio').textContent = stats.tamano_promedio;
        }

        // Exportar a PDF
        function exportarPDF() {
            window.location.href = `/admin/reportes/exportar-pdf?evento_id=${eventoSeleccionado}`;
        }

        // Exportar a Excel
        function exportarExcel() {
            window.location.href = `/admin/reportes/exportar-excel?evento_id=${eventoSeleccionado}`;
        }
    </script>
    @endpush
</x-app-layout>
