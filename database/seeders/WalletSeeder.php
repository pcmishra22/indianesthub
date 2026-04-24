<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallet;

class WalletSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Wallet::create([
                'user_id' => $i,
                'balance' => rand(1000, 10000),
                'credits' => rand(10, 100),
            ]);
        }
    }
}
