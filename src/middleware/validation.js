const { check } = require('express-validator');

exports.authValidation = [
    check('email', 'Please include a valid email').isEmail(),
    check('password', 'Password must be 6 or more characters').isLength({ min: 6 })
];

exports.trafficValidation = [
    check('targetUrl', 'Target URL is required').isURL(),
    check('requestsPerSecond', 'Requests per second must be a number').isNumeric(),
    check('duration', 'Duration must be a number').isNumeric()
];