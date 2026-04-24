<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            FAQ::create([
                'question' => 'FAQ Question ' . $i,
                'answer' => 'FAQ Answer ' . $i,
                'status' => true,
            ]);
        }
    }
}
