<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TwoFactorController extends Controller
{
    public function showForm()
    {
        return view('auth.two-factor');
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $code = rand(100000, 999999);
        Cache::put('2fa_' . $user->id, $code, now()->addMinutes(10));
        // TODO: Integrate SMS/email gateway here
        return back()->with('status', '2FA code sent.');
    }

    public function verify(Request $request)
    {
        $user = Auth::user();
        $request->validate(['code' => 'required|digits:6']);
        $cachedCode = Cache::get('2fa_' . $user->id);
        if ($cachedCode && $cachedCode == $request->code) {
            $user->two_factor_verified_at = now();
            $user->save();
            Cache::forget('2fa_' . $user->id);
            return redirect()->route('user.dashboard')->with('success', '2FA verified!');
        }
        return back()->withErrors(['code' => 'Invalid 2FA code.']);
    }
}
