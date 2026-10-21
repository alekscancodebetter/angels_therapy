#!/bin/bash
set -Eeuo pipefail

# Run from a prepared private release. The database/user are created in cPanel.
: "${PHP_BIN:?Set PHP_BIN to the hosting PHP CLI binary}"
cd -- "$(dirname -- "${BASH_SOURCE[0]}")"
[[ -f vendor/autoload.php && -f .env ]] || { echo 'Install Composer dependencies and configure .env first.' >&2; exit 1; }

"$PHP_BIN" artisan config:clear --no-interaction
"$PHP_BIN" -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (config("database.default") !== "mysql" || config("app.debug") || config("app.url") !== "https://angeltouchtherapy.co.uk") {
    fwrite(STDERR, "Expected the production MySQL configuration, HTTPS APP_URL and APP_DEBUG=false.\n");
    exit(1);
}
foreach (["database", "username", "password"] as $field) {
    $value = (string) config("database.connections.mysql.".$field);
    if ($value === "" || str_contains($value, "CHANGE_ME")) {
        fwrite(STDERR, "Fill in the MySQL credentials in the private .env first.\n");
        exit(1);
    }
}
Illuminate\Support\Facades\DB::connection()->getPdo();
'
# Safe on repeat runs: only outstanding migrations are applied; no seed/fresh/reset.
"$PHP_BIN" artisan migrate --force --no-interaction
"$PHP_BIN" artisan migrate:status --no-interaction
