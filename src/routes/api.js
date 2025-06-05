const express = require('express');
const router = express.Router();
const trafficController = require('../controllers/trafficController');
const authMiddleware = require('../middleware/auth');

// Protected routes
router.use(authMiddleware);

// Traffic routes
router.get('/traffic', trafficController.getTrafficData);
router.post('/traffic/start', trafficController.startTraffic);
router.post('/traffic/stop', trafficController.stopTraffic);
router.get('/traffic/stats', trafficController.getStats);
router.get('/traffic/logs', trafficController.getLogs);

module.exports = router;