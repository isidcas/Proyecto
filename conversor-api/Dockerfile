FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# CAMBIO: Apuntar directamente a la carpeta 'public' en la raíz
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar todo el proyecto
WORKDIR /var/www/html
COPY . .

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80