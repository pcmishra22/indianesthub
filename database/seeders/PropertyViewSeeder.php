<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyView;

class PropertyViewSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 100; $i++) {
            PropertyView::create([
                'property_id' => rand(1, 100),
                'user_id' => rand(1, 20),
                'viewed_at' => now(),
            ]);
        }
    }
}
