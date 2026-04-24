<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        return view('auth.verify-phone');
    }

    public function send(Request $request)
    {
        $request->validate(['phone' => 'required|string']);
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(10));
        // TODO: Integrate SMS gateway here
        Log::info('OTP for ' . $request->phone . ': ' . $otp);
        return back()->with('status', 'OTP sent to your phone.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp' => 'required|digits:6',
        ]);
        $cachedOtp = Cache::get('otp_' . $request->phone);
        if ($cachedOtp && $cachedOtp == $request->otp) {
            // Mark phone as verified (implement in User model)
            $user = Auth::user();
            $user->phone_verified_at = now();
            $user->save();
            Cache::forget('otp_' . $request->phone);
            return redirect()->route('user.dashboard')->with('success', 'Phone verified!');
        }
        return back()->withErrors(['otp' => 'Invalid OTP.']);
    }
}
