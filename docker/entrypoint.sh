#!/bin/bash

# Wait for database to be ready
echo "Waiting for MySQL to be ready..."
while ! timeout 1 bash -c "</dev/tcp/mysql/3306" 2>/dev/null; do
  sleep 1
done
echo "MySQL is ready!"

# Setup Laravel application
echo "Setting up Laravel application..."

# Generate app key if not exists
if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
fi

# Generate application key
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Seed database if needed
php artisan db:seed --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

echo "Laravel application setup completed!"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf