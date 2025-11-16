<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexora Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cover bg-center min-h-screen flex flex-col items-center justify-center py-12 px-4" 
      style="background-image: url('{{ asset('images/loginbg.jpeg') }}');">

    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm text-center">
        <h2 class="text-3xl font-bold mb-6 text-gray-900">Create Account</h2>

        <form id="signupForm">
            <div class="mb-4 text-left">
                <label for="name" class="block mb-2 font-medium text-sm text-gray-700">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4 text-left">
                <label for="email" class="block mb-2 font-medium text-sm text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4 text-left">
                <label for="password" class="block mb-2 font-medium text-sm text-gray-700">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6 text-left">
                <label for="password_confirmation" class="block mb-2 font-medium text-sm text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter your password" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" 
                    class="w-full bg-gray-900 text-white py-3 rounded-lg font-bold text-sm cursor-pointer 
                           transition-colors hover:bg-gray-700 shadow-lg">
                Sign Up
            </button>

            <div class="mt-6 text-sm">
                <span class="text-gray-600">Already have an account?</span>
                <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
            </div>

            <div class="mt-6 border-t pt-6">
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:underline">
                    &larr; Back to Home
                </a>
            </div>
        </form>
    </div>

    <footer class="text-center text-sm text-white/70 mt-8 mb-4">
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