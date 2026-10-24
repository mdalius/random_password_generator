# syntax=docker/dockerfile:1
FROM php:8.5-fpm-alpine

RUN apk add --no-cache nginx \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html
COPY index.php random.php ./
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/app-entrypoint

USER www-data
EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD php -r '$body = @file_get_contents("http://127.0.0.1:8080/"); exit($body !== false && str_contains($body, "<table") ? 0 : 1);'
ENTRYPOINT ["app-entrypoint"]
CMD ["php-fpm", "-F"]
