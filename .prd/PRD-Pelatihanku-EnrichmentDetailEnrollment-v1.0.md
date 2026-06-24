# PRD: Enrichment Halaman Detail Enrollment (Lite)

**Versi:** v1.0  
**Tanggal:** 24 Juni 2026  
**Penulis:** Sophia (Project Manager)  
**Status:** Approved  
**Tipe:** Lite (Enhancement)  
**Estimasi:** 🔵 2 SP (Small) — ~1 hari  
**Quality Score:** 🟢 85/100 — Good  
**RICE:** 🟡 4.0 (Medium Priority)  

---

## 1. Executive Summary

Halaman detail enrollment (`/admin/enrollments/{id}`) saat ini hanya menampilkan data peserta dan pelatihan secara minimalis (2 kolom). Padahal admin sering perlu melihat **data lengkap peserta** (data pribadi, alamat, pendidikan, jawaban pertanyaan) langsung dari halaman enrollment tanpa harus membuka halaman peserta terpisah. Enhancement ini akan memperkaya halaman detail enrollment agar memiliki tampilan dan informasi selengkap halaman detail peserta (`/admin/peserta/{id}`).

---

## 2. Background & Problem

| Masalah | Dampak |
|---------|--------|
| Admin perlu bolak-balik antara halaman enrollment dan halaman peserta untuk melihat data lengkap peserta saat memproses pendaftaran | - Waktu proses approval/reject lebih lama<br>- Admin harus membuka 2 tab browser |
| Informasi peserta di halaman enrollment saat ini terbatas (Nama, Email, WA, NIK, Role) | Admin tidak bisa menilai kelayakan peserta tanpa data pendukung (pendidikan, pekerjaan, alamat, jawaban pertanyaan) |
| Progress pendaftaran peserta (6 tahap) tidak terlihat dari halaman enrollment | Admin tidak tahu apakah peserta sudah melengkapi semua data |

---

## 3. Goals & Success Metrics

| Goal | Metric | Target | Measurement |
|------|--------|--------|-------------|
| Admin bisa melihat data lengkap peserta dari halaman enrollment | Jumlah klik yang dihemat | Admin tidak perlu buka halaman peserta terpisah | Tracking session / feedback |
| Approval process lebih efisien | Waktu proses per enrollment | Turun 30% | Log aktivitas |
| UI konsisten dengan halaman detail peserta | Kemiripan layout & informasi | 90%+ elemen yang sama | Review visual |

---

## 4. User Personas

| Role | Deskripsi | Pain Point | Goal |
|------|-----------|------------|------|
| **Admin Pelatihan** | Admin yang memproses pendaftaran masuk (approve/reject/waitlist) | Harus buka 2 tab untuk lihat data lengkap peserta | Bisa approve/reject dengan data lengkap dari 1 halaman |

---

## 5. User Stories

- [ ] **US-001** — Sebagai **admin**, saya ingin melihat **data pribadi lengkap peserta** (NIK, WhatsApp, Email, Jenis Kelamin, Tempat/Tgl Lahir, Link Medsos) di halaman detail enrollment, sehingga saya tidak perlu membuka halaman peserta terpisah.
- [ ] **US-002** — Sebagai **admin**, saya ingin melihat **alamat lengkap peserta** (Alamat KTP, RT, RW, Kodepos, Kelurahan, Kecamatan, Kota, Provinsi) di halaman detail enrollment.
- [ ] **US-003** — Sebagai **admin**, saya ingin melihat **riwayat pendidikan & pekerjaan** peserta (Pendidikan Terakhir, Institusi, Jurusan, Tahun Lulus, Status Pekerjaan, Perusahaan).
- [ ] **US-004** — Sebagai **admin**, saya ingin melihat **progress pendaftaran** peserta (timeline 6 tahap) di sidebar halaman enrollment.
- [ ] **US-005** — Sebagai **admin**, saya ingin tetap bisa melakukan **aksi** (Approve/Waitlist/Tolak/Promote/Reset) dari halaman detail enrollment.

---

## 6. Functional Requirements

### 6.1 Layout & Struktur Halaman

- **FR-001:** Ubah layout dari 2 kolom (6+6) menjadi 2 kolom dengan proporsi (8+4) — konten utama di kiri, sidebar di kanan (sama seperti halaman detail peserta).
- **FR-002:** Gunakan komponen gaya yang sama dengan halaman detail peserta (glass-card-premium, detail-label, detail-value, glow orbs, dll).

### 6.2 Konten Utama (Kolom Kiri — 8 kolom)

- **FR-003:** **Data Pribadi** — Tampilkan: Nama Lengkap, NIK, WhatsApp, Email, Jenis Kelamin, Tempat & Tanggal Lahir, Link Medsos (dengan icon platform).
- **FR-004:** **Alamat** — Tampilkan: Alamat KTP, RT, RW, Kodepos, Kelurahan, Kecamatan, Kota, Provinsi.
- **FR-005:** **Pendidikan & Pekerjaan** — Tampilkan: Pendidikan Terakhir, Nama Institusi, Jurusan, Tahun Lulus, Status Pekerjaan, Nama Perusahaan.
- **FR-006:** **Data Pelatihan** — Tampilkan: Nama Pelatihan, Batch, Dinas Penyelenggara, Deskripsi Pelatihan.
- **FR-007:** **Jawaban Pertanyaan (Tahap 5)** — Tampilkan seluruh jawaban pertanyaan peserta dengan label mapping yang sama seperti di halaman detail peserta.
- **FR-008:** **Informasi Lainnya** — Tampilkan: Tanggal Daftar, Status Aktif/Nonaktif peserta.

### 6.3 Sidebar (Kolom Kanan — 4 kolom)

- **FR-009:** **Status Pendaftaran** — Tampilkan status enrollment saat ini (Pending/Approved/Ditolak/Cadangan) dalam bentuk badge.
- **FR-010:** **Timeline Pendaftaran** — Tampilkan timeline: Tanggal Daftar, Tanggal Approve, Tanggal Ditolak, Dipromosikan dari Cadangan, Catatan (sama seperti yang sudah ada).
- **FR-011:** **Progress Pendaftaran Peserta** — Tampilkan timeline 6 tahap progres pendaftaran (Data Pribadi → Alamat & Kontak → Riwayat Pendidikan → Pilihan Pelatihan → Dokumen & Pertanyaan → Review & Kirim) dengan status completed/pending.
- **FR-012:** **Aksi** — Tampilkan tombol aksi sesuai status enrollment (Approve/Waitlist/Tolak untuk pending; Promosikan/Tolak untuk waitlist; Reset untuk approved; tanpa aksi untuk rejected).

### 6.4 Data yang Di-load Controller

- **FR-013:** Controller `show()` harus me-load relasi tambahan: `user.kecamatan`, `user.kelurahan`, `user.pesertaProfile` (sudah ada).

---

## 7. Non-Functional Requirements

- **NFR-001:** Tampilan harus konsisten dengan halaman detail peserta (warna, font, glass effect, badge).
- **NFR-002:** Response time tidak bertambah signifikan (max +200ms dari saat ini).
- **NFR-003:** Semua fallback untuk data nullable/kosong menggunakan tanda `-` (sama seperti di detail peserta).

---

## 8. User Flow

```
1. Admin membuka halaman /admin/enrollments
2. Admin klik tombol "Detail" pada salah satu enrollment
3. Halaman detail enrollment terbuka dengan layout baru:
   - Header: Nama Peserta — Nama Pelatihan + Status Badge + Tombol Kembali
   - Kolom Kiri (8): Data Pribadi, Alamat, Pendidikan & Pekerjaan, Data Pelatihan, Jawaban Pertanyaan, Informasi Lainnya
   - Kolom Kanan (4): Status Enrollment, Timeline Pendaftaran, Progress Pendaftaran (6 tahap), Aksi
4. Admin dapat melakukan aksi (Approve/Waitlist/Tolak) dari sidebar
5. Admin klik "Kembali" untuk kembali ke daftar enrollment
```

---

## 9. Business Rules & Validation

- Data peserta yang ditampilkan berasal dari relasi `enrollment->user->pesertaProfile`, `enrollment->user->kecamatan`, `enrollment->user->kelurahan`.
- Jika `pesertaProfile` null, tampilkan `-` untuk semua field.
- Status enrollment menentukan aksi yang ditampilkan (sama seperti yang sudah ada).
- Jawaban pertanyaan menggunakan field labels yang sama dengan halaman detail peserta.

---

## 10. Data Requirements

**Tidak ada tabel/field baru.** Enhancement ini hanya mengubah tampilan dan query controller.

Data yang diperlukan (semua sudah ada):
| Data | Sumber |
|------|--------|
| Data Pribadi | User + PesertaProfile |
| Alamat | PesertaProfile + relasi kecamatan/kelurahan |
| Pendidikan & Pekerjaan | PesertaProfile |
| Data Pelatihan | Pelatihan (via enrollment) |
| Jawaban Pertanyaan | PesertaProfile->jawaban_pertanyaan (JSON) |
| Progress 6 Tahap | PesertaProfile (berdasarkan isian) |
| Timeline Enrollment | Enrollment (created_at, approved_at, dll) |
| Status Enrollment | Enrollment->status |

---

## 11. Integration & Dependencies

- **Controller:** `Admin\EnrollmentController@show` — perlu update query load relasi.
- **View:** `content.admin.enrollments.show` — rewrite total (referensi dari `content.admin.peserta.show`).
- **No new database migration needed.**
- **No new routes needed.**

---

## 12. Acceptance Criteria

- [ ] **AC-001:** Halaman detail enrollment menampilkan Data Pribadi lengkap (Nama, NIK, WhatsApp, Email, Jenis Kelamin, Tempat/Tgl Lahir, Link Medsos).
- [ ] **AC-002:** Halaman menampilkan Alamat lengkap (Alamat KTP, RT, RW, Kodepos, Kelurahan, Kecamatan, Kota, Provinsi).
- [ ] **AC-003:** Halaman menampilkan Pendidikan & Pekerjaan (Pendidikan Terakhir, Institusi, Jurusan, Tahun Lulus, Status Pekerjaan, Perusahaan).
- [ ] **AC-004:** Halaman menampilkan Data Pelatihan (Nama, Batch, Dinas, Deskripsi).
- [ ] **AC-005:** Halaman menampilkan Jawaban Pertanyaan dengan label yang benar (jika ada), atau pesan "Belum ada jawaban pertanyaan" (jika kosong).
- [ ] **AC-006:** Halaman menampilkan Informasi Lainnya (Tanggal Daftar, Status Aktif).
- [ ] **AC-007:** Sidebar menampilkan Status Enrollment (badge sesuai status).
- [ ] **AC-008:** Sidebar menampilkan Timeline Pendaftaran (Tanggal Daftar, Approve, Ditolak, Promosi, Catatan).
- [ ] **AC-009:** Sidebar menampilkan Progress Pendaftaran 6 tahap dengan status completed/pending.
- [ ] **AC-010:** Sidebar menampilkan Aksi sesuai status (sama seperti di halaman detail peserta).
- [ ] **AC-011:** Semua field yang kosong/null menampilkan `-`.
- [ ] **AC-012:** Tombol Kembali mengarah ke `admin.enrollments.index`.
- [ ] **AC-013:** Tampilan konsisten dengan halaman detail peserta (glass effect, font, warna).

---

## 13. Out of Scope

- Menambahkan tabel/field database baru.
- Menambahkan fitur edit data dari halaman enrollment.
- Menambahkan fitur export dari halaman detail.
- Mengubah halaman index enrollment.
- Menambahkan fitur baru selain yang sudah ada di halaman detail peserta.

---

## 14. Open Questions

Tidak ada — semua requirement sudah clear dari referensi halaman detail peserta.

---

## 15. Risks & Mitigation

| Risk | Impact | Likelihood | Mitigation |
|------|--------|------------|------------|
| View jadi terlalu panjang (seperti detail peserta yang 887 baris) | Maintenance lebih berat | Medium | Bisa di-refactor dengan partial views jika perlu |
| Perubahan query controller menambah response time | Performa turun | Low | Hanya nambah 2-3 relasi (eager loading sudah benar) |

---

## 16. Revision History

| Versi | Tanggal | Perubahan | Penulis |
|-------|---------|-----------|---------|
| v1.0 | 24 Juni 2026 | Dokumen awal | Sophia (PM) |

---

## ⚡ RICE Score Calculation

| Komponen | Nilai | Keterangan |
|----------|:-----:|------------|
| **Reach** | 3 | Admin (1-5 orang per hari) |
| **Impact** | 1 | Meningkatkan efisiensi, tetapi tidak mengubah flow bisnis |
| **Confidence** | 80% | Yakin karena tinggal copy pattern dari halaman existing |
| **Effort** | 2 SP | ~1 hari kerja |

**RICE Score = (3 × 1 × 80%) / 2 = 1.2 → 🟡 Medium Priority**

---

## ⚡ Effort Estimation Breakdown

| Faktor | Bobot | Alasan |
|--------|:-----:|--------|
| Halaman baru | 0 | Hanya mengubah view existing |
| User roles | 0 | Tetap untuk admin |
| Tabel DB baru | 0 | Tidak ada |
| Integrasi eksternal | 0 | Tidak ada |
| Flow/logic complexity | 2 | Update controller query + rewrite view (referensi existing) |
| **Total** | **2 SP** | **🔵 Small — ~1 hari** |
