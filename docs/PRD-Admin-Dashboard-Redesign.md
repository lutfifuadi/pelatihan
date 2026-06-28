# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Redesign UI/UX Dashboard Admin (v2.0) — Sistem Pelatiahanku Premium

---

### Informasi Dokumen

| Properti | Detail |
| :--- | :--- |
| **Nama Fitur** | Redesign UI/UX Dashboard Admin (v2.0) |
| **Versi** | v2.0.0 |
| **Status** | Draft - Ready for Review |
| **Penulis** | Antigravity (PRD Specialist) |
| **Tanggal Pembuatan** | 28 Juni 2026 |
| **Prioritas** | Tinggi (P1) |
| **Target Rilis** | Q3 2026 |

#### Riwayat Versi & Perubahan (Changelog)
* **v2.0.0 (28 Juni 2026):** Rilis draf awal untuk redesign dashboard admin v2.0. Mengintegrasikan tema Premium Dark Futuristic Glassmorphism, Ringkasan Statistik Real-time, Corong Verifikasi Pendaftaran, Live Monitoring Pelatihan Hari Ini, dan Tabbed Log Aktivitas & Audit Presensi.

---

## 1. Executive Summary

Dokumen ini mendokumentasikan kebutuhan untuk Redesign UI/UX Dashboard Admin (v2.0) pada platform "Pelatihanku". Pembaruan ini bertujuan untuk menyelaraskan tampilan dashboard administrator dengan tema visual **Premium Dark Futuristic Glassmorphism** (berbasis warna gelap `#0b0f19`, font 'Outfit' & 'Sora', kartu transparan, dan efek glow neon) yang sebelumnya telah sukses diimplementasikan di halaman dashboard peserta dan antarmuka operasional lainnya. 

Selain pembaruan estetika, dashboard v2.0 ini memperkenalkan 3 komponen fungsional baru yang kritikal untuk efisiensi pemantauan admin:
1. **Real-time Status Sync** untuk koneksi WhatsApp Gateway.
2. **Registration Funnel Tracker** untuk memantau kelancaran alur verifikasi pendaftar.
3. **Live Monitoring Pelatihan Hari Ini** yang dilengkapi dengan tombol pintasan operasional presensi (Scanner & Proyektor).
4. **Tabbed Log System** untuk memisahkan aktivitas umum sistem dengan riwayat audit presensi sensitif (bypass/koreksi).

---

## 2. Latar Belakang & Masalah

### 2.1 Latar Belakang
Sebagai pusat operasional dari aplikasi "Pelatihanku", admin dashboard memegang peran vital dalam memantau data pengguna, status integrasi sistem, dan kelancaran kelas pelatihan harian. Seiring dengan diperkenalkannya fitur-fitur baru seperti **Sistem Presensi Presisi (Geofencing & Dynamic QR)**, **WhatsApp Gateway**, dan **Audit Log Presensi**, dashboard admin v1.0 dirasa kurang memadai untuk menyajikan data secara cepat, intuitif, dan responsif.

### 2.2 Masalah Utama pada Dashboard Admin v1.0
1. **Ketidakselarasan Visual (UI Inconsistency):** Dashboard peserta dan halaman operasional panitia telah di-update menggunakan gaya *Premium Dark Futuristic Glassmorphism*. Dashboard admin lama yang masih bertema terang/flat terasa asing dan tidak menyatu dengan identitas visual aplikasi yang baru.
2. **Ketiadaan Visibilitas Koneksi WA Gateway:** Admin tidak tahu apakah pengirim WhatsApp saat itu berstatus terhubung atau terputus kecuali jika mereka membuka menu pengaturan WA Gateway secara manual. Padahal notifikasi presensi sangat bergantung pada keaktifan gateway ini.
3. **Bottleneck Pendaftaran Tidak Terdeteksi:** Alur pendaftaran peserta memiliki 5 tahapan utama. Tanpa adanya visualisasi corong (funnel), admin kesulitan mendeteksi pada tahapan mana terjadi penumpukan/antrean verifikasi peserta (misalnya: menumpuk di pengecekan Newbimma atau menunggu konfirmasi WhatsApp).
4. **Operasional Kelas yang Lambat:** Panitia lapangan sering kali harus menelusuri menu pelatihan yang panjang hanya untuk membuka Scanner QR atau Layar Proyektor Kelas Aktif Hari Ini.
5. **Log Aktivitas Bercampur:** Log audit koreksi presensi (manual bypass oleh panitia atau koreksi admin) bercampur dengan log sistem umum (seperti CRUD user biasa), sehingga mempersulit proses audit kepatuhan kehadiran.

---

## 3. Target Pengguna
1. **Super Admin / Sistem Administrator (IT):** Bertanggung jawab memantau kesehatan server, gateway WhatsApp, dan log aktivitas sistem.
2. **Admin Operasional / Verifikator:** Bertanggung jawab meloloskan pendaftaran peserta, memantau funnel pendaftaran, dan melihat status pelatihan hari ini.
3. **Kepala Instansi / Supervisor:** Mengawasi performa kehadiran peserta dan melakukan audit terhadap koreksi data presensi.

---

## 4. User Stories

| ID | Aktor | Pernyataan User Story | Kriteria Penerimaan (Overview) |
| :--- | :--- | :--- | :--- |
| **US-01** | Admin | Sebagai administrator, saya ingin melihat ringkasan statistik (Total Pengguna, WA Gateway, dan Presensi Hari Ini) dengan tampilan glassmorphism yang responsif agar saya segera mengetahui kondisi umum sistem saat pertama kali login. | Menampilkan widget total peserta/instruktur/koordinator, status WA Gateway terkirim/gagal/pending beserta indikator koneksi real-time, dan ringkasan presensi harian (hadir vs kuota, persentase kehadiran). |
| **US-02** | Admin | Sebagai admin IT, saya ingin status koneksi WA Gateway di-refresh secara otomatis via AJAX polling tanpa perlu me-reload halaman agar status offline/online dapat dideteksi secara presisi. | Indikator memanggil API `/admin/whatsapp-gateway/status` setiap 30 detik (polling) atau manual klik refresh, lalu memperbarui badge warna secara dinamis. |
| **US-03** | Verifikator | Sebagai verifikator pendaftaran, saya ingin melihat grafik/batang corong (registration funnel) dari 5 tahapan status agar saya dapat mengidentifikasi penumpukan antrean verifikasi peserta dengan cepat. | Batang visual bertema neon yang menampilkan jumlah pendaftar di status `pending` -> `approved` -> `waiting_wa_confirmation` -> `waiting_newbimma_check` -> `confirmed`. |
| **US-04** | Admin Operasional | Sebagai admin operasional, saya ingin melihat daftar pelatihan yang aktif **hari ini** lengkap dengan progress bar kehadirannya dan tombol pintasan operasional agar saya bisa memandu/membantu panitia di lapangan dengan efisien. | Tabel pelatihan aktif hari ini menampilkan: nama kelas, instruktur, progres "X / Y Hadir" (dengan progress bar neon), serta shortcut icon ke halaman Scanner Panitia dan Monitoring Proyektor. |
| **US-05** | Supervisor | Sebagai supervisor audit, saya ingin melihat log aktivitas yang terbagi dalam dua tab terpisah (Umum vs Audit Presensi) agar saya dapat berfokus memeriksa tindakan bypass manual presensi yang dilakukan panitia. | Komponen log di bawah dashboard memiliki 2 tab: Tab 1 untuk CRUD/sistem umum (`activity_logs`), Tab 2 untuk log bypass/koreksi kehadiran (`audit_logs` / bypassed attendances). |

---

## 5. Spesifikasi UI/UX & Tema Desain (Premium Dark Futuristic)

Untuk memastikan konsistensi visual 100% dengan bagian dashboard lainnya, berikut adalah panduan styling yang wajib dipatuhi:

### 5.1 Skema Warna & Latar Belakang
* **Background Utama Halaman:** `#0b0f19` (Deep dark slate/blue).
* **Glow/Gradient Background:**
  ```css
  background-color: #0b0f19 !important;
  background-image: 
    radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
    radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
    radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
  ```
* **Font Keluarga:** Headings menggunakan **'Sora'** (semi-bold/bold), Body Text menggunakan **'Outfit'** (light/regular/medium).

### 5.2 Komponen Card Glassmorphism (`.glass-card-premium`)
Semua container widget wajib menerapkan style glassmorphism premium:
* **Background:** `rgba(15, 23, 42, 0.45)` (Slate dark semi-transparan).
* **Backdrop Filter:** `blur(12px)` (atau `-webkit-backdrop-filter: blur(12px)`).
* **Border:** `1px solid rgba(255, 255, 255, 0.08)`.
* **Box Shadow:** `0 8px 32px 0 rgba(0, 0, 0, 0.37)`.
* **Border Radius:** `12px` (atau `rounded-3` pada bootstrap/tailwind custom).

### 5.3 Elemen Interaktif (`.btn-glow-premium` & Badge)
* **Glow Button:** Tombol dengan border semi-transparan dan efek neon bayangan halus yang membesar saat di-hover.
* **Badge Status WA Gateway:**
  * `Connected`: Teks & border Hijau Emerald Neon (`#10b981`), dengan denyut animasi (*pulse*).
  * `Disconnected` / `Disconnect`: Teks & border Amber/Kuning Neon (`#f59e0b`).
  * `Offline` / `Unknown`: Teks & border Abu-abu (`#64748b`).

---

## 6. Struktur Visual Dashboard v2.0 (Wireframe & Layout)

Dashboard disusun menggunakan grid layout yang adaptif (responsive grid):

```
+------------------------------------------------------------------------------------------------+
|  HEADER: Judul Dashboard & Tanggal Berjalan (Theme: Sora & Outfit)                            |
+------------------------------------------------------------------------------------------------+
|  SECTION 1: SUMMARY CARDS (3 Columns Grid)                                                     |
|  +--------------------------+  +--------------------------+  +------------------------------+  |
|  | Card A: Total Pengguna   |  | Card B: WA Gateway Info  |  | Card C: Presensi Hari Ini    |  |
|  | - Peserta: XXX           |  | - Notif: Sent/Failed/Pend|  | - Pelatihan Aktif: X Kelas   |  |
|  | - Instruktur: YY         |  | - Status: [Connected]    |  | - Kehadiran: AA/BB (CC%)     |  |
|  | - Koordinator: ZZ        |  |   (AJAX Polling + Sync)  |  |   (Progress Bar Neon)        |  |
|  +--------------------------+  +--------------------------+  +------------------------------+  |
+------------------------------------------------------------------------------------------------+
|  SECTION 2: CORONG VERIFIKASI PENDAFTARAN (REGISTRATION FUNNEL) - FULL WIDTH                   |
|  +------------------------------------------------------------------------------------------+  |
|  | [Pending] ===> [Approved] ===> [Waiting WA] ===> [Cek Newbimma] ===> [Confirmed]          |  |
|  |    (90)            (60)            (45)              (30)               (25)             |  |
|  | [=== Neon Progress Bar / Funnel Chart representing distribution of applicants ===]      |  |
|  +------------------------------------------------------------------------------------------+  |
+------------------------------------------------------------------------------------------------+
|  SECTION 3: LIVE MONITORING PELATIHAN HARI INI - FULL/HALF WIDTH                               |
|  +------------------------------------------------------------------------------------------+  |
|  | Tabel Pelatihan Aktif Hari Ini:                                                           |  |
|  | Nama Pelatihan      | Instruktur     | Real-time Attendance | Operasional Shortcut       |  |
|  | Pelatihan Android   | Budi Santoso   | [==== 12/25 Hadir ===] | [Scanner] [Proyektor]      |  |
|  | Pelatihan Laravel   | Candra Wijaya  | [===== 8/15 Hadir ===] | [Scanner] [Proyektor]      |  |
|  +------------------------------------------------------------------------------------------+  |
+------------------------------------------------------------------------------------------------+
|  SECTION 4: LOG AKTIVITAS & AUDIT PRESENSI TERBARU (Tabbed Card View)                          |
|  +------------------------------------------------------------------------------------------+  |
|  |  [ Tab 1: Log Aktivitas Umum ]   |   [ Tab 2: Log Audit Presensi ] (Bypassed & Corrected) |  |
|  |  --------------------------------------------------------------------------------------  |  |
|  |  - Admin A membuat pelatihan "Pelatihan Android" (2 menit yang lalu)                    |  |
|  |  - Panitia B melakukan bypass presensi untuk Peserta C. Alasan: HP Lowbat (5 menit lalu) |  |
|  |  - Admin A mengubah status kehadiran Peserta D (10 menit yang lalu)                     |  |
|  +------------------------------------------------------------------------------------------+  |
+------------------------------------------------------------------------------------------------+
```

---

## 7. Logika Backend & Query Database Baru

Untuk menjaga performa loading dashboard tetap di bawah **1.5 detik**, query database harus dioptimalkan dengan meminimalkan query redundan dan menggunakan teknik single-query grouping atau selective columns loading.

### 7.1 Query Section 1: Statistik Kehadiran Hari Ini
Mengambil jumlah kehadiran peserta real-time pada pelatihan yang berjalan hari ini:
```php
$today = now()->toDateString();

// Mendapatkan list id pelatihan yang aktif hari ini
$activePelatihanIds = Pelatihan::where('is_active', true)
    ->where('tanggal_mulai', '<=', $today)
    ->where('tanggal_selesai', '>=', $today)
    ->pluck('id');

// Total kuota terkonfirmasi hari ini
$totalKuotaHariIni = Enrollment::whereIn('pelatihan_id', $activePelatihanIds)
    ->where('status', \App\Enums\EnrollmentStatus::Confirmed)
    ->count();

// Jumlah peserta yang sudah hadir hari ini (mengacu pada tabel attendances tanggal hari ini)
$totalHadirHariIni = Attendance::whereDate('date', $today)
    ->where('status', 'hadir')
    ->whereIn('enrollment_id', function ($query) use ($activePelatihanIds) {
        $query->select('id')
            ->from('enrollments')
            ->whereIn('pelatihan_id', $activePelatihanIds);
    })
    ->count();

$persentaseKehadiranHariIni = $totalKuotaHariIni > 0 
    ? round(($totalHadirHariIni / $totalKuotaHariIni) * 100, 1) 
    : 0;
```

### 7.2 Query Section 2: Corong Verifikasi Pendaftaran
Menghitung jumlah data pendaftaran (`enrollments`) per status pendaftaran secara efisien:
```php
$funnelData = Enrollment::selectRaw("
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending,
        SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as total_approved,
        SUM(CASE WHEN status = 'waiting_wa_confirmation' THEN 1 ELSE 0 END) as total_waiting_wa,
        SUM(CASE WHEN status = 'waiting_newbimma_check' THEN 1 ELSE 0 END) as total_waiting_newbimma,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as total_confirmed
    ")->first();
```

### 7.3 Query Section 3: Live Monitoring Pelatihan Hari Ini
Mengambil detail pelatihan berjalan hari ini beserta progres kehadiran peserta:
```php
$livePelatihans = Pelatihan::where('is_active', true)
    ->where('tanggal_mulai', '<=', $today)
    ->where('tanggal_selesai', '>=', $today)
    ->with([
        // Eager load instruktur (User terelasi lewat logic business) atau scheduler jika ada
        'enrollments' => function ($q) {
            $q->where('status', \App\Enums\EnrollmentStatus::Confirmed);
        }
    ])
    ->get()
    ->map(function ($pelatihan) use ($today) {
        $confirmedCount = $pelatihan->enrollments->count();
        
        // Hitung yang hadir di tanggal hari ini
        $hadirCount = Attendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->whereIn('enrollment_id', $pelatihan->enrollments->pluck('id'))
            ->count();

        // Cari nama instruktur (jika di-assign)
        // Catatan: Jika relasi instruktur di-load dari tabel users/pelatihan
        $instrukturName = "Belum Ditugaskan"; // Fallback default
        // Logic penugasan instruktur dapat disesuaikan dengan skema tabel database pelatihan

        return [
            'id' => $pelatihan->id,
            'nama' => $pelatihan->nama,
            'instruktur' => $instrukturName,
            'hadir' => $hadirCount,
            'total' => $confirmedCount,
            'persentase' => $confirmedCount > 0 ? round(($hadirCount / $confirmedCount) * 100) : 0
        ];
    });
```

### 7.4 Query Section 4: Log Audit Presensi
Menampilkan riwayat bypass atau koreksi kehadiran secara spesifik:
```php
// Tab 1: Log Aktivitas Umum (CRUD, dll)
$generalLogs = \App\Models\ActivityLog::with('user:id,name,role')
    ->recent()
    ->take(5)
    ->get();

// Tab 2: Log Audit Presensi (Bypass manual dan koreksi data)
// Mengambil data dari tabel audit_logs yang menargetkan entitas Attendance
$auditLogs = \App\Models\AuditLog::with('actor:id,name')
    ->where('target_entity', 'Attendance')
    ->whereIn('action_type', ['bypass', 'correction', 'update'])
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();
```

---

## 8. Kriteria Penerimaan (Acceptance Criteria - AC)

### AC-01: Visual Layout & Desain Premium Dark Glassmorphism
* **AC-01.1:** Warna background dashboard admin menggunakan kode hex `#0b0f19` dengan overlay radial gradient pink, ungu, dan indigo sesuai panduan tema.
* **AC-01.2:** Semua widget/card utama wajib memiliki class `.glass-card-premium` yang menerapkan backdrop-filter blur `12px` dan border transparan tipis `rgba(255, 255, 255, 0.08)`.
* **AC-01.3:** Font teks pada dashboard menggunakan 'Outfit' dan heading menggunakan 'Sora' tanpa ada kebocoran visual font default browser.

### AC-02: WhatsApp Gateway Status & Live Synchronization
* **AC-02.1:** Dashboard menampilkan card status WhatsApp Gateway dengan 3 state visual: Hijau Emerald (Connected), Kuning/Amber (Disconnected), dan Abu-abu (Offline).
* **AC-02.2:** Terdapat tombol kecil "Refresh" (ikon putar) di sebelah teks status koneksi. Ketika diklik, tombol tersebut memicu AJAX request ke `/admin/whatsapp-gateway/status` tanpa reload halaman.
* **AC-02.3:** AJAX Polling berjalan secara otomatis setiap 30 detik sejak halaman dashboard dimuat, untuk menjaga keakuratan data status.

### AC-03: Corong Verifikasi Pendaftaran (Funnel)
* **AC-03.1:** Grafik corong atau batang progres vertikal/horizontal menampilkan data pendaftar pada 5 status secara bertahutan: `Pending` -> `Approved` -> `Waiting WA Confirmation` -> `Waiting Newbimma Check` -> `Confirmed`.
* **AC-03.2:** Setiap tahapan corong menampilkan angka riil jumlah peserta yang sedang tertahan pada status tersebut.
* **AC-03.3:** Animasi hover pada setiap segmen corong harus menampilkan *neon shadow glow* untuk menyoroti area yang sedang dipilih oleh mouse kursor admin.

### AC-04: Live Monitoring Pelatihan Hari Ini & Shortcut Operasional
* **AC-04.1:** Widget menampilkan daftar kelas pelatihan yang berlangsung hanya pada tanggal hari ini.
* **AC-04.2:** Jika tidak ada pelatihan hari ini, widget menampilkan state kosong (*empty state*) bertuliskan "Tidak ada pelatihan aktif hari ini" dengan ikon ilustrasi bertema dark futuristic.
* **AC-04.3:** Setiap baris pelatihan memiliki progress bar kehadiran real-time yang secara dinamis menghitung `(Jumlah Hadir Hari Ini / Total Peserta Terkonfirmasi) * 100%`.
* **AC-04.4:** Setiap baris dilengkapi 2 tombol pintasan operasional yang aktif:
  * Tombol **Scanner** (ikon scan/barcode) mengarah ke halaman `/panitia/pelatihan/{id}/scanner`.
  * Tombol **Proyektor** (ikon proyektor/tv) mengarah ke halaman `/instruktur/pelatihan/{id}/monitoring`.

### AC-05: Tabbed Logs (Aktivitas Umum vs Audit Presensi)
* **AC-05.1:** Terdapat komponen Tab di bagian bawah dashboard dengan 2 tab fungsional: "Log Aktivitas Umum" dan "Audit Presensi".
* **AC-05.2:** Tab "Log Aktivitas Umum" menampilkan data log dari tabel `activity_logs`.
* **AC-05.3:** Tab "Audit Presensi" hanya menampilkan aksi koreksi manual, bypass panitia, dan perubahan status absensi yang dicatat dalam tabel `audit_logs` atau ditandai kolom `bypassed_by` / `corrected_by` pada tabel `attendances`.
* **AC-05.4:** Setiap baris audit log wajib menampilkan nama peserta terdampak, nama pelaku aksi (panitia/admin), timestamp presisi, alasan bypass/koreksi, dan alamat IP pelaku.

### AC-06: Performa & Optimasi Query
* **AC-06.1:** Halaman dashboard admin v2.0 harus dimuat sepenuhnya dalam waktu kurang dari **1.5 detik** pada koneksi internet standar (throttling fast 3G).
* **AC-06.2:** Tidak boleh terjadi N+1 Query pada pemanggilan data pelatihan aktif hari ini maupun log aktivitas. Semua relasi (seperti `user`, `actor`, `enrollments`) wajib menggunakan Eager Loading (`with`).

---

## 9. Rencana Pembagian Tugas Tim (Task Breakdown & Timeline)

Untuk menyukseskan implementasi Dashboard Admin v2.0 ini, pengerjaan dibagi ke dalam 4 spesialisasi peran:

```
[Mulai Pengembangan]
       │
       ├─► [Ayu - Frontend Developer & Design System] (Desain Glassmorphism, CSS, Blade Layout v2.0)
       │
       ├─► [Bayu - Backend Developer] (Controller Logic, Optimize DB Queries, Dynamic Funnel & Logs)
       │
       ├─► [Rizky - API Integrator] (AJAX Status Polling WA Gateway & Endpoint Integration)
       │
       └─► [Farhan - Tester / QA] (Validasi Kriteria Penerimaan, Penulisan automated test PHPUnit)
```

### 9.1 Ayu (Frontend Developer & Design System)
* **Tugas:**
  1. Membuat/memperbarui file view `resources/views/content/dashboard/admin.blade.php`.
  2. Menerapkan style `.glass-card-premium` dan gradient radial background `#0b0f19`.
  3. Mengonfigurasi font 'Outfit' dan 'Sora' pada layout dashboard.
  4. Merancang visualisasi batang corong (funnel) pendaftaran dengan CSS/Tailwind neon progress bar.
  5. Membuat layout tabbed log dengan transisi transparan yang halus.

### 9.2 Bayu (Backend Developer)
* **Tugas:**
  1. Menyesuaikan method `admin()` di `App\Http\Controllers\DashboardController.php`.
  2. Menulis query data statistik pengguna, WA gateway log harian, dan ringkasan kehadiran hari ini.
  3. Menulis query grouping untuk mendistribusikan data funnel pendaftaran.
  4. Menyiapkan data log terpisah untuk General Activity Logs dan Presence Audit Logs.
  5. Memastikan semua database query terindeks dengan baik dan terbebas dari masalah N+1 query.

### 9.3 Rizky (API Integrator)
* **Tugas:**
  1. Membuat javascript dynamic handler di `resources/assets/js/dashboard-admin.js`.
  2. Melakukan AJAX polling (setiap 30 detik) ke route `/admin/whatsapp-gateway/status` untuk mengecek koneksi terkini dari device WA.
  3. Mengaktifkan fungsi tombol refresh manual agar memicu pemanggilan AJAX instan secara real-time.
  4. Memperbarui badge status visual secara dinamis berdasarkan respons JSON API.

### 9.4 Farhan (Tester / QA & Bug Hunter)
* **Tugas:**
  1. Membuat test case fungsional untuk memverifikasi bahwa route `/dashboard/admin` mengembalikan status HTTP 200 OK untuk user dengan role `admin`.
  2. Menguji performa pemuatan halaman (target load time < 1.5s).
  3. Melakukan simulasi matinya koneksi WA Gateway dan memverifikasi perubahan warna badge secara visual di dashboard.
  4. Memastikan data audit log presensi pada Tab 2 hanya merekam data bypass/koreksi kehadiran.
