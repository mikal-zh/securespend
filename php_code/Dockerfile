FROM php:8.2-fpm
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli

# Set working directory
WORKDIR /var/www/html

# Copy source code to working directory
COPY . .

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt update && apt install zip unzip

# Pull dependency from composer.json
RUN composer install
RUN touch .env

# Expose port 9000 and start php-fpm server
EXPOSE 9000

CMD ["php-fpm"]