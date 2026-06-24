# Test Cases: Maintenance Mode Feature
**Project:** Pelatihanku  
**PRD:** PRD-Pelatihanku-MaintenanceMode-v1.0.md  
**Tester:** Farhan (QA)  
**Date:** 24 Juni 2026  

---

## A. Middleware Tests

### TC-001 (AC-002): Non-admin user redirected when maintenance ON
- **Precondition:** `maintenance_mode = 1`, guest user
- **Steps:** Access `/` or any public page
- **Expected:** Redirected to `/maintenance` (HTTP 302)
- **Status:** ✅ PASS

### TC-002 (AC-002): Non-admin user (peserta) redirected when maintenance ON
- **Precondition:** `maintenance_mode = 1`, logged-in as `peserta`
- **Steps:** Access `/pelatihan`
- **Expected:** Redirected to `/maintenance` (HTTP 302)
- **Status:** ✅ PASS

### TC-003 (AC-003): Normal access when maintenance OFF
- **Precondition:** `maintenance_mode = 0`, guest user
- **Steps:** Access `/` or any public page
- **Expected:** Page loads normally (HTTP 200), no redirect
- **Status:** ✅ PASS

### TC-004 (AC-010): Admin can access admin routes during maintenance
- **Precondition:** `maintenance_mode = 1`, logged-in as `admin`
- **Steps:** Access `/admin/settings/maintenance`, `/admin/dinas`, etc.
- **Expected:** Page loads normally (HTTP 200)
- **Status:** ✅ PASS

### TC-005 (AC-010): Admin can access non-admin routes during maintenance
- **Precondition:** `maintenance_mode = 1`, logged-in as `admin`
- **Steps:** Access `/` (homepage)
- **Expected:** Page loads normally (HTTP 200) — admin bypass via role check
- **Status:** ✅ PASS

### TC-006 (AC-011): Login page accessible during maintenance
- **Precondition:** `maintenance_mode = 1`, guest user
- **Steps:** Access `/login`
- **Expected:** Login page loads normally (HTTP 200)
- **Status:** ✅ PASS

### TC-007 (AC-011): Admin login page accessible during maintenance
- **Precondition:** `maintenance_mode = 1`, guest user
- **Steps:** Access `/admin/login`
- **Expected:** Admin login page loads normally (HTTP 200) — covered by `admin/*` bypass
- **Status:** ✅ PASS

### TC-008 (AC-016): No redirect loop accessing /maintenance
- **Precondition:** `maintenance_mode = 1`, guest user
- **Steps:** Access `/maintenance` directly
- **Expected:** Maintenance page loads (HTTP 200), not redirected
- **Status:** ✅ PASS

### TC-009 (AC-016): No redirect loop when maintenance OFF
- **Precondition:** `maintenance_mode = 0`, guest user
- **Steps:** Access `/maintenance`
- **Expected:** Redirected to `/` (MaintenanceController sees mode is OFF)
- **Status:** ✅ PASS

---

## B. Maintenance Page Tests (visual/code review)

### TC-010 (AC-004): Page is full-height (100vh), no scroll
- **Review:** `maintenance.blade.php` — `html, body { height: 100%; overflow: hidden; }`, `min-height: 100vh; min-height: 100dvh;`
- **Expected:** No scrollbars, full viewport height
- **Status:** ✅ PASS

### TC-011 (AC-005): Content perfectly centered
- **Review:** `.maintenance-wrapper { display: flex; align-items: center; justify-content: center; }`
- **Expected:** Flexbox centering both vertical and horizontal
- **Status:** ✅ PASS

### TC-012 (AC-006): Shows title from setting
- **Review:** `{{ $title ?? 'Sistem Sedang Dalam Pemeliharaan' }}`
- **Expected:** Dynamic title from `maintenance_title` setting
- **Status:** ✅ PASS

### TC-013 (AC-007): Shows message from setting
- **Review:** `{{ $message ?? '...' }}`
- **Expected:** Dynamic message from `maintenance_message` setting
- **Status:** ✅ PASS

### TC-014 (AC-008): Shows estimated time when set
- **Review:** `@if(!empty($estimatedTime))` block renders clock icon and time
- **Expected:** Estimated time section visible
- **Status:** ✅ PASS

### TC-015 (AC-009): Hides estimated time when empty
- **Review:** `@if(!empty($estimatedTime))` condition
- **Expected:** No estimated time section rendered
- **Status:** ✅ PASS

### TC-016 (AC-013): Dark theme design consistency
- **Review:** Background `#0b0f19`, dark card with blur effect, gradient accents
- **Expected:** Consistent with application dark theme
- **Status:** ✅ PASS

### TC-017 (AC-014): Responsive on mobile (320px+)
- **Review:** `@media (max-width: 480px)` media query, `clamp()` font sizes, `max-width: 520px` on card
- **Expected:** Adapts to small screens without overflow
- **Status:** ✅ PASS

### TC-018: Role="alert" for accessibility
- **Review:** `<div class="maintenance-card" role="alert" aria-live="polite">`
- **Expected:** Screen reader accessible
- **Status:** ✅ PASS

---

## C. Admin Settings Tests

### TC-019 (AC-001): Maintenance toggle works in admin settings
- **Precondition:** Logged-in as admin
- **Steps:** GET `/admin/settings/maintenance`
- **Expected:** Page renders with toggle, title, message, estimated time fields (HTTP 200)
- **Status:** ✅ PASS

### TC-020 (AC-001): Toggle ON saves and activates maintenance
- **Precondition:** Logged-in as admin, current `maintenance_mode = 0`
- **Steps:** POST to `/admin/settings/maintenance` with `maintenance_mode = 1`, valid title/message
- **Expected:** Redirect with success, DB has `maintenance_mode = 1`, cache cleared
- **Status:** ✅ PASS

### TC-021 (AC-003): Toggle OFF saves and deactivates maintenance
- **Precondition:** Logged-in as admin, current `maintenance_mode = 1`
- **Steps:** POST to `/admin/settings/maintenance` with `maintenance_mode = 0`, valid title/message
- **Expected:** Redirect with success, DB has `maintenance_mode = 0`, cache cleared
- **Status:** ✅ PASS

### TC-022: Form validation — title required
- **Precondition:** Logged-in as admin
- **Steps:** POST with `maintenance_title = ''`
- **Expected:** Validation error, `maintenance_title` is required
- **Status:** ✅ PASS

### TC-023: Form validation — message required
- **Precondition:** Logged-in as admin
- **Steps:** POST with `maintenance_message = ''`
- **Expected:** Validation error, `maintenance_message` is required
- **Status:** ✅ PASS

### TC-024: Estimated time is optional
- **Precondition:** Logged-in as admin
- **Steps:** POST with `maintenance_estimated_time = ''`
- **Expected:** Success, no validation error
- **Status:** ✅ PASS

### TC-025 (AC-012): Navbar badge appears when maintenance ON
- **Review:** `navbar-partial.blade.php` — checks cache, shows badge when `maintenanceActive === '1'`
- **Expected:** Yellow badge "Mode Maintenance Aktif" visible in admin navbar
- **Status:** ✅ PASS

### TC-026 (AC-012): Navbar badge disappears when maintenance OFF
- **Review:** Badge only rendered when `$maintenanceActive === '1'`
- **Expected:** No badge when maintenance OFF
- **Status:** ✅ PASS

### TC-027: Cache cleared on settings save
- **Review:** `Cache::forget('setting.maintenance_mode')` called in `updateMaintenance()`
- **Expected:** Next request reads fresh value from DB
- **Status:** ✅ PASS

---

## D. Regression Tests

### TC-028: Admin branding settings still work
- **Precondition:** Logged-in as admin
- **Steps:** GET `/admin/settings/branding`, POST update
- **Expected:** Page loads, update works
- **Status:** ✅ PASS

### TC-029: Admin landing settings still work
- **Precondition:** Logged-in as admin
- **Steps:** GET `/admin/settings/landing`, POST update
- **Expected:** Page loads, update works
- **Status:** ✅ PASS

### TC-030: Admin SEO settings still work
- **Precondition:** Logged-in as admin
- **Steps:** GET `/admin/settings/seo`, POST update
- **Expected:** Page loads, update works
- **Status:** ✅ PASS

### TC-031: Public pages work normally when maintenance OFF
- **Precondition:** `maintenance_mode = 0`, guest
- **Steps:** Access `/`, `/pelatihan`, `/daftar`
- **Expected:** All pages load normally
- **Status:** ✅ PASS (covered by existing tests)

### TC-032: Installer routes accessible during maintenance
- **Precondition:** `maintenance_mode = 1`
- **Steps:** Access `/install`
- **Expected:** Accessible (bypassed via `install*` pattern)
- **Status:** ✅ PASS

### TC-033: UP route (health check) accessible during maintenance
- **Precondition:** `maintenance_mode = 1`
- **Steps:** Access `/up`
- **Expected:** Accessible (bypassed)
- **Status:** ✅ PASS

---

## Summary

| Category | Total | Pass | Fail |
|----------|-------|------|------|
| A. Middleware Tests | 9 | 9 | 0 |
| B. Maintenance Page | 9 | 9 | 0 |
| C. Admin Settings | 9 | 9 | 0 |
| D. Regression Tests | 6 | 6 | 0 |
| **TOTAL** | **33** | **33** | **0** |

**Bugs Found:** 0  
**Overall Assessment:** ✅ READY FOR PRODUCTION
