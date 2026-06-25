# Test Case: Fix Statistik Enrollment

**Fitur:** Halaman `/admin/enrollments` — Stat cards (Pending, Approved, Ditolak, Cadangan)
**Tgl:** 25 Juni 2026
**Tester:**
**Lingkungan:** Production-like / Staging

---

## A. Test Case Fungsional (Statistik)

| ID | Skenario | Prekondisi | Langkah | Expected Result | Actual Result | Status |
|----|----------|-----------|---------|-----------------|---------------|--------|
| TC-001 | Load halaman tanpa filter | Data enrollment ada di DB | 1. Buka `/admin/enrollments` tanpa query params | Stat cards menampilkan total seluruh enrollment di semua pelatihan | | |
| TC-002 | Filter pelatihan tertentu | Minimal 2 pelatihan dengan data berbeda | 1. Pilih pelatihan A di dropdown "Filter Pelatihan"<br>2. Tunggu AJAX selesai | Stat cards berubah: angka sesuai data pelatihan A saja (bukan global). Tabel menampilkan data pelatihan A. | | |
| TC-003 | Ganti filter ke pelatihan lain | Masih di halaman yang sama | 1. Pilih pelatihan B di dropdown | Stat cards berubah: angka sesuai data pelatihan B. Tabel berubah ke data pelatihan B. | | |
| TC-004 | Reset filter ke "Semua Pelatihan" | Filter sedang aktif (pelatihan tertentu) | 1. Pilih "Semua Pelatihan" di dropdown | Stat cards kembali ke data global (seluruh pelatihan). Tabel menampilkan semua data. | | |
| TC-005 | Search nama peserta (tidak pengaruhi stat) | Ada beberapa peserta dari berbagai pelatihan | 1. Ketik nama peserta di search input<br>2. Tunggu AJAX selesai | Stat cards **TIDAK berubah** (tetap global atau sesuai filter pelatihan). Tabel hanya menampilkan hasil search. | | |
| TC-006 | Filter status (tidak pengaruhi stat) | Data enrollment dengan berbagai status | 1. Pilih status "Pending" di dropdown | Stat cards **TIDAK berubah**. Tabel hanya menampilkan enrollment status Pending. | | |
| TC-007 | Kombinasi filter pelatihan + search | Ada data yang cocok dan tidak cocok | 1. Pilih pelatihan A<br>2. Ketik nama peserta dari pelatihan A | Stat cards = data pelatihan A. Tabel = hasil search dalam pelatihan A. | | |
| TC-008 | Approve satu enrollment | Ada enrollment pending | 1. Klik tombol "Approve" pada enrollment pending<br>2. Konfirmasi | Page reload. Stat cards: Pending -1, Approved +1. | | |
| TC-009 | Reject enrollment | Ada enrollment pending/approved | 1. Klik tombol "Reject"<br>2. Isi alasan<br>3. Konfirmasi | Page reload. Stat cards: Rejected +1, status asal -1. | | |
| TC-010 | Pindahkan ke waitlist | Ada enrollment pending/approved | 1. Klik "Waitlist" pada enrollment<br>2. Konfirmasi | Page reload. Stat cards: Cadangan +1, status asal -1. | | |
| TC-011 | Approve All button | Filter pelatihan aktif, ada pending | 1. Filter pelatihan tertentu (muncul Approve All)<br>2. Klik "Approve All Pending"<br>3. Konfirmasi | Page reload. Stat cards: Pending = 0 (atau sesuai sisa). Badge Approve All sesuai jumlah pending sebelum approve. | | |
| TC-012 | Approve All — badge number valid | Filter pelatihan, ada 5 pending | 1. Arahkan mouse ke badge Approve All | Badge menampilkan angka **5** | | |
| TC-013 | Approve All setelah filter berubah | Ganti filter, jumlah pending berbeda | 1. Pilih pelatihan X (pending=3)<br>2. Pilih pelatihan Y (pending=7) | Saat pindah ke Y, badge Approve All = **7** | | |
| TC-014 | Approve penuh kuota → waitlist otomatis | Kuota = 10, Approved = 9, ada 3 pending | 1. Approve 1 pending (jadi 10) | Sisa 2 pending otomatis jadi waitlist. Stat: Pending -3, Approved +1, Cadangan +2 | | |

---

## B. Test Case Cache

| ID | Skenario | Prekondisi | Langkah | Expected Result | Actual Result | Status |
|----|----------|-----------|---------|-----------------|---------------|--------|
| TC-020 | Cache pelatihan expired (5 menit) | Ada pelatihan baru dibuat 5 menit lalu (tanpa refresh halaman) | 1. Buka `/admin/enrollments`<br>2. Cek dropdown Filter Pelatihan | Dropdown menampilkan pelatihan baru tersebut (cache expired, ambil data terbaru) | | |
| TC-021 | Cache di-forget saat create pelatihan | Sedang di halaman create pelatihan | 1. Buat pelatihan baru di admin<br>2. Buka `/admin/enrollments` | Pelatihan baru langsung muncul di dropdown tanpa perlu menunggu 5 menit | | |
| TC-022 | Cache di-forget saat update pelatihan | Ada pelatihan yang diubah namanya | 1. Ubah nama/batch pelatihan di admin<br>2. Buka `/admin/enrollments` | Nama/batch yang sudah diupdate tampil di dropdown | | |
| TC-023 | Cache di-forget saat hapus pelatihan | Ada pelatihan yang dinonaktifkan | 1. Nonaktifkan / hapus pelatihan<br>2. Buka `/admin/enrollments` | Pelatihan yang dihapus/nonaktif **tidak muncul** di dropdown | | |
| TC-024 | Cache konsisten antar halaman | Buka halaman lain yang pakai `pelatihan.active.list` | 1. Buka `/admin/enrollments`<br>2. Buka `/admin/certificates` | Data dropdown sama (konsisten) di kedua halaman | | |

---

## C. Test Case Regression

| ID | Skenario | Prekondisi | Langkah | Expected Result | Actual Result | Status |
|----|----------|-----------|---------|-----------------|---------------|--------|
| TC-030 | Pagination via AJAX | Data > 20 enrollment | 1. Klik halaman 2 di pagination | Tabel dan pagination berubah. Stat cards **tetap** (tidak terpengaruh oleh page change) | | |
| TC-031 | Pagination setelah filter | Filter pelatihan A aktif, data > 20 | 1. Klik halaman 2 | Tabel data pelatihan A page 2. Stat cards tetap data pelatihan A | | |
| TC-032 | Export PDF | Data enrollment ada | 1. Klik "Export PDF" | File PDF terdownload. Halaman **tidak reload**. Stat cards tidak terpengaruh. | | |
| TC-033 | Export PDF (filtered) | Filter pelatihan aktif | 1. Pilih pelatihan A<br>2. Klik "Export PDF" | PDF hanya berisi data pelatihan A. URL export mengandung `?pelatihan=X` | | |
| TC-034 | Export Excel | Data enrollment ada | 1. Klik "Export Excel" | File Excel (.xlsx) terdownload. | | |
| TC-035 | Ubah status via dropdown (AJAX) | Ada enrollment | 1. Klik dropdown Status → pilih "Approved"<br>2. Isi alasan → Konfirmasi | Page reload (form submit). Stat cards update. | | |
| TC-036 | Ubah status pending → rejected | Enrollment status pending | 1. Dropdown → Rejected → isi alasan | Stat: Pending -1, Rejected +1 | | |
| TC-037 | Ubah status approved → waitlist | Enrollment status approved | 1. Dropdown → Waitlist → isi alasan | Stat: Approved -1, Cadangan +1. Peserta dari waitlist asli dipromosikan ke approved jika ada slot. | | |
| TC-038 | Reset enrollment | Ada enrollment | 1. Klik tombol Reset (icon refresh)<br>2. Konfirmasi | Enrollment terhapus. Stat cards update (berkurang di status asal). Halaman reload. | | |
| TC-039 | Reset enrollment lalu filter ulang | Reset dilakukan | 1. Reset enrollment<br>2. Pilih filter pelatihan lain | Tidak ada error. Stat cards tetap sinkron. | | |
| TC-040 | Transfer peserta ke pelatihan lain | Ada enrollment approved | 1. Klik Transfer → pilih pelatihan lain → isi alasan | Enrollment pindah ke pelatihan baru. Stat cards pelatihan asal berubah (approved -1). | | |
| TC-041 | Load halaman via URL langsung dengan params | - | 1. Buka `/admin/enrollments?pelatihan_id=5&status=pending` | Dropdown terisi sesuai params. Stat cards = data pelatihan id=5. Tabel = pending saja. | | |
| TC-042 | Filter reset button | Filter aktif + search active | 1. Klik tombol "Reset" (di samping filter status) | Semua filter dan search direset. Page reload ke `/admin/enrollments`. Stat cards = global. | | |
| TC-043 | Search reset button (X) | Search aktif, filter pelatihan aktif | 1. Klik tombol X di search bar | Search direset. Filter pelatihan **tetap**. Fetch ulang tanpa search. Stat cards tetap sesuai filter. | | |
| TC-044 | Loading state | Koneksi lambat | 1. Pilih filter yang lambat responnya | Loading spinner muncul saat AJAX berlangsung. Spinner hilang setelah data loaded. | | |
| TC-045 | Error handling AJAX | Server error / network down | 1. Putuskan koneksi / matikan server<br>2. Ganti filter | Tabel tetap menampilkan data lama. Tidak ada error toast (hanya console.error). | | |
| TC-046 | Double-click filter cepat | - | 1. Cepat mengganti filter 3-4 kali berturut-turut | Hanya request terakhir yang diproses (request sebelumnya di-abort). Stat cards sesuai filter terakhir. | | |

---

## Ringkasan Cakupan

| Area | Jumlah TC |
|------|-----------|
| Fungsional (Statistik) | 14 |
| Cache | 5 |
| Regression | 17 |
| **Total** | **36** |

---

## Catatan Tambahan

1. **Perubahan Backend yang Sudah di-Deploy:**
   - Cache `pelatihan.active.list` dari 3600 → 300 detik (5 menit) di `EnrollmentController.php`
   - Cache di-forget saat create/update/delete pelatihan di `PelatihanController.php`
   - Response AJAX menyertakan `counts` object

2. **Perubahan Frontend yang Diperlukan:**
   - `fetchData()` harus mengupdate stat cards dari `data.counts` (saat ini belum ada)
   - Stat cards: Pending, Approved, Ditolak, Cadangan perlu diberi `id` atau `class` agar bisa diupdate via JS

3. **Anomali yang Terdeteksi:**
   - `CertificateController.php` line 31 masih menggunakan `Cache::remember(..., 3600, ...)` untuk key yang sama (`pelatihan.active.list`) — inkonsisten dengan EnrollmentController yang sudah 300. Seharusnya 300 agar seragam.
