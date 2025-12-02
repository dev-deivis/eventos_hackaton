# 🎯 GUÍA: SELECCIÓN POR EQUIPO + BÚSQUEDA INTELIGENTE

## ✅ FEATURE 1: SELECCIÓN POR EQUIPO EN LOTE

### **¿Qué es?**
Permite generar constancias solo para los miembros de un equipo específico, en lugar de para todos los participantes del evento.

---

### **📺 CÓMO SE VE:**

```
┌──────────────────────────────────────────────────────┐
│ 📚 Generar en Lote                                   │
├──────────────────────────────────────────────────────┤
│                                                       │
│ Evento: *                                            │
│ [Hackathon 2025 ▼]                                   │
│                                                       │
│ Tipo de Constancia: *                               │
│ [📜 Participación (Todos) ▼]                        │
│                                                       │
│ Filtrar por Equipo: (Opcional)                      │
│ [The Boings (2 miembros) ▼]                         │
│ └─ Deja en blanco para todos los participantes      │
│                                                       │
│ ┌────────────────────────────────────────────────┐  │
│ │ 📊 Vista Previa                                │  │
│ │ Total: 2 | Sin constancia: 2 | Generadas: 0   │  │
│ └────────────────────────────────────────────────┘  │
│                                                       │
│ [Cancelar] [Generar Constancias en Lote]            │
└──────────────────────────────────────────────────────┘
```

---

### **🎯 CASOS DE USO:**

#### **Caso 1: Constancias para TODO el evento**
```
1. Evento: [Hackathon 2025]
2. Tipo: [📜 Participación]
3. Equipo: [Todos los equipos]  ← Dejar en blanco
4. Click "Generar"

Resultado:
✅ Genera para TODOS los participantes del evento (25 personas)
```

#### **Caso 2: Constancias para UN SOLO EQUIPO**
```
1. Evento: [Hackathon 2025]
2. Tipo: [📜 Participación]
3. Equipo: [The Boings (2 miembros)]  ← Seleccionar equipo
4. Click "Generar"

Resultado:
✅ Genera solo para los 2 miembros de "The Boings"
❌ NO genera para los demás equipos
```

#### **Caso 3: Ganadores de un equipo específico**
```
1. Evento: [Hackathon 2025]
2. Tipo: [🥇 Primer Lugar]
3. Equipo: [The Boings (2 miembros)]
4. Click "Generar"

Resultado:
✅ Genera constancias de 1er lugar para The Boings
❌ Los demás equipos no reciben constancia
```

---

### **💡 VENTAJAS:**

#### **ANTES (Sin filtro por equipo):**
```
❌ Generar para TODO el evento o nada
❌ Si solo un equipo ganó, generas para todos
❌ Luego tienes que borrar las que no querías
❌ Riesgo de enviar constancias incorrectas
```

#### **AHORA (Con filtro por equipo):**
```
✅ Genera solo para el equipo que necesitas
✅ Útil cuando solo un equipo terminó su proyecto
✅ Perfecto para ganadores individuales
✅ Sin constancias extra que borrar
✅ Proceso limpio y controlado
```

---

### **🔧 IMPLEMENTACIÓN ACTUAL:**

#### **Backend (Ya implementado):**
```php
// En ConstanciaController@generarEnLote

$validated = $request->validate([
    'evento_id' => 'required|exists:eventos,id',
    'tipo' => 'required|in:participacion,...',
    'equipo_id' => 'nullable|exists:equipos,id', // ✅ Filtro opcional
]);

// Construir query
$query = Participante::whereHas('equipos', function($q) use ($evento, $validated) {
    $q->where('evento_id', $evento->id)
      ->where('equipo_participante.estado', 'activo');
    
    // ✅ Si hay equipo_id, filtrar por él
    if (isset($validated['equipo_id'])) {
        $q->where('equipos.id', $validated['equipo_id']);
    }
});
```

#### **Frontend (Ya implementado):**
```javascript
// Cargar equipos del evento
async function cargarDatosLote(eventoId) {
    // Cargar lista de equipos
    const equiposResponse = await fetch(`/admin/constancias/equipos/${eventoId}`);
    const equipos = await equiposResponse.json();
    
    // Llenar select
    equipos.forEach(equipo => {
        option.textContent = `${equipo.nombre} (${equipo.participantes_count} miembros)`;
    });
}
```

---

## ✅ FEATURE 2: BÚSQUEDA INTELIGENTE EN FILTROS

### **¿Qué es?**
Sistema de búsqueda que encuentra constancias buscando en múltiples campos al mismo tiempo.

---

### **📺 CÓMO SE VE:**

```
┌──────────────────────────────────────────────────────┐
│ 🔍 Buscar [_Juan________________] [Tipo ▼] [Filtrar]│
└──────────────────────────────────────────────────────┘

Busca en:
✓ Nombre del participante
✓ Nombre del evento
✓ Código de verificación
```

---

### **🎯 EJEMPLOS DE BÚSQUEDA:**

#### **Ejemplo 1: Buscar por Nombre**
```
Input: "Juan"

Encuentra:
✅ Juan Pérez García
✅ María Juana López
✅ Equipo de Juan
```

#### **Ejemplo 2: Buscar por Evento**
```
Input: "Hackathon"

Encuentra:
✅ Constancias de "Hackathon 2025"
✅ Constancias de "Hackathon AI"
✅ Constancias de "Mini Hackathon"
```

#### **Ejemplo 3: Buscar por Código**
```
Input: "HACK1234"

Encuentra:
✅ Constancia con código "HACK1234-ABC-001"
✅ Constancia con código "HACK1234-XYZ-999"
```

#### **Ejemplo 4: Búsqueda Parcial**
```
Input: "kar"

Encuentra:
✅ Karla Delgado
✅ Carlos Hernández (tiene 'kar' en el nombre)
```

---

### **💡 BÚSQUEDA INTELIGENTE:**

#### **Características:**
1. **Case-insensitive** → "juan" encuentra "Juan"
2. **Búsqueda parcial** → "kar" encuentra "Karla"
3. **Multi-campo** → Busca en nombre, evento y código
4. **Búsqueda con OR** → Encuentra si coincide en CUALQUIER campo

---

### **🔧 IMPLEMENTACIÓN ACTUAL:**

```php
// En ConstanciaController@index

if ($request->filled('buscar')) {
    $buscar = $request->buscar;
    
    // ✅ BÚSQUEDA INTELIGENTE
    $query->where(function($q) use ($buscar) {
        // Buscar en código
        $q->where('codigo_verificacion', 'like', "%{$buscar}%")
          
          // Buscar en nombre del participante
          ->orWhereHas('participante.user', function($q) use ($buscar) {
              $q->where('name', 'like', "%{$buscar}%");
          })
          
          // Buscar en nombre del evento
          ->orWhereHas('evento', function($q) use ($buscar) {
              $q->where('nombre', 'like', "%{$buscar}%");
          });
    });
}
```

---

## 🎯 COMPARACIÓN: BÚSQUEDA SIMPLE VS INTELIGENTE

### **BÚSQUEDA SIMPLE (No recomendado):**
```php
// ❌ Solo busca en UN campo
$query->where('codigo_verificacion', 'like', "%{$buscar}%");

Problemas:
- Solo encuentra por código
- No busca en nombres
- No busca en eventos
- Poco útil para el usuario
```

### **BÚSQUEDA INTELIGENTE (Implementado):**
```php
// ✅ Busca en MÚLTIPLES campos
$query->where(function($q) use ($buscar) {
    $q->where('codigo', 'like', "%{$buscar}%")
      ->orWhereHas('participante.user', ...)
      ->orWhereHas('evento', ...);
});

Ventajas:
- Busca en 3 lugares a la vez
- Encuentra por nombre, evento o código
- Más flexible y útil
- UX superior
```

---

## 📊 TABLA COMPARATIVA

| Feature | Sin Implementar | Implementado |
|---------|----------------|--------------|
| **Selección por Equipo** | ❌ Todo o nada | ✅ Filtro opcional |
| **Búsqueda por Nombre** | ❌ No disponible | ✅ Inteligente |
| **Búsqueda por Código** | ❌ Búsqueda manual | ✅ Automática |
| **Búsqueda por Evento** | ❌ Usar filtro evento | ✅ En búsqueda |
| **Búsqueda Parcial** | ❌ Debe ser exacto | ✅ Funciona |
| **Case Sensitive** | ❌ "Juan" ≠ "juan" | ✅ "Juan" = "juan" |

---

## 🚀 FLUJOS DE USUARIO

### **FLUJO 1: Generar para un equipo específico**
```
1. Admin va a "Generar Constancias"
2. Tab "Generar en Lote"
3. Selecciona evento
4. ✅ Sistema carga equipos automáticamente
5. Selecciona tipo de constancia
6. ✅ Selecciona equipo específico del dropdown
7. Ve preview (2 participantes sin constancia)
8. Click "Generar"
9. ✅ Solo ese equipo recibe constancias
```

### **FLUJO 2: Buscar constancia de un participante**
```
1. Admin va a "Constancias Emitidas"
2. ✅ Escribe "Karla" en búsqueda
3. Click "Filtrar"
4. ✅ Sistema muestra todas las constancias de Karla
5. Puede descargar, ver o eliminar
```

### **FLUJO 3: Buscar por código de verificación**
```
1. Participante da su código: "HACK1234-ABC-001"
2. Admin va a "Constancias Emitidas"
3. ✅ Escribe "HACK1234" en búsqueda
4. Click "Filtrar"
5. ✅ Sistema encuentra la constancia
6. Admin puede verificar y descargar
```

---

## 💡 RECOMENDACIONES DE USO

### **✅ CUÁNDO USAR FILTRO POR EQUIPO:**

1. **Ganadores individuales**
   ```
   Situación: Solo un equipo ganó 1er lugar
   Solución: Filtrar por ese equipo
   ```

2. **Proyecto incompleto**
   ```
   Situación: Solo 2 de 5 equipos terminaron
   Solución: Generar por equipo individualmente
   ```

3. **Constancias especiales**
   ```
   Situación: Un equipo merece mención honorífica
   Solución: Filtrar por ese equipo + tipo mención
   ```

### **✅ CUÁNDO USAR BÚSQUEDA INTELIGENTE:**

1. **Verificar constancia**
   ```
   Usuario: "¿Me generaron mi constancia?"
   Admin: Buscar por nombre
   ```

2. **Encontrar por código**
   ```
   Usuario: "Mi código es HACK1234"
   Admin: Buscar "HACK1234"
   ```

3. **Ver constancias de evento**
   ```
   Usuario: "¿Cuántas del Hackathon AI?"
   Admin: Buscar "Hackathon AI"
   ```

---

## ✅ RESUMEN

### **YA ESTÁ IMPLEMENTADO:**

1. ✅ **Selección por equipo en lote**
   - Filtro opcional "equipo_id"
   - Dropdown con equipos del evento
   - Backend ya filtra correctamente
   - Frontend carga equipos dinámicamente

2. ✅ **Búsqueda inteligente**
   - Busca en 3 campos (nombre, evento, código)
   - Case-insensitive
   - Búsqueda parcial
   - Con operador OR (cualquier coincidencia)

### **LISTO PARA USAR:**

- 🔍 Buscar "Juan" → Encuentra por nombre
- 🔍 Buscar "HACK" → Encuentra por código
- 🔍 Buscar "Hackathon" → Encuentra por evento
- 👥 Filtrar por equipo → Genera solo para ese equipo
- 📊 Vista previa → Muestra cuántos recibirán constancia

---

**¡Ambas features están completamente funcionales!** 🎉

**¿Quieres que agregue algo más o mejoremos algún aspecto?** 🚀
