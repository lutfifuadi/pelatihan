# Changelog Aplikasi Pelatihan

Semua perubahan penting pada aplikasi ini akan dicatat di sini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/),
dan versi mengikuti [Semantic Versioning](https://semver.org/).

---

## [1.2.5] - 2026-06-30

### Fixed
- Memperbaiki isu QR Code presensi di dashboard peserta yang tidak muncul meskipun berstatus 'confirmed' dan siap mengikuti pelatihan.
- Memindahkan rute pengambilan token presensi peserta dari '/api/peserta/attendance-token' ke rute web '/peserta/attendance-token' yang dilindungi Web Session Guard untuk mengatasi error 401 Unauthorized.
- Memuat pustaka 'qrcode.min.js' secara statis di dashboard peserta untuk mencegah error race condition 'QRCode is not defined'.
- Menambahkan error handling dan notifikasi toast yang informatif jika pengambilan token presensi gagal.

---

## [latest-build] - 2026-06-12

### Added
- Halaman daftar peserta per pelatihan (admin)
  - Tabel peserta: Nama, NIK, WhatsApp, Email, Kecamatan, Status
  - Info cards: Total peserta, Lengkap, Kuota, Tanggal pelaksanaan
  - Tombol "Salin Semua WA" (clipboard API)
  - Placeholder Export Excel
  - Pagination 20 data per halaman
- Auto-detect GitHub repository dari git remote di script deployment
- Maintenance mode (up/down) pada CI/CD workflow

### Changed
- Sidebar admin: menu "Kelola Pelatihan" jadi menu group (Daftar + Tambah)
- Workflow deploy lebih tangguh: npm fallback, sudo-safe chown

### Infrastructure
- `install.sh` — 11 langkah full auto (migrate, seed, npm build, cron, supervisor)
- `deploy.sh` — 9 langkah auto-update dengan info versi
- `setup-queue-worker.sh` — Setup Supervisor untuk queue worker
- CI/CD GitHub Actions: auto-deploy ke VPS saat push ke main
- Workflow release package: build ZIP siap pakai (frontend + vendor)

---

## [0.1.0] - 2026-06-10

### Added
- Foundation: Auth system (Jetstream + Fortify, 4 role)
- Multi-step form pendaftaran peserta (5 tahap)
- Landing page dengan hero, FAQ, CTA
- Admin CRUD: kecamatan, kelurahan, pelatihan, dinas, peserta, FAQ, settings
- Dashboard per role (admin, instruktur, koordinator, peserta)
- WhatsApp Gateway integration
- Manajemen koordinator (approve/reject)
- SEO sitemap & metadata
- 25 file migration + 8 seeder
- Multi-language template (Indonesia + Inggris)
