FROM php:8.2-apache

# Instalar extensiones
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos
COPY . /var/www/html

# Instalar dependencias
WORKDIR /var/www/html
RUN composer install --no-dev

# Permisos
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Puerto
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
