<header class="bg-white/95 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex justify-between items-center h-16">
            <div class="text-2xl font-bold text-stone-700">
                <a href="{{ route('home') }}">Flexora</a>
            </div>

            <nav class="hidden md:flex space-x-8 items-center">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Home</a>
                <a href="{{ route('studio_classes.index') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Classes</a>
                <a id="dashboardLink" href="#" class="text-gray-700 hover:text-amber-800 font-medium transition-colors hidden">Dashboard</a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Contact</a>
            </nav>

            <div id="authSection" class="hidden md:flex items-center space-x-4">
                <!-- Will be populated by JavaScript -->
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
            <a id="dashboardLinkMobile" href="#" class="block text-gray-700 hover:text-amber-800 font-medium py-2 hidden">Dashboard</a>
            <a href="{{ route('contact') }}" class="block text-gray-700 hover:text-amber-800 font-medium py-2">Contact</a>
            
            <hr class="border-gray-200 my-2">
            
            <div id="authSectionMobile" class="flex flex-col space-y-3">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", async function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const authSection = document.getElementById('authSection');
        const authSectionMobile = document.getElementById('authSectionMobile');
        const dashboardLink = document.getElementById('dashboardLink');
        const dashboardLinkMobile = document.getElementById('dashboardLinkMobile');

        // Mobile menu toggle
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Check authentication
        const token = localStorage.getItem('auth_token');
        
        if (!token) {
            // Not logged in
            authSection.innerHTML = `
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Log in</a>
                <a href="{{ route('signup') }}" class="px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-800 transition-colors">Sign Up</a>
            `;
            authSectionMobile.innerHTML = `
                <a href="{{ route('login') }}" class="block text-center text-gray-700 font-medium py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Log in</a>
                <a href="{{ route('signup') }}" class="block text-center px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-800">Sign Up</a>
            `;
        } else {
            // Logged in - fetch user profile
            try {
                const response = await fetch('/api/auth/user-profile', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const user = await response.json();
                    const firstName = user.name.split(' ')[0];
                    const isAdmin = user.role === 'admin';
                    const dashboardUrl = isAdmin ? `/admin/dashboard?token=${token}` : '/dashboard';

                    // Show dashboard link
                    dashboardLink.href = dashboardUrl;
                    dashboardLink.classList.remove('hidden');
                    dashboardLinkMobile.href = dashboardUrl;
                    dashboardLinkMobile.classList.remove('hidden');

                    // Desktop auth section
                    authSection.innerHTML = `
                        ${isAdmin ? `<a href="/admin/dashboard?token=${token}" class="text-amber-800 hover:text-amber-900 font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Admin
                        </a>` : ''}
                        <span class="text-gray-700">Halo, <b class="font-semibold">${firstName}</b></span>
                        <button onclick="handleLogout()" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors text-sm">Logout</button>
                    `;

                    // Mobile auth section
                    authSectionMobile.innerHTML = `
                        ${isAdmin ? `<a href="/admin/dashboard?token=${token}" class="block text-center text-amber-800 font-semibold py-2 border border-amber-300 rounded-lg hover:bg-amber-50 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Admin Panel
                        </a>` : ''}
                        <div class="text-gray-700 font-medium py-2">Halo, <b class="font-semibold text-amber-800">${firstName}</b></div>
                        <button onclick="handleLogout()" class="w-full text-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">Logout</button>
                    `;
                } else {
                    // Token invalid
                    localStorage.removeItem('auth_token');
                    location.reload();
                }
            } catch (error) {
                console.error('Error fetching user profile:', error);
                localStorage.removeItem('auth_token');
                location.reload();
            }
        }
    });

    // Global logout function
    function handleLogout() {
        const token = localStorage.getItem('auth_token');
        
        // Delete cookie
        document.cookie = 'auth_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        
        if (token) {
            fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            }).finally(() => {
                localStorage.removeItem('auth_token');
                window.location.href = '/';
            });
        } else {
            localStorage.removeItem('auth_token');
            window.location.href = '/';
        }
    }
</script>