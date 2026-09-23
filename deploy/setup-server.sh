#!/bin/bash
set -e
WEB=/home/kayit.kirklareli.bel.tr/public_html
PUB=$WEB/public
SA=$WEB/sanat-atolye
K39=$WEB/39kart

mkdir -p "$PUB/images"

cp /tmp/portal-index.html "$PUB/index.html"
cp "$SA/public/images/logo.png" "$PUB/images/logo.png" 2>/dev/null || true

rm -rf "$PUB/sanat-atolye" "$PUB/39kart"
ln -sfn "$SA/public" "$PUB/sanat-atolye"
ln -sfn "$K39/public" "$PUB/39kart"

rm -f "$WEB/portal.blade.php"

cat > "$SA/public/.htaccess" <<'HT'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteBase /sanat-atolye/

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HT

if [ -f "$K39/public/.htaccess" ]; then
  if ! grep -q "RewriteBase" "$K39/public/.htaccess"; then
    sed -i 's|RewriteEngine On|RewriteEngine On\n    RewriteBase /39kart/|' "$K39/public/.htaccess"
  fi
else
  cat > "$K39/public/.htaccess" <<'HT39'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>
    RewriteEngine On
    RewriteBase /39kart/
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HT39
fi

if [ ! -f "$SA/.env" ]; then
  cp "$SA/.env.example" "$SA/.env"
  KEY=$(php -r 'echo "base64:".base64_encode(random_bytes(32));')
  sed -i "s|^APP_KEY=.*|APP_KEY=$KEY|" "$SA/.env"
fi

cd "$SA"
grep -q '^APP_URL=' .env && sed -i 's|^APP_URL=.*|APP_URL=https://kayit.kirklareli.bel.tr/sanat-atolye|' .env || echo 'APP_URL=https://kayit.kirklareli.bel.tr/sanat-atolye' >> .env
grep -q '^PORTAL_URL=' .env && sed -i 's|^PORTAL_URL=.*|PORTAL_URL=https://kayit.kirklareli.bel.tr/|' .env || echo 'PORTAL_URL=https://kayit.kirklareli.bel.tr/' >> .env
grep -q '^KART39_URL=' .env && sed -i 's|^KART39_URL=.*|KART39_URL=/39kart/|' .env || echo 'KART39_URL=/39kart/' >> .env
grep -q '^APP_ENV=' .env && sed -i 's|^APP_ENV=.*|APP_ENV=production|' .env || echo 'APP_ENV=production' >> .env
grep -q '^APP_DEBUG=' .env && sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' .env || echo 'APP_DEBUG=false' >> .env

# Ensure APP_KEY not empty
if grep -q '^APP_KEY=$' .env || ! grep -q '^APP_KEY=base64:' .env; then
  KEY=$(php -r 'echo "base64:".base64_encode(random_bytes(32));')
  sed -i "s|^APP_KEY=.*|APP_KEY=$KEY|" .env
fi

if [ -f "$K39/.env" ]; then
  sed -i 's|^APP_URL=.*|APP_URL=https://kayit.kirklareli.bel.tr/39kart|' "$K39/.env"
fi

chown -R kayit6216:kayit6216 "$WEB"
chmod -R 775 "$SA/storage" "$SA/bootstrap/cache" 2>/dev/null || true
chmod -R 775 "$K39/storage" "$K39/bootstrap/cache" 2>/dev/null || true

cd "$SA"
php artisan optimize:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

if [ -f "$K39/artisan" ]; then
  cd "$K39"
  php artisan optimize:clear || true
fi

/usr/local/lsws/bin/lswsctrl restart || true

echo "==== RESULT ===="
ls -la "$PUB"
echo "---"
ls -la "$PUB/sanat-atolye" | head -8
echo "---"
ls -la "$PUB/39kart" | head -8
echo "DONE"
