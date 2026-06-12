# Manual Test Cases — Aplikasi Pelatihan

---

## TC-01: Instalasi Flow

**Deskripsi:** Menguji flow instalasi aplikasi dari awal hingga selesai
**Prioritas:** CRITICAL

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Buka `http://localhost:8000` saat app belum terinstal | Redirect ke `/install` |
| 2 | Di halaman Step 1, cek semua requirement (PHP version, PDO, dll) | Semua hijau/terpenuhi |
| 3 | Klik "Lanjut" | Masuk ke Step 2 (konfigurasi database) |
| 4 | Isi form database dengan data **salah** (host/port salah) | Klik "Test Koneksi" → muncul error "Koneksi gagal" |
| 5 | Isi form database dengan data **benar** | Klik "Test Koneksi" → muncul "Koneksi berhasil" |
| 6 | Klik "Simpan & Lanjut" | Masuk ke Step 3 (konfigurasi admin) |
| 7 | Isi App Name, Admin Name, Email, Password | Form terisi |
| 8 | Klik "Install" | Progress bar berjalan (Step 1-7) |
| 9 | Tunggu hingga selesai | Muncul "Instalasi Berhasil!" |
| 10 | Buka halaman utama | Redirect ke `/login` |

### Error Handling

| Skenario | Aksi | Expected Result |
|----------|------|-----------------|
| DB sudah ada tabel | Isi form DB yang sudah terpakai tanpa centang confirm wipe | Muncul warning "Database tidak kosong" |
| Password terlalu pendek | Isi password < 6 karakter | Validasi error |
| Admin email sudah ada | Jalankan instalasi 2x dengan email sama | Error unik email |

---

## TC-02: Registrasi Peserta (Landing Page)

**Deskripsi:** Menguji pendaftaran peserta dari landing page
**Prioritas:** CRITICAL

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Buka `/` (aplikasi sudah terinstal) | Landing page tampil |
| 2 | Scroll ke section "Daftar Sekarang" | Form pendaftaran terlihat |
| 3 | Klik tombol "Daftar Sekarang" | Scroll ke form atau redirect ke `/daftar` (redirect ke `/#beranda`) |
| 4 | Isi Nama, NIK 16 digit, No WA, Email | Form terisi |
| 5 | Klik "Daftar" | Submit form, user dibuat, auto-login |
| 6 | Redirect ke halaman sukses | Muncul "Pendaftaran berhasil" |
| 7 | Cek database user baru | User dengan role 'peserta' tersimpan |

### Duplicate Detection

| Skenario | Aksi | Expected Result |
|----------|------|-----------------|
| NIK sudah terdaftar | POST `/daftar/cek-nik` dengan NIK existing | `{"exists": true}` |
| NIK baru | POST `/daftar/cek-nik` dengan NIK baru | `{"exists": false}` |

---

## TC-03: Admin Flow (Login → CRUD → Logout)

**Deskripsi:** Menguji siklus lengkap admin dari login hingga CRUD
**Prioritas:** CRITICAL

### Admin Login

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Buka `/admin/login` | Form login admin tampil |
| 2 | Login dengan email **salah** | Error "Email atau password salah" |
| 3 | Login dengan email benar + password salah | Error "Email atau password salah" |
| 4 | Login dengan kredensial valid (admin@pelatihan.test / password) | Redirect ke `/dashboard/admin` |
| 5 | Dashboard admin tampil | Statistik, menu navigasi terlihat |

### CRUD Dinas

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Klik menu "Dinas" | Halaman index Dinas tampil |
| 2 | Klik "Tambah Dinas" | Form create tampil |
| 3 | Isi Nama Dinas, Singkatan, status Aktif | Validasi |
| 4 | Submit | Dinas baru muncul di tabel |
| 5 | Klik "Edit" pada dinas | Form edit dengan data lama |
| 6 | Ubah nama, submit | Data berubah |
| 7 | Klik "Hapus" pada dinas tanpa pelatihan | Dinas terhapus |
| 8 | Klik "Hapus" pada dinas dengan pelatihan | Error "tidak bisa dihapus" |

### CRUD Pelatihan

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Buka menu Pelatihan | List pelatihan tampil |
| 2 | Create pelatihan baru dengan data valid | Tersimpan |
| 3 | Edit pelatihan | Data berubah |
| 4 | Hapus pelatihan | Terhapus |

### Approval Koordinator

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Buka menu Koordinator → Pending | List koordinator pending |
| 2 | Klik "Setujui" | Koordinator aktif |
| 3 | Klik "Tolak" | Koordinator terhapus |

---

## TC-04: Multi-step Form Peserta

**Deskripsi:** Menguji form pendaftaran peserta 5 tahap
**Prioritas:** HIGH

| Step | Aksi | Expected Result |
|------|------|-----------------|
| 1 | Login sebagai peserta | Redirect ke `/dashboard/peserta` |
| 2 | Klik "Lengkapi Data" | Masuk ke Tab 1 (Data Pribadi) |
| 3 | Isi NIK, Nama, JK, TTL, Alamat KTP, Kecamatan, Kelurahan | Form valid |
| 4 | Klik "Simpan & Lanjut" | Data tersimpan, redirect ke Tab 2 (Pendidikan) |
| 5 | Isi Pendidikan Terakhir, Institusi, Jurusan, Tahun Lulus, Pekerjaan | Form valid |
| 6 | Klik "Simpan & Lanjut" | Redirect ke Tab 3 (Minat) |
| 7 | Pilih bidang minat, tujuan, jadwal, mode pelatihan | Form valid |
| 8 | Klik "Simpan & Lanjut" | Redirect ke Tab 4 (Dokumen) |
| 9 | Upload foto profil, scan KTP | File terupload |
| 10 | Klik "Submit" | `is_completed = true`, redirect ke dashboard |

---

## TC-05: Role-based Access Control

**Deskripsi:** Menguji bahwa user hanya bisa mengakses halaman sesuai role-nya
**Prioritas:** HIGH

| Skenario | Aksi | Expected Result |
|----------|------|-----------------|
| Guest (not logged in) | Buka `/dashboard/admin` | Redirect ke login |
| Guest | Buka `/admin/kecamatan` | Redirect ke admin login |
| Peserta | Buka `/dashboard/admin` | 403 Forbidden |
| Peserta | Buka `/admin/kecamatan` | 403 Forbidden |
| Peserta | Buka `/dashboard/peserta` | 200 OK |
| Instruktur | Buka `/dashboard/admin` | 403 Forbidden |
| Instruktur | Buka `/dashboard/instruktur` | 200 OK |
| Admin | Buka `/dashboard/admin` | 200 OK |
| Admin | Buka `/dashboard/peserta` | 403 Forbidden |
| Koordinator | Buka `/dashboard/koordinator` | 200 OK |

### Post-Condition
- Semua akses ilegal harus di-block
- Redirect atau 403 sesuai konteks
