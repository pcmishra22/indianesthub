<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminUserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('emails.admin.user_registered')
                    ->subject('New User Registration Alert')
                    ->with([
                        'user' => $this->user,
                    ]);
    }
}