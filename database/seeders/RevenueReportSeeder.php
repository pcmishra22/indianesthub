<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RevenueReport;

class RevenueReportSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 12; $i++) {
            RevenueReport::create([
                'period' => '2026-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'total_revenue' => rand(10000, 100000),
                'details' => 'Revenue details for month ' . $i,
            ]);
        }
    }
}
