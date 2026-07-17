FROM webdevops/php-nginx:7.4

ENV WEB_DOCUMENT_ROOT=/app
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Copy application source (vendor/ is excluded via .dockerignore)
COPY . /app/

# Install PHP dependencies without running build-time scripts.
# The Infix LMS app is Laravel 7 / PHP 7.4. The original richarvey image ships
# PHP 8, which crashes `artisan package:discover` (post-autoload-dump) -> the
# `composer install` build step exits 1. We use a PHP 7.4 base and defer the
# Laravel post-install steps to container runtime (see docker/entrypoint.sh).
# Composer versions available on some build hosts do not support
# --no-security-blocking, so keep the install command compatible.
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts \
    && composer dump-autoload --optimize --no-scripts

# Nginx vhost for the project-root-as-webroot layout (root index.php).
COPY docker/nginx/vhost.conf /opt/docker/etc/nginx/vhost.common.d/10-infix.conf

# Runtime entrypoint (key:generate + package:discover + storage:link).
COPY docker/entrypoint.sh /docker-entrypoint-app.sh
RUN chmod +x /docker-entrypoint-app.sh

RUN chmod -R 777 storage bootstrap/cache

# Run our entrypoint first, then hand off to the base image entrypoint.
ENTRYPOINT ["/docker-entrypoint-app.sh"]
