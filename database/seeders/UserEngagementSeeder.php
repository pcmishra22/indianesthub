<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserEngagement;

class UserEngagementSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            UserEngagement::create([
                'user_id' => rand(1, 20),
                'action' => 'view_property',
                'details' => 'Viewed property ' . rand(1, 100),
                'engaged_at' => now(),
            ]);
        }
    }
}
