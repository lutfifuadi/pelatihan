# PRD: Export Excel Enrollment Data Lengkap + Format Text (Lite)

**Versi:** v1.0
**Tanggal:** 24 Juni 2026
**Penulis:** Sophia (Project Manager)
**Status:** Draft
**Tipe:** Lite (Enhancement)
**Estimasi:** 🔵 3 SP (Small) — ~1.5 hari
**Quality Score:** 🟢 78/100 — Good
**RICE:** 🟡 3.75 (Medium Priority)

---

## 1. Executive Summary

Fitur export Excel enrollment (`/admin/enrollments/excel`) saat ini hanya mengekspor 8 kolom terbatas (Nama, NIK, WhatsApp, Email, Pelatihan, Status, Tanggal Daftar, Tanggal Approve/Reject). Enhancement ini akan memperkaya data export dengan **seluruh data yang diisi peserta selama 6 tahap pendaftaran** (data pribadi, alamat, pendidikan & pekerjaan, data pelatihan, jawaban pertanyaan) serta memastikan **semua kolom di Excel berformat Text** (bukan Number/Date/General).

---

## 2. Background & Problem

| Masalah | Dampak |
|---------|--------|
| Export Excel hanya 8 kolom — data peserta tidak lengkap | Admin harus melakukan export manual atau copy-paste dari halaman detail satu per satu untuk mendapatkan data lengkap |
| Format sel Excel masih berupa General/Date/Number (misal NIK berubah jadi eksponensial, tanggal jadi angka serial) | Data tidak siap pakai untuk rekap/laporan — NIK, WhatsApp, Kodepos terpotong atau berubah format |
| Jawaban pertanyaan (Tahap 5) tidak ikut di-export | Admin kehilangan data kualitatif penting untuk evaluasi peserta |

---

## 3. Goals & Success Metrics

| Goal | Metric | Target | Measurement |
|------|--------|--------|-------------|
| Semua data tahapan peserta tersedia di export Excel | Jumlah kolom export | Minimal 25+ kolom (dari 8 kolom sebelumnya) | Hitung kolom di file Excel |
| Data NIK, WhatsApp, Kodepos tidak terpotong/berubah | Format kolom | Semua kolom = Text | Buka Excel, cek format cell |
| Jawaban pertanyaan ikut di-export | Jawaban muncul | Semua field jawaban pertanyaan ada di export | Cek kolom jawaban di Excel |

---

## 4. User Personas

| Role | Deskripsi | Pain Point | Goal |
|------|-----------|------------|------|
| **Admin Pelatihan** | Admin yang mengelola pendaftaran dan perlu rekap data peserta | Export sekarang tidak lengkap, harus manual | Satu klik dapat data lengkap siap pakai |
| **Kepala Dinas / Pimpinan** | Pimpinan yang minta laporan data peserta | Data tidak rapi (NIK error, format kacau) | Menerima file Excel yang rapi dan benar |

---

## 5. User Stories

- [ ] **US-001** — Sebagai **admin**, saya ingin **semua data peserta dari Tahap 1 (Data Pribadi)** ikut di-export (Nama, NIK, WhatsApp, Email, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Bulan Lahir, Tahun Lahir, Link Medsos), sehingga saya punya data pribadi lengkap.
- [ ] **US-002** — Sebagai **admin**, saya ingin **semua data dari Tahap 2 (Alamat & Kontak)** ikut di-export (Alamat KTP, RT, RW, Kelurahan, Kecamatan, Kota, Provinsi, Kodepos), sehingga saya punya data alamat lengkap.
- [ ] **US-003** — Sebagai **admin**, saya ingin **semua data dari Tahap 3 (Pendidikan & Pekerjaan)** ikut di-export (Pendidikan Terakhir, Nama Institusi, Jurusan, Tahun Lulus, Status Pekerjaan, Nama Perusahaan), sehingga saya bisa menganalisis latar belakang peserta.
- [ ] **US-004** — Sebagai **admin**, saya ingin **data pelatihan lengkap** (Nama Pelatihan, Batch, Dinas Penyelenggara) ikut di-export.
- [ ] **US-005** — Sebagai **admin**, saya ingin **jawaban pertanyaan (Tahap 5)** ikut di-export dalam kolom terpisah per pertanyaan, sehingga saya bisa melihat alasan dan motivasi peserta.
- [ ] **US-006** — Sebagai **admin**, saya ingin **semua kolom di file Excel berformat Text**, sehingga NIK, WhatsApp, Kodepos, dan tanggal tidak berubah/terpotong.

---

## 6. Functional Requirements

### 6.1 Data Export — Semua Tahapan

Kolom export (minimal):

**Tahap 1 — Data Pribadi (dari User + PesertaProfile):**
- **FR-001:** Nama Peserta (`user.name`)
- **FR-002:** NIK (`user.nik`)
- **FR-003:** WhatsApp (`user.whatsapp`)
- **FR-004:** Email (`user.email`)
- **FR-005:** Jenis Kelamin (`pesertaProfile.jenis_kelamin`)
- **FR-006:** Tempat Lahir (`pesertaProfile.tempat_lahir`)
- **FR-007:** Tanggal Lahir (`pesertaProfile.tanggal_lahir`)
- **FR-008:** Bulan Lahir (`pesertaProfile.bulan_lahir`)
- **FR-009:** Tahun Lahir (`pesertaProfile.tahun_lahir`)
- **FR-010:** Link Medsos (`pesertaProfile.link_medsos` — digabung jadi string)

**Tahap 2 — Alamat & Kontak (dari PesertaProfile):**
- **FR-011:** Alamat KTP (`pesertaProfile.alamat_ktp`)
- **FR-012:** RT (`pesertaProfile.rt`)
- **FR-013:** RW (`pesertaProfile.rw`)
- **FR-014:** Kelurahan (`pesertaProfile.kelurahan` atau relasi `user.kelurahan.name`)
- **FR-015:** Kecamatan (`pesertaProfile.kecamatan` atau relasi `user.kecamatan.name`)
- **FR-016:** Kota (`pesertaProfile.kota`)
- **FR-017:** Provinsi (`pesertaProfile.provinsi`)
- **FR-018:** Kodepos (`pesertaProfile.kodepos`)

**Tahap 3 — Pendidikan & Pekerjaan (dari PesertaProfile):**
- **FR-019:** Pendidikan Terakhir (`pesertaProfile.pendidikan_terakhir`)
- **FR-020:** Nama Institusi (`pesertaProfile.nama_institusi`)
- **FR-021:** Jurusan (`pesertaProfile.jurusan`)
- **FR-022:** Tahun Lulus (`pesertaProfile.tahun_lulus`)
- **FR-023:** Status Pekerjaan (`pesertaProfile.status_pekerjaan`)
- **FR-024:** Nama Perusahaan (`pesertaProfile.nama_perusahaan`)

**Tahap 4 — Data Pelatihan (dari Enrollment + Pelatihan):**
- **FR-025:** Nama Pelatihan (`pelatihan.nama`)
- **FR-026:** Batch (`pelatihan.batch`)
- **FR-027:** Dinas Penyelenggara (`pelatihan.dinas.nama_dinas`)

**Tahap 5 — Jawaban Pertanyaan (dari PesertaProfile.jawaban_pertanyaan — JSON):**
- **FR-028:** Setiap field dalam `jawaban_pertanyaan` menjadi kolom terpisah (dinamis sesuai isi). Contoh field: Pengetahuan tentang Asep, Alasan Pelatihan, Pengalaman Bisnis, Rencana Setelah Pelatihan, Punya Usaha, Jenis Usaha, dll.

**Data Enrollment:**
- **FR-029:** Status Pendaftaran (`enrollment.status`)
- **FR-030:** Tanggal Daftar (`enrollment.created_at`)
- **FR-031:** Tanggal Approve (`enrollment.approved_at`)
- **FR-032:** Tanggal Ditolak (`enrollment.rejected_at`)
- **FR-033:** Tanggal Promosi dari Cadangan (`enrollment.waitlist_promoted_at`)
- **FR-034:** Catatan (`enrollment.notes`)

### 6.2 Format Text (Excel)

- **FR-035:** Semua kolom di file Excel harus berformat **Text** (bukan General/Number/Date).
- **FR-036:** Implementasi menggunakan `Maatanwebsite\Excel` dengan `WithCustomValueBinder` atau `WithColumnFormatting` untuk memastikan setiap kolom diformat sebagai `\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT`.

### 6.3 Query & Relasi

- **FR-037:** Update query di `EnrollmentExport.php` untuk me-load relasi: `user.pesertaProfile`, `user.kecamatan`, `user.kelurahan`, `pelatihan.dinas`.
- **FR-038:** Data peserta yang tidak memiliki `pesertaProfile` tetap di-export dengan nilai `-`.

---

## 7. Non-Functional Requirements

- **NFR-001:** Performa — Export dengan 1000 enrollment harus selesai dalam < 10 detik.
- **NFR-002:** Semua fallback untuk data nullable/kosong menggunakan tanda `-`.
- **NFR-003:** File yang dihasilkan harus kompatibel dengan Microsoft Excel (.xlsx).
- **NFR-004:** Tidak ada perubahan pada halaman UI atau routing yang sudah ada.

---

## 8. User Flow

```
1. Admin membuka halaman /admin/enrollments
2. Admin memilih filter pelatihan (opsional) dan/atau status (opsional)
3. Admin klik tombol "Export Excel" (yang sudah ada)
4. Browser mendownload file .xlsx
5. Admin buka file Excel — semua kolom berformat Text, data lengkap dari semua tahapan
```

Tidak ada perubahan pada UI — hanya enhancement pada file export.

---

## 9. Business Rules & Validation

- Jika peserta tidak memiliki `pesertaProfile`, semua field profile diisi `-`.
- `jawaban_pertanyaan` adalah JSON — setiap key menjadi kolom terpisah di Excel.
- `link_medsos` adalah JSON array — digabung menjadi string dengan format: "Platform: url" dipisah koma.
- Semua timestamp (created_at, approved_at, dll) di-export sebagai string teks (bukan serial date Excel).
- NIK, WhatsApp, Kodepos harus tetap utuh sebagai text (tidak pakai scientific notation).

---

## 10. Data Requirements

**Tidak ada tabel/field database baru** — semua data sudah ada.

Data yang diperlukan:
| Data | Sumber | Status |
|------|--------|--------|
| Data Pribadi | User + PesertaProfile | Existing |
| Alamat | PesertaProfile + relasi kecamatan/kelurahan | Existing |
| Pendidikan & Pekerjaan | PesertaProfile | Existing |
| Data Pelatihan | Pelatihan + Dinas (via enrollment) | Existing |
| Jawaban Pertanyaan | PesertaProfile->jawaban_pertanyaan (JSON) | Existing |
| Timeline Enrollment | Enrollment (created_at, approved_at, dll) | Existing |
| Status Enrollment | Enrollment->status | Existing |

Tambahan relasi yang perlu di-load:
- `user.pesertaProfile`
- `user.kecamatan`
- `user.kelurahan`
- `pelatihan.dinas`

---

## 11. Integration & Dependencies

- **Export Class:** `App\Exports\EnrollmentExport` — rewrite total (mapping, headings, collection).
- **Controller:** `Admin\ExportController@exportEnrollmentsExcel` — tidak perlu perubahan (sudah generic).
- **Library:** Maatwebsite/Laravel-Excel (existing).
- **Format Text:** Perlu implementasi `WithCustomValueBinder` atau `WithColumnFormatting`.
- **No new database migration needed.**
- **No new routes needed.**
- **No UI changes needed.**

---

## 12. Acceptance Criteria

- [ ] **AC-001:** File Excel yang didownload memiliki kolom Data Pribadi lengkap (Nama, NIK, WhatsApp, Email, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, Bulan Lahir, Tahun Lahir, Link Medsos).
- [ ] **AC-002:** File Excel memiliki kolom Alamat lengkap (Alamat KTP, RT, RW, Kelurahan, Kecamatan, Kota, Provinsi, Kodepos).
- [ ] **AC-003:** File Excel memiliki kolom Pendidikan & Pekerjaan (Pendidikan Terakhir, Institusi, Jurusan, Tahun Lulus, Status Pekerjaan, Perusahaan).
- [ ] **AC-004:** File Excel memiliki kolom Data Pelatihan (Nama Pelatihan, Batch, Dinas Penyelenggara).
- [ ] **AC-005:** File Excel memiliki kolom Jawaban Pertanyaan (setiap field dalam JSON `jawaban_pertanyaan` menjadi kolom terpisah).
- [ ] **AC-006:** File Excel memiliki kolom Enrollment (Status, Tanggal Daftar, Tanggal Approve, Tanggal Ditolak, Tanggal Promosi, Catatan).
- [ ] **AC-007:** Semua kolom di Excel berformat **Text** — NIK, WhatsApp, Kodepos tidak berubah jadi scientific notation.
- [ ] **AC-008:** Data peserta tanpa `pesertaProfile` tetap muncul dengan nilai `-`.
- [ ] **AC-009:** Filter pelatihan dan status tetap berfungsi (tidak rusak).
- [ ] **AC-010:** File bisa dibuka di Microsoft Excel tanpa error.

---

## 13. Out of Scope

- Export PDF — tetap menggunakan format yang sudah ada (tidak diubah).
- Export per halaman/pagination — export tetap semua data (sesuai filter).
- Menambahkan fitur export baru selain Excel.
- Mengubah UI/tampilan halaman enrollment.
- Menambahkan fitur kustomisasi kolom export oleh user.
- Menambahkan field database baru.

---

## 14. Open Questions

Tidak ada — semua requirement sudah clear.

---

## 15. Risks & Mitigation

| Risk | Impact | Likelihood | Mitigation |
|------|--------|------------|------------|
| Jumlah kolom banyak (~35+ kolom) membuat file lebar | User experience kurang nyaman | Medium | Prioritaskan kolom penting di awal; gunakan auto-size |
| Query jadi lebih berat karena load banyak relasi | Performa export lambat | Low | Pastikan eager loading, indexed foreign keys |
| JSON `jawaban_pertanyaan` strukturnya berbeda tiap peserta | Kolom tidak konsisten | Medium | Deteksi semua key dari semua record, union jadi kolom lengkap |

---

## 16. Revision History

| Versi | Tanggal | Perubahan | Penulis |
|-------|---------|-----------|---------|
| v1.0 | 24 Juni 2026 | Dokumen awal | Sophia (PM) |

---

## ⚡ RICE Score Calculation

| Komponen | Nilai | Keterangan |
|----------|:-----:|------------|
| **Reach** | 5 | Admin & pimpinan (5-15 orang per bulan) |
| **Impact** | 2 | Menghemat waktu rekap data, mengurangi human error |
| **Confidence** | 75% | Cukup yakin — enhancement dari fitur existing |
| **Effort** | 3 SP | ~1.5 hari |

**RICE Score = (5 × 2 × 75%) / 3 = 2.5 → 🟡 Medium Priority**

---

## ⚡ Effort Estimation Breakdown

| Faktor | Bobot | Alasan |
|--------|:-----:|--------|
| Halaman baru | 0 | Tidak ada halaman baru |
| User roles | 0 | Tetap untuk admin |
| Tabel DB baru | 0 | Tidak ada |
| Integrasi eksternal | 0 | Tidak ada |
| Flow/logic complexity | 3 | Modifikasi export class: mapping data dari 3+ relasi, flatten JSON jawaban, custom value binder untuk format text |
| **Total** | **3 SP** | **🔵 Small — ~1.5 hari** |
