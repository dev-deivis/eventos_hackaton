# 🎨 NUEVO DISEÑO DE CONSTANCIAS

## ✨ CAMBIOS REALIZADOS

### **Diseño Inspirado en el Modelo Oficial**

Basado en la constancia del TecNM Campus Oaxaca, se implementó un diseño moderno y profesional.

## 📋 CARACTERÍSTICAS DEL NUEVO DISEÑO

### **1. Layout Profesional**
- ✅ Logos institucionales (TecNM + SEP)
- ✅ Gradiente decorativo azul/morado (participación) o dorado (ganadores)
- ✅ Estrella/forma geométrica decorativa
- ✅ Diseño limpio y moderno
- ✅ 4 firmas oficiales en la parte inferior

### **2. Elementos Visuales**

#### **Header:**
- Logo TecNM (izquierda)
- Texto institucional (centro)
- Logo SEP Educación (derecha)
- Línea divisoria inferior

#### **Contenido:**
- Texto "Certificado de participación a"
- Nombre del participante en **AZUL GRANDE** (participación) o **DORADO** (ganadores)
- Línea divisoria gris
- Descripción del evento y proyecto
- Badge de reconocimiento (solo ganadores: 🥇 1er Lugar, 🥈 2do Lugar, 🥉 3er Lugar)

#### **Firmas:**
1. **M.C. Silvia Santiago Cruz** - Directora
2. **Dra. Alma Dolores Pérez Santiago** - Subdirectora Académica
3. **Dra. Marisol Altamirano Cabrera** - Subdirectora de Planeación y Vinculación
4. **Ing. Huitziil Díaz Jaimes** - Jefa del Depto. de Servicios Escolares

### **3. Diferencias entre Tipos**

#### **Constancia de Participación:**
```
- Color principal: Azul (#4f46e5)
- Gradiente: Azul/Morado
- Badge: No tiene
- Texto: "por haber participado en el evento..."
```

#### **Constancia de Ganador:**
```
- Color principal: Dorado (#d97706)
- Gradiente: Dorado/Naranja
- Badge: 🥇 1er Lugar / 🥈 2do Lugar / 🥉 3er Lugar
- Texto: "por haber obtenido el PRIMER/SEGUNDO/TERCER LUGAR..."
- Caja de información del premio (fondo amarillo)
```

## 📁 ARCHIVOS MODIFICADOS

```
✅ resources/views/constancias/pdf/participacion.blade.php
✅ resources/views/constancias/pdf/ganador.blade.php
```

## 🎨 ESPECIFICACIONES DE DISEÑO

### **Tipografía:**
- Fuente: Arial, Helvetica
- Nombre: 52px, bold, uppercase
- Texto institucional: 11px, uppercase
- Cuerpo: 13px, line-height 1.8
- Firmas: 9px (nombre), 8px (cargo)

### **Colores:**

#### Participación:
- Azul principal: `#4f46e5`
- Gradiente: `#667eea → #764ba2 → #6366f1`
- Texto: `#374151`

#### Ganadores:
- Dorado: `#d97706`
- Gradiente: `#fbbf24 → #f59e0b → #d97706`
- Badge: `#fbbf24 → #f59e0b`
- Caja premio: `#fef3c7` (fondo), `#f59e0b` (borde)

### **Dimensiones:**
- Página: 210mm x 297mm (A4)
- Padding: 40px 50px
- Gradiente decorativo: 400px x 400px
- Estrella decorativa: 280px x 280px
- Línea divisoria: 350px x 2px

## 🖼️ LOGOS

### **Logos Actuales (SVG Placeholder):**

Los logos están implementados como SVG temporales:

**Logo TecNM:**
- Círculo azul marino
- Texto "TEC" blanco centrado
- Tamaño: 80x80px

**Logo SEP:**
- Círculo café (escudo)
- Texto "EDUCACIÓN"
- Subtítulo "SECRETARÍA DE EDUCACIÓN PÚBLICA"
- Tamaño: 180px ancho

### **Para Usar Logos Reales:**

1. Coloca los archivos en: `public/images/logos/`
   - `tecnm-logo.png`
   - `sep-logo.png`

2. Actualiza las vistas:

```blade
<!-- Logo TecNM -->
<img src="{{ public_path('images/logos/tecnm-logo.png') }}" class="logo-left" alt="TecNM">

<!-- Logo SEP -->
<img src="{{ public_path('images/logos/sep-logo.png') }}" class="logo-right" alt="SEP">
```

## 🧪 PRUEBAS

### **En Localhost:**

1. Ir a la sección de constancias
2. Generar una constancia de participación
3. Verificar:
   - ✅ Logos visibles
   - ✅ Nombre en azul grande
   - ✅ Gradiente en esquina derecha
   - ✅ 4 firmas correctas
   - ✅ Texto bien formateado

4. Generar constancia de ganador
5. Verificar adicional:
   - ✅ Badge dorado con emoji
   - ✅ Caja de información del premio
   - ✅ Color dorado en nombre

### **Comandos de Prueba:**

```bash
# Iniciar servidor
php artisan serve

# Ir a:
http://localhost:8000/admin/constancias
```

## 🚀 DEPLOY A PRODUCCIÓN

Una vez probado en localhost:

```bash
git add .
git commit -m "feat: Nuevo diseño de constancias estilo TecNM oficial"
git push origin main
```

## 🎯 MEJORAS FUTURAS

### **Corto plazo:**
- [ ] Agregar logos reales (PNG/SVG de alta calidad)
- [ ] Agregar firma escaneada de los directivos
- [ ] Código QR de verificación visible
- [ ] Fecha de emisión en el documento

### **Mediano plazo:**
- [ ] Marca de agua institucional
- [ ] Borde decorativo dorado para ganadores
- [ ] Holograma de seguridad (visual)
- [ ] Diferentes diseños según tipo de evento

### **Largo plazo:**
- [ ] Sistema de plantillas personalizables
- [ ] Editor visual de constancias
- [ ] Múltiples diseños para elegir
- [ ] Internacionalización (inglés)

## 📸 PREVIEW DEL DISEÑO

### **Constancia de Participación:**
```
┌─────────────────────────────────────────┐
│ [TecNM] Tecnológico Nacional... [SEP]  │
│ ───────────────────────────────────────  │
│                                         │
│ Certificado de participación a          │
│                                         │
│         KARLA ROCÍO                     │ (Azul grande)
│     DELGADO MOLINA                      │
│ ───────────                             │
│                                         │
│ por haber participado en el evento...   │
│                                         │
│ [Firma1]  [Firma2]  [Firma3]  [Firma4] │
└─────────────────────────────────────────┘
```

### **Constancia de Ganador:**
```
┌─────────────────────────────────────────┐
│ [TecNM] Tecnológico Nacional... [SEP]  │
│ ───────────────────────────────────────  │
│                                         │
│        [ 🥇 1ER LUGAR ]                 │ (Badge dorado)
│ Certificado de reconocimiento a         │
│                                         │
│         KARLA ROCÍO                     │ (Dorado grande)
│     DELGADO MOLINA                      │
│ ───────────                             │
│                                         │
│ por haber obtenido el PRIMER LUGAR...   │
│                                         │
│ ┌─────────────────────────────────┐    │
│ │ Equipo: Innovadores Tech        │    │ (Caja amarilla)
│ │ Proyecto: Sistema Gestión...    │    │
│ └─────────────────────────────────┘    │
│                                         │
│ [Firma1]  [Firma2]  [Firma3]  [Firma4] │
└─────────────────────────────────────────┘
```

## ✅ CHECKLIST

- [x] Diseño de participación actualizado
- [x] Diseño de ganador actualizado
- [x] 4 firmas oficiales agregadas
- [x] Gradientes decorativos implementados
- [x] Colores según tipo de constancia
- [x] Badge para ganadores
- [x] Caja de información del premio
- [ ] Logos reales (pendiente - usando SVG temporales)
- [ ] Pruebas en localhost
- [ ] Deploy a producción

---

**Diseño creado:** 7 de Diciembre, 2025
**Inspirado en:** Constancia oficial TecNM Campus Oaxaca
**Estado:** ✅ Listo para pruebas
**Próximo paso:** Probar en localhost y agregar logos reales
