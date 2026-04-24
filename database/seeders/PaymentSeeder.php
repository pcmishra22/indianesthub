<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Dealer;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::take(3)->get();
        $dealers = Dealer::take(3)->get();
        $methods = ['credit_card', 'bank_transfer', 'upi'];
        $statuses = [1, 0];

        foreach ($properties as $i => $property) {
            $dealer = $dealers[$i % $dealers->count()] ?? $dealers->first();
            $start = now()->subDays(rand(0, 10));
            $duration = [30, 60, 90][($i % 3)];
            $end = $start->copy()->addDays($duration);
            Payment::updateOrCreate([
                'property_id' => $property->id,
            ], [
                'dealer_id' => $dealer->id ?? 1,
                'property_id' => $property->id,
                'plan_type' => 'listing',
                'plan_name' => 'Standard Plan',
                'amount' => rand(1000, 5000),
                'status' => $statuses[$i % 2],
                'transaction_id' => strtoupper(Str::random(10)),
                'payment_method' => $methods[$i % 3],
                'payment_data' => json_encode(['note' => 'Seeded payment']),
                'payment_type' => 'property_listing',
                'listing_duration_days' => $duration,
                'listing_start_date' => $start->toDateString(),
                'listing_end_date' => $end->toDateString(),
                'card_last_four' => strval(rand(1000,9999)),
                'card_brand' => 'VISA',
            ]);
            // Also update property expiry_date for demo
            $property->expiry_date = $end->toDateString();
            $property->save();
        }
    }
}
