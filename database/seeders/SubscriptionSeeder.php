<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $dealerIds = \App\Models\Dealer::pluck('id');
        for ($i = 1; $i <= 10; $i++) {
            Subscription::create([
                'user_id' => $i,
                'property_dealer_id' => $dealerIds[$i % $dealerIds->count()],
                'plan' => 'Basic',
                'price' => 99.99,
                'property_limit' => 10,
                'featured_limit' => 2,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'renewal_date' => now()->addMonth(),
                'status' => 'active',
            ]);
        }
    }
}
