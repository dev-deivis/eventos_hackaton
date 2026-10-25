# Plataforma de Gestión de Eventos y Hackathones

Aplicación web para organizar hackathones de principio a fin: registro de participantes, formación de equipos, entrega de proyectos, evaluación por jueces y emisión de constancias. Construida con **Laravel 12** y **PostgreSQL**.

## Funcionalidades

**Participantes**
- Registro e inicio de sesión con perfil obligatorio (carrera, habilidades) antes de formar equipos.
- Inscripción a eventos y cancelación del registro.
- Creación de equipos, solicitudes de ingreso (aceptar / rechazar), chat de equipo y abandono de equipo.
- Tablero de tareas por equipo con asignación a varios integrantes.
- Registro del proyecto con tecnologías utilizadas.
- Notificaciones dentro de la plataforma y por correo.

**Jueces**
- Panel propio para evaluar los proyectos de los equipos asignados.
- Evaluación por criterios con calificaciones y rankings.

**Administradores**
- CRUD de eventos, cambio de estado y dashboard por evento.
- Gestión de usuarios, premios y validación de proyectos.
- Generación de constancias en PDF.
- Reportes con exportación a Excel y PDF.

**Roles:** `admin`, `juez` y `participante`, protegidos con middleware.

## Stack

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.3, Laravel 12, Laravel Breeze (autenticación) |
| Base de datos | PostgreSQL (producción), SQLite (desarrollo local) |
| Frontend | Blade, Tailwind CSS, Alpine.js, Vite (con modo oscuro) |
| PDF / Excel | barryvdh/laravel-dompdf, maatwebsite/excel |
| Correos | Brevo (API) con SMTP configurable |
| Colas, sesiones y caché | Driver `database` |
| Despliegue | Configuración incluida para Railway (`nixpacks.toml`, `Procfile`) y Render (`render.yaml`) |

## Requisitos

- PHP 8.3 o superior (con las extensiones habituales de Laravel)
- Composer
- Node.js y npm

## Instalación

```bash
git clone https://github.com/dev-deivis/eventos_hackaton.git
cd eventos_hackaton

composer install
cp .env.example .env
php artisan key:generate

# Configura la base de datos en .env y luego:
php artisan migrate --seed

npm install
npm run dev
```

Atajos definidos en `composer.json`:

```bash
composer run setup   # instala dependencias, genera la llave, migra y compila assets
composer run dev     # servidor, cola, logs y Vite en paralelo
composer test        # ejecuta las pruebas
```

## Variables de entorno

Copia `.env.example` a `.env` y completa los valores. **Nunca subas tu `.env` al repositorio.**

| Variable | Descripción |
|----------|-------------|
| `APP_URL` | URL pública de la aplicación |
| `DB_CONNECTION` | `sqlite` (local) o `pgsql` (producción) |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Credenciales de PostgreSQL |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` | Configuración SMTP |
| `BREVO_API_KEY` | Llave de la API de Brevo para correos transaccionales |
| `QUEUE_CONNECTION`, `SESSION_DRIVER`, `CACHE_STORE` | `database` por defecto |

## Estructura

```
app/Http/Controllers   Controladores por módulo (eventos, equipos, proyectos, juez, admin...)
app/Http/Middleware    Roles y perfil completo
app/Models             Modelos Eloquent
app/Mail               Correos transaccionales
database/migrations    Esquema de la base de datos
database/seeders       Roles, carreras, usuarios y datos de ejemplo
resources/views        Vistas Blade organizadas por módulo
routes/web.php         Rutas de la aplicación
```

## Autor

Alonso David Almaraz Vásquez — [github.com/dev-deivis](https://github.com/dev-deivis)
