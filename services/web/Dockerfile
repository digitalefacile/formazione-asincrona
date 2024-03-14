ARG BASE_IMAGE="nginx"

FROM "${BASE_IMAGE}" AS base

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    set -eux ; \
    ln -fs /usr/share/zoneinfo/Europe/Rome /etc/localtime ; \
    apt-get update && apt-get upgrade -y && \
    apt-get install -y \
        curl \
        jq \
    && \
    apt-get autoremove --purge -y

FROM base

ENV PATH="${PATH}:/usr/local/bin/77b5d962553660704e24fcd0046db2a7"

RUN sed -i 's/\(user\s\+\)nginx\;/\1www-data\;/' /etc/nginx/nginx.conf ; \
    mkdir -p /run/php /var/www/localmoodledata /var/www/moodledata /var/www/html && \
    chown www-data: /run/php /var/www/localmoodledata /var/www/moodledata /var/www/html

COPY --link etc/nginx.web.conf /etc/nginx/conf.d/default.conf

COPY --from=app_src --link --chown=www-data:www-data ./ /var/www/html

# ENTRYPOINT [ "/usr/local/bin/77b5d962553660704e24fcd0046db2a7/entrypoint.sh" ]
