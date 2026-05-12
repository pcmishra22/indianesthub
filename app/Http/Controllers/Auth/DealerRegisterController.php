<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\DealerWelcomeEmail;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DealerRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.dealer-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:property_dealers,email',
            'phone'        => 'required|string|max:20',
            'company_name' => 'required|string|max:255',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $dealer = Dealer::create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'company_name' => $request->company_name,
            'password'     => Hash::make($request->password),
            'status'       => 'active',
        ]);

        Auth::guard('dealer')->login($dealer);

        // Send dealer welcome email via queue
        Mail::to($dealer->email)->queue(new DealerWelcomeEmail($dealer));

        // Notify admins (same template used for user registration)
        $adminRecipients = [
            'admin@indianesthub.com',
            'pcmishra22@gmail.com',
        ];

        Mail::to($adminRecipients)->send(new \App\Mail\AdminUserRegistered($dealer));

        return redirect()->route('dealer.dashboard');
    }
}
