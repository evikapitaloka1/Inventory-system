FROM php:8.4-fpm

# Install system dependencies + Node.js + Nginx
RUN apt-get update && apt-get install -y \
    git curl unzip zip nginx \
    libpng-dev libonig-dev libxml2-dev libpq-dev \
    libzip-dev libsodium-dev libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip sodium opcache xml dom simplexml \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Konfigurasi Nginx
RUN echo 'server { \
    listen 8080; \
    root /app/public; \
    index index.php; \
    location / { try_files $uri $uri/ /index.php?$query_string; } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        include fastcgi_params; \
    } \
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg)$ { \
        expires 1y; \
        add_header Cache-Control "public"; \
    } \
}' > /etc/nginx/sites-available/default

EXPOSE 8080

CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php-fpm -D && \
    nginx -g "daemon off;"