FROM php:8.2-apache

# Enable mod_rewrite for clean routing
RUN a2enmod rewrite

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Configure Apache Document Root to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf 2>/dev/null || true

# Copy application files
COPY . /var/www/html/

# Directory permissions
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]
