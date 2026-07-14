const express = require('express');
const router = express.Router();
const notificationService = require('../services/notificationService');

// Middleware de autenticación simple (token en header)
const authenticate = (req, res, next) => {
    const token = req.headers['x-api-key'] || req.query.api_key;
    const secret = process.env.AUTH_SECRET ?? 'asd';
    
    if (!token || token !== secret) {
        return res.status(401).json({ error: 'No autorizado' });
    }
    next();
};

// POST /api/notifications - Crear notificación
router.post('/', authenticate, async (req, res) => {
    try {
        const { userId, type, title, message, data, priority, ttl } = req.body;

        if (!userId || !title || !message) {
            return res.status(400).json({
                error: 'Campos requeridos: userId, title, message'
            });
        }

        const notification = await notificationService.create({
            userId,
            type: type || 'info',
            title,
            message,
            data: data || {},
            priority: priority || 'normal',
            ttl: ttl || 604800
        });

        res.status(201).json({
            success: true,
            notification: notification.toJSON()
        });
    } catch (error) {
        console.error('Error creando notificación:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
    }
});

// POST /api/notifications/broadcast - Enviar a múltiples usuarios
router.post('/broadcast', authenticate, async (req, res) => {
    try {
        const { userIds, type, title, message, data, priority } = req.body;

        if (!Array.isArray(userIds) || userIds.length === 0 || !title || !message) {
            return res.status(400).json({
                error: 'Se requiere: userIds (array), title, message'
            });
        }

        const notifications = await notificationService.broadcastToUsers(userIds, {
            type, title, message, data, priority
        });

        res.status(201).json({
            success: true,
            count: notifications.length,
            notifications: notifications.map(n => n.toJSON())
        });
    } catch (error) {
        console.error('Error en broadcast:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
    }
});

// GET /api/notifications/:userId - Obtener notificaciones de un usuario
router.get('/:userId', authenticate, async (req, res) => {
    try {
        const { userId } = req.params;
        const { page = 1, limit = 20, unread_only } = req.query;

        const result = await notificationService.getUserNotifications(userId, {
            page: parseInt(page),
            limit: parseInt(limit),
            unreadOnly: unread_only === 'true'
        });

        res.json(result);
    } catch (error) {
        console.error('Error obteniendo notificaciones:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
    }
});

// GET /api/notifications/:userId/unread-count - Solo el conteo (caché ultra rápido)
router.get('/:userId/unread-count', authenticate, async (req, res) => {
    try {
        const { userId } = req.params;
        const count = await notificationService.getUnreadCount(userId);
        res.json({ userId, unreadCount: count });
    } catch (error) {
        res.status(500).json({ error: 'Error interno' });
    }
});

// PATCH /api/notifications/:userId/:notificationId/read - Marcar como leída
router.patch('/:userId/:notificationId/read', authenticate, async (req, res) => {
    try {
        const { userId, notificationId } = req.params;
        const result = await notificationService.markAsRead(userId, notificationId);
        res.json(result);
    } catch (error) {
        res.status(500).json({ error: 'Error interno' });
    }
});

// PATCH /api/notifications/:userId/read-all - Marcar todas como leídas
router.patch('/:userId/read-all', authenticate, async (req, res) => {
    try {
        const { userId } = req.params;
        const result = await notificationService.markAllAsRead(userId);
        res.json(result);
    } catch (error) {
        res.status(500).json({ error: 'Error interno' });
    }
});

// DELETE /api/notifications/:userId/:notificationId - Eliminar notificación
router.delete('/:userId/:notificationId', authenticate, async (req, res) => {
    try {
        const { userId, notificationId } = req.params;
        const result = await notificationService.delete(userId, notificationId);
        res.json(result);
    } catch (error) {
        res.status(500).json({ error: 'Error interno' });
    }
});

// Health check
router.get('/health', (req, res) => {
    res.json({ status: 'ok', service: 'notifications', timestamp: new Date().toISOString() });
});

module.exports = router;