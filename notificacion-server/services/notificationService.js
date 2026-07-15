const redisConfig = require('../config/redis');
const Notification = require('../models/Notification');


class NotificationService {
    constructor() {
        this.redis = null;
    }

    init(redis) {
        this.redis = redis;
    }

    // Crear nueva notificación
    async create(notificationData) {
        const notification = new Notification(notificationData);
        const key = `user:${notification.userId}:notifications`;
        const notificationKey = `notification:${notification.id}`;

        // Guardar la notificación como hash
        await this.redis.client.hSet(notificationKey, {
            id: notification.id,
            userId: notification.userId,
            type: notification.type,
            title: notification.title,
            message: notification.message,
            data: JSON.stringify(notification.data),
            priority: notification.priority,
            read: notification.read ? '1' : '0',
            createdAt: notification.createdAt
        });

        // Establecer TTL en la notificación individual
        await this.redis.client.expire(notificationKey, notification.ttl);

        // Agregar el ID a la lista ordenada del usuario (por timestamp descendente)
        const score = Date.now();
        await this.redis.client.zAdd(key, {
            score: score,
            value: notification.id
        });

        // Establecer TTL en la lista de notificaciones del usuario
        await this.redis.client.expire(key, notification.ttl);

        // Publicar en el canal para tiempo real
        await this.redis.publisher.publish(
            `user:${notification.userId}:channel`,
            JSON.stringify({
                event: 'notification:new',
                data: notification.toJSON()
            })
        );

        // Incrementar contador de no leídas
        await this.redis.client.incr(`user:${notification.userId}:unread`);

        return notification;
    }

    // Obtener notificaciones de un usuario con paginación
    async getUserNotifications(userId, { page = 1, limit = 20, unreadOnly = false } = {}) {
        const key = `user:${userId}:notifications`;
        const offset = (page - 1) * limit;
        const end = offset + limit - 1;

        // Obtener IDs ordenados por timestamp (más recientes primero)
        let notificationIds = await this.redis.client.zRange(key, offset, end, { REV: true });

        // Obtener detalles de cada notificación
        const notifications = [];
        for (const id of notificationIds) {
            const notif = await this.redis.client.hGetAll(`notification:${id}`);
            if (notif && Object.keys(notif).length > 0) {
                const parsed = {
                    ...notif,
                    data: JSON.parse(notif.data || '{}'),
                    read: notif.read === '1'
                };
                if (!unreadOnly || !parsed.read) {
                    notifications.push(parsed);
                }
            }
        }

        const total = await this.redis.client.zCard(key);
        const unreadCount = parseInt(await this.redis.client.get(`user:${userId}:unread`) || '0');

        return {
            notifications,
            pagination: {
                page,
                limit,
                total,
                totalPages: Math.ceil(total / limit)
            },
            unreadCount
        };
    }

    // Marcar notificación como leída
    async markAsRead(userId, notificationId) {
        const notificationKey = `notification:${notificationId}`;
        const wasUnread = await this.redis.client.hGet(notificationKey, 'read') === '0';

        if (wasUnread) {
            await this.redis.client.hSet(notificationKey, 'read', '1');
            
            // Decrementar contador de no leídas
            const unreadKey = `user:${userId}:unread`;
            const currentUnread = parseInt(await this.redis.client.get(unreadKey) || '0');
            if (currentUnread > 0) {
                await this.redis.client.decr(unreadKey);
            }

            // Notificar via Pub/Sub
            await this.redis.publisher.publish(
                `user:${userId}:channel`,
                JSON.stringify({
                    event: 'notification:read',
                    data: { notificationId }
                })
            );
        }

        return { success: true };
    }

    // Marcar todas como leídas
    async markAllAsRead(userId) {
        const key = `user:${userId}:notifications`;
        const notificationIds = await this.redis.client.zRange(key, 0, -1);

        const pipeline = this.redis.client.multi();
        for (const id of notificationIds) {
            pipeline.hSet(`notification:${id}`, 'read', '1');
        }
        await pipeline.exec();

        // Resetear contador de no leídas
        await this.redis.client.set(`user:${userId}:unread`, '0');

        await this.redis.publisher.publish(
            `user:${userId}:channel`,
            JSON.stringify({
                event: 'notification:readAll',
                data: { count: notificationIds.length }
            })
        );

        return { success: true, count: notificationIds.length };
    }

    // Eliminar notificación
    async delete(userId, notificationId) {
        const notificationKey = `notification:${notificationId}`;
        const userKey = `user:${userId}:notifications`;

        const wasUnread = await this.redis.client.hGet(notificationKey, 'read') === '0';

        // Eliminar de la lista y el hash
        await this.redis.client.zRem(userKey, notificationId);
        await this.redis.client.del(notificationKey);

        if (wasUnread) {
            const unreadKey = `user:${userId}:unread`;
            const currentUnread = parseInt(await this.redis.client.get(unreadKey) || '0');
            if (currentUnread > 0) {
                await this.redis.client.decr(unreadKey);
            }
        }

        await this.redis.publisher.publish(
            `user:${userId}:channel`,
            JSON.stringify({
                event: 'notification:deleted',
                data: { notificationId }
            })
        );

        return { success: true };
    }

    // Obtener solo el conteo de no leídas (ultra rápido por caché)
    async getUnreadCount(userId) {
        const count = await this.redis.client.get(`user:${userId}:unread`);
        return parseInt(count || '0');
    }

    // Broadcast a múltiples usuarios
    async broadcastToUsers(userIds, notificationData) {
        const results = [];
        for (const userId of userIds) {
            const notification = await this.create({ ...notificationData, userId });
            results.push(notification);
        }
        return results;
    }
}

module.exports = new NotificationService();