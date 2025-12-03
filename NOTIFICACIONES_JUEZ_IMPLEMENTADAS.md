# 🔔 NOTIFICACIONES PARA JUEZ - IMPLEMENTADAS

## ✅ Problema Resuelto

Se implementaron las notificaciones faltantes para los jueces:
1. ✅ Notificación cuando le asignan un nuevo equipo
2. ✅ Notificación cuando un proyecto está listo para evaluar

---

## 📋 Cambios Realizados

### 1. **AdminUserController.php** ✅ MODIFICADO

**Ubicación:** `app/Http/Controllers/AdminUserController.php`

**Cambio:** Ahora cuando un admin asigna equipos a un juez, el juez recibe notificaciones.

**Código agregado:**
```php
// Obtener equipos actuales antes de sincronizar
$equiposAnteriores = $usuario->equiposAsignados()->pluck('equipos.id')->toArray();

// Sincronizar equipos asignados
$usuario->equiposAsignados()->sync($validated['equipos'] ?? []);

// Notificar sobre nuevos equipos asignados
$equiposNuevos = array_diff($validated['equipos'] ?? [], $equiposAnteriores);
foreach ($equiposNuevos as $equipoId) {
    $equipo = \App\Models\Equipo::find($equipoId);
    if ($equipo) {
        \App\Services\NotificationService::equipoAsignadoAJuez($usuario, $equipo);
    }
}
```

**Qué hace:**
- Compara los equipos anteriores con los nuevos
- Identifica qué equipos son nuevos
- Envía notificación solo por los equipos nuevos (no duplicados)

---

### 2. **Proyecto.php (Modelo)** ✅ MODIFICADO

**Ubicación:** `app/Models/Proyecto.php`

**Cambio:** Cuando el admin aprueba un proyecto, automáticamente notifica a los jueces asignados.

**Código agregado:**
```php
public function aprobarParaEvaluacion(): void
{
    $this->update([
        'estado' => self::ESTADO_LISTO_EVALUAR,
    ]);
    
    // Notificar a los jueces que el proyecto está listo
    \App\Services\NotificationService::proyectoListoParaEvaluar($this);
}
```

**Qué hace:**
- Cambia el estado del proyecto a "listo_para_evaluar"
- Notifica a todos los jueces asignados al equipo
- Solo notifica a jueces que NO han evaluado aún

---

### 3. **Equipo.php (Modelo)** ✅ MODIFICADO

**Ubicación:** `app/Models/Equipo.php`

**Cambio:** Se agregó la relación con jueces.

**Código agregado:**
```php
public function jueces(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'juez_equipo', 'equipo_id', 'juez_id')
                ->withTimestamps();
}
```

**Qué hace:**
- Define la relación many-to-many entre Equipo y User (jueces)
- Permite acceder a `$equipo->jueces` para obtener todos los jueces asignados

---

## 🎯 Tipos de Notificaciones para Juez

### 1. **Equipo Asignado** 🆕

**Tipo:** `equipo_asignado`
**Color:** Azul
**Cuándo:** Admin asigna un equipo al juez
**Título:** "📝 Nuevo equipo asignado"
**Mensaje:** "Se te asignó el equipo '{nombre_equipo}' para evaluar"
**Acción:** Click lleva a `juez.evaluar`

---

### 2. **Proyecto Listo** 🆕

**Tipo:** `proyecto_listo`
**Color:** Verde Esmeralda
**Cuándo:** Admin aprueba proyecto para evaluación
**Título:** "✅ Proyecto listo para evaluar"
**Mensaje:** "El proyecto '{nombre_proyecto}' del equipo {nombre_equipo} está listo"
**Acción:** Click lleva a `juez.evaluar`
**Nota:** Solo se envía si el juez NO ha evaluado ya

---

## 🔍 Flujo Completo

### Flujo 1: Asignación de Equipo

```
1. Admin va a /admin/usuarios/{juez}/edit
   ↓
2. Selecciona equipos para asignar
   ↓
3. Guarda cambios
   ↓
4. Sistema detecta equipos nuevos
   ↓
5. Envía notificación por cada equipo nuevo
   ↓
6. Juez ve notificación en campanita 🔔
   ↓
7. Click en notificación
   ↓
8. Va a página de evaluación del equipo
```

---

### Flujo 2: Proyecto Listo para Evaluar

```
1. Equipo entrega proyecto
   ↓
2. Admin va a /admin/proyectos/pendientes
   ↓
3. Revisa proyecto del equipo
   ↓
4. Click en "Aprobar"
   ↓
5. Proyecto cambia a "listo_para_evaluar"
   ↓
6. Sistema busca jueces asignados al equipo
   ↓
7. Por cada juez que NO ha evaluado:
   ↓
8. Envía notificación "Proyecto listo"
   ↓
9. Juez ve notificación en campanita 🔔
   ↓
10. Click en notificación
   ↓
11. Va a página de evaluación
```

---

## 🧪 Cómo Probar

### Preparación:

1. **Crear usuarios:**
   - 1 Admin
   - 1 Juez
   - 2-3 Participantes

2. **Crear estructura:**
   - 1 Evento activo
   - 1 Equipo con participantes
   - 1 Proyecto del equipo

---

### Prueba 1: Asignación de Equipo

**Como Admin:**

1. Ir a `/admin/usuarios`
2. Click en "Editar" en el juez
3. En la sección "Equipos Asignados", seleccionar un equipo
4. Click en "Actualizar Usuario"
5. Ver mensaje de éxito

**Como Juez:**

6. Iniciar sesión como juez
7. Ver la campanita 🔔
8. Debe mostrar contador "1"
9. Click en campanita
10. Ir a `/notificaciones`
11. Debe ver notificación:
    - Título: "📝 Nuevo equipo asignado"
    - Mensaje: "Se te asignó el equipo '...' para evaluar"
    - Punto rojo (no leída)
12. Click en la notificación
13. Debe ir a la página de evaluación

**✅ Resultado esperado:** Notificación visible y funcional

---

### Prueba 2: Proyecto Listo

**Como Equipo (Líder):**

1. Iniciar sesión como líder del equipo
2. Ir al equipo
3. Completar proyecto (nombre, descripción, links, tareas)
4. Click en "Entregar Proyecto"

**Como Admin:**

5. Iniciar sesión como admin
6. Ir a `/admin/proyectos/pendientes`
7. Ver el proyecto entregado
8. Click en "Revisar"
9. Click en "Aprobar para Evaluación"

**Como Juez:**

10. Iniciar sesión como juez
11. Ver la campanita 🔔
12. Debe mostrar contador con notificación nueva
13. Click en campanita → `/notificaciones`
14. Debe ver notificación:
    - Título: "✅ Proyecto listo para evaluar"
    - Mensaje: "El proyecto '...' del equipo ... está listo"
    - Punto rojo (no leída)
15. Click en la notificación
16. Debe ir a la página de evaluación

**✅ Resultado esperado:** Notificación visible y funcional

---

### Prueba 3: Sin Duplicados

**Objetivo:** Verificar que no se envían notificaciones duplicadas

**Pasos:**

1. Como Admin, asignar el mismo equipo 2 veces al juez
2. Como Juez, verificar que solo recibe 1 notificación
3. Como Admin, aprobar el mismo proyecto 2 veces
4. Como Juez, verificar que solo recibe 1 notificación

**✅ Resultado esperado:** Solo 1 notificación por acción

---

### Prueba 4: No Notificar si Ya Evaluó

**Pasos:**

1. Como Juez, evaluar un equipo completamente
2. Como Admin, "aprobar" el proyecto otra vez (cambiar estado manualmente)
3. Como Juez, verificar que NO recibe notificación

**✅ Resultado esperado:** No hay notificación si ya evaluó

---

## 📊 Estados del Proyecto

Para que funcione correctamente, el proyecto debe pasar por estos estados:

1. **borrador** → Proyecto creado
2. **en_progreso** → Equipo trabajando
3. **pendiente_revision** → 100% completado (automático)
4. **entregado** → Equipo hace "Entregar Proyecto"
5. **listo_para_evaluar** → Admin aprueba (🔔 Notificación aquí)
6. **evaluado** → Juez evalúa
7. **finalizado** → Proceso completo

---

## 🐛 Troubleshooting

### Problema: No llegan notificaciones al juez

**Solución:**

1. Verificar que el juez tiene equipos asignados:
   ```sql
   SELECT * FROM juez_equipo WHERE juez_id = [id_del_juez];
   ```

2. Verificar que el proyecto está en estado correcto:
   ```sql
   SELECT estado FROM proyectos WHERE id = [id_del_proyecto];
   ```

3. Verificar que el juez NO ha evaluado ya:
   ```sql
   SELECT * FROM evaluaciones 
   WHERE juez_id = [id_del_juez] 
   AND equipo_id = [id_del_equipo];
   ```

4. Limpiar cache:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

---

### Problema: Notificaciones duplicadas

**Causa:** Asignar el mismo equipo múltiples veces

**Solución:** El código ya previene esto comparando equipos anteriores con nuevos

---

### Problema: Click en notificación da error 404

**Causa:** Ruta `juez.evaluar` no existe

**Solución:**

1. Verificar que la ruta existe:
   ```bash
   php artisan route:list | grep juez.evaluar
   ```

2. Debe mostrar:
   ```
   GET  /juez/evaluar/{equipo}  juez.evaluar
   ```

---

## ✨ Beneficios

1. **Jueces informados:** Saben inmediatamente cuando tienen trabajo
2. **Menos emails:** Todo en la plataforma
3. **Proceso más rápido:** Notificaciones en tiempo real
4. **Mejor UX:** Jueces no tienen que estar revisando constantemente
5. **Trazabilidad:** Historial de todas las asignaciones

---

## 📝 Notas Importantes

1. **Las notificaciones solo se envían a jueces que tienen el equipo asignado**
2. **No se notifica si el juez ya evaluó el equipo**
3. **Las notificaciones persisten hasta que el juez las marque como leídas**
4. **El contador se actualiza automáticamente cada 10 segundos**
5. **Click en la notificación marca como leída y redirige**

---

## 🎉 Resultado Final

Con estos cambios, los jueces ahora reciben notificaciones en tiempo real cuando:

- ✅ Se les asigna un nuevo equipo
- ✅ Un proyecto está listo para evaluar

Todo integrado con el sistema de notificaciones existente, con:
- ✅ Contador en la campanita
- ✅ Vista completa de notificaciones
- ✅ Colores distintivos por tipo
- ✅ Redirección a la acción correspondiente
- ✅ Marcado automático como leída

---

**¡Sistema de notificaciones para juez completamente funcional!** 🚀
