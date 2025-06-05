const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { authValidation } = require('../middleware/validation');

router.post('/register', authValidation, authController.register);
router.post('/login', authController.login);

module.exports = router;