<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Dealer;

class DealerSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Dealer::updateOrCreate([
                'email' => "dealer$i@example.com",
            ], [
                'first_name' => 'Dealer',
                'last_name' => (string)$i,
                'phone' => '1234567890',
                'company_name' => 'Company ' . $i,
                'password' => Hash::make('password'),
            ]);
        }
    }
}
