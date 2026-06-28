# BUG REPORT: BUG-HAPUSLOG-001

| Metadata | Detail |
|----------|--------|
| **Title** | Bulk Delete: JSON string dikirim sebagai string, bukan array |
| **Severity** | ⚠️ **Critical** (fitur tidak berfungsi) |
| **Status** | 🆕 Open |
| **Found by** | Farhan (QA) |
| **Date** | 2026-06-27 |
| **Module** | Log Aktivitas - Bulk Delete |
| **Assigned to** | — |

## Deskripsi

Tombol "Hapus Terpilih" pada bulk delete bar mengirim data `ids` dalam format JSON string (misal `["1","2","3"]`) melalui form submission biasa (`application/x-www-form-urlencoded`). Server menerimanya sebagai **string**, bukan **array**, sehingga validasi Laravel `required|array` gagal.

## Steps to Reproduce

1. Login sebagai admin
2. Buka `/admin/activity-logs`
3. Centang 2-3 log aktivitas
4. Klik tombol "Hapus Terpilih"
5. Klik **OK** pada konfirmasi
6. ❌ **Error**: Halaman redirect dengan validasi error "The ids must be an array."

## Root Cause

Pada file `resources/views/content/admin/activity-logs/index.blade.php`:

```javascript
// Line 778 — JSON string disimpan ke hidden input
bulkIds.value = JSON.stringify(Array.from(checked).map(cb => cb.value));
```

Hidden input:

```html
<!-- Line 571 — name="ids" (bukan name="ids[]") -->
<input type="hidden" name="ids" id="bulkIds">
```

Saat form disubmit via POST biasa, nilai `["1","2","3"]` dikirim sebagai string literal, bukan array PHP. Validasi `required|array` di controller (line 83) gagal karena `$request->ids` adalah string.

## Expected vs Actual

| | Expected | Actual |
|--|----------|--------|
| **ids** | PHP array `[1, 2, 3]` | PHP string `"[1,2,3]"` |
| **Validasi** | ✅ Lolos | ❌ "The ids must be an array." |
| **Log terhapus** | ✅ Ya | ❌ Tidak |

## Suggested Fix

### Opsi A (Frontend — multiple hidden inputs)
Ganti input `name="ids"` dengan `name="ids[]"` dan buat multiple input dinamis:

```javascript
function updateBulkBar() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    if (checked.length > 0) {
        bulkDeleteBar.classList.add('show');
        selectedCount.textContent = checked.length + ' dipilih';
        
        // Hapus input ids[] lama
        document.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
        
        // Buat input baru untuk setiap checkbox
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            bulkDeleteForm.appendChild(input);
        });
    } else {
        bulkDeleteBar.classList.remove('show');
        document.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    }
}
```

### Opsi B (Backend — parse JSON string)
Parse JSON string di controller:

```php
public function bulkDestroy(Request $request)
{
    $ids = $request->input('ids');
    if (is_string($ids)) {
        $ids = json_decode($ids, true) ?? [];
        $request->merge(['ids' => $ids]);
    }

    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:activity_logs,id',
    ]);
    // ...
}
```

### Opsi C (Fetch API — kirim sebagai JSON)
Gunakan `fetch()` dengan `Content-Type: application/json` alih-alih form submission biasa.

## Impact

- ⚠️ **Bulk Delete tidak berfungsi sama sekali**
- ⚠️ Admin tidak bisa menghapus log massal
- ✅ Single Delete tetap berfungsi normal (tidak terpengaruh bug ini)
- ✅ Audit trail untuk bulk delete juga tidak tercatat (karena request gagal)

## Test Evidence

Test `test_bulk_delete_fails_when_ids_is_not_an_array` memverifikasi bahwa request dengan JSON string menghasilkan **validation error**:

```php
// Test PASS — membuktikan bahwa JSON string ditolak validasi
public function test_bulk_delete_fails_when_ids_is_not_an_array()
{
    $logs = ActivityLog::factory()->count(2)->create();
    $jsonString = json_encode($logs->pluck('id')->toArray());

    $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
        'ids' => $jsonString,
    ]);

    $response->assertSessionHasErrors(['ids']);
}
```

Test `test_admin_can_bulk_delete_activity_logs_with_proper_array` memverifikasi bahwa request dengan **array asli** berhasil:

```php
// Test PASS — membuktikan backend logic benar
public function test_admin_can_bulk_delete_activity_logs_with_proper_array()
{
    $logs = ActivityLog::factory()->count(3)->create();
    $ids = $logs->pluck('id')->toArray();

    $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
        'ids' => $ids,  // array asli
    ]);

    $response->assertSessionHas('success', '3 log aktivitas berhasil dihapus.');
    // ...
}
```

## Attachment

- File terkait: `resources/views/content/admin/activity-logs/index.blade.php` (line 571, 778)
- File terkait: `app/Http/Controllers/Admin/ActivityLogController.php` (line 82-84)
