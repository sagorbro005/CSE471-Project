#!/bin/bash

# Clear config cache to ensure we use environment variables from Render
php artisan config:clear

# Run migrations
php artisan migrate --force

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
