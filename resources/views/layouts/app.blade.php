<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Buku Tamu')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
      html, body { height: 100%; }
      body { font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
      .soft-border { border: 1px solid rgba(15,23,42,0.06); }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden">
                        <img src="{{ asset('assets/images/logo-banyumas.png') }}" alt="logo" class="w-full h-full object-cover">
                    </div>
                    <div class="w-8 h-8 rounded overflow-hidden">
                        <img src="{{ asset('assets/images/logo-telkom.png') }}" alt="logo2" class="w-full h-full object-contain">
                    </div>
                </div>

                <div class="flex items-center gap-6 ml-auto">
                    @auth
                    <nav class="hidden md:flex items-center gap-6 text-sm text-gray-600">
                        
                        <a class="flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'text-sky-600 font-medium' : 'hover:text-gray-800' }}" 
                           href="{{ route('dashboard') }}">
                            @if(request()->routeIs('dashboard'))
                            <span class="w-2 h-2 rounded-full bg-sky-600 inline-block"></span>
                            @endif
                            Dashboard
                        </a>
                        
                        <a class="flex items-center gap-2 {{ request()->routeIs('tamu.index') ? 'text-sky-600 font-medium' : 'hover:text-gray-800' }}" 
                           href="{{ route('tamu.index') }}">
                            @if(request()->routeIs('tamu.index'))
                            <span class="w-2 h-2 rounded-full bg-sky-600 inline-block"></span>
                            @endif
                            Daftar Tamu
                        </a>
                    </nav>

                  
                    <div class="relative">
                        <button id="profileBtn" class="flex items-center gap-2 border rounded-md px-3 py-1 bg-white hover:shadow-sm">
                            <img src="{{ asset('assets/icons/Icons_profile.svg') }}" class="h-4 w-4" alt="User Icon" />
                            <span class="text-sm text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="dropdownMenu" class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg hidden">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" 
                        class="text-sm bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700 transition">
                        Login
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-8xl mx-auto px-6 py-8 w-full">
        
        @yield('content')

    </main>

    <div class="h-12"></div>
    
    <script>
        const profileBtn = document.getElementById('profileBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (profileBtn) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation(); 
                dropdownMenu.classList.toggle('hidden');
            });
        }
        
        document.addEventListener('click', (event) => {
            if (dropdownMenu && !dropdownMenu.classList.contains('hidden') && !profileBtn.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', (e) => {
                e.stopPropagation(); 
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>