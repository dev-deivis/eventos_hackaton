# 🔧 FIX: CONSTANCIA INDIVIDUAL

## 🐛 PROBLEMA IDENTIFICADO

La generación de constancias individuales tenía problemas con:
1. ❌ Query incorrecta en `obtenerParticipantes()` - usaba `->where('estado', 'activo')` en lugar de `->where('equipo_participante.estado', 'activo')`
2. ❌ Estadísticas imprecisas - contaba total de constancias en lugar de participantes únicos

---

## ✅ CORRECCIONES APLICADAS

### **1. Método `obtenerParticipantes()` Corregido**

#### **ANTES (Incorrecto):**
```php
$participantes = Participante::with('user')
    ->whereHas('equipos', function($query) use ($eventoId) {
        $query->where('evento_id', $eventoId)
              ->where('estado', 'activo'); // ❌ INCORRECTO
    })
```

**Problema:** El campo `estado` no existe directamente en la tabla `equipos`. Está en la tabla pivot `equipo_participante`.

#### **DESPUÉS (Correcto):**
```php
$participantes = Participante::with('user', 'carrera')
    ->whereHas('equipos', function($query) use ($eventoId) {
        $query->where('evento_id', $eventoId)
              ->where('equipo_participante.estado', 'activo'); // ✅ CORRECTO
    })
```

**Solución:** Usar `equipo_participante.estado` para acceder al campo en la tabla pivot.

---

### **2. Método `obtenerEstadisticas()` Mejorado**

#### **ANTES (Impreciso):**
```php
$totalParticipantes = Participante::whereHas('equipos', function($query) use ($eventoId) {
    $query->where('evento_id', $eventoId);
})->count();

$conConstancia = Constancia::where('evento_id', $eventoId)->count();
```

**Problemas:**
- ❌ Cuenta participantes sin verificar que estén activos
- ❌ Cuenta total de constancias (un participante puede tener 3 constancias y contaría como 3)

#### **DESPUÉS (Preciso):**
```php
// Total de participantes ACTIVOS en el evento
$totalParticipantes = Participante::whereHas('equipos', function($query) use ($eventoId) {
    $query->where('evento_id', $eventoId)
          ->where('equipo_participante.estado', 'activo'); // ✅ Solo activos
})->distinct()->count(); // ✅ Cuenta únicos

// Participantes únicos CON constancia
$conConstancia = Participante::whereHas('constancias', function($query) use ($eventoId) {
    $query->where('evento_id', $eventoId);
})->whereHas('equipos', function($query) use ($eventoId) {
    $query->where('evento_id', $eventoId)
          ->where('equipo_participante.estado', 'activo');
})->distinct()->count(); // ✅ Cuenta participantes únicos, no constancias
```

**Mejoras:**
- ✅ Solo cuenta participantes activos
- ✅ Usa `distinct()` para contar participantes únicos
- ✅ Un participante con 3 constancias cuenta como 1

---

## 📊 COMPARACIÓN DE RESULTADOS

### **Escenario de Prueba:**
```
Evento: Hackathon 2025
- Participante A: activo, 2 constancias (participación + 1er lugar)
- Participante B: activo, 1 constancia (participación)
- Participante C: activo, sin constancias
- Participante D: inactivo (rechazado), 1 constancia
```

### **ANTES (Incorrecto):**
```
Total: 4 participantes
Con constancia: 4 constancias ❌
Sin constancia: 0 ❌
```

### **DESPUÉS (Correcto):**
```
Total: 3 participantes activos ✅
Con constancia: 2 participantes únicos ✅
Sin constancia: 1 participante ✅
```

---

## 🎯 FLUJO CORREGIDO: GENERACIÓN INDIVIDUAL

### **Paso 1: Seleccionar Evento**
```
Usuario: Selecciona "Hackathon 2025"
Sistema: 
  1. Carga participantes ACTIVOS del evento
  2. Excluye los que tienen estado 'pendiente' o 'rechazado'
  3. Muestra solo los válidos en el dropdown
```

### **Paso 2: Seleccionar Tipo**
```
Usuario: Selecciona "📜 Participación"
Sistema:
  1. Refresca lista de participantes
  2. Excluye los que YA tienen constancia de participación
  3. Solo muestra los que pueden recibirla
```

### **Paso 3: Seleccionar Participante**
```
Usuario: Selecciona "Juan Pérez - juan@example.com"
Sistema:
  1. Verifica que esté en el evento
  2. Verifica que esté activo
  3. Verifica que no tenga constancia de ese tipo
```

### **Paso 4: Generar**
```
Sistema:
  1. ✅ Crea constancia
  2. ✅ Genera código único (HACK1234-ABC-001)
  3. ✅ Guarda en BD
  4. ✅ Redirige con mensaje de éxito
```

---

## 🧪 CÓMO PROBAR

### **Test 1: Cargar Participantes**
```
1. Ir a: /admin/constancias/generar-nuevas
2. Tab "Constancia Individual"
3. Seleccionar evento
4. Debe cargar participantes activos
5. Verificar que no aparezcan rechazados
```

### **Test 2: Filtrar por Tipo**
```
1. Seleccionar evento
2. Seleccionar tipo "Participación"
3. Dropdown debe mostrar solo los SIN constancia de participación
4. Los que ya tienen, no deben aparecer
```

### **Test 3: Generar Constancia**
```
1. Seleccionar evento
2. Seleccionar tipo
3. Seleccionar participante
4. Click "Generar"
5. Debe redirigir con mensaje de éxito
6. Constancia debe aparecer en el listado
```

### **Test 4: Estadísticas**
```
1. Ir a tab "Generar en Lote"
2. Seleccionar evento
3. Ver estadísticas:
   - Total debe ser solo activos
   - Con constancia debe contar personas, no constancias
   - Sin constancia debe ser la diferencia
```

---

## 🔍 VALIDACIONES IMPLEMENTADAS

### **En `generarIndividual()`:**

1. ✅ **Evento existe**
   ```php
   'evento_id' => 'required|exists:eventos,id'
   ```

2. ✅ **Participante existe**
   ```php
   'participante_id' => 'required|exists:participantes,id'
   ```

3. ✅ **Tipo válido**
   ```php
   'tipo' => 'required|in:participacion,primer_lugar,...'
   ```

4. ✅ **Participante en evento**
   ```php
   $enEvento = $participante->equipos()
       ->where('evento_id', $validated['evento_id'])
       ->where('equipo_participante.estado', 'activo')
       ->exists();
   ```

5. ✅ **Sin duplicados**
   ```php
   $existe = Constancia::where('evento_id', $validated['evento_id'])
       ->where('participante_id', $validated['participante_id'])
       ->where('tipo', $validated['tipo'])
       ->exists();
   ```

6. ✅ **Código único**
   ```php
   do {
       $codigo = 'HACK' . ...;
   } while (self::where('codigo_verificacion', $codigo)->exists());
   ```

---

## ✅ RESULTADO FINAL

### **Ahora funciona correctamente:**

1. ✅ Carga participantes ACTIVOS
2. ✅ Filtra por tipo correctamente
3. ✅ Estadísticas precisas
4. ✅ Genera sin duplicados
5. ✅ Códigos únicos
6. ✅ Validaciones completas
7. ✅ Mensajes de error claros

---

## 📝 ARCHIVOS MODIFICADOS

**Único archivo modificado:**
- ✅ `app/Http/Controllers/ConstanciaController.php`
  - Método `obtenerParticipantes()` (líneas ~95-115)
  - Método `obtenerEstadisticas()` (líneas ~120-140)

---

## 💡 DIFERENCIAS CLAVE

### **Campo `estado` en relaciones:**

#### **❌ INCORRECTO:**
```php
// Esto NO funciona porque 'estado' no está en 'equipos'
->whereHas('equipos', function($q) {
    $q->where('estado', 'activo');
})
```

#### **✅ CORRECTO:**
```php
// Esto SÍ funciona porque accede al pivot
->whereHas('equipos', function($q) {
    $q->where('equipo_participante.estado', 'activo');
})
```

### **Estructura de tablas:**

```sql
-- Tabla: equipos
equipos (id, evento_id, nombre, descripcion, ...)
❌ NO tiene campo 'estado'

-- Tabla pivot: equipo_participante
equipo_participante (equipo_id, participante_id, estado, perfil_id, ...)
✅ SÍ tiene campo 'estado' (pendiente|activo|rechazado)
```

---

**✅ CONSTANCIA INDIVIDUAL CORREGIDA Y FUNCIONAL** 🎉
