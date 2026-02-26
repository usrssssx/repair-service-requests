#!/usr/bin/env sh
set -e

cd /var/www

if [ ! -f .env ]; then
  cp .env.example .env
fi

# Ensure dependencies are installed before running artisan
composer install --no-interaction

if ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --ansi
fi

mkdir -p database
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

php artisan migrate --force

USER_COUNT=0
if [ -f database/database.sqlite ]; then
  USER_COUNT=$(sqlite3 database/database.sqlite "select count(*) from users;" 2>/dev/null || echo 0)
fi

if [ "$USER_COUNT" -eq 0 ]; then
  php artisan db:seed --force
fi

php artisan serve --host=0.0.0.0 --port=8000
