<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Replaces having a near-identical Mailable per audience type
 * (UserBulkEmail, DealerBulkEmail, etc.) — the controller resolves each
 * recipient's display name itself (since User/Dealer/Builder/ServiceProvider
 * all name that differently: name, full_name, display_name...) and passes a
 * plain string here, so one Mailable works for every audience.
 */
class BulkAudienceEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $recipientName,
        public string $emailSubject,
        public string $body,
    ) {
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
                    ->view('emails.marketing.bulk-audience', [
                        'recipientName' => $this->recipientName,
                        'body'          => $this->body,
                    ]);
    }
}
