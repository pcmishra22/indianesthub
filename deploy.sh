#!/usr/bin/env bash
#
# deploy.sh — push local Laravel changes to indianesthub.com (Hostinger shared hosting)
#
# USAGE:
#   ./deploy.sh
#
# WHAT THIS DOES:
#   1. Syncs changed files (app/, routes/, resources/, config/, database/,
#      composer.json, composer.lock) to the server over SSH via rsync —
#      only sends what actually changed, so it's fast after the first run.
#   2. SSHes in and runs: optimize:clear, composer install, migrate --force,
#      config/route/view cache rebuild, queue:restart.
#
# SAFE BY DESIGN:
#   - .env is never touched — your live secrets/config stay put.
#   - storage/, public/storage, vendor/, node_modules/ are never synced.
#   - Only files inside the explicitly included folders are ever sent.
#   - `migrate --force` only runs migrations that haven't run on the server
#     yet — safe to run on every single deploy, forever.
#
# NOTE: no new seeders exist as of this version — if one is added later,
# run it individually and explicitly:
#   ssh -p 65002 u605731613@145.79.213.155 \
#     "cd /home/u605731613/domains/indianesthub.com/public_html/property_dealer && php artisan db:seed --class=YourSeederName"

set -e  # stop immediately if anything fails, instead of continuing with a half-broken deploy

SSH_PORT="65002"
SSH_TARGET="u605731613@145.79.213.155"
REMOTE_PATH="/home/u605731613/domains/indianesthub.com/public_html/property_dealer"
LOCAL_PATH="/home/prakash/indianesthub/"

echo "▶ Step 1/2: Syncing files to ${SSH_TARGET}:${REMOTE_PATH} ..."

rsync -avz --progress \
  -e "ssh -p ${SSH_PORT}" \
  --include="app/" \
  --include="app/***" \
  --include="routes/" \
  --include="routes/***" \
  --include="resources/" \
  --include="resources/***" \
  --include="config/" \
  --include="config/***" \
  --include="database/" \
  --include="database/***" \
  --include="stock/" \
  --include="stock/***" \
  --include="composer.json" \
  --include="composer.lock" \
  --exclude="*" \
  "${LOCAL_PATH}" \
  "${SSH_TARGET}:${REMOTE_PATH}/"

echo "✔ Files synced."
echo ""
echo "▶ Step 2/2: Installing dependencies, migrating, and rebuilding caches on the server ..."

ssh -p "${SSH_PORT}" "${SSH_TARGET}" <<EOF
set -e
cd "${REMOTE_PATH}"
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan optimize:clear
#php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
EOF

echo ""
echo "✅ Deploy complete."
