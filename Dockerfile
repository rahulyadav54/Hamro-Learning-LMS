FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Environment presets
ENV WEBROOT /var/www/html
ENV APP_ENV production
ENV APP_DEBUG false
