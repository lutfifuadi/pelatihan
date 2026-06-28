# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Sistem Kehadiran Pelatihan Presisi (Dynamic QR Code Peserta + GPS Geofencing Panitia)

---

### Executive Summary

| Properti | Detail |
| :--- | :--- |
| **Nama Fitur** | Sistem Kehadiran Pelatihan Presisi (Presensi Presisi) |
| **Versi** | v1.2.1 |
| **Status** | Draft - Ready for Review |
| **Penulis** | Antigravity (PRD Specialist) |
| **Tanggal Pembuatan**| 28 Juni 2026 |
| **Prioritas** | Tinggi (P1) |
| **Target Rilis** | Q3 2026 |

#### Riwayat Versi & Perubahan (Changelog)
* **v1.1.0 (28 Juni 2026):** Rilis draf awal (Dynamic QR di proyektor, GPS di HP peserta, OTP fallback).
* **v1.2.0 (28 Juni 2026):** Pembaruan alur utama presensi. QR Code dinamis unik ditampilkan di HP peserta dengan status pendaftaran `confirmed`. Scanning dan verifikasi GPS Geofencing dipindahkan dari HP peserta ke HP Panitia. OTP dinamis dihilangkan dan diganti dengan fitur Pencarian & Bypass Manual oleh Panitia di lokasi. Proyektor hanya menampilkan monitoring progres real-time kelas.
* **v1.2.1 (28 Juni 2026):** Penambahan spesifikasi mekanisme Offline Scanning & Local Sync pada HP Panitia untuk mengatasi kendala internet putus/lambat di lapangan.

---

## 1. Latar Belakang & Tujuan Bisnis

### 1.1 Latar Belakang
Proses presensi kehadiran peserta pelatihan saat ini masih menghadapi berbagai kendala, baik dari sisi efisiensi waktu maupun integritas data. Metode konvensional seperti tanda tangan basah atau Google Form statis rentan terhadap praktik kecurangan seperti "titip absen" (presensi tanpa kehadiran fisik) dan memakan waktu berharga instruktur di awal sesi kelas (rata-rata 10–15 menit hanya untuk administrasi kehadiran).

Oleh karena itu, diperlukan suatu sistem kehadiran presisi berbasis platform web/PWA yang memanfaatkan teknologi **Dynamic QR Code** yang ditampilkan pada ponsel peserta secara unik dan diperbarui setiap 20 detik, dipadukan dengan pemindaian oleh Panitia menggunakan fitur **Scanner Presensi** di HP masing-masing. Verifikasi **GPS Geofencing** dilakukan langsung di perangkat HP Panitia saat memindai, dikombinasikan dengan mekanisme **Bypass Manual** oleh Panitia untuk menjamin kelancaran sistem dalam kondisi abnormal.

### 1.2 Tujuan Bisnis
* **Efisiensi Waktu Kelas:** Memangkas waktu administrasi presensi dari rata-rata 15 menit menjadi kurang dari 5 menit per sesi kelas pelatihan dengan menempatkan proses verifikasi kehadiran pada panitia tanpa menyita waktu instruktur.
* **Integritas Data Kehadiran:** Menghilangkan celah kecurangan titip absen (*zero fake attendance*) dengan mewajibkan verifikasi lokasi fisik secara *real-time* yang selaras dengan lokasi pelaksanaan pelatihan melalui perangkat panitia di lapangan dan QR Code dinamis yang terus berubah.
* **Kemudahan Operasional:** Menyediakan jalur alternatif (fallback) bagi peserta dengan kendala teknis (tidak membawa ponsel, baterai habis, atau kegagalan menampilkan QR Code) agar proses presensi tetap akurat dan cepat melalui bypass manual oleh panitia.

### 1.3 Metrik Keberhasilan (KPIs)
* **Average Check-in Time:** < 3 menit total per kelas bagi panitia untuk memindai seluruh peserta yang datang.
* **Accuracy Rate:** 100% data koordinat panitia tervalidasi berada di dalam radius toleransi saat pemindaian dilakukan (atau tercatat secara transparan didelegasikan melalui bypass panitia).
* **Adoption Rate:** > 95% peserta berhasil menunjukkan QR Code dinamis mandiri untuk dipindai oleh panitia.
* **Fraud Rate:** 0% kasus titip absen (karena pemindaian dilakukan secara fisik tatap muka oleh panitia menggunakan Dynamic QR Code yang beregenerasi pendek).

---

## 2. User Stories

| ID | Aktor | Pernyataan Story | Penerimaan Kriteria (Overview) |
| :--- | :--- | :--- | :--- |
| **US-01** | Instruktur / Admin | Sebagai seorang instruktur/admin, saya ingin menampilkan layar monitoring real-time di proyektor kelas agar progres kehadiran dan keaktifan kelas dapat terpantau langsung secara visual. | Menampilkan statistik kehadiran, grafik, dan daftar kartu nama/avatar peserta yang terupdate otomatis (real-time) begitu panitia selesai melakukan pemindaian. |
| **US-02** | Panitia / Admin | Sebagai seorang panitia, saya ingin memindai Dynamic QR Code peserta menggunakan kamera WebRTC di HP saya dan mengirimkannya beserta GPS HP saya agar kehadiran peserta tercatat dengan valid. | Aplikasi PWA scanner panitia meminta izin kamera & lokasi, membaca QR Code terenkripsi peserta, menghitung geofencing di backend berdasarkan koordinat HP panitia, dan mencatat presensi jika valid. |
| **US-03** | Peserta | Sebagai seorang peserta pelatihan yang status pendaftarannya `confirmed`, saya ingin menampilkan Dynamic QR Code unik yang diperbarui setiap 20 detik di HP saya agar dapat dipindai oleh panitia saat datang. | PWA peserta memvalidasi status `enrollments.status == 'confirmed'`, menampilkan QR Code dinamis terenkripsi dengan timestamp pendek yang beregenerasi tiap 20 detik. Jika tidak confirmed, QR tidak muncul. |
| **US-04** | Panitia / Admin | Sebagai seorang panitia, saya ingin memiliki opsi bypass manual (pencarian nama/NIK) di HP saya untuk mencatat kehadiran peserta yang HP-nya bermasalah/mati setelah saya memverifikasi identitas fisiknya. | PWA scanner panitia menyediakan fitur pencarian peserta, tombol "Bypass Presensi", pengisian alasan bypass, lalu mengirimkan request bypass ke backend. |
| **US-05** | Admin | Sebagai seorang admin, saya ingin melihat dashboard monitoring real-time yang menampilkan progres presensi semua kelas agar saya dapat memantau jalannya pelatihan secara menyeluruh. | Dashboard menampilkan total pelatihan, total peserta, jumlah hadir/belum, list kelas berlangsung dengan statistik real-time, dan filter berdasarkan tanggal/lokasi/instruktur. |
| **US-06** | Admin | Sebagai seorang admin, saya ingin mengelola data pelatihan (CRUD) termasuk menentukan lokasi, radius geofencing, dan meng-assign instruktur agar pelatihan dapat dikonfigurasi dengan benar. | Admin dapat membuat, mengedit, menghapus data pelatihan; mengatur latitude, longitude, radius toleransi; serta menetapkan instruktur dan daftar peserta. |
| **US-07** | Admin | Sebagai seorang admin, saya ingin mengexport laporan kehadiran ke format Excel dan PDF dengan filter yang lengkap agar saya dapat melakukan rekapitulasi dan pelaporan secara fleksibel. | Export laporan dengan format Excel & PDF; filter berdasarkan tanggal, pelatihan, peserta, instruktur, metode presensi; header: Nama Peserta, Status, Metode, Waktu, Jarak GPS Panitia, Scanner By, Keterangan Bypass. |
| **US-08** | Admin | Sebagai seorang admin, saya ingin melihat riwayat audit log termasuk aktivitas bypass dan koreksi data agar saya dapat melacak perubahan yang dilakukan oleh siapa pun. | Audit log menampilkan riwayat bypass (oleh panitia siapa, alasan, waktu) dan riwayat koreksi/admin actions dengan detail actor, aksi, timestamp. |

---

## 3. Peran & Hierarki (Role & Hierarchy)

Sistem kehadiran ini memiliki empat peran utama dengan hierarki akses sebagai berikut:

### 3.1 Admin
- **Akses Penuh:** Manajemen seluruh data pelatihan, peserta, panitia, dan instruktur.
- **Dashboard Monitoring:** Melihat progres presensi semua kelas secara real-time.
- **Rekap & Laporan:** Melakukan export laporan kehadiran dalam format Excel dan PDF.
- **Audit Log:** Mengakses seluruh riwayat aktivitas sistem, termasuk bypass dan koreksi data.
- **Manajemen Pengguna:** Mengelola akun instruktur, panitia, dan peserta.

### 3.2 Panitia (Multiple Users)
- **Scanner Presensi:** Mengakses fitur kamera WebRTC untuk memindai QR Code di HP peserta.
- **Bypass Manual:** Melakukan pencarian peserta dan menginput bypass kehadiran secara langsung dari HP Panitia dengan alasan tertentu jika peserta mengalami kendala perangkat.
- **Lokasi & GPS:** Wajib mengaktifkan izin GPS pada perangkat mereka saat melakukan scan/bypass untuk validasi geofencing lokasi pelatihan.

### 3.3 Instruktur
- **Akses Terbatas:** Hanya dapat mengakses kelas pelatihan yang diampu.
- **Layar Monitoring Kelas:** Menampilkan progres kehadiran berupa dashboard visual real-time di proyektor kelas.
- **Monitoring Kelas:** Melihat daftar peserta dan status kehadiran di kelasnya sendiri.

### 3.4 Peserta
- **Akses Minimal:** Hanya dapat melihat status kehadiran diri sendiri dan memunculkan Dynamic QR Code presensi.
- **Dynamic QR Code Display:** Memunculkan kode QR dinamis unik yang diperbarui setiap 20 detik di HP mereka, dengan syarat status pendaftaran peserta (`enrollments.status`) adalah **`confirmed`**.

---

## 4. Spesifikasi Fitur

### Fitur A: Layar Progres & Monitoring Kelas Real-time (Proyektor Instruktur)
Fitur ini diakses oleh Instruktur melalui Laptop yang terhubung ke proyektor di ruangan pelatihan.
1. **Visualisasi Kehadiran:**
   - Menampilkan grafik interaktif/statistika persentase kehadiran kelas saat itu (contoh: "Hadir: 18 / 25 Peserta - 72%").
2. **Real-time Attendance Polling/List:**
   - Menampilkan daftar kartu nama/avatar peserta yang terupdate otomatis secara *real-time* begitu panitia melakukan pemindaian (menggunakan polling berdurasi pendek, Server-Sent Events, atau WebSocket). Ketika peserta sukses di-scan oleh panitia, namanya akan muncul di layar proyektor dengan efek animasi transisi masuk yang menarik.

### Fitur B: Mobile Web/PWA Display QR (Peserta)
Diakses oleh peserta pelatihan menggunakan ponsel mereka (browser / Progressive Web App).
1. **Dynamic QR Code Generator:**
   - Menghasilkan QR Code yang berisi payload terenkripsi (token JWT berumur pendek / *short-lived token*).
   - QR Code wajib diperbarui (regenerate) secara otomatis setiap **20 detik** untuk mencegah screenshot QR dibagikan ke luar lokasi kelas.
   - Dilengkapi dengan *progress bar* visual atau *countdown timer* (20s) untuk menunjukkan sisa waktu validitas QR saat ini.
2. **Kondisi Tampilan (Status Enrollment):**
   - Sistem melakukan pengecekan status pendaftaran peserta. Tombol "Tampilkan QR Code" atau QR Code itu sendiri **hanya akan muncul jika status pendaftaran peserta (`enrollments.status`) adalah `confirmed`** (Terkonfirmasi - sudah lolos verifikasi WA & NewBimma).
   - Jika status pendaftaran peserta di luar `confirmed` (misal: `pending`, `rejected`, dll.), tombol/QR Code akan disembunyikan dan sistem menampilkan pesan edukatif: *"QR Code presensi tidak dapat ditampilkan karena status pendaftaran Anda belum Terkonfirmasi (Confirmed)"*.

### Fitur C: Mobile Web/PWA Scanner & Bypass (Panitia)
Diakses oleh panitia pelatihan (bisa multi-panitia yang bertugas bersamaan) menggunakan ponsel mereka.
1. **Kamera WebRTC (QR Reader):**
   - Integrasi library pembaca QR (seperti HTML5-QRCode atau instascan) using API WebRTC di browser HP Panitia.
   - Memberikan respon getar (Web Vibration API) atau bunyi bip instan saat scan sukses untuk mempercepat proses antrean.
2. **Geolocation API Integration (GPS Panitia):**
   - Browser HP Panitia meminta koordinat GPS presisi tinggi (`enableHighAccuracy: true`, `timeout: 10000`, `maximumAge: 0`) saat proses memindai dilakukan.
   - Koordinat yang diambil mencakup: `latitude`, `longitude`, dan tingkat akurasi (`accuracy` dalam meter) dari HP Panitia.
3. **Validasi Geofencing & Token di Backend:**
   - Server membandingkan koordinat GPS **HP Panitia** saat memindai QR Code dengan koordinat pusat lokasi pelatihan (lokasi kelas) yang tersimpan di database.
   - **Rumus Jarak (Haversine Formula):** Digunakan untuk menghitung jarak antara kedua titik koordinat tersebut.
   - **Aturan Validasi:**
     - Status pendaftaran peserta harus `confirmed`.
     - Jarak koordinat GPS HP Panitia harus $\le$ Radius Toleransi (default: 50 meter) dari pusat lokasi pelatihan.
     - Masa berlaku token QR Code peserta belum kadaluarsa (< 20 detik dari waktu generate).
     - Jika semua valid, kehadiran dicatat sebagai `present` dengan `verified_method = 'QR'` (atau 'Admin Scan') dan mencatat ID panitia pen-scan.
4. **Offline Scanning & Local Storage:**
   - Aplikasi secara otomatis mendeteksi status koneksi internet panitia (online/offline) menggunakan Web API (`navigator.onLine` & event listener `online`/`offline`).
   - Saat offline, PWA scanner tetap aktif untuk memindai QR Code peserta.
   - Hasil pemindaian yang berhasil disimpan sementara secara lokal di memori HP Panitia (menggunakan IndexedDB atau LocalStorage) dalam bentuk antrean (*queue*).
   - Payload data scan offline yang disimpan meliputi: `qr_token`, `scan_timestamp` (waktu pemindaian yang diambil dari jam internal HP Panitia saat pemindaian berhasil), `latitude_panitia`, dan `longitude_panitia`.
   - Tampilan UI PWA Scanner memberikan feedback visual yang jelas saat berada di mode offline (seperti indikator status offline mencolok, bunyi bip/getar yang tetap berfungsi ketika memindai, dan angka penghitung/badge antrean scan offline yang belum tersinkronisasi).
5. **Auto-Synchronization:**
   - Begitu koneksi internet pada perangkat panitia terdeteksi aktif kembali (online), aplikasi secara otomatis memicu proses pengiriman (sync) antrean data presensi offline yang tersimpan di local storage ke server backend secara berurutan (*sequential queue processing / FIFO*).
6. **Pencarian & Bypass Manual:**
   - Jika peserta tidak membawa HP, HP mati, layar retak, atau kamera HP peserta bermasalah sehingga QR Code tidak bisa digenerate/ditampilkan.
   - Panitia dapat mengetik nama atau NIK peserta pada kolom pencarian di fitur Scanner HP Panitia.
   - Setelah memverifikasi identitas fisik peserta, panitia menekan tombol **"Bypass Presensi"**.
   - Panitia wajib memilih/memasukkan alasan bypass (misal: "HP Peserta Mati/Baterai Habis", "Layar HP Pecah", "Peserta Tidak Membawa HP").
   - Status presensi peserta di database ditandai sebagai `present` dengan `verified_method = 'Manual'` (atau 'Admin Bypass') dan mencatat ID panitia yang membypass serta koordinat lokasi HP panitia.

### Fitur D: Admin Dashboard Monitoring
Fitur ini diakses oleh Admin setelah login ke panel administrasi.

1. **Overview Real-time:**
   - Menampilkan ringkasan (*summary cards*) berisi:
     - Total pelatihan yang berlangsung hari ini.
     - Total peserta terdaftar di semua pelatihan aktif.
     - Jumlah peserta sudah hadir (*checked-in*).
     - Jumlah peserta belum hadir (*pending*).
   - Data diperbarui secara real-time menggunakan polling periodik atau WebSocket.

2. **Daftar Kelas Aktif:**
   - Tabel/list berisi seluruh kelas pelatihan yang sedang berlangsung, dengan informasi:
     - Nama pelatihan, tanggal, lokasi.
     - Nama instruktur yang bertugas.
     - Progress kehadiran (contoh: "12/25 Hadir" dengan progress bar).
     - Tombol aksi untuk melihat detail kelas.

3. **Filter & Pencarian:**
   - Filter berdasarkan tanggal (range date picker).
   - Filter berdasarkan lokasi pelatihan.
   - Filter berdasarkan instruktur (dropdown / autocomplete).
   - Pencarian teks bebas (nama pelatihan, nama peserta).

4. **Detail Kelas:**
   - Halaman detail menampilkan daftar seluruh peserta kelas berikut:
     - Status kehadiran masing-masing.
     - Metode presensi yang digunakan (QR/Manual).
     - Waktu presensi.
     - Jarak dari pusat pelatihan (dalam meter, diukur dari GPS HP Panitia).
     - Scanner By (Panitia yang memindai).
     - Keterangan bypass jika ada.

### Fitur E: Manajemen Data Pelatihan, Peserta & Instruktur
Fitur CRUD (Create, Read, Update, Delete) untuk mengelola seluruh master data pelatihan.

1. **Manajemen Pelatihan:**
   - Form create/edit pelatihan dengan field:
     - Nama pelatihan (wajib).
     - Tanggal pelaksanaan (wajib, dapat berupa range tanggal).
     - Lokasi pelatihan (teks alamat).
     - Latitude & Longitude (koordinat pusat pelatihan — dapat diambil dari peta interaktif).
     - Radius toleransi geofencing (default: 50 meter, dapat disesuaikan).
     - Status pelatihan (aktif/selesai/dibatalkan).
   - Daftar/list seluruh pelatihan dengan opsi edit, hapus, dan view.
   - Soft delete untuk keamanan data.

2. **Assign Instruktur:**
   - Dropdown/selector untuk memilih instruktur yang akan mengampu pelatihan.
   - Validasi: satu instruktur dapat mengampu banyak pelatihan, namun tidak boleh bentrok jadwal (pada jam yang sama).

3. **Daftar Peserta per Pelatihan:**
   - Tabel daftar peserta yang terdaftar pada suatu pelatihan.
   - Fitur import peserta dari file Excel/CSV (nama, email, NIK/NIP).
   - Fitur tambah/hapus peserta secara manual.
   - Setiap peserta yang terdaftar otomatis memiliki akses ke halaman presensi PWA untuk pelatihan tersebut.

### Fitur F: Rekapitulasi & Laporan
Fitur untuk menghasilkan laporan kehadiran yang dapat diexport.

1. **Halaman Rekapitulasi:**
   - Tabel rekap kehadiran dengan kolom:
     - No, Nama Peserta, Status (Hadir/Tidak Hadir/Izin/Sakit), Metode Presensi, Waktu Presensi, Jarak GPS Panitia (meter), Scanner By, Keterangan Bypass.
   - Filter yang dapat dikombinasikan:
     - Rentang tanggal.
     - Pelatihan tertentu.
     - Peserta tertentu (by name).
     - Instruktur tertentu.
     - Metode presensi (QR/Manual).

2. **Export Excel (.xlsx):**
   - Tombol export ke Excel dengan format yang rapi (styled header, auto-column-width).
   - File terdownload otomatis dengan nama: `Rekap_Kehadiran_{NamaPelatihan}_{Tanggal}.xlsx`.

3. **Export PDF:**
   - Tombol export ke PDF dengan layout tabel portrait/landscape.
   - Menampilkan header kop laporan (judul, tanggal pelatihan, lokasi).
   - Footer nomor halaman dan timestamp cetak.

### Fitur G: Audit Log
Fitur pencatatan seluruh aktivitas penting dalam sistem untuk keperluan audit dan kepatuhan.

1. **Riwayat Bypass:**
   - Mencatat setiap aktivitas bypass manual yang dilakukan panitia.
   - Detail log: nama peserta yang di-bypass, nama panitia pelaku bypass, alasan bypass, koordinat GPS HP Panitia, timestamp.
   - Tampilan dalam bentuk tabel kronologis terurut (descending by timestamp).

2. **Riwayat Koreksi / Admin Actions:**
   - Mencatat setiap perubahan data yang dilakukan oleh admin (create/edit/delete pelatihan, ubah status kehadiran, hapus peserta, dll).
   - Detail log: actor (admin), action (create/update/delete), target entity, detail perubahan, IP address actor, timestamp.
   - Tidak dapat dihapus atau dimodifikasi (*immutable log*).

3. **Fitur Pencarian & Filter Audit Log:**
   - Filter berdasarkan rentang tanggal.
   - Filter berdasarkan tipe aksi (bypass, koreksi, create, delete).
   - Filter berdasarkan actor (admin/panitia/instruktur).
   - Pencarian teks bebas.

---

## 5. Spesifikasi Data & Database (High-Level)

### 5.1 Modifikasi Skema Database Eksisting

#### Tabel `pelatihan` (atau `lokasi_pelatihan`)
Tabel ini menyimpan data pelatihan serta konfigurasi geofencing lokasi pelatihan tersebut.
```sql
ALTER TABLE pelatihans ADD COLUMN (
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    radius_toleransi INT DEFAULT 50 COMMENT 'Radius toleransi dalam satuan meter'
);
```

#### Tabel `attendances` (atau `presensi`)
Tabel ini mencatat setiap aktivitas presensi peserta dengan detail metadata untuk audit dan analisis keamanan.
```sql
CREATE TABLE attendances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pelatihan_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    status ENUM('present', 'absent', 'permit', 'sick') NOT NULL DEFAULT 'present',
    verified_method ENUM('QR', 'Manual') NOT NULL COMMENT 'QR: Scan oleh Panitia, Manual: Bypass oleh Panitia',
    latitude_panitia DECIMAL(10, 8) NULL COMMENT 'GPS perangkat Panitia yang memindai',
    longitude_panitia DECIMAL(11, 8) NULL COMMENT 'GPS perangkat Panitia yang memindai',
    distance_from_center INT NULL COMMENT 'Jarak kalkulasi GPS Panitia dari pusat lokasi pelatihan (meter)',
    ip_address VARCHAR(45) NULL COMMENT 'Melacak IP address Panitia',
    device_user VARCHAR(255) NULL COMMENT 'User-Agent browser Panitia',
    scanner_by BIGINT UNSIGNED NULL COMMENT 'User ID Panitia/Admin yang melakukan scan',
    bypassed_by BIGINT UNSIGNED NULL COMMENT 'User ID Panitia/Admin jika dibypass',
    bypass_reason VARCHAR(255) NULL,
    corrected_by BIGINT UNSIGNED NULL COMMENT 'User ID admin yang melakukan koreksi manual pasca-kelas',
    corrected_at TIMESTAMP NULL COMMENT 'Waktu koreksi dilakukan',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (pelatihan_id) REFERENCES pelatihans(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (scanner_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (bypassed_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (corrected_by) REFERENCES users(id) ON DELETE SET NULL
);
```

#### Tabel `audit_logs`
Tabel ini mencatat seluruh aktivitas audit trail secara kronologis dan immutable.
```sql
CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    actor_id BIGINT UNSIGNED NOT NULL COMMENT 'User ID pelaku aksi',
    actor_role ENUM('admin', 'panitia', 'instruktur') NOT NULL COMMENT 'Role pelaku',
    action_type ENUM('create', 'update', 'delete', 'bypass', 'correct', 'export', 'login') NOT NULL COMMENT 'Tipe aksi',
    target_entity VARCHAR(50) NOT NULL COMMENT 'Entitas yang diubah (pelatihan, attendance, user, dll)',
    target_id BIGINT UNSIGNED NULL COMMENT 'ID entitas target',
    description TEXT NULL COMMENT 'Deskripsi detail aksi',
    old_data JSON NULL COMMENT 'Snapshot data sebelum perubahan',
    new_data JSON NULL COMMENT 'Snapshot data setelah perubahan',
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (actor_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 5.2 Struktur Payload Token QR Code (JWT-like)
Untuk mengamankan QR Code agar tidak bisa dipalsukan, payload di-generate di HP peserta secara terenkripsi atau ditandatangani menggunakan session token rahasia pendaftaran peserta.
```json
{
  "enrollment_id": 1024,
  "user_id": 412,
  "timestamp_generated": 1782635200,
  "expire_at": 1782635220,
  "signature": "a5d8f7e2c6b4a3c1f0..."
}
```

---

## 6. Alur Pengguna (User Flow)

### 6.1 Alur Sukses (Happy Path)
1. Peserta datang ke lokasi pelatihan dengan membawa HP yang memuat PWA Pelatihanku.
2. Peserta membuka PWA Pelatihanku di HP mereka.
3. Sistem mendeteksi status pendaftaran peserta (`enrollments.status`). Karena statusnya adalah **`confirmed`**, tombol untuk memunculkan Dynamic QR Code ditampilkan.
4. Peserta menekan tombol dan menampilkan **Dynamic QR Code** unik miliknya (berisi data enrollment terenkripsi, beregenerasi setiap 20 detik).
5. Panitia membuka PWA Admin/Panitia di HP masing-masing dan memilih fitur **Scanner Presensi**.
6. Browser HP Panitia meminta izin kamera (WebRTC) dan lokasi (GPS). Panitia menyetujuinya.
7. Panitia mengarahkan kamera HP-nya ke Dynamic QR Code yang ditampilkan pada layar HP peserta.
8. PWA Panitia membaca payload QR Code peserta, mengambil koordinat GPS terkini dari HP Panitia, lalu mengirimkan data tersebut ke server backend via API.
9. Server backend memvalidasi:
   - Masa berlaku token QR Code peserta (belum kadaluarsa < 20 detik).
   - Status pendaftaran peserta harus `confirmed`.
   - Jarak koordinat GPS HP Panitia harus berada di dalam radius toleransi lokasi pelatihan (Haversine formula $\le$ 50 meter).
10. Server mencatat kehadiran peserta dengan `status = 'present'`, `verified_method = 'QR'`, dan mencatat `scanner_by = ID_Panitia`.
11. Layar proyektor monitoring kelas (menghadap ke peserta) secara otomatis terupdate menampilkan nama peserta tersebut telah hadir dengan efek animasi masuk.
12. HP Panitia bergetar singkat menandakan pemindaian sukses, dan layar HP peserta memperbarui status kehadiran diri sendiri.

### 6.2 Alur Gagal (Sad Path)

#### Kasus A: HP Panitia Diluar Radius Geofencing (Jarak > 50 Meter)
1. Panitia mencoba memindai QR Code peserta, namun koordinat GPS HP Panitia berada di luar radius lokasi pelatihan (misalnya, panitia mencoba memindai di area parkir luar yang jauh, atau di tempat lain).
2. Server menghitung jarak lokasi HP Panitia dengan lokasi pelatihan, mendapatkan hasil 80 meter.
3. Server menolak request presensi dan mengembalikan response error: `422 Unprocessable Entity` dengan kode error `OUT_OF_RANGE`.
4. Aplikasi Scanner Panitia menampilkan pesan: *"Gagal mencatat presensi. Posisi GPS Anda berada di luar radius lokasi pelatihan (terdeteksi: 80 meter)"*.

#### Kasus B: QR Code Peserta Kadaluarsa (Expired)
1. Panitia mengarahkan kamera ke HP peserta, namun peserta sudah membuka halaman QR Code tersebut sejak tadi sehingga tokennya sudah melewati batas 20 detik sebelum terkirim ke server backend.
2. Server mendeteksi timestamp token di payload QR yang dikirim sudah lewat dari `expire_at`.
3. Server menolak request dan mengembalikan response error: `422 Unprocessable Entity` dengan kode error `QR_EXPIRED`.
4. Aplikasi Scanner Panitia menampilkan pesan: *"QR Code kedaluwarsa. Silakan minta peserta untuk me-refresh QR Code terbaru pada layarnya"*.

#### Kasus C: Status Enrollment Peserta belum Confirmed
1. Peserta membuka PWA Pelatihanku, namun status pendaftaran (`enrollments.status`) miliknya masih `pending` atau `rejected` (belum lolos verifikasi WA & NewBimma).
2. PWA tidak menampilkan tombol presensi maupun QR Code dinamis.
3. PWA peserta hanya menampilkan pesan: *"QR Code presensi tidak tersedia karena pendaftaran Anda belum Terkonfirmasi"* sehingga peserta tidak dapat menyerahkan QR Code untuk di-scan oleh panitia.

#### Kasus D: Penggunaan Bypass Manual oleh Panitia (Kendala Perangkat/HP Peserta)
1. Peserta tidak membawa HP, HP mati karena baterai habis, atau layar HP pecah parah sehingga QR Code tidak bisa digenerate/dipindai.
2. Peserta melapor kepada Panitia di pintu masuk kelas.
3. Panitia memverifikasi identitas fisik peserta secara langsung (meminta KTP/kartu peserta).
4. Panitia membuka PWA Scanner di HP-nya, beralih ke menu **"Pencarian & Bypass"**, lalu mencari nama/NIK peserta tersebut.
5. Panitia menekan tombol **"Bypass Presensi"** untuk peserta bersangkutan.
6. Panitia wajib memasukkan alasan bypass (misal: "HP Peserta Mati").
7. Server memvalidasi GPS HP Panitia (harus $\le 50$ meter) saat tombol bypass ditekan.
8. Jika valid, kehadiran dicatat dengan status `present`, `verified_method = 'Manual'`, dan `bypassed_by = ID_Panitia`. Layar proyektor langsung memunculkan nama peserta tersebut secara real-time.

#### Kasus E: Alur Presensi Mode Offline & Sinkronisasi Otomatis
1. Panitia membuka PWA Scanner di pintu masuk kelas. Sistem mendeteksi bahwa koneksi internet terputus atau sangat lambat, lalu beralih ke **Mode Offline** dengan indikator visual status offline berwarna kuning/merah yang mencolok di layar HP Panitia.
2. Peserta menunjukkan Dynamic QR Code terenkripsi dari HP miliknya (PWA Peserta memunculkan QR Code yang beregenerasi tiap 20 detik menggunakan basis waktu tersinkronisasi terakhir).
3. Panitia mengarahkan kamera HP ke QR Code peserta.
4. PWA Scanner Panitia berhasil memindai QR Code, HP Panitia bergetar singkat dan berbunyi bip, serta counter antrean offline di layar bertambah (+1).
5. Aplikasi PWA Scanner merekam data scan ke dalam IndexedDB/LocalStorage perangkat panitia:
   - `qr_token`
   - `scan_timestamp` (waktu lokal saat scan berhasil dilakukan di device panitia)
   - `latitude_panitia` & `longitude_panitia` (koordinat GPS panitia saat memindai)
6. Panitia terus melanjutkan pemindaian peserta lain secara offline; counter antrean di HP Panitia terus bertambah (misalnya, menampilkan: "5 antrean scan offline belum tersinkronisasi").
7. Saat koneksi internet HP Panitia terdeteksi pulih (online), event listener `online` di PWA akan memicu modul **Auto-Sync**.
8. PWA Scanner secara otomatis mengirimkan payload antrean data offline secara berurutan (FIFO) ke API Endpoint Backend (`/api/attendances/sync-offline`).
9. Backend menerima payload data sync offline, lalu melakukan validasi khusus:
   - Mendekripsi/memverifikasi signature token QR (`qr_token`).
   - Memvalidasi selisih waktu: Backend membandingkan `timestamp_generated` yang tertanam dalam payload token QR peserta dengan `scan_timestamp` yang dikirim dari HP Panitia. Selisihnya harus $\le$ 20 detik saat scan offline tersebut terjadi (bukan dibandingkan dengan waktu saat server menerima request).
   - Memvalidasi Geofencing: Menggunakan koordinat `latitude_panitia` dan `longitude_panitia` yang direkam pada saat scan offline dilakukan untuk dihitung jaraknya dengan koordinat pusat kelas. Jarak harus berada di bawah atau sama dengan radius toleransi ($\le$ 50 meter).
   - Mencegah Replay Attack: Memeriksa apakah token QR peserta tersebut sudah pernah diproses sebelumnya (oleh panitia yang sama atau berbeda). Jika sudah pernah di-scan dan sukses, request berikutnya dengan token yang sama akan ditolak.
10. Setelah backend memvalidasi dan mencatat kehadiran peserta secara sukses, backend mengirimkan response sukses ke PWA, dan item antrean di IndexedDB HP Panitia dihapus satu demi satu hingga counter kembali ke angka 0. Layar proyektor kelas langsung terupdate secara real-time.

### 6.3 Alur Admin & Panitia (Admin & Crew Flow)

#### Alur E: Admin & Panitia Dashboard Monitoring
1. Admin/Panitia login ke panel administrasi/PWA.
2. Admin/Panitia membuka halaman Dashboard Monitoring.
3. Sistem menampilkan kartu ringkasan (total pelatihan hari ini, total peserta, jumlah hadir/belum).
4. Sistem menampilkan daftar kelas aktif dengan progress bar kehadiran.
5. Pengguna dapat menggunakan filter (tanggal, lokasi, instruktur) untuk mempersempit tampilan.
6. Pengguna mengklik salah satu kelas untuk melihat detail presensi peserta.

#### Alur F: Manajemen Pelatihan (CRUD)
1. Admin membuka menu "Manajemen Pelatihan".
2. Admin melihat daftar seluruh pelatihan yang pernah/akan dilaksanakan.
3. Admin mengklik tombol "Tambah Pelatihan Baru".
4. Admin mengisi form: nama, tanggal, alamat lokasi, koordinat (latitude/longitude), radius toleransi.
5. Admin memilih instruktur yang akan mengampu dari dropdown.
6. Admin menekan tombol "Simpan". Pelatihan baru tersimpan dan muncul di daftar.
7. Admin dapat mengedit atau menghapus pelatihan yang sudah ada.

#### Alur G: Import & Kelola Peserta
1. Admin membuka halaman detail pelatihan tertentu.
2. Admin mengklik tab "Peserta".
3. Admin dapat menambahkan peserta satu per satu melalui form, atau mengimport dari file Excel/CSV.
4. Admin mengupload file Excel/CSV yang berisi daftar peserta (nama, email, NIK/NIP).
5. Sistem memvalidasi data dan menambahkan peserta ke dalam daftar.
6. Admin dapat menghapus peserta dari daftar jika diperlukan.

#### Alur H: Export Laporan
1. Admin membuka menu "Rekap & Laporan".
2. Admin memilih filter yang diinginkan (rentang tanggal, pelatihan, instruktur, metode presensi).
3. Admin mengklik tombol "Export Excel" atau "Export PDF".
4. Sistem menghasilkan file laporan dan mendownload-nya secara otomatis.
5. File laporan berisi header: Nama Peserta, Status, Metode Presensi, Waktu Presensi, Jarak GPS Panitia, Scanner By, Keterangan Bypass.

#### Alur I: Audit Log
1. Admin membuka menu "Audit Log".
2. Sistem menampilkan tabel kronologis seluruh aktivitas sistem (bypass, koreksi, CRUD data).
3. Admin dapat memfilter berdasarkan tanggal, tipe aksi, atau actor.
4. Admin mengklik suatu baris log untuk melihat detail lengkap (termasuk snapshot data sebelum/sesudah perubahan jika ada).

---

## 7. Acceptance Criteria (Kriteria Penerimaan)

### 7.1 Fungsional

#### Kriteria Proyektor Instruktur
* **AC-1.1:** Halaman proyektor harus memuat dan memperbarui visualisasi dashboard monitoring kelas real-time tanpa refresh seluruh halaman.
* **AC-1.2:** Daftar nama/avatar peserta yang sukses melakukan presensi harus dimutakhirkan secara real-time (tenggat waktu tunda/latency maksimum 3 detik setelah disetujui server).

#### Kriteria PWA/Mobile Web Peserta
* **AC-2.1:** Tombol memunculkan Dynamic QR Code hanya akan ditampilkan jika status pendaftaran peserta (`enrollments.status`) bernilai `confirmed`. Jika tidak, akan muncul pesan edukasi bahwa status belum dikonfirmasi.
* **AC-2.2:** Dynamic QR Code yang ditampilkan harus beregenerasi setiap 20 detik secara otomatis menggunakan state reaktif di frontend.
* **AC-2.3:** Payload QR Code harus dienkripsi secara aman dengan timestamp pendek untuk mencegah penggunaan token yang kedaluwarsa.

#### Kriteria PWA Scanner & GPS Panitia
* **AC-3.1:** Aplikasi scanner panitia wajib meminta izin akses kamera WebRTC dan lokasi GPS presisi tinggi saat pertama kali dibuka.
* **AC-3.2:** Geolocation API harus dijalankan di HP Panitia saat proses pemindaian QR Code untuk mendapatkan koordinat real-time panitia (`latitude_panitia`, `longitude_panitia`).
* **AC-3.3:** Jarak geofencing dihitung secara presisi menggunakan Haversine Formula di sisi backend untuk memastikan HP Panitia berada di dalam radius toleransi (default: 50 meter) dari titik pusat lokasi pelatihan.
* **AC-3.4:** Scanner panitia harus mendukung getaran singkat (Web Vibration API) saat pemindaian sukses untuk mempermudah antrean peserta yang padat.
* **AC-3.5:** Sistem harus menangani multi-panitia yang memindai secara bersamaan dengan memvalidasi setiap token QR Code unik peserta dan mencatat ID panitia pen-scan pada field `scanner_by`.

#### Kriteria Pencarian & Bypass Manual Panitia
* **AC-4.1:** Fitur pencarian peserta harus memungkinkan panitia mencari peserta berdasarkan Nama atau NIK secara cepat di HP Panitia.
* **AC-4.2:** Aksi bypass hanya dapat dieksekusi oleh user dengan peran `panitia` atau `admin`.
* **AC-4.3:** Setiap aktivitas bypass wajib mencatat `bypassed_by` (ID Panitia), `bypass_reason` (alasan bypass), dan koordinat lokasi HP Panitia ke database.

### 7.2 Non-Fungsional & Performa
* **AC-5.1 (Latensi):** Proses verifikasi presensi (sejak panitia scan QR hingga backend mengembalikan response sukses/gagal) harus diselesaikan dalam waktu kurang dari **1.5 detik** pada kondisi jaringan 3G/4G standar.
* **AC-5.2 (Skalabilitas):** Server backend harus mampu melayani beban request presensi bersamaan (*concurrent requests*) dari 100 panitia/scan secara bersamaan dalam waktu 5 detik (ketika jam masuk kelas serentak).
* **AC-5.3 (PWA Offline Capability):** Halaman awal scan panitia harus dapat diakses offline, namun pengiriman data verifikasi tetap memerlukan koneksi internet aktif. Jika koneksi putus saat mengirim data, tampilkan pesan error: *"Koneksi internet bermasalah, silakan coba beberapa saat lagi"*.

### 7.3 User Experience (UX)
* **AC-6.1 (Responsif):** Desain halaman PWA untuk peserta dan panitia harus responsif dan dioptimalkan khusus untuk layar ponsel berukuran kecil (lebar minimal 320px).
* **AC-6.2 (Feedback Visual):**
  - Pemindaian yang berhasil harus langsung ditandai dengan perubahan warna frame kamera scanner panitia menjadi hijau, getaran singkat ponsel, dan suara bip sukses.
  - Pemindaian yang gagal harus memberikan feedback visual frame merah dengan pesan error yang jelas dan mudah dibaca di bagian bawah layar.

### 7.4 Keamanan & Privasi
* **AC-7.1 (QR Tampering Prevention):** Token QR Code yang di-generate peserta harus memiliki tanda tangan digital (HMAC/Signature) menggunakan kunci rahasia (*secret key*) yang disimpan aman di server backend. Jika payload didekripsi atau dimodifikasi tanpa signature yang valid, server wajib memblokir permintaan dengan status `403 Forbidden`.
* **AC-7.2 (Strict Geofencing Validation):** Sisi backend tidak boleh mempercayai koordinat yang dikirim tanpa validasi silang (cross-check IP address untuk mendeteksi penggunaan VPN luar negeri).
* **AC-7.3 (GPS Spoofing Detection):** Sistem harus berupaya menolak presensi jika browser panitia mendeteksi bahwa lokasi dibagikan oleh aplikasi mock location.
* **AC-7.15:** PWA scanner panitia harus dapat memindai dan menyimpan data hingga minimal 100 antrean presensi secara offline tanpa crash atau kehilangan data pada penyimpanan lokal (IndexedDB/LocalStorage).
* **AC-7.16:** Sinkronisasi otomatis (auto-sync) wajib berjalan secara background dalam waktu < 3 detik setelah koneksi internet perangkat panitia terdeteksi pulih (online).
* **AC-7.17:** Backend wajib menolak data sinkronisasi jika selisih waktu antara waktu pemindaian lokal panitia (`scan_timestamp`) dengan waktu generate token QR peserta (`timestamp_generated`) melebihi 20 detik, atau jika koordinat GPS yang direkam saat offline melanggar batas radius toleransi geofencing ($\le$ 50 meter).

### 7.5 Kriteria Admin Dashboard & Manajemen

#### Kriteria Dashboard Monitoring
* **AC-8.1:** Dashboard admin/instruktur harus menampilkan data secara real-time dengan latency maksimal 5 detik dari waktu kejadian.
* **AC-8.2:** Kartu ringkasan (summary cards) harus menampilkan angka yang akurat dan dapat diklik untuk menuju detail terkait.
* **AC-8.3:** Filter tanggal, lokasi, dan instruktur harus berfungsi dengan benar dan merespon dalam waktu < 2 detik.
* **AC-8.4:** Halaman detail kelas harus menampilkan daftar peserta lengkap dengan status, metode, waktu presensi, jarak GPS Panitia, nama panitia scanner, dan keterangan bypass.

#### Kriteria Manajemen Data (CRUD)
* **AC-8.5:** Form create/edit pelatihan harus memvalidasi semua field wajib dan memberikan pesan error yang jelas jika ada kesalahan input.
* **AC-8.6:** Peta interaktif untuk memilih koordinat lokasi harus berfungsi dengan benar dan mengisi field latitude/longitude secara otomatis.
* **AC-8.7:** Import peserta dari Excel/CSV harus memvalidasi format file dan memberikan laporan jumlah sukses/gagal.
* **AC-8.8:** Soft delete pada pelatihan tidak boleh menghapus data terkait (attendances, logs) secara permanen.

#### Kriteria Rekapitulasi & Laporan
* **AC-8.9:** Export Excel harus menghasilkan file .xlsx yang dapat dibuka di Microsoft Excel, LibreOffice, dan Google Sheets tanpa error.
* **AC-8.10:** Export PDF harus memiliki format yang rapi dengan header kop laporan, tabel, dan nomor halaman.
* **AC-8.11:** Semua filter laporan harus dapat dikombinasikan dan menghasilkan data yang sesuai.

#### Kriteria Audit Log
* **AC-8.12:** Setiap aksi bypass wajib tercatat di audit_logs dalam waktu kurang dari 1 detik setelah aksi selesai.
* **AC-8.13:** Data audit_logs tidak dapat diubah atau dihapus oleh siapapun termasuk admin (immutable).
* **AC-8.14:** Filter audit log harus berfungsi untuk semua kriteria (tanggal, tipe aksi, actor) secara independen maupun kombinasi.

---

## 8. Aspek Keamanan & Privasi (UU PDP & GDPR Compliance)

Karena fitur ini mengumpulkan data pribadi berupa data lokasi fisik (koordinat GPS) peserta pelatihan, kepatuhan terhadap undang-undang perlindungan data pribadi (UU PDP No. 27 Tahun 2022) sangatlah penting.

### 8.1 Persetujuan Penggunaan Data (Consent Management)
* Sebelum PWA mengaktifkan kamera dan GPS untuk pertama kalinya, sistem wajib menampilkan pop-up lembar persetujuan (*Consent Screen*) yang menjelaskan secara transparan:
  * Mengapa koordinat GPS (untuk perangkat panitia) dan akses kamera (untuk scanner panitia) dikumpulkan.
  * Bagaimana data tersebut digunakan (hanya untuk verifikasi presensi berdasarkan lokasi pelaksanaan pelatihan).
  * Bahwa data koordinat presensi panitia tidak akan dibagikan ke pihak ketiga dan tidak dilacak secara terus menerus (*no continuous background tracking*). Pelacakan lokasi hanya berjalan sesaat sewaktu tombol scan atau bypass ditekan (*one-time foreground tracking*).
* Pengguna memiliki hak untuk menyetujui atau menolak. Jika menolak, PWA tidak dapat memproses pemindaian kehadiran secara otomatis.

### 8.2 Retensi Data & Minimasi Data
* Data koordinat presensi (`latitude_panitia` & `longitude_panitia`) hanya disimpan untuk kepentingan verifikasi kehadiran kelas.
* Kebijakan retensi: Koordinat mentah (latitude, longitude) akan di-samarkan (*anonymized/masked*) atau dihapus setelah pelatihan selesai sepenuhnya (misal 90 hari setelah kelas berakhir), dan yang disisakan hanya data agregasi kehadiran (`status`, `verified_method`, dan `distance_from_center` untuk keperluan audit).

### 8.3 Pengamanan Token QR
* Token QR yang di-generate tidak boleh mengekspos data sensitif peserta atau data internal server secara polos.
* Setiap token QR memiliki waktu kadaluarsa mutlak selama 20 detik. Penggunaan kembali token yang sama yang sudah pernah digunakan oleh user lain untuk check-in (*replay attack*) harus dicegah dengan menyimpan hash token yang sukses digunakan di memori cache server (seperti Redis) dengan TTL 20 detik. Jika hash token yang sama digunakan kembali, server langsung memblokir request kedua.

---

## 9. Analisis Risiko & Mitigasi (Risk Assessment)

| ID | Skenario Risiko | Dampak | Probabilitas | Strategi Mitigasi |
| :--- | :--- | :--- | :--- | :--- |
| **R-01** | **Antrean Padat di Depan Panitia:** Jumlah peserta yang datang bersamaan sangat banyak sehingga terjadi penumpukan antrean pemindaian di pintu masuk. | Sedang | Tinggi | **Mitigasi:** <br>1. PWA scanner dirancang mendukung multi-panitia secara bersamaan (misal menyediakan 2-3 panitia dengan HP scanner masing-masing). <br>2. Optimasi pemindaian QR menggunakan WebRTC agar responsif (kecepatan deteksi < 1 detik) disertai getaran sukses instan. |
| **R-02** | **HP Panitia Lowbat / Kehilangan Sinyal GPS:** Panitia yang bertugas mengalami kendala pada HP-nya (kehabisan daya baterai atau GPS error di dalam gedung). | Sedang | Sedang | **Mitigasi:** <br>1. Menugaskan lebih dari satu panitia per kelas dengan perangkat cadangan. <br>2. Menyediakan opsi **Bypass Manual** di HP panitia lain yang aktif untuk mencari peserta bersangkutan dan melakukan bypass secara manual setelah verifikasi fisik. |
| **R-03** | **Koneksi Internet Putus/Lemah:** Sinyal seluler buruk di ruang pelatihan (misal di basement / gedung tinggi). | Sedang | Tinggi | **Mitigasi:** <br>1. Mengimplementasikan fitur **Offline Scanning & Local Sync** pada PWA HP Panitia (menggunakan IndexedDB/LocalStorage) untuk merekam presensi sementara tanpa internet.<br>2. Begitu koneksi internet pulih, data offline disinkronkan secara otomatis ke backend.<br>3. Jika terjadi kegagalan sinkronisasi atau masalah fatal lainnya, gunakan opsi **Bypass Manual** oleh Panitia setelah verifikasi fisik ketika koneksi internet pulih atau stabil. |
| **R-04** | **Perbedaan Waktu Server & Client (Clock Drift):** Waktu di HP peserta melenceng beberapa menit dari server sehingga QR Code selalu dianggap kadaluarsa saat di-scan panitia. | Sedang | Sedang | **Mitigasi:** <br>1. PWA peserta mengambil basis waktu dari server melalui sinkronisasi NTP/API timestamp server saat memuat halaman pertama kali, bukan mengandalkan jam lokal ponsel peserta.<br>2. Berikan toleransi clock drift sebanyak $\pm$ 1 interval waktu (20 detik) pada server saat memvalidasi token QR Code. |
| **R-05** | **Perangkat/Layar HP Peserta Bermasalah:** Peserta tidak membawa HP, HP mati, layar retak, atau tidak bisa memunculkan QR Code. | Rendah | Sedang | **Mitigasi:** Panitia menggunakan fitur **Pencarian & Bypass Manual** langsung dari HP Panitia. Panitia memverifikasi identitas fisik peserta secara langsung, mencari namanya di scanner, memasukkan alasan bypass, lalu menekan tombol bypass. |

---

## 10. Distribusi Tugas Tim (High-Level Task Allocation)

Untuk memudahkan implementasi, berikut adalah pembagian tugas awal berdasarkan area spesialisasi tim:

1. **Eka (Database Designer):**
   - Melakukan migrasi database untuk tabel `pelatihans`, `attendances`, dan `audit_logs` sesuai dengan spesifikasi kolom di Bab 5.1 (termasuk mengganti `latitude_peserta` & `longitude_peserta` menjadi `latitude_panitia` & `longitude_panitia`, serta menambahkan `scanner_by`).
   - Menyiapkan index pada kolom yang sering dicari seperti `pelatihan_id`, `user_id`, `scanner_by`, `bypassed_by`, dan `action_type` untuk performa query yang cepat.
2. **Bayu (Backend Developer):**
   - Membuat API Endpoint untuk generate token QR Code dinamis bagi peserta yang berstatus `enrollments.status == 'confirmed'`.
   - Membuat API Endpoint Scanner Presensi untuk Panitia (menerima token QR Code peserta, koordinat GPS HP Panitia, ID Panitia, IP address, detail device).
   - Membuat API Endpoint khusus bulk sync / offline check-in dengan validasi timestamp lokal (`scan_timestamp`) vs token timestamp (`timestamp_generated`).
   - Mengimplementasikan kalkulasi Haversine Formula untuk geofencing lokasi pelatihan terhadap koordinat GPS HP Panitia.
   - Mengimplementasikan generator tanda tangan token QR Code (signature) berbasis server dengan masa kedaluwarsa 20 detik.
   - Mengembangkan API untuk Bypass Manual Panitia (mencakup pencarian nama/NIK, validasi geofencing GPS HP Panitia, pencatatan alasan bypass).
   - Mengembangkan API untuk monitoring real-time (polling / WebSocket) dashboard proyektor instruktur dan dashboard admin.
   - Mengembangkan API Export laporan (Excel & PDF) dengan menyertakan detail data scanner panitia.
   - Mengembangkan API Audit Log (pencatatan otomatis setiap aksi panitia/admin + query log).
3. **Ayu (Frontend Developer):**
   - Membuat tampilan Layar Progres & Monitoring Kelas Real-time untuk Proyektor Instruktur (dashboard statistik kehadiran, grafik, list kartu nama peserta dengan efek animasi transisi masuk).
   - Membuat tampilan PWA Admin/Panitia untuk menu Scanner Presensi (kamera WebRTC) dan menu Pencarian & Bypass Manual.
   - Mendesain indikator mode offline, suara bip/getar yang tetap jalan, serta counter antrean scan offline pada UI Scanner Panitia.
   - **Mendesain dan membangun halaman Admin Dashboard Monitoring** (kartu ringkasan, tabel kelas aktif, filter, detail kelas).
   - **Mendesain dan membangun halaman Manajemen Pelatihan** (form CRUD, peta interaktif untuk pemilihan koordinat).
   - **Membangun halaman Rekapitulasi & Laporan** (tabel dengan filter, tombol export).
   - **Membangun halaman Audit Log** (tabel kronologis dengan filter).
4. **Tio (Mobile/PWA Developer):**
   - Mengembangkan interface PWA sisi peserta untuk menampilkan Dynamic QR Code ( countdown progress bar 20s, regenerasi otomatis, pengecekan status enrollment `confirmed`).
   - Mengembangkan interface PWA sisi Panitia untuk Scanner Presensi (integrasi kamera WebRTC, trigger getaran ponsel via Web Vibration API saat scan sukses).
   - Mengimplementasikan deteksi status koneksi online/offline perangkat panitia.
   - Mengimplementasikan penyimpanan antrean scan lokal menggunakan IndexedDB/LocalStorage saat offline.
   - Mengimplementasikan trigger auto-sync antrean lokal ke API backend saat koneksi internet pulih (online).
   - Mengimplementasikan pengambilan data lokasi via HTML5 Geolocation API di HP Panitia dengan tingkat akurasi tinggi saat scan/bypass.
   - Menangani manajemen perizinan (*permission prompt UI*) kamera dan GPS di berbagai browser mobile (khususnya untuk HP Panitia).
   - Mengintegrasikan UI PWA peserta dan panitia dengan API backend yang dibuat oleh Bayu.
5. **Dika (Fullstack Developer — Admin Panel):**
   - Mengintegrasikan halaman admin/panitia frontend (Ayu) dengan API backend (Bayu) untuk seluruh fitur monitoring dan bypass.
   - Mengimplementasikan logika server-side untuk export Excel menggunakan library (Maatwebsite/Laravel-Excel) dengan field baru.
   - Mengimplementasikan logika server-side untuk export PDF menggunakan library (Barryvdh/Laravel-DomPDF atau sejenisnya) dengan layout ringkas.
   - Memastikan seluruh fitur admin panel memiliki validasi, authorization (role-based), dan error handling yang baik.
   - Membantu pengujian integrasi antara komponen admin.
6. **Rizky (API Integrator):**
   - Mengintegrasikan geolocation reverse-geocode (opsional) untuk menampilkan nama alamat dari koordinat pada laporan.
   - Mengelola webhook atau notifikasi real-time jika diperlukan.
7. **Hendra (Application Security):**
   - Melakukan security review pada semua endpoint admin & panitia API untuk mencegah privilege escalation.
   - Memastikan data `scan_timestamp` yang dikirim dari client panitia terproteksi dari manipulasi (tamper-proofing) dan meminimalisir replay attack offline dengan validasi hashing/tanda tangan payload offline (jika memungkinkan) atau pencocokan strictly logic di server.
   - Memastikan audit log tidak dapat dimanipulasi (immutable logging).
   - Memastikan RBAC (Role-Based Access Control) berfungsi dengan benar di semua level akses (terutama pemisahan hak akses panitia vs instruktur vs admin).
8. **Belva (Data Privacy & Compliance):**
   - Memastikan data koordinat GPS panitia pada laporan yang diexport tetap mematuhi UU PDP.
   - Memastikan audit log tidak menyimpan data sensitif yang tidak perlu.
9. **Rudi (Automation Scripts):**
   - Membuat artisan command untuk pembersihan/anonimasi data koordinat GPS panitia sesuai kebijakan retensi.
   - Membuat scheduler/cron job untuk menonaktifkan sesi presensi yang sudah lewat secara otomatis.

---
*(Akhir dari Dokumen PRD)*
