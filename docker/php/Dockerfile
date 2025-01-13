FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libpq-dev \
    git \
    unzip \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Установка Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Копирование конфигурационных файлов
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Настройка рабочей директории
WORKDIR /var/www/project

# Копирование файлов проекта
COPY . /var/www/project

# Настройка переменных окружения
ENV TIME_ZONE Europe/Moscow
ENV PHP_ENABLE_XDEBUG 1
ENV PHP_IDE_CONFIG "serverName=test"

# Открытие порта для PHP-FPM
EXPOSE 9000

# Запуск PHP-FPM
CMD ["php-fpm"]
