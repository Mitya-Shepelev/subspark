# SubSpark Video Processing Worker
FROM php:8.3-cli-alpine

# Install FFmpeg and required extensions
RUN apk add --no-cache \
    ffmpeg \
    autoconf \
    g++ \
    make

# Install PHP extensions
RUN pecl install redis-6.0.2 \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

# Install pcntl extension for signal handling
RUN docker-php-ext-install pcntl

# Set working directory
WORKDIR /app

# Copy worker files
COPY worker/ /app/

# Make worker script executable
RUN chmod +x /app/worker.php

# Run worker
CMD ["php", "/app/worker.php"]
