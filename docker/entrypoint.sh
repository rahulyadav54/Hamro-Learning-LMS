#!/bin/sh
set -e

cd /app

# Generate app key if missing (.env is provided via Render env vars; this only
# seeds APP_KEY if the env var is empty in the running container).
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Rebuild the package cache now that .env / DB are available.
php artisan package:discover --ansi || true

# Make sure storage symlink exists.
php artisan storage:link --force || true

# Permissions for runtime writable dirs.
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

exec /opt/docker/bin/entrypoint.sh "$@"
