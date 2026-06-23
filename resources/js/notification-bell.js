/**
 * Notification Bell — Alpine.js Component
 * Handles: fetch unread, markAsRead, markAllAsRead, timeAgo, waUrl, refresh
 */
const registerNotificationBell = () => {
    if (window.Alpine) {
        window.Alpine.data('notificationBell', () => ({
            open: false,
            unreadCount: 0,
            notifications: [],

            init() {
                this.fetchNotifications();
                // Auto-refresh every 30 seconds
                setInterval(() => this.fetchNotifications(), 30000);
                this.$watch('open', (val) => {
                    if (val) this.fetchNotifications();
                });
            },

            fetchNotifications() {
                fetch('/notifications/unread')
                    .then(res => res.json())
                    .then(data => {
                        this.unreadCount = data.count;
                        this.notifications = data.items;
                    })
                    .catch(() => {});
            },

            markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    // Update local state
                    const notif = this.notifications.find(n => n.id === id);
                    if (notif) {
                        notif.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                }).catch(() => {});
            },

            markAllAsRead() {
                fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    this.notifications.forEach(n => n.read_at = new Date().toISOString());
                    this.unreadCount = 0;
                }).catch(() => {});
            },

            refresh() {
                this.fetchNotifications();
            },

            timeAgo(dateString) {
                if (!dateString) return '';
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffSec = Math.floor(diffMs / 1000);
                const diffMin = Math.floor(diffSec / 60);
                const diffHour = Math.floor(diffMin / 60);
                const diffDay = Math.floor(diffHour / 24);

                if (diffSec < 60) return 'baru saja';
                if (diffMin < 60) return diffMin + 'm';
                if (diffHour < 24) return diffHour + 'j';
                if (diffDay < 7) return diffDay + 'h';
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            },

            waUrl(waData) {
                if (!waData) return '#';
                const adminWa = waData.admin_wa || waData.admin_phone || '62888888888';
                const message = waData.message || this._buildWaMessage(waData);
                return `https://wa.me/${adminWa}?text=${encodeURIComponent(message)}`;
            },

            _buildWaMessage(data) {
                return `Halo Admin, saya telah melakukan pendaftaran pelatihan.

Nama Lengkap Sesuai KTP : ${data.nama_lengkap || '-'}
Jenis Pelatihan : ${data.pelatihan || '-'}
Kelurahan : ${data.kelurahan || '-'}
Kecamatan : ${data.kecamatan || '-'}
No. HP Peserta Terdaftar : ${data.no_hp || '-'}

#pelatihanku2026`;
            },

            toggleDropdown() {
                this.open = !this.open;
            }
        }));
    }
};

if (window.Alpine) {
    registerNotificationBell();
} else {
    document.addEventListener('alpine:init', registerNotificationBell);
}
