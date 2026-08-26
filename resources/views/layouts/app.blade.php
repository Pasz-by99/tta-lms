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
        :root { --tta-green:#2E7D32; --tta-dark:#1B5E20; }
        .bg-tta{background-color:var(--tta-green)}
        .bg-tta-dark{background-color:var(--tta-dark)}
        .text-tta{color:var(--tta-green)}
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16 items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" class="h-10 w-auto" alt="TTA">
                <div class="leading-tight">
                    <div class="font-bold text-tta text-sm sm:text-base">Tinahls Triad Agro</div>
                </div>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex items-center gap-5">
                <a href="{{ url('/') }}" class="hover:text-tta font-medium">Home</a>
                <a href="{{ url('/courses') }}" class="hover:text-tta font-medium">Courses</a>
                <a href="{{ url('/about') }}" class="hover:text-tta font-medium">About</a>
                <a href="{{ url('/contact') }}" class="hover:text-tta font-medium">Contact</a>

                @auth
                    @if(in_array(auth()->user()->role ?? '', ['admin', 'teacher']))
                        <a href="{{ url('/admin') }}" class="bg-tta text-white px-4 py-2 rounded-lg">Admin</a>
                    @else
                        <a href="{{ url('/student/dashboard') }}" class="hover:text-tta font-medium">My Learning</a>
                        <a href="{{ url('/student/calendars') }}" class="hover:text-tta font-medium">Farm Calendars</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-tta text-white px-4 py-2 rounded-lg font-medium">Login</a>
                @endauth
            </div>

            {{-- Mobile buttons --}}
            <div class="md:hidden flex items-center gap-2">
                @auth
                    @if(in_array(auth()->user()->role ?? '', ['admin', 'teacher']))
                        <a href="{{ url('/admin') }}" class="bg-tta text-white text-sm px-3 py-2 rounded-lg font-semibold">Admin</a>
                    @else
                        <a href="{{ url('/student/dashboard') }}" class="text-tta text-sm font-semibold">My Learning</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white text-sm px-3 py-2 rounded-lg font-semibold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-tta text-white text-sm px-3 py-2 rounded-lg font-semibold">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="bg-tta-dark text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <img src="{{ asset('images/logo.png') }}" class="h-12 mb-3 bg-white rounded p-1" alt="TTA">
            <p class="text-sm text-gray-300">Practical agricultural training for Namibia.</p>
        </div>
        <div>
            <h3 class="font-bold mb-3">Quick Links</h3>
            <div class="space-y-2 text-sm">
                <a href="{{ url('/') }}" class="block hover:underline">Home</a>
                <a href="{{ url('/courses') }}" class="block hover:underline">Courses</a>
                <a href="{{ url('/contact') }}" class="block hover:underline">Contact</a>
                @auth
                    <a href="{{ url('/student/dashboard') }}" class="block hover:underline">My Learning</a>
                @else
                    <a href="{{ route('login') }}" class="block hover:underline">Login</a>
                @endauth
            </div>
        </div>
        <div>
            <h3 class="font-bold mb-3">Contact</h3>
            <div class="text-sm text-gray-300 space-y-1">
                <div>+264 81 318 8489</div>
                <div>+264 81 705 0652</div>
                <div>tinahsagrotriad@gmail.com</div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>