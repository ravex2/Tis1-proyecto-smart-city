const { v4: uuidv4 } = require('uuid');

class Notification {
    constructor({ userId, type, title, message, data = {}, priority = 'normal', ttl = 604800 }) {
        this.id = uuidv4();
        this.userId = userId;
        this.type = type;           // 'info', 'warning', 'success', 'error'
        this.title = title;
        this.message = message;
        this.data = data;
        this.priority = priority;   // 'low', 'normal', 'high', 'critical'
        this.read = false;
        this.createdAt = new Date().toISOString();
        this.ttl = ttl;             // Tiempo de vida en segundos (default 7 días)
    }

    toJSON() {
        return {
            id: this.id,
            userId: this.userId,
            type: this.type,
            title: this.title,
            message: this.message,
            data: this.data,
            priority: this.priority,
            read: this.read,
            createdAt: this.createdAt
        };
    }
}

module.exports = Notification;