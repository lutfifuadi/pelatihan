# 📝 Catatan Aktivitas - Aplikasi Pelatihan

## Log Harian — Rabu, 17 Juni 2026

| Waktu | Aktivitas | Agen | Status |
|-------|-----------|------|--------|
| Sore | **Perbaikan Keamanan & Performa Aplikasi Pelatihan** — Perbaikan celah keamanan: mematikan penulisan data pribadi (PII) ke plaintext `.planing/data-user.txt`, menambahkan middleware rate limiting `throttle:10,1` untuk endpoint register publik, set default token expiration ke 1 tahun (config sanctum), dan mengaktifkan session encryption. | Sophia, Dika | ✅ |