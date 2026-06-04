FROM php:8.2-apache
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip
RUN a2enmod rewrite
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
EXPOSE 80