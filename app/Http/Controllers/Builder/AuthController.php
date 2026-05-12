<?php

namespace App\Http\Controllers\Builder;

use App\Http\Controllers\Controller;
use App\Models\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('builder')->check()) {
            return redirect()->route('builder.dashboard');
        }
        return view('builder.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('builder')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('builder.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::guard('builder')->check()) {
            return redirect()->route('builder.dashboard');
        }
        return view('builder.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:builders,email'],
            'phone'        => ['required', 'string', 'max:20'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $builder = Builder::create([
            'name'         => $validated['name'],
            'company_name' => $validated['company_name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'],
            'password'     => Hash::make($validated['password']),
            'status'       => 'active',
        ]);

        Auth::guard('builder')->login($builder);
        $request->session()->regenerate();

        // Send builder welcome email to builder + notify admins
        // (Uses the same mail templates & recipients as for user/dealer registration.)
        Mail::to($builder->email)->queue(new \App\Mail\WelcomeUser($builder));

        $adminRecipients = [
            'admin@indianesthub.com',
            'pcmishra22@gmail.com',
        ];

        Mail::to($adminRecipients)->send(new \App\Mail\AdminUserRegistered($builder));

        return redirect()->route('builder.dashboard')
            ->with('success', 'Welcome to IndianestHub Builder Portal! Your account has been created.');
    }

    public function logout(Request $request)
    {
        Auth::guard('builder')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('builder.login');
    }
}
