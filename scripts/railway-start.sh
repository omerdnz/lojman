#!/bin/sh
set -e

PORT="${PORT:-8080}"

# Railway Postgres otomatik URL
if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_URL:-}" ]; then
    export DB_URL="${DATABASE_URL}"
fi
if [ -n "${DATABASE_URL:-}" ] && [ -z "${DB_CONNECTION:-}" ]; then
    export DB_CONNECTION=pgsql
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "HATA: Railway Variables icinde APP_KEY tanimli degil."
    echo "Yerelde: php artisan key:generate --show"
    exit 1
fi

php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan package:discover --ansi 2>/dev/null || true

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "Veritabani baglantisi deneniyor (DB_CONNECTION=${DB_CONNECTION:-yok})..."
ATTEMPT=1
MAX_ATTEMPTS=15
until php artisan migrate --force --no-interaction; do
    if [ "$ATTEMPT" -ge "$MAX_ATTEMPTS" ]; then
        echo "HATA: migrate ${MAX_ATTEMPTS} denemede basarisiz. Variables: DB_CONNECTION, DATABASE_URL/DB_URL kontrol edin."
        exit 1
    fi
    echo "Migrate basarisiz (${ATTEMPT}/${MAX_ATTEMPTS}), 4 sn sonra tekrar..."
    ATTEMPT=$((ATTEMPT + 1))
    sleep 4
done

if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "Seed calistiriliyor..."
    php artisan db:seed --force --no-interaction
fi

echo "Sunucu baslatiliyor: 0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}" --no-reload
