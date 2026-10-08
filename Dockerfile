FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Set document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Configure directory permissions and AllowOverride for .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/cailaverse.conf \
    && a2enconf cailaverse

# Copy application files
COPY . /var/www/html/

# Permissions for uploads
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod +x /var/www/html/start.sh

CMD ["/var/www/html/start.sh"]
