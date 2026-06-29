# Product Requirements Document (PRD)
## Section-Based Participant Profile/Biodata Management for Admin/Operator

| Atribut Dokumen | Detail |
| --- | --- |
| **Status** | Draft |
| **Penulis** | Antigravity (PRD Specialist) |
| **Target Implementasi** | Halaman Edit Biodata Peserta (`/admin/peserta/{id}/edit-biodata`) |
| **Tanggal Pembuatan** | 30 Juni 2026 |

---

## 1. Latar Belakang & Tujuan (Goal)

Saat ini, Admin atau Operator aplikasi perlu melakukan pembaruan data biodata peserta secara langsung tanpa harus melakukan *impersonation* (masuk sebagai user/peserta bersangkutan). Untuk mempermudah proses ini dan menghindari kesalahan pengisian data yang masif, aplikasi membutuhkan mekanisme pembaruan biodata berbasis bagian (*Section-Based Editing*) pada halaman `/admin/peserta/{id}/edit-biodata`.

Tujuan utama dari fitur ini adalah:
1. **Pengeditan Tersegmentasi**: Admin/Operator dapat melihat dan mengedit biodata peserta yang dibagi ke dalam beberapa kategori/seksi visual yang jelas (Identitas/Kontak, Alamat, Pendidikan/Pekerjaan, Preferensi, Dokumen).
2. **Validasi Terisolasi**: Validasi data dibatasi hanya pada seksi yang sedang diedit. Sebagai contoh, ketika menyimpan perubahan pada seksi "Alamat", sistem hanya akan memvalidasi field yang ada di seksi "Alamat" tanpa memaksa pengisian/validasi ulang field di seksi lain yang tidak sedang diubah.
3. **Pengalaman Pengguna (UX) yang Mulus**: Menggunakan Livewire atau kombinasi tradisional Blade + Alpine.js untuk beralih antara mode tampil (*view-mode*) dan mode ubah (*edit-mode*) di dalam masing-masing kartu seksi secara instan tanpa memuat ulang seluruh halaman (*no full-page reload*).
4. **Pembersihan Kode**: Membersihkan sisa-sisa *glitch* visual atau kesalahan sintaksis dari tata letak sebelumnya agar antarmuka bersih dan profesional.

---

## 2. Alur Pengguna & Perilaku Sistem (System Behavior)

### A. Pembagian Seksi Visual (Section Cards)
Halaman edit biodata dibagi menjadi 5 kartu/seksi utama:
1. **Identitas & Kontak**
   - Field: Nama Lengkap, Email, Nomor Telepon/WhatsApp, Jenis Kelamin, Tempat Lahir, Tanggal Lahir, NIK, dll.
2. **Alamat**
   - Field: Alamat Lengkap, Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan, Kode Pos.
   - Integrasi: Dropdown bertingkat (regional dropdowns) untuk Provinsi -> Kota/Kabupaten -> Kecamatan -> Kelurahan.
3. **Pendidikan & Pekerjaan**
   - Field: Pendidikan Terakhir, Nama Instansi/Sekolah, Pekerjaan, Nama Perusahaan/Institusi, Jabatan, dll.
4. **Preferensi**
   - Field: Kategori Pelatihan yang Diminati, Preferensi Waktu Belajar, Bahasa Pengantar, dll.
5. **Dokumen**
   - Field: Upload KTP, Upload CV/Resume, Upload Ijazah, dll. (termasuk pratinjau dokumen yang sudah diunggah).

### B. Mode Tampil (View Mode) vs Mode Ubah (Edit Mode)
- **Kondisi Awal (Default)**: Setiap seksi ditampilkan dalam bentuk kartu *read-only* (View Mode) yang menampilkan data saat ini dengan rapi.
- **Tombol Edit**: Di pojok kanan atas setiap kartu, terdapat tombol "Edit" (atau ikon pensil).
- **Transisi**: Ketika tombol "Edit" diklik:
  - Kartu bersangkutan bertransisi ke Edit Mode secara instan (dikelola oleh Alpine.js atau Livewire).
  - Seksi lain tetap berada dalam View Mode (tidak terganggu).
  - Tombol di pojok kanan atas berubah menjadi "Batal" (Cancel).
  - Di bagian bawah kartu yang sedang diedit, muncul tombol "Simpan" (Save).
- **Membatalkan**: Jika tombol "Batal" diklik, kartu kembali ke View Mode dan nilai input di-reset ke data semula tanpa menyimpan perubahan.

### C. Validasi & Penyimpanan Data
- Setiap seksi memiliki rute pembaruan (*update route*) tersendiri atau menggunakan parameter khusus (seperti `?section=alamat`) untuk membedakan proses *update*.
- **Pembaruan Database**: Data disimpan ke tabel `users` (seperti nama, email) dan tabel `peserta_profiles` (seperti NIK, alamat, pendidikan, preferensi, dokumen) sesuai relasi data.
- **Isolasi Validasi**: Hanya aturan validasi untuk field pada seksi aktif yang akan dijalankan.
- **Hasil Sukses**: Setelah data berhasil divalidasi dan disimpan, kartu otomatis kembali ke View Mode dan memuat data terbaru, serta menampilkan pesan sukses (*flash message*) spesifik untuk seksi tersebut.

---

## 3. Kriteria Penerimaan (Acceptance Criteria)

### A. Transisi Antarmuka (UI/UX)
- [ ] Pengguna (Admin/Operator) dapat beralih antara View Mode dan Edit Mode pada masing-masing dari 5 seksi secara independen tanpa memicu *full page reload*.
- [ ] Transisi mode berjalan mulus secara visual dengan indikator transisi yang jelas (misal perubahan warna tombol atau munculnya form input).

### B. Validasi & Keamanan Data
- [ ] Pesan kesalahan validasi (*validation errors*) hanya muncul pada kartu seksi yang sedang aktif diedit dan tidak mempengaruhi tampilan kartu seksi lainnya.
- [ ] Mengirimkan data yang tidak valid pada seksi "Alamat" tidak boleh menggagalkan atau memicu error pada seksi "Identitas/Kontak".
- [ ] Sistem memvalidasi hak akses Admin/Operator sebelum memproses penyimpanan data (autorisasi tingkat controller/request).

### C. Dropdown Wilayah (Regional Dropdowns) pada Seksi Alamat
- [ ] Dropdown Provinsi, Kota/Kabupaten, Kecamatan, dan Kelurahan terintegrasi secara dinamis.
- [ ] Memilih Provinsi akan memperbarui daftar Kota/Kabupaten secara dinamis (menggunakan API regional atau query Livewire).
- [ ] Nilai wilayah yang sebelumnya tersimpan di database harus terpilih secara otomatis saat memasuki Edit Mode pada seksi Alamat.

### D. Audit Trail & Logging
- [ ] Setiap kali Admin/Operator melakukan pembaruan pada salah satu seksi biodata peserta, sistem harus mencatat aktivitas tersebut ke dalam tabel log audit / log aktivitas (`activity_log` atau sejenisnya).
- [ ] Data log minimal mencatat: ID Admin pelaku perubahan, ID Peserta yang diubah, nama seksi yang diperbarui, serta stempel waktu (timestamp).

### E. Bebas Gangguan Sintaksis (No Glitch)
- [ ] Tidak ada lagi kesalahan penulisan komentar Blade atau tag HTML yang tidak tertutup yang menyebabkan karakter aneh bocor ke halaman web.

---

## 4. Spesifikasi Teknis & Alur Database

### A. Skema Data Terkait
- **Tabel `users`**:
  - `name` (string)
  - `email` (string)
- **Tabel `peserta_profiles`**:
  - `user_id` (foreign key ke `users.id`)
  - `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `no_telp`
  - `alamat`, `provinsi_id`, `kota_id`, `kecamatan_id`, `kelurahan_id`, `kode_pos`
  - `pendidikan_terakhir`, `nama_instansi`, `pekerjaan`, `jabatan`
  - `preferensi_kategori`, `preferensi_waktu`
  - `dokumen_ktp`, `dokumen_cv`, `dokumen_ijazah`

### B. Contoh Endpoint & Handler
- Route: `POST /admin/peserta/{id}/edit-biodata` dengan request body `section` (misal: `section=alamat`).
- Atau memisahkan ke Route spesifik:
  - `PATCH /admin/peserta/{id}/edit-biodata/identitas`
  - `PATCH /admin/peserta/{id}/edit-biodata/alamat`
  - `PATCH /admin/peserta/{id}/edit-biodata/pendidikan`
  - `PATCH /admin/peserta/{id}/edit-biodata/preferensi`
  - `PATCH /admin/peserta/{id}/edit-biodata/dokumen`
- Controller akan memeriksa nilai parameter `section` (atau rute yang dipanggil) untuk mencocokkan *FormRequest* khusus (misal: `UpdateAlamatRequest`, `UpdateIdentitasRequest`) guna mengisolasi aturan validasi.

---

## 5. Rencana Pengujian (Test Plan)

1. **Uji Transisi Mode**: Klik tombol Edit pada setiap seksi satu per satu. Pastikan form input muncul dan tombol Batal mengembalikan tampilan ke keadaan semula.
2. **Uji Validasi Terisolasi**: Masuk ke Edit Mode pada seksi "Pendidikan & Pekerjaan", kosongkan field wajib, klik Simpan. Pastikan pesan error tampil di seksi tersebut dan seksi lainnya tidak ikut menampilkan pesan error.
3. **Uji Integrasi Wilayah**: Pada seksi Alamat, ubah Provinsi dan pastikan opsi Kota/Kabupaten diperbarui secara otomatis. Simpan perubahan dan verifikasi bahwa data wilayah tersimpan dengan benar di database.
4. **Uji Audit Log**: Setelah berhasil memperbarui salah satu seksi, periksa tabel log aktivitas untuk memastikan aksi tersebut tercatat.
