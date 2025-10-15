ARG BASE_IMAGE="php:fpm"

FROM "${BASE_IMAGE}" AS base

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    set -eux ; \
    ln -fs /usr/share/zoneinfo/Europe/Rome /etc/localtime ; \
    apt-get update && apt-get upgrade -y && \
    apt-get install -y \
        procps \
        curl \
        jq \
        cron \
        git-core \
        locales \
        default-mysql-client \
        ghostscript \
        libaio1 \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libwebp-dev \
        libavif-dev \
        libmcrypt-dev \
        libcurl4-openssl-dev \
        libxml2-dev \
        libgss3 \
        libxslt-dev \
        libicu-dev \
        libzip-dev \
        libzstd-dev \
        sassc \
        unzip \
        zip \
        graphviz \
        aspell \
    && \
    grep '\(en\|it\)_.*UTF-8' /usr/share/i18n/SUPPORTED > /etc/locale.gen && \
    locale-gen && \
    apt-get autoremove --purge -y

FROM base AS build

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    set -eux ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-avif && \
    docker-php-ext-install -j$(nproc) \
        exif \
        gd \
        intl \
        pdo_mysql \
        mysqli \
        soap \
        sockets \
        zip \
        xsl \
    && \
    pecl install \
        apcu \
        igbinary \
        lzf \
        zstd \
    && \
    pecl install -D 'enable-redis-igbinary="yes" enable-redis-lzf="yes" enable-redis-zstd="yes"' redis && \
    docker-php-ext-enable \
        apcu \
        redis \
        igbinary \
        lzf \
        zstd \
    && pecl clear-cache

ENV PATH="${PATH}:/usr/local/bin/77b5d962553660704e24fcd0046db2a7"

COPY --link --chmod=755 scripts/ /usr/local/bin/77b5d962553660704e24fcd0046db2a7/

COPY --link etc/php-custom.ini /usr/local/etc/php/conf.d/00_myapp.ini
COPY --link etc/fpm-zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    mkdir -p /run/php /var/www/localmoodledata/cache /var/www/moodledata && \
    chown -R www-data: /run/php /var/www/localmoodledata /var/www/moodledata

COPY --link etc/cron /etc/cron.d/myapp

ENTRYPOINT [ "/usr/local/bin/77b5d962553660704e24fcd0046db2a7/entrypoint.sh" ]
WORKDIR /var/www/html

FROM build AS final

COPY --link hooks/ /hooks/
COPY --link --chown=www-data:www-data etc/config.php /var/www/html/

COPY --link --chown=www-data:www-data src/ /var/www/html/

# FROM final AS download

# ARG MOODLE_VERSION=401
# RUN <<EOF
#     echo "Installing moodle branch ${MOODLE_VERSION}"
#     curl -s -N -L  https://packaging.moodle.org/stable${MOODLE_VERSION}/moodle-latest-${MOODLE_VERSION}.tgz | tar --strip-components=1 -xvz
#     chown -R www-data: ./
#     ls -lah
#     rm -rf /var/www/html/index.html
# EOF
