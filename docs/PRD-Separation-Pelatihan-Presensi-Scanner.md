# Product Requirements Document (PRD)

## 📌 Informasi Dokumen
| Atribut | Detail |
| --- | --- |
| **Nama Proyek** | Pelatihanku |
| **Fitur** | Pemisahan Pelatihan, Presensi & Scanner |
| **Versi** | v1.1.0 |
| **Tanggal Terakhir Diperbarui** | 2026-06-28 |

## 📜 Riwayat Perubahan (Changelog)
| Versi | Tanggal | Penulis | Deskripsi Perubahan |
| --- | --- | --- | --- |
| v1.0.0 | 2026-06-20 | PM Team | Dokumen awal pemisahan halaman pelatihan, presensi, dan scanner panitia. |
| v1.1.0 | 2026-06-28 | PRD Specialist | Menambahkan ketentuan penyaringan peserta berdasarkan status `confirmed` pada Halaman Presensi `/admin/presensi` serta dokumen ekspor Excel/PDF. |

---

## 📋 Executive Summary
Dokumen ini mendefinisikan kebutuhan fungsional dan teknis untuk pemisahan fungsionalitas Manajemen Pelatihan, Rekapitulasi Presensi, dan Halaman Operasional Panitia/Instruktur (Scanner & Layar Proyektor) pada platform Pelatihanku. Pemisahan ini bertujuan untuk meningkatkan kejelasan peran, meminimalisir kesalahan operasional, dan menyederhanakan antarmuka masing-masing tipe pengguna (Administrator vs Panitia Lapangan/Instruktur).

---

## 🎯 Latar Belakang
Saat ini, antarmuka administrasi pelatihan (`/admin/pelatihan`) menampung terlalu banyak fungsionalitas yang tumpang tindih. Fungsionalitas manajemen data master (CRUD Pelatihan, penentuan titik lokasi GPS, dan penugasan instruktur) bercampur dengan alat operasional harian (tombol untuk membuka layar scanner QR Code dan layar proyektor).

Hal ini menimbulkan beberapa masalah:
1. **Kebingungan Pengguna:** Administrator program sering kali terganggu atau bingung dengan adanya tombol operasional presensi saat mereka hanya ingin melakukan update data pelatihan.
2. **Kelebihan Beban Kognitif:** Satu halaman memuat terlalu banyak elemen visual (tombol, tabel, modal) yang melayani tujuan berbeda.
3. **Keamanan & Efisiensi Operasional:** Panitia di lapangan atau instruktur yang bertugas melakukan scan kehadiran peserta seharusnya tidak diberikan akses penuh ke halaman administrasi pelatihan (CRUD) hanya untuk membuka scanner atau menampilkan layar proyektor. Mereka membutuhkan halaman operasional yang ringkas, berfokus hanya pada aktivitas hari ini, dan mudah diakses dari perangkat mobile atau tablet.

Oleh karena itu, fungsionalitas ini akan dipecah menjadi tiga bagian utama yang terpisah secara logis dan otorisasi.

---

## 👥 Target Pengguna
1. **Administrator Pelatihan:** Bertanggung jawab atas pengelolaan program, pembuatan jadwal, konfigurasi titik GPS, dan penugasan instruktur.
2. **Panitia Lapangan & Instruktur:** Bertanggung jawab atas pelaksanaan harian pelatihan di lokasi, termasuk menyalakan scanner kehadiran di pintu masuk dan menampilkan proyektor QR di ruang kelas.
3. **Supervisor / Verifikator:** Pengguna yang melakukan rekapitulasi, koreksi kehadiran manual (jika ada peserta terkendala perangkat), dan mengekspor laporan kehadiran untuk administrasi akhir.

---

## 📖 User Stories

1. **Sebagai Administrator Pelatihan**, saya ingin halaman `/admin/pelatihan` berfokus penuh pada pembuatan, pembaruan, dan penghapusan pelatihan, penugasan instruktur, serta pengaturan koordinat lokasi presensi, sehingga proses administrasi program lebih bersih dan bebas gangguan operasional.
2. **Sebagai Supervisor / Verifikator Kehadiran**, saya ingin mengakses halaman khusus `/admin/presensi` yang berisi rekapitulasi dan daftar detail kehadiran per pertemuan agar saya bisa memantau statistik kehadiran, mengoreksi data secara manual, dan mengekspor laporan ke Excel/PDF dengan mudah.
3. **Sebagai Panitia Lapangan / Instruktur**, saya ingin mengakses halaman `/panitia/operasional` (atau sejenisnya) yang hanya menampilkan daftar kelas aktif *hari ini*, sehingga saya dapat langsung membuka scanner panitia atau layar proyektor kelas tanpa harus melihat tumpukan menu administrasi.

---

## 🛠️ Arsitektur & Spesifikasi Halaman Baru

### 1. Halaman Pelatihan (CRUD / Administrasi Program)
*   **Path:** `/admin/pelatihan` (menggunakan routing/controller yang sudah ada, dengan penyesuaian layout).
*   **Fungsi Utama:** 
    *   Tabel daftar pelatihan yang sedang berjalan, akan datang, atau selesai.
    *   Form Tambah/Edit Pelatihan (Nama, deskripsi, tanggal mulai/selesai, kuota, dll.).
    *   Pengaturan Koordinat Lokasi Presensi (Latitude, Longitude, Radius toleransi GPS).
    *   Assign/Penugasan Instruktur ke kelas terkait.
*   **Perubahan Layout:**
    *   **Wajib menghapus** semua tombol, link, atau modal yang berkaitan dengan pembukaan **Scanner QR Code (Scanner Panitia)** dan **Layar Proyektor** dari halaman ini.
    *   Halaman ini bersih dari aktivitas operasional presensi harian (real-time).

### 2. Halaman Presensi (Rekapitulasi Kehadiran & Koreksi)
*   **Path:** `/admin/presensi`
*   **Fungsi Utama:**
    *   Menampilkan daftar pelatihan (baik yang aktif maupun yang sudah selesai) untuk dikelola data kehadirannya.
    *   **Detail Pertemuan:** Ketika pelatihan dipilih, tampilkan daftar pertemuan (pertemuan 1, 2, 3, dst.).
    *   **Rekapitulasi Kehadiran:** Menampilkan daftar peserta pelatihan beserta status kehadirannya (Hadir, Sakit, Izin, Alpha) untuk setiap pertemuan.
        *   **Ketentuan Filter Peserta:** Daftar peserta yang ditampilkan dalam tabel/rekap presensi **hanya peserta yang memiliki status pendaftaran `confirmed` (Terkonfirmasi) saja**. Peserta dengan status lain (seperti *pending*, *approved* tetapi belum verifikasi WA/Newbimma, *rejected*, atau *waitlist*) **tidak boleh dimunculkan** di rekap absensi kelas.
    *   **Koreksi Kehadiran Manual:** Administrator/Supervisor dapat mengubah status kehadiran peserta secara manual (misal: dari Alpha menjadi Hadir atas persetujuan panitia jika ada kendala GPS) dilengkapi form alasan perubahan.
    *   **Export Fitur:** Menyediakan tombol untuk mengekspor rekapitulasi kehadiran peserta pelatihan tersebut ke format **Excel** dan **PDF**. Ketentuan khusus filter status `confirmed` juga **wajib berlaku** pada file ekspor Excel dan PDF yang diunduh dari halaman presensi tersebut (peserta dengan status di luar `confirmed` tidak boleh tercantum).

### 3. Halaman Scanner & Proyektor (Operasional Panitia/Instruktur)
*   **Path:** `/panitia/operasional`
*   **Fungsi Utama:**
    *   **Filter Otomatis:** Hanya menampilkan pelatihan yang jadwalnya aktif **HARI INI** (berdasarkan tanggal berjalan sistem).
    *   Jika tidak ada pelatihan aktif hari ini, tampilkan state kosong (*empty state*) yang informatif dan estetik.
    *   **Dua Tombol Utama** untuk setiap pelatihan aktif:
        1.  **"Buka Scanner Panitia"** (Membuka antarmuka scanner kamera belakang untuk melakukan scanning QR Code di perangkat peserta).
        2.  **"Buka Layar Proyektor"** (Membuka halaman/layar proyektor yang menampilkan QR Code dinamis untuk discan oleh peserta secara mandiri melalui HP mereka).
    *   **Responsive View:** Didesain agar sangat responsif (Mobile & Tablet Friendly) karena panitia akan sering mengakses halaman ini via smartphone di lapangan.

---

## 🔄 Alur Pengguna (User Flow)

```
[Mulai]
  │
  ├─► Login sebagai Admin ───────────► Ke Dashboard Admin
  │                                        │
  │                                        ├──► Navigasi ke "/admin/pelatihan" 
  │                                        │      └─► CRUD Pelatihan, GPS, Assign Instruktur (Bebas Tombol Scan)
  │                                        │
  │                                        └──► Navigasi ke "/admin/presensi"
  │                                               └─► Pilih Pelatihan ─► Pilih Pertemuan ─► Rekap/Koreksi Kehadiran ─► Export Excel/PDF
  │
  └─► Login sebagai Panitia/Instruktur ──► Navigasi ke "/panitia/operasional"
                                               │
                                               └──► Deteksi Otomatis: Kelas Aktif Hari Ini
                                                      │
                                                      ├───► Klik "Buka Scanner Panitia" ─► Kamera Aktif ─► Scan QR Code Peserta
                                                      └───► Klik "Buka Layar Proyektor" ─► Layar Proyektor Tampil (QR Dinamis)
```

---

## ✅ Kriteria Penerimaan (Acceptance Criteria)

### Halaman /admin/pelatihan (Administrasi)
1. **AC 1:** Halaman `/admin/pelatihan` tidak lagi memuat elemen tombol atau ikon "Scanner" maupun "Proyektor".
2. **AC 2:** Administrator tetap dapat membuat, membaca, memperbarui, menghapus pelatihan, memperbarui koordinat lokasi presensi, dan memasangkan instruktur tanpa ada gangguan atau error akibat pemisahan komponen.

### Halaman /admin/presensi (Rekap & Ekspor)
1. **AC 1:** Mengakses `/admin/presensi` menampilkan tabel daftar pelatihan yang ada di database.
2. **AC 2:** Mengklik salah satu pelatihan akan mengarahkan pengguna ke halaman detail/rekap kehadiran per pertemuan untuk pelatihan tersebut.
3. **AC 3:** Halaman detail/rekap presensi **hanya menampilkan** peserta yang memiliki status pendaftaran `confirmed` (Terkonfirmasi). Peserta dengan status lain (*pending*, *approved* tetapi belum verifikasi WA/Newbimma, *rejected*, *waitlist*) **tidak boleh muncul** di dalam tabel/rekap tersebut.
4. **AC 4:** Supervisor/Admin dapat mengubah status kehadiran peserta (misal: Hadir, Sakit, Izin, Alpa) melalui antarmuka web, dan perubahan tersebut langsung tersimpan ke database.
5. **AC 5:** Tombol "Export Excel" menghasilkan file spreadsheet `.xlsx` yang memuat tabel kehadiran peserta yang berstatus `confirmed` saja secara rapi. Peserta dengan status non-`confirmed` tidak diekspor.
6. **AC 6:** Tombol "Export PDF" menghasilkan file dokumen `.pdf` yang memuat rekapitulasi kehadiran peserta berstatus `confirmed` saja dengan format yang siap dicetak. Peserta dengan status non-`confirmed` tidak diekspor.

### Halaman /panitia/operasional (Operasional Lapangan)
1. **AC 1:** Mengakses `/panitia/operasional` hanya menampilkan daftar pelatihan yang tanggal mulainya $\le$ Hari Ini $\le$ tanggal selesainya (atau memiliki sesi aktif hari ini).
2. **AC 2:** Jika hari ini tidak ada pelatihan yang sedang berlangsung, sistem menampilkan pesan: *"Tidak ada pelatihan aktif hari ini."*
3. **AC 3:** Tombol **Buka Scanner Panitia** membuka halaman kamera scanner presensi peserta dengan benar.
4. **AC 4:** Tombol **Buka Layar Proyektor** membuka halaman tampilan QR Code dinamis (untuk discan peserta) dengan benar.
5. **AC 5:** Desain halaman `/panitia/operasional` mengikuti tema **Premium Dark Futuristic Glassmorphism** yang konsisten dengan bagian aplikasi lainnya dan dioptimalkan untuk perangkat seluler.

---

## 👥 Pembagian Tugas Tim

### 1. Database Designer (Eka)
*   Memastikan relasi tabel pelatihan, pertemuan, dan presensi/kehadiran peserta sudah optimal untuk kebutuhan rekap dan pencarian harian.
*   Jika diperlukan, menambahkan kolom penunjang log koreksi (misal: `koreksi_oleh`, `alasan_koreksi`, `waktu_koreksi` pada tabel presensi).

### 2. Backend Developer (Bayu)
*   Memisahkan logic controller pelatihan dengan controller presensi baru (`PresensiController`).
*   Membuat query pencarian kelas aktif hari ini untuk halaman operasional panitia.
*   Mengimplementasikan filter query agar data peserta yang di-load di rekapitulasi kehadiran `/admin/presensi` serta ekspor Excel dan PDF hanya peserta dengan status pendaftaran `confirmed`.
*   Mengimplementasikan endpoint/logic untuk koreksi kehadiran manual.
*   Mengintegrasikan library export Excel (misal: Laravel Excel) dan PDF (misal: DomPDF) untuk rekap kehadiran dengan menerapkan filter status `confirmed`.

### 3. Frontend Developer (Ayu)
*   Membersihkan layout halaman `/admin/pelatihan` dari tombol scanner & proyektor.
*   Mendesain antarmuka `/admin/presensi` (tabel interaktif rekapitulasi kehadiran, modal koreksi manual) dengan nuansa Glassmorphism, memastikan data peserta tersemat dengan benar.
*   Mendesain halaman `/panitia/operasional` yang minimalis, mobile-friendly, dengan tombol aksi besar yang jelas.

### 4. Tester / QA (Farhan)
*   Melakukan verifikasi fungsionalitas CRUD di halaman pelatihan pasca pembersihan tombol operasional.
*   Memastikan filter status pendaftaran bekerja dengan benar: hanya peserta `confirmed` yang tampil di halaman presensi `/admin/presensi`, dan peserta dengan status lain tidak muncul.
*   Menguji fungsionalitas filter "Hari Ini" pada halaman operasional dengan memanipulasi tanggal sistem/database.
*   Memastikan file Excel dan PDF hasil ekspor terunduh dengan data yang akurat (hanya memuat peserta `confirmed`) dan layout yang rapi.
*   Memastikan otorisasi akses (role permission) berjalan dengan benar untuk masing-masing halaman baru.
