#!/bin/sh
set -e

php artisan migrate --force

if [ "${RUN_SEED}" = "true" ]; then
    php artisan db:seed --force
fi

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
