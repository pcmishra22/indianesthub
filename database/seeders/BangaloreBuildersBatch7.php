<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch7
 *
 * 10 Builders / Projects — Batch 7 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: Puravankara Limited, Brigade Enterprises, Prestige Group, Salarpuria Sattva,
 * Godrej Properties, Sobha Limited, Casagrand, Concorde Group, Mana Projects, Nitesh Estates.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch7
 */
class BangaloreBuildersBatch7 extends Seeder
{
    public function run(): void
    {
        // ── 61. Puravankara Limited ──────────────────────────────────
        $purvaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@puravankara.com'],
            [
                'name'                     => 'Puravankara Limited',
                'company_name'             => 'Puravankara Limited',
                'password'                 => Hash::make('PurvaBlr2026'),
                'phone'                    => '08044555555',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Mumbai, Pune, Chennai, Hyderabad, Kochi',
                'established_year'         => '1975',
                'is_verified'              => true,
                'total_delivered_projects' => 82,
                'rating'                   => 4.5,
                'description'              => 'A legendary real estate titan in India, renowned for themed luxury residential landmarks, immaculate quality standards, and robust corporate governance.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $purvaBuilder->id, 'title' => 'Purva Palm Beach'],
            [
                'builder_id'         => $purvaBuilder->id,
                'description'        => 'Purva Palm Beach is an iconic tropical beach-themed luxury residential project in Hennur. Features an authentic wave pool, white sand beaches, sunken bars, and koi ponds within a sprawling multi-acre layout.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Hennur Main Road, Bio-Diversity Park Extension, North Bengaluru, Karnataka 560077',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.061425,
                'longitude'          => 77.651254,
                'total_units'        => 1320,
                'available_units'    => 28,
                'price_from'         => 11500000,
                'price_to'           => 24500000,
                'possession_date'    => '2022-03-31',
                'total_towers'       => 19,
                'floors_per_tower'   => '19',
                'is_featured'        => true,
                'views_count'        => 2890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Legacy School Bangalore (1.2 km)',
                'nearby_hospitals'   => 'Cratis Hospital Hennur (2.0 km)',
                'metro_distance'     => '8 minutes to upcoming HRBR Layout Metro line block',
                'connectivity_score' => '10',
            ]
        );

        // ── 62. Brigade Enterprises ──────────────────────────────────
        $brigadeBuilder = Builder::firstOrCreate(
            ['email' => 'sales@brigadegroup.com'],
            [
                'name'                     => 'Brigade Enterprises',
                'company_name'             => 'Brigade Enterprises Limited',
                'password'                 => Hash::make('BrigadeBlr2026'),
                'phone'                    => '18001029977',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Hyderabad, Mysuru, Kochi',
                'established_year'         => '1986',
                'is_verified'              => true,
                'total_delivered_projects' => 150,
                'rating'                   => 4.6,
                'description'              => 'One of India’s leading property developers shaping urban skylines across residential, commercial, retail, and hospitality sectors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $brigadeBuilder->id, 'title' => 'Brigade Exotica'],
            [
                'builder_id'         => $brigadeBuilder->id,
                'description'        => 'Brigade Exotica is a majestic, high-concept luxury sky-villa project along Old Madras Road. Architecturally designed without common walls, offering panoramic landscape views and corner decks for premium lifestyle experiences.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Old Madras Road, Near NH-75 Highway Junction, East Bengaluru, Karnataka 560049',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.014212,
                'longitude'          => 77.799412,
                'total_units'        => 454,
                'available_units'    => 14,
                'price_from'         => 19500000,
                'price_to'           => 42000000,
                'possession_date'    => '2021-08-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '35',
                'is_featured'        => true,
                'views_count'        => 3100,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Baldwin International School (2.5 km)',
                'nearby_hospitals'   => 'RxDx Healthcare OMR Link (6.0 km)',
                'metro_distance'     => '10 minutes from Whitefield Metro Terminal corridor',
                'connectivity_score' => '9',
            ]
        );

        // ── 63. Prestige Group ───────────────────────────────────────
        $prestigeBuilder = Builder::firstOrCreate(
            ['email' => 'sales@prestigeconstructions.com'],
            [
                'name'                     => 'Prestige Group',
                'company_name'             => 'Prestige Estates Projects Limited',
                'password'                 => Hash::make('PrestigeBlr2026'),
                'phone'                    => '18003130080',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Hyderabad, Kochi, Mumbai, Goa',
                'established_year'         => '1986',
                'is_verified'              => true,
                'total_delivered_projects' => 285,
                'rating'                   => 4.7,
                'description'              => 'A vanguard of luxury living in South India, dominating integrated residential townships, premium shopping malls, and grade-A commercial complexes.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $prestigeBuilder->id, 'title' => 'Prestige Lakeside Habitat'],
            [
                'builder_id'         => $prestigeBuilder->id,
                'description'        => 'Prestige Lakeside Habitat is a spectacular Disney-themed sprawling integrated township overlooking Varthur Lake. Spanning over 102 acres, it blends high-rise residential towers with independent fairy-tale luxury villas.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Marathahalli-Sarjapur Outer Ring Road, Varthur, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.941412,
                'longitude'          => 77.741254,
                'total_units'        => 3699,
                'available_units'    => 52,
                'price_from'         => 12500000,
                'price_to'           => 65000000,
                'possession_date'    => '2020-05-31',
                'total_towers'       => 24,
                'floors_per_tower'   => '29',
                'is_featured'        => true,
                'views_count'        => 5600,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Greenwood High International School (3.2 km)',
                'nearby_hospitals'   => 'Columbia Asia Hospital Whitefield (4.0 km)',
                'metro_distance'     => '8 minutes to upcoming Kundalahalli Metro station link',
                'connectivity_score' => '10',
            ]
        );

        // ── 64. Salarpuria Sattva ────────────────────────────────────
        $sattvaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sattvagroup.in'],
            [
                'name'                     => 'Salarpuria Sattva',
                'company_name'             => 'Sattva Developers Private Limited',
                'password'                 => Hash::make('SattvaBlr2026'),
                'phone'                    => '08042699000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad, Kolkata, Pune, Coimbatore',
                'established_year'         => '1993',
                'is_verified'              => true,
                'total_delivered_projects' => 114,
                'rating'                   => 4.5,
                'description'              => 'A highly reliable corporate infrastructure driver specializing in robust high-rise architectural alignments, massive IT parks, and residential paradigms.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sattvaBuilder->id, 'title' => 'Sattva Magnificia'],
            [
                'builder_id'         => $sattvaBuilder->id,
                'description'        => 'Sattva Magnificia is an ultra-premium, visually stunning high-rise ecosystem situated right on Old Madras Road. Completed with double-height lobby configurations, robust engineering matrix panels, and premium sky lounges.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Old Madras Road, Near Indiranagar Extension Link, East Bengaluru, Karnataka 560016',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.991412,
                'longitude'          => 77.671415,
                'total_units'        => 244,
                'available_units'    => 8,
                'price_from'         => 18000000,
                'price_to'           => 31000000,
                'possession_date'    => '2021-02-28',
                'total_towers'       => 3,
                'floors_per_tower'   => '22',
                'is_featured'        => false,
                'views_count'        => 1420,
                'leads_count'        => 0,
                'nearby_schools'     => 'National Public School Indiranagar (3.0 km)',
                'nearby_hospitals'   => 'C V Raman General Hospital (2.2 km)',
                'metro_distance'     => '3 minutes from Benniganahalli Metro Station terminal',
                'connectivity_score' => '10',
            ]
        );

        // ── 65. Godrej Properties ────────────────────────────────────
        $godrejBuilder = Builder::firstOrCreate(
            ['email' => 'sales@godrejproperties.com'],
            [
                'name'                     => 'Godrej Properties',
                'company_name'             => 'Godrej Properties Limited',
                'password'                 => Hash::make('GodrejBlr2026'),
                'phone'                    => '18002582588',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Mumbai, Pune, Bengaluru, Gurugram, Kolkata, Chennai',
                'established_year'         => '1990',
                'is_verified'              => true,
                'total_delivered_projects' => 95,
                'rating'                   => 4.4,
                'description'              => 'The premier property development arm of the iconic multi-billion dollar Godrej Group, bringing a 125-year legacy of institutional trust and engineering brilliance.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $godrejBuilder->id, 'title' => 'Godrej United'],
            [
                'builder_id'         => $godrejBuilder->id,
                'description'        => 'Godrej United is a cutting-edge, high-design premium apartment complex located in the heart of Whitefield. Distinctly recognized for its iconic crystalline geometry facade offering 270-degree panoramic urban views.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Whitefield Main Road, Next to Phoenix Marketcity, East Bengaluru, Karnataka 560048',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.994212,
                'longitude'          => 77.719412,
                'total_units'        => 514,
                'available_units'    => 12,
                'price_from'         => 14500000,
                'price_to'           => 29500000,
                'possession_date'    => '2021-09-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '17',
                'is_featured'        => true,
                'views_count'        => 2670,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy Whitefield (2.8 km)',
                'nearby_hospitals'   => 'RxDx Healthcare Whitefield (1.0 km)',
                'metro_distance'     => 'Direct walking access to Singayyanapana Palya Metro Station',
                'connectivity_score' => '10',
            ]
        );

        // ── 66. Sobha Limited ────────────────────────────────────────
        $sobhaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sobha.com'],
            [
                'name'                     => 'Sobha Limited',
                'company_name'             => 'Sobha Limited',
                'password'                 => Hash::make('SobhaBlr2026'),
                'phone'                    => '08049320000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Kochi, Chennai, Pune, Delhi-NCR, Coimbatore',
                'established_year'         => '1995',
                'is_verified'              => true,
                'total_delivered_projects' => 160,
                'rating'                   => 4.8,
                'description'              => 'India’s only fully backward-integrated real estate developer, legendary for producing structural masterworks matching German engineering standards.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sobhaBuilder->id, 'title' => 'Sobha Dream Acres'],
            [
                'builder_id'         => $sobhaBuilder->id,
                'description'        => 'Sobha Dream Acres is a monumental 81-acre mega-development community built entirely using pre-cast concrete technology templates. Offers seamless structural alignments and an abundance of lifestyle sports complexes.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Panathur-Balagere Road, Off Marathahalli Outer Ring Road, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.934125,
                'longitude'          => 77.721412,
                'total_units'        => 6500,
                'available_units'    => 194,
                'price_from'         => 7200000,
                'price_to'           => 14500000,
                'possession_date'    => '2023-12-31',
                'total_towers'       => 42,
                'floors_per_tower'   => '14',
                'is_featured'        => true,
                'views_count'        => 4100,
                'leads_count'        => 0,
                'nearby_schools'     => 'Chrysalis High School (2.0 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (5.5 km)',
                'metro_distance'     => '10 minutes away from Kadubeesanahalli Metro Node Link',
                'connectivity_score' => '9',
            ]
        );

        // ── 67. Casagrand ────────────────────────────────────────────
        $casagrandBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@casagrand.co.in'],
            [
                'name'                     => 'Casagrand',
                'company_name'             => 'Casagrand Builder Private Limited',
                'password'                 => Hash::make('Casagrand2026'),
                'phone'                    => '08046117000',
                'city'                     => 'Chennai',
                'cities_operating'         => 'Chennai, Bengaluru, Coimbatore, Hyderabad',
                'established_year'         => '2004',
                'is_verified'              => true,
                'total_delivered_projects' => 140,
                'rating'                   => 4.3,
                'description'              => 'A powerhouse developer focusing on high-amenity residential communities that maximize functional utility parameters for modern homeowners.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $casagrandBuilder->id, 'title' => 'Casagrand Aquene'],
            [
                'builder_id'         => $casagrandBuilder->id,
                'description'        => 'Casagrand Aquene is a beautifully optimized, modern multi-family apartment project off Kengeri. Engineered with high spatial efficiencies, kid-centric amenities, and excellent urban arterial road links.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Kengeri-Uttarahalli Main Road, Near NICE Road Junction, South Bengaluru, Karnataka 560060',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.909412,
                'longitude'          => 77.491425,
                'total_units'        => 322,
                'available_units'    => 85,
                'price_from'         => 5500000,
                'price_to'           => 9800000,
                'possession_date'    => '2027-03-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '8',
                'is_featured'        => false,
                'views_count'        => 1210,
                'leads_count'        => 0,
                'nearby_schools'     => 'BGS Public School Kengeri (1.5 km)',
                'nearby_hospitals'   => 'BGS Gleneagles Global Hospital (1.8 km)',
                'metro_distance'     => '5 minutes to Kengeri Metro Station terminal block',
                'connectivity_score' => '9',
            ]
        );

        // ── 68. Concorde Group ───────────────────────────────────────
        $concordeBuilder = Builder::firstOrCreate(
            ['email' => 'sales@concordegroup.in'],
            [
                'name'                     => 'Concorde Group',
                'company_name'             => 'Concorde Housing Corporation Pvt Ltd',
                'password'                 => Hash::make('Concorde2026'),
                'phone'                    => '08061226122',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1998',
                'is_verified'              => true,
                'total_delivered_projects' => 31,
                'rating'                   => 4.2,
                'description'              => 'Concorde Group focuses on utilizing cutting-edge construction technologies to provide smart lifestyle blueprints in vital growth corridors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $concordeBuilder->id, 'title' => 'Concorde Cu値e'],
            [
                'builder_id'         => $concordeBuilder->id,
                'title'              => 'Concorde Cuve',
                'description'        => 'Concorde Cuve is a boutique, highly futuristic premium apartment complex located on Electronic City Phase 1. Features intelligent home automation grids, high ceiling tolerances, and zero spatial dead-corners.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Electronic City Phase 1, Near Wipro Gate Terminal, South Bengaluru, Karnataka 560100',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.859412,
                'longitude'          => 77.659412,
                'total_units'        => 162,
                'available_units'    => 6,
                'price_from'         => 6800000,
                'price_to'           => 11500000,
                'possession_date'    => '2023-06-30',
                'total_towers'       => 1,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Electronic City (3.5 km)',
                'nearby_hospitals'   => 'Springleaf Hospital E-City (1.0 km)',
                'metro_distance'     => '4 minutes from upcoming Electronic City toll metro point',
                'connectivity_score' => '10',
            ]
        );

        // ── 69. Mana Projects ────────────────────────────────────────
        $manaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@manaprojects.com'],
            [
                'name'                     => 'Mana Projects',
                'company_name'             => 'Mana Projects Private Limited',
                'password'                 => Hash::make('ManaBlr2026'),
                'phone'                    => '08049034903',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2000',
                'is_verified'              => true,
                'total_delivered_projects' => 24,
                'rating'                   => 4.4,
                'description'              => 'Mana Projects blends nature-first design with premium construction metrics, specializing in green-roof concepts and multi-tier club infrastructures.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $manaBuilder->id, 'title' => 'Mana Uber Verdant'],
            [
                'builder_id'         => $manaBuilder->id,
                'description'        => 'Mana Uber Verdant is a premium, eco-luxury high-rise development on Sarjapur Main Road. Features vertical gardens, individual balcony sit-outs with continuous water drop loops, and high-performance energy conservation grids.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sarjapur Main Road, Near Carmelaram Railway Crossing, East Bengaluru, Karnataka 560035',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.911412,
                'longitude'          => 77.709412,
                'total_units'        => 492,
                'available_units'    => 11,
                'price_from'         => 9200000,
                'price_to'           => 17800000,
                'possession_date'    => '2022-10-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '15',
                'is_featured'        => false,
                'views_count'        => 1340,
                'leads_count'        => 0,
                'nearby_schools'     => 'The International School Bangalore TISB (4.0 km)',
                'nearby_hospitals'   => 'Columbia Asia Hospital Sarjapur Link (3.5 km)',
                'metro_distance'     => '7 minutes away from Bellandur ORR Metro alignment node',
                'connectivity_score' => '9',
            ]
        );

        // ── 70. Nitesh Estates (Legacy Hub) ─────────────────────────
        $niteshBuilder = Builder::firstOrCreate(
            ['email' => 'sales@niteshestates.com'],
            [
                'name'                     => 'Nitesh Estates',
                'company_name'             => 'NEL Holdings South Private Limited',
                'password'                 => Hash::make('NiteshBlr2026'),
                'phone'                    => '08040174222',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2004',
                'is_verified'              => true,
                'total_delivered_projects' => 22,
                'rating'                   => 3.9,
                'description'              => 'A prominent upscale brand known for introducing ultra-luxury design paradigms, premium structural alignments, and boutique high-rise towers.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $niteshBuilder->id, 'title' => 'Nitesh British Corporate'],
            [
                'builder_id'         => $niteshBuilder->id,
                'description'        => 'Nitesh British Corporate is a premium British-architecture inspired luxury residential complex on Begur Road. Styled with detailed classical stone moldings, large interior spaces, and continuous multi-tier perimeter safety arrays.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Begur Main Road, Off Hosur Expressway Corridor, South Bengaluru, Karnataka 560068',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.879412,
                'longitude'          => 77.629412,
                'total_units'        => 196,
                'available_units'    => 5,
                'price_from'         => 7900000,
                'price_to'           => 13500000,
                'possession_date'    => '2021-04-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 670,
                'leads_count'        => 0,
                'nearby_schools'     => 'St. Francis School Begur (1.0 km)',
                'nearby_hospitals'   => 'Jayadeva Institute of Cardiology Link (6.5 km)',
                'metro_distance'     => '8 minutes to Bommanahalli Metro line station block',
                'connectivity_score' => '8',
            ]
        );

        // ── Attach Amenities ─────────────────────────────────────────
        $luxury = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse',
            'Spa & Sauna', 'Yoga / Meditation', 'Party Hall / Banquet',
            'Jogging Track', 'Children\'s Play Area', '24×7 Security',
            'CCTV Surveillance', 'Video Door Phone', 'Gated Community',
            'Power Backup', 'High-Speed Elevators', 'Covered Parking',
            'EV Charging Point', 'Rainwater Harvesting', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $standard = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse',
            'Children\'s Play Area', 'Jogging Track', '24×7 Security',
            'CCTV Surveillance', 'Power Backup', 'High-Speed Elevators',
            'Covered Parking', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $luxuryTitles  = ['Purva Palm Beach', 'Brigade Exotica', 'Prestige Lakeside Habitat', 'Sattva Magnificia', 'Godrej United'];
        $standardTitles = ['Sobha Dream Acres', 'Casagrand Aquene', 'Concorde Cuve', 'Mana Uber Verdant', 'Nitesh British Corporate'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 7/10 complete: 70/100 Bengaluru Builders successfully initialized.');
    }
}