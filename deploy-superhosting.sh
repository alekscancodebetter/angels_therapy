#!/bin/bash
set -Eeuo pipefail
umask 022

REPOSITORY=/home/nikkiebl/repositories/angels_therapy_production
PRIVATE_ROOT=/home/nikkiebl/angels-therapy-production
WEB_ROOT=/home/nikkiebl/angeltouchtherapy.co.uk
SHARED="$PRIVATE_ROOT/shared"
BACKUP_ROOT="$PRIVATE_ROOT/backups"
INITIALIZED_MARKER="$PRIVATE_ROOT/production-initialized"
DEPLOY_CONFIG=/home/nikkiebl/.angels-therapy-production-deploy.conf

fail() { echo "Deploy failed: $*" >&2; exit 1; }
[[ "$(id -un)" == nikkiebl ]] || fail 'Run as cPanel user nikkiebl.'
[[ "$PRIVATE_ROOT" == /home/nikkiebl/angels-therapy-production ]] || fail 'Unexpected private root.'
[[ "$WEB_ROOT" == /home/nikkiebl/angeltouchtherapy.co.uk ]] || fail 'Unexpected production web root.'
[[ "$BACKUP_ROOT" == "$PRIVATE_ROOT/backups" ]] || fail 'Unexpected backup root.'
[[ -d "$REPOSITORY/.git" ]] || fail 'Repository path does not exist.'
[[ ! -L "$PRIVATE_ROOT" && ! -L "$WEB_ROOT" ]] || fail 'Private/web root must be real directories, not symlinks.'
cd -- "$REPOSITORY"
[[ "$(git branch --show-current)" == production ]] || fail 'Check out production first.'
[[ -z "$(git status --porcelain)" ]] || fail 'The hosting repository must have a clean working tree.'
for tool in flock rsync tar; do command -v "$tool" >/dev/null || fail "Missing tool: $tool"; done

mkdir -p "$PRIVATE_ROOT/releases" "$SHARED" "$BACKUP_ROOT"
[[ ! -L "$SHARED" && ! -L "$BACKUP_ROOT" ]] || fail 'Shared/backup paths must be real directories, not symlinks.'
chmod 700 "$PRIVATE_ROOT" "$SHARED" "$BACKUP_ROOT"
exec 9>"$PRIVATE_ROOT/deploy.lock"
flock -n 9 || fail 'Another deployment is running.'

if [[ ! -f "$SHARED/.env" ]]; then
    cp .env.superhosting.example "$SHARED/.env"
    chmod 600 "$SHARED/.env"
    echo "Created $SHARED/.env. Fill in your cPanel database credentials, then deploy again."
    exit 1
fi
if grep -q CHANGE_ME "$SHARED/.env"; then fail "Configure $SHARED/.env first."; fi

# This optional private file contains only PHP_BIN and COMPOSER_BIN assignments.
if [[ -f "$DEPLOY_CONFIG" ]]; then source "$DEPLOY_CONFIG"; fi
PHP_BIN=${PHP_BIN:-/opt/cpanel/ea-php85/root/usr/bin/php}
COMPOSER_BIN=${COMPOSER_BIN:-/opt/cpanel/composer/bin/composer}
[[ -x "$PHP_BIN" ]] || fail "PHP CLI not found: $PHP_BIN (configure $DEPLOY_CONFIG)."
[[ -f "$COMPOSER_BIN" ]] || fail "Composer not found: $COMPOSER_BIN (configure $DEPLOY_CONFIG)."
export PHP_BIN
export PATH="$(dirname "$PHP_BIN"):$PATH"
"$PHP_BIN" -r 'exit(PHP_VERSION_ID >= 80500 ? 0 : 1);' || fail 'This deployment targets PHP 8.5 or newer.'

REVISION=$(git rev-parse HEAD)
RELEASE="$PRIVATE_ROOT/releases/$(date -u +%Y%m%dT%H%M%SZ)-${REVISION:0:12}"
mkdir "$RELEASE"
git archive "$REVISION" | tar -x -C "$RELEASE"
mkdir -p "$SHARED/storage/app/public" "$SHARED/storage/app/private" \
    "$SHARED/storage/framework/cache/data" "$SHARED/storage/framework/sessions" \
    "$SHARED/storage/framework/views" "$SHARED/storage/logs"
# The archive contains only tracked storage placeholders; keep actual storage shared.
mv "$RELEASE/storage" "$RELEASE/storage-template"
ln -s "$SHARED/storage" "$RELEASE/storage"
ln -s "$SHARED/.env" "$RELEASE/.env"
cd -- "$RELEASE"
chmod -R u+rwX bootstrap/cache
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --optimize-autoloader
"$PHP_BIN" "$COMPOSER_BIN" check-platform-reqs --no-dev
if ! grep -Eq '^APP_KEY=.+$' "$SHARED/.env"; then
    "$PHP_BIN" artisan key:generate --force --no-interaction
fi
chmod 600 "$SHARED/.env"

trap 'echo "Deploy interrupted; fix the error and run deploy again. Release: $RELEASE" >&2' ERR
/bin/bash "$RELEASE/install-database.sh"
"$PHP_BIN" artisan config:cache --no-interaction
"$PHP_BIN" artisan route:cache --no-interaction
"$PHP_BIN" artisan view:cache --no-interaction
"$PHP_BIN" artisan event:cache --no-interaction

# On the first production deployment, preserve the complete WordPress document
# root as a recoverable directory. Its database is separate and is not changed.
if [[ ! -f "$INITIALIZED_MARKER" ]]; then
    BACKUP_PATH="$BACKUP_ROOT/wordpress-webroot-$(date -u +%Y%m%dT%H%M%SZ)"
    if [[ -d "$WEB_ROOT" ]]; then
        mv -- "$WEB_ROOT" "$BACKUP_PATH"
        printf '%s\n' "$BACKUP_PATH" > "$PRIVATE_ROOT/previous-webroot"
    fi
    mkdir -p "$WEB_ROOT"
    chmod 755 "$WEB_ROOT"
    if [[ -d "$BACKUP_PATH/.well-known" ]]; then
        cp -a -- "$BACKUP_PATH/.well-known" "$WEB_ROOT/.well-known"
    fi
    touch "$INITIALIZED_MARKER"
else
    mkdir -p "$WEB_ROOT"
fi
touch "$WEB_ROOT/.deploying"

# Preserve hosting-managed .htaccess sections (PHP handler, Directory Privacy, etc.).
if [[ -f "$WEB_ROOT/.htaccess" ]]; then
    cp "$WEB_ROOT/.htaccess" "$RELEASE/previous-public.htaccess"
    awk '/^# BEGIN ANGEL TOUCH DEPLOY$/{managed=1; next} /^# END ANGEL TOUCH DEPLOY$/{managed=0; next} !managed' \
        "$WEB_ROOT/.htaccess" > "$RELEASE/public.htaccess"
else
    : > "$RELEASE/public.htaccess"
fi
{
    printf '\n# BEGIN ANGEL TOUCH DEPLOY\nDirectoryIndex index.php\nOptions -Indexes\n'
    cat public/.htaccess
    printf '<FilesMatch "^\\.">\n    Require all denied\n</FilesMatch>\n# END ANGEL TOUCH DEPLOY\n'
} >> "$RELEASE/public.htaccess"

# Only Laravel public assets reach the web root. Hosting-managed ACME files and
# runtime logs are retained; stale application assets are removed.
rsync -rlt --delete --exclude='/index.php' --exclude='/.htaccess' --exclude='/.well-known' \
    --exclude='/error_log' --exclude='/.deploying' --exclude='/hot' --exclude='/storage' \
    public/ "$WEB_ROOT/"
install -m 644 "$RELEASE/public.htaccess" "$WEB_ROOT/.htaccess"
install -m 644 "$RELEASE/superhosting-index.php" "$WEB_ROOT/index.php"
ln -s "$RELEASE" "$PRIVATE_ROOT/current-next-$$"
mv -Tf "$PRIVATE_ROOT/current-next-$$" "$PRIVATE_ROOT/current"
printf '%s\n' "$REVISION" > "$PRIVATE_ROOT/deployed-commit"
rm -f -- "$WEB_ROOT/.deploying"
trap - ERR
echo "Deployed $REVISION to https://angeltouchtherapy.co.uk"
echo 'Verify /up, the home page and /admin/login in your browser. Old releases are retained for recovery.'
