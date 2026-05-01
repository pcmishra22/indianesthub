<?php

namespace App\Mail;

use App\Models\ScheduleViewing;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleViewingConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public ScheduleViewing $schedule,
        public Property        $property
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 Viewing Confirmed – ' . $this->property->title . ' | IndianestHub',
            bcc: ['admin@indianesthub.com', 'pcmishra22@gmail.com'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.buyer.schedule-viewing-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
