# PRODUCT REQUIREMENTS DOCUMENT (PRD)
## Redesign UI/UX Dashboard Admin (v3.0) — Daftar Pelatihan + Informasi Lengkap

---

### Informasi Dokumen

| Properti | Detail |
| :--- | :--- |
| **Nama Fitur** | Redesign UI/UX Dashboard Admin (v3.0) |
| **Versi** | v3.0.0 |
| **Status** | Draft - Menunggu Review |
| **Penulis** | Sophia (Project Manager) — dengan masukan dari Dika, Bayu, Ayu |
| **Tanggal Pembuatan** | 28 Juni 2026 |
| **Prioritas** | Tinggi (P1) |
| **Target Rilis** | Q3 2026 |

#### Riwayat Versi & Perubahan (Changelog)
* **v3.0.0 (28 Juni 2026):** Rilis draf awal untuk redesign dashboard admin v3.0. Menambahkan **Daftar Pelatihan Lengkap** dengan filter dan progress bar, **Quick Actions Grid**, **Mini Chart Tren Pendaftaran 7 Hari**, **Top Instruktur Aktif**, serta **penyederhanaan label WA Gateway Status** (Kirim/Cek). Memanfaatkan data $latestPelatihan yang sudah ada di controller namun belum dirender.

---

## Executive Summary

Dokumen ini mendokumentasikan kebutuhan untuk **Redesign UI/UX Dashboard Admin (v3.0)** pada platform "Pelatihanku". Pembaruan ini merupakan lanjutan dari Dashboard v2.0 yang telah sukses diimplementasikan (Summary Cards, Registration Funnel, Live Class Monitoring, Leaflet Map, Tabbed Logs).

Berdasarkan evaluasi dan masukan dari user serta tim pengembangan (Dika, Bayu, Ayu), ditemukan bahwa:
1. **List/Daftar Pelatihan belum tampil di dashboard** — Data $latestPelatihan sudah di-pass dari controller namun tidak dirender di view.
2. **Label WA Gateway Status masih panjang** — "Utama" dan "Cek Nomor" dapat dipersingkat menjadi "Kirim" dan "Cek" untuk efisiensi ruang.
3. **Belum ada shortcut aksi cepat** — Admin sering mencari tombol akses cepat ke fungsi-fungsi utama.
4. **Belum ada visualisasi tren pendaftaran** — Data historis pendaftaran belum divisualisasikan.

### Perubahan dari v2.0
| Perubahan | Deskripsi |
|:---|:---|
| ✅ **DAFTAR PELATIHAN (NEW)** | List seluruh pelatihan dengan progress bar, filter, dan expandable row |
| ✅ **LABEL WA SINGKAT** | "Utama" → "Kirim", "Cek Nomor" → "Cek" |
| ✅ **QUICK ACTIONS GRID (NEW)** | 4 shortcut: Buat Pelatihan, Presensi Hari Ini, Export Data, Laporan |
| ✅ **TREN PENDAFTARAN (NEW)** | Mini line chart 7 hari terakhir (sparkline sederhana) |
| ✅ **TOP INSTRUKTUR (NEW)** | Leaderboard instruktur paling aktif |
| ✅ Layout disusun ulang** | Daftar Pelatihan ditempatkan strategis di atas Live Class |

---

## Latar Belakang & Masalah

### 2.1 Latar Belakang
Dashboard admin v2.0 telah memberikan peningkatan signifikan dengan visualisasi Summary Cards, Registration Funnel, Live Class Monitoring, dan Tabbed Logs. Namun, setelah dilakukan penggalian kebutuhan lebih lanjut dengan tim pengembangan, ditemukan beberapa celah informasi yang perlu ditambahkan.

### 2.2 Masalah Utama pada Dashboard Admin v2.0
1. **Tidak Ada Daftar Pelatihan Komprehensif:** Dashboard hanya menampilkan pelatihan yang aktif **hari ini** (Live Class Monitoring). Admin tidak bisa melihat gambaran keseluruhan program pelatihan dari dashboard — harus klik ke menu Pelatihan.
2. **Data Pelatihan Terbaru Tidak Dirender:** Controller (DashboardController@admin) sudah me-pass $latestPelatihan (4 pelatihan terbaru) namun belum digunakan di view dmin.blade.php. Artinya ada data siap pakai yang terbuang.
3. **Label WA Gateway Terlalu Panjang:** Label "Utama" dan "Cek Nomor" memakan ruang vertikal yang berharga di dashboard. Cukup disingkat menjadi "Kirim" dan "Cek" tanpa mengurangi kejelasan informasi.
4. **Tidak Ada Shortcut Aksi Cepat:** Admin harus menavigasi ke menu lain untuk membuat pelatihan baru, membuka presensi, export data, atau melihat laporan.
5. **Belum Ada Visualisasi Tren:** Admin tidak bisa melihat apakah pendaftaran sedang meningkat atau menurun dalam seminggu terakhir.
6. **Belum Ada Informasi Instruktur:** Instruktur aktif tidak terpantau dari dashboard.

---

## Target Pengguna
1. **Super Admin / Sistem Administrator (IT):** Memantau kesehatan server, gateway WhatsApp, log aktivitas, dan daftar pelatihan.
2. **Admin Operasional / Verifikator:** Memantau pendaftaran, funnel, melihat daftar pelatihan, progress enrollment, dan shortcut ke halaman operasional.
3. **Kepala Instansi / Supervisor:** Mengawasi performa kehadiran, melihat daftar program pelatihan, dan memantau aktivitas instruktur.

---

## User Stories

| ID | Aktor | Pernyataan User Story | Kriteria Penerimaan (Overview) |
| :--- | :--- | :--- | :--- |
| **US-01** | Admin | Sebagai administrator, saya ingin melihat **daftar seluruh pelatihan** beserta status, progress pendaftaran, dan progress kehadiran dari dashboard agar saya tidak perlu membuka halaman Pelatihan terpisah. | Tabel/list pelatihan yang menampilkan nama, batch, dinas, status (Aktif/Nonaktif/Selesai/Akan Datang), progress kuota (confirmed/kuota), progress kehadiran (hadir/total confirmed), dan aksi cepat seperti lihat detail atau manage. Dilengkapi filter status, pencarian nama, dan sorting (default: terbaru). |
| **US-02** | Admin | Sebagai admin, saya ingin **label status WA Gateway dipersingkat** agar lebih ringkas dan dashboard lebih rapi tanpa mengurangi informasi. | Label "Utama:" diganti "Kirim:" dan "Cek Nomor:" diganti "Cek:" dengan indikator dot hijau/merah + teks Connected/Disconnected. Tombol refresh cukup satu saja untuk kedua status. |
| **US-03** | Admin | Sebagai admin, saya ingin **shortcut aksi cepat** di dashboard untuk mengakses fungsi utama tanpa harus navigasi menu. | 4 tombol grid: ➕ Buat Pelatihan Baru (ke route create pelatihan), 📋 Presensi Hari Ini (ke panitia/operasional), 📤 Export Data (ke export page), 📊 Laporan (ke laporan page). |
| **US-04** | Admin | Sebagai admin, saya ingin melihat **grafik tren pendaftaran 7 hari terakhir** agar dapat mengidentifikasi tren naik/turun pendaftaran. | Mini line chart/sparkline sederhana yang menunjukkan jumlah pendaftar per hari dalam 7 hari terakhir. Bisa menggunakan Chart.js ringan atau inline SVG. |
| **US-05** | Admin | Sebagai supervisor, saya ingin melihat **top instruktur paling aktif** berdasarkan jumlah sesi yang diampu bulan ini. | Leaderboard mini (5 besar) instruktur dengan nama dan jumlah sesi/bulan. |

---

## Spesifikasi Fitur

### 4.1 Fitur A: Daftar Pelatihan Lengkap (List Pelatihan)

#### Bentuk UI: Hybrid Table-Card Premium
Daftar pelatihan ditampilkan dalam bentuk **table premium** dengan baris yang dapat di-expand (accordion row) untuk melihat detail tambahan. Setiap baris mewakili satu pelatihan dengan informasi ringkas.

#### Data per Baris (Kolom Wajib)

| Kolom | Sumber Data | Tipe Visual |
|:---|:---|:---|
| **Program** | pelatihan.nama + pelatihan.batch | Teks + Badge batch |
| **Dinas** | dinas.singkatan | Badge kecil |
| **Status** | Kombinasi is_active + 	anggal_mulai vs 	anggal_selesai vs 
ow() | Dot warna + label |
| **Pendaftar** | confirmed_count / kuota | Progress bar + angka |
| **Progress Waktu** | 	anggal_mulai → 	anggal_selesai vs 
ow() | Progress bar % |
| **Pertemuan** | schedules_done_count / schedules_count | Angka X / Y |
| **Aksi** | — | Ikon 👁️ (detail) + ⚙️ (manage) |

#### Detail Warna Status
| Status | Kondisi | Warna |
|:---|:---|:---|
| 🟢 **Aktif** | is_active = true && sekarang dalam rentang tanggal | Hijau Emerald |
| 🟡 **Akan Datang** | is_active = true && sekarang sebelum 	anggal_mulai | Kuning Amber |
| 🔵 **Selesai** | is_active = true && sekarang setelah 	anggal_selesai | Biru Info |
| ⚪ **Nonaktif** | is_active = false | Abu-abu |

#### Expandable Detail (saat baris diklik)
Saat baris di-expand, tampilkan:
- **Distribusi Pendaftar per Status:** Stacked horizontal bar: Confirmed (hijau) | Approved (biru) | Pending (kuning) | Rejected (merah)
- **Sisa Kuota:** Angka sisa slot
- **Periode Pelatihan:** 	gl_mulai s/d 	gl_selesai
- **Sisa Hari:** Countdown atau "Berakhir X hari lalu"
- **Tombol Aksi Detail:** 👁️ Lihat Enrollment | 📋 Kelola Presensi | 📊 Progress

#### Filter & Search
- **Filter Status:** Semua / Aktif / Akan Datang / Selesai / Nonaktif (dropdown)
- **Filter Dinas:** Semua / per Dinas (dropdown)
- **Pencarian:** Input text untuk mencari berdasarkan nama pelatihan atau batch
- **Sorting:** Default: is_active DESC, tanggal_mulai DESC

#### Data Controller
`php
// Di DashboardService atau DashboardController@admin
 = Pelatihan::with('dinas:id,nama,singkatan')
    ->withCount([
        'enrollments',
        'enrollments as pending_count' => fn() => ->where('status', EnrollmentStatus::Pending),
        'enrollments as approved_count' => fn() => ->where('status', EnrollmentStatus::Approved),
        'enrollments as confirmed_count' => fn() => ->where('status', EnrollmentStatus::Confirmed),
        'schedules',
        'schedules as schedules_done_count' => fn() => ->whereDate('tanggal', '<', now()),
    ])
    ->orderBy('is_active', 'desc')
    ->orderBy('tanggal_mulai', 'desc')
    ->get();
`

#### Empty State
Jika tidak ada pelatihan sama sekali: Tampilkan ilustrasi "Belum ada pelatihan. Buat pelatihan pertama Anda!" dengan tombol ➕ Buat Pelatihan Baru.

---

### 4.2 Fitur B: Penyederhanaan Label WA Gateway Status

#### Saat Ini (v2.0)
`
[🟢] Utama: Connected    [🔄]
[🟢] Cek Nomor: Connected [🔄]
`

#### Target (v3.0)
`
[🟢] Kirim: Connected   [🟢] Cek: Connected   [🔄]
`

#### Detail Perubahan
| Sebelum | Sesudah |
|:---|:---|
| Label: **Utama:** | Label: **Kirim:** (ikon 📤) |
| Label: **Cek Nomor:** | Label: **Cek:** (ikon 🔍) |
| Tombol refresh per baris (2 tombol) | **Satu tombol refresh** untuk kedua status |
| Badge terpisah dengan border | **Inline compact** — dot + label + status dalam satu baris |
| Loading: "Utama: Memeriksa..." / "Cek Nomor: Memeriksa..." | Loading: dot abu-abu berkedip + "..." |

#### Implementasi
- Di view dmin.blade.php dan whatsapp-gateway/index.blade.php
- Update JavaScript checkWaConnectionStatus() — sesuaikan innerHTML dengan label baru
- Satu tombol refresh memanggil checkWaConnectionStatus(false) untuk kedua status

---

### 4.3 Fitur C: Quick Actions Grid

Tombol shortcut di baris summary cards (sebagai card ke-5 atau baris terpisah).

| Tombol | Ikon | Route | Warna |
|:---|:---|:---|:---|
| **Buat Pelatihan Baru** | ➕ 	abler-plus | dmin.pelatihan.create | Indigo |
| **Presensi Hari Ini** | 📋 	abler-clipboard-check | panitia.operasional | Emerald |
| **Export Data** | 📤 	abler-file-export | dmin.export.index | Amber |
| **Laporan Bulanan** | 📊 	abler-report-analytics | dmin.laporan.index | Pink |

#### Bentuk UI
Grid 4 kolom (responsive: 2 kolom di tablet, 1 kolom di mobile) dengan card kecil berisi ikon + label + arrow hint.

---

### 4.4 Fitur D: Mini Chart Tren Pendaftaran 7 Hari

#### Data
`php
 = Enrollment::selectRaw("DATE(created_at) as date, COUNT(*) as total")
    ->whereDate('created_at', '>=', now()->subDays(7))
    ->groupBy('date')
    ->orderBy('date')
    ->get();
`

#### Bentuk UI
- **Sparkline / Mini Line Chart** dengan Chart.js (ringan, ~50KB) atau inline SVG
- Warna gradient dari indigo → pink (sesuai tema)
- Label sumbu Y (jumlah) dan sumbu X (tanggal) minimalis
- Tooltip saat hover: "12 Juni: 8 Pendaftar"
- **Persentase change:** Tampilkan panah naik/turun + persen dibanding hari sebelumnya
- Ditempatkan di samping Registration Funnel atau di atasnya

#### Empty State
Jika tidak ada data 7 hari: Tampilkan "Belum ada data pendaftaran 7 hari terakhir."

---

### 4.5 Fitur E: Top Instruktur Aktif (Nice-to-Have)

#### Data
Menggunakan data dari tabel users (role=instruktur) yang direlasikan ke schedules atau pelatihan. Untuk versi awal, dapat menggunakan data instruktur yang memiliki pelatihan aktif.

#### Bentuk UI
Leaderboard mini (5 besar) di samping widget Peserta Terdaftar Baru atau di bawahnya:
`
🏆 Instruktur Paling Aktif
1. Budi Santoso — 4 Pelatihan 🟢
2. Candra Wijaya — 3 Pelatihan 🟢
3. Dewi Sartika — 2 Pelatihan 🟡
`

---

## Struktur Visual Dashboard v3.0 (Layout)

`
┌─────────────────────────────────────────────────────────────────────────────┐
│ 🌟 Welcome Card + Quick Actions Grid (NEW)                                  │
│ [➕ Buat Pelatihan] [📋 Presensi] [📤 Export] [📊 Laporan]                   │
├──────────┬──────────┬──────────┬──────────┬─────────────────────────────────┤
│Card 1:   │Card 2:   │Card 3:   │Card 4:   │ (existing — 4 summary cards)    │
│Pengguna  │WA Gate   │Presensi  │Status    │ + WA label disingkat            │
│          │Kirim🟢 • │Hari Ini  │Program   │                                 │
│          │Cek🟢     │72%       │12 Aktif  │                                 │
├──────────┴──────────┴──────────┴──────────┴─────────────────────────────────┤
│ 📈 Tren Pendaftaran 7 Hari (NEW) + Registration Funnel (existing)            │
├─────────────────────────────────────────────────────────────────────────────┤
│ 📚 Daftar Pelatihan (NEW) — Filter: [Semua Status] [Semua Dinas] [🔍 Cari]  │
│ ┌─────────┬──────┬────────┬────────┬──────────┬────────┬─────────┬────────┐ │
│ │ Program │Batch │ Dinas  │ Status │Pendaftar │Waktu   │Pertemuan│ Aksi   │ │
│ ├─────────┼──────┼────────┼────────┼──────────┼────────┼─────────┼────────┤ │
│ │ Web Dev │ B.03 │ Diknas │ 🟢 Aktf│ ████░75% │ ██░░40%│ 3/8     │ 👁️⚙️  │ │
│ │ ⤵ Detail: Confirmed 45 | Approved 12 | Pending 8 | Sisa Kuota: 15     │ │
│ │ Mobile  │ B.01 │ Kominfo│ ⚪ Non │ ██░░20% │ -      │ -       │ 👁️⚙️  │ │
│ └─────────┴──────┴────────┴────────┴──────────┴────────┴─────────┴────────┘ │
├────────────────────────────┬────────────────────────────────────────────────┤
│ 🎥 Live Class Monitoring  │ 🗺️ Leaflet Map (Sebaran)                         │
│ (existing)                │ (existing)                                      │
├────────────────────────────┴────────────────────────────────────────────────┤
│ 👥 Peserta Terdaftar Baru │ 🏆 Top Instruktur (NEW)                         │
├────────────────────────────┴────────────────────────────────────────────────┤
│ 📋 Log & Audit System (tabbed — existing)                                   │
└─────────────────────────────────────────────────────────────────────────────┘
`

---

## Logika Backend & Query Database Baru

### DashboardService

Disarankan membuat **pp/Services/DashboardService.php** untuk memisahkan logika query berat dari controller, sehingga lebih testable, reusable, dan mudah di-maintain.

### Query 1: Daftar Pelatihan dengan Progress
`php
public function getPelatihanList()
{
    return Pelatihan::with('dinas:id,nama,singkatan')
        ->withCount([
            'enrollments',
            'enrollments as pending_count' => fn() => ->where('status', EnrollmentStatus::Pending),
            'enrollments as approved_count' => fn() => ->where('status', EnrollmentStatus::Approved),
            'enrollments as confirmed_count' => fn() => ->where('status', EnrollmentStatus::Confirmed),
            'schedules',
            'schedules as schedules_done_count' => fn() => ->whereDate('tanggal', '<', now()),
        ])
        ->orderBy('is_active', 'desc')
        ->orderBy('tanggal_mulai', 'desc')
        ->get()
        ->map(function () {
            // Progress pendaftar
            ->progress_pendaftar = ->kuota > 0 
                ? round((->confirmed_count / ->kuota) * 100) 
                : 0;
            
            // Progress waktu
             = ->tanggal_mulai->diffInDays(->tanggal_selesai) ?: 1;
             = ->tanggal_mulai->diffInDays(now());
            ->progress_waktu = max(0, min(100, round(( / ) * 100)));
            
            // Status otomatis
            if (!->is_active) {
                ->status_label = 'Nonaktif';
                ->status_color = 'secondary';
            } elseif (->tanggal_selesai && now()->gt(->tanggal_selesai)) {
                ->status_label = 'Selesai';
                ->status_color = 'info';
            } elseif (->tanggal_mulai && now()->lt(->tanggal_mulai)) {
                ->status_label = 'Akan Datang';
                ->status_color = 'warning';
            } else {
                ->status_label = 'Aktif';
                ->status_color = 'success';
            }
            
            return ;
        });
}
`

### Query 2: Tren Pendaftaran 7 Hari
`php
public function getRegistrationTrend(int  = 7): Collection
{
    return Enrollment::selectRaw("DATE(created_at) as date, COUNT(*) as total")
        ->whereDate('created_at', '>=', now()->subDays())
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');
}
`

### Query 3: Top Instruktur
`php
public function getTopInstruktur(int  = 5): Collection
{
    // Mengambil instruktur yang memiliki jadwal paling banyak di pelatihan aktif
    return User::where('role', 'instruktur')
        ->withCount(['schedules as total_sessions' => function() {
            ->whereHas('pelatihan', fn() => ->where('is_active', true));
        }])
        ->orderBy('total_sessions', 'desc')
        ->take()
        ->get();
}
`

---

## Kriteria Penerimaan (Acceptance Criteria - AC)

### AC-01: Daftar Pelatihan
- **AC-01.1:** Dashboard menampilkan daftar pelatihan dalam bentuk table/list dengan kolom: Program (nama + batch), Dinas, Status, Pendaftar (progress bar), Progress Waktu, Pertemuan, dan Aksi.
- **AC-01.2:** Status pelatihan dikategorikan menjadi 4 warna: Aktif (hijau), Akan Datang (kuning), Selesai (biru), Nonaktif (abu-abu).
- **AC-01.3:** Baris dapat di-expand (click to expand) untuk menampilkan detail: distribusi enrollment per status (stacked bar) dan informasi tambahan (sisa kuota, periode, sisa hari).
- **AC-01.4:** Terdapat filter dropdown untuk Status (Semua/Aktif/Akan Datang/Selesai/Nonaktif) dan Dinas (Semua/per Dinas), serta input pencarian teks.
- **AC-01.5:** Jika tidak ada pelatihan, tampilkan empty state dengan ilustrasi dan tombol "Buat Pelatihan Baru".
- **AC-01.6:**  yang sudah ada di controller harus dimanfaatkan/diganti dengan query baru ini.

### AC-02: Label WA Gateway Dipersingkat
- **AC-02.1:** Label "Utama:" diganti menjadi "Kirim:" atau "📤 Kirim".
- **AC-02.2:** Label "Cek Nomor:" diganti menjadi "Cek:" atau "🔍 Cek".
- **AC-02.3:** Satu tombol refresh cukup untuk kedua status (bukan masing-masing).
- **AC-02.4:** Tampilan compact: dot hijau/merah + label + status dalam satu baris inline.
- **AC-02.5:** Perubahan diterapkan di halaman dashboard admin (dmin.blade.php) dan halaman WhatsApp Gateway settings (whatsapp-gateway/index.blade.php).

### AC-03: Quick Actions Grid
- **AC-03.1:** Terdapat 4 tombol shortcut: Buat Pelatihan Baru, Presensi Hari Ini, Export Data, Laporan Bulanan.
- **AC-03.2:** Setiap tombol memiliki ikon yang jelas dan route yang benar.
- **AC-03.3:** Grid responsif (4 kolom desktop, 2 kolom tablet, 1 kolom mobile).

### AC-04: Tren Pendaftaran 7 Hari
- **AC-04.1:** Mini chart/sparkline menampilkan jumlah pendaftar per hari selama 7 hari terakhir.
- **AC-04.2:** Menggunakan Chart.js atau inline SVG — tidak menambah beban loading berat.
- **AC-04.3:** Tampilkan persentase perubahan (naik/turun) dibanding hari sebelumnya.

### AC-05: Top Instruktur Aktif
- **AC-05.1:** Menampilkan 5 instruktur paling aktif berdasarkan jumlah sesi di pelatihan aktif.
- **AC-05.2:** Tampilkan nama instruktur dan jumlah sesi/pelatihan yang diampu.

### AC-06: Non-Fungsional
- **AC-06.1:** Semua query baru tidak boleh menimbulkan N+1 query — wajib menggunakan eager loading dan withCount.
- **AC-06.2:** Halaman dashboard harus dimuat dalam waktu < 2 detik pada koneksi standar.
- **AC-06.3:** Logic query dipisahkan ke DashboardService agar controller tetap ramping.

---

## Rencana Pembagian Tugas Tim (Task Breakdown)

### Fase 1: Core (Prioritas Tinggi)

| Task | Specialist | Estimasi |
|:---|:---|:---|
| 1. Buat DashboardService dengan method getPelatihanList(), getRegistrationTrend(), getTopInstruktur() | **Bayu** | 3 jam |
| 2. Update DashboardController@admin untuk panggil service + passing data ke view | **Bayu** | 1 jam |
| 3. Build view List Pelatihan (table premium + expandable row) | **Ayu** | 3 jam |
| 4. Implementasi filter & search (Alpine.js atau vanilla JS) | **Ayu** | 2 jam |
| 5. Shorten label WA Gateway di dmin.blade.php dan whatsapp-gateway/index.blade.php | **Ayu** | 30 menit |
| 6. Build Quick Actions Grid | **Ayu** | 1 jam |
| 7. Build Mini Chart Tren Pendaftaran (Chart.js) | **Ayu** | 2 jam |
| 8. Build Top Instruktur list | **Ayu** | 1 jam |
| 9. Update layout dashboard v3.0 (posisi baru) | **Ayu** | 1 jam |
| 10. Testing: validasi semua AC, cek performa query, pastikan tidak ada error | **Farhan** | 3 jam |

### Fase 2: Polish (Prioritas Sedang)

| Task | Specialist | Estimasi |
|:---|:---|:---|
| 11. Animasi expand row, transisi filter | **Ayu** | 1 jam |
| 12. Tooltip/hover info pada progress bar | **Ayu** | 30 menit |
| 13. Dark mode consistency check | **Ayu** | 30 menit |
| 14. Regression test untuk semua fitur dashboard | **Farhan** | 2 jam |

### Total Estimasi: ~21 jam

---

## Mockup Layout (Text-Based Wireframe)

`
┌─────────────────────────────────────────────────────────────────────────────┐
│  🌟 Selamat datang, Admin! 👋                                              │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐                       │
│  │➕ Buat   │ │📋 Presensi│ │📤 Export │ │📊 Laporan│                       │
│  │Pelatihan │ │Hari Ini  │ │Data      │ │Bulanan   │                       │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘                       │
├──────────┬──────────┬──────────┬──────────┬────────────────────────────────┤
│Total     │WA Gate   │Presensi  │Status    │                               │
│Pengguna  │Kirim 🟢 • │Hari Ini  │Program   │                               │
│1,234     │Cek 🟢    │72%       │12 Aktif  │                               │
├──────────┴──────────┴──────────┴──────────┴────────────────────────────────┤
│ 📈 Tren Pendaftaran [▁▃▅▇▆▄█] +23% ░░░░░░░░░░░                          │
│ [ ████████████████████ Registration Funnel ████████████████████████████ ] │
├─────────────────────────────────────────────────────────────────────────────┤
│ 📚 Daftar Pelatihan  [Semua Status ▼] [Semua Dinas ▼] [🔍 Cari... ]       │
│ ┌──────────────────┬──────┬────────┬────────┬────────┬──────┬────────────┐ │
│ │ Program          │ Batch│ Dinas  │Status  │Pendaft │Waktu │ Aksi       │ │
│ ├──────────────────┼──────┼────────┼────────┼────────┼──────┼────────────┤ │
│ │ ▶ Pelatihan Web  │ B.03 │ DIKNAS │ 🟢 Aktf│ ████░  │ ██░░ │ [👁️] [⚙️] │ │
│ │   Dev Laravel    │      │        │        │ 75%    │ 40%  │            │ │
│ ├──────────────────┼──────┼────────┼────────┼────────┼──────┼────────────┤ │
│ │ ▶ Mobile Dev     │ B.01 │ KOMINFO│ ⚪ Non │ ██░░   │  -   │ [👁️] [⚙️] │ │
│ │   Kotlin         │      │        │        │ 20%    │      │            │ │
│ ├──────────────────┼──────┼────────┼────────┼────────┼──────┼────────────┤ │
│ │ ▶ ...            │ ...  │ ...    │ ...    │ ...    │ ...  │ ...        │ │
│ └──────────────────┴──────┴────────┴────────┴────────┴──────┴────────────┘ │
├───────────────────────────┬────────────────────────────────────────────────┤
│ 🎥 Live Class Monitoring │ 🗺️ Sebaran Pendaftar per Kecamatan              │
│ (existing)               │ (existing)                                      │
├───────────────────────────┴────────────────────────────────────────────────┤
│ 👥 Peserta Baru           │ 🏆 Top Instruktur                              │
│ • Ahmad Fauzi (2 jam llu) │ 1. Budi Santoso — 4 Pelatihan                 │
│ • Siti Nurhaliza (5 jam)  │ 2. Candra Wijaya — 3 Pelatihan                │
│ • ... (4 peserta)        │ 3. ...                                        │
├───────────────────────────┴────────────────────────────────────────────────┤
│ 📋 Log & Audit System  [Log Aktivitas Umum] [Audit Presensi]              │
│ (existing)                                                                │
└─────────────────────────────────────────────────────────────────────────────┘
`

---

## Catatan Implementasi

### Prioritas Pengerjaan
1. **✅ Shorten WA labels** — paling cepat (30 menit), efek langsung terlihat
2. **✅ List Pelatihan** — fitur utama yang diminta user
3. **✅ Quick Actions Grid** — polish + usability
4. **✅ Tren Pendaftaran** — visualisasi data, tergantung Chart.js
5. **✅ Top Instruktur** — nice-to-have, tergantung query

### Teknis
- Gunakan data yang **sudah ada** di $latestPelatihan atau ganti dengan query baru yang lebih komprehensif
- DashboardService untuk memisahkan logika berat dari controller
- Alpine.js untuk interaktivitas ringan (expand row, filter client-side)
- Chart.js untuk mini chart (atau inline SVG jika ingin tanpa library)

---

## Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|:---|:---|:---|
| Query getPelatihanList() berat jika banyak pelatihan | Dashboard lambat | Tambahkan pagination (10 per halaman) atau cache 5 menit |
| Chart.js menambah load time | Loading lambat | Lazy load Chart.js, fallback ke tabel angka jika gagal load |
| Expandable row kompleks diimplementasi | Bug interaksi | Gunakan Alpine.js yang sudah terbukti stabil |
| WA Gateway polling bentrok dengan fitur baru | Konflik JS | Gunakan namespace fungsi yang berbeda, test regresi |

---

## Referensi Terkait
- docs/PRD-Admin-Dashboard-Redesign.md — PRD v2.0 yang sudah diimplementasikan
- esources/views/content/dashboard/admin.blade.php — View dashboard saat ini
- pp/Http/Controllers/DashboardController.php — Controller dashboard saat ini
- esources/views/content/admin/whatsapp-gateway/index.blade.php — Halaman WA Gateway settings
- .planning/REMINDER.md — Daftar fitur lain yang menunggu eksekusi

---

*Dokumen ini disusun oleh Sophia (PM) berdasarkan hasil konsultasi dengan tim pengembangan (Dika, Bayu, Ayu) dan kebutuhan user. Siap untuk direview dan ditindaklanjuti.*
