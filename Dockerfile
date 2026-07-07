FROM serversideup/php:8.2-fpm-nginx

USER root

RUN install-php-extensions \
    pdo_pgsql \
    zip \
    gd \
    intl \
    bcmath

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install

RUN npm run build

RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 8080