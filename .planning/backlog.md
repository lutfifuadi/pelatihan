# 📋 Backlog Fitur — Aplikasi Pelatihan

> **Dibuat**: 12 Juni 2026 | **Update**: 12 Juni 2026
> **Status**: Foundation (Fase 1) ✅ Selesai — 24 fitur tersisa di 7 fase ke depan
> 
> ✅ **Fitur Baru Selesai**: Halaman Daftar Peserta per Pelatihan (lihat halaman 3)

---

## 📊 Ringkasan

| Fase | Nama | Jumlah Task | Prioritas | Status |
|------|------|-------------|-----------|--------|
| Fase 2 | Core Training (Manajemen Materi) | 5 task | 🔴 **High** | ⏳ Belum |
| Fase 3 | Enrollment & Peserta | 6 task | 🟡 Medium | ⏳ Belum |
| Fase 4 | Tugas & Penilaian | 5 task | 🟡 Medium | ⏳ Belum |
| Fase 5 | Sertifikat & Diskusi | 4 task | 🟢 Low | ⏳ Belum |
| Fase 6 | Notifikasi & Jadwal | 4 task | 🟢 Low | ⏳ Belum |
| Fase 7 | Polish & PWA | 7 task | 🟢 Low | ⏳ Belum |
| Fase 8 | Deployment & Go Live | 5 task | 🟢 Low | ⏳ Belum |
| | **TOTAL** | **36 task** | | |

---

## ✅ FITUR BARU SELESAI — 12 Juni 2026

> **Dikerjakan oleh**: Dika (Fullstack)

| # | Fitur | Detail | Yang Dibuat |
|---|-------|--------|-------------|
| ✅ | **Daftar Peserta per Pelatihan** | Lihat siapa saja peserta yang ikut pelatihan, untuk laporan, follow up, & persiapan hari H | • Relasi `pesertaProfiles` di Model Pelatihan<br>• Method `show()` di Admin PelatihanController<br>• Halaman `/admin/pelatihan/{id}/peserta`<br>• Tabel: Nama, NIK, WA, Email, Kecamatan, Status<br>• Tombol "Salin Semua WA" (Clipboard API)<br>• 4 Info Cards (Total, Lengkap, Kuota, Tanggal)<br>• Tombol "Export Excel" (placeholder)<br>• Sidebar: submenu Daftar & Tambah Pelatihan |

---

## 🟢 FASE 2 (Baru): Approve/Reject — Absensi — Sertifikat ✅ SELESAI

> Sesuai arahan Mas Lutfi, pelatihan bersifat offline. Fokus ke manajemen peserta, bukan materi online.

### ✅ Fitur 1: Approve/Reject + Waiting List
| # | Fitur | Detail | Status |
|---|-------|--------|--------|
| ✅ | **Migration enrollments** | user_id, pelatihan_id, status (pending/approved/rejected/waitlist) | ✅ Selesai |
| ✅ | **Model Enrollment + Relasi** | User & Pelatihan hasMany Enrollment | ✅ Selesai |
| ✅ | **EnrollmentController** | approve, reject, waitlist, promote | ✅ Selesai |
| ✅ | **View Admin** | Index dengan filter, stat cards, modal reject | ✅ Selesai |
| ✅ | **Auto-promote waitlist** | Saat reject, otomatis promosikan dari cadangan jika ada slot | ✅ Selesai |

### ✅ Fitur 2: Absensi Kehadiran
| # | Fitur | Detail | Status |
|---|-------|--------|--------|
| ✅ | **Migration attendances** | enrollment_id, pertemuan_ke, status (hadir/sakit/izin/alpa), date | ✅ Selesai |
| ✅ | **Model Attendance** | Relasi ke Enrollment | ✅ Selesai |
| ✅ | **AttendanceController** | index (form absensi), store, rapport (rekap) | ✅ Selesai |
| ✅ | **View Form Absensi** | Alpine.js status toggle, set all hadir/alpa | ✅ Selesai |
| ✅ | **View Rekapitulasi** | Tabel peserta × pertemuan, persentase kehadiran | ✅ Selesai |

### ✅ Fitur 3: Sertifikat PDF + Verifikasi
| # | Fitur | Detail | Status |
|---|-------|--------|--------|
| ✅ | **Install DomPDF** | barryvdh/laravel-dompdf v3.1 | ✅ Selesai |
| ✅ | **Migration certificates** | enrollment_id, certificate_number (unique), file_path | ✅ Selesai |
| ✅ | **CertificateController** | generate, batch, download, verify | ✅ Selesai |
| ✅ | **Template PDF Sertifikat** | Dark theme gold certificate | ✅ Selesai |
| ✅ | **Halaman Verifikasi Publik** | /verifikasi-sertifikat?nomor=xxx | ✅ Selesai |

### ✅ Notifikasi WA Terintegrasi
| # | Event | Template |
|---|-------|----------|
| ✅ | PendaftaranApproved | pendaftaran_diterima |
| ✅ | PendaftaranRejected (baru) | pendaftaran_ditolak |
| ✅ | SertifikatDiterbitkan (baru) | kelulusan |

### 📦 Database Baru Diperlukan
| Tabel | Kolom Utama |
|-------|-------------|
| `training_modules` | id, pelatihan_id, title, description, order, is_active |
| `materials` | id, module_id, type (pdf/video/embed), title, file_path, url, order, duration |

---

## 🟡 FASE 3: Enrollment & Peserta

| # | Fitur | Detail | Delegasi | Estimasi |
|---|-------|--------|----------|----------|
| 3.1 | **Migration: enrollments, attendances** | Tabel enrollment dan absensi | Eka | ½ hari |
| 3.2 | **Fitur Pendaftaran Pelatihan** | Peserta bisa daftar ke pelatihan tertentu | Bayu | 1 hari |
| 3.3 | **Approve/Reject Peserta Pelatihan** | Admin/koordinator setujui/tolak pendaftaran | Sari | 1 hari |
| 3.4 | **Absensi / Kehadiran Peserta** | Instruktur bisa absen peserta (scan QR/manual) | Dika | 1 hari |
| 3.5 | **Dashboard Peserta (Progress Detail)** | Progress belajar, modul selesai, nilai | Ayu | 1 hari |
| 3.6 | **Dashboard Instruktur (Lengkap)** | Daftar peserta, jadwal mengajar, rekap absen | Ayu | 1 hari |

### 📦 Database Baru Diperlukan
| Tabel | Kolom Utama |
|-------|-------------|
| `enrollments` | id, user_id, pelatihan_id, status (pending/approved/rejected), approved_at |
| `attendances` | id, enrollment_id, pertemuan_ke, status (hadir/sakit/izin/alpa), date |

---

## 🟡 FASE 4: Tugas & Penilaian

| # | Fitur | Detail | Delegasi | Estimasi |
|---|-------|--------|----------|----------|
| 4.1 | **Migration: assignments, questions, submissions** | Tabel tugas, soal, dan jawaban | Eka | 1 hari |
| 4.2 | **CRUD Tugas / Kuis** | Buat tugas, soal pilihan ganda, essay, upload file | Bayu | 2 hari |
| 4.3 | **Halaman Kerjakan Tugas (Peserta)** | Peserta jawab soal, upload jawaban | Ayu + Dika | 2 hari |
| 4.4 | **Penilaian & Feedback (Instruktur)** | Instruktur nilai essay, beri feedback | Bayu | 1 hari |
| 4.5 | **Nilai Otomatis (Pilihan Ganda)** | Sistem koreksi otomatis untuk PG | Bayu | 1 hari |

### 📦 Database Baru Diperlukan
| Tabel | Kolom Utama |
|-------|-------------|
| `assignments` | id, module_id, title, type (tugas/kuis), deadline, passing_grade |
| `assignment_questions` | id, assignment_id, type (pg/essay/upload), question, options (json), correct_answer |
| `submissions` | id, assignment_id, user_id, answers (json), score, feedback, submitted_at |

---

## 🟢 FASE 5: Sertifikat & Diskusi

| # | Fitur | Detail | Delegasi | Estimasi |
|---|-------|--------|----------|----------|
| 5.1 | **Migration: certificates, discussions** | Tabel sertifikat dan diskusi | Eka | ½ hari |
| 5.2 | **Generate Sertifikat PDF** | Cetak sertifikat otomatis setelah lulus | Bayu + Rudi | 2 hari |
| 5.3 | **Verifikasi Sertifikat (QR Code)** | QR code untuk verifikasi keaslian sertifikat | Rizky | 1 hari |
| 5.4 | **Forum Diskusi per Pelatihan** | Tanya jawab, komentar, diskusi antar peserta & instruktur | Dika | 2 hari |

### 📦 Database Baru Diperlukan
| Tabel | Kolom Utama |
|-------|-------------|
| `certificates` | id, enrollment_id, certificate_number (unique), issued_at, file_path, qr_code |
| `discussions` | id, pelatihan_id, user_id, title, body, pinned |
| `discussion_comments` | id, discussion_id, user_id, body, parent_id (nullable) |

---

## 🟢 FASE 6: Notifikasi & Jadwal

| # | Fitur | Detail | Delegasi | Estimasi |
|---|-------|--------|----------|----------|
| 6.1 | **Migration: notifications, schedules** | Tabel notifikasi dan jadwal | Eka | ½ hari |
| 6.2 | **Notifikasi In-app (Bell Icon)** | Notifikasi di pojok kanan atas, daftar notifikasi | Dika | 1 hari |
| 6.3 | **Notifikasi Email** | Email otomatis (pendaftaran, pengingat, kelulusan) | Bayu + Rizky | 1 hari |
| 6.4 | **Kalender Jadwal (FullCalendar)** | Tampilan kalender interaktif untuk jadwal pelatihan | Ayu | 2 hari |

### 📦 Database Baru Diperlukan
| Tabel | Kolom Utama |
|-------|-------------|
| `notifications` | id, user_id, type, title, body, is_read, link, data (json) |
| `schedules` | id, pelatihan_id, title, description, start_date, end_date, location, type (online/offline) |

---

## 🟢 FASE 7: Polish & PWA

| # | Fitur | Detail | Delegasi | Estimasi |
|---|-------|--------|----------|----------|
| 7.1 | **PWA (Progressive Web App)** | Service worker, manifest, installable, offline support | Tio | 2 hari |
| 7.2 | **Export Laporan (PDF, Excel)** | Export data peserta, absensi, nilai ke PDF/Excel | Rudi | 1 hari |
| 7.3 | **Multi Bahasa (Indonesia + Inggris)** | Konten siap, tinggalisi file terjemahan | Dika + Intan | 2 hari |
| 7.4 | **SEO Optimization** | Meta tags, sitemap, structured data, canonical URL | Laras | 1 hari |
| 7.5 | **Performance Optimization** | Caching, lazy loading, minifikasi asset, optimasi query | Nadia | 1 hari |
| 7.6 | **Security Audit** | OWASP scan, XSS/SQLi/CSRF protection review | Hendra | 1 hari |
| 7.7 | **Bug Hunting** | Cari dan dokumentasikan bug di seluruh sistem | Nisa | 1 hari |

### ⚡ Pra-Fase 7 (Quick Wins)
| Fitur | Delegasi | Estimasi |
|-------|----------|----------|
| Code Review & Refactoring | Rian | 1 hari |
| Dokumentasi API & Panduan | Intan | 1 hari |
| Forgot / Reset Password (uji coba) | Farhan | ½ hari |
| Log Aktivitas Admin | Bayu + Sari | 1 hari |
| Backup Data Otomatis | Rudi | ½ hari |

---

## 🟢 FASE 8: Deployment & Go Live

| # | Fitur | Detail | Delegasi | Estimasi |
|---|-------|--------|----------|----------|
| 8.1 | **Setup Git & Version Control** | Branching strategy, gitflow | Gilang | ½ hari |
| 8.2 | **CI/CD Pipeline** | Automated testing & deployment | Gilang | 1 hari |
| 8.3 | **Deployment Production** | Deploy ke server produksi | Gilang | 1 hari |
| 8.4 | **UAT (User Acceptance Test)** | Pengujian dengan user nyata | Farhan | 2 hari |
| 8.5 | **Go Live! 🎉** | Monitoring & support awal | Semua | - |

---

## 🔗 Referensi

- Rencana detail per fase: `.planing/timeline.md`
- Spesifikasi fitur lengkap: `.planing/fitur.md`
- Perancangan database: `.planing/database.md`
- Target harian: `.planing/hari-ini.md`

---

> **Catatan**: Backlog ini akan diupdate setiap kali ada progres pengerjaan fitur.
> Prioritas bisa berubah sesuai arahan user (Kak).
