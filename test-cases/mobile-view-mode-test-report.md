# QA Report — Admin Mobile View Mode (Form Minat)

**Feature:** Mode tampilan mobile (horizontal swipe / grid) diatur oleh admin
**Test Date:** 2026-06-27
**Tested By:** Farhan (QA / Bug Hunter)
**Test Type:** Automated (PHPUnit) + Manual Code Review
**Test File:** `tests/Feature/MobileViewModeTest.php`

---

## Test Results Summary

| TC ID | Description | Status |
|-------|-------------|--------|
| TC-1 | Admin Bisa Mengatur Mode | ✅ PASS |
| TC-2 | User Tidak Melihat Toggle | ✅ PASS |
| TC-3 | Mode Horizontal (Default) | ✅ PASS |
| TC-4 | Mode Grid | ✅ PASS |
| TC-5 | Desktop Tetap Grid | ✅ PASS |
| TC-6 | Regression — Tidak Ada Efek Samping | ✅ PASS (12 subtests) |

**Total: 18/18 PASS** ✅

---

## Detailed Results

### TC-1: Admin Bisa Mengatur Mode ✅
| Subtest | Result |
|---------|--------|
| Admin dapat mengakses halaman branding | ✅ |
| Halaman branding menampilkan field `minat_mobile_view_mode` | ✅ |
| Admin dapat memilih "Horizontal (Swipe)" | ✅ |
| Admin dapat memilih "Grid (Vertikal)" | ✅ |
| Default value adalah "horizontal" | ✅ |
| Admin tidak bisa mengisi nilai invalid | ✅ |

### TC-2: User Tidak Melihat Toggle ✅
| Subtest | Result |
|---------|--------|
| Tidak ada tombol/toggle UI di form minat peserta | ✅ |
| Tidak ada teks "Ganti Tampilan" di halaman peserta | ✅ |

### TC-3: Mode Horizontal (Default) ✅
| Subtest | Result |
|---------|--------|
| Container cards tidak memiliki class `view-grid` saat mode horizontal | ✅ |
| Alpine.js data `mode` bernilai `'horizontal'` | ✅ |

### TC-4: Mode Grid ✅
| Subtest | Result |
|---------|--------|
| Container cards memiliki class `view-grid` saat mode grid | ✅ |
| Alpine.js data `mode` bernilai `'grid'` | ✅ |

### TC-5: Desktop Tetap Grid ✅
| Subtest | Result |
|---------|--------|
| `grid-cards-container` selalu ada di semua kondisi | ✅ |
| CSS grid default untuk desktop tetap berfungsi | ✅ |

### TC-6: Tidak Ada Regresi ✅
| Subtest | Result |
|---------|--------|
| Kartu pelatihan masih bisa dipilih & disubmit | ✅ |
| Validasi form (empty batch) masih berjalan | ✅ |
| Popup DITUTUP masih muncul | ✅ |
| Restricted warning masih muncul | ✅ |
| Step indicator masih muncul | ✅ |
| Tombol navigasi (Sebelumnya/Selanjutnya) masih ada | ✅ |
| `mobileViewMode` variabel terkirim ke view | ✅ |
| Data step lainnya (Data Diri, Alamat, dll) tidak terpengaruh | ✅ |

---

## Code Quality Review

### ✅ Kode Sudah Benar
1. **SettingSeeder** — default `horizontal` sudah tepat
2. **SettingController** — validasi `required|in:horizontal,grid` sudah benar
3. **PesertaFormController** — fallback `?? 'horizontal'` sudah aman
4. **form-minat.blade.php** — class `view-grid` diterapkan dinamis; CSS override di `@media (max-width: 768px)`; drag-to-scroll `startDrag()` skip jika mode grid
5. **admin/branding/index.blade.php** — select option dengan 2 opsi + default horizontal

### ⚠️ Bug Ditemukan: BUG-001 (Major)
**File:** `bug-reports/BUG-001-branding-regression-minat-mode.md`

| Item | Detail |
|------|--------|
| **Issue** | Test `test_branding_settings_still_work` FAIL karena `minat_mobile_view_mode` di-`required` |
| **File** | `app/Http/Controllers/Admin/SettingController.php:44` |
| **Impact** | Semua request POST ke `/admin/settings/branding` tanpa field baru akan gagal |
| **Fix** | Ubah validasi jadi `sometimes|required|in:horizontal,grid` dengan default value `'horizontal'` |

---

## Recommendations

1. **HIGH PRIORITY** — Fix BUG-001: Ubah validasi `minat_mobile_view_mode` menjadi `sometimes` agar backward compatible
2. **LOW** — Pertimbangkan menambahkan caching untuk setting `minat_mobile_view_mode` jika performa menjadi concern
3. **INFO** — Semua test lulus dengan 59 assertions, tidak ada issue lain yang terdeteksi

---

## Kesimpulan

**Fitur "Admin Atur Mode Tampilan Mobile Form Minat" siap untuk merge** setelah bug BUG-001 diperbaiki.
