<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Reportes y Análisis</h1>
                <p class="text-gray-600 mt-1">Análisis detallado de participación y rendimiento</p>
            </div>

            <!-- Selector de Evento -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Evento</label>
                <select id="evento-select" class="w-full md:w-1/2 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                    <option value="">Todos los eventos</option>
                    @foreach(\App\Models\Evento::orderBy('created_at', 'desc')->get() as $evento)
                        <option value="{{ $evento->id }}">{{ $evento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tabs de Navegación -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <button onclick="switchTab('evento')" id="tab-evento" class="tab-button border-b-2 border-indigo-600 text-indigo-600 py-4 px-1 font-semibold text-sm">
                        Reporte del Evento
                    </button>
                    <button onclick="switchTab('historicos')" id="tab-historicos" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Análisis Históricos
                    </button>
                    <button onclick="switchTab('exportaciones')" id="tab-exportaciones" class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 font-semibold text-sm transition">
                        Exportaciones
                    </button>
                </nav>
            </div>

            <!-- Tab Content: Reporte del Evento -->
            <div id="content-evento" class="tab-content">
                
                <!-- KPIs Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Participantes -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600">Total Participantes</h3>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-purple-600" id="kpi-participantes">{{ \App\Models\Participante::count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Registrados en el Evento</p>
                        </div>
                    </div>

                    <!-- Equipos Formados -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-pink-100 rounded-lg">
                                <svg class="w-6 h-6 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600">Equipos Formados</h3>
                        </div>
                        <div>
                            <p class="text-4xl font-bold text-pink-600" id="kpi-equipos">{{ \App\Models\Equipo::count() }}</p>
                            <p class="text-xs text-gray-500 mt-1" id="kpi-promedio-miembros">Promedio 4.5 miembros</p>
                        </div>
                    </div>

                    <!-- Tasa de Finalización -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600">Tasa de Finalización</h3>
                        </div>
                        <div>
                            @php
                                $equiposConProyecto = \App\Models\Equipo::has('proyecto')->count();
                                $totalEquipos = \App\Models\Equipo::count();
                                $tasa = $totalEquipos > 0 ? round(($equiposConProyecto / $totalEquipos) * 100, 1) : 0;
                            @endphp
                            <p class="text-4xl font-bold text-green-600" id="kpi-tasa">{{ $tasa }}%</p>
                            <p class="text-xs text-gray-500 mt-1" id="kpi-equipos-terminados">{{ $equiposConProyecto }} equipos terminaron</p>
                        </div>
                    </div>

                    <!-- Puntuación Promedio -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-600">Puntuación Promedio</h3>
                        </div>
                        <div>
                            @php
                                $promedio = \App\Models\Evaluacion::avg('calificacion_total') ?? 0;
                                $maximo = \App\Models\Evaluacion::max('calificacion_total') ?? 100;
                            @endphp
                            <p class="text-4xl font-bold text-yellow-600" id="kpi-promedio">{{ round($promedio, 1) }}</p>
                            <p class="text-xs text-gray-500 mt-1" id="kpi-maximo">Máximo: {{ round($maximo, 1) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Gráficas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    
                    <!-- Participación por Carrera -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="font-bold text-gray-900">Participación por Carrera</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Distribución de participantes según su carrera</p>
                        <div class="relative" style="height: 300px;">
                            <canvas id="chart-carreras"></canvas>
                        </div>
                    </div>

                    <!-- Distribución de Roles -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            <h3 class="font-bold text-gray-900">Distribución de Roles</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Roles más populares en los equipos</p>
                        <div class="relative" style="height: 300px;">
                            <canvas id="chart-roles"></canvas>
                        </div>
                    </div>

                </div>

                <!-- Estadísticas de Equipos -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <h3 class="font-bold text-gray-900">Estadísticas de Equipos</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-6">Formación y composición de equipos</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-sm text-purple-700 font-medium mb-2">Equipos Completos</p>
                            <p class="text-3xl font-bold text-purple-600" id="stat-completos">
                                {{ \App\Models\Equipo::whereRaw('(SELECT COUNT(*) FROM equipo_participante WHERE equipo_id = equipos.id AND estado = "activo") >= 3')->count() }}
                            </p>
                            <div class="mt-2 h-2 bg-purple-200 rounded-full overflow-hidden">
                                @php
                                    $completos = \App\Models\Equipo::whereRaw('(SELECT COUNT(*) FROM equipo_participante WHERE equipo_id = equipos.id AND estado = "activo") >= 3')->count();
                                    $totalEq = \App\Models\Equipo::count();
                                    $porcentajeCompletos = $totalEq > 0 ? ($completos / $totalEq * 100) : 0;
                                @endphp
                                <div class="h-full bg-purple-600" style="width: {{ $porcentajeCompletos }}%"></div>
                            </div>
                        </div>

                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700 font-medium mb-2">Equipos Incompletos</p>
                            <p class="text-3xl font-bold text-blue-600" id="stat-incompletos">
                                {{ \App\Models\Equipo::whereRaw('(SELECT COUNT(*) FROM equipo_participante WHERE equipo_id = equipos.id AND estado = "activo") < 3')->count() }}
                            </p>
                            <div class="mt-2 h-2 bg-blue-200 rounded-full overflow-hidden">
                                @php
                                    $incompletos = \App\Models\Equipo::whereRaw('(SELECT COUNT(*) FROM equipo_participante WHERE equipo_id = equipos.id AND estado = "activo") < 3')->count();
                                    $porcentajeIncompletos = $totalEq > 0 ? ($incompletos / $totalEq * 100) : 0;
                                @endphp
                                <div class="h-full bg-blue-600" style="width: {{ $porcentajeIncompletos }}%"></div>
                            </div>
                        </div>

                        <div class="text-center p-4 bg-indigo-50 rounded-lg">
                            <p class="text-sm text-indigo-700 font-medium mb-2">Tamaño Promedio</p>
                            <p class="text-3xl font-bold text-indigo-600" id="stat-promedio">
                                @php
                                    $promedioMiembros = \DB::table('equipo_participante')
                                        ->where('estado', 'activo')
                                        ->groupBy('equipo_id')
                                        ->selectRaw('COUNT(*) as total')
                                        ->get()
                                        ->avg('total');
                                @endphp
                                {{ number_format($promedioMiembros ?? 0, 1) }} miembros
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tab Content: Análisis Históricos -->
            <div id="content-historicos" class="tab-content hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl shadow-lg p-12 text-center text-white">
                    <svg class="w-20 h-20 mx-auto mb-6 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h2 class="text-3xl font-bold mb-3">📊 Análisis Históricos</h2>
                    <p class="text-xl mb-6 opacity-90">Comparación entre eventos y tendencias temporales</p>
                    <p class="text-sm opacity-75">Disponible próximamente</p>
                </div>
            </div>

            <!-- Tab Content: Exportaciones -->
            <div id="content-exportaciones" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Exportar a Excel</h3>
                        <p class="text-gray-600 mb-6">Descarga todos los datos en formato Excel</p>
                        <button class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                            Descargar Excel
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Exportar a PDF</h3>
                        <p class="text-gray-600 mb-6">Genera un reporte completo en PDF</p>
                        <button class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                            Descargar PDF
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        // Función para cambiar tabs
        function switchTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remover clase activa de todos los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-indigo-600', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Mostrar contenido seleccionado
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            
            // Activar botón seleccionado
            const activeButton = document.getElementById(`tab-${tabName}`);
            activeButton.classList.add('border-indigo-600', 'text-indigo-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500');
        }

        // Datos para gráficas
        let chartCarreras, chartRoles;

        // Inicializar gráficas al cargar
        document.addEventListener('DOMContentLoaded', function() {
            cargarDatosReportes();
        });

        // Cambiar evento
        document.getElementById('evento-select').addEventListener('change', function() {
            cargarDatosReportes(this.value);
        });

        async function cargarDatosReportes(eventoId = '') {
            try {
                const url = eventoId 
                    ? `/admin/reportes/datos?evento_id=${eventoId}`
                    : '/admin/reportes/datos';
                
                console.log('Cargando datos desde:', url);
                
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Datos recibidos:', data);
                
                // Actualizar KPIs
                document.getElementById('kpi-participantes').textContent = data.kpis.participantes;
                document.getElementById('kpi-equipos').textContent = data.kpis.equipos;
                document.getElementById('kpi-tasa').textContent = data.kpis.tasa_finalizacion + '%';
                document.getElementById('kpi-promedio').textContent = data.kpis.puntuacion_promedio;
                document.getElementById('kpi-equipos-terminados').textContent = data.kpis.equipos_terminados + ' equipos terminaron';
                document.getElementById('kpi-promedio-miembros').textContent = 'Promedio ' + data.kpis.promedio_miembros + ' miembros';
                document.getElementById('kpi-maximo').textContent = 'Máximo: ' + data.kpis.puntuacion_maxima;
                
                // Actualizar estadísticas de equipos
                document.getElementById('stat-completos').textContent = data.estadisticas_equipos.completos;
                document.getElementById('stat-incompletos').textContent = data.estadisticas_equipos.incompletos;
                document.getElementById('stat-promedio').textContent = data.estadisticas_equipos.promedio + ' miembros';
                
                // Actualizar gráficas
                if (data.carreras && data.carreras.length > 0) {
                    console.log('Actualizando gráfica de carreras:', data.carreras);
                    actualizarGraficaCarreras(data.carreras);
                } else {
                    console.warn('No hay datos de carreras');
                }
                
                if (data.roles && data.roles.length > 0) {
                    console.log('Actualizando gráfica de roles:', data.roles);
                    actualizarGraficaRoles(data.roles);
                } else {
                    console.warn('No hay datos de roles');
                }
                
            } catch (error) {
                console.error('Error al cargar datos:', error);
                alert('Error al cargar datos: ' + error.message);
            }
        }

        function actualizarGraficaCarreras(datos) {
            const ctx = document.getElementById('chart-carreras');
            
            if (!ctx) {
                console.error('Canvas chart-carreras no encontrado');
                return;
            }
            
            if (!window.Chart) {
                console.error('Chart.js no está cargado');
                return;
            }
            
            if (!datos || datos.length === 0) {
                console.warn('No hay datos para gráfica de carreras');
                return;
            }
            
            console.log('Creando gráfica de carreras con datos:', datos);
            
            if (chartCarreras) {
                chartCarreras.destroy();
            }
            
            try {
                chartCarreras = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: datos.map(d => d.nombre),
                        datasets: [{
                            label: 'Estudiantes',
                            data: datos.map(d => d.total),
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(251, 146, 60, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                            ],
                            borderColor: [
                                'rgb(99, 102, 241)',
                                'rgb(236, 72, 153)',
                                'rgb(251, 146, 60)',
                                'rgb(34, 197, 94)',
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        const dataset = context.dataset.data;
                                        const total = dataset.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed.y / total) * 100).toFixed(1);
                                        return percentage + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 5
                                }
                            }
                        }
                    }
                });
                
                console.log('Gráfica de carreras creada exitosamente');
            } catch (error) {
                console.error('Error al crear gráfica de carreras:', error);
            }
        }

        function actualizarGraficaRoles(datos) {
            const ctx = document.getElementById('chart-roles');
            
            if (!ctx) {
                console.error('Canvas chart-roles no encontrado');
                return;
            }
            
            if (!window.Chart) {
                console.error('Chart.js no está cargado');
                return;
            }
            
            if (!datos || datos.length === 0) {
                console.warn('No hay datos para gráfica de roles');
                return;
            }
            
            console.log('Creando gráfica de roles con datos:', datos);
            
            if (chartRoles) {
                chartRoles.destroy();
            }
            
            try {
                chartRoles = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: datos.map(d => d.rol),
                        datasets: [{
                            label: 'Participantes',
                            data: datos.map(d => d.total),
                            backgroundColor: 'rgba(236, 72, 153, 0.8)',
                            borderColor: 'rgb(236, 72, 153)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    afterLabel: function(context) {
                                        const dataset = context.dataset.data;
                                        const total = dataset.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed.x / total) * 100).toFixed(1);
                                        return percentage + '%';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                
                console.log('Gráfica de roles creada exitosamente');
            } catch (error) {
                console.error('Error al crear gráfica de roles:', error);
            }
        }
    </script>
</x-app-layout>
