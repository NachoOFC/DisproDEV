FROM php:8.2-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copiar archivos
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Compilar assets
RUN npm install && npm run prod

# Permisos
RUN chmod -R 755 storage bootstrap/cache

# Apache configuration
RUN echo '<Directory /var/www/html/public>\n  AllowOverride All\n  Require all granted\n</Directory>' > /etc/apache2/conf-available/laravel.conf && \
    a2enconf laravel && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
