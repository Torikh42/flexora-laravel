<header class="bg-white/95 shadow-md sticky top-0 z-50">
    <div class="container mx-auto flex justify-between items-center p-4 px-6">
        <div class="text-2xl font-bold text-stone-700">
            <a href="{{ route('home') }}">Flexora</a>
        </div>
        <nav class="hidden md:flex space-x-8">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Home</a>
            <a href="{{ route('classes.index') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Classes</a>
            <a href="{{ route('memberships.index') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Membership</a>
            <a href="#" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Contact</a>
        </nav>
        <div id="authSection" class="flex items-center space-x-4">
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", async function() {
      const authSection = document.getElementById("authSection");
      const token = localStorage.getItem('auth_token');

      console.log('Navbar script loaded. Token:', token);

      const renderLoggedOut = () => {
        console.log('Rendering logged out state.');
        authSection.innerHTML = `
          <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-800 font-medium transition-colors">Log in</a>
          <a href="{{ route('signup') }}" class="px-4 py-2 bg-stone-700 text-white rounded-lg hover:bg-stone-800 transition-colors">Sign Up</a>
        `;
      };

      if (!token) {
        return renderLoggedOut();
      }

      console.log('Token found, attempting to fetch user profile.');
      try {
        const response = await fetch('/api/auth/user-profile', {
          method: 'GET',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
          }
        });

        console.log('User profile response status:', response.status);

        if (response.ok) {
          const user = await response.json();
          console.log('User profile fetched successfully:', user);

          if (user) {
            const firstName = user.name.split(' ')[0];
            authSection.innerHTML = `
              <span class="text-gray-700">Halo, <b class="font-semibold">${firstName}</b></span>
              <button id="logoutBtn" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">Logout</button>
            `;

            document.getElementById("logoutBtn").addEventListener("click", async () => {
              localStorage.removeItem('auth_token');
              window.location.href = '/';
            });
          } else {
            console.error('Token was valid, but no user was returned.');
            renderLoggedOut();
          }
        } else {
          console.error('Failed to fetch user profile. Status:', response.status);
          localStorage.removeItem('auth_token');
          renderLoggedOut();
        }
      } catch (error) {
        console.error('Error fetching user profile:', error);
        renderLoggedOut();
      }
    });
</script>
