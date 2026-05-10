<?php

namespace App\Listeners;

use App\Mail\AdminUserRegistered;
use App\Mail\WelcomeUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserRegisteredListener
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // 1) Send welcome to the user
        Mail::to($user->email)->send(new WelcomeUser($user));

        // 2) Notify admins (requested recipients)
        $adminRecipients = [
            'admin@indianesthub.com',
            'pcmishra22@gmail.com',
        ];

        Log::info('Sending admin user registration email', [
            'user_id' => $user->id ?? null,
            'user_email' => $user->email,
            'admin_recipients' => $adminRecipients,
        ]);

        Mail::to($adminRecipients)->send(new AdminUserRegistered($user));
    }
}

