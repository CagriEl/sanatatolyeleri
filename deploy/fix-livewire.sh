#!/bin/bash
set -e
SA=/home/kayit.kirklareli.bel.tr/public_html/sanat-atolye
cd "$SA"

# Session cookie scoped to app path
grep -q '^SESSION_PATH=' .env && sed -i 's|^SESSION_PATH=.*|SESSION_PATH=/sanat-atolye|' .env || echo 'SESSION_PATH=/sanat-atolye' >> .env
grep -q '^SESSION_COOKIE=' .env && sed -i 's|^SESSION_COOKIE=.*|SESSION_COOKIE=sanat_atolyeleri_session|' .env || echo 'SESSION_COOKIE=sanat_atolyeleri_session' >> .env

/usr/local/lsws/lsphp83/bin/php artisan optimize:clear
chown -R kayit6216:kayit6216 app bootstrap storage

echo DONE
grep -E '^(APP_URL|SESSION_)' .env
