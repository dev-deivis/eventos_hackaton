# 🎯 TARJETA DE REFERENCIA - PRESENTACIÓN MAÑANA

---

## 🔐 CREDENCIALES DE ACCESO

### **Administrador (Profesor)**
```
Email: admin@hackathon.com
Password: password
Rol: Administrador completo
```

### **Juez (Evaluador)**
```
Email: juez1@hackathon.com
Password: password
Rol: Evaluador de proyectos
```

### **Participante (Estudiante)**
```
Email: juan.perez@alumno.com
Password: password
Rol: Participante / Líder de equipo
```

---

## 📋 FLUJO DE DEMOSTRACIÓN

### **1. Como Administrador (5 min)**
- ✅ Login con admin@hackathon.com
- ✅ Ver dashboard administrativo
- ✅ Crear nuevo evento (si es necesario)
- ✅ Asignar jueces a equipos
- ✅ Ver estadísticas generales
- ✅ Generar reportes

### **2. Como Juez (5 min)**
- ✅ Login con juez1@hackathon.com
- ✅ Ver equipos asignados
- ✅ Evaluar proyecto con criterios
- ✅ Dejar comentarios
- ✅ Ver rankings en tiempo real

### **3. Como Participante (5 min)**
- ✅ Login con juan.perez@alumno.com
- ✅ Ver perfil y equipo
- ✅ Ver proyecto del equipo
- ✅ Gestionar tareas
- ✅ Ver evaluaciones recibidas
- ✅ Descargar constancia (si aplica)

---

## 🚨 CHECKLIST PRE-PRESENTACIÓN

### **Antes de la Reunión:**
- [ ] App desplegada y funcionando
- [ ] Probar login con las 3 cuentas
- [ ] Verificar que hay datos (eventos, equipos)
- [ ] Verificar conexión a Supabase
- [ ] Tener URL lista: `https://tu-app.onrender.com`

### **Durante la Presentación:**
- [ ] Tener laptop con buena conexión
- [ ] Navegador con pestañas abiertas (Admin, Juez, Participante)
- [ ] Tener esta tarjeta visible
- [ ] Tener acceso a Supabase Dashboard
- [ ] Tener acceso a Render Dashboard (logs)

---

## 🔧 URLs IMPORTANTES

```
App Principal: https://TU-APP.onrender.com
Supabase Dashboard: https://supabase.com/dashboard
Render Dashboard: https://dashboard.render.com
GitHub Repo: https://github.com/TU-USUARIO/hackathon-events
```

---

## ⚡ COMANDOS DE EMERGENCIA

### Si algo falla durante la demo:

**Re-desplegar en Render:**
```
Dashboard > tu-servicio > Manual Deploy > Deploy latest commit
```

**Ver logs en tiempo real:**
```
Dashboard > tu-servicio > Logs
```

**Verificar BD en Supabase:**
```
Dashboard > Table Editor > Ver tablas
```

**Re-ejecutar seeders:**
```
Render Shell:
php artisan db:seed --force
```

---

## 💡 PUNTOS CLAVE A MENCIONAR

### **Tecnologías Usadas:**
- ✅ Laravel 11 (Backend)
- ✅ PostgreSQL en Supabase (Base de datos en la nube)
- ✅ Tailwind CSS + Alpine.js (Frontend)
- ✅ Sistema de roles (Admin, Juez, Participante)
- ✅ Notificaciones en tiempo real
- ✅ Deploy en Render (Cloud hosting)

### **Funcionalidades Destacadas:**
- ✅ Gestión completa de eventos
- ✅ Sistema de equipos y proyectos
- ✅ Evaluación por criterios
- ✅ Rankings automáticos
- ✅ Generación de constancias PDF
- ✅ Dashboard por roles
- ✅ Gestión de tareas por equipo

---

## 🎭 POSIBLES PREGUNTAS

**P: ¿Por qué Supabase?**
R: Base de datos PostgreSQL en la nube, gratuita, con backups automáticos y panel visual para administración.

**P: ¿Por qué Render?**
R: Hosting gratuito, deploy automático desde GitHub, y soporte nativo para Laravel.

**P: ¿Cómo escala?**
R: Supabase permite hasta 500MB gratis, Render ofrece planes pagos para más recursos.

**P: ¿Es seguro?**
R: Sí, usa HTTPS, encriptación SSL en BD, passwords hasheados, y validaciones en backend.

**P: ¿Tiempo de desarrollo?**
R: [Tu respuesta - ej: "2 meses, trabajando 3-4 horas diarias"]

---

## 📊 ESTADÍSTICAS PARA MENCIONAR

- **Tablas en BD:** 28+
- **Modelos Laravel:** 18
- **Migraciones:** 28
- **Seeders:** 6
- **Roles de usuario:** 3 (Admin, Juez, Participante)
- **Funcionalidades principales:** 15+

---

## ✅ VERIFICACIÓN FINAL (10 MIN ANTES)

```bash
# 1. Verificar app funcionando
curl https://tu-app.onrender.com

# 2. Login como admin
# Browser: admin@hackathon.com / password

# 3. Login como juez
# Browser: juez1@hackathon.com / password

# 4. Login como participante
# Browser: juan.perez@alumno.com / password

# 5. Verificar Supabase
# Dashboard > Table Editor > Ver que hay datos
```

---

## 🎯 ESTRUCTURA DE PRESENTACIÓN (15 MIN)

**1. Introducción (2 min)**
- Problema que resuelve
- Tecnologías usadas

**2. Demo Admin (5 min)**
- Crear evento
- Asignar jueces
- Ver reportes

**3. Demo Juez (4 min)**
- Evaluar proyecto
- Ver rankings

**4. Demo Participante (3 min)**
- Ver equipo
- Ver evaluaciones

**5. Cierre (1 min)**
- Funcionalidades futuras
- Preguntas

---

## 💪 CONSEJOS FINALES

✅ **Mantén la calma** - tienes backup de todo
✅ **Practica el flujo** 2-3 veces antes
✅ **Ten agua cerca** - hablarás mucho
✅ **Sonríe** - muestra confianza en tu trabajo
✅ **Si algo falla** - explica qué haría normalmente

---

**¡ÉXITO EN TU PRESENTACIÓN! 🚀**

*Guardado el: [Fecha]*
*URL de la app: [Completar después del deploy]*
*Duración estimada: 15 minutos*
