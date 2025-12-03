# 🚀 CÓDIGO PARA COMPLETAR NOTIFICACIONES

## TareaController - Agregar estas líneas

### En el método `store()` (después de crear la tarea):

```php
use App\Services\NotificationService;

// Después de: $tarea = TareaProyecto::create(...);

// Notificar a los participantes asignados
if ($tarea->participantes->count() > 0) {
    $asignadosUserIds = $tarea->participantes->pluck('user_id')->toArray();
    NotificationService::tareaAsignada($tarea, $asignadosUserIds);
}
```

### En el método `toggleEstado()` (cuando se completa):

```php
// Después de: $tarea->update(['completada' => !$tarea->completada]);

// Si la tarea fue marcada como completada
if ($tarea->completada) {
    NotificationService::tareaCompletada($tarea, auth()->user());
}
```

---

## JuezController - Agregar en `guardarEvaluacion()`

### Después de guardar las calificaciones:

```php
use App\Services\NotificationService;

// Calcular calificación final
$calificacionFinal = $calificaciones->avg('puntos');

// Notificar a los miembros del equipo
NotificationService::evaluacionRecibida(
    $equipo, 
    auth()->user(), 
    round($calificacionFinal, 2)
);
```

---

## AdminController - Proyectos

### En el método `aprobarProyecto()`:

```php
use App\Services\NotificationService;

// Después de aprobar el proyecto
$proyecto->update(['estado' => 'aprobado']);

// Notificar al equipo
NotificationService::proyectoAprobado($proyecto);
```

### En el método `rechazarProyecto()`:

```php
use App\Services\NotificationService;

// Después de rechazar el proyecto
$proyecto->update(['estado' => 'rechazado']);

// Notificar al equipo con el motivo
NotificationService::proyectoRechazado(
    $proyecto, 
    $request->input('motivo', 'No especificado')
);
```

---

## ConstanciaController - Constancias Generadas

### En `generarIndividual()` (después de generar):

```php
use App\Services\NotificationService;

// Después de: $constancia = Constancia::create(...);

// Notificar al participante
NotificationService::constanciaGenerada($constancia);
```

### En `generarEnLote()` (dentro del foreach):

```php
use App\Services\NotificationService;

foreach ($participantes as $participante) {
    $constancia = Constancia::create([...]);
    
    // Notificar al participante
    NotificationService::constanciaGenerada($constancia);
}
```

---

## VERIFICAR QUE ESTÉ EL USE EN LOS ARCHIVOS

Al inicio de cada controlador, agregar:

```php
use App\Services\NotificationService;
```

---

## TESTING RÁPIDO

### 1. Prueba de Tareas

```bash
# Crear una tarea y asignarla
# Verificar que aparezca la notificación en el dashboard del asignado
```

### 2. Prueba de Evaluación

```bash
# Como juez, evalúa un equipo
# Verificar que todos los miembros reciban la notificación
```

### 3. Prueba de Proyecto

```bash
# Como admin, aprueba o rechaza un proyecto
# Verificar que el equipo reciba la notificación
```

### 4. Prueba de Constancia

```bash
# Como admin, genera una constancia
# Verificar que el participante reciba la notificación
```

---

## UBICACIONES DE ARCHIVOS

```
app/Http/Controllers/TareaController.php
app/Http/Controllers/JuezController.php
app/Http/Controllers/AdminController.php
app/Http/Controllers/ConstanciaController.php
```

---

## COMANDOS ÚTILES

```bash
# Limpiar caché
php artisan cache:clear

# Recargar autoload
composer dump-autoload

# Ver logs
php artisan pail

# Verificar rutas
php artisan route:list | grep notificacion
```

---

Implementa estos cambios y ¡el sistema estará 100% completo! 🎉
