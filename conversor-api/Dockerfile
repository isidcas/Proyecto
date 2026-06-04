FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

RUN a2enmod rewrite

# 1. ESTO ES LO IMPORTANTE: Apuntamos directamente a tu carpeta 'public'
RUN sed -i 's|/var/www/html|/var/www/html/html/Proyect/public|g' /etc/apache2/sites-available/000-default.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Tu código está aquí dentro
WORKDIR /var/www/html

COPY . .

# 3. Permisos a la carpeta real
RUN chown -R www-data:www-data /var/www/html/html/Proyect/public

EXPOSE 80