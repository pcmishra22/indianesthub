<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            Message::create([
                'chat_id' => rand(1, 10),
                'sender_id' => rand(1, 20),
                'content' => 'Message content ' . $i,
                'type' => 'text',
                'attachment' => null,
                'emoji' => null,
            ]);
        }
    }
}
