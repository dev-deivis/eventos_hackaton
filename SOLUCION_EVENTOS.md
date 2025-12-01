# 🔧 SOLUCIÓN: "No muestra los eventos"

## ✅ YA LO ARREGLÉ

Actualicé el controlador para que traiga TODOS los eventos sin filtro.

---

## 🚀 PASOS PARA SOLUCIONAR:

### PASO 1: Limpiar caché
```
Doble clic en: fix_eventos.bat
```

### PASO 2: Verificar si tienes eventos
```
Doble clic en: verificar_eventos.bat
```

Verás algo como:
```
Total de eventos: 2
1 - Hackathon 2023 (completado)
2 - Datathon 2024 (abierto)
```

### PASO 3A: Si tienes eventos (cuenta > 0)
✅ ¡Perfecto! Recarga la página y deberían aparecer.

### PASO 3B: Si NO tienes eventos (cuenta = 0)
❌ Necesitas crear eventos de prueba:
```
Doble clic en: crear_datos_prueba.bat
```

---

## 🎯 DESPUÉS DE LOS PASOS:

1. Recarga el navegador (Ctrl+F5)
2. Dashboard Admin → Constancias
3. Pestaña "Generar Nuevas"
4. ¡Los eventos deberían aparecer en el dropdown!

---

## 📝 RESUMEN DE ARCHIVOS:

```
1. fix_eventos.bat           ← Ejecuta primero (limpia caché)
2. verificar_eventos.bat     ← Ejecuta segundo (verifica BD)
3. crear_datos_prueba.bat    ← Solo si NO tienes eventos
```

---

## 🐛 SI AÚN NO APARECEN:

### Opción 1: Crear evento manualmente
1. Ve a: Dashboard Admin
2. Clic en "Crear Evento"
3. Llena el formulario
4. Guarda el evento
5. Vuelve a Constancias

### Opción 2: Usar SQL directo
Abre phpMyAdmin o MySQL y ejecuta:
```sql
SELECT id, nombre, estado FROM eventos;
```

Si no hay resultados = no hay eventos en la BD.

---

## ✅ CAMBIOS QUE HICE:

**Antes:**
```php
$eventos = Evento::where('estado', 'finalizado')
    ->orWhere('estado', 'activo')
    ->get();
```

**Ahora:**
```php
$eventos = Evento::orderBy('created_at', 'desc')->get();
```

Ahora trae TODOS los eventos, no importa su estado.

---

**Ejecuta `fix_eventos.bat` y luego `verificar_eventos.bat` y dime qué sale!** 🚀
