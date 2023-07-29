FROM php:8.0-fpm

# Update PHP to 8.0
RUN apt-get update && apt-get install -y php8.0-fpm php8.0-cli php8.0-mysql

# Copy project files
COPY . .

# Update Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Install Laravel dependencies
RUN composer install

# Start Laravel server
CMD ["php artisan serve"]
