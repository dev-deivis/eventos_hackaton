<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    @php
                        $dashboardRoute = 'dashboard';
                        if (auth()->user()->isAdmin()) {
                            $dashboardRoute = 'admin.dashboard';
                        } elseif (auth()->user()->isJuez()) {
                            $dashboardRoute = 'juez.dashboard';
                        }
                    @endphp
                    <a href="{{ route($dashboardRoute) }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Dropdown de Notificaciones (JavaScript Vanilla) -->
                <div class="relative" id="notificaciones-dropdown">
                    <!-- Botón de Notificaciones -->
                    <button id="notificaciones-btn"
                            type="button"
                            style="position: relative; display: inline-block;"
                            class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out focus:outline-none">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>

                        <!-- Badge con contador -->
                        <span id="notificaciones-badge"
                              style="display: none; position: absolute; top: -4px; right: -4px;"
                              class="bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse">
                            0
                        </span>
                    </button>

                    <!-- Dropdown de Notificaciones -->
                    <div id="notificaciones-menu"
                         style="display: none; position: absolute; right: 0; top: 100%;"
                         class="mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-[9999] max-h-[32rem] flex flex-col">

                        <!-- Encabezado -->
                        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50 rounded-t-lg">
                            <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                            <div class="flex items-center gap-2">
                                <button id="marcar-todas-btn"
                                        style="display: none;"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    Marcar todas leídas
                                </button>
                                <a href="{{ route('notificaciones.index') }}"
                                   class="text-xs text-gray-600 hover:text-gray-800 font-medium">
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

                <!-- Dropdown de Usuario -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @php
                $dashboardRoute = 'dashboard';
                if (auth()->user()->isAdmin()) {
                    $dashboardRoute = 'admin.dashboard';
                } elseif (auth()->user()->isJuez()) {
                    $dashboardRoute = 'juez.dashboard';
                }
            @endphp
            <x-responsive-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script>
// Script de notificaciones - Ejecuta DESPUÉS de que TODO el DOM esté listo
(function() {
    console.log('🚀 SCRIPT DE NOTIFICACIONES CARGADO');

    const btn = document.getElementById('notificaciones-btn');
    const menu = document.getElementById('notificaciones-menu');
    const badge = document.getElementById('notificaciones-badge');
    const lista = document.getElementById('notificaciones-lista');
    const loading = document.getElementById('notificaciones-loading');
    const empty = document.getElementById('notificaciones-empty');
    const marcarTodasBtn = document.getElementById('marcar-todas-btn');
    const dropdown = document.getElementById('notificaciones-dropdown');

    console.log('Elementos:', { btn: !!btn, menu: !!menu, badge: !!badge });

    // Verificar que los elementos existen
    if (!btn || !menu) {
        console.error('❌ No se encontraron los elementos del dropdown');
        return;
    }

    console.log('✅ Dropdown inicializado correctamente');

    let notificaciones = [];
    let count = 0;

    // Click en campanita - EXACTAMENTE igual que tu botón de prueba
    btn.onclick = function() {
        console.log('🔔 CLICK DETECTADO!');

        if (menu.style.display === 'none' || menu.style.display === '') {
            console.log('Abriendo...');
            menu.style.display = 'block';
            cargarNotificaciones();
        } else {
            console.log('Cerrando...');
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
@endpush
