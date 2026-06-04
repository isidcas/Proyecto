FROM php:8.2-apache

# Instalar dependencias
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# Activar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para que apunte a tu carpeta profunda
RUN sed -i 's|/var/www/html|/var/www/html/html/Proyect/public|g' /etc/apache2/sites-available/000-default.conf

# Definir directorio de trabajo
WORKDIR /var/www/html

# Copiar todo el contenido del repositorio al contenedor
COPY . .

# CAMBIO CRÍTICO: Damos permisos a TODO el directorio de trabajo recursivamente.
# Esto evita que falle si una subcarpeta específica no existe aún.
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80