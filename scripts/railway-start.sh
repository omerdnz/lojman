#!/bin/sh
set -e

# Build asamasinda olusan onbellek, Railway runtime env degiskenlerini icermez.
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Running migrations..."
php artisan migrate --force

if [ "${RUN_SEED}" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force
fi

echo "Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
