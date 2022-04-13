FROM php:8.0-cli

RUN apt-get update && apt-get install -y \
        cron \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libonig-dev \
        libmagickwand-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip exif opcache iconv mbstring \
#    && pecl install xdebug && docker-php-ext-enable xdebug \
#    && pecl install mcrypt-1.0.4 && docker-php-ext-enable mcrypt \
    && pecl install imagick-3.7.0 && docker-php-ext-enable imagick

RUN echo "* * * * * /usr/local/bin/php -c /usr/local/etc/php /opt/una/periodic/cron.php 2>&1 | sed -e \"s/\(.*\)/[\`date\`] \1/\" >>/var/log/unacron.log" > /tmp/crontab && crontab /tmp/crontab && rm -f /tmp/crontab

CMD ["cron", "-f"]
