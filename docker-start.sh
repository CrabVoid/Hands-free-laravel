#!/bin/sh

set -eu

php artisan migrate --force
php artisan optimize

exec /usr/bin/supervisord -n -c /etc/supervisord.conf
