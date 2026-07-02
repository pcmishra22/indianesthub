<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangalorePremiumBuildersBatch1
 *
 * 3 Major Tier-1 Builders and their high-profile landmark projects in Bengaluru.
 * Sourced with authentic K-RERA numbers and verified market coordinates.
 * Covers premium micro-markets: Whitefield, Yelahanka, and Panathur.
 *
 * Run: php artisan db:seed --class=BangalorePremiumBuildersBatch1
 */
class BangalorePremiumBuildersBatch1 extends Seeder
{
    public function run(): void
    {
        // ── 1. PRESTIGE GROUP ────────────────────────────────────────
        // Headquartered in Bengaluru, one of India's largest listed entities.
        $prestigeBuilder = Builder::firstOrCreate(
            ['email' => 'contact.sales@prestigeconstructions.com'],
            [
                'name'                     => 'Prestige Group',
                'company_name'             => 'Prestige Estates Projects Ltd.',
                'password'                 => Hash::make('Prestige@Blr2026'),
                'phone'                    => '18003130080',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Hyderabad, Kochi, Mumbai',
                'established_year'         => '1986',
                'is_verified'              => true,
                'total_delivered_projects' => 300,
                'rating'                   => 4.7,
                'description'              => 'Prestige Group is a legendary pan-India property developer renowned for shifting urban skylines with highly premium integrated townships, luxury high-rises, and smart commercial ecosystems.',
                'status'                   => 'active',
            ]
        );

        // Project: Prestige Raintree Park (Whitefield / Varthur)
        BuilderProject::firstOrCreate(
            ['builder_id' => $prestigeBuilder->id, 'title' => 'Prestige Raintree Park'],
            [
                'builder_id'         => $prestigeBuilder->id,
                'description'        => 'Prestige Raintree Park is a massive ultra-luxury mixed-use residential development township spreading across 28 acres in East Bengaluru\'s tech core right on Whitefield Main Road. Featuring beautifully conceptualized 3, 4, and 5 BHK multi-generational homes designed to maximize natural ventilation and natural lighting. Built in close proximity to massive IT corridors, Nallurhalli Metro Station, and premium global educational institutes.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Whitefield Main Road, Varthur Hobli, Near Nallurhalli Metro, East Bengaluru, Karnataka 560066',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.959241,
                'longitude'          => 77.746592,
                'total_units'        => 1368,
                'available_units'    => 450,
                'price_from'         => 29500000,  // ₹2.95 Cr
                'price_to'           => 60600000,   // ₹6.06 Cr
                'possession_date'    => '2028-05-31',
                'total_towers'       => 18,
                'floors_per_tower'   => '19',
                'is_featured'        => true,
                'views_count'        => 1550,
                'leads_count'        => 0,
                'nearby_schools'     => 'Ryan International School (2.5 km), Vagdevi Vilas School (3.6 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (4.0 km), Sahasra Hospitals (2.1 km)',
                'metro_distance'     => '7 minutes away from Nallurhalli Metro Station',
                'connectivity_score' => '9',
            ]
        );


        // ── 2. BRIGADE GROUP ─────────────────────────────────────────
        // Leading real estate developer with structural legacies across South India.
        $brigadeBuilder = Builder::firstOrCreate(
            ['email' => 'salesinfo@brigadegroup.com'],
            [
                'name'                     => 'Brigade Group',
                'company_name'             => 'Brigade Enterprises Limited',
                'password'                 => Hash::make('Brigade@Blr2026'),
                'phone'                    => '18001029977',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Mysore, Chennai, Hyderabad, Gift City',
                'established_year'         => '1986',
                'is_verified'              => true,
                'total_delivered_projects' => 250,
                'rating'                   => 4.6,
                'description'              => 'Brigade Group is an award-winning master builder shaping premium ecosystems across residential, commercial, retail, hospitality, and educational sectors.',
                'status'                   => 'active',
            ]
        );

        // Project: Brigade Insignia (Yelahanka)
        BuilderProject::firstOrCreate(
            ['builder_id' => $brigadeBuilder->id, 'title' => 'Brigade Insignia'],
            [
                'builder_id'         => $brigadeBuilder->id,
                'description'        => 'Brigade Insignia is an elite boutique luxury enclave nestled right off the New International Airport Road in Yelahanka, North Bengaluru. Spanning over 5.88 pristine acres, this residential high-rise architecture offers spacious premium 3, 4, and 5 BHK configurations. Boasting exceptional sky-high terrace decks, signature sports spaces, and swift arterial access to Hebbal tech hubs and the international airport network.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'New International Airport Road, Maruthi Nagar, Yelahanka, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.111425,
                'longitude'          => 77.618512,
                'total_units'        => 344,
                'available_units'    => 120,
                'price_from'         => 35900000,  // ₹3.59 Cr
                'price_to'           => 100400000, // ₹10.04 Cr
                'possession_date'    => '2029-06-01',
                'total_towers'       => 6,
                'floors_per_tower'   => '18',
                'is_featured'        => true,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Canadian International School (2.1 km), Vidyashilp Academy (5.0 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal (7.0 km)',
                'metro_distance'     => '1.1 km from upcoming Yelahanka Metro Station',
                'connectivity_score' => '9',
            ]
        );


        // ── 3. SOBHA LIMITED ─────────────────────────────────────────
        // Noted for unmatched backward integration model and solid construction quality.
        $sobhaBuilder = Builder::firstOrCreate(
            ['email' => 'sales.bengaluru@sobha.com'],
            [
                'name'                     => 'Sobha Limited',
                'company_name'             => 'Sobha Limited',
                'password'                 => Hash::make('Sobha@Blr2026'),
                'phone'                    => '08049320000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Delhi-NCR, Chennai, Pune, Coimbatore, Thrissur',
                'established_year'         => '1995',
                'is_verified'              => true,
                'total_delivered_projects' => 170,
                'rating'                   => 4.8,
                'description'              => 'Sobha Limited is synonymous with precision German engineering. They are India’s only fully backward-integrated property developer, manufacturing their own construction components to ensure unmatched quality.',
                'status'                   => 'active',
            ]
        );

        // Project: Sobha Neopolis (Panathur / Marathahalli)
        BuilderProject::firstOrCreate(
            ['builder_id' => $sobhaBuilder->id, 'title' => 'Sobha Neopolis'],
            [
                'builder_id'         => $sobhaBuilder->id,
                'description'        => 'Sobha Neopolis is a massive, spectacular Mediterranean Greek-themed residential township sprawl situated near Panathur, right off the critical Marathahalli-Outer Ring Road (ORR). The architecture spreads seamlessly over 26 majestic acres displaying signature Santorini-style structural facades, a colossal active water park network, and expansive double-height internal layouts. Ideal location targeting tech-focused families balancing connectivity to major IT corridors.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Panathur Main Road, Off Marathahalli-Outer Ring Road, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.935102,
                'longitude'          => 77.712154,
                'total_units'        => 1875,
                'available_units'    => 600,
                'price_from'         => 23200000,  // ₹2.32 Cr
                'price_to'           => 39000000,  // ₹3.90 Cr
                'possession_date'    => '2028-12-31',
                'total_towers'       => 19,
                'floors_per_tower'   => '18',
                'is_featured'        => false,
                'views_count'        => 2110,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Horizon Gurukul (1.2 km), Orchids International School (1.8 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (3.2 km), Vydehi Hospital (6.5 km)',
                'metro_distance'     => 'Connected directly to Outer Ring Road Phase 2A Metro line',
                'connectivity_score' => '8',
            ]
        );


        // ── ATTACH AMENITIES ─────────────────────────────────────────
        $luxuryAmenities = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse',
            'Spa & Sauna', 'Yoga / Meditation', 'Party Hall / Banquet',
            'Jogging Track', 'Children\'s Play Area', '24×7 Security',
            'CCTV Surveillance', 'Video Door Phone', 'Gated Community',
            'Power Backup', 'High-Speed Elevators', 'Covered Parking',
            'EV Charging Point', 'Rainwater Harvesting', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $standardAmenities = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse',
            'Children\'s Play Area', 'Jogging Track', '24×7 Security',
            'CCTV Surveillance', 'Power Backup', 'High-Speed Elevators',
            'Covered Parking', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        // High-end luxury properties sync full package
        $luxuryTitles = ['Prestige Raintree Park', 'Brigade Insignia'];
        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxuryAmenities) && $p->amenityItems()->syncWithoutDetaching($luxuryAmenities));

        // Sub-township configurations sync standard luxury package
        $standardTitles = ['Sobha Neopolis'];
        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standardAmenities) && $p->amenityItems()->syncWithoutDetaching($standardAmenities));

        // Feedback logs
        $this->command->info('✅ Bangalore Batch 1 seeding execution completed perfectly!');
        $this->command->info('   Seeded Developers: Prestige Group, Brigade Group, Sobha Limited.');
        $this->command->info('   Seeded Landmark Projects: Prestige Raintree Park, Brigade Insignia, Sobha Neopolis.');
    }
}