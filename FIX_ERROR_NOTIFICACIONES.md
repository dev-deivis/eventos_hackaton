# 🔧 SOLUCIÓN AL ERROR DE NOTIFICACIONES

## 🐛 Problema Identificado

**Error:** Sesión se cierra o da error después de múltiples actualizaciones de página
**Causa:** Acumulación excesiva de notificaciones en el payload JSON (9+ notificaciones con URLs largas)
**Síntoma:** Error después de varios ciclos de cierre/inicio de sesión

---

## ✅ Soluciones Implementadas

### 1. **Reducir Límite de Notificaciones**
- **Archivo:** `app/Http/Controllers/NotificacionController.php`
- **Cambio:** `take(10)` → `take(5)`
- **Beneficio:** 50% menos datos en cada request de polling

### 2. **Comando de Limpieza Automática**
- **Archivo:** `app/Console/Commands/LimpiarNotificacionesAntiguas.php`
- **Uso:** `php artisan notificaciones:limpiar`
- **Función:** Elimina notificaciones leídas antiguas

### 3. **Middleware de Prevención**
- **Archivo:** `app/Http/Middleware/LimitarNotificacionesSesion.php`
- **Función:** Auto-marca notificaciones antiguas si hay más de 50

---

## 📋 PASOS DE APLICACIÓN

### Paso 1: Limpiar Notificaciones Existentes

```bash
# Limpiar notificaciones de más de 30 días
php artisan notificaciones:limpiar

# O limpiar de forma más agresiva (7 días)
php artisan notificaciones:limpiar --dias=7
```

### Paso 2: Registrar el Comando (Laravel 12)

Abre `routes/console.php` y agrega:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('notificaciones:limpiar')->daily();
```

### Paso 3: Registrar el Middleware (Laravel 12)

Abre `bootstrap/app.php` y agrega en la sección de middleware:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\LimitarNotificacionesSesion::class,
    ]);
})
```

**O** si ya existe la configuración, agrégalo a los middlewares web existentes.

### Paso 4: Limpiar Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🔍 VERIFICACIÓN

### Antes de la Solución
```json
{
  "notificaciones": [ /* 9-10 notificaciones */ ],
  "count": 9
}
```
**Tamaño:** ~3-5 KB por request
**Problema:** Se acumulan hasta causar error

### Después de la Solución
```json
{
  "notificaciones": [ /* máximo 5 notificaciones */ ],
  "count": 5
}
```
**Tamaño:** ~2 KB por request
**Beneficio:** No hay acumulación excesiva

---

## 🎯 COMANDOS ÚTILES

### Limpiar Notificaciones Manualmente

```bash
# Por defecto: 30 días
php artisan notificaciones:limpiar

# Personalizado: 15 días
php artisan notificaciones:limpiar --dias=15

# Agresivo: 7 días
php artisan notificaciones:limpiar --dias=7
```

### Verificar Notificaciones en BD

```bash
php artisan tinker
```

```php
// Contar notificaciones no leídas
\App\Models\User::find(1)->notificaciones()->noLeidas()->count();

// Ver notificaciones antiguas
\App\Models\Notificacion::where('created_at', '<', now()->subDays(30))->count();

// Marcar todas como leídas para un usuario
\App\Models\User::find(1)->notificaciones()->update(['leida' => true, 'leida_en' => now()]);
```

---

## 💡 PREVENCIÓN FUTURA

### 1. **Limpieza Automática Diaria**

En `routes/console.php`:
```php
Schedule::command('notificaciones:limpiar')->daily();
```

### 2. **Marcar Notificaciones Antiguas**

En `routes/console.php`:
```php
Schedule::command('notificaciones:limpiar --dias=15')->weekly();
```

### 3. **Monitorear Acumulación**

Agregar al dashboard admin:
```php
// En AdminController.php
$notificacionesNoLeidas = \App\Models\Notificacion::where('leida', false)->count();
$notificacionesAntiguas = \App\Models\Notificacion::where('created_at', '<', now()->subDays(30))->count();
```

---

## 🚨 SOLUCIÓN INMEDIATA (Si el error persiste)

Si sigues teniendo el error:

### Opción 1: Limpiar TODO
```bash
php artisan notificaciones:limpiar --dias=1
```

### Opción 2: Marcar Todas Como Leídas (BD)
```sql
UPDATE notificaciones SET leida = 1, leida_en = NOW() WHERE leida = 0;
```

### Opción 3: Eliminar Notificaciones Antiguas (BD)
```sql
DELETE FROM notificaciones WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY) AND leida = 1;
```

---

## 📊 OPTIMIZACIONES ADICIONALES

### 1. Aumentar Tiempo de Polling

En `resources/views/layouts/navigation.blade.php` o donde esté el polling:

```javascript
// Cambiar de 30s a 60s
setInterval(() => {
    fetchNotificaciones();
}, 60000); // Era 30000
```

### 2. Usar Cache para Notificaciones

En `NotificacionController.php`:

```php
use Illuminate\Support\Facades\Cache;

public function obtenerNoLeidas()
{
    $userId = auth()->id();
    
    return Cache::remember("notificaciones_user_{$userId}", 30, function () {
        return auth()->user()
            ->notificaciones()
            ->noLeidas()
            ->recientes()
            ->take(5)
            ->get()
            ->map(function ($notificacion) {
                return [
                    'id' => $notificacion->id,
                    'tipo' => $notificacion->tipo,
                    'titulo' => $notificacion->titulo,
                    'mensaje' => $notificacion->mensaje,
                    'url_accion' => $notificacion->url_accion,
                    'created_at' => $notificacion->created_at->diffForHumans(),
                ];
            });
    });
}
```

### 3. Configurar Sesión en Base de Datos

En `.env`:
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

Luego:
```bash
php artisan session:table
php artisan migrate
```

---

## 🎉 RESULTADO ESPERADO

✅ **Antes:**
- Error después de múltiples actualizaciones
- Sesión se cierra inesperadamente
- 9+ notificaciones acumuladas

✅ **Después:**
- Sin errores de sesión
- Máximo 5 notificaciones mostradas
- Limpieza automática periódica
- Mejor rendimiento general

---

## 📞 TROUBLESHOOTING

### Error: "Class 'Schedule' not found"
**Solución:** Importar correctamente en `routes/console.php`:
```php
use Illuminate\Support\Facades\Schedule;
```

### Error: "Middleware not found"
**Solución:** Verificar que el archivo existe en `app/Http/Middleware/`

### Notificaciones siguen acumulándose
**Solución:** Ejecutar manualmente:
```bash
php artisan notificaciones:limpiar --dias=7
```

---

**Fecha:** Diciembre 10, 2025  
**Estado:** ✅ Solución Implementada  
**Archivos Modificados:** 3  
**Archivos Creados:** 2
