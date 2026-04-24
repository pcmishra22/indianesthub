<?php

namespace App\Mail;

use App\Models\Dealer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Dealer $dealer,
        public string $expiryDate,
        public int    $daysLeft
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Your IndianestHub Subscription Expires in ' . $this->daysLeft . ' Days',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer.renewal-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
