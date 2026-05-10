<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserLoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('emails.auth.login_notification')
                    ->subject('New Login Detected')
                    ->with([
                        'name' => $this->user->name,
                    ]);
    }
}