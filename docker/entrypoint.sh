#!/bin/bash
set -e

# Default to 80 if PORT is not set
CLEAN_PORT="${PORT:-80}"

# Apply PORT to Nginx
sed -i "s/__PORT__/$CLEAN_PORT/g" /etc/nginx/nginx.conf

# Wait for DB if needed (optional simple ping can be done, but just run migrate with force)
echo "Running migrations..."
php artisan migrate --force

echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Supervisor (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
