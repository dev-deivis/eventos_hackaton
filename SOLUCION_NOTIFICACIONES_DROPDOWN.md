# 🔧 Solución al Problema del Dropdown de Notificaciones

## 📋 Diagnóstico del Problema

El sistema de notificaciones está implementado pero el dropdown no se despliega al hacer clic en la campanita. El problema puede estar en:

1. **Alpine.js no se carga** - El componente x-data no se inicializa
2. **Conflicto de z-index** - El dropdown queda detrás de otros elementos
3. **Error en la ruta API** - No obtiene las notificaciones
4. **JavaScript bloqueado** - Hay un error que detiene la ejecución

## ✅ Soluciones Paso a Paso

### **Paso 1: Verificar que Alpine.js está cargando**

Abre la consola del navegador (F12) y verifica:

```javascript
// Escribe esto en la consola
Alpine
```

Si muestra `undefined`, Alpine.js no está cargando.

**SOLUCIÓN:** Verificar que el layout principal incluye el JS compilado:

```blade
<!-- En: resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- ... otros tags ... -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

Si no está, ejecuta en terminal:
```bash
npm run dev
```

---

### **Paso 2: Verificar la ruta de notificaciones**

Verifica que la ruta responde correctamente. En la consola del navegador:

```javascript
fetch('/notificaciones/obtener-no-leidas')
    .then(r => r.json())
    .then(data => console.log('Respuesta:', data))
    .catch(err => console.error('Error:', err));
```

Si hay un error 404 o 500, el problema está en el backend.

---

### **Paso 3: Corrección del Componente de Notificaciones**

El problema más común es que **Alpine.js no está inicializando el componente**. Aquí está la versión corregida:


**Reemplaza el código del dropdown en `navigation.blade.php`** (líneas 36-169):

```blade
<!-- Dropdown de Notificaciones - VERSIÓN CORREGIDA -->
<div x-data="notificacionesDropdown()" 
     x-init="init()"
     @click.away="dropdownOpen = false" 
     class="relative">
    
    <!-- Botón de Campanita -->
    <button @click="toggleDropdown()" 
            type="button"
            class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
        </svg>
        
        <!-- Badge con contador -->
        <span x-show="count > 0" 
              x-text="count" 
              x-cloak
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[1.25rem] h-5 flex items-center justify-center px-1 animate-pulse">
        </span>
    </button>
    
    <!-- Dropdown de Notificaciones -->
    <div x-show="dropdownOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 transform -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden"
         style="z-index: 9999;">
        
        <!-- Header -->
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                </svg>
                Notificaciones
            </h3>
            <button @click="marcarTodasLeidas()" 
                    x-show="count > 0"
                    type="button"
                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline transition">
                Marcar todas
            </button>
        </div>
        
        <!-- Lista de Notificaciones -->
        <div class="max-h-96 overflow-y-auto">
            <!-- Loading -->
            <div x-show="loading" class="px-4 py-8 text-center">
                <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-gray-500 mt-2">Cargando...</p>
            </div>
            
            <!-- Sin notificaciones -->
            <div x-show="!loading && notificaciones.length === 0" class="px-4 py-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-900 mb-1">Todo al día</p>
                <p class="text-xs text-gray-500">No tienes notificaciones pendientes</p>
            </div>
            
            <!-- Lista de notificaciones -->
            <template x-for="notif in notificaciones" :key="notif.id">
                <a :href="getNotificationUrl(notif.id)"
                   :class="'block px-4 py-3 border-l-4 hover:bg-gray-50 transition duration-150 cursor-pointer ' + getColorClass(notif.tipo)">
                    <div class="flex items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate" x-text="notif.titulo"></p>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2" x-text="notif.mensaje"></p>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="formatearFecha(notif.created_at)"></span>
                            </p>
                        </div>
                    </div>
                </a>
            </template>
        </div>
        
        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 text-center" 
             x-show="notificaciones.length > 0">
            <a href="#" 
               @click.prevent="dropdownOpen = false"
               class="text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline">
                Cerrar
            </a>
        </div>
    </div>
</div>

<script>
function notificacionesDropdown() {
    return {
        dropdownOpen: false,
        notificaciones: [],
        count: 0,
        loading: false,
        
        init() {
            console.log('🔔 Sistema de notificaciones inicializado');
            this.cargarNotificaciones();
            
            // Polling cada 30 segundos
            setInterval(() => {
                if (!this.dropdownOpen) {
                    this.cargarNotificaciones();
                }
            }, 30000);
            
            // Recargar al volver a la pestaña
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden && !this.dropdownOpen) {
                    this.cargarNotificaciones();
                }
            });
        },
        
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
            if (this.dropdownOpen) {
                console.log('📂 Dropdown abierto, recargando notificaciones...');
                this.cargarNotificaciones();
            }
        },
        
        async cargarNotificaciones() {
            this.loading = true;
            try {
                const response = await fetch('{{ route('notificaciones.obtener-no-leidas') }}', {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                this.notificaciones = data.notificaciones || [];
                this.count = data.count || 0;
                
                console.log(`✅ ${this.count} notificaciones cargadas`);
            } catch (error) {
                console.error('❌ Error cargando notificaciones:', error);
                this.notificaciones = [];
                this.count = 0;
            } finally {
                this.loading = false;
            }
        },
        
        formatearFecha(fecha) {
            const date = new Date(fecha);
            const ahora = new Date();
            const diff = Math.floor((ahora - date) / 1000);
            
            if (diff < 60) return 'Justo ahora';
            if (diff < 3600) return `Hace ${Math.floor(diff / 60)} min`;
            if (diff < 86400) return `Hace ${Math.floor(diff / 3600)} h`;
            if (diff < 604800) return `Hace ${Math.floor(diff / 86400)} días`;
            
            return date.toLocaleDateString('es-ES', { 
                day: 'numeric', 
                month: 'short',
                year: date.getFullYear() !== ahora.getFullYear() ? 'numeric' : undefined
            });
        },
        
        getColorClass(tipo) {
            const colores = {
                'solicitud_equipo': 'border-l-blue-500 bg-blue-50',
                'solicitud_aceptada': 'border-l-green-500 bg-green-50',
                'solicitud_rechazada': 'border-l-red-500 bg-red-50',
                'nuevo_miembro_equipo': 'border-l-indigo-500 bg-indigo-50',
                'mensaje_equipo': 'border-l-purple-500 bg-purple-50',
                'tarea_asignada': 'border-l-yellow-500 bg-yellow-50',
                'tarea_completada': 'border-l-emerald-500 bg-emerald-50',
                'evaluacion_recibida': 'border-l-orange-500 bg-orange-50',
                'proyecto_aprobado': 'border-l-green-500 bg-green-50',
                'proyecto_rechazado': 'border-l-red-500 bg-red-50',
                'nuevo_evento': 'border-l-pink-500 bg-pink-50',
                'constancia_generada': 'border-l-amber-500 bg-amber-50',
                'miembro_abandono': 'border-l-gray-500 bg-gray-50',
                'proyecto_entregado': 'border-l-indigo-500 bg-indigo-50',
                'nuevo_equipo': 'border-l-cyan-500 bg-cyan-50',
                'equipo_asignado': 'border-l-blue-500 bg-blue-50',
                'proyecto_listo': 'border-l-emerald-500 bg-emerald-50',
            };
            return colores[tipo] || 'border-l-blue-500 bg-blue-50';
        },
        
        getNotificationUrl(notifId) {
            return `{{ url('/notificaciones') }}/${notifId}/marcar-leida`;
        },
        
        async marcarTodasLeidas() {
            try {
                const response = await fetch('{{ route('notificaciones.marcar-todas-leidas') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    console.log('✅ Todas las notificaciones marcadas como leídas');
                    await this.cargarNotificaciones();
                }
            } catch (error) {
                console.error('❌ Error marcando notificaciones:', error);
            }
        }
    };
}
</script>

<!-- Estilos para x-cloak (evita flash de contenido) -->
<style>
[x-cloak] { 
    display: none !important; 
}
</style>
```

---


### **Paso 4: Verificar el Layout Principal**

Asegúrate de que `app.blade.php` incluye Alpine.js:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
```

---

### **Paso 5: Crear Script de Prueba**

Crea un archivo `test-notificaciones.php` en la raíz:

```php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Crear notificación de prueba para el usuario actual
$userId = 1; // Cambia esto por tu ID de usuario

$notificacion = \App\Models\Notificacion::create([
    'user_id' => $userId,
    'tipo' => 'mensaje_equipo',
    'titulo' => 'Prueba de Notificación',
    'mensaje' => 'Esta es una notificación de prueba del sistema',
    'url_accion' => '/dashboard',
    'leida' => false,
]);

echo "✅ Notificación creada: ID {$notificacion->id}\n";
echo "📧 Para usuario: {$notificacion->user->name}\n";
echo "🔔 Tipo: {$notificacion->tipo}\n";
echo "💬 Mensaje: {$notificacion->mensaje}\n";
```

Ejecuta:
```bash
php test-notificaciones.php
```

---

### **Paso 6: Verificar Base de Datos**

Verifica que la tabla de notificaciones existe:

```sql
-- En tu cliente SQL o tinker
SELECT * FROM notificaciones WHERE user_id = 1 AND leida = 0;
```

Si no existe, crea la migración:

```bash
php artisan make:migration create_notificaciones_table
```

Y usa este contenido:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tipo'); // solicitud_equipo, tarea_asignada, etc.
            $table->string('titulo');
            $table->text('mensaje');
            $table->string('url_accion')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('leida_en')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'leida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
```

Luego ejecuta:
```bash
php artisan migrate
```

---

### **Paso 7: Depuración en Consola del Navegador**

Abre las herramientas de desarrollo (F12) y ejecuta:

```javascript
// 1. Verificar Alpine.js
console.log('Alpine cargado:', typeof Alpine !== 'undefined');

// 2. Verificar el componente
const dropdown = document.querySelector('[x-data*="notificacionesDropdown"]');
console.log('Dropdown encontrado:', dropdown !== null);

// 3. Probar la API manualmente
fetch('/notificaciones/obtener-no-leidas')
    .then(r => r.json())
    .then(data => {
        console.log('✅ Respuesta API:', data);
        console.log('📊 Notificaciones:', data.notificaciones);
        console.log('🔢 Count:', data.count);
    })
    .catch(err => console.error('❌ Error:', err));
```

---

## 🚀 Pasos de Implementación

### **Opción A: Reemplazo Completo (Recomendado)**

1. **Backup del archivo actual:**
   ```bash
   cp resources/views/layouts/navigation.blade.php resources/views/layouts/navigation.blade.php.backup
   ```

2. **Reemplaza el dropdown completo** en `navigation.blade.php` (líneas 36-169) con el código corregido del Paso 3

3. **Verifica que Alpine.js está corriendo:**
   ```bash
   npm run dev
   ```

4. **Crea notificaciones de prueba:**
   ```bash
   php test-notificaciones.php
   ```

5. **Prueba en el navegador** y revisa la consola (F12)

---

### **Opción B: Depuración Manual**

Si prefieres depurar el código actual:

1. **Abre la consola del navegador** (F12)
2. **Busca errores de JavaScript** (líneas rojas)
3. **Verifica que Alpine.js carga:** `console.log(Alpine)`
4. **Verifica la respuesta de la API:** usa el código del Paso 7
5. **Añade console.log** en las funciones para ver qué está fallando

---

## 📊 Checklist de Verificación

- [ ] Alpine.js está cargando (`npm run dev` funcionando)
- [ ] La ruta `/notificaciones/obtener-no-leidas` responde correctamente
- [ ] La tabla `notificaciones` existe en la base de datos
- [ ] Hay notificaciones de prueba creadas
- [ ] El dropdown tiene `z-index: 9999` (no está oculto)
- [ ] No hay errores en la consola del navegador (F12)
- [ ] El botón tiene `type="button"` (evita submit accidental)
- [ ] La función `init()` se ejecuta al cargar

---

## 🎯 Resultados Esperados

Después de aplicar las correcciones:

✅ Al hacer clic en la campanita 🔔, el dropdown se despliega
✅ Se muestran las notificaciones con sus colores correspondientes
✅ El contador muestra el número correcto de notificaciones
✅ Al hacer clic en una notificación, te redirige correctamente
✅ El botón "Marcar todas" funciona correctamente
✅ Las transiciones son suaves (fade in/out)

---

## 🐛 Problemas Comunes y Soluciones

### **Problema 1: "Alpine is not defined"**
**Solución:**
```bash
npm install
npm run dev
```

### **Problema 2: "404 Not Found" en la API**
**Solución:** Verifica que las rutas estén en `web.php`:
```bash
php artisan route:list | grep notificaciones
```

### **Problema 3: El dropdown no se ve (queda detrás)**
**Solución:** Añade `z-index: 9999` al contenedor del dropdown

### **Problema 4: No hay notificaciones**
**Solución:** Crea notificaciones de prueba con el script del Paso 5

### **Problema 5: El contador siempre muestra 0**
**Solución:** Verifica en la base de datos:
```sql
SELECT COUNT(*) FROM notificaciones WHERE user_id = YOUR_USER_ID AND leida = 0;
```

---

## 📞 ¿Aún no funciona?

Si después de seguir todos los pasos el dropdown aún no funciona:

1. **Copia TODO el contenido del navegador (consola F12)** y compártelo
2. **Ejecuta:** `php artisan route:list | grep notificaciones` y comparte el resultado
3. **Comparte el error específico** que ves en la consola
4. **Prueba el archivo** `public/test-notificaciones.html` para aislar el problema

---

## 🎉 Bonus: Mejoras Adicionales

Una vez que funcione, puedes agregar:

- **Sonido de notificación** cuando llegue una nueva
- **Web Sockets** en lugar de polling (Laravel Reverb/Pusher)
- **Notificaciones push del navegador**
- **Filtros por tipo de notificación**
- **Búsqueda en notificaciones**

---

**¡Ahora tu sistema de notificaciones debería funcionar perfectamente! 🎊**
