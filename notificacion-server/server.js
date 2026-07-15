const express = require('express');
const cors = require('cors');
const { createServer } = require('http');
const { Server } = require('socket.io');
const rateLimit = require('express-rate-limit');
require('dotenv').config();

const redisConfig = require('./config/redis');
const notificationService = require('./services/notificationService');
const notificationRoutes = require('./routes/notifications');

const app = express();
const httpServer = createServer(app);

// Socket.IO para notificaciones en tiempo real
const io = new Server(httpServer, {
    cors: {
        origin: process.env.CORS_ORIGIN || '*',
        methods: ['GET', 'POST']
    }
});

// Middleware
app.use(cors({
    origin: process.env.CORS_ORIGIN || '*',
    methods: ['GET', 'POST', 'PATCH', 'DELETE'],
    allowedHeaders: ['Content-Type', 'x-api-key']
}));
app.use(express.json({ limit: '10mb' }));

// Rate limiting
const limiter = rateLimit({
    windowMs: 1 * 60 * 1000, // 1 minuto
    max: 100,
    message: { error: 'Demasiadas solicitudes, intenta de nuevo en un minuto' }
});
app.use('/api/', limiter);

// Rutas
app.use('/api/notifications', notificationRoutes);

// Socket.IO - Conexiones en tiempo real
const connectedUsers = new Map();

io.on('connection', (socket) => {
    console.log('🔌 Cliente conectado:', socket.id);

    // El cliente se une con su userId
    socket.on('join', async (userId) => {
        if (!userId) return;
        
        connectedUsers.set(userId, socket.id);
        socket.join(`user:${userId}`);
        
        // Suscribirse al canal de Redis para este usuario
        await redisConfig.subscriber.subscribe(`user:${userId}:channel`, (message) => {
            const data = JSON.parse(message);
            // Emitir al usuario específico
            io.to(`user:${userId}`).emit('notification', data);
        });

        console.log(`👤 Usuario ${userId} conectado`);
    });

    socket.on('disconnect', () => {
        // Encontrar y remover usuario
        for (const [userId, socketId] of connectedUsers.entries()) {
            if (socketId === socket.id) {
                connectedUsers.delete(userId);
                redisConfig.subscriber.unsubscribe(`user:${userId}:channel`);
                console.log(`👤 Usuario ${userId} desconectado`);
                break;
            }
        }
        console.log('🔌 Cliente desconectado:', socket.id);
    });
});

// Inicialización
async function startServer() {
    try {
        // Conectar a Redis
        const redis = await redisConfig.connect();
        
        // Inicializar servicio
        notificationService.init(redis);

        const PORT = process.env.PORT || 3001;
        httpServer.listen(PORT, () => {
            console.log(`🚀 Servidor de Notificaciones Corriendo`);
        });

        // Manejo de cierre graceful
        process.on('SIGTERM', async () => {
            console.log('SIGTERM recibido, cerrando...');
            await redisConfig.disconnect();
            httpServer.close(() => process.exit(0));
        });

        process.on('SIGINT', async () => {
            console.log('SIGINT recibido, cerrando...');
            await redisConfig.disconnect();
            httpServer.close(() => process.exit(0));
        });

    } catch (error) {
        console.error('Error al iniciar servidor:', error);
        process.exit(1);
    }
}

startServer();