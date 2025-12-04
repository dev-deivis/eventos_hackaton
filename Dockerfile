FROM php:8.2-cli

# Instalar dependencias críticas SOLAMENTE
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_pgsql pgsql gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

# Copiar dependencias primero (cache de Docker)
COPY composer.json composer.lock ./

# Instalar dependencias PHP
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --optimize-autoloader

# Copiar código
COPY . .

# Completar autoload
RUN composer dump-autoload --optimize --no-dev

# Crear directorios
RUN mkdir -p storage/framework/{cache/data,sessions,views} \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Comando - migraciones, seeders, servidor

CMD php artisan migrate --force && php artisan config:clear && php artisan serve --host=0.0.0.0 --port=8080