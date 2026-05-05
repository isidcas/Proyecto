FROM php:8.2-apache

# Instala Xdebug y las dependencias necesarias
RUN pecl install -o -f xdebug \
    && docker-php-ext-enable xdebug
    
    
# Actualizamos los repositorios y las herramientas esenciales
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Instalamos las extensiones necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer
