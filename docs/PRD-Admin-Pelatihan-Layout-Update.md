# Product Requirements Document (PRD)

## 📋 Executive Summary
Dokumen ini menjelaskan spesifikasi kebutuhan perubahan tata letak (layout) pada halaman kelola pelatihan admin (`/admin/pelatihan`). Perubahan ini bertujuan untuk mengoptimalkan tampilan informasi pelatihan dengan mengganti kolom **Batch** menjadi kolom **Tanggal Pelatihan** (yang menampilkan rentang tanggal mulai s.d. selesai) serta menjaga kerapian teks dengan mencegah pemenggalan kata (*word wrapping*) pada kolom nama pelatihan dan tanggal pelatihan. Seluruh perubahan wajib selaras dengan tema desain **Premium Dark Futuristic Glassmorphism** yang sudah berjalan.

---

## 🎯 Latar Belakang
Pada antarmuka daftar pelatihan admin saat ini, kolom **Batch** dinilai kurang informatif dibandingkan dengan tanggal pelaksanaan pelatihan secara langsung. Selain itu, lebar kolom yang tidak menentu terkadang menyebabkan nama pelatihan atau data teks krusial terpotong/turun ke baris baru (*wrap*), yang mengurangi estetika dari tema futuristik premium. Oleh karena itu, diperlukan pembaruan kolom tabel pada daftar pelatihan serta penerapan gaya `whitespace: nowrap` (`no-wrap`) pada kolom tertentu.

---

## 👥 Target Pengguna
- **Administrator / Verifikator Sistem:** Membutuhkan kemudahan dan kecepatan dalam memantau rentang waktu pelaksanaan setiap pelatihan tanpa harus membuka halaman detail.

---

## 📖 User Stories
1. **Melihat Rentang Tanggal:** Sebagai seorang Administrator, saya ingin dapat melihat rentang tanggal pelaksanaan pelatihan (tanggal mulai s.d. selesai) langsung pada tabel daftar pelatihan sehingga saya dapat memantau jadwal pelatihan dengan lebih cepat.
2. **Kerapian Tampilan:** Sebagai seorang Administrator, saya ingin nama pelatihan dan rentang tanggal ditampilkan secara utuh dalam satu baris (tidak patah ke bawah) agar tampilan tabel tetap rapi, profesional, dan mudah dibaca sesuai tema Premium Dark.

---

## ✅ Acceptance Criteria (Kriteria Penerimaan)

### 1. Struktur Tabel & Penghapusan Kolom
*   **Kriteria:** Kolom **Batch** harus dihapus sepenuhnya dari tabel daftar pelatihan.
*   **Penerapan:** 
    *   Hapus elemen header kolom (`th`) yang menampilkan teks/link sorting untuk **Batch** (baris 322–331).
    *   Hapus elemen isi kolom (`td`) yang menampilkan nilai `$pelatihan->batch` (baris 379).

### 2. Penambahan Kolom Tanggal Pelatihan
*   **Kriteria:** Kolom **Tanggal Pelatihan** harus ditambahkan sebagai pengganti kolom Batch atau diletakkan sebelum/setelah nama pelatihan. Sesuai tata letak default, kolom baru diletakkan setelah kolom No dan sebelum kolom Nama Pelatihan.
*   **Format Tampilan:** Menampilkan rentang `tanggal_mulai` s.d. `tanggal_selesai` dalam format `d/m/Y`, dengan pemisah `-`. Contoh: `28/06/2026 - 30/06/2026`.
*   **Penerapan Sintaks:** 
    *   Header tabel (`th`): Menampilkan teks "Tanggal Pelatihan" dengan opsi penyortiran berdasarkan `tanggal_mulai` atau tetap statis jika tidak didukung. Disarankan menggunakan parameter sorting `tanggal_mulai`.
    *   Isi tabel (`td`): 
        ```html
        {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d/m/Y') }}
        ```

### 3. Penerapan Kelas `no-wrap` (White-space No-wrap)
*   **Kriteria:** Mencegah pembungkusan teks (*text wrapping*) pada kolom **Nama Pelatihan** dan kolom **Tanggal Pelatihan** agar tetap berada dalam satu baris.
*   **Penerapan CSS:** 
    *   Tambahkan kelas Bootstrap `text-nowrap` atau inline style `white-space: nowrap;` pada tag `td` untuk kolom **Nama Pelatihan**.
    *   Tambahkan kelas Bootstrap `text-nowrap` atau inline style `white-space: nowrap;` pada tag `td` untuk kolom **Tanggal Pelatihan**.

### 4. Penyelarasan Desain
*   **Kriteria:** Tampilan kolom baru dan seluruh baris tabel harus konsisten menggunakan gaya **Premium Dark Futuristic Glassmorphism** (warna teks `#white` / `#f8fafc`, tingkat kecerahan, ukuran font, dan border halus bernuansa semi-transparan).

---

## 🛠️ File yang Dimodifikasi
*   **`resources/views/content/admin/pelatihan/index.blade.php`**

---

## 📅 Rencana Dampak & Pengujian
1.  **Pengujian Tampilan (UI/UX):** Pastikan lebar tabel menyesuaikan secara horizontal (atau memiliki scrollbar horizontal jika melebihi lebar kontainer) tanpa merusak layout Glassmorphism.
2.  **Verifikasi Format Tanggal:** Pastikan tanggal dikonversi secara benar menggunakan format lokal/Carbon `d/m/Y`.
3.  **Verifikasi Sortir:** Jika sorting diubah dari `batch` ke `tanggal_mulai`, pastikan controller admin pelatihan mendukung sorting untuk field `tanggal_mulai` (catatan: modifikasi controller di luar scope PRD ini, namun jika controller hanya mendukung sorting field dasar, parameter sort pada header perlu disesuaikan).
