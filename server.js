const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const dotenv = require('dotenv');
const path = require('path');
const cookieParser = require('cookie-parser');
const { errorHandler } = require('./src/middleware/errorHandler');

// Load environment variables
dotenv.config();

// Initialize express
const app = express();

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());

// Serve static files
app.use(express.static('public'));
app.use('/views', express.static('views'));

// Database connection
mongoose.connect(process.env.MONGODB_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true
})
.then(() => console.log('MongoDB Connected'))
.catch(err => console.error('MongoDB connection error:', err));

// Routes
const authRoutes = require('./src/routes/auth');
const trafficRoutes = require('./src/routes/traffic');
const settingsRoutes = require('./src/routes/settings');

app.use('/api/auth', authRoutes);
app.use('/api/traffic', trafficRoutes);
app.use('/api/settings', settingsRoutes);

// Error handling
app.use(errorHandler);

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});