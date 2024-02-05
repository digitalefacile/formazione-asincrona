#!/usr/bin/env bash

set -eo pipefail

DOC_ROOT="/var/www/html"

MOODLE_VER="$(php admin/cli/cfg.php --name=branch --no-eol || true)"

PRE_INSTALL_HOOK="/hooks/pre_install.sh"
POST_INSTALL_HOOK="/hooks/post_install.sh"
POST_DEPLOY_HOOK="/hooks/post_deploy.sh"

function run_deploy()
{
  if [ -f "${PRE_INSTALL_HOOK}" ]; then
    echo "[$(basename "${0}")] HOOKS: Running PRE_INSTALL_HOOK"
    chmod +x "${PRE_INSTALL_HOOK}"
    ${PRE_INSTALL_HOOK}
  fi

  case "${1}" in
    install)
      echo "[$(basename "${0}")] Running installer..."
      
      php admin/cli/install.php --chmod="2750" \
                                --lang="${MOODLE_LANG}" \
                                --wwwroot="${MOODLE_HOST:-"http://127.0.0.1:8080"}" \
                                --dataroot="${MOODLE_DATAROOT:-"/var/www/moodledata"}" \
                                --dbtype="${MOODLE_DATABASE_TYPE}" \
                                --dbhost="${MOODLE_DATABASE_HOST}" \
                                --dbname="${MOODLE_DATABASE_NAME}" \
                                --dbuser="${MOODLE_DATABASE_USER}" \
                                --dbpass="${MOODLE_DATABASE_PASSWORD}" \
                                ${MOODLE_DATABASE_PORT_NUMBER:+"--dbport=${MOODLE_DATABASE_PORT_NUMBER}"} \
                                ${MOODLE_DATABASE_PREFIX:+"--prefix=${MOODLE_DATABASE_PREFIX}"} \
                                --fullname="${MOODLE_SITE_NAME}" \
                                --shortname="${MOODLE_SITE_NAME}" \
                                --adminuser="${MOODLE_ADMIN_USERNAME}" \
                                --adminpass="${MOODLE_ADMIN_PASSWORD}" \
                                --adminemail="${MOODLE_ADMIN_EMAIL}" \
                                --non-interactive \
                                --allow-unstable \
                                --agree-license
      
      ;;
    upgrade)
      echo "[$(basename "${0}")] Running upgrade..."
      php admin/cli/alternative_component_cache.php --rebuild
      php admin/cli/upgrade.php --non-interactive --allow-unstable
      ;;
  esac
  
  if [ -f "${POST_INSTALL_HOOK}" ]; then
    echo "[$(basename "${0}")] HOOKS: Running POST_INSTALL_HOOK"
    chmod +x "${POST_INSTALL_HOOK}"
    ${POST_INSTALL_HOOK}
  fi

  if [ "$RUNTYPE" != "dev" ]; then
    php admin/cli/build_theme_css.php #--themes=boost

    php admin/cli/purge_caches.php
  fi

}

while ! mysqladmin ping -h"${MOODLE_DATABASE_HOST}" --silent; do
  echo "[$(basename "${0}")] Waiting on database connection.."
  sleep 2
done

START=$(date +%s)

case "${MOODLE_DATABASE_TYPE}" in
  mysqli|mariadb|auroramysql)
    DB_EMPTY="$(mysql -h"${MOODLE_DATABASE_HOST}" \
                      -u"${MOODLE_DATABASE_USER}" \
                      -p"${MOODLE_DATABASE_PASSWORD}" \
                      -D "${MOODLE_DATABASE_NAME}" -e "SHOW TABLES;")"
  ;;
  sqlsrv|oci|psql)
    DB_EMPTY="Not implemented"
  ;;
  *)
    DB_EMPTY="DB Type NOT supported"
  ;;
esac

. config.sh

[ "$RUNTYPE" != "dev" ] && php admin/cli/maintenance.php --enable || true

case "${1}" in
  install|upgrade|deploy)
    echo "[$(basename "${0}")] Running automated Moodle ${1}"
    run_deploy "${1}"
    ;;
  auto)

    CHECK_STATUS=$(php admin/cli/upgrade.php --non-interactive --lang=en --is-pending 2>&1 || true)

    if [[ ${CHECK_STATUS} == *"No upgrade needed for the installed version"* ]]; then
      echo "[$(basename "${0}")] Installation is up to date. Running automated Moodle deploy"
      run_deploy deploy
    elif [[ ${CHECK_STATUS} == *"An upgrade is pending"* ]]; then
      run_deploy upgrade
    else
      if [[ "${UNATTENDED}" == "true" && "${DB_EMPTY}" == "" ]]; then
        mv -f ./config.php ./config.preupgrade.php
        run_deploy install
        mv -f ./config.preupgrade.php ./config.php
      else
        run_deploy deploy
      fi
    fi
    ;;
  *)
    echo "[$(basename "${0}")] No recognized parameter. Valid parameters: install, upgrade, deploy, auto."
    echo "[$(basename "${0}")] Starting automated Moodle deploy..."
    run_deploy deploy
    ;;
esac

if [ -f "${POST_DEPLOY_HOOK}" ]; then
  echo "[$(basename "${0}")] HOOKS: Running POST_DEPLOY_HOOK"
  chmod +x "${POST_DEPLOY_HOOK}"
  ${POST_DEPLOY_HOOK}
fi

[ "$RUNTYPE" != "dev" ] && php admin/cli/maintenance.php --disable || true

END=$(date +%s)
echo "[$(basename "${0}")] Run completed in $((END - START)) seconds"
