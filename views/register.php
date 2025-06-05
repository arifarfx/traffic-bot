<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Traffic Bot</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
</head>
<body class="border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <h1>Traffic Bot</h1>
            </div>
            <div class="card card-md">
                <div class="card-body">
                    <h2 class="h2 text-center mb-4">Create new account</h2>
                    <form id="registerForm" method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" placeholder="your@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm password" required>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Create new account</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center text-muted mt-3">
                Already have account? <a href="login.php" tabindex="-1">Sign in</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = {
            username: formData.get('username'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirmPassword: formData.get('confirmPassword')
        };
        
        if (data.password !== data.confirmPassword) {
            alert('Passwords do not match');
            return;
        }
        
        try {
            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.status === 'success') {
                localStorage.setItem('token', result.token);
                window.location.href = '/dashboard.php';
            } else {
                alert(result.message || 'Registration failed');
            }
        } catch (error) {
            console.error('Registration error:', error);
            alert('An error occurred during registration');
        }
    });
    </script>
</body>
</html>