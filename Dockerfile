FROM php:8.1-fpm-alpine

# RUN apk add --no-cache bash
# RUN apk add nginx
# RUN docker-php-ext-install pdo pdo_mysql sockets
# RUN curl -sS https://getcomposer.org/installer | php -- \
#      --install-dir=/usr/local/bin --filename=composer

# Instale o driver do PostgreSQL
RUN apk add --update --no-cache postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql sockets

# Instale o cliente PostgreSQL (opcional, mas útil para executar comandos de linha de comando)
RUN apk add --update --no-cache postgresql-client

COPY conf/ngnix/ngnix.conf /etc/nginx/conf.d/default.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install

# Limpar e gerar o cache das configurações e rotas do Laravel
RUN php artisan config:cache && php artisan route:cache
CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]

