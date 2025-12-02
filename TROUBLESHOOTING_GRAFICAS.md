# 🔧 TROUBLESHOOTING: GRÁFICAS NO SE MUESTRAN

## 🐛 PROBLEMA IDENTIFICADO

Las gráficas de Chart.js no aparecen en la vista de reportes.

---

## ✅ CORRECCIONES APLICADAS

### **1. Contenedor con clase `relative`**

**ANTES:**
```html
<div style="height: 300px;">
    <canvas id="chart-carreras"></canvas>
</div>
```

**DESPUÉS:**
```html
<div class="relative" style="height: 300px;">
    <canvas id="chart-carreras"></canvas>
</div>
```

**Razón:** Chart.js necesita que el contenedor tenga posición definida para calcular dimensiones.

---

### **2. Validaciones en JavaScript**

Se agregaron múltiples validaciones para debug:

```javascript
function actualizarGraficaCarreras(datos) {
    // 1. Verificar que el canvas existe
    if (!ctx) {
        console.error('Canvas chart-carreras no encontrado');
        return;
    }
    
    // 2. Verificar que Chart.js está cargado
    if (!window.Chart) {
        console.error('Chart.js no está cargado');
        return;
    }
    
    // 3. Verificar que hay datos
    if (!datos || datos.length === 0) {
        console.warn('No hay datos para gráfica');
        return;
    }
    
    // 4. Logs de debug
    console.log('Creando gráfica con datos:', datos);
    
    // 5. Try-catch para capturar errores
    try {
        // ... crear gráfica
        console.log('Gráfica creada exitosamente');
    } catch (error) {
        console.error('Error al crear gráfica:', error);
    }
}
```

---

### **3. Logs detallados en cargarDatosReportes()**

```javascript
async function cargarDatosReportes(eventoId = '') {
    try {
        const url = ...;
        console.log('Cargando datos desde:', url);
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Datos recibidos:', data);
        
        // Validar datos antes de actualizar
        if (data.carreras && data.carreras.length > 0) {
            console.log('Actualizando gráfica de carreras:', data.carreras);
            actualizarGraficaCarreras(data.carreras);
        } else {
            console.warn('No hay datos de carreras');
        }
        
    } catch (error) {
        console.error('Error al cargar datos:', error);
        alert('Error al cargar datos: ' + error.message);
    }
}
```

---

## 🔍 CÓMO DIAGNOSTICAR EL PROBLEMA

### **Paso 1: Abrir Consola del Navegador**

```
Chrome/Edge: F12 o Ctrl+Shift+I
Firefox: F12 o Ctrl+Shift+K
Safari: Cmd+Option+I
```

### **Paso 2: Recargar la página**

```
Ctrl+R o F5
```

### **Paso 3: Ver mensajes en consola**

Deberías ver:
```
✅ Cargando datos desde: /admin/reportes/datos
✅ Datos recibidos: { kpis: {...}, carreras: [...], roles: [...] }
✅ Actualizando gráfica de carreras: [...]
✅ Creando gráfica de carreras con datos: [...]
✅ Gráfica de carreras creada exitosamente
✅ Actualizando gráfica de roles: [...]
✅ Creando gráfica de roles con datos: [...]
✅ Gráfica de roles creada exitosamente
```

---

## 🚨 POSIBLES ERRORES Y SOLUCIONES

### **Error 1: Chart.js no está cargado**

**Mensaje en consola:**
```
Chart.js no está cargado
```

**Causa:** El CDN de Chart.js no cargó

**Solución:**
```html
<!-- Verificar que esta línea existe antes del </body> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

**Alternativa:** Usar versión local
```bash
npm install chart.js
```

---

### **Error 2: No hay datos para gráfica**

**Mensaje en consola:**
```
No hay datos de carreras
No hay datos de roles
```

**Causa:** La base de datos no tiene datos

**Solución:**
1. Verificar que hay participantes registrados
2. Verificar que tienen carreras asignadas
3. Verificar que están en equipos con roles

**Query de verificación:**
```sql
-- Verificar participantes por carrera
SELECT c.nombre, COUNT(*) as total
FROM participantes p
JOIN carreras c ON p.carrera_id = c.id
GROUP BY c.nombre;

-- Verificar roles
SELECT rol_equipo, COUNT(*) as total
FROM equipo_participante
WHERE estado = 'activo' AND rol_equipo IS NOT NULL
GROUP BY rol_equipo;
```

---

### **Error 3: Canvas no encontrado**

**Mensaje en consola:**
```
Canvas chart-carreras no encontrado
```

**Causa:** Los IDs de los canvas no coinciden

**Solución:** Verificar HTML
```html
<!-- Debe tener ID exacto -->
<canvas id="chart-carreras"></canvas>
<canvas id="chart-roles"></canvas>
```

---

### **Error 4: HTTP 404 o 500**

**Mensaje en consola:**
```
HTTP error! status: 404
HTTP error! status: 500
```

**Causa:** La ruta API no existe o falla

**Solución:**
1. Verificar ruta en `web.php`:
```php
Route::get('/reportes/datos', [AdminController::class, 'datosReportes'])
    ->name('reportes.datos');
```

2. Verificar método existe en controlador:
```php
public function datosReportes(Request $request) {
    // ...
}
```

3. Limpiar caché:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

---

### **Error 5: CORS o Mixed Content**

**Mensaje en consola:**
```
Blocked by CORS policy
Mixed Content: The page was loaded over HTTPS...
```

**Causa:** Diferencia HTTP/HTTPS

**Solución:** Usar ruta relativa
```javascript
// ✅ CORRECTO
const url = '/admin/reportes/datos';

// ❌ INCORRECTO
const url = 'http://127.0.0.1:8000/admin/reportes/datos';
```

---

## 🧪 PRUEBA MANUAL DE LA API

### **Opción 1: Navegador**

```
http://127.0.0.1:8000/admin/reportes/datos
```

Debería devolver JSON:
```json
{
  "kpis": {...},
  "carreras": [...],
  "roles": [...]
}
```

### **Opción 2: cURL**

```bash
curl http://127.0.0.1:8000/admin/reportes/datos
```

### **Opción 3: Postman**

```
GET http://127.0.0.1:8000/admin/reportes/datos
```

---

## 📊 VERIFICAR DATOS DE PRUEBA

Si no hay datos, crear algunos:

### **1. Crear Carreras:**
```sql
INSERT INTO carreras (nombre, created_at, updated_at) VALUES
('Ingeniería en Sistemas Computacionales', NOW(), NOW()),
('Ingeniería Industrial', NOW(), NOW());
```

### **2. Crear Participantes:**
```sql
-- Asociar participantes existentes con carreras
UPDATE participantes SET carrera_id = 1 WHERE id <= 10;
UPDATE participantes SET carrera_id = 2 WHERE id > 10;
```

### **3. Asignar Roles:**
```sql
-- Asignar roles a miembros de equipos
UPDATE equipo_participante 
SET rol_equipo = 'Programador'
WHERE id % 3 = 0;

UPDATE equipo_participante 
SET rol_equipo = 'Diseñador'
WHERE id % 3 = 1;

UPDATE equipo_participante 
SET rol_equipo = 'Analista de Negocios'
WHERE id % 3 = 2;
```

---

## ✅ CHECKLIST DE VERIFICACIÓN

### **Frontend:**
- [ ] Chart.js CDN cargado correctamente
- [ ] Canvas con IDs correctos (`chart-carreras`, `chart-roles`)
- [ ] Contenedores con altura definida (300px)
- [ ] Clase `relative` en contenedores
- [ ] JavaScript sin errores de sintaxis

### **Backend:**
- [ ] Ruta `/admin/reportes/datos` existe
- [ ] Método `datosReportes()` implementado
- [ ] Query devuelve datos válidos
- [ ] Response JSON bien formado

### **Datos:**
- [ ] Hay participantes en BD
- [ ] Participantes tienen carreras
- [ ] Participantes están en equipos
- [ ] Miembros de equipos tienen roles asignados

### **Consola del Navegador:**
- [ ] Sin errores rojos
- [ ] Logs muestran "Datos recibidos"
- [ ] Logs muestran "Gráfica creada exitosamente"

---

## 🎯 SOLUCIÓN RÁPIDA

Si nada funciona, prueba esto:

### **1. Verificar Chart.js:**
```html
<!-- Agregar antes del script de las gráficas -->
<script>
    console.log('Chart.js cargado:', !!window.Chart);
</script>
```

### **2. Datos de prueba hardcodeados:**
```javascript
// Temporal para probar
const datosTest = [
    { nombre: 'ISC', total: 45 },
    { nombre: 'Industrial', total: 20 }
];
actualizarGraficaCarreras(datosTest);
```

### **3. Gráfica básica:**
```javascript
// Test minimalista
const ctx = document.getElementById('chart-carreras');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Test'],
        datasets: [{
            data: [10],
            backgroundColor: 'blue'
        }]
    }
});
```

---

## 📝 ARCHIVOS MODIFICADOS

1. ✅ `resources/views/admin/reportes/index.blade.php`
   - Agregada clase `relative` a contenedores
   - Logs de debug en JavaScript
   - Validaciones en todas las funciones

---

## 🚀 PRÓXIMO PASO

1. **Abre la consola del navegador** (F12)
2. **Recarga la página** (Ctrl+R)
3. **Lee los mensajes** que aparecen
4. **Comparte los errores** si los hay

**Si ves los logs "Gráfica creada exitosamente" pero no se muestran:**
- Problema de CSS o dimensiones
- Inspeccionar elemento del canvas

**Si ves "No hay datos de carreras/roles":**
- Problema de base de datos
- Ejecutar queries de verificación

**Si ves "Chart.js no está cargado":**
- Problema de CDN
- Verificar conexión a internet
- Usar versión local de Chart.js

---

**¿Qué mensajes ves en la consola?** 🔍
