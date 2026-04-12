#!/usr/bin/env bash
set -euo pipefail

# Relay Cloud — Deployment Script
# Run from /var/www/relay-cloud as the deploy user
# Usage: bash deploy/deploy.sh

APP_DIR="/var/www/relay-cloud"
cd "$APP_DIR"

echo "==> Pulling latest code..."
git pull origin main

echo "==> Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Installing Node dependencies and building assets..."
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"
npm ci
npm run build

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Setting permissions..."
chown -R deploy:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Restarting services..."
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx

echo "==> Deployment complete!"
echo "    $(date '+%Y-%m-%d %H:%M:%S')"
