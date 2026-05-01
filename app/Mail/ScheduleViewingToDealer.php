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

class ScheduleViewingToDealer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public ScheduleViewing $schedule,
        public Property        $property
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 New Viewing Scheduled – ' . $this->property->title,
            bcc: ['admin@indianesthub.com', 'pcmishra22@gmail.com'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.dealer.schedule-viewing',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
