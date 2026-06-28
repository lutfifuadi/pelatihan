# BUG REPORT: BUG-001

## Title
[BUG] Regresi — Test `test_branding_settings_still_work` gagal karena field `minat_mobile_view_mode` required

## Severity
**Major** — Merusak regression test yang sudah ada; potensi error saat update branding settings dari kode lain/integrasi.

## Environment
- **File:** `app/Http/Controllers/Admin/SettingController.php`
- **Test:** `tests/Feature/MaintenanceModeTest.php::test_branding_settings_still_work`
- **Branch:** current

## Description
Penambahan field `minat_mobile_view_mode` di controller `SettingController@updateBranding` dengan validasi `required|in:horizontal,grid` menyebabkan **semua request POST ke `/admin/settings/branding` tanpa field ini akan gagal validasi**.

Ini memutus kompatibilitas dengan:
1. Regression test existing (`test_branding_settings_still_work`)
2. Kode lain / API client yang memanggil endpoint ini tanpa field baru
3. Potensi error jika admin menyimpan via bookmarklet / automation script lama

## Steps to Reproduce
1. Jalankan: `php artisan test --filter=test_branding_settings_still_work`
2. Test gagal dengan error: `"The minat mobile view mode field is required."`

## Expected Result
Test `test_branding_settings_still_work` harus PASS tanpa perubahan.

## Actual Result
```
FAILED  Tests\Feature\MaintenanceModeTest > branding settings still work
Session has unexpected errors: {
    "default": [
        "The minat mobile view mode field is required."
    ]
}
```

## Root Cause
Validasi di `SettingController@updateBranding` baris 44:
```php
'minat_mobile_view_mode' => 'required|in:horizontal,grid',
```

Namun test di `MaintenanceModeTest` line 378-385 tidak mengirim field tersebut:
```php
$response = $this->post('/admin/settings/branding', [
    'brand_name' => 'Test Brand',
    'brand_logo_size' => 'md',
    'institution_name' => 'Test Institution',
    'validate_whatsapp' => '1',
    'broadcast_enabled' => '1',
    'timezone' => 'Asia/Jakarta',
    // minat_mobile_view_mode TIDAK ADA
]);
```

## Recommendation
**Opsi A (Rekomendasi):** Jadikan field `minat_mobile_view_mode` sebagai `sometimes` atau beri default value `'horizontal'` di controller agar backward compatible:
```php
'minat_mobile_view_mode' => 'sometimes|required|in:horizontal,grid',
```
Dan di bagian save:
```php
'value' => $request->input($key, $key === 'minat_mobile_view_mode' ? 'horizontal' : ''),
```

**Opsi B:** Update test `test_branding_settings_still_work` untuk menyertakan field baru.

**Opsi C (Paling aman):** Kombinasi Opsi A + B.

## Reported By
Farhan — QA / Bug Hunter
Date: 2026-06-27
