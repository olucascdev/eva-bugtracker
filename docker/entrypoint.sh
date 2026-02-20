#!/bin/sh
set -e

echo "🚀 Starting Bug Tracker Eva..."

# Storage directories
echo "📁 Ensuring storage directories..."
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Storage link
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

# Cache config, routes, views
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

echo "✅ Application ready!"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
