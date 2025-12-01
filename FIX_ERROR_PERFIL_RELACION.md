# 🐛 FIX: ERROR "Call to undefined relationship [perfil]"

## 🔍 ERROR DETECTADO

```
Illuminate\Database\Eloquent\RelationNotFoundException
Call to undefined relationship [perfil] on model [App\Models\Participante].
```

**Ubicación:** `JuezController@evaluar()` línea 75 y `evaluar.blade.php` línea 63

---

## 🕵️ ANÁLISIS DEL PROBLEMA

### **Problema en el Controlador:**

```php
// ❌ INCORRECTO
$equipo->load(['evento', 'participantes.user', 'participantes.perfil', 'proyecto']);
```

**Error:** Intenta cargar `participantes.perfil` pero el modelo `Participante` NO tiene una relación directa llamada `perfil()`.

---

### **Problema en la Vista:**

```blade
{{-- ❌ INCORRECTO --}}
@if($participante->perfil)
    <div class="text-xs text-gray-500">{{ $participante->perfil->nombre }}</div>
@endif
```

**Error:** Intenta acceder a `$participante->perfil` pero esa relación no existe.

---

## 📊 ESTRUCTURA DE LA BASE DE DATOS

### **Tabla: `equipo_participante` (pivot)**
```sql
id
equipo_id
participante_id
perfil_id     ← El perfil está aquí, en el pivot!
estado
timestamps
```

El perfil **NO es una relación directa del participante**, sino que está en la **tabla pivot** que vincula participantes con equipos.

**Razón:** Un participante puede tener diferentes perfiles en diferentes equipos:
- En equipo A → Frontend Developer
- En equipo B → Backend Developer
- En equipo C → Designer

---

## ✅ SOLUCIÓN IMPLEMENTADA

### **1. Controlador corregido:**

```php
// ✅ CORRECTO
$equipo->load(['evento', 'participantes.user', 'proyecto']);
```

**Eliminamos** `'participantes.perfil'` porque no existe esa relación.

---

### **2. Vista corregida:**

```blade
{{-- ✅ CORRECTO --}}
@if($participante->pivot && $participante->pivot->perfil_id)
    <div class="text-xs text-gray-500">
        {{ \App\Models\Perfil::find($participante->pivot->perfil_id)->nombre ?? 'Sin perfil' }}
    </div>
@endif
```

**Cómo funciona:**
1. Verificamos que existe `$participante->pivot` (la relación pivot)
2. Verificamos que hay un `perfil_id` en el pivot
3. Buscamos el perfil directamente con `Perfil::find()`
4. Mostramos el nombre del perfil

---

## 🔄 ALTERNATIVA MEJORADA (Opcional)

Si quieres una solución más eficiente, puedes eager load el perfil desde el modelo `Equipo`:

### **Opción 1: Cargar perfil en la relación**

En `app/Models/Equipo.php`:

```php
public function participantes()
{
    return $this->belongsToMany(Participante::class, 'equipo_participante')
        ->withPivot(['perfil_id', 'estado'])
        ->with('pivot.perfil') // ← Eager load el perfil del pivot
        ->withTimestamps();
}
```

Luego en el modelo `EquipoParticipante` (si lo creas):

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EquipoParticipante extends Pivot
{
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }
}
```

### **Opción 2: Método helper en Participante**

En `app/Models/Participante.php`:

```php
/**
 * Obtener el perfil de este participante en un equipo específico
 */
public function perfilEnEquipo(int $equipoId)
{
    $pivot = $this->equipos()
        ->where('equipos.id', $equipoId)
        ->first()
        ?->pivot;
    
    return $pivot ? Perfil::find($pivot->perfil_id) : null;
}
```

Uso en la vista:

```blade
@if($perfil = $participante->perfilEnEquipo($equipo->id))
    <div class="text-xs text-gray-500">{{ $perfil->nombre }}</div>
@endif
```

---

## 📝 RESUMEN

### **Antes (❌ Error):**
```php
// Controlador
$equipo->load(['participantes.perfil']); // ← Relación inexistente

// Vista
$participante->perfil->nombre // ← Acceso a relación inexistente
```

### **Ahora (✅ Funciona):**
```php
// Controlador
$equipo->load(['participantes.user', 'proyecto']); // ← Sin perfil

// Vista
\App\Models\Perfil::find($participante->pivot->perfil_id)->nombre // ← Acceso correcto
```

---

## 🎓 LECCIÓN APRENDIDA

**Cuando trabajas con tablas pivot:**
- Los campos del pivot se acceden con `->pivot->campo`
- NO puedes hacer eager load directo de campos del pivot como si fueran relaciones
- Si necesitas un modelo del pivot, accede por `->pivot->relacion`

**Estructura de acceso:**
```
$equipo                     (Modelo Equipo)
  ->participantes           (Colección de Participantes)
    ->pivot                 (Modelo Pivot equipo_participante)
      ->perfil_id           (Campo en el pivot)
```

---

## ✅ ARCHIVOS MODIFICADOS

| Archivo | Cambio |
|---------|--------|
| `JuezController.php` línea 75 | ✅ Eliminado `'participantes.perfil'` |
| `evaluar.blade.php` línea 63 | ✅ Cambiado a `$participante->pivot->perfil_id` |

---

**¡Error resuelto! Ahora el formulario de evaluación carga correctamente.** 🎉✅
