# Test Case — Sistem Notifikasi

---

## TC-NOTIF-001: Kirim Notifikasi via Template
| Item | Detail |
|------|--------|
| **Module** | NotificationService |
| **Priority** | Critical |
| **Precondition** | Template dengan key 'welcome_peserta' ada di database |
| **Steps** | 1. Panggil `NotificationService::sendByTemplate($user, 'welcome_peserta', ['nama'=>'Andi', 'pelatihan'=>'Microsoft Office'])` |
| **Expected** | - Notifikasi tercatat di tabel `notifications` dengan status `pending` (jika channel whatsapp)<br>- Data berisi `template_key` dan data yang dikirim<br>- Jika channel `in_app`, status langsung `sent` dengan `sent_at` terisi |

---

## TC-NOTIF-002: Bell Icon Menampilkan Notifikasi Unread
| Item | Detail |
|------|--------|
| **Module** | Frontend — Notification Bell |
| **Priority** | High |
| **Precondition** | User login, ada notifikasi unread |
| **Steps** | 1. Login sebagai user<br>2. Lihat bell icon di navbar<br>3. Klik bell icon |
| **Expected** | - Badge merah muncul dengan jumlah unread<br>- Dropdown menampilkan 5 notifikasi terbaru<br>- Setiap notif menampilkan title, body (truncated), timestamp |

---

## TC-NOTIF-003: Halaman History Notifikasi
| Item | Detail |
|------|--------|
| **Module** | Frontend — History Page |
| **Priority** | High |
| **Precondition** | User login sebagai peserta |
| **Steps** | 1. Buka `/notifications`<br>2. Filter berdasarkan channel (in_app)<br>3. Filter berdasarkan status (unread)<br>4. Klik tombol "Tandai Semua Dibaca" |
| **Expected** | - Halaman menampilkan notifikasi milik user saja<br> - Pagination 20 per halaman<br> - Filter channel dan status berfungsi<br> - "Tandai Semua Dibaca" mengupdate `read_at` semua notifikasi user |

---

## TC-NOTIF-004: Tandai Notifikasi Dibaca
| Item | Detail |
|------|--------|
| **Module** | NotificationController — markAsRead |
| **Priority** | High |
| **Precondition** | User login, memiliki notifikasi unread |
| **Steps** | 1. POST `/notifications/{id}/read`<br>2. Cek database |
| **Expected** | - `read_at` terisi dengan timestamp<br>- User lain tidak bisa menandai notifikasi milik user lain (403) |

---

## TC-NOTIF-005: Halaman Preferensi Notifikasi
| Item | Detail |
|------|--------|
| **Module** | Frontend — Preferences Page |
| **Priority** | Medium |
| **Precondition** | User login |
| **Steps** | 1. Buka `/notifications/preferences`<br>2. Nonaktifkan toggle "WhatsApp"<br>3. Atur quiet hours (22:00 - 06:00)<br>4. Simpan |
| **Expected** | - Preferensi tersimpan di tabel `user_notification_preferences`<br> - User tidak akan menerima notifikasi WhatsApp saat preferensi nonaktif<br> - Notifikasi tidak dikirim selama quiet hours |

---

## TC-NOTIF-006: Render Template Mengganti Placeholder
| Item | Detail |
|------|--------|
| **Module** | NotificationService — renderTemplate |
| **Priority** | Critical |
| **Precondition** | Template dengan title "Halo {nama}" dan body "Selamat datang di {pelatihan}" |
| **Steps** | 1. Panggil `renderTemplate($template, ['nama'=>'Budi', 'pelatihan'=>'Web Dev'])` |
| **Expected** | - Title menjadi "Halo Budi"<br>- Body menjadi "Selamat datang di Web Dev"<br>- Placeholder `{app_name}` diisi dengan config `app.name` |

---

## TC-NOTIF-007: Kirim Notifikasi Saat Template Tidak Ditemukan
| Item | Detail |
|------|--------|
| **Module** | NotificationService — sendByTemplate |
| **Priority** | Medium |
| **Precondition** | Template dengan key 'non_existent_key' tidak ada di database |
| **Steps** | 1. Panggil `sendByTemplate($user, 'non_existent_key', [])` |
| **Expected** | - Return `null`<br>- Tidak ada notifikasi baru yang tercatat<br>- Log warning tercatat |

---

## TC-NOTIF-008: cannotNotify Ketika Preferensi Nonaktif
| Item | Detail |
|------|--------|
| **Module** | NotificationService — canNotify |
| **Priority** | Critical |
| **Precondition** | User memiliki preferensi dengan `whatsapp_enabled = false` |
| **Steps** | 1. Panggil `canNotify($user, 'whatsapp')` |
| **Expected** | - Return `false`<br>- `sendByTemplate` juga return `null` |

---

## TC-NOTIF-009: cannotNotify Selama Quiet Hours
| Item | Detail |
|------|--------|
| **Module** | NotificationService — canNotify |
| **Priority** | Medium |
| **Precondition** | User memiliki preferensi dengan quiet_hours_start=22:00, quiet_hours_end=06:00, dan waktu sekarang di antara jam tersebut |
| **Steps** | 1. Panggil `canNotify($user, 'whatsapp')` saat pukul 23:00 |
| **Expected** | - Return `false`<br>- Notifikasi tidak dikirim selama quiet hours |

---

## TC-NOTIF-010: Kirim WhatsApp ke Nomor Tidak Valid
| Item | Detail |
|------|--------|
| **Module** | NotificationService / SendWhatsAppNotification |
| **Priority** | High |
| **Precondition** | User memiliki nomor WhatsApp kosong |
| **Steps** | 1. Panggil `send($user, 'whatsapp', 'Title', 'Body')` saat `$user->whatsapp` kosong |
| **Expected** | - Return `null`<br>- Log warning tercatat "User {id} has no WhatsApp number" |

---

## TC-NOTIF-011: Admin Melihat Log Pengiriman
| Item | Detail |
|------|--------|
| **Module** | Admin — Notification Log |
| **Priority** | High |
| **Precondition** | Login sebagai admin |
| **Steps** | 1. Buka `/admin/notifications`<br>2. Filter berdasarkan channel 'whatsapp'<br>3. Filter berdasarkan status 'failed'<br>4. Filter berdasarkan tanggal<br>5. Klik salah satu notifikasi untuk melihat detail modal |
| **Expected** | - Halaman menampilkan semua notifikasi dengan pagination 15<br>- Filter channel, status, dan tanggal berfungsi<br>- Detail modal menampilkan data lengkap notifikasi termasuk user dan template<br>- Tombol resend tersedia untuk notifikasi failed |

---

## TC-NOTIF-012: Admin CRUD Template WhatsApp
| Item | Detail |
|------|--------|
| **Module** | Admin — Template Management |
| **Priority** | High |
| **Precondition** | Login sebagai admin |
| **Steps** | 1. Buka `/admin/notification-templates`<br>2. Klik "Tambah Template"<br>3. Isi form: key = 'test_template', name = 'Test Template', body = 'Halo {nama}'<br>4. Simpan<br>5. Edit template yang baru dibuat<br>6. Hapus template |
| **Expected** | - Template tersimpan di database<br>- Variables otomatis diekstrak dari `{...}` di body<br>- Edit berhasil mengubah data<br>- Delete menghapus template dari database |

---

## TC-NOTIF-013: Admin Broadcast WhatsApp
| Item | Detail |
|------|--------|
| **Module** | Admin — Broadcast |
| **Priority** | High |
| **Precondition** | Login sebagai admin, ada template aktif dengan channel whatsapp |
| **Steps** | 1. Buka `/admin/notifications/broadcast`<br>2. Pilih target "Semua Peserta"<br>3. Pilih template<br>4. Klik Kirim |
| **Expected** | - Halaman menampilkan pilihan target (all_peserta, by_pelatihan, all_koordinator, custom)<br>- Broadcast masuk ke antrian queue (database)<br>- Notifikasi tercatat untuk setiap recipient |

---

## TC-NOTIF-014: Event Listener Memicu Notifikasi
| Item | Detail |
|------|--------|
| **Module** | Events / Listeners |
| **Priority** | Critical |
| **Precondition** | Template 'welcome_peserta' aktif di database |
| **Steps** | 1. Dispatch event `PesertaRegistered` dengan user dan pelatihan<br>2. Tunggu listener memproses |
| **Expected** | - Listener `SendNotificationListener::handlePesertaRegistered` terpanggil<br>- `sendByTemplate` dipanggil dengan key 'welcome_peserta'<br>- Notifikasi tercatat untuk user tersebut |

---

## TC-NOTIF-015: Job SendWhatsAppNotification Retry Logic
| Item | Detail |
|------|--------|
| **Module** | Jobs — SendWhatsAppNotification |
| **Priority** | High |
| **Precondition** | Notifikasi pending dengan channel whatsapp |
| **Steps** | 1. Dispatch job `SendWhatsAppNotification` ke queue<br>2. Job gagal (WhatsApp API return false)<br>3. Job gagal lagi sampai 3 attempts |
| **Expected** | - Job memiliki `tries = 3`<br>- Setelah semua attempt gagal, `failed()` method dipanggil<br>- Status notifikasi menjadi `failed` dengan `failed_reason` terisi |

---

## TC-NOTIF-016: ProcessPendingNotifications Memproses Antrian
| Item | Detail |
|------|--------|
| **Module** | NotificationService — processPendingNotifications |
| **Priority** | Medium |
| **Precondition** | Ada notifikasi whatsapp dengan status 'pending' |
| **Steps** | 1. Panggil `processPendingNotifications()`<br>2. Cek update notifikasi |
| **Expected** | - Setiap notifikasi pending diproses<br>- Jika berhasil: status jadi 'sent', sent_at terisi<br>- Jika gagal: status jadi 'failed', failed_reason terisi |

---

## TC-NOTIF-017: User Akses Halaman Tanpa Login
| Item | Detail |
|------|--------|
| **Module** | Middleware — Authentication |
| **Priority** | High |
| **Precondition** | User tidak login |
| **Steps** | 1. Buka `/notifications` tanpa login |
| **Expected** | - Redirect ke halaman login |

---

## TC-NOTIF-018: Non-Admin Tidak Bisa Akses Admin Notifikasi
| Item | Detail |
|------|--------|
| **Module** | Middleware — Role Check |
| **Priority** | High |
| **Precondition** | Login sebagai user dengan role 'peserta' |
| **Steps** | 1. Buka `/admin/notifications` |
| **Expected** | - Response 403 Forbidden |

---

## TC-NOTIF-019: Validasi Nomor WhatsApp (08xx → 628xx)
| Item | Detail |
|------|--------|
| **Module** | WhatsAppValidationService — normalizeNumber |
| **Priority** | Medium |
| **Precondition** | - |
| **Steps** | 1. Panggil `normalizeNumber('08123456789')` |
| **Expected** | - Return `'628123456789'` |

---

## TC-NOTIF-020: Kirim Test Template dari Admin
| Item | Detail |
|------|--------|
| **Module** | Admin — Test Template |
| **Priority** | Medium |
| **Precondition** | Admin memiliki nomor whatsapp terisi |
| **Steps** | 1. Login sebagai admin dengan nomor whatsapp<br>2. Buka daftar template<br>3. Klik "Test Kirim" pada salah satu template |
| **Expected** | - Notifikasi baru tercatat dengan status 'pending'<br>- Job `SendWhatsAppNotification` di-dispatch<br>- Redirect back dengan success message |
