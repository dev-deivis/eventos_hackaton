# Render Deployment Configuration

Este archivo define la configuración para desplegar automáticamente en Render.

## Base de datos incluida

El archivo `render.yaml` incluye:
- Web service (aplicación Laravel)
- PostgreSQL database (plan gratuito 90 días)

## Variables de entorno automáticas

Render configurará automáticamente:
- `DATABASE_URL` - Conexión a PostgreSQL
- `APP_KEY` - Se genera automáticamente
- `APP_URL` - URL de tu aplicación

## Pasos para desplegar

### 1. Preparar el repositorio
```bash
git add .
git commit -m "Add Render configuration"
git push
```

### 2. Crear cuenta en Render
- Ve a https://render.com
- Regístrate con GitHub

### 3. Crear nuevo servicio
- Click en "New +"
- Selecciona "Blueprint"
- Conecta tu repositorio
- Render detectará automáticamente `render.yaml`

### 4. Variables de entorno adicionales
Después del primer deploy, agrega en el dashboard de Render:
- `APP_URL` - Tu URL de Render (ej: https://tu-app.onrender.com)

## Archivos importantes

- `render-build.sh` - Script que se ejecuta al construir la aplicación
- `render.yaml` - Configuración de servicios y base de datos
- `.env.production` - Variables de entorno para producción

## Base de datos

El plan gratuito incluye:
- 1 GB de almacenamiento
- PostgreSQL 16
- Dura 90 días, después se pausa (no se elimina)

Para extender después de 90 días:
- Actualiza a plan de pago ($7/mes)
- O usa base de datos externa gratuita (Neon, Supabase)

## Troubleshooting

### Error "Permission denied" en render-build.sh
El archivo necesita permisos de ejecución. Render lo hace automáticamente.

### Error con migraciones
Verifica que todas tus migraciones sean compatibles con PostgreSQL.
SQLite y PostgreSQL tienen algunas diferencias menores.

### Assets no se cargan
Verifica que `APP_URL` esté correctamente configurado en las variables de entorno.
