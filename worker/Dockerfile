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

# Copy required includes for video processing
COPY includes/inc.php /app/includes/inc.php
COPY includes/connect.php /app/includes/connect.php
COPY includes/db.php /app/includes/db.php
COPY includes/functions.php /app/includes/functions.php
COPY includes/convertToMp4Format.php /app/includes/convertToMp4Format.php
COPY includes/convertVideoToBlurredReelsFormat.php /app/includes/convertVideoToBlurredReelsFormat.php
COPY includes/createVideoThumbnail.php /app/includes/createVideoThumbnail.php
COPY includes/object_storage.php /app/includes/object_storage.php
COPY includes/storage_config.php /app/includes/storage_config.php

# Copy AWS SDK and other dependencies if needed for object storage
COPY includes/vendor /app/includes/vendor

# Make worker script executable
RUN chmod +x /app/worker.php

# Run worker
CMD ["php", "/app/worker.php"]
