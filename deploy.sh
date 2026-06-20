#!/bin/bash

# ============================================================
# Script Deploy - Aplikasi Pelatihan
# Server: VPS / aaPanel
# Jalankan setiap ada update kode:
#   bash deploy.sh
#
# Best practices yang diterapkan:
#   - set -euo pipefail (henti pada error kritis)
#   - Maintenance mode aman dengan trap up
#   - Verifikasi manifest.json setelah build/fallback
#   - Health check curl ke homepage setelah deploy
#   - Logging ke storage/logs/deploy-*.log
# ============================================================

set -euo pipefail

# ----------------------------------------------------------
# Konfigurasi
# ----------------------------------------------------------
APP_PATH="$(cd "$(dirname "$0")" && pwd)"
WEB_USER="${WEB_USER:-www-data}"
WEB_GROUP="${WEB_GROUP:-www-data}"
DEPLOY_LOG_DIR="$APP_PATH/storage/logs"
DEPLOY_LOG="$DEPLOY_LOG_DIR/deploy-$(date +%Y%m%d_%H%M%S).log"
HEALTH_URL="${HEALTH_URL:-https://pelatihanku.my.id}"
BUILD_ASSET_NAME="aplikasi-pelatihan-build.zip"
GITHUB_OWNER=""
GITHUB_REPO=""
MAINTENANCE_ACTIVE=false

# Pastikan direktori log ada
mkdir -p "$DEPLOY_LOG_DIR"

# ----------------------------------------------------------
# Logging helpers
# ----------------------------------------------------------
log() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] $1"
    echo "$msg" | tee -a "$DEPLOY_LOG"
}

log_info()  { log "[INFO]  $1"; }
log_ok()    { log "[OK]    $1"; }
log_warn()  { log "[WARN]  $1"; }
log_error() { log "[ERROR] $1"; }

die() {
    log_error "$1"
    log_error "Deploy GAGAL. Lihat log lengkap: $DEPLOY_LOG"
    exit 1
}

# ----------------------------------------------------------
# Trap: selalu matikan maintenance mode jika sempat dinyalakan
# ----------------------------------------------------------
cleanup() {
    local exit_code=$?
    if [ "$MAINTENANCE_ACTIVE" = true ]; then
        log_warn "Cleanup: maintenance mode masih aktif, mencoba mematikan..."
        cd "$APP_PATH" && php artisan up 2>&1 | tee -a "$DEPLOY_LOG" || true
        log_info "Maintenance mode OFF."
    fi
    if [ $exit_code -ne 0 ]; then
        log_error "Script berakhir dengan exit code $exit_code."
    fi
}
trap cleanup EXIT

# ----------------------------------------------------------
# Auto-detect GitHub owner & repo dari git remote
# ----------------------------------------------------------
detect_github_repo() {
    if command -v git &> /dev/null && git rev-parse --git-dir &> /dev/null; then
        local remote_url
        remote_url=$(git remote get-url origin 2>/dev/null || true)
        if [ -n "$remote_url" ]; then
            local owner_https repo_https owner_ssh repo_ssh
            owner_https=$(echo "$remote_url" | sed -n 's|https://github.com/\([^/]*\)/\(.*\)\.git|\1|p')
            repo_https=$(echo "$remote_url" | sed -n 's|https://github.com/\([^/]*\)/\(.*\)\.git|\2|p')
            owner_ssh=$(echo "$remote_url" | sed -n 's|git@github.com:\([^/]*\)/\(.*\)\.git|\1|p')
            repo_ssh=$(echo "$remote_url" | sed -n 's|git@github.com:\([^/]*\)/\(.*\)\.git|\2|p')
            GITHUB_OWNER="${owner_https:-$owner_ssh}"
            GITHUB_REPO="${repo_https:-$repo_ssh}"
        fi
    fi

    if [ -z "$GITHUB_OWNER" ] || [ -z "$GITHUB_REPO" ]; then
        GITHUB_OWNER="lutfifuadi"
        GITHUB_REPO="pelatihan"
        log_warn "Git remote tidak terdeteksi. Menggunakan default: $GITHUB_OWNER/$GITHUB_REPO"
    else
        log_info "Git remote terdeteksi: $GITHUB_OWNER/$GITHUB_REPO"
    fi
}

# ----------------------------------------------------------
# Pastikan dijalankan dari direktori aplikasi
# ----------------------------------------------------------
cd "$APP_PATH" || die "Path tidak ditemukan: $APP_PATH"

# Muat variabel dari .env (untuk GITHUB_TOKEN, APP_URL, dsb.)
if [ -f ".env" ]; then
    set -a
    # shellcheck source=/dev/null
    . ./.env
    set +a
fi

# Jika HEALTH_URL tidak di-set di env, gunakan APP_URL
if [ -n "${APP_URL:-}" ] && [ "$HEALTH_URL" = "https://pelatihanku.my.id" ]; then
    HEALTH_URL="$APP_URL"
fi

detect_github_repo

echo "==========================================" | tee -a "$DEPLOY_LOG"
echo "  Deploy Aplikasi Pelatihan - VPS" | tee -a "$DEPLOY_LOG"
echo "==========================================" | tee -a "$DEPLOY_LOG"
echo "  Path terdeteksi : $APP_PATH" | tee -a "$DEPLOY_LOG"
echo "  Web user/group  : $WEB_USER:$WEB_GROUP" | tee -a "$DEPLOY_LOG"
echo "  Health URL      : $HEALTH_URL" | tee -a "$DEPLOY_LOG"
echo "  Log file        : $DEPLOY_LOG" | tee -a "$DEPLOY_LOG"
echo "==========================================" | tee -a "$DEPLOY_LOG"

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
# 0. Maintenance mode ON
# ----------------------------------------------------------
log_info "[0/12] Maintenance mode ON..."
php artisan down --retry=60 --refresh=15 \
    || die "Gagal mengaktifkan maintenance mode."
MAINTENANCE_ACTIVE=true
log_ok "Maintenance mode aktif."

# ----------------------------------------------------------
# 1. Pull kode terbaru dari Git
# ----------------------------------------------------------
log_info "[1/12] Pull kode terbaru dari Git..."

if [ -n "${GITHUB_TOKEN:-}" ]; then
    log_info "Menggunakan GITHUB_TOKEN untuk autentikasi Git..."
    REMOTE_URL=$(git remote get-url origin)
    if [[ "$REMOTE_URL" == https://github.com* ]]; then
        NEW_URL="https://$GITHUB_TOKEN@${REMOTE_URL#https://}"
        git remote set-url origin "$NEW_URL"
    fi
fi

git pull origin main 2>&1 | tee -a "$DEPLOY_LOG" || die "Git pull gagal. Periksa koneksi, token, atau konflik."
log_ok "Git pull berhasil."

# ----------------------------------------------------------
# 2. Install / update Composer dependencies
# ----------------------------------------------------------
log_info "[2/12] Install Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tee -a "$DEPLOY_LOG" \
    || die "Composer install gagal."
log_ok "Composer dependencies terinstall."

# ----------------------------------------------------------
# 3. Download frontend assets dari GitHub Release (build sudah di-CI)
# ----------------------------------------------------------
log_info "[3/12] Download frontend assets dari GitHub Release..."

BUILD_DOWNLOADED=false

AUTH_HEADER=""
WGET_HEADER=""
if [ -n "${GITHUB_TOKEN:-}" ]; then
    AUTH_HEADER="-H Authorization: token $GITHUB_TOKEN"
    WGET_HEADER="--header=Authorization: token $GITHUB_TOKEN"
    log_info "Menggunakan GITHUB_TOKEN untuk autentikasi API."
fi

LATEST_URL=$(curl -sL $AUTH_HEADER "https://api.github.com/repos/$GITHUB_OWNER/$GITHUB_REPO/releases/latest" \
    | grep "browser_download_url" \
    | grep "$BUILD_ASSET_NAME" \
    | cut -d '"' -f 4) || true

if [ -n "$LATEST_URL" ]; then
    log_info "Mengunduh: $LATEST_URL"

    if [ -n "${GITHUB_TOKEN:-}" ]; then
        ASSET_ID=$(curl -sL $AUTH_HEADER "https://api.github.com/repos/$GITHUB_OWNER/$GITHUB_REPO/releases/latest" \
            | grep -B 1 "$BUILD_ASSET_NAME" \
            | grep '"id":' \
            | head -n 1 \
            | cut -d ':' -f 2 \
            | tr -d ' ,') || true

        wget -q $WGET_HEADER --header="Accept: application/octet-stream" \
            -O "/tmp/$BUILD_ASSET_NAME" \
            "https://api.github.com/repos/$GITHUB_OWNER/$GITHUB_REPO/releases/assets/$ASSET_ID" 2>&1 | tee -a "$DEPLOY_LOG" \
            || die "Gagal mengunduh asset release (private repo / asset ID tidak ditemukan)."
    else
        wget -q -O "/tmp/$BUILD_ASSET_NAME" "$LATEST_URL" 2>&1 | tee -a "$DEPLOY_LOG" \
            || die "Gagal mengunduh asset release."
    fi

    rm -rf public/build
    unzip -o "/tmp/$BUILD_ASSET_NAME" 'public/build/*' -d "$APP_PATH" > /dev/null \
        || die "Gagal mengekstrak asset release."
    rm -f "/tmp/$BUILD_ASSET_NAME"
    log_ok "public/build berhasil diperbarui dari release."
else
    log_warn "Tidak ada release ditemukan. Mempertahankan public/build yang sudah ada."
    if [ ! -d "public/build" ] || [ ! -f "public/build/manifest.json" ]; then
        die "Tidak ada public/build dari release maupun local. Halaman akan blank putih."
    fi
fi

# ----------------------------------------------------------
# 4. Verifikasi manifest.json
# ----------------------------------------------------------
log_info "[4/12] Verifikasi build assets (manifest.json)..."
if [ ! -f "public/build/manifest.json" ]; then
    die "public/build/manifest.json tidak ditemukan. Halaman akan blank putih."
fi
# Pastikan file tidak kosong dan valid JSON minimal
if [ ! -s "public/build/manifest.json" ]; then
    die "public/build/manifest.json kosong."
fi
log_ok "manifest.json ditemukan ($(wc -c < public/build/manifest.json) bytes)."

# Validasi: pastikan manifest berisi entry minimal dari kode terbaru
validate_manifest() {
    if command -v python3 &> /dev/null; then
        python3 -c "
import json, sys
try:
    m = json.load(open('public/build/manifest.json'))
    # Cek minimal entry yang pasti ada di setiap build baru
    key = 'resources/js/app.js'
    if key not in m:
        print('$key tidak ditemukan di manifest.json')
        sys.exit(1)
    print('manifest.json valid: $key ditemukan')
except Exception as e:
    print('manifest.json error:', e)
    sys.exit(1)
" 2>&1 | tee -a "$DEPLOY_LOG" || return 1
    fi
    return 0
}

if ! validate_manifest; then
    log_warn "manifest.json tidak mengandung entry kode terbaru. Release mungkin outdated."
    # Coba build lokal jika node tersedia
    if command -v node &> /dev/null && command -v npm &> /dev/null; then
        log_info "Mencoba build dari source (npm ci && npm run build)..."
        rm -rf public/build
        if npm ci --legacy-peer-deps --no-audit --no-fund 2>&1 | tee -a "$DEPLOY_LOG" \
            && npm run build 2>&1 | tee -a "$DEPLOY_LOG"; then
            log_ok "Frontend assets berhasil dibuild dari source."
            if ! validate_manifest; then
                die "Build lokal menghasilkan manifest.json yang tidak valid."
            fi
        else
            die "npm build gagal. Tidak ada build asset yang valid. Halaman akan blank putih."
        fi
    else
        die "manifest.json tidak valid dan Node.js tidak tersedia. Halaman akan blank putih."
    fi
fi

# ----------------------------------------------------------
# 5. Cek file .env
# ----------------------------------------------------------
log_info "[5/12] Cek file .env..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate --force
    log_warn "File .env dibuat dari .env.example. Harap sesuaikan konfigurasi database!"
else
    log_ok "File .env sudah ada."
fi

# ----------------------------------------------------------
# 6. Jalankan migrasi database
# ----------------------------------------------------------
log_info "[6/12] Migrasi database..."
php artisan migrate --force 2>&1 | tee -a "$DEPLOY_LOG" || die "Migrasi database gagal. Aplikasi bisa QueryException/blank."
log_ok "Migrasi database berhasil."

# ----------------------------------------------------------
# 7. Pastikan symlink storage ada
# ----------------------------------------------------------
log_info "[7/12] Cek symlink storage..."
if [ ! -L "public/storage" ]; then
    log_info "Membuat symlink storage..."
    php artisan storage:link || die "Gagal membuat storage symlink."
else
    log_ok "Symlink storage sudah ada."
fi

# ----------------------------------------------------------
# 8. Optimasi Laravel
# ----------------------------------------------------------
log_info "[8/12] Optimasi Laravel..."
php artisan config:clear 2>&1 | tee -a "$DEPLOY_LOG" || true
php artisan route:clear 2>&1 | tee -a "$DEPLOY_LOG" || true
php artisan view:clear 2>&1 | tee -a "$DEPLOY_LOG" || true
php artisan cache:clear 2>&1 | tee -a "$DEPLOY_LOG" || true
php artisan config:cache 2>&1 | tee -a "$DEPLOY_LOG" || die "config:cache gagal."
php artisan route:cache 2>&1 | tee -a "$DEPLOY_LOG" || die "route:cache gagal."
php artisan view:cache 2>&1 | tee -a "$DEPLOY_LOG" || die "view:cache gagal."
log_ok "Optimasi selesai."

# ----------------------------------------------------------
# 9. Deploy Notification System & Queue
# ----------------------------------------------------------
log_info "[9/12] Deploy Notification System..."
php artisan db:seed --class=NotificationTemplateSeeder --force 2>&1 | tee -a "$DEPLOY_LOG" \
    || log_warn "NotificationTemplateSeeder tidak ditemukan atau gagal, dilewati."
php artisan queue:restart 2>&1 | tee -a "$DEPLOY_LOG" || log_warn "queue:restart gagal, mungkin tidak ada queue worker."

if command -v supervisorctl &> /dev/null; then
    sudo supervisorctl reread 2>&1 | tee -a "$DEPLOY_LOG" || true
    sudo supervisorctl update 2>&1 | tee -a "$DEPLOY_LOG" || true
    sudo supervisorctl restart laravel-worker:* 2>&1 | tee -a "$DEPLOY_LOG" || log_warn "laravel-worker belum terdaftar di supervisor."
    sudo supervisorctl restart laravel-scheduler 2>&1 | tee -a "$DEPLOY_LOG" || log_warn "laravel-scheduler belum terdaftar di supervisor."
    log_ok "Supervisor services restarted."
else
    log_info "supervisorctl tidak ditemukan, lewati restart supervisor."
fi

# ----------------------------------------------------------
# 10. Set permission
# ----------------------------------------------------------
log_info "[10/12] Set permission folder & ownership..."

# Pastikan storage & bootstrap/cache writable
chmod -R 775 storage bootstrap/cache 2>&1 | tee -a "$DEPLOY_LOG" || die "Gagal set permission storage/cache."

# Set ownership hanya untuk direktori yang memang perlu diakses web server
if id "$WEB_USER" &>/dev/null; then
    chown -R "$WEB_USER":"$WEB_GROUP" storage bootstrap/cache public/build 2>&1 | tee -a "$DEPLOY_LOG" \
        || log_warn "Gagal chown beberapa direktori (mungkin butuh sudo)."
else
    log_warn "User '$WEB_USER' tidak ditemukan di sistem. Lewati chown."
fi

# Pastikan artisan executable dan direktori root tetap readable
chmod 755 artisan
find "$APP_PATH" -type f -exec chmod 644 {} \;
find "$APP_PATH" -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM jika tersedia (membersihkan opcache/realpath cache)
if command -v systemctl &> /dev/null && systemctl list-units --type=service | grep -q php.*-fpm; then
    log_info "Restart PHP-FPM..."
    sudo systemctl restart php*-fpm 2>&1 | tee -a "$DEPLOY_LOG" || log_warn "Gagal restart php-fpm."
fi

log_ok "Permission selesai."

# ----------------------------------------------------------
# 11. Maintenance mode OFF
# ----------------------------------------------------------
log_info "[11/12] Maintenance mode OFF..."
php artisan up 2>&1 | tee -a "$DEPLOY_LOG" || die "Gagal mematikan maintenance mode."
MAINTENANCE_ACTIVE=false
log_ok "Maintenance mode nonaktif."

# ----------------------------------------------------------
# 12. Health check homepage
# ----------------------------------------------------------
log_info "[12/12] Health check ke $HEALTH_URL ..."
HEALTH_STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$HEALTH_URL" 2>&1 || true)
if [ "$HEALTH_STATUS" = "200" ] || [ "$HEALTH_STATUS" = "302" ] || [ "$HEALTH_STATUS" = "301" ]; then
    log_ok "Health check OK (HTTP $HEALTH_STATUS)."
else
    log_warn "Health check tidak OK (HTTP $HEALTH_STATUS). Cek server/Nginx segera."
fi

# ----------------------------------------------------------
# Informasi versi
# ----------------------------------------------------------
DEPLOY_TIME=$(date "+%Y-%m-%d %H:%M:%S")
GIT_HASH=$(git rev-parse --short HEAD 2>/dev/null || echo "unknown")
GIT_BRANCH=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")

echo "" | tee -a "$DEPLOY_LOG"
echo "==========================================" | tee -a "$DEPLOY_LOG"
echo "  Deploy Selesai!" | tee -a "$DEPLOY_LOG"
echo "==========================================" | tee -a "$DEPLOY_LOG"
echo "  Waktu deploy : $DEPLOY_TIME" | tee -a "$DEPLOY_LOG"
echo "  Branch       : $GIT_BRANCH" | tee -a "$DEPLOY_LOG"
echo "  Commit       : $GIT_HASH" | tee -a "$DEPLOY_LOG"
echo "  Log file     : $DEPLOY_LOG" | tee -a "$DEPLOY_LOG"
echo "==========================================" | tee -a "$DEPLOY_LOG"
