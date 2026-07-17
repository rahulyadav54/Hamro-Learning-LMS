FROM webdevops/php-nginx:8.4

ENV WEB_DOCUMENT_ROOT=/app
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Copy application source (vendor/ is excluded via .dockerignore)
COPY . /app/

# Install PHP dependencies without running build-time scripts.
# The deployed dependency set now requires PHP 8.4.1+, so the container must
# match that runtime or Composer will generate a failing platform check.
# Composer versions available on some build hosts do not support
# --no-security-blocking, so keep the install command compatible.
RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && composer dump-autoload --optimize --no-scripts

# Nginx vhost for the project-root-as-webroot layout (root index.php).
COPY docker/nginx/vhost.conf /opt/docker/etc/nginx/vhost.common.d/10-infix.conf

# Runtime entrypoint (key:generate + package:discover + storage:link).
COPY docker/entrypoint.sh /docker-entrypoint-app.sh
RUN chmod +x /docker-entrypoint-app.sh

# Create runtime writable directories first; empty dirs may not exist in the
# Docker build context when .dockerignore filters their contents.
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Run our entrypoint first, then hand off to the base image entrypoint.
ENTRYPOINT ["/docker-entrypoint-app.sh"]
