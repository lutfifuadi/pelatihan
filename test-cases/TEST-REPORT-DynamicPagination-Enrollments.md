# Test Report: Dynamic Pagination Selector — /admin/enrollments

| **Tester** | Farhan (Tester / QA & Bug Hunter) |
|------------|-----------------------------------|
| **Tanggal** | 29 Juni 2026 |
| **Fitur** | Dropdown "Tampilkan X data per halaman" (10, 20, 30, 50, Semua) |
| **Lingkungan** | Laravel 11, PHP 8.2, Windows, Chrome (via HTTP test) |
| **Server** | http://127.0.0.1:8000 |

---

## Ringkasan Hasil

| AC ID | Deskripsi | Status | Catatan |
|-------|-----------|--------|---------|
| **AC-01** | Dropdown muncul dengan opsi 10, 20, 30, 50, Semua | ✅ **PASS** | Dropdown `<select id="per-page-selector">` ditemukan di HTML dengan semua opsi. Default: 20 |
| **AC-02** | Ganti nilai dropdown → update tabel tanpa page reload (AJAX) | ✅ **PASS** | AJAX request `X-Requested-With: XMLHttpRequest` mengembalikan data JSON, bukan redirect |
| **AC-03** | Jumlah data sesuai dengan yang dipilih | ✅ **PASS** | `per_page=10` → 10 baris, `per_page=30` → 30 baris, `per_page=50` → 50 baris |
| **AC-04** | Pilihan "Semua" (All) menampilkan seluruh data | ❌ **FAIL** | **CRITICAL BUG** — error 500 saat memilih "Semua" |
| **AC-05** | Pagination links menyesuaikan jumlah per halaman | ✅ **PASS** | Pagination muncul saat data > per_page, hilang saat data muat di 1 halaman |
| **AC-06** | Filter (search, status, pelatihan) tetap berfungsi setelah ganti per_page | ✅ **PASS** | Kombinasi filter + per_page berhasil, response mengandung `counts` yang benar |
| **AC-07** | Tema glassmorphism konsisten | ✅ **PASS** | Class `glass-card-premium` digunakan pada wrapper tabel dan dropdown |
| **AC-08** | Tidak ada error di console browser | ❌ **FAIL** | Error 500 saat memilih "Semua" akan muncul di console |

**Kesimpulan: 6 PASS, 2 FAIL (1 Critical, 1 Minor terkait)**

---

## Detail Pengujian per AC

### AC-01: Dropdown Muncul
**Metode:** HTTP GET `/admin/enrollments` → parsing HTML
**Hasil:**
- Selector `#per-page-selector` ditemukan
- Opsi: `<option value="10">10</option>`, `<option value="20" selected>20</option>`, `<option value="30">30</option>`, `<option value="50">50</option>`, `<option value="all">Semua</option>`
- Label "Tampilkan" dan "data per halaman" muncul
- **Status: ✅ PASS**

### AC-02: AJAX tanpa Page Reload
**Metode:** Request dengan header `X-Requested-With: XMLHttpRequest`
**Hasil:**
- Response berupa JSON: `{ "rows": "...", "pagination": "...", "counts": {...}, "per_page": "..." }`
- Bukan redirect/HTML penuh
- **Status: ✅ PASS**

### AC-03: Jumlah Data Sesuai Per Page
**Metode:** AJAX request dengan `per_page=10`, `per_page=30`, `per_page=50`
**Hasil:**
| per_page | Rows Diharapkan | Rows Aktual | Status |
|----------|----------------|-------------|--------|
| 10 | ≤ 10 | 10 | ✅ |
| 20 | ≤ 20 | 20 | ✅ |
| 30 | ≤ 30 | 30 | ✅ |
| 50 | ≤ 50 | 50 | ✅ |
- **Status: ✅ PASS**

### AC-04: Pilihan "Semua" (All)
**Metode:** AJAX request dengan `per_page=all`
**Hasil:**
- **HTTP 500 Internal Server Error**
- Error message: `Method Illuminate\Database\Eloquent\Collection::firstItem does not exist`
- File: `resources/views/content/admin/enrollments/_table_rows.blade.php`
- **Status: ❌ FAIL — CRITICAL BUG**

### AC-05: Pagination Menyesuaikan
**Metode:** Verifikasi properti `pagination` pada response JSON
**Hasil:**
- `per_page=10`: Pagination: True (karena data > 10)
- `per_page=50`: Pagination: True (karena data > 50)
- `search=test` (hanya 7 hasil): Pagination: False (semua muat 1 halaman)
- **Status: ✅ PASS**

### AC-06: Filter + Per_Page Kombinasi
**Metode:** Kombinasi filter status, search, pelatihan_id dengan berbagai per_page
**Hasil:**
| Test | HTTP | Rows | Pagination | Status |
|------|------|------|------------|--------|
| `status=pending&per_page=10` | 200 | 10 | True | ✅ |
| `status=pending&per_page=50` | 200 | 50 | True | ✅ |
| `search=test&per_page=20` | 200 | 7 | False | ✅ |
| `pelatihan_id=1&per_page=30` | 200 | 7 | False | ✅ |
- Response juga mengandung `counts` (statistik status) yang terupdate
- **Status: ✅ PASS**

### AC-07: Tema Glassmorphism
**Metode:** Verifikasi class CSS di HTML
**Hasil:**
- Class `glass-card-premium` ditemukan di wrapper tabel, filter cards, bulk action bar, dan export buttons
- Gaya gelap/glassy konsisten dengan komponen admin lain
- **Status: ✅ PASS**

### AC-08: Tidak Ada Error Console
**Metode:** Pengecekan error response
**Hasil:**
- Numerik (10, 20, 30, 50): ✅ Tidak ada error
- **"Semua" (all): ❌ Error 500** — akan muncul di console browser sebagai HTTP error
- **Status: ❌ FAIL — terkait bug AC-04**

---

## 🔴 BUG REPORT

### BUG-001: [CRITICAL] Pilihan "Semua" Menyebabkan Error 500

| Field | Value |
|-------|-------|
| **Title** | [CRITICAL] `per_page=all` menyebabkan error `Collection::firstItem does not exist` |
| **Severity** | **Critical** — Fitur "Semua" tidak berfungsi sama sekali |
| **Environment** | Semua browser, semua OS |
| **File** | `app/Http/Controllers/Admin/EnrollmentController.php` (line 61-62), `resources/views/content/admin/enrollments/_table_rows.blade.php` (line 4), `resources/views/content/admin/enrollments/index.blade.php` (line 341) |
| **Steps to Reproduce** | 1. Buka `/admin/enrollments` 2. Pilih "Semua" dari dropdown "Tampilkan" 3. Lihat error 500 |
| **Expected Result** | Semua data enrollment tampil dalam satu halaman tanpa pagination |
| **Actual Result** | HTTP 500 error: `Method Illuminate\Database\Eloquent\Collection::firstItem does not exist` |
| **Root Cause** | Saat `per_page=all`, controller menggunakan `->get()` yang mengembalikan `Collection`, bukan `LengthAwarePaginator`. View memanggil method `firstItem()` dan `hasPages()` yang hanya ada pada paginator. |

#### Detail Root Cause

**Controller (EnrollmentController.php:60-66):**
```php
$perPage = $request->input('per_page', 20);
if ($perPage === 'all') {
    $enrollments = $query->orderBy('created_at', 'desc')->get(); // ← Returns Collection
} else {
    $perPage = max(1, min(100, (int) $perPage));
    $enrollments = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString(); // ← Returns Paginator
}
```

**View (_table_rows.blade.php:4):** ← Error terjadi di sini
```php
<td>{{ $enrollments->firstItem() + $index }}</td>
// Collection tidak punya method firstItem()
```

**View (index.blade.php:341):** ← Juga akan error
```php
@if($enrollments->hasPages())
// Collection tidak punya method hasPages()
```

**Controller (AJAX response, line 107):** ← Juga error
```php
$pagination = $enrollments->hasPages() ? $enrollments->links()->render() : '';
// Collection tidak punya method hasPages()
```

#### Recommended Fix

Pada `_table_rows.blade.php`, ganti `$enrollments->firstItem()` dengan `$loop->first ? 1 : ($enrollments instanceof \Illuminate\Contracts\Pagination\Paginator ? $enrollments->firstItem() : 1)` atau gunakan counter manual `$index + 1`.

Pada `index.blade.php` dan controller, cek tipe data sebelum memanggil method paginator:
```php
$hasPagination = $enrollments instanceof \Illuminate\Contracts\Pagination\Paginator && $enrollments->hasPages();
```

Alternatif: Gunakan `paginate()` dengan jumlah besar (999999) sebagai pengganti `get()` untuk "Semua" agar tetap mendapatkan Paginator.

---

## Test Case Mapping

| Test Case ID | AC ID | Skenario | Tipe | Prioritas | Status |
|-------------|-------|----------|------|-----------|--------|
| TC-001 | AC-01 | Dropdown muncul dengan opsi lengkap | Manual | High | ✅ |
| TC-002 | AC-02 | Ganti per_page via dropdown → AJAX tanpa reload | Manual | High | ✅ |
| TC-003 | AC-03 | per_page=10 → 10 baris | Manual | High | ✅ |
| TC-004 | AC-03 | per_page=20 → 20 baris | Manual | High | ✅ |
| TC-005 | AC-03 | per_page=30 → 30 baris | Manual | High | ✅ |
| TC-006 | AC-03 | per_page=50 → 50 baris | Manual | High | ✅ |
| TC-007 | AC-04 | per_page=all → semua data | Manual | Critical | ❌ |
| TC-008 | AC-05 | Pagination muncul saat data > per_page | Manual | High | ✅ |
| TC-009 | AC-05 | Pagination hilang saat data ≤ per_page | Manual | High | ✅ |
| TC-010 | AC-06 | Filter status + ganti per_page | Manual | High | ✅ |
| TC-011 | AC-06 | Filter search + ganti per_page | Manual | High | ✅ |
| TC-012 | AC-06 | Filter pelatihan + ganti per_page | Manual | Medium | ✅ |
| TC-013 | AC-07 | Tema glassmorphism konsisten | Visual | Medium | ✅ |
| TC-014 | AC-08 | Console browser tanpa error | Manual | High | ❌ |

---

## Rekomendasi

1. **🚨 CRITICAL: Segera fix BUG-001** — Fitur "Semua" sama sekali tidak berfungsi dan menyebabkan error 500. Ini bloker untuk user yang ingin melihat seluruh data dalam satu halaman.

2. **Saran implementasi untuk fix:**
   - Pada view `_table_rows.blade.php`, gunakan `$loop->index + 1` sebagai nomor urut untuk Collection, atau conditional check
   - Pada view `index.blade.php` dan controller, conditional check `$enrollments instanceof LengthAwarePaginator` sebelum panggil `hasPages()`
   - Alternatif lebih sederhana: gunakan `paginate(999999)` untuk `per_page=all` (tidak perlu Collection)

3. **Regression test:** Setelah fix, jalankan ulang semua test case di atas.

4. **Tambahkan automated test** untuk endpoint `per_page=all` di PHPUnit Feature test agar bug serupa tidak terulang.

---

*Laporan ditulis oleh Farhan — Tester / QA & Bug Hunter*
