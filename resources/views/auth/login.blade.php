<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Tinahls Triad Agro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-tta { background-color: #2E7D32; }
        .bg-tta-dark { background-color: #1B5E20; }
        .text-tta { color: #2E7D32; }
        .login-bg {
            background-image: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.55)), url('{{ asset('images/cattle-bg.jpg') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen">

<div class="min-h-screen grid lg:grid-cols-2">
    {{-- Left side image --}}
    <div class="hidden lg:flex login-bg text-white items-center justify-center p-12">
        <div class="max-w-md">
            <img src="{{ asset('images/logo.png') }}" alt="TTA" class="h-16 mb-6 bg-white rounded-lg p-2">
            <h1 class="text-4xl font-bold leading-tight mb-4">Welcome to Tinahls Triad Agro</h1>
            <p class="text-lg text-gray-100 mb-6">
                Practical agricultural training, farm management and mentorship for Namibia.
            </p>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="bg-white/15 rounded-xl p-4">
                    <div class="text-2xl font-bold">7</div>
                    <div class="text-sm text-gray-200">Service Pillars</div>
                </div>
                <div class="bg-white/15 rounded-xl p-4">
                    <div class="text-2xl font-bold">15+</div>
                    <div class="text-sm text-gray-200">Online Courses</div>
                </div>
                <div class="bg-white/15 rounded-xl p-4">
                    <div class="text-2xl font-bold">N$</div>
                    <div class="text-sm text-gray-200">Local Pricing</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right side form --}}
    <div class="flex items-center justify-center p-6 sm:p-10 bg-gray-50">
        <div class="w-full max-w-md">
            <div class="text-center lg:text-left mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="TTA" class="h-14 mx-auto lg:mx-0 mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                <p class="text-gray-500 mt-1">Log in to Tinahls Triad Agro Learning Platform</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="bg-white rounded-2xl shadow-lg border p-6 sm:p-8 space-y-5">
                @csrf

                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                        Email or Student Number
                    </label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                           placeholder="TTA-2026-0001 or email">
                    @error('login')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-700">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-tta hover:underline">Forgot password?</a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-tta hover:bg-tta-dark text-white font-semibold py-3 rounded-xl transition">
                    Log in
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Students: use your <strong>Student Number</strong><br>
                Admin: use your email address
            </p>

            <p class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-sm text-tta hover:underline">← Back to website</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>