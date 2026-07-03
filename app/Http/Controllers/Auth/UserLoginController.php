<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Block login before attempting auth if admin has disabled this account.
        $user = User::where('email', $request->input('email'))->first();
        if ($user && $user->status === 'blocked') {
            return back()
                ->withErrors(['email' => 'Your account has been disabled. Please contact admin.'])
                ->withInput($request->only('email'));
        }

        if (auth()->guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('user.dashboard'));
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
