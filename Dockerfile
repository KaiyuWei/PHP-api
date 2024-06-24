FROM arm64v8/php:8.2-fpm-alpine

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    mysql-client

RUN docker-php-ext-install pdo_mysql

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN curl -o /usr/local/bin/wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh \
    && chmod +x /usr/local/bin/wait-for-it.sh

COPY --chown=www-data:www-data . /var/www
COPY entrypoint.sh /usr/local/bin

RUN chown -R www-data:www-data /var/www

RUN composer install --no-dev --optimize-autoloader

RUN composer run:migrations

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
