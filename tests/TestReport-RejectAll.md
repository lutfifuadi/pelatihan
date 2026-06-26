# Test Report: Reject All Enrollments

**Tanggal:** 26 Juni 2026
**Tester:** Farhan (QA)

## Summary
- Total Test: 19
- Passed: 19
- Failed: 0

## Test Results (Automated — PHPUnit)

### AC-001: [PASS] — Tombol muncul saat pelatihan dipilih
- Halaman `admin.enrollments.index?pelatihan_id=1` menampilkan tombol "Reject All Pending"
- Tombol berisi badge jumlah pending dan data-url route reject-all
- Tombol menggunakan class `btn-danger` (merah)
- Test: `test_index_page_shows_reject_all_button_when_pelatihan_filtered`

### AC-002: [PASS] — Tombol tidak muncul tanpa filter
- Halaman `admin.enrollments.index` (tanpa pelatihan_id) tidak menampilkan tombol
- Blade menggunakan `@if(request('pelatihan_id'))` untuk conditional rendering
- Test: `test_index_page_hides_reject_all_button_without_pelatihan_filter`

### AC-003: [PASS] — Konfirmasi SweetAlert2 (Manual Test)
- Klik tombol → popup muncul dengan judul "Tolak Semua?"
- Terdapat tombol "Ya, Tolak Semua!" (merah) dan "Batal"
- Pesan menampilkan jumlah pending yang akan ditolak
- Diverifikasi secara manual di browser

### AC-004: [PASS] — Reject massal berhasil
- 3 enrollment pending untuk pelatihan tertentu semuanya berubah jadi `rejected`
- Kolom `rejected_at` terisi, `notes` berisi "[Reject All: ...]"
- Hanya status `pending` yang diubah; `approved`, `rejected`, `waitlist` tidak tersentuh
- Activity log tercatat dengan action `rejected` untuk Enrollment
- Test: `test_reject_all_rejects_all_pending_enrollments`, `test_reject_all_only_affects_pending_enrollments`, `test_reject_all_logs_activity`

### AC-005: [PASS] — Notifikasi WA dispatch
- Event `PendaftaranRejected` dispatch sebanyak jumlah pending
- Dispatch terjadi di luar transaction sehingga tidak rollback jika gagal
- Error dispatch tidak memutus proses (try-catch dengan log warning)
- Test: `test_reject_all_dispatches_notification_for_each_pending`

### AC-006: [PASS] — Tidak ada pending = error
- Pelatihan tanpa enrollment pending → muncul flash error "Tidak ada pendaftaran pending untuk pelatihan ini."
- Pelatihan dengan enrollment approved/rejected/waitlist saja juga error
- Test: `test_reject_all_returns_error_when_no_pending`, `test_reject_all_returns_error_when_no_enrollments`

### AC-007: [PASS] — Promosi waitlist
- Ketika ada slot kosong setelah reject (kuota 3, 2 pending di-reject) → waitlist terpromosi ke approved
- `waitlist_promoted_at` dan `approved_at` terisi
- Jika kuota penuh (1 approved sudah penuh) → waitlist tetap waitlist
- Test: `test_reject_all_promotes_waitlist_when_slots_freed`, `test_reject_all_does_not_promote_waitlist_when_quota_full`

### AC-008: [PASS] — Update badge
- Setelah reject all, jumlah pending di badge menjadi 0
- Test: `test_reject_all_updates_pending_count_to_zero`

## Regression

### Approve All: [PASS]
- `EnrollmentKuotaBlokadeTest` — 13 tests passing (approve all, FCFS, kuota block)

### Reject individual: [PASS]
- `EnrollmentChangeStatusTest::test_pending_to_rejected_sets_rejected_at_and_sends_notification` — PASS
- `EnrollmentChangeStatusTest::test_waitlist_to_rejected` — PASS

### Approve individual: [PASS]
- `EnrollmentChangeStatusTest::test_pending_to_approved_sets_approved_at` — PASS
- `EnrollmentChangeStatusTest::test_rejected_to_approved_sets_approved_at` — PASS

### Filter & Search: [PASS]
- `AdminTest::test_admin_can_search_and_filter_users` — PASS
- Halaman index enrollment menampilkan data sesuai filter pelatihan

### Transfer Enrollment: [PASS]
- `EnrollmentTransferTest` — 8 tests passing

### Export Enrollment: [PASS]
- `ExportEnrollmentTest` — 16 tests passing

### Lainnya:
- Auth, Dashboard, Landing, Maintenance, Notifikasi, WA Support — semua PASS

## Total Suite
- **292 tests passed** (871 assertions)
- **0 failures**

## Manual Test Checklist (Browser)

| No | Skenario | Status | Catatan |
|----|----------|--------|---------|
| 1 | Buka /admin/enrollments?pelatihan_id=1 → tombol Reject All muncul | ✅ PASS | Tombol merah dengan badge jumlah |
| 2 | Buka /admin/enrollments → tombol Reject All tidak muncul | ✅ PASS | |
| 3 | Klik Reject All → SweetAlert2 konfirmasi | ✅ PASS | Judul "Tolak Semua?", tombol "Ya, Tolak Semua!" |
| 4 | Klik "Batal" → tidak terjadi reject | ✅ PASS | |
| 5 | Klik "Ya, Tolak Semua!" → loading spinner, reload, success flash | ✅ PASS | |
| 6 | Cek DB: status jadi rejected, rejected_at terisi | ✅ PASS | |
| 7 | Cek activity log: action=rejected untuk Enrollment | ✅ PASS | |
| 8 | Pelatihan tanpa pending → error flash | ✅ PASS | |
| 9 | Approve All masih berfungsi setelah Reject All | ✅ PASS | |

## Bugs Found
- **Tidak ada.** Semua acceptance criteria terpenuhi.

## Fix yang Dilakukan
- Migration `2026_06_25_130515` dan `2026_06_25_135248` menggunakan `MODIFY COLUMN` yang tidak kompatibel dengan SQLite. Ditambahkan guard `if (DB::getDriverName() !== 'sqlite')` agar migration bisa jalan di environment testing (SQLite :memory:).

## Kesimpulan
**Layak rilis.** Fitur Reject All berfungsi sesuai spesifikasi. Seluruh acceptance criteria terpenuhi, semua regression test passing, dan tidak ditemukan bug.
