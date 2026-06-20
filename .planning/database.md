# 🗄️ Perancangan Database — Aplikasi Pelatihan

> Update: 12 Juni 2026 — Berdasarkan kondisi aktual (25 file migration)

---

## 📊 Tabel yang SUDAH Ada di Sistem

### 1. `users` ✅ (Sudah dimodifikasi)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| name | string | |
| email | string (unique) | |
| nik | string (16) unique nullable | NIK peserta |
| whatsapp | string (20) nullable | |
| role | string (20) | default: 'peserta' |
| phone | string (20) nullable | |
| avatar | string nullable | |
| bio | text nullable | |
| is_active | boolean | default: true |
| kecamatan_id | bigint (FK→kecamatans) nullable | |
| kelurahan_id | bigint (FK→kelurahans) nullable | |
| email_verified_at | timestamp nullable | |
| password | string | |
| two_factor_secret | text nullable | |
| two_factor_recovery_codes | text nullable | |
| two_factor_confirmed_at | timestamp nullable | |
| remember_token | string nullable | |
| current_team_id | bigint nullable | |
| profile_photo_path | string (2048) nullable | |
| timestamps | | |

### 2. `password_reset_tokens` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| email | string (PK) | |
| token | string | |
| created_at | timestamp nullable | |

### 3. `sessions` ✅
| Kolom | Tipe |
|-------|------|
| id | string (PK) |
| user_id | bigint (FK) nullable |
| ip_address | string (45) nullable |
| user_agent | text nullable |
| payload | longText |
| last_activity | integer |

### 4. `cache` ✅
| Kolom | Tipe |
|-------|------|
| key | string (PK) |
| value | mediumText |
| expiration | integer |

### 5. `cache_locks` ✅
| Kolom | Tipe |
|-------|------|
| key | string (PK) |
| owner | string |
| expiration | integer |

### 6. `jobs` ✅
| Kolom | Tipe |
|-------|------|
| id | bigint (PK) |
| queue | string |
| payload | longText |
| attempts | tinyint |
| reserved_at | integer nullable |
| available_at | integer |
| created_at | integer |

### 7. `job_batches` ✅
| Kolom | Tipe |
|-------|------|
| id | string (PK) |
| name | string |
| total_jobs | integer |
| pending_jobs | integer |
| failed_jobs | integer |
| failed_job_ids | longText |
| options | mediumText nullable |
| cancelled_at | integer nullable |
| created_at | integer |
| finished_at | integer nullable |

### 8. `failed_jobs` ✅
| Kolom | Tipe |
|-------|------|
| id | bigint (PK) |
| uuid | string unique |
| connection | text |
| queue | text |
| payload | longText |
| exception | longText |
| failed_at | timestamp |

### 9. `passkeys` ✅
| Kolom | Tipe |
|-------|------|
| id | bigint (PK) |
| user_id | bigint (FK→users) |
| name | string |
| credential_id | string unique |
| credential | json |
| last_used_at | timestamp nullable |
| timestamps | |

### 10. `personal_access_tokens` ✅
| Kolom | Tipe |
|-------|------|
| id | bigint (PK) |
| tokenable_type | string |
| tokenable_id | bigint |
| name | text |
| token | string (64) unique |
| abilities | text nullable |
| last_used_at | timestamp nullable |
| expires_at | timestamp nullable |
| timestamps | |

### 11. `kecamatans` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| name | string (100) | 30 kecamatan Kota Bandung |
| timestamps | | |

### 12. `kelurahans` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| name | string (200) | 151 kelurahan |
| kecamatan_id | bigint (FK→kecamatans) | |
| is_active | boolean | default: true |
| timestamps | | |

### 13. `peserta_profiles` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| kelurahan_id | bigint (FK→kelurahans) nullable | |
| user_id | bigint (FK→users) | cascade delete |
| pelatihan_id | bigint (FK→pelatihan) nullable | |
| nama_lengkap | string | |
| jenis_kelamin | string nullable | |
| tempat_lahir | string nullable | |
| tanggal_lahir | string nullable | |
| bulan_lahir | string nullable | |
| tahun_lahir | string nullable | |
| nik | string nullable | |
| alamat_ktp | text nullable | |
| rt | string nullable | |
| rw | string nullable | |
| kelurahan | string nullable | |
| kecamatan | string nullable | |
| kota | string nullable | |
| provinsi | string nullable | |
| kodepos | string nullable | |
| whatsapp | string nullable | |
| email | string nullable | |
| link_medsos | json nullable | Array platform+url |
| batch_pelatihan | string nullable | |
| pendidikan_terakhir | string nullable | |
| nama_institusi | string nullable | |
| jurusan | string nullable | |
| tahun_lulus | string nullable | |
| status_pekerjaan | string nullable | |
| nama_perusahaan | string nullable | |
| bidang_minat | json nullable | Array minat |
| tujuan_pelatihan | text nullable | |
| preferensi_jadwal | string nullable | |
| preferensi_mode | string nullable | |
| foto_profil | string nullable | |
| scan_ktp | string nullable | |
| is_completed | boolean | default: false |
| timestamps | | |

### 14. `pelatihan` ✅ (Awal: `pelatihans`, di-rename jadi `pelatihan`)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| dinas_id | bigint (FK→dinas) nullable | |
| nama | string (200) | |
| batch | string (50) unique | |
| deskripsi | text nullable | |
| batas_pendaftaran | date nullable | |
| tanggal_mulai | date nullable | |
| tanggal_selesai | date nullable | |
| kuota | integer nullable | |
| is_active | boolean | default: true |
| timestamps | | |

### 15. `kecamatan_pelatihan` ✅ (Pivot)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| kecamatan_id | bigint (FK→kecamatans) | |
| pelatihan_id | bigint (FK→pelatihan) | |
| timestamps | | |
| **Unique** | | (kecamatan_id, pelatihan_id) |

### 16. `dinas` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| nama_dinas | string (200) | |
| singkatan | string (50) nullable | |
| is_active | boolean | default: true |
| timestamps | | |

### 17. `settings` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| key | string (100) unique | |
| value | text nullable | |
| group | string (50) | default: 'general' |
| label | string (200) nullable | |
| timestamps | | |

### 18. `faqs` ✅
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint (PK) | |
| question | string (500) | |
| answer | text | |
| order | integer | default: 0 |
| is_active | boolean | default: true |
| timestamps | | |

---

## 📋 Tabel yang BELUM Dibuat (Rencana ke Depan)

| Tabel | Prioritas | Keterangan |
|-------|-----------|------------|
| `training_categories` | Medium | Kategori pelatihan (sementara pakai dinas) |
| `training_modules` | **High** | Modul/Bab per pelatihan |
| `materials` | **High** | Materi (PDF, video, embed) per modul |
| `assignments` | **High** | Tugas/kuis per modul |
| `assignment_questions` | **High** | Soal per tugas |
| `submissions` | **High** | Jawaban peserta |
| `enrollments` | **High** | Pendaftaran peserta ke pelatihan |
| `attendances` | Medium | Absensi kehadiran |
| `schedules` | Medium | Jadwal pertemuan |
| `certificates` | Medium | Sertifikat kelulusan |
| `discussions` | Medium | Forum diskusi |
| `discussion_comments` | Low | Komentar diskusi |
| `notifications` | Low | Notifikasi in-app |

---

## 🔗 Relasi Antar Tabel (Saat Ini)

```
users ──┬── role (admin/instruktur/koordinator/peserta)
        │
        ├── belongsTo ──> kecamatans
        ├── belongsTo ──> kelurahans
        ├── hasOne ──> peserta_profiles
        │
pelatihan ──┬── belongsTo ──> dinas
           │
           ├── belongsToMany ──> kecamatans (via kecamatan_pelatihan)
           │
           └── hasMany ──> peserta_profiles
```

---

## 🔐 Catatan Teknis
- Semua tabel menggunakan **InnoDB** & **utf8mb4**
- Foreign key constraints aktif
- Migration sudah 25 file, siap dijalankan
- Database aktif: MariaDB di server remote (`103.197.191.226`)
