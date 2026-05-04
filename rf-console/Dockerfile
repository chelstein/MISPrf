# rf-console container for DigitalOcean App Platform.
# Apache + PHP 8.2; document root = public/.
# DO App Platform routes traffic to the http_port declared in .do/app.yaml
# (8080), so Apache listens on 8080 here.

FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libonig-dev libxml2-dev unzip git ca-certificates \
    && docker-php-ext-install zip mbstring \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Repoint Apache at /public, listen on 8080, allow .htaccess.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
 && sed -ri 's!Listen 80!Listen 8080!' /etc/apache2/ports.conf \
 && sed -ri 's!:80>!:8080>!' /etc/apache2/sites-enabled/*.conf \
 && sed -ri 's!AllowOverride None!AllowOverride All!' /etc/apache2/apache2.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Cache vendor layer separately for fast rebuilds.
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# Application code.
COPY . .

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
 && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

CMD ["apache2-foreground"]
