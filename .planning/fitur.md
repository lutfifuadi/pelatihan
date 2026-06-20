# 📋 Spesifikasi Fitur — Aplikasi Pelatihan

> Update: 12 Juni 2026 — Status fitur terkini

---

## 1. 🔐 Autentikasi & Manajemen User
- [x] Login (email + password) — via Laravel Jetstream + Fortify
- [x] Register (untuk peserta) — via Jetstream
- [x] Logout
- [ ] Forgot / Reset Password — halaman sudah ada, perlu diuji
- [x] Role-based: **Admin**, **Instruktur**, **Koordinator**, **Peserta**
- [x] RoleMiddleware — validasi akses per role
- [x] Profil user (edit profil, foto, bio) — via Jetstream
- [x] Two-factor authentication
- [x] API Token management (Sanctum)

## 2. 📊 Dashboard
- [x] Dashboard Admin: statistik peserta, pelatihan, progress (716 baris, dark premium) + widget notifikasi
- [x] Dashboard Instruktur: daftar peserta, jadwal (basic)
- [x] Dashboard Koordinator: monitoring (basic)
- [x] Dashboard Peserta: pelatihan saya, progress (764 baris, premium)

## 3. 🏫 Manajemen Pelatihan (Training)
- [x] CRUD Pelatihan (Admin) — sudah dengan controller & views
- [x] Kategori pelatihan — via Dinas (dinas terkait)
- [x] Jadwal pelatihan (tanggal mulai, selesai, batas pendaftaran)
- [x] Status pelatihan: aktif/nonaktif
- [x] Upload thumbnail / banner — sudah ada kolom
- [x] Kuota peserta — kolom `kuota` sudah ada
- [x] Relasi kecamatan (kecamatan_pelatihan)
- [x] Relasi Dinas (dinas_id)
- [ ] Batch pelatihan lanjutan

## 4. 📖 Manajemen Materi
- [ ] CRUD Modul / Bab pelatihan — **BELUM**
- [ ] Upload materi: PDF, Video (YouTube/embed), Dokumen — **BELUM**
- [ ] Tampilkan materi per pertemuan — **BELUM**
- [ ] Status selesai baca / tonton — **BELUM**

## 5. 👨‍🎓 Manajemen Peserta
- [x] Pendaftaran peserta via landing page (multi-step form, 5 tahap)
- [x] Validasi NIK & WhatsApp real-time
- [x] Admin lihat daftar peserta + detail
- [x] Admin hapus peserta
- [x] Manajemen koordinator (approve/reject)
- [ ] Approve / Reject pendaftaran ke pelatihan — **BELUM**
- [ ] Absensi / kehadiran peserta — **BELUM**
- [ ] Nilai & evaluasi peserta — **BELUM**
- [ ] Sertifikat kelulusan (generate PDF) — **BELUM**

## 6. 📝 Tugas & Kuis
- [ ] Buat tugas/kuis per modul — **BELUM**
- [ ] Tipe soal: pilihan ganda, essay, upload file — **BELUM**
- [ ] Pengumpulan tugas oleh peserta — **BELUM**
- [ ] Penilaian oleh instruktur — **BELUM**
- [ ] Nilai otomatis untuk pilihan ganda — **BELUM**

## 7. 💬 Diskusi / Forum
- [ ] Forum diskusi per pelatihan — **BELUM**
- [ ] Kirim komentar / pertanyaan — **BELUM**
- [ ] Balas komentar — **BELUM**
- [ ] Notifikasi jika ada diskusi baru — **BELUM**

## 8. 📅 Jadwal & Kalender
- [ ] Kalender pelatihan (FullCalendar) — **library sudah terinstall, fitur belum**
- [ ] Jadwal pertemuan online/offline — **BELUM**
- [ ] Pengingat jadwal (notifikasi) — **SUDAH siap (cron job)**
- [ ] Notifikasi WA reminder jadwal — cron job siap (07:00 setiap hari)

## 9. 📄 Sertifikat
- [ ] Generate sertifikat PDF otomatis — **BELUM**
- [ ] Template sertifikat yang bisa diatur — **BELUM**
- [ ] Nomor sertifikat unik — **BELUM**
- [ ] Verifikasi sertifikat (via QR code / link) — **BELUM**

## 10. 🔔 Notifikasi ✅ (SELESAI — 12 Juni 2026)
- [x] Database: 3 migration (notification_templates, notifications, user_notification_preferences)
- [x] Backend service: NotificationService, NotificationTemplateService
- [x] Event-driven: PesertaRegistered, PendaftaranApproved, TugasBaru, JadwalReminder + Listener
- [x] Queue: SendWhatsAppNotification (backoff 5/15/30s), ProcessPendingNotifications (database driver)
- [x] WhatsApp Service Enhanced (+5 method: footer, reply, full response, check number, retry)
- [x] WhatsApp Validation Service (normalize, isRegistered, bulkCheck)
- [x] Notifikasi in-app (bell icon + dropdown Alpine.js, polling 30 detik)
- [x] Halaman history notifikasi (filter by channel & status, pagination, mark all read)
- [x] Halaman preferensi notifikasi per user (toggle WA/email/in-app + quiet hours)
- [x] Admin log pengiriman (filter, detail modal, resend failed)
- [x] Admin CRUD template WA (create, edit, delete, test kirim, auto-extract variables)
- [x] Admin broadcast WA (target: all peserta/by pelatihan/all koordinator/custom CSV)
- [x] Dashboard widget notifikasi (WA terkirim, gagal, template aktif, pending)
- [x] 4 Artisan commands: send-reminders, process-queue, cleanup, test
- [x] Scheduler cron: reminder 07:00, queue 5 menit, cleanup 02:00
- [x] 6 template default: welcome_peserta, pendaftaran_diterima, pendaftaran_ditolak, tugas_baru, pengingat_jadwal, kelulusan
- [x] Automatisasi kirim notifikasi WA via queue worker + supervisor
- [x] Testing: 41 PHPUnit test, 20 test case manual
- [x] Event wiring di controller registrasi (Landing + Koordinator)

## 11. ⚙️ Manajemen Sistem (Admin)
- [x] CRUD Kecamatan — selesai
- [x] CRUD Kelurahan — selesai
- [x] CRUD Pelatihan — selesai
- [x] CRUD Dinas — selesai
- [x] CRUD FAQ — selesai
- [x] Manajemen Koordinator (approve/reject) — selesai
- [x] Setting aplikasi (branding, lock kota/provinsi) — selesai
- [x] WhatsApp Gateway management — selesai
- [x] Log Pengiriman Notifikasi — selesai (Sari)
- [x] CRUD Template WA — selesai (Sari)
- [x] Broadcast WA — selesai (Sari)
- [ ] Log aktivitas — **BELUM**
- [ ] Backup data — **BELUM**

## 12. 📱 Lainnya
- [ ] PWA (Progressive Web App) — **BELUM**
- [ ] Export laporan (PDF, Excel) — **BELUM**
- [ ] Multi bahasa (Indonesia + Inggris) — **template sudah ada, konten belum**
- [ ] SEO friendly — **BELUM**
- [x] Responsive design (mobile friendly) — **Vuexy template sudah responsive**

---

> **Legenda**: ✅ = Selesai | ❌/⬜ = Belum dikerjakan
