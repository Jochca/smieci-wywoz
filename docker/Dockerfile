# ----------------------------
# MARK:       Base           -
# ----------------------------
FROM php:8.3.12-fpm-alpine3.19 AS base

RUN echo "::group:: Docker build"

RUN apk update
RUN apk add --no-cache \ 
    nginx \ 
    supervisor \ 
    curl \
    libzip-dev \
    zip \
    bash \
    supercronic \
    mysql-client \
    tzdata

RUN docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql && \
    docker-php-ext-enable sodium && \
    docker-php-ext-install zip

COPY docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor/conf.d/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/supercronic/crontab /usr/local/etc/supercronic/crontab

RUN rm /usr/local/etc/php-fpm.d/www.conf.default
RUN chown -R nobody.nobody /var/www/html && \
    chown -R nobody.nobody /run && \
    chown -R nobody.nobody /var/lib/nginx && \
    chown -R nobody.nobody /var/log/nginx && \
    chown -R nobody.nobody /usr/local/etc/supercronic 

WORKDIR /var/www/html

# ----------------------------



# ----------------------------
# MARK:    Composer          -
# ----------------------------
FROM base AS base-composer

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN alias composer='php /usr/bin/composer'

# ----------------------------



# ----------------------------
# MARK:   Development        -
# ----------------------------
FROM base-composer AS development

RUN apk add --no-cache $PHPIZE_DEPS
RUN apk add --update linux-headers
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY docker/php-fpm/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY docker/php-fpm/conf.d/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini

# Pliki będą dołączone przez mount

USER nobody

EXPOSE 80
ENTRYPOINT ["/usr/bin/supervisord", "--configuration", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1/api/v1/health

# ----------------------------



# ----------------------------
# MARK:       Test           -
# ----------------------------
FROM base-composer AS test

COPY --chown=nobody . /var/www/html
COPY .env.ci .env

RUN --mount=type=cache,target=/tmp/cache \
    composer install --no-interaction

USER nobody

RUN echo "::group:: Tests"

RUN php artisan test

# ----------------------------



# ----------------------------
# MARK:    Production        -
# ----------------------------
FROM base-composer AS production

COPY --chown=nobody . /var/www/html
COPY --chown=nobody .env.prod .env

RUN --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction
    
USER nobody

RUN php artisan storage:link
    
EXPOSE 80
ENTRYPOINT ["/usr/bin/supervisord", "--configuration", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1/api/v1/health

# ----------------------------