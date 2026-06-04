FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# Copiar nuestra configuración personalizada de Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Asegurar permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80