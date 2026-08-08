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
            UserSeeder::class,
            AgentSeeder::class,
            AmenitySeeder::class,
            BangaloreBuilderProjectsBatch1::class,
            BangaloreBuildersBatch1::class,
            BangaloreBuildersBatch2::class,
            BangaloreBuildersBatch3::class,
            BangaloreBuildersBatch4::class,
            BangaloreBuildersBatch5::class,
            BangaloreBuildersBatch6::class,
            BangaloreBuildersBatch7::class,
            BangaloreBuildersBatch8::class,
            BangaloreBuildersBatch9::class,
            BangaloreBuildersBatch10::class,
            BangalorePremiumBuildersBatch1::class,
            BannerSeeder::class,
            BlogSeeder::class,
            BuilderDemoSeeder::class,
            BuilderSeeder::class,
            ChatSeeder::class,
            ContactSeeder::class,
            DealerSeeder::class,
            FAQSeeder::class,
            HyderabadBuildersBatch1::class,
            HyderabadBuildersBatch2::class,
            HyderabadBuildersBatch3::class,
            InquirySeeder::class,
            InvoiceSeeder::class,
            LeadSourceSeeder::class,
            MarketplaceCategorySeeder::class,
            MarketplaceProductSeeder::class,
            MarketplaceVendorSeeder::class,
            MessageSeeder::class,
            MmrBuildersBatch1::class,
            MmrBuildersBatch2::class,
            MmrBuildersBatch3::class,
            MohaliKhararBuilderProjectsSeeder::class,
            MohaliKhararProjectsBatch2Seeder::class,
            MohaliRealEstateBlogSeeder::class,
            NcrBuildersBatch1::class,
            NcrBuildersBatch2::class,
            NcrBuildersBatch3::class,
            NearbyProjectsSeeder::class,
            PaymentSeeder::class,
            PropertyImageSeeder::class,
            PropertySeeder::class,
            PropertyViewSeeder::class,
            PuneBuildersBatch1::class,
            PuneBuildersBatch2::class,
            PuneBuildersBatch3::class,
            RevenueReportSeeder::class,
            ReviewSeeder::class,
            SearchStatSeeder::class,
            ServiceCategorySeeder::class,
            SubscriptionSeeder::class,
            TaxSeeder::class,
            TricityBuilderProjectsBatch3::class,
            TricityBuilderProjectsSeeder::class,
            TricityBuilderSeeder::class,
            TricityDealersSeeder::class,
            TricityPropertiesSeeder::class,
            TricityRealDataSeeder::class,
            TricityRealDataSeederBatch2::class,
            TricityRealDataSeederBatch3::class,
            UserEngagementSeeder::class,
            WalletSeeder::class,
            // Real Tricity Data (run these to populate live data)
            ZirakpurProximitySeeder::class,
            ZirakpurProximitySeederBatch2::class,
            ZirakpurProximitySeederBatch3::class,
            ZirakpurExtendedRingSeeder::class,
            NearbyProjectsSeeder::class,
            TricityBuilderProjectsSeeder::class,  
            TricityBuilderProjectsBatch3::class, 
        ]);
    }
}
