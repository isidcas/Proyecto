FROM php:8.2-apache

# 1. Instalar dependencias
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip

# 2. Activar mod_rewrite para que tu .htaccess funcione
RUN a2enmod rewrite

# 3. Configurar Apache para apuntar directamente a tu subcarpeta
# Esto le dice a Apache que la raíz del servidor es donde está tu index.php
RUN sed -i 's|/var/www/html|/var/www/html/html/Proyect/public|g' /etc/apache2/sites-available/000-default.conf

# 4. Copiar TODO tu proyecto (conversor-api) a la carpeta base de Apache
WORKDIR /var/www/html
COPY . .

# 5. Dar permisos (al hacer chown sobre el directorio actual '.', funcionará siempre)
RUN chown -R www-data:www-data .

EXPOSE 80