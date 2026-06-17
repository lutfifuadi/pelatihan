# Security Audit Report — Aplikasi Pelatihan

**Tanggal Audit:** 14 Juni 2026  
**Auditor:** Hendra (Application Security Specialist)  
**Framework:** Laravel 12  
**PHP Version:** ^8.2  
**Stack:** Livewire 3, Jetstream 5, Sanctum, MySQL/MariaDB  

---

## Ringkasan Eksekutif

| Tingkat Keparahan | Jumlah Temuan | Status |
|---|---|---|
| **CRITICAL** | 1 | 🔴 Perlu Perbaikan Segera |
| **HIGH** | 2 | 🟠 Perlu Perbaikan |
| **MEDIUM** | 4 | 🟡 Perlu Diperhatikan |
| **LOW** | 6 | 🔵 Rekomendasi |
| **Total** | 13 | |

---

## OWASP Top 10 Checklist

- [x] **A01: Broken Access Control** — Middleware role: ✅ Berfungsi dengan baik. Admin routes sudah diproteksi `role:admin`. Double-check di beberapa controller masih perlu diperkuat.
- [x] **A02: Cryptographic Failures** — ⚠️ Password hardcoded untuk registrasi publik. SESSION_ENCRYPT=false.
- [x] **A03: Injection** — ✅ Semua query menggunakan Eloquent ORM / parameter binding. Tidak ada raw SQL concatenation.
- [x] **A04: Insecure Design** — ⚠️ Tidak ada rate limiting pada endpoint publik (registrasi, cek NIK, cek WA).
- [x] **A05: Security Misconfiguration** — ⚠️ Security headers belum diterapkan. Debug mode patut dicek di production.
- [x] **A06: Vulnerable and Outdated Components** — ⚠️ Beberapa depedency JavaScript memiliki versi yang perlu diperhatikan.
- [x] **A07: Identification and Authentication Failures** — 🔴 Hardcoded password untuk seluruh user registrasi publik.
- [x] **A08: Software and Data Integrity Failures** — ✅ Tidak ditemukan celah terkait integrity.
- [x] **A09: Security Logging and Monitoring** — ✅ Activity logging sudah baik dengan ActivityLogger.
- [x] **A10: Server-Side Request Forgery** — ✅ Tidak ditemukan celah SSRF.

---

## 🔴 TEMUAN #01: Hardcoded Password untuk Semua Pendaftar (CRITICAL)

**CVSS v3.1:** 9.8 (CRITICAL) — CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:U/C:H/I:H/A:H

### Lokasi
`app/Http/Controllers/Landing/RegistrationController.php:45`

### Detail
```php
'password' => Hash::make('pelatihanku2026'),
```

Setiap user yang mendaftar melalui halaman `/daftar` mendapatkan **password yang sama persis** (`pelatihanku2026`). Ini berarti:
1. Setiap peserta bisa login menggunakan NIK orang lain dengan password yang sama.
2. Password bersifat statis dan tidak pernah berubah.
3. Tidak ada mekanisme untuk memaksa user mengganti password setelah login pertama.

### Remediation
- Generate random password yang unik untuk setiap user.
- Kirim password ke WhatsApp/email user.
- Atau gunakan flow "buat password sendiri" di form registrasi.

### Perbaikan Diterapkan ✅
Mengganti hardcoded password dengan random string 12 karakter, dikirim via event ke user.

---

## 🟠 TEMUAN #02: File Upload Tanpa Validasi (HIGH)

**CVSS v3.1:** 7.5 (HIGH) — CVSS:3.1/AV:N/AC:L/PR:L/UI:N/S:U/C:H/I:H/A:N

### Lokasi
`app/Http/Controllers/Peserta/PesertaFormController.php:266-272`

### Detail
```php
if ($request->hasFile('foto_profil')) {
    $fotoProfil = $request->file('foto_profil')->store('uploads/peserta', 'public');
}
if ($request->hasFile('scan_ktp')) {
    $scanKtp = $request->file('scan_ktp')->store('uploads/peserta', 'public');
}
```

Tidak ada validasi:
- Tipe file (MIME type)
- Ekstensi file
- Ukuran file maksimum
- Double extension attack (misal: `shell.php.jpg`)

### Remediation
- Tambahkan validasi `File::types(['jpg', 'png', 'jpeg', 'pdf'])->max(2 * 1024)`.
- Validasi MIME type asli file.
- Cek double extension.

---

## 🟠 TEMUAN #03: Data PII Ditulis ke File Plaintext (HIGH)

**CVSS v3.1:** 6.5 (MEDIUM) — CVSS:3.1/AV:L/AC:L/PR:N/UI:N/S:U/C:H/I:N/A:N

### Lokasi
`app/Http/Controllers/Peserta/PesertaFormController.php:113` dan `:351`

### Detail
Data sensitif pengguna (NIK, nama lengkap, alamat, nomor WA, email) ditulis ke file:
```php
file_put_contents(base_path('.planing/data-user.txt'), $text, FILE_APPEND | LOCK_EX);
```

File ini tidak terproteksi, bisa diakses jika server dikompromisi, dan tidak ada enkripsi.

### Remediation
- Hentikan penulisan data PII ke file plaintext.
- Jika data diperlukan untuk export/logging, gunakan database dengan enkripsi.
- Hapus file `.planing/data-user.txt` yang sudah ada.

---

## 🟡 TEMUAN #04: Tidak Ada Rate Limiting pada Endpoint Publik (MEDIUM)

**CVSS v3.1:** 5.3 (MEDIUM) — CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:U/C:N/I:N/A:L

### Lokasi
- `POST /daftar` — `RegistrationController::register`
- `POST /daftar/cek-nik` — `RegistrationController::checkNik`
- `POST /daftar/cek-wa` — `RegistrationController::checkWa`

### Detail
Endpoint publik tidak memiliki proteksi rate limiting. Attacker bisa:
- Melakukan brute force NIK/email untuk mengetahui mana yang sudah terdaftar (enumeration).
- Mendaftarkan ribuan akun palsu secara otomatis (automated registration).

### Remediation
Tambahkan middleware `throttle` pada route:
```php
Route::post('/daftar', [RegistrationController::class, 'register'])
    ->middleware('throttle:5,60')
    ->name('landing.register');
```

---

## 🟡 TEMUAN #05: Sanctum Token Tidak Pernah Expired (MEDIUM)

**CVSS v3.1:** 5.3 (MEDIUM) — CVSS:3.1/AV:N/AC:L/PR:L/UI:N/S:U/C:L/I:L/A:N

### Lokasi
`config/sanctum.php:53`

### Detail
```php
'expiration' => null,
```

Token API tidak pernah kedaluwarsa. Jika token bocor, attacker bisa mengakses API selamanya.

### Remediation
Set expiration:
```php
'expiration' => 525600, // 1 tahun dalam menit, atau 1440 untuk 24 jam
```

---

## 🟡 TEMUAN #06: Session Encryption Tidak Diaktifkan (MEDIUM)

**CVSS v3.1:** 4.3 (MEDIUM)

### Lokasi
`.env.example:32` dan `config/session.php:50`

### Detail
```php
SESSION_ENCRYPT=false
```

Jika session disimpan di database atau file, data session bisa dibaca jika penyimpanan dikompromikan.

### Remediation
Set `SESSION_ENCRYPT=true` di production.

---

## 🟡 TEMUAN #07: Password Tidak Wajib diubah Setelah Login Pertama (MEDIUM)

### Lokasi
`app/Http/Controllers/Landing/RegistrationController.php:55`

### Detail
Setelah registrasi, user langsung di-auto login (`auth()->login($user)`). Tidak ada mekanisme untuk memaksa user mengganti password default.

Jika password hardcoded diperbaiki menjadi random, tetap harus ada mekanisme force password change di first login.

### Remediation
- Set flag `password_changed_at = null` saat create user.
- Buat middleware/event listener yang redirect ke halaman ganti password jika `password_changed_at` null.

---

## 🔵 TEMUAN #08: Security Headers Belum Diterapkan (LOW)

### Detail
Tidak ada HTTP security headers yang diimplementasikan. Ini penting untuk melindungi dari serangan berbasis browser.

### Rekomendasi Nginx Configuration
```nginx
add_header X-Frame-Options "DENY" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

# Content Security Policy
add_header Content-Security-Policy "
    default-src 'self';
    script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;
    style-src 'self' 'unsafe-inline' https:;
    img-src 'self' data: https:;
    font-src 'self' https: data:;
    connect-src 'self' https:;
    frame-ancestors 'none';
    object-src 'none';
    base-uri 'self';
" always;
```

---

## 🔵 TEMUAN #09: CORS Configuration Tidak Ada (LOW)

### Detail
Tidak ada file `config/cors.php`. Laravel 11/12 menggunakan pendekatan berbeda, perlu dipastikan CORS terkonfigurasi.

### Remediation
Pastikan di `bootstrap/app.php` atau via middleware, CORS hanya mengizinkan origin yang dikenal.

---

## 🔵 TEMUAN #10: APP_DEBUG di Environment (LOW)

### Lokasi
`.env.example:4`

### Detail
```php
APP_DEBUG=true
```

Harus dipastikan `APP_DEBUG=false` di production untuk mencegah informasi sensitif bocor via error pages.

---

## 🔵 TEMUAN #11: Dependency Usang (LOW)

### composer.json
- `laravel/framework` ^12.0 — OK (latest stable)
- `livewire/livewire` ^3.6.4 — OK
- `maatwebsite/excel` ^3.1 — OK
- `barryvdh/laravel-dompdf` ^3.1 — OK
- `laravel/jetstream` ^5.5 — OK

### package.json
Beberapa package cukup tua:
- `jquery` 3.7.1 — OK
- `bootstrap` 5.3.5 — OK
- `dropzone` 5.9.3 — Rilis terbaru adalah 6.x, perlu upgrade
- `chart.js` 4.4.9 — OK
- `moment` 2.30.1 — sudah deprecated, rekomendasi改用 dayjs
- `datatables.net-bs5` 2.1.8 — OK

### Perintah Audit
Jalankan di pipeline CI/CD:
```bash
composer audit
npm audit --audit-level=high
```

---

## 🔵 TEMUAN #12: Stored XSS Potensial di Blade (LOW)

### Lokasi
- `resources/views/terms.blade.php:29` — `{!! $terms !!}`
- `resources/views/policy.blade.php:29` — `{!! $policy !!}`

### Detail
Fitur `termsAndPrivacyPolicy()` Jetstream **tidak aktif** di konfigurasi, sehingga resiko rendah. Namun kode ini tetap ada dan bisa menjadi masalah jika diaktifkan tanpa sanitasi.

### Remediation
Jika fitur diaktifkan, pastikan konten $terms/$policy tidak mengandung user input tanpa escaping.

---

## 🔵 TEMUAN #13: Tidak Ada Logout dari Semua Device (LOW)

### Detail
Fitur "logout dari semua device" tidak tersedia. Jika password user dikompromikan, user tidak bisa menginvalidasi session yang sudah ada.

### Remediasi
Gunakan fitur `Laravel\Fortify\Actions\InvalidateUserSessions` atau implementasi manual.

---

## Daftar Perbaikan yang Diterapkan

| # | File | Perubahan |
|---|---|---|
| 1 | `app/Http/Controllers/Landing/RegistrationController.php` | Generate random password 12 karakter, bukan hardcoded |
| 2 | `docs/security-audit.md` | Dokumentasi audit keamanan |

---

## Rekomendasi untuk Production

### Segera (Pre-Launch)
1. ✅ Perbaiki hardcoded password — **SUDAH DIPERBAIKI**
2. 🔲 Tambahkan validasi file upload di `PesertaFormController`
3. 🔲 Hentikan penulisan data PII ke file plaintext `.planing/data-user.txt`
4. 🔲 Tambahkan rate limiting pada endpoint publik (`throttle:5,60`)
5. 🔲 Set `SESSION_ENCRYPT=true` dan `APP_DEBUG=false`
6. 🔲 Set `SANCTUM EXPIRATION` ke nilai tertentu (misal 1 tahun)

### Pra-Deployment
7. 🔲 Implementasi HTTP Security Headers di Nginx
8. 🔲 Konfigurasi CORS
9. 🔲 Jalankan `composer audit` dan `npm audit`
10. 🔲 Aktifkan force password change untuk user baru

### Monitoring
11. 🔲 Setup activity log monitoring (sudah ada ActivityLogger)
12. 🔲 Implementasi GitHub Actions Security Pipeline (SAST + Dependency Scan)
13. 🔲 Regular security scan dengan Semgrep atau tool SAST lainnya

---

*Audit dilakukan oleh Hendra (Application Security Specialist)*
*Framework: Laravel 12 | PHP ^8.2 | Database: MySQL/MariaDB*
