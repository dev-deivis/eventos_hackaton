# 🧪 GUÍA DE TESTING: Sistema de Notificaciones

## 📋 Checklist de Pruebas

### ✅ Pruebas Básicas

#### 1. Acceso a la Vista
- [ ] Hacer login como **Participante**
- [ ] Click en campanita 🔔 en la navegación
- [ ] Verificar redirección a `/notificaciones`
- [ ] Verificar que la página carga correctamente

#### 2. Contador de Notificaciones
- [ ] Verificar que aparece el número en la campanita si hay no leídas
- [ ] Verificar que el badge es rojo con animación pulse
- [ ] Verificar que el contador se actualiza automáticamente (esperar 10 seg)
- [ ] Cambiar de pestaña y volver → verificar actualización automática

#### 3. Estadísticas en el Header
- [ ] Verificar que muestra "Total notificaciones"
- [ ] Verificar que muestra "No leídas" con número correcto
- [ ] Verificar que muestra "Leídas" con número correcto
- [ ] Verificar que los números suman correctamente

---

### ✅ Pruebas de Funcionalidad

#### 4. Lista de Notificaciones
- [ ] Verificar que las notificaciones se muestran en orden (más reciente primero)
- [ ] Verificar que notificaciones NO LEÍDAS tienen:
  - Fondo colorido
  - Punto rojo en esquina superior derecha
  - Borde coloreado grueso
- [ ] Verificar que notificaciones LEÍDAS tienen:
  - Fondo blanco
  - Badge verde "✓ Leída hace X"
  - Sin punto rojo

#### 5. Colores por Tipo
Verificar que cada tipo tiene su color correcto:
- [ ] `solicitud_equipo` → Azul
- [ ] `solicitud_aceptada` → Verde
- [ ] `solicitud_rechazada` → Rojo
- [ ] `tarea_asignada` → Amarillo
- [ ] `evaluacion_recibida` → Naranja
- [ ] `proyecto_aprobado` → Verde
- [ ] `constancia_generada` → Ámbar

#### 6. Iconos por Tipo
Verificar que cada tipo muestra el icono correcto:
- [ ] Equipos → Icono de personas
- [ ] Tareas → Icono de calendario
- [ ] Evaluaciones → Icono de estrella
- [ ] Mensajes → Icono de sobre
- [ ] Constancias → Icono de documento

#### 7. Timestamps
- [ ] Verificar formato "Hace X min" (< 1 hora)
- [ ] Verificar formato "Hace X h" (< 24 horas)
- [ ] Verificar formato "Hace X días" (< 7 días)
- [ ] Verificar fecha normal (> 7 días)

#### 8. Click en Notificación
- [ ] Click en notificación NO LEÍDA → Marca como leída
- [ ] Verificar redirección a la URL de acción
- [ ] Regresar a `/notificaciones`
- [ ] Verificar que ahora aparece como LEÍDA
- [ ] Verificar que el contador bajó en 1

#### 9. Marcar Todas Como Leídas
- [ ] Verificar que el botón solo aparece si hay no leídas
- [ ] Click en "Marcar todas como leídas"
- [ ] Confirmar en el diálogo
- [ ] Verificar que todas cambian a LEÍDAS
- [ ] Verificar que el contador en campanita = 0
- [ ] Verificar que el botón desaparece

#### 10. Paginación
- [ ] Si hay más de 20 notificaciones, verificar que aparece paginación
- [ ] Click en página 2 → Verificar que carga correctamente
- [ ] Click en página anterior ← Verificar navegación
- [ ] Click en página siguiente → Verificar navegación

---

### ✅ Pruebas por Rol

#### 11. Como Participante
- [ ] Crear una solicitud para unirse a equipo
- [ ] Verificar notificación en el líder del equipo
- [ ] Aceptar/rechazar solicitud
- [ ] Verificar notificación en el solicitante
- [ ] Completar una tarea
- [ ] Verificar notificación para el equipo

#### 12. Como Admin
- [ ] Crear un nuevo evento
- [ ] Verificar notificaciones para participantes
- [ ] Aprobar un proyecto
- [ ] Verificar notificación para el equipo
- [ ] Rechazar un proyecto
- [ ] Verificar notificación con motivo

#### 13. Como Juez
- [ ] Asignar equipo a juez
- [ ] Verificar notificación de asignación
- [ ] Evaluar un equipo
- [ ] Verificar notificación para el equipo evaluado
- [ ] Ver rankings
- [ ] Verificar acceso a notificaciones

---

### ✅ Pruebas de Diseño Responsive

#### 14. Desktop (> 1024px)
- [ ] Verificar layout de 3 columnas en estadísticas
- [ ] Verificar que las notificaciones ocupan buen ancho
- [ ] Verificar espaciado correcto
- [ ] Verificar que el texto no se corta

#### 15. Tablet (768px - 1023px)
- [ ] Verificar layout de 2 columnas en estadísticas
- [ ] Verificar que las notificaciones son legibles
- [ ] Verificar que la paginación funciona

#### 16. Mobile (< 767px)
- [ ] Verificar layout de 1 columna en estadísticas
- [ ] Verificar que el texto se ajusta
- [ ] Verificar que los iconos son del tamaño correcto
- [ ] Verificar que el contador en campanita es visible
- [ ] Verificar que las notificaciones son clickeables fácilmente

---

### ✅ Pruebas de Performance

#### 17. Carga Inicial
- [ ] Verificar que la página carga en < 2 segundos
- [ ] Verificar que no hay errores en consola
- [ ] Verificar que Alpine.js está cargado
- [ ] Verificar que el contador se actualiza al cargar

#### 18. Actualización Automática
- [ ] Abrir consola del navegador
- [ ] Esperar 10 segundos
- [ ] Verificar que se hace fetch automático
- [ ] Verificar que no hay errores en consola
- [ ] Verificar que el contador se actualiza

#### 19. Múltiples Notificaciones
- [ ] Crear 50+ notificaciones de prueba
- [ ] Verificar que la paginación funciona
- [ ] Verificar que no hay lag al scrollear
- [ ] Verificar que la navegación es fluida

---

### ✅ Pruebas de Edge Cases

#### 20. Sin Notificaciones
- [ ] Usuario nuevo sin notificaciones
- [ ] Verificar mensaje "No tienes notificaciones"
- [ ] Verificar icono de campana vacía
- [ ] Verificar que el contador no aparece (= 0)

#### 21. Notificación Sin URL de Acción
- [ ] Crear notificación sin `url_accion`
- [ ] Click en la notificación
- [ ] Verificar redirección al dashboard
- [ ] Verificar que se marca como leída

#### 22. Notificación con URL Inválida
- [ ] Crear notificación con URL que no existe
- [ ] Click en la notificación
- [ ] Verificar manejo del error 404
- [ ] Verificar que se marca como leída

#### 23. Cambios de Tab
- [ ] Abrir notificaciones
- [ ] Cambiar a otra pestaña del navegador
- [ ] Esperar 15 segundos
- [ ] Regresar a la pestaña
- [ ] Verificar que el contador se actualizó

---

### ✅ Pruebas de Seguridad

#### 24. Autenticación
- [ ] Intentar acceder a `/notificaciones` sin login
- [ ] Verificar redirección a login
- [ ] Login y verificar acceso correcto

#### 25. Autorización
- [ ] User A crea notificación para User B
- [ ] Login como User A
- [ ] Verificar que NO ve notificación de User B
- [ ] Login como User B
- [ ] Verificar que SÍ ve su notificación

#### 26. CSRF Protection
- [ ] Verificar que "Marcar todas" tiene token CSRF
- [ ] Intentar hacer POST sin token → Debe fallar
- [ ] Hacer POST con token → Debe funcionar

---

### ✅ Pruebas de Navegación

#### 27. Navegación Interna
- [ ] Desde dashboard → Click campanita → Notificaciones
- [ ] Desde notificaciones → Click notificación → Acción
- [ ] Desde acción → Regresar (back button) → Notificaciones
- [ ] Verificar que todo funciona correctamente

#### 28. Navegación Externa
- [ ] Copiar URL `/notificaciones`
- [ ] Pegar en nueva pestaña
- [ ] Verificar que carga correctamente
- [ ] Verificar que el contador aparece

---

## 🐛 Errores Comunes a Verificar

### Backend
- [ ] Error 500 al cargar notificaciones → Revisar logs
- [ ] Notificaciones duplicadas → Revisar seeder
- [ ] Contador incorrecto → Revisar query SQL

### Frontend
- [ ] Badge no aparece → Verificar Alpine.js
- [ ] Contador no se actualiza → Verificar setInterval
- [ ] Colores incorrectos → Verificar clases Tailwind
- [ ] Iconos no se muestran → Verificar SVG

### Base de Datos
- [ ] Tabla `notificaciones` existe
- [ ] Columna `leida` es booleana
- [ ] Columna `leida_en` es timestamp nullable
- [ ] Relaciones correctas con `users`

---

## 📊 Resultados Esperados

### ✅ PASS: Todo Correcto
```
✓ Vista carga correctamente
✓ Contador funciona
✓ Notificaciones se muestran
✓ Colores e iconos correctos
✓ Click marca como leída
✓ Redirección funciona
✓ Paginación funciona
✓ Responsive funciona
✓ Sin errores en consola
```

### ❌ FAIL: Hay Problemas
```
✗ Vista no carga → Revisar rutas
✗ Contador en 0 siempre → Revisar API
✗ Sin colores → Revisar Tailwind
✗ Click no funciona → Revisar rutas
✗ Errores en consola → Revisar JS
```

---

## 🔧 Comandos Útiles

### Crear Notificaciones de Prueba
```bash
php artisan db:seed --class=NotificacionesTestSeeder
```

### Ver Logs
```bash
tail -f storage/logs/laravel.log
```

### Limpiar Cache
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Reiniciar Servidor
```bash
php artisan serve
```

---

## 📝 Reporte de Bugs

Si encuentras un bug, documenta:

1. **¿Qué hiciste?** (Pasos para reproducir)
2. **¿Qué esperabas?** (Comportamiento esperado)
3. **¿Qué pasó?** (Comportamiento actual)
4. **Contexto:**
   - Rol: Admin/Juez/Participante
   - Navegador: Chrome/Firefox/Safari
   - Tamaño de pantalla: Desktop/Mobile
   - Errores en consola: Sí/No
5. **Screenshots** (si aplica)

---

## ✅ Checklist Final

Antes de dar por terminado:

- [ ] Todas las pruebas PASS
- [ ] No hay errores en consola
- [ ] Funciona en Chrome, Firefox, Safari
- [ ] Funciona en Desktop, Tablet, Mobile
- [ ] Funciona para Admin, Juez, Participante
- [ ] Documentación actualizada
- [ ] Código limpio y comentado

---

¡Listo para testing! 🚀
