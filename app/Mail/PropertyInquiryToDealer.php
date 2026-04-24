<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyInquiryToDealer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inquiry  $inquiry,
        public Property $property
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 New Inquiry Received – ' . $this->property->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer.inquiry-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
