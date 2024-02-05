#!/usr/bin/env bash

if [ "${RUNTYPE}" = "dev" ]; then

  sed -i 's/\(fastcgi_param DEVELOPER_MODE\).*/\1 true/' /etc/nginx/conf.d/myapp.conf
else

  sed -i 's/\(fastcgi_param DEVELOPER_MODE\).*/\1 false/' /etc/nginx/conf.d/myapp.conf
fi

exec /docker-entrypoint.sh nginx -g "daemon off;"
