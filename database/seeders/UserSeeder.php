<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            User::updateOrCreate([
                'email' => 'user' . $i . '@example.com',
            ], [
                'name' => 'User ' . $i,
                'password' => bcrypt('password'),
            ]);
        }
    }
}
