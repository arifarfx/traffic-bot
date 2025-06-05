// Authentication state management
let authToken = localStorage.getItem('token');

// Check if user is authenticated
function isAuthenticated() {
    return !!authToken;
}

// Redirect to login if not authenticated
function checkAuth() {
    if (!isAuthenticated() && !window.location.pathname.includes('login.php')) {
        window.location.href = '/login.php';
    }
}

// Logout function
function logout() {
    localStorage.removeItem('token');
    window.location.href = '/login.php';
}

// API request helper
async function apiRequest(endpoint, options = {}) {
    if (authToken) {
        options.headers = {
            ...options.headers,
            'Authorization': `Bearer ${authToken}`
        };
    }
    
    try {
        const response = await fetch(`/api/${endpoint}`, options);
        const data = await response.json();
        
        if (response.status === 401) {
            logout();
            return;
        }
        
        return data;
    } catch (error) {
        console.error(`API Request Error (${endpoint}):`, error);
        throw error;
    }
}

// Format date helper
function formatDate(date) {
    return new Date(date).toLocaleString();
}

// Format number helper
function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}

// Add event listeners when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check authentication on every page load
    checkAuth();
    
    // Setup logout functionality
    const logoutButton = document.getElementById('logout');
    if (logoutButton) {
        logoutButton.addEventListener('click', (e) => {
            e.preventDefault();
            logout();
        });
    }
    
    // Setup global AJAX error handling
    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled promise rejection:', event.reason);
    });
});