FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

RUN a2enmod rewrite

# ESTE ES EL CAMBIO MÁS SEGURO
# Editamos el archivo de configuración de Apache para que apunte directamente a 'public'
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

# Aseguramos permisos
RUN chown -R www-data:www-data /var/www/html/public

EXPOSE 80