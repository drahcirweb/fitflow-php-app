FROM php:8.2-apache

# Instalar extensiones
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Habilitar rewrite
RUN a2enmod rewrite

# Copiar archivos
COPY . /var/www/html

# Permisos para uploads
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]