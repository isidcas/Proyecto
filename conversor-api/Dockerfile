FROM php:8.2-apache

# Actualizar e instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    wget \
    && docker-php-ext-install pdo_mysql zip

# Activar el módulo rewrite de Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar los archivos del proyecto
COPY . /var/www/html

# DESCARGAR EL CERTIFICADO SSL DE AIVEN DIRECTAMENTE EN LA RAÍZ DEL PROYECTO
RUN wget https://api.aiven.io/v1/project/fa07/service/mysql-236bdb45-project-fa07/ca -O /var/www/html/ca.pem

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Ejecutar composer
RUN composer install --no-interaction --optimize-autoloader

EXPOSE 80