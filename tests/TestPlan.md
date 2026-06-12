# Test Plan — Aplikasi Pelatihan

## 1. Scope Testing

| Modul | Kategori | Prioritas |
|-------|----------|-----------|
| Installer (Step 1-3, Progress, Process) | Instalasi Sistem | CRITICAL |
| Authentication (Login, Register, Role-based) | Auth & Keamanan | CRITICAL |
| Landing Page (Home, Daftar, Detail Pelatihan) | Public Pages | CRITICAL |
| Admin Dashboard & CRUD (Dinas, Kecamatan, Kelurahan, Pelatihan, Peserta, Koordinator, FAQ) | Admin Panel | CRITICAL |
| Role-based Dashboard (Admin, Peserta, Instruktur, Koordinator) | Access Control | HIGH |
| Multi-step Pendaftaran Peserta (Tab 1-5) | Peserta Flow | HIGH |
| API Endpoints (Kelurahan dropdown) | API | MEDIUM |
| SEO Routes (Sitemap, Robots.txt) | SEO | MEDIUM |
| Database Seeder (Integritas data) | Data | HIGH |
| Manual Testing (Flow End-to-End) | E2E | MEDIUM |

## 2. Prioritas

| Prioritas | Deskripsi |
|-----------|-----------|
| **CRITICAL** | Core functionality — harus berfungsi penuh sebelum rilis |
| **HIGH** | Fitur penting — error bisa menghambat pengguna |
| **MEDIUM** | Pendukung — perlu di-test tapi tidak blocking |
| **LOW** | Enhancement / minor |

## 3. Test Approach

### Automated (PHPUnit)
- Database: SQLite in-memory dengan `RefreshDatabase`
- Factory/Seeder untuk data awal
- Assertions: HTTP status, redirect, view data, database records, JSON structure
- Perintah: `php artisan test --env=testing`

### Manual
- Test case terstruktur per flow (Instalasi, Registrasi, Admin CRUD, Multi-step Form)
- Screenshot/record untuk dokumentasi temuan
- Verifikasi lintas browser (Chrome, Firefox, Edge)

## 4. Test Strategy Matrix

| Test File | Modul | #Test Methods | Approach |
|-----------|-------|---------------|----------|
| InstallerTest.php | Install Flow | 4 | Automated |
| AuthTest.php | Auth | 5 | Automated |
| LandingTest.php | Landing Page | 5 | Automated |
| AdminTest.php | Admin CRUD | 7+ | Automated |
| DashboardTest.php | Role Dashboards | 4 | Automated |
| PesertaFormTest.php | Multi-step Form | 5 | Automated |
| ApiTest.php | SEO & API | 3 | Automated |
| SeederTest.php | Database Seeders | 4 | Automated |
| ManualTestCases.md | E2E Flows | 5 scenarios | Manual |

## 5. Environment

- PHP 8.1+
- SQLite (testing) / MySQL (production)
- Laravel 11
- Node.js 18+ (for Vite/asset compilation)
