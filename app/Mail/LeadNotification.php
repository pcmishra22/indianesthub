<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Dealer $dealer,
        public array  $lead  // ['name', 'phone', 'email', 'message', 'property_title', 'source']
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔥 New Lead Received – IndianestHub',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer.lead-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
