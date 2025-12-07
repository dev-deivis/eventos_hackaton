# 🎯 ESTADÍSTICAS REALES DE PERFIL - PARTICIPANTE, JUEZ Y ADMIN

## ✅ IMPLEMENTACIÓN COMPLETADA

Se han implementado estadísticas reales y personalizadas para cada tipo de usuario en el perfil, mostrando información relevante según su rol.

---

## 📊 ESTADÍSTICAS IMPLEMENTADAS POR ROL

### **1. PARTICIPANTE**

#### **Estadísticas Principales:**
```php
✅ Eventos Participados     // Cuenta eventos únicos
✅ Total de Equipos         // Todos los equipos del participante
✅ Veces como Líder        // Equipos donde fue líder
✅ Constancias Obtenidas   // Total de constancias
✅ Proyectos Presentados   // Equipos con proyecto
✅ Total de Premios        // Suma de 1°, 2° y 3° lugar
```

#### **Logros y Premios:**
```
🥇 Primer Lugar   // Cantidad de veces que ganó 1er lugar
🥈 Segundo Lugar  // Cantidad de veces que ganó 2do lugar  
🥉 Tercer Lugar   // Cantidad de veces que ganó 3er lugar
👥 Líder de Equipo // Cantidad de equipos liderados
```

#### **Código del Controlador:**
```php
if ($user->isParticipante() && $user->participante) {
    $participante = $user->participante;
    
    // Obtener todos los equipos
    $equipos = $participante->equipos()->with(['evento', 'proyecto'])->get();
    
    // Contar eventos únicos
    $eventosParticipados = $equipos->unique('evento_id')->count();
    
    // Contar veces como líder
    $vecesLider = $equipos->filter(function($equipo) use ($participante) {
        return $equipo->lider_id === $participante->id;
    })->count();
    
    // Obtener proyectos con premios
    $proyectosConPremio = $equipos->filter(function($equipo) {
        if (!$equipo->proyecto) return false;
        return in_array($equipo->proyecto->lugar_obtenido, [1, 2, 3]);
    });
    
    $premiosObtenidos = [
        'primero' => $proyectosConPremio->where('proyecto.lugar_obtenido', 1)->count(),
        'segundo' => $proyectosConPremio->where('proyecto.lugar_obtenido', 2)->count(),
        'tercero' => $proyectosConPremio->where('proyecto.lugar_obtenido', 3)->count(),
    ];
    
    $stats = [
        'eventos_participados' => $eventosParticipados,
        'total_equipos' => $equipos->count(),
        'veces_lider' => $vecesLider,
        'proyectos_presentados' => $proyectosPresentados,
        'premios' => $premiosObtenidos,
        'total_premios' => array_sum($premiosObtenidos),
        'constancias' => $constancias,
    ];
}
```

#### **Vista - Tarjetas de Estadísticas:**
```html
<div class="grid grid-cols-2 gap-4">
    <!-- Eventos -->
    <div class="text-center p-4 bg-indigo-50 rounded-lg">
        <div class="text-3xl font-bold text-indigo-600">
            {{ $stats['eventos_participados'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Eventos</div>
    </div>

    <!-- Equipos -->
    <div class="text-center p-4 bg-purple-50 rounded-lg">
        <div class="text-3xl font-bold text-purple-600">
            {{ $stats['total_equipos'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Equipos</div>
    </div>

    <!-- Veces Líder -->
    <div class="text-center p-4 bg-pink-50 rounded-lg">
        <div class="text-3xl font-bold text-pink-600">
            {{ $stats['veces_lider'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Veces Líder</div>
    </div>

    <!-- Constancias -->
    <div class="text-center p-4 bg-green-50 rounded-lg">
        <div class="text-3xl font-bold text-green-600">
            {{ $stats['constancias'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Constancias</div>
    </div>
</div>
```

#### **Vista - Logros:**
```html
<!-- Primer Lugar -->
@if($stats['premios']['primero'] > 0)
<div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
    <div class="flex items-start gap-3">
        <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white text-xl">
            🥇
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-gray-900">
                {{ $stats['premios']['primero'] }}x Primer Lugar
            </h4>
            <p class="text-xs text-gray-600 mt-1">
                Ganaste el primer lugar en {{ $stats['premios']['primero'] }} evento(s)
            </p>
        </div>
    </div>
</div>
@endif
```

---

### **2. JUEZ**

#### **Estadísticas Principales:**
```php
✅ Eventos como Juez              // Eventos únicos donde evaluó
✅ Proyectos Evaluados            // Proyectos únicos evaluados
✅ Total de Evaluaciones          // Todas las evaluaciones realizadas
✅ Promedio de Calificaciones     // Promedio de calificaciones otorgadas
✅ Evaluaciones por Evento        // Desglose por evento
```

#### **Código del Controlador:**
```php
if ($user->isJuez()) {
    // Obtener todas las evaluaciones del juez
    $evaluaciones = \App\Models\Evaluacion::where('juez_id', $user->id)
        ->with(['proyecto.equipo.evento'])
        ->get();
    
    // Eventos únicos
    $eventosComoJuez = $evaluaciones->pluck('proyecto.equipo.evento')
        ->filter()
        ->unique('id')
        ->count();
    
    // Total de proyectos evaluados
    $proyectosEvaluados = $evaluaciones->unique('proyecto_id')->count();
    
    // Promedio de calificaciones otorgadas
    $promedioCalificaciones = $evaluaciones->avg('calificacion_total');
    
    // Evaluaciones por evento
    $evaluacionesPorEvento = $evaluaciones->groupBy(function($eval) {
        return $eval->proyecto->equipo->evento->nombre ?? 'Sin evento';
    })->map(function($evals) {
        return $evals->count();
    });
    
    $juezStats = [
        'eventos_como_juez' => $eventosComoJuez,
        'proyectos_evaluados' => $proyectosEvaluados,
        'total_evaluaciones' => $evaluaciones->count(),
        'promedio_calificaciones' => round($promedioCalificaciones, 2),
        'evaluaciones_por_evento' => $evaluacionesPorEvento,
    ];
}
```

#### **Vista - Tarjetas de Estadísticas:**
```html
<div class="grid grid-cols-2 gap-4">
    <!-- Eventos -->
    <div class="text-center p-4 bg-blue-50 rounded-lg">
        <div class="text-3xl font-bold text-blue-600">
            {{ $juezStats['eventos_como_juez'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Eventos</div>
    </div>

    <!-- Proyectos Evaluados -->
    <div class="text-center p-4 bg-indigo-50 rounded-lg">
        <div class="text-3xl font-bold text-indigo-600">
            {{ $juezStats['proyectos_evaluados'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Proyectos</div>
    </div>

    <!-- Total Evaluaciones -->
    <div class="text-center p-4 bg-purple-50 rounded-lg col-span-2">
        <div class="text-3xl font-bold text-purple-600">
            {{ $juezStats['total_evaluaciones'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Total de Evaluaciones Realizadas</div>
    </div>
</div>

<!-- Promedio de Calificaciones -->
<div class="flex justify-between items-center">
    <span class="text-sm text-gray-600">Promedio de Calificaciones</span>
    <span class="font-bold text-yellow-600 flex items-center gap-1">
        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">...</svg>
        {{ $juezStats['promedio_calificaciones'] }}/10
    </span>
</div>
```

#### **Vista - Evaluaciones por Evento:**
```html
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Evaluaciones por Evento</h3>
    <div class="space-y-3">
        @foreach($juezStats['evaluaciones_por_evento']->take(5) as $evento => $cantidad)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700 flex-1 truncate">
                    {{ $evento }}
                </span>
                <span class="ml-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold">
                    {{ $cantidad }}
                </span>
            </div>
        @endforeach
    </div>
</div>
```

---

### **3. ADMINISTRADOR**

#### **Estadísticas Principales:**
```php
✅ Eventos Creados        // Eventos creados por este admin
✅ Eventos Activos        // Eventos con estado 'abierto'
✅ Total de Usuarios      // Usuarios registrados en el sistema
✅ Total de Equipos       // Equipos formados
✅ Total de Proyectos     // Proyectos presentados
```

#### **Código del Controlador:**
```php
if ($user->isAdmin()) {
    // Total de eventos creados por este admin
    $eventosCreados = \App\Models\Evento::where('created_by', $user->id)->count();
    
    // Total de usuarios en el sistema
    $totalUsuarios = \App\Models\User::count();
    
    // Total de eventos activos
    $eventosActivos = \App\Models\Evento::where('estado', 'abierto')->count();
    
    // Total de equipos en el sistema
    $totalEquipos = \App\Models\Equipo::count();
    
    // Total de proyectos presentados
    $totalProyectos = \App\Models\Proyecto::count();
    
    $adminStats = [
        'eventos_creados' => $eventosCreados,
        'total_usuarios' => $totalUsuarios,
        'eventos_activos' => $eventosActivos,
        'total_equipos' => $totalEquipos,
        'total_proyectos' => $totalProyectos,
    ];
}
```

#### **Vista - Panel de Administrador:**
```html
<div class="grid grid-cols-2 gap-4">
    <!-- Eventos Creados -->
    <div class="text-center p-4 bg-indigo-50 rounded-lg">
        <div class="text-3xl font-bold text-indigo-600">
            {{ $adminStats['eventos_creados'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Eventos Creados</div>
    </div>

    <!-- Eventos Activos -->
    <div class="text-center p-4 bg-green-50 rounded-lg">
        <div class="text-3xl font-bold text-green-600">
            {{ $adminStats['eventos_activos'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Eventos Activos</div>
    </div>

    <!-- Usuarios -->
    <div class="text-center p-4 bg-blue-50 rounded-lg">
        <div class="text-3xl font-bold text-blue-600">
            {{ $adminStats['total_usuarios'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Usuarios</div>
    </div>

    <!-- Equipos -->
    <div class="text-center p-4 bg-purple-50 rounded-lg">
        <div class="text-3xl font-bold text-purple-600">
            {{ $adminStats['total_equipos'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Equipos</div>
    </div>

    <!-- Proyectos -->
    <div class="text-center p-4 bg-pink-50 rounded-lg col-span-2">
        <div class="text-3xl font-bold text-pink-600">
            {{ $adminStats['total_proyectos'] }}
        </div>
        <div class="text-sm text-gray-600 mt-1">Proyectos Presentados</div>
    </div>
</div>

<!-- Botón al Dashboard -->
<div class="mt-4">
    <a href="{{ route('admin.dashboard') }}" 
       class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        Ir al Panel de Administrador
    </a>
</div>
```

---

## 📂 ARCHIVOS MODIFICADOS

```
app/Http/Controllers/ProfileController.php
├─ show(): Método completamente reescrito
├─ Estadísticas para Participante (128 líneas)
├─ Estadísticas para Juez
└─ Estadísticas para Administrador

resources/views/profile/show.blade.php
├─ Sección de estadísticas Participante
├─ Sección de logros y premios Participante
├─ Sección de estadísticas Juez
├─ Sección de evaluaciones por evento
└─ Sección de estadísticas Admin
```

---

## 🎨 DISEÑO VISUAL

### **Participante:**
```
┌─────────────────────────────────────────┐
│ 📊 Estadísticas                         │
├─────────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐              │
│  │   15    │  │    8    │              │
│  │ Eventos │  │ Equipos │              │
│  └─────────┘  └─────────┘              │
│  ┌─────────┐  ┌─────────┐              │
│  │    5    │  │    3    │              │
│  │  Líder  │  │Constanc.│              │
│  └─────────┘  └─────────┘              │
├─────────────────────────────────────────┤
│ Proyectos Presentados:            12   │
│ Total de Premios:          ⭐ 8        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ 🏆 Logros y Premios                     │
├─────────────────────────────────────────┤
│ 🥇 3x Primer Lugar                      │
│    Ganaste el primer lugar en 3 eventos │
│                                         │
│ 🥈 3x Segundo Lugar                     │
│    Obtuviste el segundo lugar en 3...  │
│                                         │
│ 🥉 2x Tercer Lugar                      │
│    Lograste el tercer lugar en 2...    │
│                                         │
│ 👥 Líder de Equipo                      │
│    Has liderado 5 equipo(s)            │
└─────────────────────────────────────────┘
```

### **Juez:**
```
┌─────────────────────────────────────────┐
│ 📋 Estadísticas como Juez               │
├─────────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐              │
│  │    8    │  │   42    │              │
│  │ Eventos │  │Proyectos│              │
│  └─────────┘  └─────────┘              │
│  ┌───────────────────────┐              │
│  │          86           │              │
│  │ Total de Evaluaciones │              │
│  └───────────────────────┘              │
├─────────────────────────────────────────┤
│ Promedio de Calificaciones:  ⭐ 8.5/10 │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Evaluaciones por Evento                 │
├─────────────────────────────────────────┤
│ Hackathon 2025              [  12  ]   │
│ Datathon Innovación         [   8  ]   │
│ Concurso de Apps            [   6  ]   │
└─────────────────────────────────────────┘
```

### **Admin:**
```
┌─────────────────────────────────────────┐
│ 🎯 Panel de Administrador               │
├─────────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐              │
│  │   24    │  │    5    │              │
│  │ Eventos │  │ Activos │              │
│  │ Creados │  │         │              │
│  └─────────┘  └─────────┘              │
│  ┌─────────┐  ┌─────────┐              │
│  │   156   │  │   78    │              │
│  │Usuarios │  │ Equipos │              │
│  └─────────┘  └─────────┘              │
│  ┌───────────────────────┐              │
│  │          45           │              │
│  │  Proyectos Presentados│              │
│  └───────────────────────┘              │
├─────────────────────────────────────────┤
│ [  Ir al Panel de Administrador  ]     │
└─────────────────────────────────────────┘
```

---

## 🚀 PARA PROBAR

```bash
# 1. Acceder a tu perfil
http://localhost:8000/profile

# 2. Como Participante verás:
✅ Eventos participados (reales)
✅ Equipos totales (reales)
✅ Veces como líder (calculado)
✅ Constancias obtenidas (reales)
✅ Premios ganados (1°, 2°, 3° lugar)
✅ Logros visuales con badges

# 3. Como Juez verás:
✅ Eventos donde evaluaste
✅ Proyectos evaluados
✅ Total de evaluaciones
✅ Promedio de calificaciones
✅ Desglose por evento

# 4. Como Admin verás:
✅ Eventos que creaste
✅ Eventos activos actuales
✅ Total de usuarios
✅ Total de equipos
✅ Total de proyectos
✅ Botón al panel admin
```

---

## ✅ ESTADO FINAL

```
╔═══════════════════════════════════════════════════════╗
║                                                       ║
║     ESTADÍSTICAS REALES DE PERFIL                    ║
║     ══════════════════════════════                   ║
║                                                       ║
║  ✅ Participante: 8 estadísticas + premios          ║
║  ✅ Juez: 5 estadísticas + desglose                 ║
║  ✅ Admin: 5 estadísticas del sistema               ║
║  ✅ Datos reales de la base de datos                ║
║  ✅ Badges visuales para logros                     ║
║  ✅ Tarjetas coloridas por categoría               ║
║  ✅ Responsive design                               ║
║                                                       ║
║  Estado: ✅ LISTO PARA PRODUCCIÓN                   ║
║                                                       ║
╚═══════════════════════════════════════════════════════╝
```

---

**Estado:** ✅ **COMPLETADO**  
**Fecha:** Diciembre 6, 2025  
**Desarrollado por:** Claude Assistant  

---

**¡Sistema completo de estadísticas por rol implementado! 🎉**
