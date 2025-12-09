<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexora Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cover bg-center min-h-screen flex flex-col items-center justify-center py-12 px-4" 
      style="background-image: url('{{ asset('images/loginbg.jpeg') }}');">

    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm text-center">
        <h2 class="text-3xl font-bold mb-6 text-gray-900">Welcome to Flexora</h2>

        <form id="loginForm">
            @csrf
            
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <div class="mb-4 text-left">
                <label for="email" class="block mb-2 font-medium text-sm text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6 text-left">
                <label for="password" class="block mb-2 font-medium text-sm text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" 
                    class="w-full bg-gray-900 text-white py-3 rounded-lg font-bold text-sm cursor-pointer 
                           transition-colors hover:bg-gray-700 shadow-lg">
                Login
            </button>

            <div class="mt-6 text-sm">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ url('/signup') }}" class="text-blue-600 hover:underline">Sign Up</a>
            </div>

            <div class="mt-6 border-t pt-6">
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:underline">
                    &larr; Back to Home
                </a>
            </div>
        </form>
    </div>

    <footer class="text-center text-sm text-white/70 mt-8">
        &copy; 2025 Flexora Studio
    </footer>

    <script>
        // Helper function to set cookie
        function setCookie(name, value, days) {
            const expires = new Date();
            expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
            document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
        }

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Store JWT token in localStorage AND cookie
                    localStorage.setItem('auth_token', data.access_token);
                    setCookie('auth_token', data.access_token, 7); // 7 days
                    
                    // Check user role and redirect accordingly
                    if (data.user && data.user.role === 'admin') {
                        // Redirect admin to admin dashboard (no need for token in URL)
                        window.location.href = '/admin/dashboard';
                    } else {
                        window.location.href = '/dashboard';
                    }
                } else {
                    alert(`❌ Login failed: ${data.error || 'Please check your credentials'}`);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ An error occurred during login');
            }
        });
    </script>
</body>
</html>