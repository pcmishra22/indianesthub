<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SavedSearch;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendNewListingAlerts extends Command
{
    protected $signature = 'properties:send-new-listing-alerts';
    protected $description = 'Send alerts to users for new listings matching their saved searches';

    public function handle()
    {
        $since = Carbon::now()->subDay();
        $newProperties = Property::where('created_at', '>=', $since)->get();
        $searches = SavedSearch::all();
        foreach ($searches as $search) {
            $matches = $newProperties->filter(function ($property) use ($search) {
                // Example: match city, property_type, price range
                return (
                    (!$search->city || $property->city == $search->city) &&
                    (!$search->property_type || $property->property_type == $search->property_type) &&
                    (!$search->min_price || $property->price >= $search->min_price) &&
                    (!$search->max_price || $property->price <= $search->max_price)
                );
            });
            if ($matches->count() > 0) {
                $user = User::find($search->user_id);
                if ($user && $user->email) {
                    Mail::raw(
                        "New properties matching your saved search are available:",
                        function ($message) use ($user) {
                            $message->to($user->email)
                                    ->subject('New Property Listings Alert');
                        }
                    );
                    // Optionally, add dashboard notification logic here
                }
            }
        }
        $this->info('New listing alerts sent.');
    }
}
