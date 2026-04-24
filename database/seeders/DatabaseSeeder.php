<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            AdminSeeder::class,
            DealerSeeder::class,
            // Real Tricity Data (run these to populate live data)
            TricityRealDataSeeder::class,
            TricityRealDataSeederBatch2::class,
            TricityRealDataSeederBatch3::class,
            TricityDealersSeeder::class,
            TricityPropertiesSeeder::class,
            ZirakpurProximitySeeder::class,
            ZirakpurProximitySeederBatch2::class,
            ZirakpurProximitySeederBatch3::class,
            PropertySeeder::class,
            UserSeeder::class,
            ReviewSeeder::class,
            AgentSeeder::class,
            FAQSeeder::class,
            BannerSeeder::class,
            SubscriptionSeeder::class,
            WalletSeeder::class,
            InvoiceSeeder::class,
            BlogSeeder::class,
            InquirySeeder::class,
            ContactSeeder::class,
            PropertyImageSeeder::class,
            PaymentSeeder::class,
            TaxSeeder::class,
            ChatSeeder::class,
            MessageSeeder::class,
            PropertyViewSeeder::class,
            SearchStatSeeder::class,
            LeadSourceSeeder::class,
            UserEngagementSeeder::class,
            RevenueReportSeeder::class,
        ]);
    }
}
