#!/bin/bash
set -Eeuo pipefail

APPLICATION_ROOT=/home/nikkiebl/angels-therapy-production/current
PHP_BIN=${PHP_BIN:-/opt/cpanel/ea-php85/root/usr/bin/php}

[[ -x "$PHP_BIN" ]] || { echo "PHP CLI not found: $PHP_BIN" >&2; exit 1; }
[[ -f "$APPLICATION_ROOT/artisan" ]] || { echo "Production release is not available." >&2; exit 1; }

cd -- "$APPLICATION_ROOT"
"$PHP_BIN" artisan queue:work database --queue=default --stop-when-empty --tries=3 --timeout=60 --no-interaction
