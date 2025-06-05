# Continuing from the previous settings.php file...

                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Security Settings</h3>
                </div>
                <div class="card-body">
                    <form id="passwordForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="currentPassword" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="newPassword" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirmPassword" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">API Settings</h3>
                </div>
                <div class="card-body">
                    <form id="apiSettingsForm">
                        <div class="mb-3">
                            <label class="form-label">Default Request Timeout (ms)</label>
                            <input type="number" class="form-control" name="requestTimeout" id="requestTimeout" required min="1000" max="30000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max Concurrent Requests</label>
                            <input type="number" class="form-control" name="maxConcurrentRequests" id="maxConcurrentRequests" required min="1" max="100">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save API Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadUserProfile();
    loadApiSettings();
    
    document.getElementById('profileForm').addEventListener('submit', handleProfileSubmit);
    document.getElementById('passwordForm').addEventListener('submit', handlePasswordSubmit);
    document.getElementById('apiSettingsForm').addEventListener('submit', handleApiSettingsSubmit);
});

async function loadUserProfile() {
    try {
        const response = await fetch('/api/auth/profile', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('username').value = data.user.username;
            document.getElementById('email').value = data.user.email;
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

async function loadApiSettings() {
    try {
        const response = await fetch('/api/settings', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('requestTimeout').value = data.settings.requestTimeout;
            document.getElementById('maxConcurrentRequests').value = data.settings.maxConcurrentRequests;
        }
    } catch (error) {
        console.error('Error loading API settings:', error);
    }
}

async function handleProfileSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        username: formData.get('username'),
        email: formData.get('email')
    };
    
    try {
        const response = await fetch('/api/auth/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            alert('Profile updated successfully');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
    }
}

async function handlePasswordSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        currentPassword: formData.get('currentPassword'),
        newPassword: formData.get('newPassword'),
        confirmPassword: formData.get('confirmPassword')
    };
    
    if (data.newPassword !== data.confirmPassword) {
        alert('New passwords do not match');
        return;
    }
    
    try {
        const response = await fetch('/api/auth/password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            alert('Password changed successfully');
            e.target.reset();
        }
    } catch (error) {
        console.error('Error changing password:', error);
    }
}

async function handleApiSettingsSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        requestTimeout: parseInt(formData.get('requestTimeout')),
        maxConcurrentRequests: parseInt(formData.get('maxConcurrentRequests'))
    };
    
    try {
        const response = await fetch('/api/settings', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            alert('API settings updated successfully');
        }
    } catch (error) {
        console.error('Error updating API settings:', error);
    }
}
</script>