<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string  $recipientName,
        public string  $recipientType  = 'dealer',  // 'dealer' or 'builder'
        public ?string $trackingToken  = null,       // null = no tracking pixel
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏠 Grow Your Real Estate Business – Choose a Plan on IndianestHub',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposal',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
