const mongoose = require('mongoose');

const trafficSchema = new mongoose.Schema({
    targetUrl: {
        type: String,
        required: true
    },
    requestsPerSecond: {
        type: Number,
        required: true
    },
    duration: {
        type: Number,
        required: true
    },
    status: {
        type: String,
        enum: ['running', 'stopped', 'completed', 'failed'],
        default: 'running'
    },
    startedBy: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        required: true
    },
    startedAt: {
        type: Date,
        default: Date.now
    },
    stoppedAt: {
        type: Date
    },
    stats: {
        successfulRequests: {
            type: Number,
            default: 0
        },
        failedRequests: {
            type: Number,
            default: 0
        },
        averageResponseTime: {
            type: Number,
            default: 0
        }
    }
}, {
    timestamps: true
});

module.exports = mongoose.model('Traffic', trafficSchema);