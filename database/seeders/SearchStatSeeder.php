<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SearchStat;

class SearchStatSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            SearchStat::create([
                'user_id' => rand(1, 20),
                'query' => 'Search query ' . $i,
                'results_count' => rand(1, 20),
                'searched_at' => now(),
            ]);
        }
    }
}
