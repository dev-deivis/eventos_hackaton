<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Eventos Académicos') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo y Título -->
                    <div class="flex items-center">
                        @php
                            $dashboardRoute = 'dashboard';
                            if (auth()->check()) {
                                if (auth()->user()->isAdmin()) {
                                    $dashboardRoute = 'admin.dashboard';
                                } elseif (auth()->user()->isJuez()) {
                                    $dashboardRoute = 'juez.dashboard';
                                }
                            }
                        @endphp
                        <a href="{{ route($dashboardRoute) }}" class="flex-shrink-0 flex items-center gap-3 hover:opacity-80 transition-opacity">
                            <div class="bg-indigo-600 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">Eventos Académicos</span>
                        </a>
                    </div>

                    <!-- Acciones de Usuario -->
                    @auth
                        <div class="flex items-center gap-4">
                            <!-- Tema Oscuro -->
                            <button class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                </svg>
                            </button>

                            <!-- Notificaciones -->
                            <div class="relative" id="notificaciones-dropdown">
                                <button id="notificaciones-btn" class="p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 relative">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                                    </svg>
                                    <span id="notificaciones-badge" style="display: none;" class="absolute top-1 right-1 flex items-center justify-center h-5 w-5 text-xs font-bold text-white bg-red-500 rounded-full ring-2 ring-white animate-pulse">0</span>
                                </button>

                                <!-- Dropdown de Notificaciones -->
                                <div id="notificaciones-menu" style="display: none; position: absolute; right: 0; top: 100%;" class="mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-[9999] max-h-[32rem] flex flex-col">
                                    <!-- Encabezado -->
                                    <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50 rounded-t-lg">
                                        <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                                        <div class="flex items-center gap-2">
                                            <button id="marcar-todas-btn" style="display: none;" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                Marcar todas leídas
                                            </button>
                                            <a href="{{ route('notificaciones.index') }}" class="text-xs text-gray-600 hover:text-gray-800 font-medium">
                                                Ver todas
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Lista de Notificaciones -->
                                    <div class="overflow-y-auto flex-1">
                                        <!-- Estado de carga -->
                                        <div id="notificaciones-loading" class="p-8 text-center">
                                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                            <p class="mt-2 text-sm text-gray-600">Cargando notificaciones...</p>
                                        </div>

                                        <!-- Sin notificaciones -->
                                        <div id="notificaciones-empty" style="display: none;" class="p-8 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">No hay notificaciones nuevas</p>
                                        </div>

                                        <!-- Lista de notificaciones -->
                                        <div id="notificaciones-lista"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Perfil -->
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.show') }}" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 hover:text-indigo-600">{{ auth()->user()->name }}</span>
                                </a>
                            </div>

                            <!-- Salir -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4.414l-4.293 4.293a1 1 0 01-1.414 0L4 7.414V13h10V7.414z" clip-rule="evenodd"/>
                                    </svg>
                                    Salir
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
                                Registrarse
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Mensajes Flash -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 font-medium">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 font-medium">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Script de Notificaciones -->
    <script>
    (function() {
        console.log('🚀 Sistema de notificaciones iniciado');

        const btn = document.getElementById('notificaciones-btn');
        const menu = document.getElementById('notificaciones-menu');
        const badge = document.getElementById('notificaciones-badge');
        const lista = document.getElementById('notificaciones-lista');
        const loading = document.getElementById('notificaciones-loading');
        const empty = document.getElementById('notificaciones-empty');
        const marcarTodasBtn = document.getElementById('marcar-todas-btn');
        const dropdown = document.getElementById('notificaciones-dropdown');

        if (!btn || !menu) {
            console.error('❌ Elementos del dropdown no encontrados');
            return;
        }

        console.log('✅ Elementos encontrados correctamente');

        let notificaciones = [];
        let count = 0;

        // Click en campanita
        btn.onclick = function() {
            console.log('🔔 Click en campanita');

            if (menu.style.display === 'none' || menu.style.display === '') {
                console.log('Abriendo dropdown...');
                menu.style.display = 'block';
                cargarNotificaciones();
            } else {
                console.log('Cerrando dropdown...');
                menu.style.display = 'none';
            }
        };

        // Cerrar al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (dropdown && !dropdown.contains(e.target)) {
                menu.style.display = 'none';
            }
        });

        // Cargar notificaciones
        async function cargarNotificaciones() {
            loading.style.display = 'block';
            empty.style.display = 'none';
            lista.innerHTML = '';

            try {
                const response = await fetch('{{ route('notificaciones.obtener-no-leidas') }}');
                if (response.ok) {
                    const data = await response.json();
                    notificaciones = data.notificaciones || [];
                    count = data.count || 0;

                    actualizarBadge();
                    renderizarNotificaciones();
                }
            } catch (error) {
                console.error('Error al cargar notificaciones:', error);
            } finally {
                loading.style.display = 'none';
            }
        }

        // Actualizar badge
        function actualizarBadge() {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
                if (marcarTodasBtn) marcarTodasBtn.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
                if (marcarTodasBtn) marcarTodasBtn.style.display = 'none';
            }
        }

        // Renderizar notificaciones
        function renderizarNotificaciones() {
            if (notificaciones.length === 0) {
                empty.style.display = 'block';
                return;
            }

            lista.innerHTML = notificaciones.map(n => `
                <a href="{{ url('/notificaciones') }}/${n.id}/marcar-leida"
                   class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition duration-150 ease-in-out">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            ${getIcono(n.tipo)}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">${n.titulo}</p>
                            <p class="text-sm text-gray-600 mt-1">${n.mensaje}</p>
                            <p class="text-xs text-gray-500 mt-1">${n.created_at}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                        </div>
                    </div>
                </a>
            `).join('');
        }

        // Obtener icono según tipo
        function getIcono(tipo) {
            const iconos = {
                'solicitud_equipo': '<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>',
                'proyecto_calificado': '<svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>',
                'mensaje_equipo': '<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/></svg>'
            };
            return iconos[tipo] || '<svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/></svg>';
        }

        // Marcar todas como leídas
        if (marcarTodasBtn) {
            marcarTodasBtn.onclick = async function(e) {
                e.preventDefault();
                try {
                    const response = await fetch('{{ route('notificaciones.marcar-todas-leidas') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (response.ok) {
                        notificaciones = [];
                        count = 0;
                        actualizarBadge();
                        renderizarNotificaciones();
                    }
                } catch (error) {
                    console.error('Error al marcar notificaciones:', error);
                }
            };
        }

        // Cargar al inicio y cada 30 segundos
        cargarNotificaciones();
        setInterval(cargarNotificaciones, 30000);
    })();
    </script>

@stack('scripts')
</body>
</html>
