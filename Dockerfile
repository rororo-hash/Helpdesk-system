# Gunakan official PHP 8.1 dengan Apache
FROM php:8.1-apache

# Install extension PHP yang diperlukan
RUN docker-php-ext-install mysqli mbstring

# Salin semua fail projek ke folder web server
COPY . /var/www/html/

# Pastikan folder data boleh tulis
RUN chown -R www-data:www-data /var/www/html/data

# Buka port 80 untuk web
EXPOSE 80
