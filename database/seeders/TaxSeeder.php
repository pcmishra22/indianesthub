<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    public function run()
    {
        Tax::create([
            'name' => 'GST',
            'rate' => 18.00,
            'type' => 'percentage',
        ]);
        Tax::create([
            'name' => 'Service Tax',
            'rate' => 5.00,
            'type' => 'percentage',
        ]);
    }
}
