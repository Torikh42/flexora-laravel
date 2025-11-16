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
            <div class="mb-4 text-left">
                <label for="email" class="block mb-2 font-medium text-sm text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required
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
        document.getElementById("loginForm").addEventListener("submit", async function(e){
            e.preventDefault();
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (!email || !password) {
                return alert("⚠️ Email and Password cannot be empty!");
            }

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('auth_token', data.access_token);
                    alert('✅ Login Successful!');
                    window.location.href = "{{ url('/') }}";
                } else {
                    alert(`❌ ${data.error || 'Login failed. Please check your credentials.'}`);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ An error occurred during login.');
            }
        });
    </script>
</body>
</html>