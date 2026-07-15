const { createClient } = require('redis');
require('dotenv').config();

class RedisConfig {
    constructor() {
        this.publisher = null;
        this.subscriber = null;
        this.client = null;
    }

    async connect() {
        const redisOptions = {
            socket: {
                host: process.env.REDIS_HOST || 'localhost',
                port: process.env.REDIS_PORT || 6379,
                reconnectStrategy: (retries) => {
                    console.log(`Intento de reconexión Redis #${retries}`);
                    return Math.min(retries * 100, 3000);
                }
            }
        };

        if (process.env.REDIS_PASSWORD) {
            redisOptions.password = process.env.REDIS_PASSWORD;
        }

        // Cliente principal para operaciones CRUD
        this.client = createClient(redisOptions);
        
        // Publisher para Pub/Sub
        this.publisher = createClient(redisOptions);
        
        // Subscriber para Pub/Sub
        this.subscriber = createClient(redisOptions);

        this.client.on('error', (err) => console.error('Redis Client Error:', err));
        this.client.on('connect', () => console.log('✓ Redis Client conectado'));

        await this.client.connect();
        await this.publisher.connect();
        await this.subscriber.connect();

        return {
            client: this.client,
            publisher: this.publisher,
            subscriber: this.subscriber
        };
    }

    async disconnect() {
        await this.client?.disconnect();
        await this.publisher?.disconnect();
        await this.subscriber?.disconnect();
    }
}

module.exports = new RedisConfig();