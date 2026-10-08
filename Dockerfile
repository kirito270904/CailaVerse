FROM php:8.2-apache

# Enable Apache mod_rewrite (needed for .htaccess routing)
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install fileinfo extension (used for MIME type validation on uploads)
RUN docker-php-ext-enable fileinfo || true

# Set Apache document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update default Apache site config to point to public/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

# Allow .htaccess overrides in public/ (required for mod_rewrite)
RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf 2>/dev/null || true

# Copy project files into the container
COPY . /var/www/html/

# Make sure uploads folder exists and is writable
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 755 /var/www/html/public/uploads

# Set correct ownership for the whole project
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
