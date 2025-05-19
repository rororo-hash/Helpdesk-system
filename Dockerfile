FROM php:8.1-apache

# Pasang dependencies yang diperlukan untuk PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install mysqli mbstring zip

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/data

EXPOSE 80