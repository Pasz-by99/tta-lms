<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Tinahls Triad Agro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-8">
        <h1 class="text-2xl font-bold text-center mb-2 text-green-800">Change Password</h1>
        <p class="text-center text-gray-600 mb-6">
            You must set a new password before continuing.
        </p>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-50 text-red-700 text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.force.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1">New Password</label>
                <input type="password" name="password" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600">
            </div>

            <button type="submit"
                    class="w-full bg-green-700 text-white font-semibold py-2 rounded-lg hover:bg-green-800">
                Update Password
            </button>
        </form>
    </div>
</body>
</html>