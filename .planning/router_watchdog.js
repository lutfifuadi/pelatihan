const Database = require('C:/Users/luthf/AppData/Roaming/9router/runtime/node_modules/better-sqlite3');
const db = new Database('C:/Users/luthf/AppData/Roaming/9router/db/data.sqlite');

// Konfigurasi Threshold
const REQUEST_LIMIT = 1500; // Limit request harian Gemini CLI
const TOKEN_LIMIT = 15000000; // Contoh limit 15jt token (sesuaikan jika perlu)
const THRESHOLD = 0.7; // 70% pemakaian (Sisa 30%)

function checkAndRotate() {
    console.log(`[${new Date().toLocaleString('id-ID')}] Memeriksa penggunaan kuota...`);
    
    // 1. Ambil data usage hari ini (dateKey format YYYY-MM-DD)
    const today = new Date().toISOString().split('T')[0];
    const row = db.prepare("SELECT data FROM usageDaily WHERE dateKey = ?").get(today);

    if (!row) {
        console.log("ℹ️ Belum ada data penggunaan untuk hari ini.");
        return;
    }

    const usageData = JSON.parse(row.data);
    const accountsUsage = usageData.byAccount || {};

    // 2. Loop setiap akun yang punya penggunaan hari ini
    for (const [accountId, stats] of Object.entries(accountsUsage)) {
        const isNearRequestLimit = stats.requests >= (REQUEST_LIMIT * THRESHOLD);
        const isNearTokenLimit = stats.promptTokens >= (TOKEN_LIMIT * THRESHOLD);

        if (isNearRequestLimit || isNearTokenLimit) {
            console.log(`⚠️ Akun ${accountId} mendekati limit!`);
            console.log(`   - Requests: ${stats.requests}/${REQUEST_LIMIT}`);
            console.log(`   - Tokens: ${stats.promptTokens}/${TOKEN_LIMIT}`);

            // 3. Nonaktifkan akun di tabel providerConnections
            const result = db.prepare("UPDATE providerConnections SET isActive = 0 WHERE id = ? AND isActive = 1").run(accountId);
            
            if (result.changes > 0) {
                console.log(`✅ Berhasil menonaktifkan akun ${accountId}.`);
                
                // 4. Pastikan ada akun cadangan yang aktif
                const nextAccount = db.prepare("SELECT name FROM providerConnections WHERE provider = 'gemini-cli' AND isActive = 1 ORDER BY priority ASC LIMIT 1").get();
                if (nextAccount) {
                    console.log(`🚀 Akun aktif berikutnya: ${nextAccount.name}`);
                } else {
                    console.log("❌ PERINGATAN: Tidak ada akun cadangan Gemini CLI yang tersedia!");
                }
            }
        }
    }
}

// Jalankan setiap 1 menit untuk respon cepat
setInterval(checkAndRotate, 60 * 1000);
console.log("🤖 Watchdog 9router aktif. Memantau setiap 60 detik...");
checkAndRotate();
