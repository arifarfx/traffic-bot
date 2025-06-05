require('dotenv').config();

module.exports = {
    port: process.env.PORT || 3000,
    mongoUri: process.env.MONGODB_URI || 'mongodb://localhost:27017/traffic_bot',
    jwtSecret: process.env.JWT_SECRET || 'your-secret-key',
    environment: process.env.NODE_ENV || 'development',
    defaultSettings: {
        requestTimeout: 5000,
        maxConcurrentRequests: 10,
        defaultDuration: 300, // 5 minutes
        maxRequestsPerSecond: 100
    },
    cors: {
        origin: '*',
        methods: ['GET', 'POST', 'PUT', 'DELETE'],
        allowedHeaders: ['Content-Type', 'Authorization']
    }
};