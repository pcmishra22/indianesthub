<?php

namespace App\Mail;

use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PropertyEdmEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Property $property,
        public string $emailSubject,
        public string $message,
        public string $publicUrl,
        public ?string $senderName = null,
        public ?string $senderEmail = null,
        public ?string $senderPhone = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
            replyTo: $this->senderEmail ? [new Address($this->senderEmail, $this->senderName ?? '')] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.marketing.property-edm',
            with: [
                'property'     => $this->property,
                'message'      => $this->message,
                'publicUrl'    => $this->publicUrl,
                'senderName'   => $this->senderName,
                'senderPhone'  => $this->senderPhone,
                'emailSubject' => $this->emailSubject,
            ],
        );
    }
}
