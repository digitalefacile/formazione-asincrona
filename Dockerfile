ARG BASE_IMAGE="bitnami/moodle"

FROM "${BASE_IMAGE}" AS base

# Fix: start just crond in a dedicated Pod
COPY --chown=root:root --chmod=755 scripts/cron-run.sh /opt/bitnami/scripts/moodle/run.sh

# Fix: add custom configs to config.php
COPY --chown=root:root --chmod=755 scripts/add-custom-configs.sh /docker-entrypoint-init.d/

## Copy Source Code
## --chown=daemon:root
COPY --chown=1001:daemon --chmod=755 src/www/ /opt/bitnami/moodle/

# Custom Configs
COPY --chown=1001:daemon --chmod=750 /etc/config-custom.php /opt/bitnami/moodle/
