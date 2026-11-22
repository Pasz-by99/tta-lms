<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tinahls Triad Agro Learning Platform')</title>

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2E7D32">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="TTA Learning">
    <link rel="apple-touch-icon" href="/images/icons/icon-192.png">

    {{-- Tailwind CDN (safe for Render) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tta: '#2E7D32',
                        'tta-dark': '#1B5E20',
                    }
                }
            }
        }
    </script>
    <style>
        .bg-tta { background-color: #2E7D32; }
        .text-tta { color: #2E7D32; }
        .hover\:bg-tta-dark:hover { background-color: #1B5E20; }
        .hover\:text-tta:hover { color: #2E7D32; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="TTA Logo" class="h-12 w-auto">
                    <div class="hidden sm:block">
                        <div class="font-bold text-lg text-tta leading-tight">Tinahls Triad Agro</div>
                        <div class="text-xs text-gray-500">Learning Platform</div>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="hover:text-tta font-medium">Home</a>
                    <a href="{{ route('courses.index') }}" class="hover:text-tta font-medium">Courses</a>
                    <a href="{{ route('about') }}" class="hover:text-tta font-medium">About</a>
                    <a href="{{ route('contact') }}" class="hover:text-tta font-medium">Contact</a>

                    @auth
                        @if(in_array(auth()->user()->role ?? '', ['admin', 'teacher']))
                            <a href="/admin" class="hover:text-tta font-medium">Admin</a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="hover:text-tta font-medium">My Learning</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-tta text-white px-5 py-2 rounded-lg hover:bg-tta-dark transition font-medium">
                            Login
                        </a>
                    @endauth
                </div>

                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="p-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('home') }}" class="block py-2 hover:text-tta font-medium">Home</a>
                <a href="{{ route('courses.index') }}" class="block py-2 hover:text-tta font-medium">Courses</a>
                <a href="{{ route('about') }}" class="block py-2 hover:text-tta font-medium">About</a>
                <a href="{{ route('contact') }}" class="block py-2 hover:text-tta font-medium">Contact</a>

                @auth
                    @if(in_array(auth()->user()->role ?? '', ['admin', 'teacher']))
                        <a href="/admin" class="block py-2 hover:text-tta font-medium">Admin</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="block py-2 hover:text-tta font-medium">My Learning</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 text-red-600 font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 bg-tta text-white text-center rounded-lg font-medium">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-tta text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid md:grid-cols-3 gap-8">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="TTA" class="h-14 mb-4 bg-white rounded p-1">
                <p class="text-sm text-gray-200">
                    Empowering farmers and agripreneurs through practical agricultural training in Namibia.
                </p>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:underline">Courses</a></li>
                    <li><a href="{{ route('about') }}" class="hover:underline">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-4">Contact Us</h3>
                <ul class="space-y-2 text-sm text-gray-200">
                    <li>📞 +264 81 318 8489</li>
                    <li>📞 +264 81 705 0652</li>
                    <li>✉️ tinahsagrotriad@gmail.com</li>
                    <li>📍 Namibia</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-green-800 text-center py-4 text-sm text-gray-300">
            © {{ date('Y') }} Tinahls Triad Agro CC. All rights reserved.
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js')
                    .then(function (reg) { console.log('SW registered', reg.scope); })
                    .catch(function (err) { console.log('SW failed', err); });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
