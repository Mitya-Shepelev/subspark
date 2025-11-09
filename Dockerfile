# SubSpark - Production Docker Image
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    ffmpeg \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    curl \
    git \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        mbstring \
        intl \
        opcache \
        exif \
        fileinfo

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP for production
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN { \
        echo 'upload_max_filesize=128M'; \
        echo 'post_max_size=128M'; \
        echo 'max_execution_time=300'; \
        echo 'max_input_time=300'; \
        echo 'memory_limit=256M'; \
        echo 'display_errors=Off'; \
        echo 'expose_php=Off'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

# Configure PHP-FPM to pass environment variables
RUN { \
        echo '[www]'; \
        echo 'clear_env = no'; \
    } > /usr/local/etc/php-fpm.d/zz-docker.conf

# Create nginx config
RUN mkdir -p /etc/nginx/http.d
COPY docker/nginx/subspark.conf /etc/nginx/http.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Create supervisor config
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Create necessary directories
RUN mkdir -p \
    /var/www/html/uploads/avatars \
    /var/www/html/uploads/covers \
    /var/www/html/uploads/files \
    /var/www/html/uploads/videos \
    /var/www/html/uploads/reels \
    /var/www/html/uploads/pixel \
    /var/www/html/uploads/xvideos \
    /var/www/html/uploads/spImages \
    /run/nginx \
    /var/log/nginx \
    /var/log/supervisor

# Set permissions
RUN chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads \
    && chown -R www-data:www-data /var/log/nginx \
    && chown -R www-data:www-data /run/nginx

# Expose port
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
