<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForcePasswordChangeController extends Controller
{
    public function show()
    {
        return view('auth.force-password-change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        if ($user->role === 'admin' || $user->role === 'teacher') {
            return redirect('/admin')->with('status', 'Password updated successfully.');
        }

        return redirect('/student/dashboard')->with('status', 'Password updated successfully.');
    }
}