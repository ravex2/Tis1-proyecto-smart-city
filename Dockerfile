FROM php:8.2-apache

# Instalamos dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Instalamos extensiones PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql zip bcmath

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Habilitamos mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Configuramos PHP para modo desarrollo
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Configuramos Apache para permitir .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Configuramos el DocumentRoot (por si acaso)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Creamos directorio de trabajo
WORKDIR /var/www/html

# Exponemos el puerto 80
EXPOSE 80