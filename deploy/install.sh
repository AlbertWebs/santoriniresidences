#!/usr/bin/env bash
set -euo pipefail
cd /var/www/santorini-staging
if [ ! -f .env ]; then
    cp .env.example .env
    sed -i 's/^APP_NAME=.*/APP_NAME="Santorini Residences"/; s/^APP_ENV=.*/APP_ENV=staging/; s/^APP_DEBUG=.*/APP_DEBUG=false/; s|^APP_URL=.*|APP_URL=https://staging.santoriniresidences.com|; s/^LOG_LEVEL=.*/LOG_LEVEL=warning/; s/^SESSION_ENCRYPT=.*/SESSION_ENCRYPT=true/' .env
    printf '\nSESSION_SECURE_COOKIE=true\n' >> .env
    printf 'ADMIN_PASSWORD=%s\n' "$(openssl rand -hex 20)" >> .env
    touch database/database.sqlite
fi
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
if grep -q '^APP_KEY=$' .env; then php artisan key:generate --force; fi
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
sudo chown -R ubuntu:www-data /var/www/santorini-staging
sudo chmod 640 .env
sudo chmod -R g+rwX storage bootstrap/cache database
sudo install -d /var/www/santorini-coming-soon
sudo install -m 644 deploy/under-construction/index.html /var/www/santorini-coming-soon/index.html
sudo install -m 644 public/media/tower-dusk.webp public/media/logo-santorini.png /var/www/santorini-coming-soon/
sudo install -m 644 deploy/nginx.conf /etc/nginx/sites-available/santorini
sudo ln -s /etc/nginx/sites-available/santorini /etc/nginx/sites-enabled/santorini
sudo nginx -t
sudo systemctl reload nginx
