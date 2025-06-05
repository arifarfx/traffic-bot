const Traffic = require('../models/Traffic');
const { validationResult } = require('express-validator');
const moment = require('moment');

exports.getTrafficData = async (req, res) => {
    try {
        const { startDate, endDate } = req.query;
        let query = {};

        if (startDate && endDate) {
            query.createdAt = {
                $gte: moment(startDate).startOf('day'),
                $lte: moment(endDate).endOf('day')
            };
        }

        const trafficData = await Traffic.find(query)
            .sort({ createdAt: -1 })
            .limit(100);

        res.json({
            status: 'success',
            data: trafficData
        });
    } catch (error) {
        res.status(500).json({
            status: 'error',
            message: error.message
        });
    }
};

exports.startTraffic = async (req, res) => {
    try {
        const { targetUrl, requestsPerSecond, duration } = req.body;

        const traffic = new Traffic({
            targetUrl,
            requestsPerSecond,
            duration,
            status: 'running',
            startedBy: req.user.id
        });

        await traffic.save();

        // Start traffic generation logic here
        
        res.json({
            status: 'success',
            message: 'Traffic generation started',
            data: traffic
        });
    } catch (error) {
        res.status(500).json({
            status: 'error',
            message: error.message
        });
    }
};

exports.stopTraffic = async (req, res) => {
    try {
        const { id } = req.params;
        
        const traffic = await Traffic.findById(id);
        if (!traffic) {
            return res.status(404).json({
                status: 'error',
                message: 'Traffic session not found'
            });
        }

        traffic.status = 'stopped';
        traffic.stoppedAt = new Date();
        await traffic.save();

        res.json({
            status: 'success',
            message: 'Traffic generation stopped',
            data: traffic
        });
    } catch (error) {
        res.status(500).json({
            status: 'error',
            message: error.message
        });
    }
};

exports.getStats = async (req, res) => {
    try {
        const stats = await Traffic.aggregate([
            {
                $group: {
                    _id: '$status',
                    count: { $sum: 1 },
                    avgRequestsPerSecond: { $avg: '$requestsPerSecond' }
                }
            }
        ]);

        res.json({
            status: 'success',
            data: stats
        });
    } catch (error) {
        res.status(500).json({
            status: 'error',
            message: error.message
        });
    }
};