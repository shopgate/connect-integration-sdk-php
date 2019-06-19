#!/usr/bin/env bash
# react on SIGINT properly
trap : TERM INT

ping -c 1 host.docker.internal
if [[ ! $? = 0 ]] && [[ -z $(grep host.docker.internal /etc/hosts) ]]; then
  echo `/sbin/ip route|awk '/default/ { print $3 }'` host.docker.internal >> /etc/hosts
fi

XDEBUG_CONNECT_TO_PORT=${XDEBUG_CONNECT_TO_PORT:-9000}
sed -i "s#\%XDEBUG_PORT\%#${XDEBUG_CONNECT_TO_PORT}#" /usr/local/etc/php/conf.d/xdebug.ini

# keep container running
tail -f /dev/null & wait