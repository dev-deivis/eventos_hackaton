# 📋 MODO OSCURO - VISTAS PENDIENTES

## ✅ VISTAS YA COMPLETADAS

```
✅ Dashboard Admin (100%)
✅ Dashboard Participante (100%)
✅ Dashboard Juez (100%)
✅ Gestión de Eventos - Lista (100%)
✅ Navegación Principal (100%)
✅ Layouts (app y app-layout) (100%)
```

---

## ⚠️ VISTAS PARCIALMENTE COMPLETADAS

### **1. Crear Evento** (30%)
```
Archivo: resources/views/eventos/create.blade.php

✅ Header (título y descripción)
✅ Badge de usuario
✅ Card contenedor
❌ Labels de formulario
❌ Inputs de texto
❌ Selects
❌ Textareas
❌ Mensajes de error
❌ Ayudas (texto pequeño)
```

---

## ❌ VISTAS SIN MODO OSCURO

### **2. Ver Evento (Show)**
```
Archivo: resources/views/eventos/show.blade.php

❌ Header del evento
❌ Cards de información
❌ Descripción
❌ Detalles (fecha, ubicación, etc.)
❌ Equipos registrados
❌ Botones de acción
```

### **3. Usuarios (Admin)**
```
Archivo: resources/views/admin/usuarios/index.blade.php

❌ Header
❌ Formulario de búsqueda
❌ Tabla de usuarios
❌ Badges de rol
❌ Botones de acción
```

### **4. Rankings**
```
Archivo: resources/views/admin/rankings.blade.php

❌ Header
❌ Filtros
❌ Tabla de rankings
❌ Estadísticas
❌ Posiciones
```

### **5. Proyectos Pendientes**
```
Archivo: resources/views/admin/proyectos/pendientes.blade.php

❌ Header
❌ Lista de proyectos
❌ Cards de proyecto
❌ Badges de estado
❌ Botones de acción
```

### **6. Constancias**
```
Archivo: resources/views/admin/constancias/index.blade.php

❌ Header
❌ Formulario de filtros
❌ Lista de constancias
❌ Botones de descarga
```

---

## 🎯 PATRÓN PARA APLICAR

Para completar cada vista, aplicar estas clases:

### **Títulos y Headers:**
```html
<!-- Principal -->
<h1 class="text-gray-900 dark:text-white">

<!-- Secundario -->
<h2 class="text-gray-800 dark:text-gray-100">
<h3 class="text-gray-700 dark:text-gray-300">
```

### **Textos:**
```html
<!-- Normal -->
<p class="text-gray-600 dark:text-gray-400">

<!-- Secundario -->
<span class="text-gray-500 dark:text-gray-400">
```

### **Cards y Contenedores:**
```html
<div class="bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700">
<div class="bg-gray-50 dark:bg-gray-700">
<div class="bg-gray-100 dark:bg-gray-700">
```

### **Formularios:**
```html
<!-- Labels -->
<label class="text-gray-700 dark:text-gray-300">

<!-- Inputs -->
<input class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 placeholder-gray-400 dark:placeholder-gray-500">

<!-- Selects -->
<select class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">

<!-- Textareas -->
<textarea class="bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600">
```

### **Tablas:**
```html
<!-- Header -->
<thead class="bg-gray-50 dark:bg-gray-700">
<th class="text-gray-700 dark:text-gray-300">

<!-- Body -->
<tbody class="bg-white dark:bg-gray-800">
<td class="text-gray-900 dark:text-white">

<!-- Borders -->
border-gray-200 dark:border-gray-700
```

### **Badges:**
```html
<!-- Info -->
<span class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">

<!-- Success -->
<span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">

<!-- Warning -->
<span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300">

<!-- Error -->
<span class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
```

### **Botones Secundarios:**
```html
<button class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600">
```

---

## 🚀 PROCESO RECOMENDADO

Para cada vista:

1. **Abrir el archivo**
2. **Buscar estos elementos:**
   - Headers (h1, h2, h3)
   - Cards (div con bg-white)
   - Textos (p, span con text-gray-*)
   - Labels de formulario
   - Inputs, selects, textareas
   - Tablas
   - Badges
   - Botones

3. **Aplicar clases dark según el patrón**

4. **Probar en localhost:**
   ```bash
   php artisan serve
   # Abrir navegación
   # Click en botón de luna
   # Verificar que todo se vea
   ```

5. **Commit:**
   ```bash
   git add .
   git commit -m "feat: Completar modo oscuro en [nombre-vista]"
   git push origin main
   ```

---

## 💡 SCRIPT AUTOMATIZADO (OPCIONAL)

Si quieres hacerlo más rápido, puedes usar este script de PowerShell:

```powershell
function Agregar-ClasesDark {
    param($archivo)
    
    $contenido = Get-Content $archivo -Raw -Encoding UTF8
    
    # Aplicar reemplazos
    $contenido = $contenido -replace 'class="([^"]*)text-gray-900([^"]*)"', 'class="$1text-gray-900 dark:text-white$2"'
    $contenido = $contenido -replace 'class="([^"]*)text-gray-700([^"]*)"', 'class="$1text-gray-700 dark:text-gray-300$2"'
    $contenido = $contenido -replace 'class="([^"]*)text-gray-600([^"]*)"', 'class="$1text-gray-600 dark:text-gray-400$2"'
    $contenido = $contenido -replace 'class="([^"]*)bg-white([^"]*)"', 'class="$1bg-white dark:bg-gray-800$2"'
    $contenido = $contenido -replace 'class="([^"]*)border-gray-100([^"]*)"', 'class="$1border-gray-100 dark:border-gray-700$2"'
    
    Set-Content $archivo -Value $contenido -Encoding UTF8 -NoNewline
}

# Usar:
Agregar-ClasesDark "resources/views/eventos/show.blade.php"
```

**⚠️ ADVERTENCIA:** Revisar manualmente después del script para corregir duplicados.

---

## 📊 PROGRESO ESTIMADO

```
Dashboard y Navegación:   ████████████████████ 100%
Gestión de Eventos:       ████████████████████ 100%
Crear Evento:             ██████░░░░░░░░░░░░░░  30%
Ver Evento:               ░░░░░░░░░░░░░░░░░░░░   0%
Usuarios:                 ░░░░░░░░░░░░░░░░░░░░   0%
Rankings:                 ░░░░░░░░░░░░░░░░░░░░   0%
Proyectos Pendientes:     ░░░░░░░░░░░░░░░░░░░░   0%
Constancias:              ░░░░░░░░░░░░░░░░░░░░   0%

TOTAL:                    █████░░░░░░░░░░░░░░░  25%
```

---

## 🎯 PRIORIDAD RECOMENDADA

1. **ALTA:** Ver Evento (show) - Muy usado
2. **MEDIA:** Rankings - Usado por admin y jueces
3. **MEDIA:** Proyectos Pendientes - Usado por admin
4. **BAJA:** Usuarios - Solo admin
5. **BAJA:** Constancias - Esporádico

---

## ✨ NOTA FINAL

Las vistas más importantes (dashboards y navegación) YA están completas.

Las demás vistas **funcionan correctamente** en modo oscuro, solo que algunos textos pueden verse oscuros en fondo oscuro.

**No es crítico** completarlas todas ahora, pero mejora la experiencia de usuario.

---

**Estado:** ⏳ EN PROGRESO (25% completo)
**Críticas:** ✅ COMPLETADAS
**Opcionales:** ⚠️ PENDIENTES
