document.addEventListener('alpine:init', () => {
    Alpine.data('notificationBell', () => ({
        open: false,
        unreadCount: 0,
        notifications: [],

        init() {
            this.fetchNotifications();
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
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '' }
            }).then(() => this.fetchNotifications());
        },

        toggleDropdown() {
            this.open = !this.open;
        }
    }));
});
