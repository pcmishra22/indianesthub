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

class PropertyInquiryConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inquiry  $inquiry,
        public Property $property
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Your Inquiry Has Been Received – IndianestHub',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.buyer.inquiry-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
