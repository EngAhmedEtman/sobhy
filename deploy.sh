#!/bin/bash
set -e

echo "=========================================="
echo "🚀 Starting Deployment for Sobhy Reda Project..."
echo "=========================================="

# 1. Put Application in Maintenance Mode (Fail-safe)
(php artisan down --retry=60) 2>/dev/null || php artisan down 2>/dev/null || true

# 2. Fetch and Pull latest code from Git repository
echo "📥 Pulling latest changes from Git (branch: main)..."
git fetch origin main
git reset --hard origin/main

# 3. Install / Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Clear old caches and build new optimized cache files
echo "🧹 Optimizing Laravel framework..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Build Frontend Assets if NPM exists
if command -v npm &> /dev/null
then
    echo "⚡ Building frontend assets with Vite..."
    npm install --no-audit --no-fund
    npm run build
fi

# 7. Ensure storage symlink exists
php artisan storage:link || true

# 8. Bring Application back online
echo "✅ Bringing Application back Online..."
php artisan up || true

echo "=========================================="
echo "🎉 Deployment Completed Successfully!"
echo "=========================================="
