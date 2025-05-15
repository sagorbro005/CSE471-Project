#!/bin/bash

# Clear config cache to ensure we use environment variables from Render
php artisan config:clear

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Ensure storage directory is writable
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage

# Setup static images in public directory
/var/www/html/docker/static-images.sh

# Ensure public directory has correct permissions
chmod -R 755 /var/www/html/public
chown -R www-data:www-data /var/www/html/public

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
