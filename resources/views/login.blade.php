<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexora Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cover bg-center h-screen flex flex-col items-center justify-center" style="background-image: url('{{ asset('images/loginbg.jpeg') }}');">

    <div class="bg-white/70 backdrop-blur-sm p-10 rounded-2xl shadow-2xl w-full max-w-sm text-center">
        <h2 class="text-2xl font-bold mb-5">Welcome to Flexora</h2>

        <form id="loginForm">
            <div class="mb-4 text-left">
                <label for="email" class="block mb-1 font-bold text-sm">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required
                       class="w-full px-4 py-2 rounded-full border-none outline-none text-sm">
            </div>

            <div class="mb-2 text-left">
                <label for="password" class="block mb-1 font-bold text-sm">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required
                       class="w-full px-4 py-2 rounded-full border-none outline-none text-sm">
            </div>

            <div class="text-right mb-4">
                <a href="#" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full bg-amber-300 text-black py-3 rounded-full font-bold text-base cursor-pointer transition-transform transform hover:scale-105 shadow-lg">
                Login
            </button>

            <div class="mt-4 text-xs">
                Don't have an account? <a href="{{ url('/signup') }}" class="text-blue-600 hover:text-blue-800 hover:underline">Sign Up</a>
            </div>
            
            <div class="mt-2 text-xs">
                <a href="#" class="text-blue-600 hover:text-blue-800">Terms and Service</a>
            </div>

            <div class="mt-4">
                <a href="{{ url('/') }}" class="inline-block bg-pink-500 text-white px-5 py-2 rounded-full text-sm no-underline transition-colors hover:bg-pink-700">
                    ⬅ Back to Home
                </a>
            </div>
        </form>
    </div>

    <footer class="bg-pink-500 text-white text-center p-3 mt-5 w-full fixed bottom-0 left-0">
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

