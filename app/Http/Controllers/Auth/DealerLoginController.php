<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealerLoginController extends Controller
{
    protected $redirectTo = '/dealer/dashboard';

    public function showLoginForm()
    {
        return view('auth.dealer-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if dealer exists and is not blocked/inactive before attempting login
        $dealer = Dealer::where('email', $request->email)->first();

        if ($dealer) {
            if ($dealer->status === 'blocked') {
                return back()
                    ->withErrors(['email' => 'Your account has been blocked. Please contact admin.'])
                    ->withInput($request->only('email'));
            }
            if ($dealer->status === 'inactive') {
                return back()
                    ->withErrors(['email' => 'Your account is inactive. Please contact admin to activate it.'])
                    ->withInput($request->only('email'));
            }
        }

        if (Auth::guard('dealer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo);
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('dealer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('dealer.login')
                         ->with('success', 'You have been logged out successfully.');
    }
}
