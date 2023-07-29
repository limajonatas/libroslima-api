FROM php:8.0-fpm

# Update PHP to 8.0
RUN sudo apt-get update && sudo apt-get install -y php8.0-fpm php8.0-cli php8.0-mysql

# Copy project files
COPY . .

# Update Laravel config
ENV APP_ENV local
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Install Laravel dependencies
RUN composer install

# Start Laravel server
CMD ["php artisan serve"]
