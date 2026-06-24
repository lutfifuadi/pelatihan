# WhatsApp Support Icon — Manual Test Checklist

| ID | AC | Test Case | Precondition | Steps | Expected Result | Status |
|----|----|-----------|-------------|-------|-----------------|--------|
| TC-001 | AC-001 | Ikon WhatsApp muncul di pojok kanan bawah semua halaman publik | Ada minimal 1 nomor WA aktif di database | 1. Buka halaman beranda `/` 2. Buka halaman pelatihan `/pelatihan` 3. Buka halaman detail pelatihan | Ikon WhatsApp (lingkaran hijau dengan logo WA) terlihat di pojok kanan bawah setiap halaman | ⬜ |
| TC-002 | AC-002 | Ikon tidak muncul di halaman admin/dashboard | Ada minimal 1 nomor WA aktif | 1. Login sebagai admin 2. Buka `/admin/dashboard` 3. Navigasi ke halaman admin lain | Ikon WhatsApp tidak muncul di halaman admin manapun | ⬜ |
| TC-003 | AC-003 | Ikon tidak muncul jika tidak ada nomor aktif | Tidak ada nomor WA aktif di database (atau semua nonaktif) | 1. Buka halaman beranda `/` 2. Periksa pojok kanan bawah | Ikon WhatsApp tidak terlihat | ⬜ |
| TC-004 | AC-004 | Ikon memiliki animasi pulse | Ada nomor WA aktif | 1. Buka halaman publik 2. Arahkan perhatian ke ikon | Ikon memiliki efek pulse (mengembang-mengecil) secara terus menerus | ⬜ |
| TC-005 | AC-005 | Ikon memiliki efek shadow dan membesar saat di-hover | Ada nomor WA aktif | 1. Hover mouse ke atas ikon | Ikon membesar (scale 1.1x) dan shadow lebih terang | ⬜ |
| TC-006 | AC-006 | Klik ikon → muncul popup daftar nomor | Ada minimal 2 nomor WA aktif | 1. Klik ikon WhatsApp | Popup muncul menampilkan daftar nomor WA beserta labelnya | ⬜ |
| TC-007 | AC-007 | Popup bisa ditutup dengan klik di luar atau tombol close | Popup sedang terbuka | 1. Klik area di luar popup 2. Atau klik tombol × di header popup | Popup tertutup | ⬜ |
| TC-008 | AC-008 | Klik nomor → buka wa.me/[nomor] di tab baru | Popup terbuka menampilkan nomor | 1. Klik salah satu nomor di popup | Tab baru terbuka ke `https://wa.me/[nomor]` | ⬜ |
| TC-009 | AC-009 | Admin bisa menambah nomor WA via Settings > Branding | Login sebagai admin | 1. Buka `/admin/settings/branding` 2. Cari bagian WhatsApp Numbers 3. Isi label dan nomor 4. Simpan | Nomor baru muncul di daftar | ⬜ |
| TC-010 | AC-010 | Admin bisa mengedit nomor | Ada nomor WA di database | 1. Buka halaman setting branding 2. Klik edit pada nomor 3. Ubah label/nomor 4. Simpan | Data nomor berhasil diupdate | ⬜ |
| TC-011 | AC-011 | Admin bisa menghapus nomor | Ada nomor WA di database | 1. Buka halaman setting branding 2. Klik hapus pada nomor 3. Konfirmasi | Nomor hilang dari daftar | ⬜ |
| TC-012 | AC-012 | Admin bisa mengurutkan nomor | Ada minimal 3 nomor WA | 1. Buka halaman setting branding 2. Seret (drag & drop) nomor ke urutan baru | Urutan nomor berubah sesuai drag | ⬜ |
| TC-013 | AC-013 | Validasi: nomor hanya angka, min 10, max 15 digit | Login sebagai admin | 1. Input nomor dengan huruf (abc) 2. Input nomor 5 digit 3. Input nomor 20 digit | Error: "Nomor hanya boleh berisi angka", "Nomor minimal 10 digit", "Nomor maksimal 15 digit" | ⬜ |
| TC-014 | AC-014 | Validasi: label wajib diisi, max 100 karakter | Login sebagai admin | 1. Submit dengan label kosong 2. Input label 150 karakter | Error: "Label wajib diisi" / validasi max 100 karakter | ⬜ |
| TC-015 | AC-015 | Validasi: tidak boleh duplikasi nomor | Sudah ada nomor 628xxx | 1. Tambah nomor baru dengan nomor yang sama | Error: "Nomor sudah terdaftar" | ⬜ |
| TC-016 | AC-016 | Urutan nomor di popup sesuai urutan admin | Nomor diurutkan: A (urutan 1), B (urutan 2), C (urutan 3) | 1. Buka halaman publik 2. Klik ikon WA | Popup menampilkan A, B, C sesuai urutan | ⬜ |
| TC-017 | AC-017 | Nomor nonaktif tidak muncul di popup | Ada nomor aktif dan nonaktif | 1. Buka halaman publik 2. Klik ikon WA | Hanya nomor aktif yang muncul di popup | ⬜ |
| TC-018 | AC-018 | Responsive di mobile | Ada nomor WA aktif | 1. Buka halaman publik di HP (viewport ≤ 640px) | Ikon dan popup menyesuaikan ukuran (lebih kecil), posisi tetap aman | ⬜ |

---
**Keterangan Status:**
- ⬜ = Not Tested
- ✅ = Pass
- ❌ = Fail
- 🚫 = Blocked
