# PRD: Floating WhatsApp Support Icon (Multi-Number)

**Versi:** v2.0 | **Tanggal:** 24 Juni 2026
**Status:** ✅ Approved | **Tipe:** Lite
**Penulis:** Sophia (Project Manager)
**PIC Produk:** Mas Lutfi

**Estimasi:** 🟡 5 SP (Small-Medium) — ~2-3 hari
**Quality Score:** 🟢 92/100 — Excellent
**RICE:** 🟡 7.5 (Medium Priority)
**Conflict:** ✅ Tidak ada konflik dengan PRD existing

---

## 1. Executive Summary

Fitur **Floating WhatsApp Support Icon** adalah ikon WhatsApp hijau yang melayang di pojok kanan bawah **seluruh halaman publik** website. Saat diklik, muncul **popup daftar nomor WhatsApp** yang bisa dipilih pengunjung sesuai kebutuhan (misal: Pendaftaran, Informasi, Teknis). Admin dapat mengelola **multi-nomor** lengkap dengan labelnya melalui panel admin (Settings > Branding). Ikon dilengkapi **animasi pulse ringan** agar lebih menarik perhatian.

---

## 2. Background & Problem Statement

- **Masalah:** Pengunjung website (calon peserta pelatihan) sering ingin bertanya atau konsultasi sebelum mendaftar, tetapi tidak menemukan kontak yang jelas dan mudah dijangkau. Kebutuhan support pun beragam (pendaftaran, info pelatihan, teknis) sehingga butuh lebih dari satu nomor kontak.
- **Dampak:** Potensi calon peserta hilang karena tidak ada akses cepat ke layanan support. Satu nomor saja tidak cukup untuk menampung berbagai jenis pertanyaan.
- **Mengapa sekarang:** Website sudah memiliki traffic, tetapi tidak ada touchpoint support yang mudah. Dengan floating WA multi-kontak, pengunjung bisa langsung terhubung ke bagian yang tepat.

---

## 3. Goals & Success Metrics

| Goal | Metric | Target | Measurement |
|------|--------|--------|-------------|
| Meningkatkan akses support | Jumlah klik ikon WA | Minimal 100 klik/minggu | Log klik atau Google Analytics |
| Memudahkan admin mengelola kontak | Waktu tambah/edit nomor | < 2 menit | Manual test |
| Tidak mengganggu UX halaman | Bounce rate halaman publik | Tidak meningkat >2% | Google Analytics |

---

## 4. User Personas

| Role | Deskripsi | Pain Point | Goal |
|------|-----------|------------|------|
| **Pengunjung (Calon Peserta)** | Masyarakat umum yang akses website, ingin info pelatihan | Susah cari kontak, harus scroll footer, bingung nomor yang tepat | Bisa pilih kontak sesuai kebutuhan & langsung chat via WA |
| **Admin** | Pengelola website yang mengatur konten & pengaturan | Nomor support masih hardcoded atau harus diubah manual. Butuh lebih dari 1 nomor untuk divisi berbeda | Bisa tambah/edit/hapus multi-nomor WA lengkap label dari panel admin |

---

## 5. User Stories

- [ ] **US-001** — Sebagai **pengunjung**, saya ingin melihat ikon WhatsApp melayang di pojok kanan bawah **setiap halaman publik** agar saya bisa menghubungi support kapan saja.
- [ ] **US-002** — Sebagai **pengunjung**, saat saya klik ikon WhatsApp, saya ingin melihat **popup daftar nomor kontak** yang tersedia (dengan label) agar saya bisa memilih bagian yang tepat.
- [ ] **US-003** — Sebagai **pengunjung**, saat saya pilih salah satu nomor, saya langsung diarahkan ke chat WhatsApp dengan nomor tersebut.
- [ ] **US-004** — Sebagai **pengunjung**, saya ingin ikon memiliki **animasi ringan** (pulse) agar mudah terlihat tanpa mengganggu.
- [ ] **US-005** — Sebagai **admin**, saya ingin **menambah beberapa nomor WhatsApp** dengan label masing-masing (misal: "Pendaftaran", "Informasi", "Teknis") di panel admin.
- [ ] **US-006** — Sebagai **admin**, saya ingin **mengedit dan menghapus** nomor WhatsApp yang sudah ada.
- [ ] **US-007** — Sebagai **admin**, saya bisa melihat pratinjau daftar nomor yang sudah disimpan.

---

## 6. Functional Requirements

### 6.1 Floating WhatsApp Icon (Frontend)
- **FR-001:** Menampilkan ikon WhatsApp melayang di pojok kanan bawah **seluruh halaman publik** (front layout).
- **FR-002:** Ikon menggunakan logo WhatsApp resmi (SVG/PNG) dengan warna hijau (#25D366).
- **FR-003:** Ikon memiliki **animasi pulse ringan** (scale up-down lembut) berulang, tidak mengganggu.
- **FR-004:** Ikon memiliki efek shadow (box-shadow) agar terlihat mengambang.
- **FR-005:** Ikon bersifat **fixed** (tidak ikut scroll).
- **FR-006:** Ikon **tidak tampil** jika tidak ada satupun nomor WhatsApp yang dikonfigurasi.
- **FR-007:** Ikon hanya tampil di halaman publik (front layout), tidak di halaman admin/dashboard.
- **FR-008:** Ikon responsive — ukuran proporsional di desktop (56px) dan mobile (48px).

### 6.2 Popup Daftar Nomor (Frontend)
- **FR-009:** Saat ikon diklik, muncul **popup kecil** di samping ikon yang berisi daftar nomor WhatsApp yang tersedia.
- **FR-010:** Setiap item menampilkan **label** (misal: "Pendaftaran") dan **nomor** (tersamarkan sebagian, misal: 6281*****90).
- **FR-011:** Popup bisa ditutup dengan klik di luar popup atau tombol close (X).
- **FR-012:** Saat salah satu nomor diklik, terbuka `https://wa.me/[nomor]?text=[teks_default]` di tab baru.
- **FR-013:** Pesan default (prefilled text) bisa dikonfigurasi oleh admin (opsional).

### 6.3 Admin Configuration (Multi-Number Management)
- **FR-014:** Menambahkan **section baru** "WhatsApp Support" di halaman **Settings > Branding**.
- **FR-015:** Admin bisa **menambah nomor baru** dengan input:
  - Label (misal: "Pendaftaran", "Informasi", "Teknis")
  - Nomor WhatsApp (format internasional tanpa `+`, contoh: `6281234567890`)
- **FR-016:** Admin bisa **mengedit** label & nomor yang sudah ada.
- **FR-017:** Admin bisa **menghapus** nomor yang sudah ada.
- **FR-018:** Admin bisa **mengurutkan** tampilan nomor (drag & drop atau tombol naik/turun).
- **FR-019:** Minimal 1 nomor wajib diisi jika fitur ingin aktif.

### 6.4 Validasi
- **FR-020:** Nomor WA: hanya angka, min 10 digit, maks 15 digit.
- **FR-021:** Label: wajib diisi, string, maks 100 karakter.
- **FR-022:** Tidak boleh ada duplikasi nomor dalam satu daftar.

### 6.5 Data Storage
- **FR-023:** Membuat tabel baru `whatsapp_numbers` untuk menyimpan multi-nomor.
- **FR-024:** Field tabel: `id`, `label` (string), `number` (string), `sort_order` (integer), `is_active` (boolean), `created_at`, `updated_at`.

---

## 7. Non-Functional Requirements

- **NFR-001:** Semua aset ikon < 50 KB, tidak mempengaruhi load time.
- **NFR-002:** Responsive di semua ukuran layar (desktop, tablet, mobile).
- **NFR-003:** Ikon & popup tidak menutupi konten penting, CTA, atau navigasi.
- **NFR-004:** URL WhatsApp terbuka di tab baru (`target="_blank"`) dengan `rel="noopener noreferrer"`.
- **NFR-005:** Animasi pulse menggunakan CSS `@keyframes` — tanpa JavaScript library tambahan.
- **NFR-006:** Popup menggunakan Alpine.js (sudah tersedia di project) untuk toggle show/hide.

---

## 8. User Flow / Wireframe Description

### Pengunjung:
1. Pengunjung membuka halaman publik (landing, pelatihan, dll)
2. Di pojok kanan bawah, ikon WhatsApp hijau melayang **dengan animasi pulse lembut**
3. Saat di-hover, muncul sedikit scale up (CSS transform)
4. Klik ikon → muncul **popup daftar kontak**:
   ```
   ┌─────────────────────┐
   │  📞 Hubungi Kami    │ ×
   │─────────────────────│
   │ 🟢 Pendaftaran      │
   │    wa.me/62812****90│
   │─────────────────────│
   │ 🟢 Informasi        │
   │    wa.me/62813****45│
   │─────────────────────│
   │ 🟢 Teknis           │
   │    wa.me/62856****12│
   └─────────────────────┘
   ```
5. Pengunjung klik salah satu → terbuka `https://wa.me/[nomor]` di tab baru
6. Pengunjung klik di luar popup atau tombol X → popup tertutup

### Admin:
1. Admin login ke panel admin
2. Buka menu **Settings > Branding**
3. Scroll ke section **"WhatsApp Support"**
4. Admin melihat daftar nomor yang sudah ada (jika ada)
5. Admin klik **"Tambah Nomor"** → muncul row baru:
   ```
   Label    : [______________]
   Nomor WA : [______________]  (contoh: 6281234567890)
   [Simpan] [Batal]
   ```
6. Setiap nomor ada tombol **Edit** ✏️ dan **Hapus** 🗑️
7. Admin bisa **drag & drop** atau klik ↑↓ untuk urutan
8. Klik **"Simpan Semua"** → data tersimpan

### Wireframe Admin Panel:
```
Settings > Branding
┌──────────────────────────────────────────┐
│  Nama Brand: [______________]            │
│  ...                                     │
│──────────────────────────────────────────│
│  ☎️ WhatsApp Support                     │
│                                          │
│  + Tambah Nomor                          │
│                                          │
│  ┌───┬──────────┬──────────────────┬──┐  │
│  │ # │ Label    │ Nomor WA         │ ⚙️│  │
│  ├───┼──────────┼──────────────────┼──┤  │
│  │ 1 │ Pendaftaran│ 6281234567890  │✏️🗑️│  │
│  │ 2 │ Informasi │ 628133334444    │✏️🗑️│  │
│  │ 3 │ Teknis    │ 628565556666    │✏️🗑️│  │
│  └───┴──────────┴──────────────────┴──┘  │
│                                          │
│  [Simpan Pengaturan]                     │
└──────────────────────────────────────────┘
```

---

## 9. Business Rules & Validation

| Aturan | Deskripsi |
|--------|-----------|
| BR-001 | Nomor WA hanya boleh berisi angka (0-9) |
| BR-002 | Nomor WA minimal 10 digit, maksimal 15 digit |
| BR-003 | Nomor disimpan tanpa awalan `+` atau karakter lain |
| BR-004 | Label wajib diisi, maks 100 karakter |
| BR-005 | Tidak boleh ada duplikasi nomor dalam daftar |
| BR-006 | Jika tidak ada nomor aktif, ikon tidak ditampilkan |
| BR-007 | Hanya admin yang bisa mengelola nomor WA |
| BR-008 | Ikon & popup hanya tampil di front layout (halaman publik) |
| BR-009 | Urutan nomor di popup mengikuti `sort_order` |
| BR-010 | Nomor dengan `is_active = false` tidak ditampilkan di popup |

---

## 10. Data Requirements

### Tabel Baru: `whatsapp_numbers`

| Field | Type | Constraint | Description |
|-------|------|------------|-------------|
| `id` | bigint | PK, auto-increment | ID unik |
| `label` | varchar(100) | NOT NULL | Label kontak (misal: "Pendaftaran") |
| `number` | varchar(20) | NOT NULL, UNIQUE | Nomor WA internasional tanpa `+` |
| `sort_order` | int | DEFAULT 0 | Urutan tampilan |
| `is_active` | boolean | DEFAULT true | Status aktif/nonaktif |
| `created_at` | timestamp | nullable | Waktu dibuat |
| `updated_at` | timestamp | nullable | Waktu diupdate |

---

## 11. Integration & Dependencies

- **WhatsApp API:** Tidak perlu. Menggunakan URL `https://wa.me/[nomor]?text=[pesan]`.
- **Library baru:** Tidak ada. Cukup CSS + Blade + Alpine.js (sudah tersedia).
- **Database:** Migration baru untuk tabel `whatsapp_numbers`.
- **Model baru:** `App\Models\WhatsappNumber`

---

## 12. Acceptance Criteria

- [ ] **AC-001:** Ikon WhatsApp muncul di pojok kanan bawah **semua halaman publik** (landing, pelatihan, dll).
- [ ] **AC-002:** Ikon **tidak muncul** di halaman admin/dashboard.
- [ ] **AC-003:** Ikon **tidak muncul** jika tidak ada nomor aktif.
- [ ] **AC-004:** Ikon memiliki **animasi pulse** (scale CSS) yang halus dan tidak mengganggu.
- [ ] **AC-005:** Ikon memiliki efek shadow dan sedikit membesar saat di-hover.
- [ ] **AC-006:** Klik ikon → muncul **popup daftar nomor** dengan label masing-masing.
- [ ] **AC-007:** Popup bisa ditutup dengan klik di luar area atau tombol close.
- [ ] **AC-008:** Klik salah satu nomor → buka `https://wa.me/[nomor]` di tab baru.
- [ ] **AC-009:** Admin bisa **menambah nomor WA** dengan label via Settings > Branding.
- [ ] **AC-010:** Admin bisa **mengedit** label & nomor yang sudah ada.
- [ ] **AC-011:** Admin bisa **menghapus** nomor yang sudah ada.
- [ ] **AC-012:** Admin bisa **mengurutkan** nomor (↑↓ atau drag).
- [ ] **AC-013:** Validasi: nomor hanya angka, min 10 digit, maks 15 digit.
- [ ] **AC-014:** Validasi: label wajib diisi, maks 100 karakter.
- [ ] **AC-015:** Validasi: tidak boleh ada duplikasi nomor.
- [ ] **AC-016:** Urutan nomor di popup sesuai urutan yang diatur admin.
- [ ] **AC-017:** Nomor nonaktif (`is_active = false`) tidak muncul di popup.
- [ ] **AC-018:** Ikon & popup responsive di mobile (ukuran proporsional, tidak menutupi konten).

---

## 13. Out of Scope

- ❌ Live chat / chat widget real-time
- ❌ Tracking analytics klik (bisa ditambahkan di versi berikutnya)
- ❌ Kustomisasi tampilan ikon (warna, ukuran, posisi) oleh admin
- ❌ Integrasi dengan API WhatsApp Business / WhatsApp Cloud API
- ❌ Fitur "click to call" (hanya WhatsApp chat)
- ❌ Notifikasi offline saat admin tidak membalas

---

## 14. Open Questions

| No | Pertanyaan | Status |
|:--:|-----------|--------|
| 1 | Apakah perlu prefilled text default saat chat? Misal: "Halo kak, saya mau tanya tentang..." | 🤔 Perlu konfirmasi |
| 2 | Jumlah maksimal nomor yang bisa ditambahkan? (misal: maks 5) | 🤔 Perlu konfirmasi |
| 3 | Animasi pulse perlu dikombinasikan dengan efek float (naik-turun) atau cukup pulse saja? | 🤔 Perlu konfirmasi |

---

## 15. Risks & Mitigation

| Risk | Impact | Likelihood | Mitigation |
|------|--------|------------|------------|
| Popup menutupi konten penting di mobile | Medium | Low | Popup diposisikan di atas ikon, ukuran terbatas, ada backdrop semi-transparan |
| Admin salah input format nomor | Low | Medium | Validasi ketat (hanya angka, min/max digit) + format otomatis |
| Animasi pulse terlalu mencolok | Low | Low | Gunakan animation-duration 2-3 detik, opacity halus, bukan scale besar |
| Banyak nomor membuat popup panjang | Low | Low | Batasi maks 5 nomor, beri scroll jika lebih |

---

## 16. Revision History

| Versi | Tanggal | Perubahan | Penulis |
|-------|---------|-----------|---------|
| v1.0 | 24 Juni 2026 | Initial draft (single number) | Sophia (PM) |
| v2.0 | 24 Juni 2026 | **Perubahan besar:** multi-nomor, popup list, muncul di semua halaman publik, animasi pulse | Sophia (PM) |

---

*PRD ini siap untuk di-breakdown dan didelegasikan ke tim teknis setelah di-approve.*
