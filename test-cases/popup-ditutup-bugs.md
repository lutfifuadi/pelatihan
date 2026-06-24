# Bug Report: Popup Pendaftaran Ditutup & Watermark DITUTUP
**Tester:** Farhan (QA)
**Date:** 24 Juni 2026

---

## Bug PD-B001 (CRITICAL): Undefined constant "show" in pelatihan-index.blade.php

### Description
When the `/pelatihan` page renders, PHP throws `Error: Undefined constant "show"` because `:open="show"` in the `<x-popup-ditutup>` component evaluates `show` as a PHP expression, but `show` is an Alpine.js JavaScript variable only available at runtime.

### Affected File
`resources/views/content/landing/pelatihan-index.blade.php`, line 1145

### Code
```blade
<x-popup-ditutup
    :open="show"         <-- BUG: "show" treated as PHP constant
    namaPelatihan=""
    batch=""
    tanggalDitutup=""
    onClose="close()"
/>
```

### Root Cause
In Blade, `:open="show"` is shorthand for `v-bind:open="show"` which evaluates `show` as a PHP expression. Since `show` is not defined in PHP scope (it's an Alpine.js `x-data` property), PHP throws an "Undefined constant" error.

Additionally, the `popup-ditutup.blade.php` component does NOT use the `$open` prop at all — the component always uses Alpine's `x-show="show"` which reads from its parent Alpine scope.

### Steps to Reproduce
1. Visit `/pelatihan` page (either as guest or logged in user)
2. The page will fail with HTTP 500 and throw `Undefined constant "show"`

### Expected Behavior
The page should load without errors. The popup component should work correctly using Alpine.js reactivity.

### Suggested Fix

**Option 1 (Recommended):** Remove the unused `:open` prop entirely since the component doesn't use it:

```blade
<x-popup-ditutup
    namaPelatihan=""
    batch=""
    tanggalDitutup=""
/>
```

**Option 2:** If the prop is needed for future use, pass it as a static string (not dynamic PHP):

```blade
<x-popup-ditutup
    open="false"
    namaPelatihan=""
    batch=""
    tanggalDitutup=""
/>
```

### Severity
**CRITICAL** — This breaks the entire `/pelatihan` page with HTTP 500 error.

### Evidence
Confirmed by PHPUnit test failure:
- `Tests\Feature\WhatsAppSupportTest::test_floating_icon_appears_on_pelatihan_index`
- Error: `Undefined constant "show"`
- Status: 500 received instead of expected 200

---

## Bug PD-B002 (LOW): Unused `$open` prop in popup-ditutup component

### Description
The `popup-ditutup.blade.php` component declares an `$open` prop with default `'false'`, but the component template never uses `$open`. All visibility is controlled via Alpine's `x-show="show"` which reads from the parent Alpine data.

### Affected File
`resources/views/components/popup-ditutup.blade.php`, line 2

### Code
```blade
@props([
    'open' => 'false',   // <-- Declared but never used in component
    'namaPelatihan' => '',
    ...
])
```

### Impact
Low — unused prop causes confusion but no functional issue. However, it indicates the component was designed with a different approach and may not work as expected if someone tries to pass `$open` as a prop.

### Suggested Fix
Remove the `'open' => 'false'` from the `@props` array if the component relies entirely on Alpine `x-show`.

---

## Summary

| Bug ID | Severity | Status | Found Via |
|--------|----------|--------|-----------|
| PD-B001 | CRITICAL | Open | PHPUnit test failure (WhatsAppSupportTest) |
| PD-B002 | LOW | Open | Code review |
