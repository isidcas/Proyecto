FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql zip

# Activar el módulo rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar todo el proyecto (esto ya incluye tu archivo ca.pem local)
COPY . /var/www/html

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

# Instalar dependencias
RUN composer install --no-interaction --optimize-autoloader

EXPOSE 80