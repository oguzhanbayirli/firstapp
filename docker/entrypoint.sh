#!/bin/bash
set -e

echo "Starting Laravel application..."

# Wait for MySQL
echo "Waiting for MySQL..."
while ! mysqladmin ping -h"mysql" --silent; do
    sleep 1
done
echo "MySQL is ready!"

# Wait for Redis
echo "Waiting for Redis..."
while ! redis-cli -h redis ping; do
    sleep 1
done
echo "Redis is ready!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
if [ ! -L /var/www/html/public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

echo "Application is ready!"

# Execute the main command
exec "$@"
