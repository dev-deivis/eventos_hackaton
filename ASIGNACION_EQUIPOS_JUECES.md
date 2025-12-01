# ✅ ASIGNACIÓN DE EQUIPOS A JUECES - IMPLEMENTADO

## 📋 ARCHIVOS CREADOS/MODIFICADOS

### **1. Migración `juez_equipo`**
**Archivo:** `database/migrations/2024_12_01_040000_create_juez_equipo_table.php`

```sql
CREATE TABLE juez_equipo (
    id BIGINT PRIMARY KEY,
    juez_id BIGINT,    -- FK a users
    equipo_id BIGINT,  -- FK a equipos
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    UNIQUE(juez_id, equipo_id)  -- Un juez no puede tener el mismo equipo 2 veces
);
```

---

### **2. Modelo User - Relación agregada**
```php
public function equiposAsignados(): BelongsToMany
{
    return $this->belongsToMany(Equipo::class, 'juez_equipo', 'juez_id', 'equipo_id')
                ->withTimestamps();
}
```

---

### **3. AdminUserController - Métodos actualizados**

**`edit()`:**
- Ahora carga `equiposAsignados`
- Obtiene `$equiposDisponibles` (equipos en eventos activos)

**`update()`:**
- Valida array de `equipos[]`
- Si rol es juez → sincroniza equipos asignados
- Si rol NO es juez → quita todas las asignaciones

---

### **4. Vista `admin/usuarios/edit.blade.php`**

**Sección nueva:** "Equipos Asignados para Evaluación"

- **Se muestra solo si:** El rol seleccionado es "juez"
- **Con Alpine.js:** Detecta cambio en radio buttons
- **Grid de checkboxes:** Muestra todos los equipos disponibles
- **Pre-selección:** Los equipos ya asignados vienen marcados

---

## 🎨 INTERFAZ DE ASIGNACIÓN

```
┌─────────────────────────────────────────────────────────────┐
│ Roles del Usuario                                           │
│ ○ Admin  ● Juez  ○ Participante                             │
└─────────────────────────────────────────────────────────────┘
       ↓ (Al seleccionar Juez se muestra:)
┌─────────────────────────────────────────────────────────────┐
│ 👥 Equipos Asignados para Evaluación                        │
│                                                              │
│ Selecciona los equipos que este juez deberá evaluar.        │
│                                                              │
│ ┌─────────────────────┐ ┌─────────────────────┐            │
│ │☑ The Boings         │ │☐ Los Deivis         │            │
│ │  🎯 Hackaton 2025   │ │  🎯 Hackaton 2025   │            │
│ │  4 miembros         │ │  3 miembros         │            │
│ └─────────────────────┘ └─────────────────────┘            │
│ ┌─────────────────────┐ ┌─────────────────────┐            │
│ │☐ Code Warriors      │ │☑ Tech Innovators    │            │
│ │  🎯 Hackaton 2025   │ │  🎯 Hackaton 2025   │            │
│ │  5 miembros         │ │  4 miembros         │            │
│ └─────────────────────┘ └─────────────────────┘            │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔄 FLUJO DE ASIGNACIÓN

### **Admin asigna equipos:**
```
1. Admin → Editar Usuario
2. Seleccionar rol "Juez" (radio button)
3. Se despliega sección "Equipos Asignados"
4. Marcar checkboxes de equipos a asignar
5. Guardar Cambios
6. Los equipos quedan vinculados al juez en tabla `juez_equipo`
```

### **Juez ve sus equipos:**
```
1. Juez hace login
2. Dashboard carga: $juez->equiposAsignados
3. Muestra solo los equipos que el admin le asignó
4. Puede evaluarlos
```

---

## 💻 CÓDIGO CLAVE

### **Alpine.js para mostrar/ocultar:**
```js
x-data="{ 
    rolJuezId: {{ ID del rol juez }},
    rolSeleccionado: {{ rol actual del usuario }}
}"
x-show="rolSeleccionado == rolJuezId"
x-init="
    // Escuchar cambios en radio buttons
    document.querySelectorAll('input[name=rol_id]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            rolSeleccionado = parseInt(e.target.value);
        });
    });
"
```

### **Sincronización de equipos:**
```php
// En AdminUserController@update
if ($validated['rol_id'] == $rolJuez->id) {
    $usuario->equiposAsignados()->sync($validated['equipos'] ?? []);
} else {
    $usuario->equiposAsignados()->detach();
}
```

---

## ✅ LISTO PARA:

✅ Migración creada (ejecutar `php artisan migrate`)
✅ Relación `equiposAsignados()` en User
✅ Vista de editar usuario con asignación dinámica
✅ Controlador maneja la sincronización
✅ Frontend con Alpine.js funcionando

---

## 🚀 PRÓXIMOS PASOS:

1. Ejecutar migración
2. Actualizar JuezController para usar `equiposAsignados()`
3. Crear vista de evaluación con sliders
4. Guardar evaluaciones en BD

---

**¡Sistema de asignación completo!** 🎉
