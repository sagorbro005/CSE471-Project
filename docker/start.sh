#!/bin/bash

# Clear config cache to ensure we use environment variables from Render
php artisan config:clear

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Check if we need to migrate persistent data from a previous deployment
if [ -d "/var/www/html/storage/persistent/uploads" ]; then
  echo "Restoring previous uploads from persistent storage..."
  cp -R /var/www/html/storage/persistent/uploads/* /var/www/html/storage/app/public/ 2>/dev/null || true
fi

# Ensure storage directory structure and permissions are correct
mkdir -p /var/www/html/storage/app/public/products
mkdir -p /var/www/html/storage/app/public/sliders
mkdir -p /var/www/html/storage/app/public/blogs
mkdir -p /var/www/html/storage/persistent/uploads/products
mkdir -p /var/www/html/storage/persistent/uploads/sliders
mkdir -p /var/www/html/storage/persistent/uploads/blogs

chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/storage

# Ensure public directory has correct permissions (ALL of public)
chmod -R 755 /var/www/html/public
chown -R www-data:www-data /var/www/html/public

echo "Storage setup complete"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
