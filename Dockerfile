ARG BASE_IMAGE="bitnami/moodle"

FROM "${BASE_IMAGE}" AS base

# Patch to execute just crond in a dedicated Pod
COPY --chown=root:root --chmod=755 scripts/cron-run.sh /opt/bitnami/scripts/moodle/run.sh

## Copy Source Code
## --chown=1001:1
COPY --chown=daemon:root --chmod=755 src/www/ /opt/bitnami/moodle/

