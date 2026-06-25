# Test Cases & UAT: Fitur Wajib Chat WA + Verifikasi Newbimma v1.1

**Referensi PRD:** `PRD-Pelatihanku-WajibChatWA-v1.1.md`
**Project:** Laravel 12 + Livewire 3

---

## Flow Overview

```
pending → approved (generate kode) → waiting_wa_confirmation
  → (peserta chat WA) → waiting_newbimma_check
  → (admin cek Newbimma)
    ├─ TIDAK terdaftar → confirmed
    └─ SUDAH pernah → rejected (dengan catatan)
```

---

## 1. Manual Test Cases

| ID | Skenario | Langkah | Hasil Diharapkan | Status |
|----|----------|---------|------------------|--------|
| TC-001 | Admin approve enrollment → kode verifikasi ter-generate | 1. Login sebagai Admin.<br>2. Buka menu **Pendaftaran**.<br>3. Klik tombol **Approve** pada enrollment status `pending`.<br>4. Konfirmasi approve. | 1. Status enrollment berubah menjadi `waiting_wa_confirmation`.<br>2. Field `verification_code` terisi dengan format `PTHK-{id}-{6char}`.<br>3. Field `verification_code_expires_at` terisi (now + 24 jam).<br>4. Muncul flash message sukses. | Belum Dites |
| TC-002 | Popup congratulations muncul di dashboard peserta | 1. Login sebagai peserta yang enrollment-nya baru di-approve.<br>2. Buka halaman dashboard. | 1. Popup modal `WaitingConfirmation` muncul di tengah layar.<br>2. Background gelap dengan blur overlay.<br>3. Ada ikon centang hijau besar.<br>4. Teks "Selamat! 🎉 Pendaftaran Anda Disetujui!" tampil.<br>5. Confetti berjatuhan (canvas). | Belum Dites |
| TC-003 | Kode verifikasi tampil dengan format benar | 1. Lihat popup congratulations di dashboard peserta.<br>2. Perhatikan kode verifikasi yang ditampilkan. | 1. Format: `PTHK-{id_enrollment}-{6 karakter random}`.<br>2. Karakter: huruf kapital A-Z (tanpa 0, O, 1, I, L) + angka 2-9.<br>3. Contoh valid: `PTHK-42-AB3X9K`.<br>4. Contoh tidak valid: `PTHK-42-ABC123` (mengandung 1). | Belum Dites |
| TC-004 | Tombol copy kode verifikasi berfungsi | 1. Di popup congratulations, klik ikon **copy** di samping kode verifikasi.<br>2. Buka Notepad / text editor.<br>3. Paste (Ctrl+V). | 1. Kode verifikasi tersalin ke clipboard.<br>2. Isi paste sama persis dengan kode yang ditampilkan.<br>3. Tidak ada karakter tambahan (spasi, newline). | Belum Dites |
| TC-005 | Countdown 24 jam berjalan real-time | 1. Buka popup congratulations.<br>2. Perhatikan teks "Berlaku hingga: X jam Y menit Z detik".<br>3. Tunggu 10 detik, perhatikan perubahan. | 1. Countdown menampilkan sisa waktu dalam format `X jam Y menit Z detik`.<br>2. Angka detik berkurang setiap 1 detik.<br>3. Setelah 24 jam berlalu, teks berubah menjadi "Kode sudah expired".<br>4. Countdown berhenti berjalan. | Belum Dites |
| TC-006 | Tombol WA membuka wa.me dengan pesan lengkap | 1. Di popup congratulations, klik tombol **Chat Admin via WhatsApp**.<br>2. Perhatikan URL yang terbuka di tab baru. | 1. URL: `https://wa.me/{nomor_admin}?text={pesan_encoded}`.<br>2. Nomor admin sesuai data `whatsapp_numbers` aktif pertama.<br>3. Pesan berisi: Nama, NIK, WA, Kelurahan, Kecamatan, Pelatihan, Tanggal, Dinas, Kode Verifikasi.<br>4. Format pesan rapi dengan pemisah `━━━`. | Belum Dites |
| TC-007 | Admin klik "Sudah Chat WA" → status berubah | 1. Login sebagai Admin.<br>2. Buka enrollment dengan status `waiting_wa_confirmation`.<br>3. Klik tombol **Sudah Chat WA**.<br>4. Konfirmasi. | 1. Status berubah menjadi `waiting_newbimma_check`.<br>2. `wa_confirmed_at` terisi timestamp sekarang.<br>3. `wa_confirmed_by` terisi ID admin yang login.<br>4. Muncul flash message sukses.<br>5. Tombol "Sudah Chat WA" berubah menjadi tombol "Cek Newbimma", "Valid", "Tolak". | Belum Dites |
| TC-008 | Admin klik "Valid & Konfirmasi" → status confirmed | 1. Login sebagai Admin.<br>2. Buka enrollment dengan status `waiting_newbimma_check`.<br>3. Klik tombol **Valid**.<br>4. Konfirmasi. | 1. Status berubah menjadi `confirmed`.<br>2. `newbimma_checked_at` terisi.<br>3. `newbimma_checked_by` terisi ID admin.<br>4. `newbimma_result` = `valid`.<br>5. Muncul flash message sukses.<br>6. Tombol aksi WA/Newbimma tidak muncul lagi. | Belum Dites |
| TC-009 | Admin klik "Tidak Valid & Tolak" → status rejected + notes | 1. Login sebagai Admin.<br>2. Buka enrollment dengan status `waiting_newbimma_check`.<br>3. Klik tombol **Tolak**.<br>4. Konfirmasi. | 1. Status berubah menjadi `rejected`.<br>2. `newbimma_checked_at` terisi.<br>3. `newbimma_checked_by` terisi ID admin.<br>4. `newbimma_result` = `invalid`.<br>5. `notes` terisi "Pernah mengikuti pelatihan yang sama di Newbimma".<br>6. Muncul flash message error. | Belum Dites |
| TC-010 | Filter tab "Menunggu Chat WA" menampilkan data yang benar | 1. Login sebagai Admin.<br>2. Buka halaman index Pendaftaran.<br>3. Pilih filter status **Menunggu Chat WA** (`waiting_wa_confirmation`). | 1. Hanya enrollment dengan status `waiting_wa_confirmation` yang tampil.<br>2. Badge status menampilkan "Menunggu Chat WA" dengan ikon WA.<br>3. Count status sesuai dengan jumlah data. | Belum Dites |
| TC-011 | Filter tab "Cek Newbimma" menampilkan data yang benar | 1. Login sebagai Admin.<br>2. Buka halaman index Pendaftaran.<br>3. Pilih filter status **Cek Newbimma** (`waiting_newbimma_check`). | 1. Hanya enrollment dengan status `waiting_newbimma_check` yang tampil.<br>2. Badge status menampilkan "Cek Newbimma" dengan ikon search.<br>3. Count status sesuai dengan jumlah data. | Belum Dites |
| TC-012 | Pencarian berdasarkan kode verifikasi | 1. Login sebagai Admin.<br>2. Buka halaman index Pendaftaran.<br>3. Ketik kode verifikasi (contoh: `PTHK-42-AB3X9K`) di kolom pencarian.<br>4. Tekan Enter. | 1. Data enrollment dengan kode verifikasi yang sesuai muncul.<br>2. Data lain tidak tampil.<br>3. Pencarian case-insensitive. | Belum Dites |
| TC-013 | Kode expired setelah 24 jam | 1. Approve enrollment (set `verification_code_expires_at` ke 1 menit dari now untuk testing).<br>2. Tunggu hingga expired.<br>3. Login sebagai Admin.<br>4. Coba klik **Sudah Chat WA** pada enrollment tersebut. | 1. Muncul error "Kode verifikasi sudah expired. Silakan generate ulang."<br>2. Status tetap `waiting_wa_confirmation`.<br>3. Tidak ada perubahan data. | Belum Dites |
| TC-014 | Konfirmasi tidak bisa diubah setelah status confirmed/rejected | 1. Cari enrollment dengan status `confirmed` atau `rejected`.<br>2. Periksa apakah tombol **Sudah Chat WA**, **Valid**, **Tolak** masih muncul. | 1. Tombol **Sudah Chat WA** tidak muncul untuk status `confirmed`/`rejected`.<br>2. Tombol **Valid**/**Tolak** tidak muncul untuk status `confirmed`/`rejected`.<br>3. Tidak ada cara untuk mengubah status WA/Newbimma via UI. | Belum Dites |

---

## 2. Scenario Test (Skenario End-to-End)

### Skenario 1: Happy Path — Approve → Peserta Chat → Newbimma Valid → Confirmed

| ID | Skenario | Langkah | Hasil Diharapkan | Status |
|----|----------|---------|------------------|--------|
| ST-001 | Complete happy flow end-to-end | **Prekondisi:** Ada enrollment dengan status `pending`, data peserta lengkap, nomor admin aktif di `whatsapp_numbers`.<br><br>1. **Admin:** Approve enrollment → status jadi `waiting_wa_confirmation`, kode verifikasi ter-generate.<br>2. **Peserta:** Login ke dashboard → popup congratulations muncul dengan kode verifikasi dan countdown 24 jam.<br>3. **Peserta:** Klik tombol WA → terbuka `wa.me/{admin}` dengan pre-filled message.<br>4. **Peserta:** Kirim pesan ke admin via WA (simulasi).<br>5. **Admin:** Klik **Sudah Chat WA** → status jadi `waiting_newbimma_check`.<br>6. **Admin:** Cek Newbimma (manual via website eksternal) → peserta TIDAK terdaftar.<br>7. **Admin:** Klik **Valid** → status jadi `confirmed`.<br>8. **Peserta:** Login dashboard → popup sudah tidak muncul, melihat dashboard normal. | 1. Step 1-8 berjalan tanpa error.<br>2. Database: status = `confirmed`, `newbimma_result` = `valid`.<br>3. Timeline lengkap: `approved_at`, `wa_confirmed_at`, `newbimma_checked_at` terisi.<br>4. Semua activity log tercatat. | Belum Dites |

### Skenario 2: Rejected Path — Approve → Peserta Chat → Newbimma Invalid → Rejected

| ID | Skenario | Langkah | Hasil Diharapkan | Status |
|----|----------|---------|------------------|--------|
| ST-002 | Rejected flow end-to-end | **Prekondisi:** Ada enrollment dengan status `pending`, data peserta lengkap.<br><br>1. **Admin:** Approve enrollment → status `waiting_wa_confirmation`.<br>2. **Peserta:** Login dashboard → popup muncul.<br>3. **Peserta:** Klik WA, kirim pesan ke admin.<br>4. **Admin:** Klik **Sudah Chat WA** → status `waiting_newbimma_check`.<br>5. **Admin:** Cek Newbimma → peserta SUDAH pernah ikut pelatihan yang sama.<br>6. **Admin:** Klik **Tolak** → status jadi `rejected`, notes terisi otomatis.<br>7. **Peserta:** Login dashboard → melihat status penolakan, tidak ada popup congratulations. | 1. Step 1-7 berjalan tanpa error.<br>2. Database: status = `rejected`, `newbimma_result` = `invalid`, notes = "Pernah mengikuti pelatihan yang sama di Newbimma".<br>3. Timeline: `approved_at`, `wa_confirmed_at`, `newbimma_checked_at` terisi.<br>4. Activity log tercatat. | Belum Dites |

### Skenario 3: Kode Expired — Approve → Tunggu 24 Jam → Kode Expired

| ID | Skenario | Langkah | Hasil Diharapkan | Status |
|----|----------|---------|------------------|--------|
| ST-003 | Expired code flow | **Prekondisi:** Ada enrollment dengan status `pending`.<br><br>1. **Admin:** Approve enrollment → kode ter-generate dengan masa berlaku 24 jam.<br>2. **Peserta:** Login dashboard → popup muncul dengan countdown 24 jam.<br>3. **Peserta:** TIDAK mengirim chat WA selama 24 jam.<br>4. **Otomatis:** Setelah 24 jam, kode expired (`verification_code_expires_at` terlewat).<br>5. **Peserta:** Refresh dashboard → countdown menampilkan "Kode sudah expired".<br>6. **Admin:** Coba klik **Sudah Chat WA** → error "Kode verifikasi sudah expired. Silakan generate ulang."<br>7. **Admin:** Lakukan reset enrollment, lalu approve ulang untuk generate kode baru. | 1. Step 5: Countdown berhenti di "Kode sudah expired".<br>2. Step 6: Error muncul, status tetap `waiting_wa_confirmation`.<br>3. Step 7: Kode baru ter-generate, countdown reset 24 jam.<br>4. Kode lama sudah tidak valid. | Belum Dites |

---

## 3. Edge Cases

| ID | Skenario | Langkah | Hasil Diharapkan | Status |
|----|----------|---------|------------------|--------|
| EC-001 | Peserta tidak punya WhatsApp | 1. Approve enrollment peserta yang tidak punya nomor WA.<br>2. Login sebagai peserta.<br>3. Klik tombol **Chat Admin via WhatsApp**. | 1. Tombol WA tetap muncul dan functional.<br>2. `wa.me` tetap terbuka (nomor admin).<br>3. Tidak ada error/crash.<br>4. **Saran:** Pertimbangkan opsi alternatif konfirmasi untuk peserta tanpa WA. | Belum Dites |
| EC-002 | Kode verifikasi duplikat (collision) | 1. Approve 2 enrollment berbeda secara simultan/berurutan.<br>2. Bandingkan kode verifikasi keduanya. | 1. Kode verifikasi kedua enrollment HARUS berbeda.<br>2. Format `PTHK-{id}-{6char}` menjamin keunikan karena ID enrollment berbeda.<br>3. Jika ID kebetulan sama (tidak mungkin karena auto-increment), 6 karakter random tetap memberikan uniqueness.<br>4. Database constraint `unique` pada kolom `verification_code` mencegah duplikat. | Belum Dites |
| EC-003 | Admin langsung klik Valid tanpa cek Newbimma | 1. Approve enrollment.<br>2. Langsung klik **Valid** tanpa klik **Sudah Chat WA** sebelumnya. | 1. Tombol **Valid** tidak muncul karena status masih `waiting_wa_confirmation`.<br>2. Admin harus klik **Sudah Chat WA** dulu untuk mengubah status ke `waiting_newbimma_check`.<br>3. Tidak ada cara bypass flow. | Belum Dites |
| EC-004 | Admin langsung klik Valid tanpa cek Newbimma (setelah Sudah Chat WA) | 1. Approve enrollment.<br>2. Klik **Sudah Chat WA** → status `waiting_newbimma_check`.<br>3. Langsung klik **Valid** tanpa benar-benar cek Newbimma. | 1. Sistem tidak mencegah admin klik Valid (tidak ada validasi tambahan).<br>2. **Risiko:** Peserta yang sudah pernah ikut Newbimma bisa lolos.<br>3. **Saran:** Tidak ada perlindungan teknis, hanya mengandalkan integritas admin. | Belum Dites |
| EC-005 | Nomor WA admin tidak ada di tabel whatsapp_numbers | 1. Hapus semua data dari tabel `whatsapp_numbers`.<br>2. Approve enrollment.<br>3. Login sebagai peserta.<br>4. Klik tombol WA. | 1. WA link tetap terbuka dengan nomor fallback `6280000000000` (lihat kode `WaitingConfirmation.php` line 48).<br>2. Tidak ada error/crash.<br>3. **Catatan:** Nomor fallback mungkin tidak aktif, perlu diedukasi admin untuk mengisi nomor WA. | Belum Dites |
| EC-006 | Admin approve enrollment yang sudah kadaluarsa kuota | 1. Isi kuota pelatihan sampai penuh.<br>2. Coba approve enrollment baru. | 1. Approve gagal.<br>2. Muncul error "Kuota pelatihan sudah penuh".<br>3. Status tetap `pending`.<br>4. Kode verifikasi tidak ter-generate. | Belum Dites |
| EC-007 | Double klik tombol "Sudah Chat WA" | 1. Buka enrollment `waiting_wa_confirmation`.<br>2. Klik tombol **Sudah Chat WA** 2x cepat. | 1. Hanya 1 request yang diproses.<br>2. Status berubah ke `waiting_newbimma_check` satu kali.<br>3. Tidak ada error atau data duplikasi timestamp.<br>4. **Catatan:** Perlu verifikasi apakah CSRF/Laravel mencegah double submit. | Belum Dites |
| EC-008 | Popup congratulations muncul di halaman selain dashboard | 1. Login sebagai peserta yang enrollment-nya `waiting_wa_confirmation`.<br>2. Buka halaman selain dashboard (misal: profil, riwayat). | 1. Popup TIDAK muncul di halaman lain.<br>2. Popup hanya muncul di dashboard peserta.<br>3. Tidak ada flash/redirect error. | Belum Dites |
| EC-009 | Admin klik "Sudah Chat WA" untuk enrollment yang sudah expired kodenya, lalu reset dan approve ulang | 1. Approve enrollment, tunggu hingga expired.<br>2. Coba klik **Sudah Chat WA** → error expired.<br>3. Klik **Reset** enrollment.<br>4. Approve ulang enrollment yang sama. | 1. Reset berhasil, status kembali ke `pending`?<br>2. Approve ulang menghasilkan kode verifikasi baru.<br>3. `verification_code_expires_at` reset ke now + 24 jam.<br>4. Kode lama sudah tidak berlaku. | Belum Dites |

---

## Ringkasan Test Coverage

| Kategori | Jumlah Test Case | Status |
|----------|-----------------|--------|
| Manual Test Cases | 14 | Belum Dites |
| Scenario Test (E2E) | 3 | Belum Dites |
| Edge Cases | 9 | Belum Dites |
| **Total** | **26** | **Belum Dites** |

## Lingkungan Testing

| Aspek | Detail |
|-------|--------|
| **Browser** | Chrome (latest), Firefox (latest), Edge (latest) |
| **Device** | Desktop 1920x1080, Tablet 768x1024, Mobile 375x812 |
| **Database** | MySQL 8.0 (sesuai production) |
| **Role User** | Admin (superadmin), Peserta (user biasa) |
| **Dependencies** | Canvas-confetti (CDN), Alpine.js (bawaan Livewire 3) |

## Catatan Penting

1. **Konfirmasi Newbimma bersifat manual** — Admin harus cek di website Newbimma eksternal, tidak ada integrasi API otomatis.
2. **Validasi kode expired** hanya dilakukan saat admin klik "Sudah Chat WA", bukan saat popup ditampilkan.
3. **Nomor WA admin** menggunakan tabel `whatsapp_numbers`, pastikan sudah terisi sebelum testing flow WA.
4. **Popup hanya muncul di halaman dashboard** — dicek via `@if(auth()->user()->enrollments()->where('status', 'waiting_wa_confirmation')->exists())`.
5. **Kode verifikasi** menggunakan karakter non-ambigu (tanpa 0/O, 1/I/L) untuk mengurangi kesalahan input manual.
