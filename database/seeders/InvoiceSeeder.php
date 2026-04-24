<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Invoice::create([
                'user_id' => $i,
                'amount' => rand(1000, 10000),
                'tax' => rand(100, 500),
                'status' => 'paid',
                'payment_id' => null,
                'details' => 'Invoice details ' . $i,
            ]);
        }
    }
}
