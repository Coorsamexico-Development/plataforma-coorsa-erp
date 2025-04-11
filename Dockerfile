FROM php:8.1-fpm-alpine
ARG UID=1000
ARG GID=1000

ENV TZ=UTC

RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    npm \
    sqlite \
    supervisor \
    unzip \
    wget \
    zip && \
    npm install -g npm@latest && \
    docker-php-ext-install \
    gd \
    mysqli \
    pdo \
    pdo_mysql \
    zip && \
    mkdir -p /run/nginx /app

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/php.ini

COPY . /app

RUN wget -q -O /usr/local/bin/composer http://getcomposer.org/composer.phar && \
    chmod +x /usr/local/bin/composer && \
    cd /app && \
    composer install --ignore-platform-reqs --optimize-autoloader --no-dev && \
    php artisan route:clear && \
    php artisan cache:clear && \
    php artisan storage:link && \
    npm ci && \
    npm run build && \
    chown -R www-data: /app

CMD ["sh", "/app/docker/startup.sh"]