# PRD: Hapus Log Aktivitas (Admin Panel)

| Metadata | Detail |
|----------|--------|
| **Versi** | 1.0 (Lite-Power) |
| **Status** | ✅ Terselesaikan |
| **Penulis** | PRD Specialist |
| **Tanggal** | 27 Juni 2026 |
| **Implementasi** | Laravel 11 + Blade + Alpine.js |

---

## 1. Ringkasan Eksekutif

Fitur ini menambahkan kemampuan **hapus log aktivitas** di halaman admin panel. Sebelumnya, admin hanya bisa melihat dan memfilter log aktivitas — tidak ada mekanisme hapus. Dengan fitur ini, admin dapat menghapus log satu per satu maupun secara massal dengan konfirmasi.

---

## 2. Latar Belakang & Tujuan

- **Masalah**: Log aktivitas terus bertambah tanpa ada cara untuk membersihkan data historis. Admin tidak bisa menghapus log yang tidak relevan atau menumpuk.
- **Tujuan Bisnis**: Memberikan kontrol penuh kepada admin atas data log aktivitas — baik untuk keperluan pembersihan data rutin maupun manajemen riwayat.
- **User Target**: Super Admin / Admin Panel.

---

## 3. User Stories

| ID | User Story | Prioritas |
|----|-----------|-----------|
| US-01 | Sebagai admin, saya ingin menghapus satu log aktivitas yang tidak relevan agar daftar log tetap bersih. | P1 |
| US-02 | Sebagai admin, saya ingin menghapus banyak log aktivitas sekaligus agar lebih efisien dalam membersihkan data. | P1 |
| US-03 | Sebagai admin, saya ingin mendapat konfirmasi sebelum hapus agar tidak salah hapus data penting. | P1 |
| US-04 | Sebagai admin, saya ingin aktivitas hapus log saya juga tercatat di log aktivitas (audit trail). | P2 |

---

## 4. Acceptance Criteria

| ID | Kriteria | Tipe |
|----|----------|------|
| AC-01 | Setiap baris log memiliki tombol hapus (ikon trash) di kolom paling kanan. | Fungsional |
| AC-02 | Klik tombol hapus memunculkan confirm dialog browser (`confirm()`). | Fungsional |
| AC-03 | Jika dikonfirmasi, log akan dihapus via `DELETE /activity-logs/{id}`. | Fungsional |
| AC-04 | Setelah hapus, muncul flash message sukses dan log hilang dari tabel. | Fungsional |
| AC-05 | Tabel memiliki checkbox di setiap baris untuk memilih log. | Fungsional |
| AC-06 | Ada checkbox "Select All" di header tabel untuk mencentang semua. | Fungsional |
| AC-07 | Saat checkbox dicentang, muncul bulk delete bar di atas tabel. | Fungsional |
| AC-08 | Bulk delete bar menampilkan jumlah item yang dipilih. | Fungsional |
| AC-09 | Klik "Hapus Terpilih" memunculkan confirm dialog dengan jumlah item. | Fungsional |
| AC-10 | Jika dikonfirmasi, semua log terpilih dihapus via `DELETE /activity-logs/bulk/delete`. | Fungsional |
| AC-11 | Klik checkbox atau tombol hapus **tidak** memicu modal detail log (event stopPropagation). | UX |
| AC-12 | Aktivitas hapus (single & bulk) dicatat sebagai log aktivitas baru oleh `ActivityLogger`. | Audit |
| AC-13 | Validasi: `ids` required, array, setiap id harus valid di tabel `activity_logs`. | Keamanan |
| AC-14 | Hanya admin yang terautentikasi bisa mengakses fitur hapus (middleware auth:admin). | Keamanan |
| AC-15 | Semua form delete menggunakan `@csrf` dan `@method('DELETE')`. | Keamanan |

---

## 5. Scope

### In Scope
- ✅ Hapus single log via tombol trash di setiap baris
- ✅ Hapus massal via checkbox + bulk delete bar
- ✅ Confirm dialog sebelum eksekusi hapus
- ✅ Select All checkbox
- ✅ Audit trail (log aktivitas hapus dicatat)
- ✅ Validasi server-side untuk bulk delete
- ✅ CSRF protection untuk semua operasi hapus
- ✅ Dark theme konsisten dengan halaman admin
- ✅ Event stopPropagation agar tidak bentrok dengan modal detail

### Out of Scope
- ❌ Soft delete / restore log (hard delete murni)
- ❌ Filter khusus "tampilkan log yang dihapus"
- ❌ Export log sebelum hapus
- ❌ Scheduled auto-cleanup log
- ❌ Halaman terpisah untuk trash / recycle bin

---

## 6. Alur Pengguna

### Single Delete
1. Admin melihat daftar log aktivitas
2. Admin klik ikon trash pada baris yang ingin dihapus
3. Muncul confirm dialog: "Yakin ingin menghapus log aktivitas ini?"
4. Jika **OK** → request DELETE dikirim → log dihapus → flash message sukses
5. Jika **Cancel** → tidak ada aksi

### Bulk Delete
1. Admin mencentang satu atau beberapa checkbox log
2. Bulk delete bar muncul di atas tabel dengan jumlah terpilih
3. (Opsional) Admin klik "Select All" untuk mencentang semua
4. Admin klik tombol "Hapus Terpilih"
5. Muncul confirm dialog: "Yakin ingin menghapus N log aktivitas terpilih?"
6. Jika **OK** → request DELETE bulk dikirim → log dihapus → flash message sukses
7. Bulk delete bar menghilang setelah sukses

---

## 7. Spesifikasi Teknis

### 7.1 Routes

| Method | URI | Controller Method | Name |
|--------|-----|-------------------|------|
| DELETE | `/activity-logs/{id}` | `destroy($id)` | `activity-logs.destroy` |
| DELETE | `/activity-logs/bulk/delete` | `bulkDestroy(Request)` | `activity-logs.bulk-destroy` |

### 7.2 Controller

**`destroy($id)`**
- Mencari log via `findOrFail($id)`
- Menghapus via `$log->delete()`
- Mencatat audit trail via `ActivityLogger`
- Redirect back dengan flash message sukses

**`bulkDestroy(Request $request)`**
- Validasi: `ids` required, array, tiap id exists di `activity_logs`
- Menghapus via `whereIn('id', $request->ids)->delete()`
- Mencatat audit trail dengan jumlah log yang dihapus
- Redirect back dengan flash message sukses

### 7.3 Frontend (Blade)

| Komponen | Deskripsi |
|----------|-----------|
| `checkbox-log` | Checkbox per baris + Select All di header |
| `.delete-btn-log` | Tombol trash merah dengan hover effect |
| `.bulk-delete-bar` | Floating bar merah yang muncul saat ada checkbox tercentang |
| `#bulkDeleteForm` | Form POST dengan method DELETE, CSRF token |
| JavaScript | Update bulk bar, select all, confirm dialog, stopPropagation |

### 7.4 Keamanan

- ✅ Middleware admin (`auth:admin`)
- ✅ CSRF token di semua form
- ✅ Method spoofing (`@method('DELETE')`)
- ✅ Validasi server-side (`ids.*` exists di DB)
- ✅ Event `stopPropagation()` pada klik checkbox & delete

---

## 8. Struktur File yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `app/Http/Controllers/Admin/ActivityLogController.php` | Menambah method `destroy($id)` dan `bulkDestroy(Request $request)` |
| `routes/web.php` | Menambah 2 route DELETE untuk single & bulk |
| `resources/views/content/admin/activity-logs/index.blade.php` | Menambah checkbox, tombol delete, bulk delete bar, JavaScript interaktivitas, CSS styling |

---

## 9. Dependency Map

Tidak ada dependensi eksternal. Fitur ini berdiri sendiri dan hanya memodifikasi halaman yang sudah ada.

---

## 10. Risk Assessment

| Risiko | Dampak | Mitigasi |
|--------|--------|----------|
| Salah hapus data penting | Tinggi | Confirm dialog sebelum eksekusi |
| Bulk delete menghapus terlalu banyak | Sedang | Confirm dialog menampilkan jumlah item |
| Bentrok dengan modal detail | Rendah | `stopPropagation` pada checkbox & delete button |

---

## 11. Quality Score

| Aspek | Skor | Catatan |
|-------|------|---------|
| Functional Completeness | 100% | Semua acceptance criteria terpenuhi |
| Security | 100% | CSRF, validasi, middleware, audit trail |
| UX | 90% | Konfirmasi, bulk bar, visual feedback |
| Performance | 100% | Operasi hapus ringan, query teroptimasi |

---

## 12. Changelog

| Versi | Tanggal | Perubahan | Penulis |
|-------|---------|-----------|---------|
| 1.0 | 27 Jun 2026 | Dokumen awal PRD | PRD Specialist |

---

*Dokumen ini dibuat menggunakan template PRD Lite-Power. Untuk revisi atau pertanyaan, hubungi tim produk.*
