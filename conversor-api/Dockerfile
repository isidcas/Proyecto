FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar todo el contenido a una carpeta temporal
COPY . /tmp/proyecto

# MOVER TUS ARCHIVOS A LA RAÍZ DE APACHE
# Esto toma tu carpeta 'public' y la pone en /var/www/html
RUN mv /tmp/proyecto/html/Proyect/public/* /var/www/html/

# Asegurar que el resto de tu código (App, etc.) esté disponible
RUN mv /tmp/proyecto/html/Proyect/App /var/www/html/App

# Limpiar archivos temporales
RUN rm -rf /tmp/proyecto

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80