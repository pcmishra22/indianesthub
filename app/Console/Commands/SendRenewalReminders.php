<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Models\Dealer;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendRenewalReminders extends Command
{
    protected $signature = 'properties:send-renewal-reminders';
    protected $description = 'Send renewal reminders to dealers for expiring properties';

    public function handle()
    {
        $today = Carbon::today();
        $soon = $today->copy()->addDays(7);
        $expiring = Property::whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [$today, $soon])
            ->get();
        foreach ($expiring as $property) {
            $dealer = $property->dealer;
            if ($dealer && $dealer->email) {
                Mail::raw(
                    "Your property '{$property->title}' is expiring on {$property->expiry_date}. Please renew your listing.",
                    function ($message) use ($dealer, $property) {
                        $message->to($dealer->email)
                                ->subject('Property Renewal Reminder');
                    }
                );
            }
        }
        $this->info('Renewal reminders sent.');
    }
}
