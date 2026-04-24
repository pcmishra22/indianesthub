<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerWelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Dealer $dealer) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Welcome to IndianestHub – Start Posting Properties Today!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
