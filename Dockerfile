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
    curl-dev \
    openssl-dev \
    git \
    zip \
    unzip \
    autoconf \
    g++ \
    make

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
        fileinfo \
        calendar \
        curl

# Install Redis extension from PECL
RUN pecl install redis-6.0.2 \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure PHP for production (optimized settings)
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=256'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'opcache.jit=tracing'; \
        echo 'opcache.jit_buffer_size=128M'; \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.save_comments=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN { \
        echo 'upload_max_filesize=128M'; \
        echo 'post_max_size=128M'; \
        echo 'max_execution_time=600'; \
        echo 'max_input_time=600'; \
        echo 'memory_limit=512M'; \
        echo 'display_errors=Off'; \
        echo 'expose_php=Off'; \
        echo 'allow_url_fopen=On'; \
        echo 'error_log=/proc/self/fd/2'; \
        echo 'log_errors=On'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

# Configure PHP-FPM to pass environment variables and enable error logging
RUN { \
        echo '[www]'; \
        echo 'clear_env = no'; \
        echo 'catch_workers_output = yes'; \
        echo 'decorate_workers_output = no'; \
        echo '; Maximum time for processing a single request (10 minutes for FFmpeg)'; \
        echo 'request_terminate_timeout = 600'; \
        echo '; Log slow requests (30 seconds)'; \
        echo 'request_slowlog_timeout = 30'; \
        echo 'slowlog = /proc/self/fd/2'; \
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

# Install PHP dependencies (AWS SDK, QR Code, etc.)
RUN cd /var/www/html/includes && composer install --no-dev --optimize-autoloader

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
