#!/bin/bash
# Health check untuk Notification System

echo "=== Notification System Health Check ==="
echo ""

# 1. Cek queue worker berjalan
if pgrep -f "queue:work" > /dev/null; then
    echo "[✅] Queue Worker: RUNNING"
else
    echo "[❌] Queue Worker: NOT RUNNING"
fi

# 2. Cek scheduler berjalan
if pgrep -f "schedule:work" > /dev/null; then
    echo "[✅] Scheduler: RUNNING"
else
    echo "[❌] Scheduler: NOT RUNNING"
fi

echo ""

# 3. Cek tabel notifikasi
echo "[📊] Notifications:"
php artisan tinker --execute="echo 'Total: ' . \App\Models\Notification::count() . PHP_EOL;"

# 4. Cek template notifikasi
php artisan tinker --execute="echo 'Templates: ' . \App\Models\NotificationTemplate::count() . PHP_EOL;"

# 5. Cek notifikasi pending
php artisan tinker --execute="echo 'Pending: ' . \App\Models\Notification::where('status', 'pending')->count() . PHP_EOL;"

echo ""

# 6. Cek failed jobs
echo "[📋] Failed Jobs:"
php artisan queue:failed | head -5

echo ""
echo "=== Health Check Complete ==="
