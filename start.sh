#!/bin/bash
set -e

# Railway provides PORT environment variable (e.g. 8080 or dynamic port). Default to 80 if not set.
PORT="${PORT:-80}"

# Configure Apache to listen on the given PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/*.conf

# Ensure Apache foreground execution
exec apache2-foreground
