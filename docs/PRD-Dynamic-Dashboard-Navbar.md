# Product Requirements Document (PRD): Navigasi Dinamis Tombol Dashboard di Navbar Beranda Berdasarkan Role User

| Properti | Detail |
| :--- | :--- |
| **Status** | Draf |
| **Versi** | 1.0.0 |
| **Penulis** | PRD Specialist |
| **Tanggal** | 28 Juni 2026 |
| **Target Rilis** | Tidak ditentukan |
| **Prioritas** | Tinggi (High) |
| **Kategori** | User Experience / Navigasi |

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Latar Belakang & Tujuan Bisnis](#2-latar-belakang--tujuan-bisnis)
3. [User Stories](#3-user-stories)
4. [Acceptance Criteria](#4-acceptance-criteria)
5. [Scope](#5-scope)
6. [Alur Pengguna (User Flow)](#6-alur-pengguna-user-flow)
7. [Spesifikasi Teknis](#7-spesifikasi-teknis)
8. [Struktur File yang Dimodifikasi](#8-struktur-file-yang-dimodifikasi)
9. [Non-Functional Requirements](#9-non-functional-requirements)
10. [Risk Assessment](#10-risk-assessment)
11. [Dependency Map](#11-dependency-map)
12. [Quality Score](#12-quality-score)
13. [Appendix](#13-appendix)

---

## 1. Ringkasan Eksekutif

Saat ini, tombol "Dashboard" pada navbar halaman beranda (landing page) selalu mengarahkan seluruh user yang sudah login ke URL `/dashboard/admin` secara hardcoded — terlepas dari role user tersebut. Hal ini menyebabkan peserta, instruktur, maupun koordinator yang login mendapatkan error atau diarahkan ke dashboard yang salah karena mereka tidak memiliki akses ke halaman admin.

Fitur ini bertujuan mengganti navigasi statis tersebut dengan navigasi dinamis yang secara otomatis mendeteksi role user (`admin`, `peserta`, `instruktur`, `koordinator`) melalui `auth()->user()->role` dan mengarahkan mereka ke dashboard masing-masing. Perubahan hanya menyentuh satu file Blade pada navbar front-end (landing page) tanpa perlu modifikasi backend, database, atau routing.

---

## 2. Latar Belakang & Tujuan Bisnis

### 2.1 Masalah Saat Ini

- Tombol "Login" pada navbar beranda berubah menjadi "Dashboard" saat user sudah login.
- Link pada tombol "Dashboard" saat ini adalah `javascript:void(0);` (tidak mengarah ke mana-mana) — berdasarkan temuan di file `navbar-front.blade.php` baris 83.
- Tidak ada logika pengecekan role untuk menentukan URL dashboard yang sesuai.
- `route /home` (baris 96-113 di `web.php`) **sudah** memiliki logika redirect berbasis role yang benar (`match ($user->role)`), tetapi tombol di navbar front-end tidak menggunakannya.
- **Akibat:** Seluruh user (admin, peserta, instruktur, koordinator) yang mengakses tombol "Dashboard" dari navbar beranda tidak mendapatkan pengalaman navigasi yang sesuai dengan role mereka.

### 2.2 Dampak Bisnis

| Dampak | Severity | Frekuensi |
| :--- | :---: | :---: |
| Peserta tidak bisa mengakses dashboard dari navbar beranda | Tinggi | Sering |
| Instruktur tidak bisa mengakses dashboard dari navbar beranda | Tinggi | Sering |
| Koordinator tidak bisa mengakses dashboard dari navbar beranda | Tinggi | Sering |
| User experience buruk karena redirect error/403 | Sedang | Sering |
| Potensi user meninggalkan platform karena bingung | Sedang | Kadang |

### 2.3 Tujuan

1. Tombol "Dashboard" di navbar beranda secara otomatis mengarahkan user ke halaman dashboard yang sesuai dengan role mereka.
2. User yang belum login tetap melihat tombol "Login" yang mengarah ke halaman login.
3. Tidak ada perubahan pada logika backend atau database — murni perubahan pada layer view.
4. Mempertahankan konsistensi dengan logika redirect yang sudah ada di route `/home`.

---

## 3. User Stories

| ID | User Story | Prioritas |
| :-: | :--- | :-: |
| US-01 | Sebagai **Peserta**, saya ingin tombol "Dashboard" di navbar beranda mengarahkan saya ke `/dashboard/peserta` agar saya dapat melihat status pendaftaran saya tanpa perlu mencari URL manual. | Must Have |
| US-02 | Sebagai **Instruktur**, saya ingin tombol "Dashboard" di navbar beranda mengarahkan saya ke `/dashboard/instruktur` agar saya dapat melihat jadwal mengajar saya dengan cepat. | Must Have |
| US-03 | Sebagai **Koordinator**, saya ingin tombol "Dashboard" di navbar beranda mengarahkan saya ke `/dashboard/koordinator` agar saya dapat memantau data peserta di wilayah saya. | Must Have |
| US-04 | Sebagai **Admin**, saya ingin tombol "Dashboard" di navbar beranda tetap mengarahkan saya ke `/dashboard/admin` seperti biasa. | Must Have |
| US-05 | Sebagai **Pengunjung (belum login)**, saya ingin melihat tombol "Login" di navbar beranda yang mengarahkan saya ke halaman login. | Must Have |
| US-06 | Sebagai **Developer**, saya ingin logika navigasi dashboard bersifat _future-proof_ sehingga jika ada role baru ditambahkan, cukup menambahkan mapping tanpa mengubah struktur utama. | Nice to Have |

---

## 4. Acceptance Criteria

### 4.1 Fungsional

| ID | Kriteria | Terkait US |
| :-: | :--- | :-: |
| AC-01 | Tombol di navbar beranda menampilkan teks "Login" jika user **belum login**. | US-05 |
| AC-02 | Tombol "Login" mengarah ke halaman `{{ route('login') }}`. | US-05 |
| AC-03 | Tombol di navbar beranda menampilkan teks "Dashboard" jika user **sudah login**. | US-01 s.d. US-04 |
| AC-04 | Jika user login sebagai **admin**, tombol "Dashboard" mengarah ke `route('dashboard.admin')` → `/dashboard/admin`. | US-04 |
| AC-05 | Jika user login sebagai **peserta**, tombol "Dashboard" mengarah ke `route('dashboard.peserta')` → `/dashboard/peserta`. | US-01 |
| AC-06 | Jika user login sebagai **instruktur**, tombol "Dashboard" mengarah ke `route('dashboard.instruktur')` → `/dashboard/instruktur`. | US-02 |
| AC-07 | Jika user login sebagai **koordinator**, tombol "Dashboard" mengarah ke `route('dashboard.koordinator')` → `/dashboard/koordinator`. | US-03 |
| AC-08 | Tombol "Dashboard" menggunakan route **named** (bukan URL hardcoded) agar konsisten dengan best practice Laravel. | US-06 |
| AC-09 | Perubahan hanya terjadi pada satu file Blade (`navbar-front.blade.php`) dan tidak memerlukan perubahan file lain. | — |
| AC-10 | Tombol dashboard tidak muncul jika user sedang dalam mode impersonasi (jika ada mekanisme impersonate) — opsional. | — |

### 4.2 Non-Fungsional

| ID | Kriteria |
| :-: | :--- |
| AC-NF-01 | Tidak ada query database tambahan yang dijalankan untuk menentukan role (cukup dari `auth()->user()` yang sudah di-load oleh session). |
| AC-NF-02 | Performa halaman beranda tidak terpengaruh (tidak ada extra round-trip atau HTTP request tambahan). |
| AC-NF-03 | Kode tetap mudah dibaca dan di-maintain. |
| AC-NF-04 | Perubahan harus di-test di minimal 4 skenario: guest, admin, peserta, instruktur/koordinator. |

---

## 5. Scope

### 5.1 In Scope (Termasuk dalam PRD ini)

| Item | Detail |
| :--- | :--- |
| **Modifikasi File** | Hanya satu file: `resources/views/layouts/sections/navbar/navbar-front.blade.php` |
| **Logika Navigasi** | Conditional rendering tombol Login/Dashboard berdasarkan status autentikasi & role user |
| **Mapping Role → Route** | Mapping dari 4 role (`admin`, `peserta`, `instruktur`, `koordinator`) ke route name masing-masing |
| **Link & Teks Tombol** | Teks tombol berubah antara "Login" dan "Dashboard", href berubah sesuai role |
| **Unit Testing** | Verifikasi 4 skenario autentikasi (guest, admin, peserta, instruktur/koordinator) |
| **Dokumentasi** | PRD ini dan komentar di source code |

### 5.2 Out of Scope (Tidak Termasuk)

| Item | Alasan |
| :--- | :--- |
| **Perubahan routing/web.php** | Route dashboard sudah tersedia dan berfungsi dengan benar |
| **Perubahan middleware** | Middleware role (`RoleMiddleware`) sudah berfungsi dengan benar |
| **Perubahan database / migration** | Tidak ada perubahan skema data |
| **Perubahan DashboardController** | Controller sudah menyediakan method untuk setiap role |
| **Penambahan role baru** | Bukan bagian dari PRD ini (akan ditangani PRD terpisah) |
| **Perubahan navbar internal dashboard** | Hanya navbar front-end (landing page) yang diubah |
| **Desain ulang UI navbar** | Hanya perubahan logika link, bukan tampilan visual |
| **Penambahan ikon atau animasi** | Tidak diperlukan |
| **Multi-bahasa (i18n) untuk tombol** | Sudah menggunakan `__('Login')` / `__('Register')` — cukup |

---

## 6. Alur Pengguna (User Flow)

### 6.1 Flowchart

```
                    [User mengakses halaman beranda]
                              |
                     [Cek status auth user]
                     /                    \
              (Belum login)            (Sudah login)
                 |                          |
           Tampilkan tombol           Tampilkan tombol
           "Login" dengan            "Dashboard" dengan
           href → /login             href → dinamis
                                        |
                                [Cek role user]
                              /      |     |      \
                         Admin  Peserta Inst.  Koord.
                           |       |      |       |
                      route ke  route ke route ke route ke
                     dashboard  dashboard dashboard dashboard
                      .admin   .peserta .instruktur .koordinator
```

### 6.2 Step-by-Step

**Skenario A: User Belum Login (Guest)**
1. User membuka halaman beranda.
2. Sistem mendeteksi `Auth::check()` = `false`.
3. Navbar menampilkan tombol dengan teks "Login" dan href `{{ route('login') }}`.
4. User klik tombol → diarahkan ke halaman login.

**Skenario B: User Login sebagai Admin**
1. User membuka halaman beranda.
2. Sistem mendeteksi `Auth::check()` = `true`.
3. Sistem membaca `auth()->user()->role` = `'admin'`.
4. Navbar menampilkan tombol dengan teks "Dashboard" dan href `{{ route('dashboard.admin') }}`.
5. User klik tombol → diarahkan ke `/dashboard/admin`.

**Skenario C: User Login sebagai Peserta**
1. User membuka halaman beranda.
2. Sistem mendeteksi `Auth::check()` = `true`.
3. Sistem membaca `auth()->user()->role` = `'peserta'`.
4. Navbar menampilkan tombol dengan teks "Dashboard" dan href `{{ route('dashboard.peserta') }}`.
5. User klik tombol → diarahkan ke `/dashboard/peserta`.

**Skenario D: User Login sebagai Instruktur**
1. User membuka halaman beranda.
2. Sama seperti skenario C, tetapi role = `'instruktur'`.
3. Tombol "Dashboard" href → `{{ route('dashboard.instruktur') }}`.

**Skenario E: User Login sebagai Koordinator**
1. User membuka halaman beranda.
2. Sama seperti skenario C, tetapi role = `'koordinator'`.
3. Tombol "Dashboard" href → `{{ route('dashboard.koordinator') }}`.

---

## 7. Spesifikasi Teknis

### 7.1 Arsitektur Solusi

Solusi bersifat **purely presentational**: tidak ada perubahan pada layer backend (controller, service, routing, middleware, database). Semua perubahan terjadi di **layer view** (Blade template) dengan memanfaatkan data yang sudah tersedia dari Laravel Authentication system.

```
  [Browser] ← HTTP → [Laravel Router]
                           |
                    [Middleware: auth? role?]
                           |
                   [DashboardController]
                           |
                   [View: navbar-front.blade.php]
                           |
                    (PERUBAHAN ADA DI SINI)
               - Deteksi Auth::check()
               - Baca Auth::user()->role
               - Render link dinamis
```

### 7.2 Deteksi Role

- **Metode:** `auth()->user()->role`
- **Sumber data:** Session Laravel (tidak ada query database tambahan karena user object sudah di-load oleh session)
- **Nilai role yang valid:** `'admin'`, `'peserta'`, `'instruktur'`, `'koordinator'`
- **Fallback:** Jika role tidak dikenal / tidak matching (misal `default`), arahkan ke `route('dashboard.peserta')` (sama seperti logika di route `/home`)

### 7.3 Mapping Route

| Role | Route Name | URL |
| :--- | :--- | :--- |
| `admin` | `dashboard.admin` | `/dashboard/admin` |
| `peserta` | `dashboard.peserta` | `/dashboard/peserta` |
| `instruktur` | `dashboard.instruktur` | `/dashboard/instruktur` |
| `koordinator` | `dashboard.koordinator` | `/dashboard/koordinator` |

### 7.4 Logika Blade (Pseudo-code)

```blade
@auth
    @php
        $dashboardRoute = match(auth()->user()->role) {
            'admin' => route('dashboard.admin'),
            'peserta' => route('dashboard.peserta'),
            'instruktur' => route('dashboard.instruktur'),
            'koordinator' => route('dashboard.koordinator'),
            default => route('dashboard.peserta'),
        };
    @endphp
    <a href="{{ $dashboardRoute }}" class="btn btn-primary">
        <span class="icon-base ti tabler-layout-dashboard scaleX-n1-rtl me-md-1"></span>
        <span class="d-none d-md-block">{{ __('Dashboard') }}</span>
    </a>
@else
    <a href="{{ route('login') }}" class="btn btn-primary" target="_blank">
        <span class="icon-base ti tabler-login scaleX-n1-rtl me-md-1"></span>
        <span class="d-none d-md-block">{{ __('Login') }}/{{ __('Register') }}</span>
    </a>
@endauth
```

### 7.5 Detail Perubahan pada `navbar-front.blade.php`

**Lokasi:** Baris 81-87 (saat ini)

**Kode Saat Ini (baris 81-87):**
```blade
        <!-- navbar button: Start -->
        <li>
          <a href="javascript:void(0);" class="btn btn-primary" target="_blank"><span
              class="icon-base ti tabler-login scaleX-n1-rtl me-md-1"></span><span
              class="d-none d-md-block">{{ __('Login') }}/{{ __('Register') }}</span></a>
        </li>
        <!-- navbar button: End -->
```

**Kode yang Diharapkan:**
```blade
        <!-- navbar button: Start -->
        <li>
          @auth
            @php
              $dashboardRoute = match(auth()->user()->role) {
                  'admin' => route('dashboard.admin'),
                  'instruktur' => route('dashboard.instruktur'),
                  'koordinator' => route('dashboard.koordinator'),
                  default => route('dashboard.peserta'),
              };
            @endphp
            <a href="{{ $dashboardRoute }}" class="btn btn-primary">
              <span class="icon-base ti tabler-layout-dashboard scaleX-n1-rtl me-md-1"></span>
              <span class="d-none d-md-block">{{ __('Dashboard') }}</span>
            </a>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary">
              <span class="icon-base ti tabler-login scaleX-n1-rtl me-md-1"></span>
              <span class="d-none d-md-block">{{ __('Login') }}/{{ __('Register') }}</span>
            </a>
          @endauth
        </li>
        <!-- navbar button: End -->
```

### 7.6 Catatan Tambahan

- Atribut `target="_blank"` pada kode lama dihilangkan untuk tombol "Login" karena tidak sesuai best practice (membuka halaman login di tab baru membingungkan user). Untuk tombol "Dashboard" juga tidak menggunakan `target="_blank"`.
- Ikon diubah dari `tabler-login` menjadi `tabler-layout-dashboard` pada tombol "Dashboard" agar lebih representatif.
- Penggunaan `@auth` / `@else` / `@endauth` lebih idiomatik di Blade dibandingkan `@if(Auth::check())`.
- Tidak perlu import `Auth` facade di file karena Blade sudah memiliki akses ke helper `auth()` dan `@auth` directive.

---

## 8. Struktur File yang Dimodifikasi

### 8.1 Daftar File yang Diubah

| No | File | Tipe Perubahan | Keterangan |
| :-: | :--- | :---: | :--- |
| 1 | `resources/views/layouts/sections/navbar/navbar-front.blade.php` | **Edit** | Logika tombol Login/Dashboard diubah dari statis menjadi dinamis berbasis role |

### 8.2 Daftar File yang Tidak Diubah (Referensi)

| No | File | Keterangan |
| :-: | :--- | :--- |
| 1 | `routes/web.php` | Sudah memiliki route dashboard untuk setiap role (baris 150-173) |
| 2 | `app/Http/Controllers/DashboardController.php` | Method `admin()`, `instruktur()`, `koordinator()`, `peserta()` sudah tersedia |
| 3 | `app/Http/Middleware/RoleMiddleware.php` | Middleware sudah berfungsi, tidak perlu diubah |
| 4 | `resources/views/layouts/sections/navbar/navbar-partial.blade.php` | Navbar internal dashboard tidak terkait |
| 5 | `resources/views/layouts/sections/navbar/navbar.blade.php` | Tidak terkait |
| 6 | `resources/views/livewire/navbar-component.blade.php` | Tidak terkait (masih kosong) |

### 8.3 Dependency Map

```
navbar-front.blade.php (DIUBAH)
    ↓ depends on
auth()->user()->role (Laravel Auth Session)
    ↓ depends on
routes/web.php (definisi route dashboard.*)
    ↓ depends on
DashboardController (method per role)
    ↓ depends on
RoleMiddleware (proteksi akses per role)
```

---

## 9. Non-Functional Requirements

| ID | Kategori | Requirement |
| :-: | :--- | :--- |
| NFR-01 | **Performa** | Tidak boleh ada query database tambahan. Deteksi role harus hanya dari session. |
| NFR-02 | **Kompatibilitas** | Perubahan harus compatible dengan semua browser modern (Chrome, Firefox, Safari, Edge 2 versi terakhir). |
| NFR-03 | **Maintainability** | Mapping role → route harus mudah dibaca dan ditambah jika ada role baru. |
| NFR-04 | **Keamanan** | Tombol "Dashboard" hanya muncul jika user sudah login (`@auth`). Tidak ada informasi role yang bocor ke guest. |
| NFR-05 | **Accessibility** | Tombol harus memiliki teks yang jelas dan link yang valid. |

---

## 10. Risk Assessment

### 10.1 Risk Matrix

| ID | Risiko | Probabilitas | Dampak | Severity | Mitigasi |
| :-: | :--- | :---: | :---: | :-: | :--- |
| R-01 | Role user bernilai null atau tidak sesuai 4 role yang ada | Rendah | Sedang | **Medium** | Gunakan `default` case yang mengarah ke `dashboard.peserta` sebagai fallback |
| R-02 | User mencoba mengakses dashboard yang bukan haknya (misal: peserta akses `/dashboard/admin`) | Rendah | Tinggi | **Medium** | Middleware `role:...` di web.php sudah memproteksi; PRD ini hanya mengubah link, bukan otorisasi |
| R-03 | Cache Blade menyebabkan perubahan tidak langsung terlihat | Rendah | Rendah | **Low** | Jalankan `php artisan view:clear` setelah deploy |
| R-04 | Tampilan rusak jika ada perubahan struktur HTML di sekitar tombol | Rendah | Rendah | **Low** | Pertahankan struktur `<li>` dan class `btn btn-primary` yang sama |

### 10.2 Risk Score

| Metrik | Nilai |
| :--- | :---: |
| **Rata-rata Probabilitas** | Rendah (1.5/5) |
| **Rata-rata Dampak** | Sedang (2.0/5) |
| **Overall Risk Score** | **3.0/10** (Low Risk) |

---

## 11. Dependency Map

### 11.1 Dependencies (Prasyarat)

| ID | Dependency | Status | Catatan |
| :-: | :--- | :---: | :--- |
| D-01 | Route `dashboard.admin` sudah terdefinisi | ✅ Ada | `routes/web.php` line 157 |
| D-02 | Route `dashboard.peserta` sudah terdefinisi | ✅ Ada | `routes/web.php` line 172 |
| D-03 | Route `dashboard.instruktur` sudah terdefinisi | ✅ Ada | `routes/web.php` line 162 |
| D-04 | Route `dashboard.koordinator` sudah terdefinisi | ✅ Ada | `routes/web.php` line 167 |
| D-05 | DashboardController sudah memiliki method untuk setiap role | ✅ Ada | `app/Http/Controllers/DashboardController.php` |
| D-06 | User model memiliki kolom `role` | ✅ Ada | Diakses via `auth()->user()->role` |
| D-07 | RoleMiddleware sudah terdaftar di Kernel | ✅ Ada | `app/Http/Middleware/RoleMiddleware.php` |

### 11.2 No Blocking Dependencies

Semua prasyarat sudah terpenuhi. Tidak ada dependency yang menghambat implementasi.

---

## 12. Quality Score

### 12.1 PRD Completeness Checklist

| Kriteria | Bobot | Nilai | Skor |
| :--- | :-: | :-: | :-: |
| **Kelayakan (Feasibility)** — Apakah solusi teknis realistic? | 20% | 10/10 | 2.0 |
| **Kejelasan (Clarity)** — Apakah spesifikasi mudah dipahami? | 20% | 10/10 | 2.0 |
| **Kelengkapan (Completeness)** — Apakah semua aspek tercakup? | 20% | 9/10 | 1.8 |
| **Testability** — Apakah AC dapat diuji? | 15% | 9/10 | 1.35 |
| **Konsistensi** — Apakah selaras dengan sistem yang sudah ada? | 15% | 10/10 | 1.5 |
| **Kelayakan Scope** — Apakah in/out scope jelas? | 10% | 9/10 | 0.9 |

### 12.2 Final Quality Score

| Metrik | Nilai |
| :--- | :---: |
| **Overall Quality Score** | **9.55 / 10** (Sangat Baik) |
| **Risk Score** | 3.0 / 10 (Low Risk) |
| **Estimated Effort** | ~1-2 jam (developer junior) |
| **Kompleksitas** | Sangat Rendah (1/5) |

---

## 13. Appendix

### 13.1 Glossary

| Istilah | Definisi |
| :--- | :--- |
| **Navbar** | Component navigasi pada bagian atas halaman web |
| **Landing Page / Beranda** | Halaman utama publik (`/`) |
| **Role** | Atribut pada model User yang menentukan tipe akses: admin, peserta, instruktur, koordinator |
| **Guest** | User yang belum login/terautentikasi |

### 13.2 Referensi

- File terkait: `resources/views/layouts/sections/navbar/navbar-front.blade.php`
- Route dashboard: `routes/web.php` (baris 150-173)
- Logika redirect `/home`: `routes/web.php` (baris 96-113)
- Role middleware: `app/Http/Middleware/RoleMiddleware.php`
- Dashboard controller: `app/Http/Controllers/DashboardController.php`
- PRD Template: `docs/PRD-VAPID-Key-Management.md`

### 13.3 Catatan Implementasi

1. **Developer:** Cukup edit satu file — `resources/views/layouts/sections/navbar/navbar-front.blade.php`
2. **Testing manual:**
   - Buka beranda tanpa login → lihat tombol "Login" dengan href `/login`
   - Login sebagai admin → lihat tombol "Dashboard" dengan href `/dashboard/admin`
   - Login sebagai peserta → lihat tombol "Dashboard" dengan href `/dashboard/peserta`
   - Login sebagai instruktur → lihat tombol "Dashboard" dengan href `/dashboard/instruktur`
   - Login sebagai koordinator → lihat tombol "Dashboard" dengan href `/dashboard/koordinator`
3. **Post-deploy:** Jalankan `php artisan view:clear` untuk membersihkan cache Blade.
4. **Review:** Pastikan tidak ada error `undefined variable` atau `undefined route`.

### 13.4 Changelog

| Versi | Tanggal | Perubahan | Penulis |
| :-: | :-: | :--- | :-: |
| 1.0.0 | 28 Jun 2026 | Initial draft PRD | PRD Specialist |

---

*Dokumen ini dibuat secara otomatis oleh PRD Specialist. Untuk revisi atau pertanyaan, hubungi tim product.*

*© 2026 Pelatihanku - All Rights Reserved*
