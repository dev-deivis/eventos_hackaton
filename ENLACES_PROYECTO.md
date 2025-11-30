# 🔗 ENLACES DEL PROYECTO - CONTROL DE ACCESO

## ✅ IMPLEMENTACIÓN COMPLETADA

### 🎯 FUNCIONALIDAD:
Mostrar enlaces del proyecto (Repositorio, Demo, Presentación) solo a:
- ✅ Miembros del equipo
- ✅ Jueces
- ✅ Administradores

---

## 📝 CAMBIO REALIZADO

### Archivo: `resources/views/equipos/show.blade.php`

**Ubicación:** Después de la descripción del equipo, dentro del header

**Código agregado:**
```blade
<!-- Enlaces del Proyecto (Solo para miembros, jueces y admin) -->
@if($equipo->proyecto && ($esMiembro || auth()->user()->hasRole('juez') || auth()->user()->hasRole('admin')))
    <div class="mt-4 flex flex-wrap gap-3">
        @if($equipo->proyecto->repositorio_url)
            <a href="{{ $equipo->proyecto->repositorio_url }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-medium transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387..."/>
                </svg>
                Ver Repositorio
            </a>
        @endif

        @if($equipo->proyecto->demo_url)
            <a href="{{ $equipo->proyecto->demo_url }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                </svg>
                Ver Demo
            </a>
        @endif

        @if($equipo->proyecto->presentacion_url)
            <a href="{{ $equipo->proyecto->presentacion_url }}" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm11-1a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1V8a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                </svg>
                Ver Presentación
            </a>
        @endif
    </div>
@endif
```

---

## 🔒 VALIDACIONES DE SEGURIDAD

### CONDICIONES PARA MOSTRAR:

```php
$equipo->proyecto  // Debe existir un proyecto registrado
&&
(
    $esMiembro     // Usuario es miembro activo del equipo
    ||
    auth()->user()->hasRole('juez')  // Usuario es juez
    ||
    auth()->user()->hasRole('admin') // Usuario es admin
)
```

### TABLA DE PERMISOS:

| Rol | ¿Ve enlaces? | ¿Por qué? |
|-----|--------------|-----------|
| Miembro activo | ✅ | Es parte del equipo |
| Miembro pendiente | ❌ | No está activo aún |
| Líder | ✅ | Es miembro del equipo |
| Juez | ✅ | Debe evaluar el proyecto |
| Admin | ✅ | Supervisión general |
| Usuario regular | ❌ | No autorizado |
| Visitante no auth | ❌ | No autenticado |

---

## 🎨 DISEÑO DE LOS BOTONES

### 1. **REPOSITORIO** (GitHub)
- **Color:** Negro (`bg-gray-900`)
- **Hover:** Gris oscuro (`bg-gray-800`)
- **Ícono:** Logo completo de GitHub
- **Target:** Nueva pestaña (`_blank`)

### 2. **DEMO**
- **Color:** Azul (`bg-blue-600`)
- **Hover:** Azul oscuro (`bg-blue-700`)
- **Ícono:** Play (círculo con triángulo)
- **Target:** Nueva pestaña (`_blank`)

### 3. **PRESENTACIÓN**
- **Color:** Morado (`bg-purple-600`)
- **Hover:** Morado oscuro (`bg-purple-700`)
- **Ícono:** Diapositivas/Slides
- **Target:** Nueva pestaña (`_blank`)

### Características comunes:
- ✅ Responsive (flex-wrap)
- ✅ Gap de 3 (12px entre botones)
- ✅ Padding consistente (px-4 py-2)
- ✅ Bordes redondeados (rounded-lg)
- ✅ Transición suave al hover
- ✅ Texto pequeño (text-sm)
- ✅ Font medium
- ✅ Íconos de 20px (w-5 h-5)

---

## 📍 UBICACIÓN EN LA INTERFAZ

```
┌──────────────────────────────────────────────────────────┐
│                    HEADER DEL EQUIPO                      │
├──────────────────────────────────────────────────────────┤
│ Hackathon Warriors                          [Abandonar]  │
│ Hackathon de IA 2024                                     │
│ Líder: Juan Pérez • 4/5 miembros                        │
│                                                           │
│ Equipo enfocado en desarrollar soluciones de IA para    │
│ mejorar la educación...                                  │
│                                                           │
│ [🔗 Ver Repositorio]  [▶ Ver Demo]  [📊 Ver Presentación] │ ← AQUÍ
│                                                           │
└──────────────────────────────────────────────────────────┘
```

---

## 🧪 CASOS DE PRUEBA

### TEST 1: Miembro del equipo con proyecto completo
```
Setup: 
- Usuario es miembro activo
- Proyecto tiene los 3 enlaces

Resultado esperado:
✅ Ve 3 botones: Repositorio, Demo, Presentación
✅ Click en "Ver Repositorio" abre GitHub
✅ Click en "Ver Demo" abre sitio de demo
✅ Click en "Ver Presentación" abre Google Slides/PowerPoint
```

### TEST 2: Miembro con proyecto parcial
```
Setup:
- Usuario es miembro activo
- Proyecto solo tiene repositorio_url

Resultado esperado:
✅ Ve 1 botón: Repositorio
❌ NO ve botones de Demo ni Presentación
```

### TEST 3: Usuario NO miembro
```
Setup:
- Usuario autenticado pero NO es miembro del equipo
- Proyecto tiene todos los enlaces

Resultado esperado:
❌ NO ve ningún botón de enlaces
✅ Ve resto de información pública (nombre, descripción)
```

### TEST 4: Solicitud pendiente
```
Setup:
- Usuario solicitó unirse (estado: pendiente)
- Proyecto tiene enlaces

Resultado esperado:
❌ NO ve botones (solo miembros ACTIVOS)
✅ Espera a ser aceptado
```

### TEST 5: Juez evaluando
```
Setup:
- Usuario con rol 'juez'
- NO es miembro del equipo
- Proyecto con todos los enlaces

Resultado esperado:
✅ Ve los 3 botones
✅ Puede acceder a repositorio y demo para evaluar
```

### TEST 6: Admin supervisando
```
Setup:
- Usuario con rol 'admin'
- NO es miembro del equipo

Resultado esperado:
✅ Ve todos los botones disponibles
✅ Acceso completo a los recursos
```

### TEST 7: Equipo sin proyecto
```
Setup:
- Usuario es miembro
- Equipo NO ha registrado proyecto

Resultado esperado:
❌ NO muestra sección de enlaces
✅ Muestra mensaje: "Aún no han registrado su proyecto"
```

---

## 🔗 VALIDACIÓN DE URLs

### En el modelo Proyecto:
Los enlaces se validan al registrar/editar:

```php
'repositorio_url' => 'nullable|url|max:255',
'demo_url' => 'nullable|url|max:255',
'presentacion_url' => 'nullable|url|max:255',
```

### Ejemplos de URLs válidas:
```
Repositorio:
- https://github.com/usuario/proyecto
- https://gitlab.com/usuario/proyecto

Demo:
- https://proyecto.vercel.app
- https://proyecto.netlify.app
- https://proyecto.herokuapp.com

Presentación:
- https://docs.google.com/presentation/d/...
- https://www.canva.com/design/...
- https://prezi.com/view/...
```

---

## 📊 MATRIZ DE VISIBILIDAD

| Condición | Repositorio | Demo | Presentación |
|-----------|-------------|------|--------------|
| Sin proyecto | ❌ | ❌ | ❌ |
| Proyecto + NO autorizado | ❌ | ❌ | ❌ |
| Proyecto + Miembro + URL nula | ❌ | ✅ | ✅ |
| Proyecto + Miembro + URL llena | ✅ | ✅ | ✅ |
| Proyecto + Juez + URLs llenas | ✅ | ✅ | ✅ |
| Proyecto + Admin + URLs llenas | ✅ | ✅ | ✅ |

---

## 🎯 BENEFICIOS DE ESTA IMPLEMENTACIÓN

### 1. **SEGURIDAD**
- Solo personas autorizadas ven los enlaces
- Previene acceso no deseado durante desarrollo
- Protege propiedad intelectual del equipo

### 2. **USABILIDAD**
- Acceso rápido a recursos del proyecto
- No necesitan buscar enlaces en chat
- Un click y se abre en nueva pestaña

### 3. **EVALUACIÓN**
- Jueces acceden fácilmente a proyectos
- Todos los recursos en un solo lugar
- Proceso de calificación más eficiente

### 4. **ADMINISTRACIÓN**
- Admins pueden revisar proyectos
- Monitoreo de avances
- Supervisión efectiva

### 5. **FLEXIBILIDAD**
- No todos los enlaces son obligatorios
- Solo muestra los que existen
- Adaptable a diferentes tipos de proyectos

---

## 🚀 FLUJO DE USO

### COMO MIEMBRO:
```
1. Entrar a "Mis Equipos"
2. Click "Ver Equipo"
3. En header del equipo, ver botones de enlaces
4. Click "Ver Repositorio"
5. → Se abre GitHub en nueva pestaña
6. Revisar código, hacer commits, etc.
```

### COMO JUEZ:
```
1. Ir a evento
2. Ver lista de equipos
3. Click en equipo a evaluar
4. Ver enlaces en header
5. Click "Ver Demo"
6. → Probar la aplicación
7. Click "Ver Repositorio"
8. → Revisar código fuente
9. Calificar basado en criterios
```

### COMO ADMIN:
```
1. Dashboard admin
2. Ver todos los equipos
3. Supervisar avances
4. Acceder a cualquier proyecto
5. Verificar que tengan enlaces registrados
```

---

## ✅ RESULTADO FINAL

Ahora la vista del equipo muestra:

1. ✅ Información del equipo (siempre visible)
2. ✅ Enlaces del proyecto (solo autorizados):
   - 🔗 Repositorio (negro)
   - ▶️ Demo (azul)
   - 📊 Presentación (morado)
3. ✅ Control de acceso robusto
4. ✅ Diseño profesional
5. ✅ Responsive
6. ✅ Abre en nueva pestaña
7. ✅ Validación de permisos

**¡Enlaces del proyecto implementados con seguridad!** 🔒🔗
