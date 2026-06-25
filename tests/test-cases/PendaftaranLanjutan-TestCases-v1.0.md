# Test Cases: Fitur Pendaftaran Lanjutan & Manajemen KTA v1.0

**Referensi PRD:** `PRD-Pelatihanku-PendaftaranLanjutanKTA-v1.0.md`

---

## 1. Fungsionalitas Form Pendaftaran Baru

| Test Case ID | Skenario | Langkah-langkah | Hasil yang Diharapkan | Status |
|--------------|----------|-----------------|-----------------------|--------|
| REG-001      | Happy Path | 1. Buka halaman /register. <br> 2. Isi semua field (nama, email, password, NIK, status, sumber info) dengan data valid dan unik. <br> 3. Klik "Daftar". | 1. User berhasil terdaftar. <br> 2. Redirect ke halaman dashboard peserta. <br> 3. Data NIK, status tokoh, dan sumber informasi tersimpan di database. | Belum Dites |
| REG-002      | Validasi NIK Duplikat | 1. Gunakan NIK yang sudah terdaftar. <br> 2. Isi field lain dengan data valid. <br> 3. Klik "Daftar". | 1. Pendaftaran gagal. <br> 2. Muncul pesan error "NIK sudah terdaftar". | Belum Dites |
| REG-003      | Validasi Field Wajib (NIK) | 1. Kosongkan field NIK. <br> 2. Isi field lain. <br> 3. Klik "Daftar". | 1. Pendaftaran gagal. <br> 2. Muncul pesan error validasi untuk NIK. | Belum Dites |
| REG-004      | Validasi Field Wajib (Status Tokoh) | 1. Jangan pilih "Status Tokoh". <br> 2. Isi field lain. <br> 3. Klik "Daftar". | 1. Pendaftaran gagal. <br> 2. Muncul pesan error validasi untuk Status Tokoh. | Belum Dites |
| REG-005      | Validasi Field Wajib (Sumber Info) | 1. Jangan pilih "Sumber Informasi". <br> 2. Isi field lain. <br> 3. Klik "Daftar". | 1. Pendaftaran gagal. <br> 2. Muncul pesan error validasi untuk Sumber Informasi. | Belum Dites |
| REG-006      | Interaksi Field Sumber Informasi | 1. Pilih "Sosial Media" -> field detail harus hilang. <br> 2. Pilih "Koordinator" -> field detail harus muncul. <br> 3. Pilih "Lainnya" -> field detail harus muncul. | Tampilan field detail (`sumber_informasi_detail`) sesuai dengan pilihan. | Belum Dites |
| REG-007      | UI Placeholder | 1. Buka halaman /register. | 1. Placeholder untuk komponen Live Capture Foto Diri terlihat. <br> 2. Placeholder untuk komponen Live Capture KTP terlihat. | Belum Dites |

---

## 2. Fungsionalitas CRUD Manajemen KTA

| Test Case ID | Skenario | Langkah-langkah | Hasil yang Diharapkan | Status |
|--------------|----------|-----------------|-----------------------|--------|
| KTA-001      | Menampilkan Halaman Index | 1. Login sebagai Admin. <br> 2. Buka URL `/admin/kta-members`. | 1. Halaman menampilkan tabel anggota KTA. <br> 2. Ada tombol "Tambah Anggota", "Impor", "Sinkron". <br> 3. Data anggota KTA (jika ada) tampil dengan benar. <br> 4. Paginasi berfungsi. | Belum Dites |
| KTA-002      | Tambah Anggota (Happy Path) | 1. Di halaman index, klik "Tambah Anggota". <br> 2. Isi semua field dengan data valid. <br> 3. Klik "Simpan". | 1. Redirect kembali ke halaman index. <br> 2. Muncul pesan sukses. <br> 3. Data baru muncul di tabel. | Belum Dites |
| KTA-003      | Tambah Anggota (NIK Duplikat) | 1. Gunakan NIK yang sudah ada di database KTA. <br> 2. Klik "Simpan". | 1. Proses gagal. <br> 2. Muncul pesan error validasi NIK. | Belum Dites |
| KTA-004      | Edit Anggota | 1. Di halaman index, klik "Edit" pada salah satu data. <br> 2. Ubah beberapa data. <br> 3. Klik "Update". | 1. Redirect kembali ke halaman index. <br> 2. Muncul pesan sukses. <br> 3. Data di tabel ter-update. | Belum Dites |
| KTA-005      | Hapus Anggota | 1. Di halaman index, klik "Hapus" pada salah satu data. <br> 2. Konfirmasi penghapusan. | 1. Redirect kembali ke halaman index. <br> 2. Muncul pesan sukses. <br> 3. Data hilang dari tabel. | Belum Dites |

---
