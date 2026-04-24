<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            Banner::create([
                'title' => 'Banner ' . $i,
                'image' => 'banner' . $i . '.jpg',
                'status' => true,
            ]);
        }
    }
}
