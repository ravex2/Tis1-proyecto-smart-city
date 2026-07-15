
class NotificationManager {
    constructor(userId, serverUrl = 'http://localhost:3001') {
        this.userId = userId;
        this.serverUrl = serverUrl;
        this.socket = null;
        this.onNewNotification = null;
        this.onReadNotification = null;
        this.onDeleteNotification = null;
    }

    connect() {
        if (typeof io === 'undefined') {
            console.warn('Socket.IO no está cargado');
            return;
        }

        this.socket = io(this.serverUrl, {
            transports: ['websocket', 'polling']
        });

        this.socket.on('connect', () => {
            console.log('Conectado al servidor de notificaciones');
            this.socket.emit('join', this.userId);
        });

        this.socket.on('notification', (data) => {
            console.log('Notificación recibida:', data);

            switch (data.event) {
                case 'notification:new':
                    if (this.onNewNotification) this.onNewNotification(data.data);
                    break;
                case 'notification:read':
                    if (this.onReadNotification) this.onReadNotification(data.data);
                    break;
                case 'notification:deleted':
                    if (this.onDeleteNotification) this.onDeleteNotification(data.data);
                    break;
                case 'notification:readAll':
                    // Actualizar todas las notificaciones como leídas en la UI
                    if (this.onReadNotification) this.onReadNotification({ all: true });
                    break;
            }
        });

        this.socket.on('disconnect', () => {
            console.log('Desconectado del servidor');
            // Reintentar conexión
            setTimeout(() => this.connect(), 3000);
        });
    }

    disconnect() {
        if (this.socket) {
            this.socket.disconnect();
        }
    }

    async getUnreadCount() {
        const response = await fetch(
            `${this.serverUrl}/api/notifications/${this.userId}/unread-count`,
            { headers: { 'x-api-key': 'tu_secreto_secreto' } }
        );
        const data = await response.json();
        return data.unreadCount;
    }

    async markAsRead(notificationId) {
        const response = await fetch(
            `${this.serverUrl}/api/notifications/${this.userId}/${notificationId}/read`,
            {
                method: 'PATCH',
                headers: { 'x-api-key': 'tu_secreto_secreto' }
            }
        );
        return response.json();
    }
}

// Uso:
const notifManager = new NotificationManager(userId, 'http://localhost:3001');

notifManager.onNewNotification = (notification) => {
    // Mostrar notificación toast, badge, etc.
    showNotificationToast(notification);
    updateUnreadBadge();
};

notifManager.onReadNotification = (data) => {
    if (data.all) {
        // Marcar todas como leídas en la UI
        document.querySelectorAll('.notification.unread').forEach(el => {
            el.classList.remove('unread');
        });
    } else {
        // Marcar una específica
        document.querySelector(`#notif-${data.notificationId}`)?.classList.remove('unread');
    }
    updateUnreadBadge();
};

notifManager.connect();