<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeadSource;

class LeadSourceSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            LeadSource::create([
                'lead_id' => $i,
                'source' => 'Website',
                'created_at' => now(),
            ]);
        }
    }
}
