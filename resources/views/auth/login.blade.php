<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Tinahls Triad Agro</title>
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
        .focus\:ring-tta:focus { --tw-ring-color: var(--tta-green); }
        .focus\:border-tta:focus { border-color: var(--tta-green); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        {{-- Logo + Title --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Tinahls Triad Agro" class="h-20 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Tinahls Triad Agro</h1>
            <p class="text-gray-600 mt-1">Learning Platform</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-center mb-6">Sign in to your account</h2>

            {{-- Session status --}}
            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Login field --}}
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700 mb-1">
                        Email or Student Number
                    </label>
                    <input
                        id="login"
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-600 focus:ring focus:ring-green-200 px-3 py-2.5 border"
                        placeholder="TTA-2026-0001 or email"
                    >
                    @error('login')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-600 focus:ring focus:ring-green-200 px-3 py-2.5 border"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-700 focus:ring-green-600">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-tta hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-tta hover:bg-tta-dark text-white font-semibold py-2.5 rounded-lg transition">
                    Log in
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Students: use your <strong>Student Number</strong><br>
            Admin: use your email address
        </p>

        <p class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-sm text-tta hover:underline">← Back to website</a>
        </p>
    </div>

</body>
</html>