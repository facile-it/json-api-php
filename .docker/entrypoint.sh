#!/bin/sh
set -e

if test -n "$TZ"; then
    echo "date.timezone = \"$TZ\"" > /usr/local/etc/php/conf.d/date.ini
fi

docker-php-entrypoint "$@"
