<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chat;

class ChatSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Chat::create([
                'buyer_id' => $i,
                'seller_id' => $i,
                'channel' => 'in-app',
            ]);
        }
    }
}
