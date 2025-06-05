const express = require('express');
const router = express.Router();
const trafficController = require('../controllers/trafficController');
const auth = require('../middleware/auth');
const { trafficValidation } = require('../middleware/validation');

router.use(auth);

router.get('/data', trafficController.getTrafficData);
router.post('/start', trafficValidation, trafficController.startTraffic);
router.post('/stop/:id', trafficController.stopTraffic);
router.get('/stats', trafficController.getStats);

module.exports = router;