#!/bin/bash
SA=/home/kayit.kirklareli.bel.tr/public_html/sanat-atolye
cd "$SA"

# Deduplicate APP_URL lines - keep one
awk '!seen[$0]++' .env > .env.tmp && mv .env.tmp .env

# Use file drivers so pages work before MySQL is configured
for KEY in SESSION_DRIVER CACHE_STORE QUEUE_CONNECTION; do
  case $KEY in
    SESSION_DRIVER) VAL=file ;;
    CACHE_STORE) VAL=file ;;
    QUEUE_CONNECTION) VAL=sync ;;
  esac
  if grep -q "^${KEY}=" .env; then
    sed -i "s|^${KEY}=.*|${KEY}=${VAL}|" .env
  else
    echo "${KEY}=${VAL}" >> .env
  fi
done

# Temporary sqlite so artisan/migrate won't explode if something still hits DB
touch database/database.sqlite
chown kayit6216:kayit6216 database/database.sqlite
chmod 664 database/database.sqlite

# Ensure DB_CONNECTION stays sqlite until user sets MySQL
grep -q '^DB_CONNECTION=' .env && sed -i 's|^DB_CONNECTION=.*|DB_CONNECTION=sqlite|' .env || echo 'DB_CONNECTION=sqlite' >> .env

/usr/local/lsws/lsphp83/bin/php artisan migrate --force || true
/usr/local/lsws/lsphp83/bin/php artisan optimize:clear || true

chown -R kayit6216:kayit6216 storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache

echo "==== ENV CHECK ===="
grep -E '^(APP_URL|PORTAL|SESSION|CACHE|QUEUE|DB_)' .env | sort -u
echo DONE
