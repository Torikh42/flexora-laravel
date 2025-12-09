<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Flexora</title>
    @vite('resources/css/app.css')
</head>
<body>
    <script>
        // Check JWT token before loading admin panel
        const token = localStorage.getItem('auth_token');
        
        if (!token) {
            window.location.href = '/login';
        } else {
            // Verify token and get user info
            fetch('/api/auth/user-profile', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                    return Promise.reject();
                }
                return response.json();
            })
            .then(user => {
                if (user.role !== 'admin') {
                    alert('Access denied. Admin only.');
                    window.location.href = '/';
                }
                // If admin, append token to all admin links
                const currentUrl = new URL(window.location.href);
                if (!currentUrl.searchParams.has('token')) {
                    currentUrl.searchParams.set('token', token);
                    window.history.replaceState({}, '', currentUrl);
                }
            })
            .catch(() => {
                // Error handled above
            });
        }
    </script>
    
    <!-- Original admin layout content will load after token check -->
    @include('layouts.admin-content')
</body>
</html>
