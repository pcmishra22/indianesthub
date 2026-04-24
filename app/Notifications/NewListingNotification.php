<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewListingNotification extends Notification
{
    use Queueable;

    public $listing;

    public function __construct($listing)
    {
        $this->listing = $listing;
    }

    public function via($notifiable)
    {
        return ['mail', 'nexmo', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Property Listing')
            ->line('A new property has been listed: ' . $this->listing->title)
            ->action('View Listing', url('/properties/' . $this->listing->id));
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
            ->content('New property listed: ' . $this->listing->title);
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->listing->title,
            'id' => $this->listing->id,
            'type' => 'new_listing',
        ];
    }
}
