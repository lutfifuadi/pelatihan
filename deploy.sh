#!/bin/bash

# ============================================================
# Script Deploy - Aplikasi Pelatihan
# Server: VPS / aaPanel
# Jalankan setiap ada update kode:
#   bash deploy.sh
# ============================================================

APP_PATH="$(cd "$(dirname "$0")" && pwd)"
WEB_USER="www"

echo "=========================================="
echo "  Deploy Aplikasi Pelatihan - VPS"
echo "=========================================="
echo "  Path terdeteksi: $APP_PATH"
echo "=========================================="

# Pastikan dijalankan dari direktori aplikasi
cd "$APP_PATH" || { echo "[ERROR] Path tidak ditemukan: $APP_PATH"; exit 1; }

# Auto-detect GitHub owner & repo dari git remote
GITHUB_OWNER=""
GITHUB_REPO=""

if command -v git &> /dev/null && git rev-parse --git-dir &> /dev/null 2>&1; then
    REMOTE_URL=$(git remote get-url origin 2>/dev/null)
    if [ -n "$REMOTE_URL" ]; then
        OWNER_HTTPS=$(echo "$REMOTE_URL" | sed -n 's|https://github.com/\([^/]*\)/\(.*\)\.git|\1|p')
        REPO_HTTPS=$(echo "$REMOTE_URL" | sed -n 's|https://github.com/\([^/]*\)/\(.*\)\.git|\2|p')
        OWNER_SSH=$(echo "$REMOTE_URL" | sed -n 's|git@github.com:\([^/]*\)/\(.*\)\.git|\1|p')
        REPO_SSH=$(echo "$REMOTE_URL" | sed -n 's|git@github.com:\([^/]*\)/\(.*\)\.git|\2|p')
        GITHUB_OWNER="${OWNER_HTTPS:-$OWNER_SSH}"
        GITHUB_REPO="${REPO_HTTPS:-$REPO_SSH}"
        echo "[INFO] Git remote terdeteksi: $GITHUB_OWNER/$GITHUB_REPO"
    fi
fi

if [ -z "$GITHUB_OWNER" ] || [ -z "$GITHUB_REPO" ]; then
    GITHUB_OWNER="lutfifuadi"
    GITHUB_REPO="pelatihan"
    echo "[INFO] Git remote tidak terdeteksi. Menggunakan default: $GITHUB_OWNER/$GITHUB_REPO"
fi

# Muat variabel dari .env
if [ -f ".env" ]; then
    set -a
    [ -f .env ] && . ./.env
    set +a
fi

# ----------------------------------------------------------
# 1. Pull kode terbaru dari Git
# ----------------------------------------------------------
echo ""
echo "[1/11] Pull kode terbaru dari Git..."

if [ -n "$GITHUB_TOKEN" ]; then
    echo "[INFO] Menggunakan GITHUB_TOKEN untuk autentikasi Git..."
    REMOTE_URL=$(git remote get-url origin)
    if [[ $REMOTE_URL == https://github.com* ]]; then
        NEW_URL="https://$GITHUB_TOKEN@${REMOTE_URL#https://}"
        git remote set-url origin "$NEW_URL"
    fi
fi

git pull origin main
if [ $? -ne 0 ]; then
    echo "[ERROR] Git pull gagal. Periksa koneksi, token, atau konflik."
    exit 1
fi

# ----------------------------------------------------------
# 2. Install / update Composer dependencies
# ----------------------------------------------------------
echo ""
echo "[2/11] Install Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# ----------------------------------------------------------
# 3. Build frontend assets — fallback ke GitHub Release
# ----------------------------------------------------------
echo ""
echo "[3/11] Build frontend assets..."

BUILD_SUCCESS=false
if command -v node &> /dev/null && command -v npm &> /dev/null; then
    echo "[INFO] Node.js ditemukan, menjalankan npm ci && npm run build..."
    npm ci --no-audit --no-fund 2>/dev/null && npm run build 2>/dev/null
    if [ $? -eq 0 ] && [ -d "public/build" ] && [ "$(ls -A public/build 2>/dev/null)" ]; then
        BUILD_SUCCESS=true
        echo "[OK] Frontend assets berhasil dibuild dari source."
    else
        echo "[WARN] npm build gagal. Mencoba download dari GitHub Release..."
    fi
else
    echo "[WARN] Node.js tidak ditemukan. Mencoba download dari GitHub Release..."
fi

# Fallback: download public/build dari GitHub Release
if [ "$BUILD_SUCCESS" = false ]; then
    echo "[INFO] Mencoba download public/build dari GitHub Release..."

    AUTH_HEADER=""
    WGET_HEADER=""
    if [ -n "$GITHUB_TOKEN" ]; then
        AUTH_HEADER="-H \"Authorization: token $GITHUB_TOKEN\""
        WGET_HEADER="--header=\"Authorization: token $GITHUB_TOKEN\""
        echo "[INFO] Menggunakan GITHUB_TOKEN untuk autentikasi API."
    fi

    LATEST_URL=$(curl -s $AUTH_HEADER "https://api.github.com/repos/$GITHUB_OWNER/$GITHUB_REPO/releases/latest" \
        | grep "browser_download_url" \
        | grep "aplikasi-pelatihan-build.zip" \
        | cut -d '"' -f 4)

    if [ -n "$LATEST_URL" ]; then
        echo "[INFO] Mengunduh: $LATEST_URL"
        if [ -n "$GITHUB_TOKEN" ]; then
            ASSET_ID=$(curl -s $AUTH_HEADER "https://api.github.com/repos/$GITHUB_OWNER/$GITHUB_REPO/releases/latest" \
                | grep -B 1 "aplikasi-pelatihan-build.zip" \
                | grep "\"id\":" \
                | head -n 1 \
                | cut -d ':' -f 2 \
                | tr -d ' ,')

            wget -q $WGET_HEADER --header="Accept: application/octet-stream" \
                -O /tmp/aplikasi-pelatihan-build.zip \
                "https://api.github.com/repos/$GITHUB_OWNER/$GITHUB_REPO/releases/assets/$ASSET_ID"
        else
            wget -q -O /tmp/aplikasi-pelatihan-build.zip "$LATEST_URL"
        fi

        if [ $? -eq 0 ]; then
            rm -rf public/build
            unzip -o /tmp/aplikasi-pelatihan-build.zip 'public/build/*' -d "$APP_PATH" > /dev/null
            rm /tmp/aplikasi-pelatihan-build.zip
            echo "[OK] public/build berhasil diperbarui dari release."
        else
            echo "[WARN] Gagal mengunduh build asset. public/build tetap menggunakan versi sebelumnya."
        fi
    else
        echo "[WARN] Tidak ada release ditemukan, public/build tetap menggunakan versi sebelumnya."
    fi
fi

# ----------------------------------------------------------
# 4. Cek file .env & update GitHub config
# ----------------------------------------------------------
echo ""
echo "[4/11] Cek file .env..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate --force
    echo "[INFO] File .env dibuat dari .env.example. Harap sesuaikan konfigurasi database!"
else
    echo "[OK] File .env sudah ada."
fi

# Pastikan GITHUB_REPO_OWNER & GITHUB_REPO_NAME selalu terkini di .env
if grep -q "^GITHUB_REPO_OWNER=" .env; then
    sed -i "s|^GITHUB_REPO_OWNER=.*|GITHUB_REPO_OWNER=$GITHUB_OWNER|" .env
else
    echo "GITHUB_REPO_OWNER=$GITHUB_OWNER" >> .env
fi
if grep -q "^GITHUB_REPO_NAME=" .env; then
    sed -i "s|^GITHUB_REPO_NAME=.*|GITHUB_REPO_NAME=$GITHUB_REPO|" .env
else
    echo "GITHUB_REPO_NAME=$GITHUB_REPO" >> .env
fi

# ----------------------------------------------------------
# 5. Jalankan migrasi database
# ----------------------------------------------------------
echo ""
echo "[5/11] Migrasi database..."
php artisan migrate --force

# ----------------------------------------------------------
# 6. Pastikan symlink storage ada
# ----------------------------------------------------------
echo ""
echo "[6/11] Cek symlink storage..."
if [ ! -L "public/storage" ]; then
    echo "[INFO] Membuat symlink storage..."
    php artisan storage:link
else
    echo "[OK] Symlink storage sudah ada."
fi

# ----------------------------------------------------------
# 7. Optimasi Laravel
# ----------------------------------------------------------
echo ""
echo "[7/11] Optimasi Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "[OK] Optimasi selesai."

# ----------------------------------------------------------
# 8. Deploy Notification System
# ----------------------------------------------------------
echo ""
echo "[8/11] Deploy Notification System..."

# Seed notification templates (jika belum ada)
php artisan db:seed --class=NotificationTemplateSeeder --force 2>/dev/null || echo "[WARN] NotificationTemplateSeeder tidak ditemukan, lewati..."

# Queue & Scheduler
php artisan queue:restart 2>/dev/null || echo "[WARN] queue:restart gagal, mungkin tidak ada queue worker."

# Restart supervisor services (jika ada)
if command -v supervisorctl &> /dev/null; then
    sudo supervisorctl reread 2>/dev/null || true
    sudo supervisorctl update 2>/dev/null || true
    sudo supervisorctl restart laravel-worker:* 2>/dev/null || echo "[WARN] laravel-worker belum terdaftar di supervisor."
    sudo supervisorctl restart laravel-scheduler 2>/dev/null || echo "[WARN] laravel-scheduler belum terdaftar di supervisor."
    echo "[OK] Supervisor services restarted."
else
    echo "[INFO] supervisorctl tidak ditemukan, lewati restart supervisor."
fi

echo "[OK] Notification System deployed."

# ----------------------------------------------------------
# 9. Set permission
# ----------------------------------------------------------
echo ""
echo "[9/11] Set permission folder & ownership..."
chown -R "$WEB_USER":"$WEB_USER" "$APP_PATH"
find "$APP_PATH" -type f -exec chmod 644 {} \;
find "$APP_PATH" -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
echo "[OK] Permission selesai."

# ----------------------------------------------------------
# 10. Informasi versi
# ----------------------------------------------------------
echo ""
echo "[10/11] Informasi deploy..."
DEPLOY_TIME=$(date "+%Y-%m-%d %H:%M:%S")
GIT_HASH=$(git rev-parse --short HEAD 2>/dev/null || echo "unknown")
GIT_BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")
echo "  Waktu deploy : $DEPLOY_TIME"
echo "  Branch       : $GIT_BRANCH"
echo "  Commit       : $GIT_HASH"

# ----------------------------------------------------------
# 11. Selesai
# ----------------------------------------------------------
echo ""
echo "[11/11] Selesai."
echo ""
echo "=========================================="
echo "  Deploy Selesai!"
echo "=========================================="
echo "  Versi: $GIT_BRANCH ($GIT_HASH)"
echo "=========================================="
echo ""
