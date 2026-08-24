<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Clear any old intended URL (this was sending students to /admin)
        $request->session()->forget('url.intended');

        // First login must change password
        if ($user->must_change_password) {
            return redirect()->route('password.change');
        }

        // Role-based redirect (strict)
        if (in_array($user->role, ['admin', 'teacher'], true)) {
            return redirect('/admin');
        }

        // Everyone else (students) go to student dashboard
        return redirect('/student/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}