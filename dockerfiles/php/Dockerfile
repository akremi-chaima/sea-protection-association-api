FROM php:8.2-fpm-alpine
# Install deps
RUN apk add --no-cache $PHPIZE_DEPS tzdata autoconf c-client cmake curl git g++ mysql-client openssh-client python3 linux-headers \
    cyrus-sasl-dev icu-dev icu-libs imap imap-dev libmcrypt libmcrypt-dev libmemcached libmemcached-dev \
    libxml2 libxml2-dev postgresql-dev postgresql-libs rabbitmq-c rabbitmq-c-dev sqlite-dev sqlite-libs zlib zlib-dev libzip-dev

RUN pecl install memcached redis xdebug
RUN docker-php-ext-enable memcached redis xdebug
RUN docker-php-ext-install imap intl opcache pcntl pdo pdo_mysql pdo_pgsql soap zip

# install composer
RUN apk info | grep "\-dev" | xargs apk del autoconf dpkg file g++ gcc pkgconf re2c python3
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN printf '[PHP]\date.timezone = "Europe/Paris"\n' > /usr/local/etc/php/conf.d/tzone.ini


# On ajoute les sources
ADD ./resources /app
WORKDIR /app

# On crée en projet proprement avec l'utilisateur qui va bien
RUN chown -R www-data. .
USER www-data

USER root
