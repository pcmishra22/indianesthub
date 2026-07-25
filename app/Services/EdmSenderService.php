<?php

namespace App\Services;

use App\Mail\PropertyEdmEmail;
use App\Models\EmailTracking;
use App\Models\Property;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EdmSenderService
{
    /**
     * Send a property EDM to a list of email addresses and log each send.
     *
     * @param  array<int, array{email:string, name?:string}>  $recipients
     * @return int  Number of emails queued
     */
    public function send(
        Property $property,
        string $subject,
        string $message,
        array $recipients,
        string $publicUrl,
        ?string $senderName,
        ?string $senderEmail,
        ?string $senderPhone,
        string $senderType,
        int $senderId,
    ): int {
        $sent = 0;

        foreach ($recipients as $recipient) {
            $email = is_array($recipient) ? ($recipient['email'] ?? null) : $recipient;
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $name = is_array($recipient) ? ($recipient['name'] ?? '') : '';

            try {
                Mail::to($email)->queue(new PropertyEdmEmail(
                    property: $property,
                    emailSubject: $subject,
                    message: $message,
                    publicUrl: $publicUrl,
                    senderName: $senderName,
                    senderEmail: $senderEmail,
                    senderPhone: $senderPhone,
                ));

                EmailTracking::create([
                    'email_type'      => 'property_edm',
                    'recipient_email' => $email,
                    'recipient_name'  => $name,
                    'recipient_type'  => 'lead',
                    'property_id'     => $property->id,
                    'sender_type'     => $senderType,
                    'sender_id'       => $senderId,
                    'token'           => Str::random(40),
                    'status'          => 'sent',
                    'sent_at'         => now(),
                ]);

                $sent++;
            } catch (\Throwable $e) {
                EmailTracking::create([
                    'email_type'      => 'property_edm',
                    'recipient_email' => $email,
                    'recipient_name'  => $name,
                    'recipient_type'  => 'lead',
                    'property_id'     => $property->id,
                    'sender_type'     => $senderType,
                    'sender_id'       => $senderId,
                    'token'           => Str::random(40),
                    'status'          => 'failed',
                    'failure_reason'  => $e->getMessage(),
                ]);
            }
        }

        return $sent;
    }
}
