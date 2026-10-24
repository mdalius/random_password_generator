#!/bin/sh
set -eu

nginx -t
nginx
exec docker-php-entrypoint "$@"
