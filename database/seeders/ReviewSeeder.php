<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            Review::create([
                'user_id' => rand(1, 20),
                'property_id' => rand(1, 100),
                'agent_id' => rand(1, 10),
                'rating' => rand(1, 5),
                'review_text' => 'Review text ' . $i,
                'status' => 'approved',
            ]);
        }
    }
}
