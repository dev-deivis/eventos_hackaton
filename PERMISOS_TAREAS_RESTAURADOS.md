# 🔒 PERMISOS DE TAREAS RESTAURADOS

## ✅ Problema Corregido

Se restauraron las validaciones correctas para marcar/desmarcar tareas:
- **Líder:** Puede marcar cualquier tarea
- **Miembro asignado:** Solo puede marcar sus tareas
- **Miembro no asignado:** No puede marcar ninguna tarea

---

## 🎯 Reglas de Permisos

### Líder del Equipo
✅ Puede marcar/desmarcar **CUALQUIER** tarea del proyecto  
✅ Tiene control total sobre todas las tareas  
✅ No necesita estar asignado a la tarea  

**Razón:** El líder coordina el proyecto completo

### Miembro Asignado
✅ Puede marcar/desmarcar **SOLO** las tareas asignadas a él  
❌ No puede tocar tareas de otros miembros  
❌ No puede marcar tareas sin asignar  

**Razón:** Solo controla su propio trabajo

### Miembro No Asignado
❌ No puede marcar ninguna tarea  
👁️ Solo puede verlas (checkbox visible pero deshabilitado)  

**Razón:** No tiene tareas asignadas

---

## 🔧 Validaciones Implementadas

### Backend - `TareaController::toggleApi()`

```php
// 1. Verificar miembro activo
$miembroActivo = $equipo->participantes()
    ->where('participantes.id', $participante->id)
    ->wherePivot('estado', 'activo')
    ->exists();

// 2. Verificar equipo no evaluado
if ($equipo->fueEvaluado()) {
    return error 403;
}

// 3. Verificar tarea del equipo
if ($tarea->proyecto_id !== $equipo->proyecto->id) {
    return error 403;
}

// 4. Verificar permiso: líder O asignado
$esLider = $equipo->lider_id === $participante->id;
$estaAsignado = $tarea->participantes->contains('id', $participante->id);

if (!$esLider && !$estaAsignado) {
    return error 403: "No estás asignado a esta tarea";
}
```

**Respuestas:**
```json
// Éxito
{
  "success": true,
  "tarea": {
    "id": 123,
    "completada": true,
    "estado": "completada"
  }
}

// Error - Sin permiso
{
  "success": false,
  "message": "No estás asignado a esta tarea. Solo los participantes asignados y el líder pueden marcarla."
}
```

### Frontend - `show.blade.php`

```php
@php
    $puedeMarcar = $esMiembro && (
        $esLider || 
        $tarea->participantes->contains('id', auth()->user()->participante->id)
    );
@endphp

@if ($puedeMarcar)
    <!-- Botón clickeable -->
    <button type="submit" data-toggle-tarea="{{ $tarea->id }}" ...>
@else
    <!-- Checkbox visual (no clickeable) -->
    <div class="mt-1 w-6 h-6 rounded ..." title="No tienes permiso">
@endif
```

**Variable `$puedeMarcar`:**
- `true` → Muestra botón clickeable
- `false` → Muestra div visual solamente

### JavaScript

El código ya maneja correctamente los errores 403:

```javascript
if (data.success) {
    actualizarEstadoTarea(tareaId, data.tarea.completada);
} else {
    mostrarNotificacion(data.message, 'error'); // ← Muestra mensaje del backend
}
```

---

## 🧪 Pruebas

### Test 1: Como Líder

```
1. Inicia sesión como líder de equipo
2. Ve a la página del equipo
3. Busca una tarea asignada a otro miembro
4. Click en el checkbox de esa tarea
5. ✅ Resultado: Se marca/desmarca correctamente
```

### Test 2: Como Miembro Asignado

```
1. Inicia sesión como miembro del equipo
2. Ve a la página del equipo
3. Busca una tarea ASIGNADA A TI
4. Click en el checkbox
5. ✅ Resultado: Se marca/desmarca correctamente

6. Busca una tarea NO ASIGNADA A TI
7. Click en el checkbox
8. ❌ Resultado: Notificación roja
   "No estás asignado a esta tarea..."
```

### Test 3: Como Miembro Sin Tareas

```
1. Inicia sesión como miembro sin tareas asignadas
2. Ve a la página del equipo
3. Observa los checkboxes
4. ✅ Resultado: Todos los checkboxes son DIVs (no clickeables)
5. Intenta hacer click
6. ❌ Resultado: No pasa nada (no hay botón)
```

---

## 📊 Comparación

| Rol | Antes (Bug) | Después (Correcto) |
|-----|-------------|-------------------|
| **Líder** | Podía marcar todas ✅ | Puede marcar todas ✅ |
| **Miembro Asignado** | Podía marcar todas ❌ | Solo sus tareas ✅ |
| **Miembro Sin Tareas** | Podía marcar todas ❌ | No puede marcar ✅ |

---

## 🎨 Interfaz Visual

### Tarea que PUEDES marcar (líder o asignado)
```html
<button type="submit" data-toggle-tarea="123"
    class="... hover:scale-110 cursor-pointer">
    <!-- Interactivo, animación hover -->
</button>
```

### Tarea que NO PUEDES marcar
```html
<div class="... cursor-not-allowed opacity-60"
    title="No tienes permiso para marcar esta tarea">
    <!-- Solo visual, no clickeable -->
</div>
```

---

## 🔍 Mensajes de Error

Cuando un usuario intenta marcar una tarea sin permiso:

**Notificación roja:**
> ❌ No estás asignado a esta tarea. Solo los participantes asignados y el líder pueden marcarla.

**Consola (opcional):**
```
Error 403: Forbidden
No estás asignado a esta tarea...
```

---

## 📝 Lógica Resumida

```
Usuario hace click en checkbox
    ↓
Frontend: ¿$puedeMarcar = true?
    ├─ SÍ → Envía request a toggleApi()
    └─ NO → No hay botón, solo div visual
    
Backend: toggleApi() recibe request
    ↓
Validación 1: ¿Es miembro activo? → NO → Error 403
    ↓
Validación 2: ¿Equipo evaluado? → SÍ → Error 403
    ↓
Validación 3: ¿Tarea del equipo? → NO → Error 403
    ↓
Validación 4: ¿Es líder O asignado? → NO → Error 403
    ↓
Todas OK → Cambia estado → Retorna success
    ↓
Frontend actualiza UI con animación
```

---

## 🚀 Activar

Ejecuta:
```bash
restaurar-permisos-tareas.bat
```

Recarga navegador: **Ctrl + Shift + R**

---

## 📁 Archivos Modificados

1. `app/Http/Controllers/TareaController.php`
   - Método `toggleApi()` con validaciones completas

2. `resources/views/equipos/show.blade.php`
   - Variable `$puedeMarcar` calculada
   - Renderizado condicional del checkbox

3. `public/js/equipos-tiempo-real.js`
   - Ya manejaba errores correctamente (sin cambios)

---

## 💡 Notas Técnicas

### ¿Por qué doble validación?

**Frontend (Blade):**
- Previene clicks innecesarios
- Mejora UX (no muestra botón si no puede usarlo)
- Reduce carga del servidor

**Backend (Controller):**
- Seguridad real (no se puede bypass)
- Protege contra manipulación del DOM
- Validación autoritativa

### Relación Participante-Tarea

```php
// Verificar asignación
$tarea->participantes->contains('id', $participanteId)

// Esto consulta la tabla pivot:
tarea_participante
├── tarea_id
└── participante_id
```

---

**¡Permisos restaurados correctamente!** 🔒

Ahora solo el líder y los asignados pueden marcar sus tareas.
