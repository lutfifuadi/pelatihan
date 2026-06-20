# 📅 Timeline Pengembangan — Aplikasi Pelatihan

> Update: 12 Juni 2026 — Progres terkini

---

## 🚀 Fase 1: Foundation (Minggu 1) — ✅ **SELESAI**
*Setup dasar aplikasi — sudah berjalan*

| Task | Anggota | Status | Keterangan |
|------|---------|--------|------------|
| Generate APP_KEY & setup env | Gilang | ✅ Selesai | APP_KEY sudah digenerate, .env terisi |
| Setup database | Eka, Gilang | ✅ Selesai | MariaDB remote (103.197.191.226) |
| Migrasi users + kolom role, phone, avatar, dll | Eka | ✅ Selesai | 25 migration siap |
| Buat RoleMiddleware | Bayu | ✅ Selesai | admin, instruktur, koordinator, peserta |
| Auth logic (login/register) | Bayu, Ayu | ✅ Selesai | Jetstream + Fortify |
| Halaman dashboard per role | Ayu | ✅ Selesai | Admin (716 baris), Peserta (764 baris) |
| Multi-step form peserta (5 tahap) | Dika, Ayu | ✅ Selesai | Data diri → Pendidikan → Minat → Dokumen |
| Landing page | Ayu | ✅ Selesai | Hero, FAQ, CTA (1282+ baris) |
| Admin CRUD (kecamatan, kelurahan, dll) | Sari, Bayu | ✅ Selesai | 9 resource controller |
| WhatsApp Gateway integration | Rizky | ✅ Selesai | Cek nomor + kirim pesan |
| Seeder data master | Eka | ✅ Selesai | 8 seeder (kecamatan, kelurahan, admin, dll) |

**✅ Deliverable**: Aplikasi bisa login/register dengan role, admin panel, landing page ✅

---

## 🚀 Fase 1B: Sistem Notifikasi (Minggu 1 Ekstensi — 12 Juni 2026) ✅ **SELESAI**
*Pengembangan sistem notifikasi multi-channel*

| Task | Anggota | Status | Keterangan |
|------|---------|--------|------------|
| Migration: notification_templates, notifications, preferences | Eka | ✅ Selesai | 3 migration + 3 model |
| NotificationService + Events + Listener | Bayu | ✅ Selesai | Service, 4 Events, Subscriber |
| WhatsAppService enhanced + ValidationService | Rizky | ✅ Selesai | +5 method, validation, config |
| Bell icon Alpine.js + History + Preferences | Ayu | ✅ Selesai | Polling 30s, filter, toggle |
| Admin Log + Template CRUD + Broadcast | Sari | ✅ Selesai | Full CRUD + broadcast queue |
| 4 Artisan commands + Scheduler | Rudi | ✅ Selesai | Reminder, queue, cleanup, test |
| Testing: 41 PHPUnit test + 20 manual | Farhan | ✅ Selesai | All passing, 3 bugs fixed |
| Queue Worker + Supervisor + docker-compose | Gilang | ✅ Selesai | Worker, scheduler, health check |
| Code review + bug fixes | Rian | ✅ Selesai | Backoff, race condition, cache |
| UI: border-radius maksimal 5px | Wira | ✅ Selesai | SCSS override + hardcoded fix |
| Event wiring di controller registrasi | Bayu | ✅ Selesai | Landing + Koordinator register |
| Git: Commit & push ke GitHub | Semua | ✅ Selesai | 65 files, 5.513 baris |

**✅ Deliverable**: Notifikasi WA + in-app berjalan, admin bisa atur template & broadcast

---

## 🚀 Fase 2: Core Training (Minggu 2) — ⏳ **BELUM DIMULAI**
*Modul inti pelatihan*

| Task | Anggota | Estimasi | Prioritas |
|------|---------|----------|-----------|
| Migrasi: modules, materials | Eka | 1 hari | 🔴 High |
| CRUD Modul/Bab pelatihan | Bayu, Ayu | 2 hari | 🔴 High |
| Upload materi (PDF, Video, Embed) | Rizky | 1 hari | 🔴 High |
| Halaman detail pelatihan + materi | Ayu | 1 hari | 🔴 High |
| Tampilkan materi per pertemuan | Dika | 1 hari | 🟡 Medium |

**✅ Deliverable**: Admin bisa buat pelatihan + materi, user bisa lihat

---

## 🚀 Fase 3: Enrollment & Peserta (Minggu 3) — ⏳ **BELUM DIMULAI**
*Manajemen peserta*

| Task | Anggota | Estimasi | Prioritas |
|------|---------|----------|-----------|
| Migrasi: enrollments, attendances | Eka | 1/2 hari | 🟡 Medium |
| Fitur pendaftaran pelatihan | Bayu | 1 hari | 🟡 Medium |
| Approve/Reject peserta pelatihan | Sari | 1 hari | 🟡 Medium |
| Absensi peserta | Dika | 1 hari | 🟡 Medium |
| Dashboard peserta (detail progress) | Ayu | 1 hari | 🟡 Medium |
| Dashboard instruktur (lengkap) | Ayu | 1 hari | 🟡 Medium |

**✅ Deliverable**: Peserta bisa daftar, absen, lihat progress

---

## 🚀 Fase 4: Tugas & Penilaian (Minggu 4) — ⏳ **BELUM DIMULAI**
*Evaluasi pembelajaran*

| Task | Anggota | Estimasi |
|------|---------|----------|
| Migrasi: assignments, questions, submissions | Eka | 1 hari |
| CRUD Tugas/Kuis | Bayu | 2 hari |
| Halaman kerjakan tugas (peserta) | Ayu, Dika | 2 hari |
| Penilaian & feedback (instruktur) | Bayu | 1 hari |
| Nilai otomatis (pilihan ganda) | Bayu | 1 hari |

**✅ Deliverable**: Tugas bisa dibuat, dikerjakan, dan dinilai

---

## 🚀 Fase 5: Sertifikat & Diskusi (Minggu 5) — ⏳ **BELUM DIMULAI**
*Fitur pendukung*

| Task | Anggota | Estimasi |
|------|---------|----------|
| Migrasi: certificates, discussions | Eka | 1/2 hari |
| Generate sertifikat PDF | Bayu, Rudi | 2 hari |
| Verifikasi sertifikat (QR code) | Rizky | 1 hari |
| Forum diskusi | Dika | 2 hari |
| Fitur komentar & balasan | Ayu, Bayu | 1 hari |

**✅ Deliverable**: Sertifikat terbit otomatis, forum diskusi aktif

---

## 🚀 Fase 6: Jadwal & Kalender (Minggu 6) — ⏳ **BELUM DIMULAI**
*Fitur pengingat & kalender*
> ⚠️ Notifikasi sudah selesai di Fase 1B, tersisa fitur kalender

| Task | Anggota | Estimasi |
|------|---------|----------|
| Kalender jadwal (FullCalendar) | Ayu | 2 hari |
| Jadwal pertemuan online/offline | Bayu | 1 hari |

**✅ Deliverable**: Jadwal terlihat di kalender

---

## 🚀 Fase 7: Polish & PWA (Minggu 7) — ⏳ **BELUM DIMULAI**
*Finishing & mobile*

| Task | Anggota | Estimasi |
|------|---------|----------|
| Setup PWA (Service Worker, manifest) | Tio | 2 hari |
| Export laporan (PDF, Excel) | Rudi | 1 hari |
| SEO optimization | Laras | 1 hari |
| Performance optimization | Nadia | 1 hari |
| Security audit | Hendra | 1 hari |
| Bug hunting | Nisa | 1 hari |
| Code review | Rian | 1 hari |
| Dokumentasi | Intan | 1 hari |

**✅ Deliverable**: Aplikasi siap rilis

---

## 🚀 Fase 8: Deployment (Minggu 8) — ⏳ **BELUM DIMULAI**
*Go live!*

| Task | Anggota | Estimasi |
|------|---------|----------|
| Setup Git & version control | Gilang | 1/2 hari |
| CI/CD pipeline | Gilang | 1 hari |
| Deployment production | Gilang | 1 hari |
| UAT (User Acceptance Test) | Farhan | 2 hari |
| Go Live! 🎉 | Semua | - |

**✅ Deliverable**: Aplikasi live!

---

## 📈 Ringkasan Timeline

| Fase | Minggu | Fokus | Status |
|-----|--------|-------|--------|
| **Fase 1** | **Minggu 1** | **Foundation (auth, role, setup)** | ✅ **SELESAI** |
| **Fase 1B** | **Minggu 1 Ekstensi** | **Sistem Notifikasi WA + Multi-Channel** | ✅ **SELESAI** |
| Fase 2 | Minggu 2 | Core Training (CRUD pelatihan, materi) | ⏳ Next |
| Fase 3 | Minggu 3 | Enrollment & Peserta | ⏳ |
| Fase 4 | Minggu 4 | Tugas & Penilaian | ⏳ |
| Fase 5 | Minggu 5 | Sertifikat & Diskusi | ⏳ |
| Fase 6 | Minggu 6 | Jadwal & Kalender | ⏳ |
| Fase 7 | Minggu 7 | Polish & PWA | ⏳ |
| Fase 8 | Minggu 8 | Deployment & Go Live | ⏳ |

**Progres: ~30%** 🎯

> **Catatan**: Fase 1 & 1B sudah selesai dengan fitur yang lebih banyak dari rencana awal
> (multi-step form, landing page, WA gateway, admin panel lengkap, sistem notifikasi).
> Siap lanjut ke Fase 2 (Manajemen Materi).
