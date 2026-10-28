FROM php:8.2-apache

# Installer l'extension PostgreSQL pour PHP
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activer le module rewrite
RUN a2enmod rewrite

# Copier le code de l'application
COPY . /var/www/html/

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html