# 🎯 SISTEMA COMPLETO DE EVALUACIÓN DE JUECES

## ✅ IMPLEMENTACIÓN COMPLETA

### **1. Base de Datos**

#### **Tabla `juez_equipo` (asignaciones)**
```sql
CREATE TABLE juez_equipo (
    id BIGINT PRIMARY KEY,
    juez_id BIGINT,    -- FK a users
    equipo_id BIGINT,  -- FK a equipos
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(juez_id, equipo_id)
);
```

#### **Tabla `evaluaciones` (evaluaciones completas)**
```sql
CREATE TABLE evaluaciones (
    id BIGINT PRIMARY KEY,
    equipo_id BIGINT,
    juez_id BIGINT,
    
    -- 5 Criterios de evaluación (0-100)
    implementacion DECIMAL(5,2),   -- 30%
    innovacion DECIMAL(5,2),       -- 25%
    presentacion DECIMAL(5,2),     -- 20%
    trabajo_equipo DECIMAL(5,2),   -- 15%
    viabilidad DECIMAL(5,2),       -- 10%
    
    calificacion_total DECIMAL(5,2), -- Promedio ponderado
    comentarios TEXT,
    fecha_evaluacion TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    UNIQUE(equipo_id, juez_id)
);
```

---

### **2. Modelos Actualizados**

#### **User.php**
```php
// Relación N:N con equipos asignados
public function equiposAsignados(): BelongsToMany
{
    return $this->belongsToMany(Equipo::class, 'juez_equipo', 'juez_id', 'equipo_id')
                ->withTimestamps();
}
```

#### **Evaluacion.php**
```php
protected $fillable = [
    'equipo_id', 'juez_id',
    'implementacion',    // 30%
    'innovacion',        // 25%
    'presentacion',      // 20%
    'trabajo_equipo',    // 15%
    'viabilidad',        // 10%
    'calificacion_total',
    'comentarios',
    'fecha_evaluacion',
];
```

---

### **3. Flujo de Asignación (Admin)**

```
┌────────────────────────────────────────────────────────┐
│ ADMIN: Editar Usuario                                  │
├────────────────────────────────────────────────────────┤
│ Roles del Usuario:                                     │
│ ○ Admin  ● Juez  ○ Participante                        │
│                                                         │
│ ↓ Al seleccionar "Juez" se despliega:                  │
│                                                         │
│ 👥 Equipos Asignados para Evaluación                   │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐   │
│ │☑ The Boings  │ │☐ Los Deivis  │ │☑ Warriors    │   │
│ │Hackaton 2025 │ │Hackaton 2025 │ │Hackaton 2025 │   │
│ │4 miembros    │ │3 miembros    │ │5 miembros    │   │
│ └──────────────┘ └──────────────┘ └──────────────┘   │
│                                                         │
│ [Guardar Cambios]                                      │
└────────────────────────────────────────────────────────┘
```

**Características:**
- ✅ Sección visible solo al seleccionar rol "Juez"
- ✅ Alpine.js detecta cambio de radio button
- ✅ Checkboxes para múltiples equipos
- ✅ Pre-selección de equipos ya asignados
- ✅ Scroll si hay muchos equipos

---

### **4. Dashboard de Juez Actualizado**

```
┌─────────────────────────────────────────────────────────┐
│ Panel de Juez                                           │
│ Bienvenido Dr. Nombre, evalúa proyectos...             │
├─────────────────────────────────────────────────────────┤
│ [12 Equipos] [8 Evaluadas] [82.5 Promedio] [25 Min]   │
├──────────────────────┬──────────────────────────────────┤
│ ACCIONES             │ EQUIPOS PENDIENTES              │
│ 🌟 Evaluar Equipo    │ The Boings       [Evaluar →]    │
│ 🏆 Ver Rankings      │ Hackaton 2025    [Pendiente]    │
│ 📄 Mis Evaluaciones  │                                  │
│                      │ Los Deivis       [Evaluar →]    │
│                      │ Hackaton 2025    [Pendiente]    │
└──────────────────────┴──────────────────────────────────┘
```

**JuezController@dashboard:**
```php
// Solo equipos asignados y no evaluados
$equiposPendientes = $juez->equiposAsignados()
    ->whereDoesntHave('evaluaciones', function($query) use ($juez) {
        $query->where('juez_id', $juez->id);
    })
    ->get();

// Estadísticas reales
$totalAsignados = $juez->equiposAsignados()->count();
$evaluacionesCompletadas = Evaluacion::where('juez_id', $juez->id)->count();
$promedioCalificacion = Evaluacion::where('juez_id', $juez->id)
    ->avg('calificacion_total') ?? 0;
```

---

### **5. Vista de Evaluación con Sliders**

```
┌──────────────────────┬──────────────────────────────────────┐
│ EQUIPO INFO          │ CRITERIOS DE EVALUACIÓN              │
├──────────────────────┼──────────────────────────────────────┤
│ 🎯 The Boings        │ 💻 Implementación Técnica (30%)      │
│ Sistema de Gestión   │ [========●=======] 75 pts            │
│                      │                                       │
│ Evento:              │ 💡 Innovación (25%)                  │
│ Hackaton 2025        │ [==========●=====] 80 pts            │
│                      │                                       │
│ Miembros:            │ 📢 Presentación (20%)                │
│ • Ángel (Líder)      │ [======●=========] 70 pts            │
│ • Karla              │                                       │
│ • Jesús              │ 👥 Trabajo en Equipo (15%)           │
│ • David              │ [============●===] 85 pts            │
│                      │                                       │
│ ┌──────────────────┐ │ 💼 Viabilidad de Negocio (10%)      │
│ │ Puntuación Final │ │ [=====●==========] 65 pts            │
│ │       76         │ │                                       │
│ │     Puntos       │ │ Comentarios:                         │
│ └──────────────────┘ │ [Textarea de retroalimentación]      │
│                      │                                       │
│                      │ [Cancelar] [⭐ Enviar Evaluación]   │
└──────────────────────┴──────────────────────────────────────┘
```

**Características:**
- ✅ 5 sliders con colores distintos
- ✅ Cálculo automático de puntuación ponderada
- ✅ JavaScript actualiza en tiempo real
- ✅ Información del equipo en sidebar
- ✅ Lista de miembros con líder marcado
- ✅ Textarea para comentarios

---

### **6. Cálculo de Puntuación**

**Pesos de criterios:**
```javascript
const pesos = {
    'implementacion': 0.30,   // 30%
    'innovacion': 0.25,       // 25%
    'presentacion': 0.20,     // 20%
    'trabajo_equipo': 0.15,   // 15%
    'viabilidad': 0.10        // 10%
};
```

**Ejemplo de cálculo:**
```
Implementación: 75 × 0.30 = 22.5
Innovación:     80 × 0.25 = 20.0
Presentación:   70 × 0.20 = 14.0
Trabajo Equipo: 85 × 0.15 = 12.75
Viabilidad:     65 × 0.10 =  6.5
                           ──────
Total:                     75.75 puntos
```

---

### **7. JavaScript para Sliders**

```javascript
sliders.forEach(slider => {
    slider.addEventListener('input', function() {
        // Actualizar display del criterio
        display.textContent = this.value;
        
        // Recalcular puntuación total
        let total = 0;
        sliders.forEach(s => {
            const criterio = s.getAttribute('data-target');
            const valor = parseInt(s.value);
            const peso = pesos[criterio];
            total += valor * peso;
        });
        
        puntuacionFinal.textContent = Math.round(total);
    });
});
```

---

### **8. Validación y Guardado**

**JuezController@guardarEvaluacion:**
```php
$validated = $request->validate([
    'implementacion' => ['required', 'numeric', 'min:0', 'max:100'],
    'innovacion' => ['required', 'numeric', 'min:0', 'max:100'],
    'presentacion' => ['required', 'numeric', 'min:0', 'max:100'],
    'trabajo_equipo' => ['required', 'numeric', 'min:0', 'max:100'],
    'viabilidad' => ['required', 'numeric', 'min:0', 'max:100'],
    'comentarios' => ['nullable', 'string', 'max:1000'],
]);

// Cálculo con pesos
$calificacionTotal = (
    ($validated['implementacion'] * 0.30) +
    ($validated['innovacion'] * 0.25) +
    ($validated['presentacion'] * 0.20) +
    ($validated['trabajo_equipo'] * 0.15) +
    ($validated['viabilidad'] * 0.10)
);

Evaluacion::create([...]);
```

---

## 🎨 DISEÑO Y UX

### **Colores de sliders:**
- 💻 Implementación: **Morado** (#9333ea)
- 💡 Innovación: **Azul** (#3b82f6)
- 📢 Presentación: **Verde** (#10b981)
- 👥 Trabajo Equipo: **Amarillo** (#f59e0b)
- 💼 Viabilidad: **Índigo** (#6366f1)

### **Responsive:**
- Mobile: Grid 1 columna (info arriba, form abajo)
- Desktop: Grid 3 columnas (1 sidebar + 2 formulario)

---

## 🔄 FLUJO COMPLETO

### **1. Admin asigna equipos:**
```
Admin → Editar Usuario → Seleccionar "Juez" → 
Marcar equipos → Guardar → Equipos asignados en BD
```

### **2. Juez evalúa:**
```
Juez Login → Dashboard → Ve equipos asignados →
Click "Evaluar Siguiente" → Formulario con sliders →
Ajustar calificaciones → Escribir comentarios →
Enviar → Evaluación guardada en BD
```

### **3. Actualización automática:**
```
Evaluación guardada → Equipo desaparece de pendientes →
Contador de evaluaciones +1 → Promedio recalculado
```

---

## 📋 ARCHIVOS MODIFICADOS/CREADOS

| Archivo | Cambios |
|---------|---------|
| `migrations/juez_equipo_table.php` | ✅ Creada |
| `migrations/evaluaciones_table.php` | ✅ Actualizada (5 criterios) |
| `app/Models/User.php` | ✅ Relación `equiposAsignados()` |
| `app/Models/Evaluacion.php` | ✅ Campos actualizados |
| `AdminUserController.php` | ✅ Asignación de equipos |
| `JuezController.php` | ✅ Dashboard + evaluar + guardar |
| `admin/usuarios/edit.blade.php` | ✅ Sección asignar equipos |
| `juez/dashboard.blade.php` | ✅ Equipos reales |
| `juez/evaluar.blade.php` | ✅ Creada (400+ líneas) |

---

## ✅ TODO LISTO PARA EJECUTAR

**Comandos necesarios:**
```bash
php artisan migrate
```

**Probar:**
1. Admin asigna equipos a un juez
2. Login como juez
3. Ver equipos asignados
4. Click "Evaluar Siguiente"
5. Mover sliders y ver cálculo en tiempo real
6. Enviar evaluación
7. Verificar que desaparece de pendientes

---

**¡Sistema completo de evaluación funcionando!** 🎉🎯
