# Product Requirements Document (PRD): Fitur Deteksi Status WhatsApp Gateway

| Properti | Detail |
| :--- | :--- |
| **Status** | Draf |
| **Versi** | 1.1.0 |
| **Target Pengguna** | Administrator Sistem / IT Support |
| **Topik** | Pemantauan Koneksi WhatsApp Gateway |
| **Tanggal Pembuatan** | Minggu, 28 Juni 2026 |

---

## 1. Executive Summary

Fitur Deteksi Status WhatsApp Gateway bertujuan untuk menyediakan indikator visual yang *real-time* dan interaktif mengenai status koneksi perangkat WhatsApp ke server gateway. Dengan memanfaatkan AJAX polling pada saat halaman pertama kali dimuat serta opsi manual refresh, administrator dapat memantau secara langsung apakah WhatsApp Gateway dalam status `Connected`, `Disconnected` (Disconnect), atau `Offline/Unknown` tanpa perlu memuat ulang keseluruhan halaman web.

---

## 2. Latar Belakang & Tujuan

Aplikasi "Pelatihanku" bergantung pada pengiriman notifikasi berbasis WhatsApp untuk pengingat presensi, pelatihan, dan pengumuman lainnya. Ketika perangkat pengirim (*sender number*) terputus dari jaringan WhatsApp Gateway (misalnya karena sesi kedaluwarsa, tidak ada internet pada perangkat, atau masalah pada server gateway), semua pesan notifikasi akan gagal terkirim tanpa sepengetahuan administrator.

**Tujuan:**
- Memberikan visibilitas langsung mengenai status konektivitas perangkat WhatsApp pengirim langsung dari admin panel.
- Membantu tim administrator mengidentifikasi masalah koneksi dengan cepat sehingga dapat segera melakukan penanganan (seperti re-scan QR atau menghubungkan ulang perangkat).
- Meminimalkan kegagalan pengiriman pesan notifikasi karena status perangkat tidak siap/terputus.

---

## 3. User Stories

*   **Sebagai Admin,** saya ingin melihat indikator status koneksi WhatsApp Gateway saat membuka halaman manajemen WhatsApp agar saya langsung mengetahui apakah sistem siap mengirimkan pesan.
*   **Sebagai Admin,** saya ingin melihat badge warna yang merepresentasikan status koneksi (hijau untuk terhubung, merah untuk terputus, abu-abu untuk offline/tidak dikenal) agar saya dapat mencerna informasi status secara cepat.
*   **Sebagai Admin,** saya ingin memiliki tombol refresh manual di sebelah badge status koneksi untuk mengecek ulang status perangkat tanpa harus memuat ulang (reload) seluruh halaman panel admin.

---

## 4. Spesifikasi Teknis

### A. Integrasi API Gateway Eksternal
Aplikasi akan mengonsumsi API eksternal untuk mendeteksi status perangkat.
- **Endpoint:** `https://wa.lutfifuadi.my.id/info-devices`
- **Method:** `GET` atau `POST`
- **Parameter Request:**
  - `api_key` (string, required) - Kunci API WhatsApp Gateway yang dikonfigurasi pada aplikasi.
  - `number` (string, required) - Nomor HP pengirim yang terdaftar/ingin dicek.
- **Contoh URL Request (GET):**
  `https://wa.lutfifuadi.my.id/info-devices?api_key=1234567890&number=6281222xxxxx`
- **Contoh JSON Response (Success):**
  ```json
  {
      "status": true,
      "info": [
          {
              "id": 1,
              "user_id": 1,
              "body": "628122xxxxxx",
              "webhook": null,
              "status": "Disconnect",
              "created_at": "2024-08-16T11:07:27.000000Z",
              "updated_at": "2024-08-16T11:07:27.000000Z",
              "message_sent": 0,
              "chatgpt": null,
              "typebot": 0,
              "reject_call": 0,
              "reject_message": null,
              "can_read_message": 0,
              "reply_when": "Personal",
              "chatgpt_name": null,
              "chatgpt_api": null,
              "gemini_name": null,
              "gemini_api": null,
              "claude_name": null,
              "claude_api": null,
              "webhook_read": 0,
              "webhook_reject_call": 0,
              "webhook_typing": 0,
              "bot_typing": 0
          }
      ]
  }
  ```

### B. Backend Laravel Service
1.  **WhatsAppService (`App\Services\WhatsAppService`):**
    - Tambahkan metode `checkDeviceStatus()`.
    - Metode ini bertugas melakukan HTTP Request ke API menggunakan wrapper `Illuminate\Support\Facades\Http`.
    - Data `api_key` dan `number` diambil secara dinamis dari konfigurasi sistem (baik config file, database setting, atau `.env`).
    - Menangani kemungkinan error (seperti timeout, HTTP status >= 400, atau respon JSON tidak valid).

### C. AJAX Routing & Controller
1.  **Routing:**
    - Definisikan route di dalam grup middleware admin (`routes/web.php`):
      `GET /admin/whatsapp-gateway/status` dengan nama route `admin.whatsapp-gateway.status`.
2.  **Controller:**
    - Controller admin menangani request ke route di atas.
    - Mengambil konfigurasi `whatsapp_api_key` dan `whatsapp_sender` (nomor pengirim).
    - Memanggil `WhatsAppService::checkDeviceStatus()`.
    - Memproses respon dari API dan mengembalikan JSON response dengan format konsisten untuk dikonsumsi frontend, misalnya:
      ```json
      {
          "success": true,
          "device_status": "Connected", // Connected / Disconnected / Offline
          "sender": "628122xxxxxx",
          "message": "Device status retrieved successfully."
      }
      ```
    - Jika API tidak merespon atau terjadi kegagalan koneksi ke API Gateway, return fallback berupa status `Offline` atau `Unknown`.

### D. Frontend UI/UX (AJAX Polling & Display)
1.  **Lokasi Halaman:** `/admin/whatsapp-gateway`
2.  **Indikator Status Badge Glowing:**
    - **Connected:** Badge hijau terang dengan efek glow/pulsa animasi. Teks: `Connected`.
    - **Disconnected (Disconnect):** Badge merah terang dengan efek glow/pulsa animasi. Teks: `Disconnected`.
    - **Offline/Unknown (Error Koneksi):** Badge abu-abu/kuning. Teks: `Offline` atau `Unknown`.
    - **Loading/Fetching:** Badge dengan animasi loading spinner mini atau status "Checking...".
3.  **Tombol Refresh Manual:**
    - Tombol berbentuk ikon sync / spinner di samping badge status.
    - Ketika diklik, tombol akan memicu pemanggilan fungsi JavaScript `fetch` ke route `admin.whatsapp-gateway.status` tanpa memuat ulang halaman.
    - Memberikan efek putaran (spin) pada ikon saat proses fetching sedang berlangsung.
4.  **Automatic Load (On Page Load):**
    - Menggunakan JavaScript (Vanilla JS atau Alpine.js) untuk secara otomatis melakukan fetch ke endpoint AJAX admin segera setelah elemen halaman selesai dimuat (`DOMContentLoaded`).
5.  **Periodic Real-Time Polling:**
    - Sistem wajib mendeteksi status secara real-time menggunakan JavaScript `setInterval` (periodik) setiap 10 detik setelah halaman dimuat.
    - Pengecekan berkala (polling) harus berjalan secara *silent* (tidak perlu menampilkan animasi spin berputar kencang pada tombol refresh agar tidak mengganggu fokus admin, namun cukup perbarui warna/teks badge secara mulus saat response diterima).
    - Jika tombol refresh diklik secara manual, barulah jalankan animasi spinner sebagai feedback aksi pengguna.
    - Pastikan interval dibersihkan jika halaman di-unload (`window.addEventListener('beforeunload', ...)` atau `window.addEventListener('unload', ...)`).

---

## 5. Alur Pengguna (User Flow)

```
[Admin mengakses Halaman WhatsApp Gateway]
                 │
                 ▼
[Halaman dimuat & Indikator Status menampilkan "Checking..."]
                 │
                 ▼ (JavaScript fetch otomatis ke route admin)
[Laravel Controller mengambil config & request ke API Gateway]
                 │
        ┌────────┴────────┐
        ▼                 ▼
   [API Berhasil]   [API Gagal / Timeout]
        │                 │
        │                 └────────────────────────┐
        ▼                                          ▼
[Parsing Respon Status ("Connected" / "Disconnect")]  [Ubah Status menjadi "Offline"]
        │                                          │
        └─────────────────┬────────────────────────┘
                          │
                          ▼
[Halaman mengupdate Badge Status & menghilangkan spinner]
                          │
         ┌────────────────┴────────────────┐
         ▼ (Opsional)                      ▼
[Admin melihat status OK]         [Admin menekan Tombol Refresh]
                                           │
                                           ▼
                                 [Ulangi proses fetch]
```

---

## 6. Kriteria Penerimaan (Acceptance Criteria)

1.  **AC 1: Integrasi Service**
    - Service `WhatsAppService` harus memiliki method `checkDeviceStatus()` yang mengirim parameter `api_key` dan `number` yang valid ke endpoint API Gateway.
    - Service dapat mendeteksi kegagalan koneksi (seperti timeout) dan mengembalikan respons yang aman (tidak melempar fatal crash/error 500).

2.  **AC 2: Route & Controller**
    - Route `admin.whatsapp-gateway.status` hanya dapat diakses oleh user dengan hak akses admin terautentikasi.
    - Controller mengembalikan data berformat JSON berisi status konektivitas perangkat.

3.  **AC 3: Representasi UI (Badge Status)**
    - Tampilan badge harus berubah secara dinamis berdasarkan respons AJAX:
      - Status `Connected` -> Hijau glowing.
      - Status `Disconnect` -> Merah glowing.
      - Status lainnya / Gagal koneksi API -> Abu-abu/Kuning.
    - Saat melakukan loading, terdapat status indikasi "Checking..." agar pengguna tahu proses pengecekan sedang berjalan.

4.  **AC 4: Fungsionalitas Refresh & Auto-Fetch**
    - Pengecekan status berjalan secara otomatis 1x sesaat setelah halaman web dimuat.
    - Tombol refresh berfungsi dengan benar; menekan tombol refresh memicu reload status via AJAX secara instan dan memutar ikon refresh selama loading.
5.  **AC 5: Real-Time Periodic Polling**
    - Sistem wajib melakukan polling status secara berkala setiap 10 detik.
    - Polling berkala harus berjalan secara silent (tanpa spin animation pada tombol refresh).
    - Status badge harus terupdate secara dinamis dan mulus berdasarkan hasil polling berkala.
    - Interval polling harus dibersihkan dengan benar saat halaman di-unload.

---

## 7. Pembagian Tugas Tim

-   **Backend Developer (Bayu):**
    - Membuat konfigurasi pendukung jika belum ada (API Key & Nomor Pengirim).
    - Menambahkan method `checkDeviceStatus()` di `App\Services\WhatsAppService`.
    - Membuat route `/admin/whatsapp-gateway/status` beserta Controller pendukungnya.
    - Melakukan testing unit/endpoint backend untuk memastikan JSON response sesuai kontrak.
-   **Frontend Developer (Ayu):**
    - Membuat/memperbarui desain halaman `/admin/whatsapp-gateway`.
    - Mengintegrasikan styling Tailwind CSS untuk badge status glowing (hijau/merah/abu-abu).
    - Menulis kode JavaScript/Alpine.js untuk penanganan auto-fetch on load dan klik tombol refresh manual.
-   **Tester / QA (Farhan):**
    - Memverifikasi status badge berubah sesuai dengan kondisi perangkat sebenarnya (terhubung vs terputus).
    - Menguji skenario kegagalan API Gateway (seperti mematikan koneksi internet atau memalsukan API Key) untuk memastikan fallback ke status "Offline" berfungsi tanpa merusak halaman admin.

---
*Dokumen ini dibuat untuk memandu pengembangan fitur deteksi status WhatsApp Gateway.*
