<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Traffic Bot</title>
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
                    <h2 class="h2 text-center mb-4">Login to your account</h2>
                    <form id="loginForm" method="post">
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" name="email" placeholder="your@email.com" required autofocus>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Your password" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember">
                                <span class="form-check-label">Remember me</span>
                            </label>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center text-muted mt-3">
                Don't have account yet? <a href="register.php" tabindex="-1">Sign up</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = {
            email: formData.get('email'),
            password: formData.get('password')
        };
        
        try {
            const response = await fetch('/api/auth/login', {
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
                alert(result.message || 'Login failed');
            }
        } catch (error) {
            console.error('Login error:', error);
            alert('An error occurred during login');
        }
    });
    </script>
</body>
</html>