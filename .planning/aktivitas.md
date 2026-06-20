# 📝 Catatan Aktivitas - Aplikasi Pelatihan

## Log Harian — Sabtu, 20 Juni 2026

| Waktu | Aktivitas | Agen | Status |
|-------|-----------|------|--------|
| Sore | **Landing Page Content Management** — Menambahkan ~50 setting key (group: 'landing') untuk semua teks halaman beranda publik (Hero, Form, Langkah, Pelatihan, Mengapa, CTA). Membuat halaman admin `/admin/settings/landing` dengan accordion per section. Semua teks di `beranda.blade.php` kini dinamis dari database. | Sophia, Dika | ✅ |

## Log Harian — Rabu, 17 Juni 2026

| Waktu | Aktivitas | Agen | Status |
|-------|-----------|------|--------|
| Sore | **Perbaikan Keamanan & Performa Aplikasi Pelatihan** — Perbaikan celah keamanan: mematikan penulisan data pribadi (PII) ke plaintext `.planing/data-user.txt`, menambahkan middleware rate limiting `throttle:10,1` untuk endpoint register publik, set default token expiration ke 1 tahun (config sanctum), dan mengaktifkan session encryption. | Sophia, Dika | ✅ |