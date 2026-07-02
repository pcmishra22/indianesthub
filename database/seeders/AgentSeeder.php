<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Agent;

class AgentSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
                DB::table('property_dealers')->insert([
                    [
                        'first_name' => 'Agent',
                        'last_name' => $i == 1 ? 'One' : 'Two',
                        'email' => 'agent' . $i . '@example.com',
                        'phone' => '7340753780',
                        'company_name' => 'Company ' . $i,
                        'password' => bcrypt('password'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
        }
    }
}
