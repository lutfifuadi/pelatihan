# Test Plan: Popup Pendaftaran Ditutup & Watermark DITUTUP
**Version:** 1.0
**Tester:** Farhan (QA)
**Date:** 24 Juni 2026
**PRD Ref:** PRD-Pelatihanku-PopupPendaftaranDitutup-v1.0.md

---

## A. Backend Logic Tests

### TC-PD-001 | AC-001: isPendaftaranDitutup() returns true for past deadline
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan record exists with `batas_pendaftaran` set to yesterday |
| **Test Steps** | 1. Call `$pelatihan->isPendaftaranDitutup()` on a Pelatihan model where `batas_pendaftaran = now()->subDay()` |
| **Expected Result** | Returns `true` |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-002 | AC-002: isPendaftaranDitutup() returns false for null deadline
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan record exists with `batas_pendaftaran = null` |
| **Test Steps** | 1. Call `$pelatihan->isPendaftaranDitutup()` on a Pelatihan model where `batas_pendaftaran = null` |
| **Expected Result** | Returns `false` |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-003 | AC-001/BR-003: isPendaftaranDitutup() returns false for today's deadline
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan record exists with `batas_pendaftaran` set to today |
| **Test Steps** | 1. Call `$pelatihan->isPendaftaranDitutup()` where `batas_pendaftaran = now()->startOfDay()` |
| **Expected Result** | Returns `false` (still open until end of day) |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-004 | AC-015: isPendaftaranDitutup() returns false for future deadline
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan record exists with `batas_pendaftaran` set to tomorrow |
| **Test Steps** | 1. Call `$pelatihan->isPendaftaranDitutup()` where `batas_pendaftaran = now()->addDay()` |
| **Expected Result** | Returns `false` |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-005 | AC-010/AC-012: saveMinat() rejects closed pelatihan
| Field | Value |
|-------|-------|
| **Preconditions** | Authenticated user. Closed pelatihan exists (batas_pendaftaran yesterday). User has completed steps 1-3. |
| **Test Steps** | 1. POST to `dashboard.peserta.form-minat.store` with `batch_pelatihan` = closed pelatihan's batch |
| **Expected Result** | Redirect back with error flash message. `PesertaProfile.batch_pelatihan` NOT updated. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-006 | AC-013: submitFinal() rejects closed pelatihan
| Field | Value |
|-------|-------|
| **Preconditions** | Authenticated user. Closed pelatihan selected in session. All steps completed. |
| **Test Steps** | 1. POST to `dashboard.peserta.form-review.submit` with `konfirmasi = true` |
| **Expected Result** | Redirect back with error flash message. Enrollment NOT created. `is_completed` stays false. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-007 | AC-015: submitFinal() succeeds for open pelatihan
| Field | Value |
|-------|-------|
| **Preconditions** | Authenticated user. Open pelatihan (batas_pendaftaran null or future) selected. All steps completed. |
| **Test Steps** | 1. POST to `dashboard.peserta.form-review.submit` with `konfirmasi = true` |
| **Expected Result** | Redirect to success page. Enrollment created. `is_completed = true`. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-008 | AC-014: Error message is clear and informative
| Field | Value |
|-------|-------|
| **Preconditions** | Closed pelatihan exists |
| **Test Steps** | 1. Trigger TC-PD-005 or TC-PD-006. Observe the error message. |
| **Expected Result** | Error message includes: pelatihan name, closure date, instruction to pick another. E.g., "Pendaftaran untuk '[Nama]' sudah ditutup pada 24/06/2026." |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## B. Frontend Visual Tests — Public /pelatihan Page

### TC-PD-009 | AC-003: Watermark "DITUTUP" appears on closed cards
| Field | Value |
|-------|-------|
| **Preconditions** | At least one closed pelatihan and one open pelatihan exist in DB. `is_active = true` for both. |
| **Test Steps** | 1. Visit `/pelatihan` as guest. 2. Observe closed pelatihan card. |
| **Expected Result** | Closed card shows watermark overlay with red "DITUTUP" text rotated ~25deg |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-010 | AC-005: Badge shows "Pendaftaran Ditutup" (red)
| Field | Value |
|-------|-------|
| **Preconditions** | Same as TC-PD-009 |
| **Test Steps** | 1. Visit `/pelatihan`. 2. Check badge on closed card. |
| **Expected Result** | Badge text = "Pendaftaran Ditutup". Badge has `card-status-closed` class (red styling). |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-011 | AC-004: Button changes to "Ditutup" (disabled, red style)
| Field | Value |
|-------|-------|
| **Preconditions** | Same as TC-PD-009 |
| **Test Steps** | 1. Visit `/pelatihan`. 2. Check the footer button on closed card. |
| **Expected Result** | Button shows text "Ditutup" with a ban icon. Has `btn-ditutup-card` class (cursor: not-allowed, red border, red text). |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-012 | AC-003/BR-006: Quota bar hidden on closed cards
| Field | Value |
|-------|-------|
| **Preconditions** | Closed pelatihan exists with finite kuota. |
| **Test Steps** | 1. Visit `/pelatihan`. 2. Check if quota progress bar is visible on closed card. |
| **Expected Result** | No `.quota-bar` element rendered for closed cards (the `@if(!$isKuotaUnlimited && !$isDitutup)` condition hides it). |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-013 | AC-003: No watermark on open cards
| Field | Value |
|-------|-------|
| **Preconditions** | Open pelatihan exists (no batas_pendaftaran) |
| **Test Steps** | 1. Visit `/pelatihan`. 2. Check open pelatihan card. |
| **Expected Result** | No watermark overlay visible. Normal display with "Pendaftaran Dibuka" badge, active "Daftar" button, and quota bar. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## C. Popup Tests

### TC-PD-014 | AC-006: Clicking "Ditutup" button opens popup
| Field | Value |
|-------|-------|
| **Preconditions** | Closed pelatihan exists. Guest user on `/pelatihan`. |
| **Test Steps** | 1. Click the "Ditutup" button on closed pelatihan card. |
| **Expected Result** | Popup appears with fade+scale animation. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-015 | AC-007: Popup shows correct pelatihan name and closure date
| Field | Value |
|-------|-------|
| **Preconditions** | Popup open from TC-PD-014 |
| **Test Steps** | 1. Observe popup content. |
| **Expected Result** | Popup title: "Pendaftaran Ditutup". Message includes: nama pelatihan, batch number, closure date. Sub-message: "Silakan pilih pelatihan lain yang masih tersedia." |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-016 | AC-008: Popup closed via X button
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Click the X (close) button in top-right corner. |
| **Expected Result** | Popup closes with fade-out animation. Body scroll restored. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-017 | AC-008: Popup closed via backdrop click
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Click the dark backdrop area outside the popup card. |
| **Expected Result** | Popup closes. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-018 | AC-008: Popup closed via ESC key
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Press the Escape key on keyboard. |
| **Expected Result** | Popup closes. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-019 | AC-007: Popup has correct icon
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Observe the icon in the popup. |
| **Expected Result** | Icon shows `tabler-calendar-off` inside a red circle background. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-020 | AC-009: Popup has correct styling (glassmorphism, dark theme)
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Inspect popup styles. |
| **Expected Result** | Popup card: max-width 440px, background rgba(15,23,42,0.95), backdrop-filter blur(16px), border-radius 12px. Backdrop: rgba(0,0,0,0.6) with blur(8px). Text colors follow dark theme. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-021 | AC-006: Popup also appears from form-minat.blade.php
| Field | Value |
|-------|-------|
| **Preconditions** | Authenticated user. Closed pelatihan exists. User at form step 4 (minat). |
| **Test Steps** | 1. Click on a disabled card (with DITUTUP watermark) in the grid. |
| **Expected Result** | Popup appears via CustomEvent 'open-popup-ditutup'. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-022 | AC-017: Popup has keyboard trap (Tab cycling)
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Press Tab repeatedly. 2. Press Shift+Tab repeatedly. |
| **Expected Result** | Focus cycles within popup elements only. When on last element and Tab pressed, focus wraps to first element. When on first element and Shift+Tab pressed, focus wraps to last element. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## D. Form Step 4 Tests

### TC-PD-023 | AC-010: Closed pelatihan not selectable
| Field | Value |
|-------|-------|
| **Preconditions** | Authenticated user. Closed and open pelatihan exist. |
| **Test Steps** | 1. Go to form step 4. 2. Try to click/select a closed pelatihan card. |
| **Expected Result** | The card is not selected (radio indicator stays empty). Only the popup appears. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-024 | AC-011: Closed pelatihan still visible in grid
| Field | Value |
|-------|-------|
| **Preconditions** | Same as TC-PD-023 |
| **Test Steps** | 1. Go to form step 4. 2. Observe all cards. |
| **Expected Result** | Closed cards are visible (not hidden), with watermark overlay, red badge, and closure info. They have `disabled` class with opacity 0.5. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-025 | AC-011: Closure info text displayed on card
| Field | Value |
|-------|-------|
| **Preconditions** | Same as TC-PD-023 |
| **Test Steps** | 1. Go to form step 4. 2. Look at closed card bottom. |
| **Expected Result** | Red warning box: "Pendaftaran ditutup pada [tanggal]" |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-026 | Watermark overlay visible on form cards
| Field | Value |
|-------|-------|
| **Preconditions** | Same as TC-PD-023 |
| **Test Steps** | 1. Go to form step 4. 2. Check closed card. |
| **Expected Result** | `.watermark-overlay-card` with `.watermark-text-card` "DITUTUP" visible. Red, rotated, semi-transparent. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-027 | Form submit button works for open pelatihan
| Field | Value |
|-------|-------|
| **Preconditions** | Authenticated user. Open pelatihan selected. |
| **Test Steps** | 1. Select an open pelatihan card. 2. Click "Selanjutnya". |
| **Expected Result** | Form submits successfully. Redirect to step 5 with success message. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## E. Detail Page Tests

### TC-PD-028 | AC-013/FR-013: Detail page shows banner when closed
| Field | Value |
|-------|-------|
| **Preconditions** | Closed pelatihan with `is_active = true` exists. |
| **Test Steps** | 1. Visit `/pelatihan/{id}` for a closed pelatihan. |
| **Expected Result** | Red alert banner appears at top with: icon, title "Pendaftaran Ditutup", message including closure date, and "Lihat Pelatihan Lain" button. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-029 | Detail page no banner for open pelatihan
| Field | Value |
|-------|-------|
| **Preconditions** | Open pelatihan (batas_pendaftaran null) exists. |
| **Test Steps** | 1. Visit `/pelatihan/{id}` for an open pelatihan. |
| **Expected Result** | No red banner. Page displays normally. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## F. Regression Tests

### TC-PD-030 | AC-015: Open pelatihan (no deadline) works normally
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan with `batas_pendaftaran = null` and `is_active = true` |
| **Test Steps** | 1. Visit `/pelatihan`. 2. Visit detail page. 3. Go through enrollment flow. |
| **Expected Result** | All flows work as before. No watermark. Normal Daftar button. Enrollment succeeds. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-031 | Open pelatihan (future deadline) works normally
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan with `batas_pendaftaran = tomorrow` and `is_active = true` |
| **Test Steps** | 1. Visit `/pelatihan`. 2. Attempt enrollment. |
| **Expected Result** | Shows as open. Enrollment succeeds. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-032 | Admin CRUD unaffected
| Field | Value |
|-------|-------|
| **Preconditions** | Admin is logged in |
| **Test Steps** | 1. Browse pelatihan management pages. 2. Create/edit/delete pelatihan. |
| **Expected Result** | Admin CRUD works exactly as before. No changes to admin views. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-033 | Enrollment approval flow unaffected
| Field | Value |
|-------|-------|
| **Preconditions** | Admin logged in. Pending enrollment exists. |
| **Test Steps** | 1. Approve an enrollment. 2. Reject an enrollment. |
| **Expected Result** | Approval/rejection works as before. No side effects. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-034 | Non-active pelatihan unaffected
| Field | Value |
|-------|-------|
| **Preconditions** | Pelatihan with `is_active = false` and past `batas_pendaftaran` |
| **Test Steps** | 1. Visit `/pelatihan`. |
| **Expected Result** | Inactive pelatihan is not shown (filtered by `where('is_active', true)` in controller). |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## G. Responsive & Accessibility Tests

### TC-PD-035 | AC-016: Watermark responsive on mobile
| Field | Value |
|-------|-------|
| **Preconditions** | Closed pelatihan exists |
| **Test Steps** | 1. Open `/pelatihan` on mobile viewport (320px+). |
| **Expected Result** | Watermark scales properly (font-size clamp(1.5rem, 4vw, 2.5rem)). Card layout stays intact. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-036 | AC-016: Popup responsive on mobile
| Field | Value |
|-------|-------|
| **Preconditions** | Closed pelatihan exists |
| **Test Steps** | 1. Open popup on mobile viewport (320px+). |
| **Expected Result** | Popup: padding 32px 20px 24px, margin 10px, max-width 100%. Icon: 60px. Text wraps properly. Buttons stack vertically. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

### TC-PD-037 | AC-017: Popup has proper ARIA attributes
| Field | Value |
|-------|-------|
| **Preconditions** | Popup is open |
| **Test Steps** | 1. Inspect the popup element. |
| **Expected Result** | `role="dialog"`, `aria-modal="true"`, `aria-labelledby="popup-ditutup-title"`. Close button has `aria-label="Tutup popup"`. |
| **Actual Result** | |
| **Status** | ⏳ Not Tested |

---

## Summary

| Section | Total | Passed | Failed | Not Tested |
|---------|-------|--------|--------|------------|
| A. Backend Logic | 8 | 0 | 0 | 8 |
| B. Frontend Visual | 5 | 0 | 0 | 5 |
| C. Popup | 9 | 0 | 0 | 9 |
| D. Form Step 4 | 5 | 0 | 0 | 5 |
| E. Detail Page | 2 | 0 | 0 | 2 |
| F. Regression | 5 | 0 | 0 | 5 |
| G. Responsive/A11y | 3 | 0 | 0 | 3 |
| **Total** | **37** | **0** | **0** | **37** |
