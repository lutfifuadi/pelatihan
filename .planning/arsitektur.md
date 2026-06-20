# 🏗️ Arsitektur Sistem — Aplikasi Pelatihan

> Update: 12 Juni 2026 — Berdasarkan kondisi aktual

---

## 📐 Arsitektur Umum

```
┌─────────────────────────────────────────────────────────┐
│                    🖥️ Client                            │
│   Browser (Desktop / Mobile / PWA)                      │
└────────────────────────┬────────────────────────────────┘
                         │ HTTP/HTTPS
                         ▼
┌─────────────────────────────────────────────────────────┐
│                    🌐 Web Server                         │
│              Nginx / Apache (via Docker)                 │
└────────────────────────┬────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────┐
│              ⚙️ Laravel 12 Application                   │
│                                                         │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────┐      │
│  │  Routes  │  │ Middleware│  │  Controllers      │      │
│  │ (web.php)│  │ (auth,   │  │  (Admin, Auth,    │      │
│  │ 206 baris│  │  role,   │  │   Landing, Peserta│      │
│  │          │  │  locale) │  │   pages, dll)     │      │
│  └──────────┘  └──────────┘  └────────┬──────────┘      │
│                                       │                  │
│  ┌────────────────────────────────────┴──────────────┐   │
│  │              Service Layer                         │   │
│  │  (WhatsAppService, Helpers)                        │   │
│  └────────────────────────────────────┬──────────────┘   │
│                                       │                  │
│  ┌────────────────────────────────────┴──────────────┐   │
│  │              Models / Eloquent ORM                │   │
│  │  (8 Models: User, Dinas, Faq, Kecamatan,         │   │
│  │   Kelurahan, Pelatihan, PesertaProfile, Setting)  │   │
│  └────────────────────────────────────┬──────────────┘   │
└───────────────────────────────────────┬──────────────────┘
                                        │
                                        ▼
┌─────────────────────────────────────────────────────────┐
│                    🗄️ Database                           │
│              MariaDB (production) via remote server      │
│              (103.197.191.226)                           │
└─────────────────────────────────────────────────────────┘
```

---

## 🧱 Layer Aplikasi (Kondisi Aktual)

### 1. **Route Layer** (`routes/web.php`) ✅
- 206 baris routing lengkap
- Route publik: landing page, registrasi koordinator, locale
- Route auth (Jetstream): login, register, dashboard per role
- Route admin: CRUD kecamatan, kelurahan, pelatihan, dinas, peserta, koordinator, faq, settings, whatsapp-gateway
- Route peserta: multi-step form (5 tahap)
- Route API internal: dependent dropdown kelurahan

### 2. **Middleware Layer** (`app/Http/Middleware/`) ✅
- `RoleMiddleware` — akses berdasarkan role (admin, instruktur, koordinator, peserta)
- `LocaleMiddleware` — multi bahasa (template)
- `CheckUserActive` — cek status aktif user
- `auth:sanctum` — autentikasi Sanctum/Jetstream
- `verified` — email verifikasi

### 3. **Controller Layer** (`app/Http/Controllers/`) ✅
```
Controllers/
├── Admin/               → DinasController, FaqController, KecamatanController,
│                          KelurahanController, KoordinatorController,
│                          PelatihanController, PesertaController,
│                          SettingController, WhatsAppGatewayController
│   └── Auth/            → AdminLoginController
├── Auth/                → LoginController, LogoutController, RegisterController
├── authentications/     → LoginBasic, RegisterBasic (legacy redirect)
├── Landing/             → RegistrationController
├── language/            → LanguageController
├── pages/               → HomePage, MiscError, Page2
├── Peserta/             → PesertaFormController
├── Controller.php       → Base controller
├── DashboardController  → Dashboard per role
└── KoordinatorRegisterController
```

### 4. **Service Layer** (`app/Services/`)
- `WhatsAppService` — integrasi WhatsApp Gateway API
- `Helpers.php` — fungsi bantu global

### 5. **Model Layer** (`app/Models/`) ✅ — 8 Models:
- `User.php` — dengan role, relasi ke kecamatan, kelurahan, peserta_profiles
- `Dinas.php` — dinas terkait pelatihan
- `Faq.php` — frequently asked questions
- `Kecamatan.php` — master kecamatan (Kota Bandung)
- `Kelurahan.php` — master kelurahan per kecamatan
- `Pelatihan.php` — data pelatihan, relasi ke dinas & kecamatan
- `PesertaProfile.php` — profil lengkap peserta (5 tahap form)
- `Setting.php` — key-value settings (branding, lock wilayah)

### 6. **View Layer** (`resources/views/`) ✅
```
views/
├── layouts/             → layoutMaster, blankLayout, horizontalLayout,
│                          contentNavbarLayout, layoutFront, commonMaster
│   └── sections/        → footer, menu, navbar, scripts, styles
├── admin/               → auth/login
├── auth/                → login, register, forgot-password, reset-password,
│                          verify-email, two-factor-challenge, confirm-password
├── content/
│   ├── admin/           → branding, dinas, faqs, kecamatan, kelurahan,
│   │                      koordinator, pelatihan, peserta, whatsapp-gateway
│   ├── authentications/ → login-basic, register-basic
│   ├── dashboard/       → admin (716 baris), instruktur, koordinator,
│   │                      peserta (764 baris)
│   │   └── peserta/     → form-pendaftaran (917 baris, multi-step Alpine.js),
│   │                      form-pendidikan, form-minat, form-dokumen
│   ├── koordinator/     → register, sukses
│   ├── landing/         → beranda (1282+ baris), konfirmasi
│   ├── pages/           → pages-home, pages-misc-error, pages-page2
│   └── peserta/         → form-lanjutan (1068 baris, 5-step multi-form)
├── components/          → 29 blade components (Jetstream)
├── profile/             → show, update-profile, update-password, dll
├── api/                 → API token management
└── emails/              → team-invitation
```

### 7. **Asset Layer** (`resources/assets/`)
- CSS: Bootstrap 5.3.5 custom (Sass)
- JS: Alpine.js, 111+ JS files (Vuexy template)
- Vite 6 untuk build & bundling

---

## 📦 Teknologi Detail (Kondisi Aktual)

| Kategori | Terpasang | Keterangan |
|----------|-----------|------------|
| **Framework** | ✅ Laravel 12.x | `laravel/framework: ^12.0` |
| **PHP Version** | ✅ 8.2+ | |
| **Database** | ✅ MariaDB (remote) | `103.197.191.226` |
| **Template** | ✅ Vuexy v3 (Bootstrap 5) | `pixinvent/vuexy-laravel-bootstrap-jetstream` |
| **Auth** | ✅ Laravel Jetstream 5.5 (Livewire) + Fortify | Login/register/2FA/passkeys |
| **Frontend JS** | ✅ Alpine.js + Livewire 3.6.4 | Interaktivitas ringan |
| **API Auth** | ✅ Laravel Sanctum 4.0 | API tokens |
| **Build Tool** | ✅ Vite 6 | `vite: ^6.3.5` |
| **CSS Framework** | ✅ Bootstrap 5.3.5 | |
| **Charts** | ✅ ApexCharts 4.2.0 + Chart.js 4.4.9 | |
| **Calendar** | ✅ FullCalendar 6.x | Library terinstall, fitur belum |
| **Form Validation** | ✅ @form-validation/* | Validasi client-side |
| **Rich Text** | ✅ Quill 2.0.3 | |
| **File Upload** | ✅ Dropzone 5.9.3 | |
| **Notifications** | ✅ SweetAlert2, Notyf, Notiflix | |
| **Maps** | ✅ Leaflet 1.9.4 | |
| **DataTables** | ✅ Datatables.net | |
| **Date Picker** | ✅ Flatpickr | |
| **Select Box** | ✅ Select2, Tagify | |
| **Step Wizard** | ✅ bs-stepper | |
| **Animation** | ✅ AOS, Animate.css, Swiper | |
| **PDF Generate** | ❌ Belum | Rencana: DomPDF/Snappy |
| **PWA** | ❌ Belum | Rencana: Service Worker |
| **Queue** | ✅ Database driver | Untuk task async |
| **Cache** | ✅ Database driver | |
| **Session** | ✅ Database driver | |
| **Deployment** | ✅ Docker (Laravel Sail) | PHP 8.2 + MySQL 8.0 |

---

## 🔐 Keamanan (Kondisi Aktual)
- ✅ RoleMiddleware — akses berbasis role (admin, instruktur, koordinator, peserta)
- ✅ CSRF Protection — built-in Laravel
- ✅ XSS Prevention — Blade escaping
- ✅ SQL Injection — Eloquent ORM
- ✅ Auth:sanctum — autentikasi token
- ✅ Two-factor authentication
- ✅ Email verification
- ✅ CheckUserActive — status akun
- ⬜ Security audit menyeluruh — **BELUM**

## ⚡ Performa (Kondisi Aktual)
- ⬜ Eager loading — perlu review
- ⬜ Caching query — perlu implementasi
- ⬜ Pagination — sudah menggunakan Laravel pagination
- ⬜ Image optimization — perlu setup
- ⬜ Lazy loading — perlu review
- ⬜ Queue untuk task berat — driver database sudah siap

---

## 📁 Catatan Penting
- **.env mengandung kredensial live** (DB remote + WA API key) — jangan di-commit ke Git
- **Belum ada Git repository** — perlu `git init`
- **25 migration** siap dijalankan
- **8 seeder** siap digunakan
- **Belum ada automated tests** (PHPUnit)
