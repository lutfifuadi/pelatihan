# Product Requirements Document (PRD): Pengaturan VAPID Key via Database

| Properti | Detail |
| :--- | :--- |
| **Status** | Draf |
| **Versi** | 1.0.0 |
| **Target Pengguna** | Administrator Sistem |
| **Topik** | Manajemen Konfigurasi Keamanan |

---

## 1. Latar Belakang & Tujuan
Saat ini, kunci VAPID (*Voluntary Application Server Identification*) disimpan di file `.env`. Hal ini menyulitkan tim administrator untuk memperbarui kunci tanpa harus mengakses server atau melakukan *deployment* ulang. 

**Tujuan:**
Memindahkan penyimpanan `VAPID_PUBLIC_KEY` dan `VAPID_PRIVATE_KEY` dari file `.env` ke tabel konfigurasi di database. Hal ini memungkinkan manajemen kunci melalui panel admin secara dinamis, meningkatkan fleksibilitas operasional, dan mendukung lingkungan *multi-tenant* atau *auto-scaling* di masa depan.

## 2. User Stories
*   **Sebagai Admin,** saya ingin melihat status kunci VAPID saat ini melalui panel admin agar saya tahu apakah notifikasi push sudah terkonfigurasi dengan benar.
*   **Sebagai Admin,** saya ingin memperbarui kunci VAPID melalui UI agar saya bisa mengganti kunci tanpa menyentuh file sistem (`.env`).
*   **Sebagai Sistem,** saya harus membaca kunci VAPID dari database dengan performa yang optimal (caching).

## 3. Spesifikasi Fitur
*   **Penyimpanan:** Kunci VAPID akan disimpan dalam tabel `settings` atau tabel khusus `vapid_keys`.
*   **Enkripsi:** `VAPID_PRIVATE_KEY` **wajib** dienkripsi di database menggunakan standar enkripsi yang kuat (misalnya: AES-256-CBC) dengan `APP_KEY` sebagai kunci enkripsi.
*   **UI/Admin:**
    *   Formulir input untuk Public Key dan Private Key.
    *   Tombol "Generate New Keys" untuk mempermudah pembuatan pasangan kunci baru.
*   **Caching:** Nilai dari database harus di-*cache* (misal: Redis atau memcached) untuk menghindari query database berulang pada setiap *request* notifikasi.

## 4. Perubahan Teknis
*   **Migration:** Membuat tabel/entry baru untuk menyimpan konfigurasi VAPID.
*   **Service Layer:**
    *   Membuat atau memodifikasi `VapidService` untuk mengambil kunci dari database (dengan fallback ke `.env` jika belum di-*migrate*).
    *   Implementasi `Encryption/Decryption` sebelum menyimpan ke atau membaca dari database.
*   **Security:** Penambahan middleware/validasi input untuk memastikan kunci yang disimpan valid.

## 5. Kriteria Penerimaan (Acceptance Criteria)
1.  Admin dapat menyimpan kunci VAPID baru melalui form di admin panel.
2.  Private Key yang disimpan di database harus dalam bentuk terenkripsi.
3.  Sistem dapat mengirimkan push notification dengan menggunakan kunci yang diambil dari database.
4.  Terdapat mekanisme *fallback*: jika tidak ada kunci di database, sistem menggunakan kunci dari file `.env` (jika ada).
5.  Perubahan kunci di database tercatat di log audit (jika ada fitur audit log).

## 6. Pertimbangan Keamanan
*   **Enkripsi At Rest:** Private key tidak boleh disimpan sebagai *plain text*.
*   **Akses UI:** Hanya super admin yang memiliki akses ke modul pengaturan kunci VAPID.
*   **Proteksi Log:** Pastikan kunci tidak tercetak di log aplikasi selama proses debugging.
*   **Rotasi Kunci:** Perlu dokumentasi atau peringatan bahwa penggantian kunci VAPID akan membatalkan *subscription* pengguna yang lama jika aplikasi klien tidak melakukan *re-subscribe*.

---
*Dokumen ini dibuat untuk memandu pengembangan fitur manajemen VAPID.*
