<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class NewListingAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string     $recipientName,
        public Collection $properties,
        public string     $searchLabel = 'your saved search'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🏠 ' . $this->properties->count() . ' New Properties Match ' . $this->searchLabel . ' – IndianestHub',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.buyer.new-listing-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
