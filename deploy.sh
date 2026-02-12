#!/bin/bash

# Install Node.js dependencies
npm ci --only=production

# Build assets
npm run build

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Clear Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Deployment completed successfully!"