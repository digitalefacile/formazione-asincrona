#!/usr/bin/env bash

set -eo pipefail

for env_var in \
  "MOODLE_DATABASE_PASSWORD" \
  "MOODLE_ADMIN_PASSWORD"
do
    file_env_var="${env_var}_FILE"
    if [[ -n "${!file_env_var:-}" ]]; then
        if [[ -r "${!file_env_var:-}" ]]; then
            export "${env_var}=$(< "${!file_env_var}")"
            unset "${file_env_var}"
        else
            echo "[$(basename "${0}")] Skipping export of '${env_var}'. '${!file_env_var:-}' is not readable."
        fi
    fi
done

if [[ -n "$*" && "${1}" ]]; then
  echo "[$(basename "${0}")] Executing override command"
  exec "$@"
  exit
fi

while ! mysqladmin ping -h"${MOODLE_DATABASE_HOST}" --silent; do
  echo "[$(basename "${0}")] Waiting on database connection.."
  sleep 2
done

START=$(date +%s)

if [ "${RUNTYPE}" == "dev" ]; then
  cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

  echo 'display_errors = On' >> /usr/local/etc/php/conf.d/10_env-options.ini
else
  cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

  echo 'display_errors = Off' >> /usr/local/etc/php/conf.d/10_env-options.ini
fi

if [ "$(whoami)" == "root" ]; then
  su -m -s /bin/bash www-data -c "config.sh"
  else
  . config.sh
fi

END=$(date +%s)
echo "[$(basename "${0}")] Startup preparation finished in $((END - START)) seconds"

if [ "${CRON}" == "true" ]; then
  echo "[$(basename "${0}")] CRON: Starting crontab"
  [ ! -s /etc/environment ] && env >> /etc/environment

  exec cron -f
else
  echo "[$(basename "${0}")] APP: Starting php-fpm"
  exec php-fpm
fi
