# Aura Tac — Deployment (CloudPanel / Ubuntu)

Site root: `/home/s-plus-auratac/htdocs/auratac.s-plus.me`
Document Root in CloudPanel must point to **`.../auratac.s-plus.me/public`**.
Repo: https://github.com/ahmedmuawad/auratac-erp

> Run commands **one line at a time** (avoid pasting big blocks — the terminal mangles heredocs/parentheses).

## First-time deploy
```
cd /home/s-plus-auratac/htdocs/auratac.s-plus.me
git init
git remote add origin https://github.com/ahmedmuawad/auratac-erp.git
git fetch origin
git checkout -f main
composer install --no-dev --optimize-autoloader
cp .env.server .env
sed -i 's|^DB_PASSWORD=|DB_PASSWORD=YOUR_DB_PASSWORD|' .env
php artisan key:generate
php artisan migrate:fresh --force
php artisan db:seed --class=RoleAndPermissionSeeder --force
php artisan db:seed --class=InitialSettingsSeeder --force
php artisan app:make-admin
php artisan storage:link
php artisan vendor:publish --force --tag=livewire:assets
php artisan optimize:clear
chmod -R 775 storage bootstrap/cache
```

Login: `admin` / `ChangeMe@123` then OTP `123456` (test mode). Change the password after first login.

## Update (every release)
```
cd /home/s-plus-auratac/htdocs/auratac.s-plus.me
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan vendor:publish --force --tag=livewire:assets
php artisan optimize:clear
```

## Gotchas we already hit (and the fixes)
- **`MODIFY ... ENUM` syntax error on sqlite** → the `.env` was missing so Laravel used the default sqlite. Always create `.env` (mysql) before migrating. The custom migrations are now guarded to run on mysql only.
- **`Access denied for user` 1045** → the MySQL database/user didn't exist or the password in `.env` didn't match. Create the DB + user in CloudPanel and set `DB_PASSWORD` in `.env`.
- **Login just refreshes / no OTP** → `livewire/livewire.js` returned 404 because Nginx treats `.js` URLs as static files. Fix with `php artisan vendor:publish --force --tag=livewire:assets` (serves Livewire JS as real files). Alternative: add to the Nginx vhost:
  ```nginx
  location ^~ /livewire { try_files $uri /index.php?$query_string; }
  ```
- **Session not persisting (419)** → keep `SESSION_SECURE_COOKIE=false` until a valid HTTPS certificate is installed; then you may set it back to `true`.
- **Logo still old** → replace `public/logo.png` via File Manager, or upload from **Settings → Branding** (print cards use the uploaded logo too).
- Node/npm are **not** needed on the server — compiled assets ship in `public/build`.
