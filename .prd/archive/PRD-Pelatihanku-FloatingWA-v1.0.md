# PRD: Floating WhatsApp Support Icon

**Versi:** v1.0 | **Tanggal:** 24 Juni 2026
**Status:** Draft | **Tipe:** Lite
**Penulis:** Sophia (Project Manager)
**PIC Produk:** Mas Lutfi

**Estimasi:** 🔵 3 SP (Small) — ~1 hari
**Quality Score:** 🟢 90/100 — Excellent
**RICE:** 🟡 8.0 (Medium Priority)
**Conflict:** ✅ Tidak ada konflik dengan PRD existing

---

## 1. Executive Summary

Fitur ini menambahkan **ikon WhatsApp melayang (floating)** di pojok kanan bawah halaman publik (landing page) yang berfungsi sebagai tombol **hubungi support** via WhatsApp. Nomor WhatsApp yang dituju bisa diatur dengan mudah oleh Admin melalui halaman pengaturan (Settings) di panel admin. Cukup dengan sekali klik, pengunjung langsung terhubung ke nomor support melalui aplikasi WhatsApp.

---

## 2. Background & Problem Statement

- **Masalah:** Pengunjung website (calon peserta pelatihan) sering kali ingin bertanya atau konsultasi sebelum mendaftar, tetapi tidak menemukan kontak yang jelas dan mudah dijangkau.
- **Dampak:** Potensi calon peserta hilang karena tidak ada akses cepat ke layanan support. Pengunjung harus mencari kontak di halaman "about" atau footer yang kurang menonjol.
- **Mengapa sekarang:** Landing page adalah pintu utama konversi pendaftaran. Dengan adanya tombol WhatsApp melayang, pengunjung bisa langsung terhubung dengan tim support dalam 1 klik, meningkatkan engagement dan tingkat pendaftaran.

---

## 3. Goals & Success Metrics

| Goal | Metric | Target | Measurement |
|------|--------|--------|-------------|
| Meningkatkan akses support | Jumlah klik tombol WhatsApp | Minimal 50 klik/minggu | Tracking via Google Analytics event atau log klik |
| Memudahkan admin mengelola nomor kontak | Waktu konfigurasi nomor | < 1 menit | Manual test |
| Tidak mengganggu UX halaman | Bounce rate landing page | Tidak meningkat >2% | Google Analytics |

---

## 4. User Personas

| Role | Deskripsi | Pain Point | Goal |
|------|-----------|------------|------|
| **Pengunjung (Calon Peserta)** | Masyarakat umum yang mengakses landing page, ingin tahu info pelatihan | Susah mencari kontak support, harus scroll ke footer | Bisa langsung chat WhatsApp dengan 1 klik |
| **Admin** | Pengelola website yang mengatur konten dan pengaturan | Nomor support masih hardcoded atau harus diubah manual di file | Bisa ganti nomor WhatsApp kapan saja dari panel admin |

---

## 5. User Stories

- [ ] **US-001** — Sebagai **pengunjung**, saya ingin melihat ikon WhatsApp melayang di pojok kanan bawah halaman agar saya bisa langsung chat support tanpa perlu mencari kontak.
- [ ] **US-002** — Sebagai **pengunjung**, saya ingin ikon WhatsApp memiliki tooltip "Hubungi Support" agar jelas fungsinya saat di-hover.
- [ ] **US-003** — Sebagai **admin**, saya ingin mengisi nomor WhatsApp support di halaman Settings > Branding agar nomor bisa diubah kapan saja tanpa perlu koding.
- [ ] **US-004** — Sebagai **admin**, saya ingin melihat pratinjau nomor yang sudah disimpan agar saya yakin nomor yang dimasukkan sudah benar.

---

## 6. Functional Requirements

### 6.1 Floating WhatsApp Icon (Frontend)
- **FR-001:** Menampilkan ikon WhatsApp melayang di pojok kanan bawah halaman publik (landing page).
- **FR-002:** Ikon menggunakan logo WhatsApp resmi (SVG/PNG) dengan warna hijau (#25D366).
- **FR-003:** Saat di-klik, membuka WhatsApp Web dengan nomor yang sudah dikonfigurasi menggunakan URL `https://wa.me/[nomor]`.
- **FR-004:** Ikon memiliki efek shadow dan sedikit animasi (pulse/float) agar terlihat menarik.
- **FR-005:** Ikon memiliki tooltip "Hubungi Support" saat di-hover.
- **FR-006:** Ikon bersifat fixed / sticky (tidak ikut scroll).
- **FR-007:** Ikon hanya tampil di halaman publik (front layout), tidak di halaman admin/dashboard.
- **FR-008:** Ikon tidak tampil jika nomor WhatsApp belum dikonfigurasi (kosong).

### 6.2 Admin Configuration
- **FR-009:** Menambahkan field input "Nomor WhatsApp Support" di halaman **Settings > Branding** (atau Settings > Landing).
- **FR-010:** Format nomor internasional tanpa tanda `+` (contoh: `6281234567890`).
- **FR-011:** Validasi: hanya angka yang diperbolehkan, minimal 10 digit, maksimal 15 digit.
- **FR-012:** Menampilkan pratinjau link WhatsApp yang akan dibuka: `https://wa.me/[nomor]`.
- **FR-013:** Tombol "Simpan" untuk menyimpan pengaturan.

### 6.3 Data Storage
- **FR-014:** Menyimpan nomor WhatsApp di tabel `settings` dengan key `whatsapp_support_number`, group `general`, label "Nomor WhatsApp Support".

---

## 7. Non-Functional Requirements

- **NFR-001:** Ikon WhatsApp harus ringan (< 50 KB) dan tidak mempengaruhi waktu muat halaman.
- **NFR-002:** Ikon harus responsive di semua ukuran layar (desktop, tablet, mobile).
- **NFR-003:** Ikon tidak menutupi konten penting atau tombol CTA.
- **NFR-004:** URL WhatsApp terbuka di tab baru (`target="_blank"`) dengan `rel="noopener noreferrer"`.
- **NFR-005:** Tidak memerlukan library/package tambahan (murni CSS + HTML + Blade).

---

## 8. User Flow / Wireframe Description

### Pengunjung:
1. Pengunjung membuka halaman utama (landing page)
2. Di pojok kanan bawah, terlihat ikon WhatsApp hijau melayang dengan efek shadow
3. Saat di-hover, muncul tooltip "Hubungi Support"
4. Pengunjung mengklik ikon → terbuka tab baru ke `https://wa.me/6281234567890`
5. Pengunjung langsung bisa chat

### Admin:
1. Admin login ke panel admin
2. Admin membuka menu **Settings > Branding**
3. Di bagian bawah form, ada field **"Nomor WhatsApp Support"**
4. Admin memasukkan nomor (contoh: `6281234567890`)
5. Di bawah field, muncul pratinjau: `https://wa.me/6281234567890`
6. Admin klik "Simpan" → muncul notifikasi sukses
7. Nomor langsung aktif di landing page

### Wireframe (Text-based):
```
+------------------------------------------+
|  [Logo]  Pelatihanku               [Menu] |
|                                            |
|     HERO SECTION                           |
|     CTA BUTTON                             |
|                                            |
|     PELATIHAN                              |
|                                            |
|     FOOTER                                 |
|                                      [WA]  |  ← Ikon melayang di pojok kanan bawah
+------------------------------------------+

Admin Panel:
Settings > Branding
+------------------------------------------+
|  Nama Brand: [____]                       |
|  ...                                      |
|  Nomor WhatsApp Support: [62812......]    |
|  Pratinjau: https://wa.me/62812......     |
|  [Simpan]                                 |
+------------------------------------------+
```

---

## 9. Business Rules & Validation

| Aturan | Deskripsi |
|--------|-----------|
| BR-001 | Hanya angka yang valid untuk nomor WhatsApp |
| BR-002 | Nomor minimal 10 digit, maksimal 15 digit |
| BR-003 | Nomor disimpan tanpa awalan `+` atau karakter lain |
| BR-004 | Jika nomor kosong, ikon tidak ditampilkan |
| BR-005 | Hanya admin yang bisa mengubah nomor WhatsApp |
| BR-006 | Ikon hanya tampil di front layout (halaman publik) |

---

## 10. Data Requirements

### Tabel: `settings` (Existing)

| Field | Key | Value | Group | Label |
|-------|-----|-------|-------|-------|
| Baris baru | `whatsapp_support_number` | `6281234567890` | `general` | `Nomor WhatsApp Support` |

**Tidak perlu tabel baru.** Cukup tambah 1 baris di tabel `settings` yang sudah ada.

---

## 11. Integration & Dependencies

- **WhatsApp API:** Tidak perlu. Cukup menggunakan URL `https://wa.me/[nomor]` yang merupakan fitur resmi WhatsApp.
- **Dependency:** Tidak ada. Fitur ini standalone tanpa integrasi pihak ketiga.
- **Database:** Tidak perlu migration baru. Menggunakan tabel `settings` yang sudah ada.

---

## 12. Acceptance Criteria

- [ ] **AC-001:** Ikon WhatsApp muncul di pojok kanan bawah halaman publik (landing page, halaman pelatihan, dll).
- [ ] **AC-002:** Ikon tidak muncul di halaman admin/dashboard.
- [ ] **AC-003:** Ikon tidak muncul jika nomor WhatsApp belum diisi (kosong).
- [ ] **AC-004:** Klik ikon membuka `https://wa.me/[nomor]` di tab baru.
- [ ] **AC-005:** Tooltip "Hubungi Support" muncul saat mouse hover di atas ikon.
- [ ] **AC-006:** Admin bisa mengisi nomor WhatsApp di Settings > Branding.
- [ ] **AC-007:** Validasi: input hanya menerima angka, min 10 digit, maks 15 digit.
- [ ] **AC-008:** Pratinjau link `https://wa.me/[nomor]` muncul di bawah field input.
- [ ] **AC-009:** Setelah disimpan, nomor langsung digunakan oleh ikon di landing page.
- [ ] **AC-010:** Ikon tetap di posisinya saat halaman di-scroll (position: fixed).
- [ ] **AC-011:** Ikon responsive di mobile (tidak terlalu besar, tidak menutupi konten).

---

## 13. Out of Scope

- ❌ Menambahkan beberapa nomor WhatsApp (hanya 1 nomor support)
- ❌ Live chat / chat widget (hanya redirect ke WhatsApp)
- ❌ Tracking analytics klik (bisa ditambahkan di versi berikutnya)
- ❌ Kustomisasi tampilan ikon (warna, ukuran, posisi)
- ❌ Integrasi dengan API WhatsApp Business
- ❌ Fitur "click to call" (hanya WhatsApp chat)

---

## 14. Open Questions

| No | Pertanyaan | Status |
|:--:|-----------|--------|
| 1 | Apakah nomor WhatsApp support cukup 1 atau perlu multi-nomor? | 🤔 Perlu konfirmasi |
| 2 | Ikon ingin muncul di semua halaman publik atau hanya landing page? | 🤔 Perlu konfirmasi |
| 3 | Apakah perlu animasi tertentu (pulse, float, shake)? | 🤔 Perlu konfirmasi |

---

## 15. Risks & Mitigation

| Risk | Impact | Likelihood | Mitigation |
|------|--------|------------|------------|
| Ikon menutupi tombol CTA di mobile | Medium | Low | Beri jarak aman (margin-bottom: 80px) dan test di berbagai ukuran layar |
| Nomor salah format saat input | Low | Medium | Validasi input ketat + pratinjau link |
| Pengguna tidak sadar itu tombol WhatsApp | Low | Low | Gunakan logo WhatsApp yang familiar + tooltip |

---

## 16. Revision History

| Versi | Tanggal | Perubahan | Penulis |
|-------|---------|-----------|---------|
| v1.0 | 24 Juni 2026 | Initial draft | Sophia (PM) |

---

*PRD ini siap untuk di-breakdown dan didelegasikan ke tim teknis setelah di-approve.*
