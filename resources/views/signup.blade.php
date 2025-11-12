<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexora Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cover bg-center h-screen flex flex-col items-center justify-center py-12" style="background-image: url('{{ asset('images/loginbg.jpeg') }}');">

    <div class="bg-white/80 backdrop-blur-sm p-8 rounded-xl shadow-2xl w-full max-w-sm text-center">
        <h2 class="text-xl font-bold mb-4">Create Your Account</h2>

        <form id="signupForm">
            <div class="mb-3 text-left">
                <label for="name" class="block mb-1 font-bold text-xs">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required
                       class="w-full px-3 py-2 rounded-full border-none outline-none text-xs">
            </div>

            <div class="mb-3 text-left">
                <label for="email" class="block mb-1 font-bold text-xs">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required
                       class="w-full px-3 py-2 rounded-full border-none outline-none text-xs">
            </div>

            <div class="mb-3 text-left">
                <label for="password" class="block mb-1 font-bold text-xs">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required
                       class="w-full px-3 py-2 rounded-full border-none outline-none text-xs">
            </div>

            <div class="mb-4 text-left">
                <label for="password_confirmation" class="block mb-1 font-bold text-xs">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter your password" required
                       class="w-full px-3 py-2 rounded-full border-none outline-none text-xs">
            </div>

            <button type="submit" class="w-full bg-amber-300 text-black py-2 rounded-full font-bold text-sm cursor-pointer transition-transform transform hover:scale-105 shadow-lg">
                Sign Up
            </button>

            <div class="mt-3 text-xs">
                Already have an account? <a href="{{ url('/login') }}" class="text-blue-600 hover:text-blue-800 hover:underline">Login</a>
            </div>

            <div class="mt-3">
                <a href="{{ url('/') }}" class="inline-block bg-pink-500 text-white px-4 py-2 rounded-full text-xs no-underline transition-colors hover:bg-pink-700">
                    ⬅ Back to Home
                </a>
            </div>
        </form>
    </div>

    <footer class="bg-pink-500 text-white text-center p-3 mt-5 w-full fixed bottom-0 left-0">
        &copy; 2025 Flexora Studio
    </footer>

    <script>
        document.getElementById("signupForm").addEventListener("submit", async function(e){
            e.preventDefault();
            
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const password_confirmation = document.getElementById("password_confirmation").value;

            if (!name || !email || !password) {
                return alert("⚠️ All fields must be filled!");
            }
            
            if (password !== password_confirmation) {
                return alert("⚠️ Password and Confirm Password do not match!");
            }

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('✅ Registration successful! Please log in.');
                    window.location.href = "{{ url('/login') }}";
                } else {
                    // Handle errors returned by the server
                    let errorMessage = 'Registration failed. Please try again.';
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join('\n');
                    } else if (data.message) {
                        errorMessage = data.message;
                    }
                    alert(`❌ ${errorMessage}`);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ An error occurred during registration.');
            }
        });
    </script>
</body>
</html>

