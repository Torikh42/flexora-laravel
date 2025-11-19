<header class="bg-white/95 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center h-16">
            <div class="text-2xl font-bold text-stone-700">
                <a href="{{ route('home') }}">Flexora</a>
            </div>

            <nav class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Home</a>
                <a href="{{ route('studio_classes.index') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Classes</a>
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Dashboard</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Contact</a>
            </nav>

            <div id="authSectionDesktop" class="hidden md:flex items-center space-x-4">
                </div>

            <div class="md:hidden flex items-center">
                <button id="mobileMenuBtn" class="text-gray-700 hover:text-amber-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg absolute w-full left-0 top-16">
        <div class="px-4 py-4 space-y-3 flex flex-col">
            <a href="{{ route('home') }}" class="block text-gray-700 hover:text-amber-800 font-medium py-2">Home</a>
            <a href="{{ route('studio_classes.index') }}" class="block text-gray-700 hover:text-amber-800 font-medium py-2">Classes</a>
            <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-amber-800 font-medium py-2">Dashboard</a>
            <a href="{{ route('contact') }}" class="block text-gray-700 hover:text-amber-800 font-medium py-2">Contact</a>
            
            <hr class="border-gray-200 my-2">
            
            <div id="authSectionMobile" class="flex flex-col space-y-3">
                </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", async function() {
        // --- Mobile Menu Toggle Logic ---
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // --- Auth Logic ---
        setTimeout(async () => {
            const authSectionDesktop = document.getElementById("authSectionDesktop");
            const authSectionMobile = document.getElementById("authSectionMobile");
            
            let token = localStorage.getItem('auth_token');
            console.log('Navbar script loaded. Token exists:', !!token);

            // Helper to attach logout listener safely
            const attachLogoutListener = (btnId) => {
                const btn = document.getElementById(btnId);
                if (btn) {
                    btn.addEventListener("click", handleLogout);
                }
            };

            // Shared Logout Function
            const handleLogout = async () => {
                try {
                    const response = await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });
                    // Always clear token regardless of server response
                    localStorage.removeItem('auth_token');
                    window.location.href = '/';
                } catch (error) {
                    console.error('Error during logout:', error);
                    localStorage.removeItem('auth_token');
                    window.location.href = '/';
                }
            };

            // Function to render "Logged Out" state
            const renderLoggedOut = () => {
                console.log('Rendering logged out state.');
                
                // Desktop View
                authSectionDesktop.innerHTML = `
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Log in</a>
                    <a href="{{ route('signup') }}" class="px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-800 transition-colors">Sign Up</a>
                `;

                // Mobile View (Stacked buttons)
                authSectionMobile.innerHTML = `
                    <a href="{{ route('login') }}" class="block text-center text-gray-700 font-medium py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Log in</a>
                    <a href="{{ route('signup') }}" class="block text-center px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-800">Sign Up</a>
                `;
            };

            // Function to render "Logged In" state
            const renderLoggedIn = (user) => {
                const firstName = user.name.split(' ')[0];

                // Desktop View
                authSectionDesktop.innerHTML = `
                    <span class="text-gray-700">Halo, <b class="font-semibold">${firstName}</b></span>
                    <button id="logoutBtnDesktop" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors text-sm">Logout</button>
                `;

                // Mobile View
                authSectionMobile.innerHTML = `
                     <div class="text-gray-700 font-medium py-2">Halo, <b class="font-semibold text-amber-800">${firstName}</b></div>
                     <button id="logoutBtnMobile" class="w-full text-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">Logout</button>
                `;

                // Attach listeners to the newly created buttons
                attachLogoutListener('logoutBtnDesktop');
                attachLogoutListener('logoutBtnMobile');
            };

            const refreshToken = async () => {
                try {
                    const response = await fetch('/api/auth/refresh', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        token = data.access_token;
                        localStorage.setItem('auth_token', token);
                        return true;
                    }
                    return false;
                } catch (error) {
                    return false;
                }
            };

            const fetchUserProfile = async () => {
                try {
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 5000);

                    const response = await fetch('/api/auth/user-profile', {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        signal: controller.signal
                    });

                    clearTimeout(timeoutId);

                    if (response.ok) {
                        const user = await response.json();
                        renderLoggedIn(user);
                    } else {
                        // Token invalid or expired
                        localStorage.removeItem('auth_token');
                        renderLoggedOut();
                    }
                } catch (error) {
                    console.error('Error fetching profile:', error);
                    renderLoggedOut();
                }
            };

            // --- Main Execution Flow ---
            if (!token) {
                renderLoggedOut();
            } else {
                await fetchUserProfile();
                
                // Auto-refresh logic
                setInterval(async () => {
                    if (localStorage.getItem('auth_token')) {
                        await refreshToken();
                    }
                }, 600000);
            }

        }, 100);
    });
</script>