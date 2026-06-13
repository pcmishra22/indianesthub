<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserBulkEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $subject, $body)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.user.bulk-email', [
                        'user' => $this->user,
                        'body' => $this->body,
                    ]);
    }
}