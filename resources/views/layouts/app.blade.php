<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tinahls Triad Agro') - Learning Platform</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <style>
        :root {
            --tta-green: #2E7D32;
            --tta-dark: #1B5E20;
        }
        .bg-tta { background-color: var(--tta-green); }
        .bg-tta-dark { background-color: var(--tta-dark); }
        .text-tta { color: var(--tta-green); }
        .hover\:bg-tta-dark:hover { background-color: var(--tta-dark); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="TTA Logo" class="h-10 w-auto">
                <div class="hidden sm:block">
                    <div class="font-bold text-tta leading-tight">Tinahls Triad Agro</div>
                    <div class="text-xs text-gray-500">Learning Platform</div>
                </div>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ url('/') }}" class="hover:text-tta font-medium">Home</a>
                <a href="{{ url('/courses') }}" class="hover:text-tta font-medium">Courses</a>
                <a href="{{ url('/about') }}" class="hover:text-tta font-medium">About</a>
                <a href="{{ url('/contact') }}" class="hover:text-tta font-medium">Contact</a>

                @auth
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                        <a href="{{ url('/admin') }}" class="bg-tta text-white px-4 py-2 rounded-lg font-medium">Admin Panel</a>
                    @else
                        <a href="{{ url('/student/dashboard') }}" class="hover:text-tta font-medium">My Learning</a>
                        <a href="{{ url('/student/calendars') }}" class="hover:text-tta font-medium">Farm Calendars</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-tta text-white px-5 py-2 rounded-lg font-medium">Login</a>
                @endauth
            </div>

            {{-- Mobile menu button --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg border text-gray-700">
                ☰
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="md:hidden hidden border-t bg-white">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ url('/') }}" class="block py-2 font-medium">Home</a>
            <a href="{{ url('/courses') }}" class="block py-2 font-medium">Courses</a>
            <a href="{{ url('/about') }}" class="block py-2 font-medium">About</a>
            <a href="{{ url('/contact') }}" class="block py-2 font-medium">Contact</a>

            @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'teacher')
                    <a href="{{ url('/admin') }}" class="block py-2 font-medium text-tta">Admin Panel</a>
                @else
                    <a href="{{ url('/student/dashboard') }}" class="block py-2 font-medium">My Learning</a>
                    <a href="{{ url('/student/calendars') }}" class="block py-2 font-medium">Farm Calendars</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-2 text-red-600 font-medium">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 font-medium text-tta">Login</a>
            @endauth
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="bg-tta-dark text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <img src="{{ asset('images/logo.png') }}" alt="TTA" class="h-14 mb-4 bg-white rounded p-1">
            <p class="text-sm text-gray-300">
                Empowering farmers and agripreneurs through practical agricultural training in Namibia.
            </p>
        </div>
        <div>
            <h3 class="font-bold text-lg mb-4">Quick Links</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
                <li><a href="{{ url('/courses') }}" class="hover:underline">Courses</a></li>
                <li><a href="{{ url('/about') }}" class="hover:underline">About Us</a></li>
                <li><a href="{{ url('/contact') }}" class="hover:underline">Contact</a></li>
            </ul>
        </div>
        <div>
            <h3 class="font-bold text-lg mb-4">Contact Us</h3>
            <ul class="space-y-2 text-sm text-gray-300">
                <li>📞 +264 81 318 8489</li>
                <li>📞 +264 81 705 0652</li>
                <li>✉️ tinahsagrotriad@gmail.com</li>
                <li>📍 Namibia</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-green-800 text-center py-4 text-sm text-gray-400">
        © {{ date('Y') }} Tinahls Triad Agro CC. All rights reserved.
    </div>
</footer>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

</body>
</html>