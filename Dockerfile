FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitamos mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Configuramos PHP para modo desarrollo (para ver errores en pantalla)
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html