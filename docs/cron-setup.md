# Setup Cron Job & Notification System

## Cron untuk Scheduler

Tambahkan ke crontab server:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🔔 Notifikasi WhatsApp

### Prasyarat
- Queue worker berjalan (lihat setup di bawah)
- WA Gateway API key sudah diisi di admin panel
- Pastikan `.env` memiliki `QUEUE_CONNECTION=database`

### Setup Queue Worker

**Via Supervisor (production):**
```bash
sudo supervisorctl start laravel-worker:*
```

**Manual (development):**
```bash
php artisan queue:work database --sleep=3 --tries=3
```

### Setup Scheduler

**Via crontab:**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Via Supervisor:**
```bash
sudo supervisorctl start laravel-scheduler
```

### Supervisor Config Files

Config files tersedia di `docker/supervisor/`:
- `laravel-worker.conf` — Queue worker (2 process)
- `laravel-scheduler.conf` — Scheduler

Copy ke `/etc/supervisor/conf.d/`:
```bash
sudo cp docker/supervisor/*.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
```

### Commands

| Command | Deskripsi |
|---------|-----------|
| `php artisan notifications:send-reminders` | Kirim reminder jadwal besok |
| `php artisan notifications:process-queue` | Proses notifikasi pending |
| `php artisan notifications:cleanup {days}` | Hapus notifikasi lama (default: 30) |
| `php artisan notifications:test {user}` | Kirim notifikasi test ke user |

### Template Default

| Key | Kegunaan |
|-----|----------|
| welcome_peserta | Selamat datang peserta baru |
| pendaftaran_diterima | Peserta diterima di pelatihan |
| pendaftaran_ditolak | Peserta ditolak |
| tugas_baru | Tugas/kuis baru |
| pengingat_jadwal | Pengingat jadwal besok |
| kelulusan | Sertifikat terbit |

### Health Check

Jalankan script health check:
```bash
bash scripts/check-notification-system.sh
```

### Deployment

Deploy notification system sudah termasuk dalam `deploy.sh` (step 8/11):
- Seed template notifikasi
- Restart queue worker
- Restart supervisor services
