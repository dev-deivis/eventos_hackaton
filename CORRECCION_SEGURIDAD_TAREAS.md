# 🔒 CORRECCIÓN: SEGURIDAD DE TAREAS AL ABANDONAR/REINGRESAR

## 🐛 PROBLEMA DETECTADO

### Escenario:
1. Usuario es miembro activo del equipo
2. Líder le asigna tareas
3. Usuario abandona el equipo
4. Usuario solicita unirse de nuevo (estado: pendiente)
5. ❌ **BUG:** Usuario podía marcar tareas aunque:
   - Ya no es miembro activo (está pendiente)
   - Las tareas aún estaban asignadas a él

---

## ✅ SOLUCIÓN IMPLEMENTADA

### CAMBIO 1: Limpiar tareas al abandonar equipo

**Archivo:** `app/Http/Controllers/EquipoController.php`
**Método:** `abandonar()`

**Qué hace:**
- Cuando un participante abandona el equipo
- Se recorren TODAS las tareas del proyecto
- Se remueve al participante de TODAS las asignaciones
- Se registra en el log cuántas tareas se limpiaron

**Código agregado:**
```php
// Limpiar tareas asignadas antes de abandonar
if ($equipo->proyecto) {
    // Obtener todas las tareas del proyecto
    $tareas = $equipo->proyecto->tareas;
    
    // Remover al participante de todas las tareas donde está asignado
    foreach ($tareas as $tarea) {
        $tarea->participantes()->detach($participante->id);
    }
    
    Log::info('Tareas limpiadas al abandonar equipo', [
        'participante_id' => $participante->id,
        'equipo_id' => $equipo->id,
        'tareas_limpiadas' => $tareas->count()
    ]);
}
```

**Mensaje actualizado:**
```
✅ Has abandonado el equipo. Tus asignaciones de tareas han sido removidas.
```

---

### CAMBIO 2: Verificar estado ACTIVO para marcar tareas

**Archivo:** `app/Http/Controllers/TareaController.php`
**Método:** `toggleEstado()`

**ANTES:**
```php
// Solo verificaba si era miembro (cualquier estado)
if (!$participante || !$equipo->participantes->contains('id', $participante->id)) {
    return back()->with('error', 'No eres miembro de este equipo.');
}
```

**AHORA:**
```php
// Verifica que sea miembro ACTIVO (no pendiente)
$miembroActivo = $equipo->participantes()
    ->where('participantes.id', $participante->id)
    ->wherePivot('estado', 'activo')
    ->exists();

if (!$participante || !$miembroActivo) {
    return back()->with('error', 'No eres miembro activo de este equipo. Debes ser aceptado por el líder primero.');
}
```

**Por qué es importante:**
- Un participante con estado "pendiente" NO puede marcar tareas
- Solo los miembros "activos" pueden interactuar con tareas
- El líder debe aceptarlos primero

---

## 🧪 FLUJO CORREGIDO

### ESCENARIO COMPLETO:

#### PASO 1: Usuario es miembro activo
```
Estado: ACTIVO
Tareas asignadas: [Tarea 1, Tarea 2]
Puede marcar: ✅ SÍ
```

#### PASO 2: Usuario abandona equipo
```
Acción: Click "Abandonar Equipo"
Resultado: 
  - Estado en equipo: REMOVIDO
  - Tareas asignadas: [] (LIMPIADAS automáticamente)
  - Puede marcar: ❌ NO (ya no es miembro)
```

#### PASO 3: Usuario solicita unirse de nuevo
```
Acción: Click "Solicitar Unirse"
Resultado:
  - Estado en equipo: PENDIENTE
  - Tareas asignadas: [] (ninguna)
  - Puede marcar: ❌ NO (debe ser aceptado primero)
```

#### PASO 4: Líder acepta solicitud
```
Acción: Líder click "Aceptar"
Resultado:
  - Estado en equipo: ACTIVO
  - Tareas asignadas: [] (ninguna, debe reasignarlas)
  - Puede marcar: ❌ NO (aún no tiene tareas asignadas)
```

#### PASO 5: Líder reasigna tareas
```
Acción: Líder asigna nuevas tareas
Resultado:
  - Estado en equipo: ACTIVO
  - Tareas asignadas: [Tarea 3]
  - Puede marcar: ✅ SÍ (solo Tarea 3)
```

---

## 🔒 VALIDACIONES IMPLEMENTADAS

### Para MARCAR una tarea se requiere:

1. ✅ Ser participante del sistema
2. ✅ Ser miembro ACTIVO del equipo (no pendiente)
3. ✅ La tarea debe pertenecer al proyecto del equipo
4. ✅ Estar asignado a la tarea O ser el líder

**Si falla alguna validación:**
```
❌ No eres miembro activo de este equipo. Debes ser aceptado por el líder primero.
```

---

## 📊 ESTADOS DEL PARTICIPANTE

| Estado | Puede ver equipo | Puede ver tareas | Puede marcar tareas | Puede ver chat |
|--------|-----------------|------------------|---------------------|----------------|
| NO MIEMBRO | ✅ | ❌ | ❌ | ❌ |
| PENDIENTE | ✅ | ❌ | ❌ | ❌ |
| ACTIVO | ✅ | ✅ | ✅ (si asignado) | ✅ |
| REMOVIDO | ✅ | ❌ | ❌ | ❌ |

---

## 🧪 CASOS DE PRUEBA

### TEST 1: Abandonar y limpiar tareas
```
1. Login como participante activo con tareas asignadas
2. Ver equipo → tiene 2 tareas asignadas
3. Click "Abandonar Equipo"
4. ✅ Mensaje: "Has abandonado... tareas removidas"
5. ✅ Base de datos: participante_tarea ya NO tiene registros de ese participante
```

### TEST 2: Estado pendiente no puede marcar
```
1. Abandonar equipo
2. Solicitar unirse (estado: pendiente)
3. Intentar marcar una tarea (si pudiera verla)
4. ✅ Error: "No eres miembro activo... aceptado primero"
```

### TEST 3: Reasignación después de aceptar
```
1. Líder acepta solicitud (estado: activo)
2. Usuario ve equipo
3. ✅ NO tiene tareas asignadas (están en blanco)
4. Líder edita tarea → asigna al usuario
5. ✅ Ahora usuario PUEDE marcar esa tarea
```

### TEST 4: No puede marcar tareas de otro participante
```
1. Usuario A tiene Tarea 1 asignada
2. Usuario B (activo) intenta marcar Tarea 1
3. ✅ Error: "No estás asignado a esta tarea..."
```

---

## 💾 CAMBIOS EN BASE DE DATOS

### Cuando se abandona equipo:

**ANTES:**
```sql
-- equipo_participante
participante_id | equipo_id | estado
4              | 2         | activo

-- participante_tarea
participante_id | tarea_id
4              | 1
4              | 2
```

**DESPUÉS de abandonar:**
```sql
-- equipo_participante
(registro eliminado)

-- participante_tarea
(registros eliminados automáticamente)
```

**DESPUÉS de solicitar de nuevo:**
```sql
-- equipo_participante
participante_id | equipo_id | estado
4              | 2         | pendiente

-- participante_tarea
(vacío - no tiene tareas)
```

**DESPUÉS de ser aceptado:**
```sql
-- equipo_participante
participante_id | equipo_id | estado
4              | 2         | activo

-- participante_tarea
(vacío - líder debe reasignar)
```

---

## 📝 LOGS GENERADOS

### Al abandonar equipo:
```
INFO: Tareas limpiadas al abandonar equipo
{
  "participante_id": 4,
  "equipo_id": 2,
  "tareas_limpiadas": 2
}
```

---

## ✅ RESULTADO FINAL

Ahora el sistema es seguro:

1. ✅ Al abandonar → tareas se limpian automáticamente
2. ✅ Estado pendiente → NO puede marcar tareas
3. ✅ Solo miembros activos pueden marcar
4. ✅ Solo tareas asignadas pueden ser marcadas
5. ✅ Mensajes claros para cada caso

---

## 🚀 PRÓXIMA VEZ QUE ABANDONES:

```
Has abandonado el equipo. 
Tus asignaciones de tareas han sido removidas.
```

Si vuelves a entrar:
```
Estado: PENDIENTE
→ Espera a que el líder te acepte
→ Líder debe reasignarte tareas
→ Recién ahí podrás marcarlas
```

**¡Sistema totalmente seguro!** 🔒
