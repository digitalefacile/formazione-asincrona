ARG BASE_IMAGE="bitnami/moodle"

FROM "${BASE_IMAGE}" AS base

ARG PHPREDIS_VERSION="6.0.2"

RUN apt-get update && \
    apt-get install -y autoconf build-essential wget && \
    wget "https://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz" && \
    tar xzf "redis-${PHPREDIS_VERSION}.tgz" && \
    cd "redis-${PHPREDIS_VERSION}" && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    cd .. && rm -rf "redis-${PHPREDIS_VERSION}/" "redis-${PHPREDIS_VERSION}.tgz" && \
    apt purge wget build-essential autoconf ; apt autoremove --purge -y ; apt autoclean -y ; apt clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/apt/archives/* && \
    echo "extension = redis" >> /opt/bitnami/php/lib/php.ini

# Fix: start just crond in a dedicated Pod
COPY --chown=root:root --chmod=755 scripts/cron-run.sh /opt/bitnami/scripts/moodle/run.sh

# Fix: add custom configs to config.php
COPY --chown=root:root --chmod=755 scripts/add-custom-configs.sh /docker-entrypoint-init.d/

## Copy Source Code
## --chown=daemon:root
COPY --chown=1001:daemon --chmod=755 src/www/ /opt/bitnami/moodle/

# Custom Configs
COPY --chown=1001:daemon --chmod=750 /etc/config-custom.php /opt/bitnami/moodle/
