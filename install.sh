#!/bin/bash

# ============================================================
# Script Instalasi Pertama - Aplikasi Pelatihan
# Server: VPS / aaPanel
# Jalankan SEKALI saat setup pertama:
#   bash install.sh
# ============================================================

APP_PATH="$(cd "$(dirname "$0")" && pwd)"
WEB_USER="www"

echo "=========================================="
echo "  Instalasi Aplikasi Pelatihan - VPS"
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

# ----------------------------------------------------------
# 1. Persiapan folder wajib Laravel
# ----------------------------------------------------------
echo ""
echo "[1/8] Persiapan folder & permission..."
mkdir -p bootstrap/cache storage/framework/{sessions,views,cache/data} storage/logs
chmod -R 775 bootstrap/cache storage
echo "[OK] Folder siap."

# ----------------------------------------------------------
# 2. Install Composer dependencies
# ----------------------------------------------------------
echo ""
echo "[2/8] Install Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# ----------------------------------------------------------
# 3. Setup file .env & generate key
# ----------------------------------------------------------
echo ""
echo "[3/8] Setup file .env & generate key..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "[OK] File .env dibuat dari .env.example."
else
    echo "[OK] File .env sudah ada, dilewati."
fi

php artisan key:generate --force
echo "[OK] APP_KEY berhasil digenerate."

# Muat variabel dari .env
if [ -f ".env" ]; then
    export $(grep -v '^#' .env | xargs)
fi

# Tulis GITHUB_REPO_OWNER & GITHUB_REPO_NAME ke .env jika belum ada atau berbeda
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
echo "[OK] GITHUB_REPO_OWNER=$GITHUB_OWNER, GITHUB_REPO_NAME=$GITHUB_REPO ditulis ke .env"

# ----------------------------------------------------------
# 4. Build frontend assets (npm) — fallback ke GitHub Release
# ----------------------------------------------------------
echo ""
echo "[4/8] Build frontend assets..."

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
        echo "[INFO] Menggunakan GITHUB_TOKEN untuk autentikasi."
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
            echo "[OK] public/build berhasil diekstrak dari release."
        else
            echo "[WARN] Gagal mengunduh build asset. Tampilan mungkin rusak."
        fi
    else
        echo "[WARN] Tidak ada release ditemukan di GitHub. Tampilan mungkin rusak."
    fi
fi

# ----------------------------------------------------------
# 5. Buat symlink storage
# ----------------------------------------------------------
echo ""
echo "[5/8] Buat symlink storage..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "[OK] Symlink public/storage dibuat."
else
    echo "[OK] Symlink public/storage sudah ada, dilewati."
fi

# ----------------------------------------------------------
# 6. Set permission
# ----------------------------------------------------------
echo ""
echo "[6/8] Set permission folder & ownership..."
chown -R "$WEB_USER":"$WEB_USER" "$APP_PATH"
find "$APP_PATH" -type f -exec chmod 644 {} \;
find "$APP_PATH" -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
echo "[OK] Permission selesai."

# ----------------------------------------------------------
# 7. Setup queue worker jika Supervisor tersedia
# ----------------------------------------------------------
echo ""
echo "[7/8] Setup queue worker..."
if command -v supervisorctl &> /dev/null; then
    echo "[INFO] Supervisor ditemukan, setup queue worker..."
    if [ -f "$APP_PATH/setup-queue-worker.sh" ]; then
        echo "[INFO] Menjalankan setup-queue-worker.sh..."
        bash "$APP_PATH/setup-queue-worker.sh"
    else
        echo "[INFO] File setup-queue-worker.sh tidak ditemukan."
        echo "[INFO] Buat konfigurasi Supervisor manual untuk queue worker."
    fi
else
    echo ""
    echo "=========================================="
    echo "  [WARN] Supervisor tidak ditemukan!"
    echo "  Queue worker tidak akan berjalan."
    echo "  Untuk mengaktifkan notifikasi & antrian:"
    echo "    1. Install Supervisor"
    echo "    2. Buat konfigurasi queue worker"
    echo "=========================================="
fi

# ----------------------------------------------------------
# 8. Setup cron job untuk scheduler
# ----------------------------------------------------------
echo ""
echo "[8/8] Setup cron job untuk scheduler..."
CRON_JOB="* * * * * cd $APP_PATH && php artisan schedule:run >> /dev/null 2>&1"
EXISTING_CRON=$(crontab -l 2>/dev/null || true)
if echo "$EXISTING_CRON" | grep -q "php artisan schedule:run"; then
    echo "[OK] Cron job untuk scheduler sudah ada, dilewati."
else
    (echo "$EXISTING_CRON"; echo "$CRON_JOB") | crontab -
    echo "[OK] Cron job untuk scheduler ditambahkan."
    echo "     $CRON_JOB"
fi

# ----------------------------------------------------------
# 9. Tanya mode instalasi
# ----------------------------------------------------------
echo ""
echo "=========================================="
echo "  Instalasi Lingkungan Selesai!"
echo "=========================================="
echo ""
echo "Pilih mode instalasi:"
echo "  [1] CLI (headless) — Install langsung via command line"
echo "     Lebih cepat, tidak perlu browser."
echo ""
echo "  [2] Browser (wizard) — Buka wizard instalasi di browser"
echo "     Cocok jika ingin konfigurasi visual."
echo ""
read -p "Pilihan [1/2] (default: 1): " INSTALL_MODE
INSTALL_MODE="${INSTALL_MODE:-1}"

if [ "$INSTALL_MODE" = "1" ] || [ "$INSTALL_MODE" = "" ]; then
    echo ""
    echo "=========================================="
    echo "  Mode CLI — Instalasi Headless"
    echo "=========================================="
    echo ""
    echo "Memulai instalasi database & aplikasi..."
    echo ""
    
    # Jalankan instalasi via Artisan command
    php artisan app:install --fresh
    
    INSTALL_EXIT=$?
    
    if [ $INSTALL_EXIT -eq 0 ]; then
        echo ""
        echo "=========================================="
        echo "  ✅ INSTALASI BERHASIL!"
        echo "=========================================="
        echo ""
        echo "  Silakan login dengan akun admin yang sudah dibuat."
        echo "  https://domain-anda/login"
        echo ""
    else
        echo ""
        echo "=========================================="
        echo "  ❌ INSTALASI GAGAL"
        echo "=========================================="
        echo ""
        echo "  Coba jalankan manual:"
        echo "    php artisan app:install --fresh"
        echo ""
        echo "  Atau pilih mode browser:"
        echo "    Buka https://domain-anda/install"
        echo ""
    fi
else
    echo ""
    echo "=========================================="
    echo "  Mode Browser — Buka Wizard Instalasi"
    echo "=========================================="
    echo ""
    echo "Langkah selanjutnya:"
    echo "  Buka browser dan akses wizard instalasi:"
    echo "  https://domain-anda/install"
    echo ""
    echo "  Wizard akan memandu:"
    echo "  - Konfigurasi database"
    echo "  - Data aplikasi"
    echo "  - Pembuatan akun admin"
    echo ""
fi
