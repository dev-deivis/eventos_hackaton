# 🚀 GUÍA DE DESPLIEGUE - SISTEMA DE CORREOS EN RAILWAY

## ✅ ESTADO ACTUAL

- ✅ Sistema de correos funcionando en LOCAL
- ✅ Credenciales Brevo correctas
- ✅ 6 tipos de correos implementados
- 🔄 Pendiente: Configurar en Railway

---

## 📋 PASO A PASO

### **PASO 1: Probar todos los correos en local** ⏱️ 5 minutos

Antes de subir a producción, verifica que TODOS los tipos de correos funcionen:

```bash
php prueba-todos-correos.php
```

Esto enviará 6 correos de prueba a tu email:
1. 🎉 Nuevo Evento
2. 👥 Solicitud a Equipo
3. ✅ Solicitud Aceptada
4. ⭐ Evaluación Completada
5. ✅ Proyecto Aprobado
6. 🏆 Constancia Generada

**Resultado esperado:**
```
✅ Todos los correos enviados exitosamente
📧 Revisa tu bandeja: deberías tener 6 correos
```

---

### **PASO 2: Configurar variables en Railway** ⏱️ 3 minutos

#### Opción A: Usar el script (recomendado)
```bash
.\configurar-railway.bat
```

#### Opción B: Manual

1. **Abrir Railway**
   - Ve a: https://railway.app/
   - Login con tu cuenta
   - Selecciona tu proyecto

2. **Ir a Variables**
   - Click en tu servicio web
   - Pestaña **"Variables"**
   - Click **"New Variable"**

3. **Agregar estas variables:**

```env
MAIL_ENABLED=true
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=9d814c001@smtp-brevo.com
MAIL_PASSWORD=TU_CLAVE_SMTP_DE_BREVO_AQUI
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email_verificado@gmail.com
MAIL_FROM_NAME=Hackathon Events
```

⚠️ **MUY IMPORTANTE:** 
- Copia `MAIL_PASSWORD` EXACTAMENTE como está en tu `.env` local
- NO agregues espacios ni comillas extras

4. **Guardar**
   - Railway hará **redeploy automático**
   - Espera 2-3 minutos

---

### **PASO 3: Verificar el deploy** ⏱️ 2 minutos

1. **Ver progreso del deploy**
   - Pestaña **"Deployments"** en Railway
   - Debe mostrar: **"Building"** → **"Deploying"** → **"Success"**

2. **Revisar logs**
   - Pestaña **"Logs"** en Railway
   - Busca errores relacionados con `MAIL_`
   - Debe aparecer: `Configuration loaded successfully`

---

### **PASO 4: Probar en producción** ⏱️ 5 minutos

#### Prueba 1: Crear un evento (si eres admin)

1. Ve a tu app: https://web-production-ef44a.up.railway.app/
2. Login como **admin**
3. Ir a **"Eventos"** → **"Crear Evento"**
4. Crea un evento de prueba
5. **Resultado esperado:**
   - Notificación en la app: ✅
   - Correo enviado a participantes: ✅

#### Prueba 2: Solicitud a equipo

1. Login como **participante**
2. Ir a **"Equipos"**
3. Solicitar unirse a un equipo
4. **Resultado esperado:**
   - Notificación al líder: ✅
   - Correo al líder: ✅

#### Prueba 3: Revisar logs

En Railway, pestaña **"Logs"**, busca:
```
[INFO] Correo enviado exitosamente
```

---

## 🔍 VERIFICACIÓN COMPLETA

### ✅ Checklist de producción

- [ ] Variables configuradas en Railway
- [ ] Deploy exitoso (sin errores)
- [ ] Logs muestran "Correo enviado exitosamente"
- [ ] Correos llegando a la bandeja
- [ ] No van a SPAM
- [ ] Enlaces en correos funcionan
- [ ] Plantillas se ven bien en móvil y escritorio

---

## 🐛 TROUBLESHOOTING

### Error: "Authentication failed" en Railway

**Causa:** Variable `MAIL_PASSWORD` incorrecta

**Solución:**
1. Ve a tu `.env` local
2. Copia el valor exacto de `MAIL_PASSWORD`
3. Actualiza en Railway (Variables)
4. Railway hará redeploy automático

---

### Error: "Connection refused"

**Causa:** Puerto bloqueado (raro con Brevo, común con Gmail)

**Solución:**
- Brevo debería funcionar sin problemas
- Si persiste, contacta a soporte de Railway

---

### Correos no llegan (pero no hay error)

**Causa:** Email no verificado en Brevo

**Solución:**
1. Ve a Brevo → **"Senders"**
2. Verifica que `alonsoalmaraz18@gmail.com` tenga ✅ verde
3. Si no, click **"Verify"** y revisa tu correo

---

### Correos van a SPAM

**Solución:**
1. **Verificar dominio en Brevo** (opcional, mejora deliverability)
2. **Configurar SPF:**
   - Agregar a tu DNS: `v=spf1 include:spf.brevo.com ~all`
3. **Activar DKIM en Brevo:**
   - Settings → Senders → DKIM

---

## 📊 MONITOREO

### Dashboard de Brevo

- Ve a: https://app.brevo.com/
- **Statistics** → Ver:
  - Correos enviados
  - Tasa de apertura
  - Clicks
  - Bounces

### Logs de Laravel

En Railway, pestaña **"Logs"**, filtra por:
```
grep "Correo"
```

---

## 🎯 PRÓXIMOS PASOS (Opcional)

### 1. Queue para correos asíncronos (recomendado)

Actualmente los correos se envían **sincrónicamente** (bloquean la petición).

Para mejorar:
```bash
# En Railway, agrega variable:
QUEUE_CONNECTION=database

# Luego, en Railway, ejecuta:
php artisan queue:work --daemon
```

Esto enviará correos en **background** sin bloquear.

---

### 2. Preferencias de usuario

Permitir a usuarios elegir qué notificaciones quieren por correo:

```php
// Tabla: user_preferences
- user_id
- notif_nuevo_evento (bool)
- notif_solicitud_equipo (bool)
- notif_evaluacion (bool)
- etc.
```

---

### 3. Plantillas personalizadas

- Agregar logo de tu institución
- Colores institucionales
- Footer con redes sociales

---

## 📈 MÉTRICAS ESPERADAS

Con 300 correos/día de Brevo (plan gratuito):

- **Desarrollo:** ~10-20 correos/día (suficiente)
- **Producción (20 usuarios):** ~50-100 correos/día (suficiente)
- **Producción (100 usuarios):** ~200-300 correos/día (ajustado)

Si superas 300/día, considera:
- Upgrade a plan de pago de Brevo
- Usar queue para agrupar notificaciones

---

## ✅ RESUMEN

```
1. ✅ Probar local: php prueba-todos-correos.php
2. ✅ Configurar Railway: .\configurar-railway.bat
3. ✅ Esperar deploy (2-3 min)
4. ✅ Probar en producción
5. ✅ Verificar logs
6. 🎉 ¡Listo!
```

---

## 📞 SOPORTE

Si tienes problemas:
1. Revisa los logs de Railway
2. Verifica las variables de entorno
3. Prueba regenerar API Key de Brevo
4. Contacta a soporte de Railway si es problema de red

---

**Fecha:** Diciembre 8, 2024  
**Versión:** 1.0  
**Estado:** ✅ Sistema probado y funcionando en local  
**Siguiente:** 🚀 Deploy a Railway  

---

## 🎓 DOCUMENTACIÓN RELACIONADA

- `SISTEMA_CORREOS_IMPLEMENTADO.md` - Documentación técnica completa
- `GUIA_CONFIGURACION_BREVO.md` - Configuración detallada de Brevo
- `CHECKLIST_ACTIVACION_CORREOS.md` - Lista de verificación
- `RESUMEN_CORREOS_BREVO.md` - Resumen ejecutivo

---

¡Sistema de correos listo para producción! 🚀📧
