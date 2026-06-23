#!/bin/sh

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

echo "=== Lojman baslatiliyor (PORT=${PORT}) ==="
echo "DB_CONNECTION=${DB_CONNECTION:-yok}"
echo "DATABASE_URL=${DATABASE_URL:+tanimli}"

php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan package:discover --ansi 2>/dev/null || true

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Healthcheck icin once HTTP sunucusunu baslat (migrate DB'yi bekletmesin)
echo "Sunucu baslatiliyor: 0.0.0.0:${PORT}"
php artisan serve --host=0.0.0.0 --port="${PORT}" --no-reload &
SERVER_PID=$!
sleep 3

if ! kill -0 "$SERVER_PID" 2>/dev/null; then
    echo "HATA: Sunucu baslatilamadi."
    exit 1
fi
echo "Sunucu calisiyor (PID ${SERVER_PID}). Healthcheck: /health"

echo "Veritabani migrate..."
ATTEMPT=1
MAX_ATTEMPTS=20
MIGRATE_OK=0
while [ "$ATTEMPT" -le "$MAX_ATTEMPTS" ]; do
    if timeout 20 php artisan migrate --force --no-interaction 2>&1; then
        MIGRATE_OK=1
        break
    fi
    echo "Migrate denemesi ${ATTEMPT}/${MAX_ATTEMPTS} basarisiz, 3 sn bekleniyor..."
    ATTEMPT=$((ATTEMPT + 1))
    sleep 3
done

if [ "$MIGRATE_OK" -eq 0 ]; then
    echo "UYARI: Migrate tamamlanamadi. DATABASE_URL ve PostgreSQL servisini kontrol edin."
else
    echo "Migrate tamamlandi."
    if [ "${RUN_SEED:-false}" = "true" ]; then
        echo "Seed calistiriliyor..."
        timeout 120 php artisan db:seed --force --no-interaction 2>&1 || echo "UYARI: Seed basarisiz."
    fi
fi

echo "Uygulama hazir."
wait "$SERVER_PID"
