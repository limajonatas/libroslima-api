FROM php:8.1-fpm-alpine

RUN apk add --no-cache bash
RUN apk add nginx
RUN docker-php-ext-install pdo pdo_mysql sockets
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY conf/ngnix/ngnix.conf /etc/nginx/conf.d/default.conf
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install

# Limpar e gerar o cache das configurações e rotas do Laravel
RUN php artisan config:cache && php artisan route:cache

# Executar o migrate para aplicar as migrações no banco de dados
# RUN php artisan migrate --force

# Iniciar o servidor PHP-FPM
# CMD ["php-fpm"]
CMD ["nginx", "-g", "daemon off;"]
# CMD ["/scripts/deploy.sh"]

