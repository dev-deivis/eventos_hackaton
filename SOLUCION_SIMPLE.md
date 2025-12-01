# 🚀 SOLUCIÓN SIMPLE - 2 PASOS

## ✅ PASO 1: Instalar DomPDF

**Ejecuta:**
```
Doble clic en: instalar_dompdf.bat
```

O desde terminal:
```bash
composer require barryvdh/laravel-dompdf
```

---

## ✅ PASO 2: Crear Datos de Prueba

**Ejecuta:**
```
Doble clic en: crear_datos_prueba.bat
```

O desde terminal:
```bash
php artisan db:seed --class=ConstanciasTestSeeder
```

---

## 🎯 VERÁS ESTE MENSAJE:

```
✅ ¡Datos de prueba creados exitosamente!

═══════════════════════════════════════
📋 RESUMEN:
═══════════════════════════════════════
   - Evento: Hackathon 2025 (COMPLETADO)
   - Participantes: 5
   - Equipos: 2
   - Proyectos: 2

🎯 EMAILS DE PRUEBA:
   - Karla Delgado Molina
     Email: participante1@tecnm.mx
   - Jesús Martínez Martínez
     Email: participante2@tecnm.mx
   ...

🔑 Contraseña para todos: password123
```

---

## 📝 PASO 3: Generar Tu Primera Constancia

1. **Abre tu navegador**
2. **Ve a:** Dashboard Admin
3. **Clic en:** Botón "Constancias" (rosa)
4. **Clic en:** Pestaña "Generar Nuevas"
5. **Llena el formulario:**
   ```
   Nombre: Karla Delgado Molina
   Email: participante1@tecnm.mx
   Evento: Hackathon 2025  ← Ahora sí aparecerá
   Tipo: Participación
   ```
6. **Clic en:** "Generar Constancia" (botón rosa)
7. **Verás:** La constancia creada
8. **Clic en:** "Descargar" para obtener el PDF

---

## 🎨 O Generar TODAS de Golpe:

1. **Pestaña "Generar Nuevas"**
2. **Scroll abajo** hasta "Generar en Lote"
3. **Selecciona:**
   - Evento: Hackathon 2025
   - Tipo: Participación
4. **Clic en:** "Generar Constancias en Lote"
5. **¡Boom!** 5 constancias creadas instantáneamente

---

## 🐛 SI HAY ERROR:

### "No se encontró un participante con ese email"
- ✅ Usa: `participante1@tecnm.mx` (no tu email)
- ✅ Re-ejecuta: `crear_datos_prueba.bat`

### "No aparece el evento en el dropdown"
- ✅ Verifica que el seeder se ejecutó correctamente
- ✅ Revisa que diga: "Evento: Hackathon 2025 (COMPLETADO)"

### "Call to undefined method PDF::loadView"
- ✅ Ejecuta: `instalar_dompdf.bat`
- ✅ Luego: `php artisan config:clear`

---

## 📂 ARCHIVOS PARA EJECUTAR:

```
📁 eventos_hackaton/
   ├── instalar_dompdf.bat        ← Ejecuta primero
   └── crear_datos_prueba.bat     ← Ejecuta segundo
```

---

**¡Ejecuta los 2 archivos y en 1 minuto estará listo!** 🚀
