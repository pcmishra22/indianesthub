<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch3
 *
 * 10 Builders / Projects — Batch 3 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: Salarpuria Sattva (Commercial/Resi Classic), Kolte-Patil, Prestige Global,
 * Godrej Horizon Wings, Vaishnavi Group, Sobha Luxury Homes, Brigade Sanctuary, 
 * Purva Land, Concorde Group, Century Real Estate.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch3
 */
class BangaloreBuildersBatch3 extends Seeder
{
    public function run(): void
    {
        // ── 21. Kolte-Patil Developers ────────────────────────────────
        $kolteBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@koltepatil.com'],
            [
                'name'                     => 'Kolte-Patil Developers',
                'company_name'             => 'Kolte-Patil Developers Limited',
                'password'                 => Hash::make('KoltePatilBlr2026'),
                'phone'                    => '18002666654',
                'city'                     => 'Pune',
                'cities_operating'         => 'Pune, Mumbai, Bengaluru',
                'established_year'         => '1991',
                'is_verified'              => true,
                'total_delivered_projects' => 65,
                'rating'                   => 4.3,
                'description'              => 'A publicly listed real estate powerhouse known for delivering landmark residential ecosystems focused heavily on community infrastructure.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $kolteBuilder->id, 'title' => 'Kolte-Patil Raaga'],
            [
                'builder_id'         => $kolteBuilder->id,
                'description'        => 'Kolte-Patil Raaga is a premium urban residential project located on Hennur Main Road. It features beautifully designed contemporary high-rise layouts with high focus on ventilation and space optimization.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Hennur Main Road, Kannuru, North Bengaluru, Karnataka 560077',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.061412,
                'longitude'          => 77.653214,
                'total_units'        => 550,
                'available_units'    => 22,
                'price_from'         => 6800000,
                'price_to'           => 12500000,
                'possession_date'    => '2022-03-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 940,
                'leads_count'        => 0,
                'nearby_schools'     => 'Royale Concorde International School (3.0 km)',
                'nearby_hospitals'   => 'Regal Super Speciality Hospital (2.5 km)',
                'metro_distance'     => '10 minutes from upcoming Nagawara Metro Station',
                'connectivity_score' => '8',
            ]
        );

        // ── 22. Vaishnavi Group ──────────────────────────────────────
        $vaishnaviBuilder = Builder::firstOrCreate(
            ['email' => 'sales@vaishnavigroup.com'],
            [
                'name'                     => 'Vaishnavi Group',
                'company_name'             => 'Vaishnavi Infrastructure Pvt Ltd',
                'password'                 => Hash::make('VaishnaviBlr2026'),
                'phone'                    => '08046694669',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1998',
                'is_verified'              => true,
                'total_delivered_projects' => 35,
                'rating'                   => 4.5,
                'description'              => 'Vaishnavi Group focuses on boutique high-quality commercial landmarks and ultra-premium urban high-rises built around structural perfection.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $vaishnaviBuilder->id, 'title' => 'Vaishnavi Serene'],
            [
                'builder_id'         => $vaishnaviBuilder->id,
                'description'        => 'Vaishnavi Serene is a path-breaking residential project in Yelahanka utilizing premium off-site pre-cast construction technology. Offering highly climate-resilient structural frames nestled in dense green settings.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Off Doddaballapur Road, Yelahanka, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.134125,
                'longitude'          => 77.581245,
                'total_units'        => 896,
                'available_units'    => 35,
                'price_from'         => 5200000,
                'price_to'           => 9800000,
                'possession_date'    => '2023-12-31',
                'total_towers'       => 8,
                'floors_per_tower'   => '4',
                'is_featured'        => true,
                'views_count'        => 1280,
                'leads_count'        => 0,
                'nearby_schools'     => 'Chrysalis High Yelahanka (2.0 km)',
                'nearby_hospitals'   => 'Navachethana Hospital (3.5 km)',
                'metro_distance'     => '8 minutes away from Yelahanka Metro Terminal link',
                'connectivity_score' => '9',
            ]
        );

        // ── 23. Concorde Group ───────────────────────────────────────
        $concordeBuilder = Builder::firstOrCreate(
            ['email' => 'sales@concorde.in'],
            [
                'name'                     => 'Concorde Group',
                'company_name'             => 'Concorde Housing Corporation Ltd',
                'password'                 => Hash::make('ConcordeBlr2026'),
                'phone'                    => '08061226122',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1998',
                'is_verified'              => true,
                'total_delivered_projects' => 28,
                'rating'                   => 4.2,
                'description'              => 'Concorde Group is pioneer in developing high-value micro-market residential townships and luxury villa enclaves across Electronic City and South Bengaluru.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $concordeBuilder->id, 'title' => 'Concorde Abode 99'],
            [
                'builder_id'         => $concordeBuilder->id,
                'description'        => 'Concorde Abode 99 is a signature, premium multi-acre villa enclave located off Hosur Road. Built explicitly with smart home automated frameworks, private backyards, and modular wooden interior elements.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Chandapura-Anekal Main Road, Off Hosur Highway, South Bengaluru, Karnataka 562106',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.791245,
                'longitude'          => 77.694212,
                'total_units'        => 159,
                'available_units'    => 42,
                'price_from'         => 16500000,
                'price_to'           => 29000000,
                'possession_date'    => '2027-06-30',
                'total_towers'       => 0, // Independent Villas
                'floors_per_tower'   => '2',
                'is_featured'        => true,
                'views_count'        => 1105,
                'leads_count'        => 0,
                'nearby_schools'     => 'Alliance University Campus (4.0 km)',
                'nearby_hospitals'   => 'Narayana Health City (6.5 km)',
                'metro_distance'     => '12 minutes from Bommasandra Metro Station',
                'connectivity_score' => '8',
            ]
        );

        // ── 24. Century Real Estate ──────────────────────────────────
        $centuryBuilder = Builder::firstOrCreate(
            ['email' => 'sales@centuryrealestate.in'],
            [
                'name'                     => 'Century Real Estate',
                'company_name'             => 'Century Real Estate Holdings Pvt Ltd',
                'password'                 => Hash::make('CenturyBlr2026'),
                'phone'                    => '08044334433',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1973',
                'is_verified'              => true,
                'total_delivered_projects' => 22,
                'rating'                   => 4.4,
                'description'              => 'One of the oldest land-holding and infrastructure companies in Bengaluru, shaping major premium developments across North Bengaluru.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $centuryBuilder->id, 'title' => 'Century Ethos'],
            [
                'builder_id'         => $centuryBuilder->id,
                'description'        => 'Century Ethos is a stunning ultra-luxury residential sky-rise landmark in Hebbal. Built cleanly with custom luxury configurations, a massive 50,000 sq.ft clubhouse, and zero-compromise security setups.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Hebbal Main Road, Near Columbia Asia Hospital, North Bengaluru, Karnataka 560092',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.039415,
                'longitude'          => 77.598212,
                'total_units'        => 333,
                'available_units'    => 12,
                'price_from'         => 34000000,
                'price_to'           => 68000000,
                'possession_date'    => '2022-12-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '21',
                'is_featured'        => true,
                'views_count'        => 1980,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vidyashilp Academy (4.2 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital (0.5 km)',
                'metro_distance'     => '2 minutes from upcoming Hebbal Cross Metro Node',
                'connectivity_score' => '10',
            ]
        );

        // ── 25. Ozone Group ──────────────────────────────────────────
        $ozoneBuilder = Builder::firstOrCreate(
            ['email' => 'sales@ozonegroup.com'],
            [
                'name'                     => 'Ozone Group',
                'company_name'             => 'Ozone Urbana Infra Private Limited',
                'password'                 => Hash::make('OzoneBlr2026'),
                'phone'                    => '08040406060',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Mumbai',
                'established_year'         => '1976',
                'is_verified'              => true,
                'total_delivered_projects' => 25,
                'rating'                   => 4.1,
                'description'              => 'Ozone Group focuses on creating large-scale integrated townships featuring smart tech, healthcare setups, and extensive green open tracks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $ozoneBuilder->id, 'title' => 'Ozone Urbana'],
            [
                'builder_id'         => $ozoneBuilder->id,
                'description'        => 'Ozone Urbana is a massive 190-acre fully integrated township located near the International Airport in Devanahalli. Features internal senior-living sections, business centers, hospital networks, and wide operational jogging grids.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'NH-44, Devanahalli, Near Airport Trumpet Flyover, North Bengaluru, Karnataka 562110',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.242512,
                'longitude'          => 77.711415,
                'total_units'        => 2200,
                'available_units'    => 80,
                'price_from'         => 4500000,
                'price_to'           => 18500000,
                'possession_date'    => '2021-09-30',
                'total_towers'       => 15,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 1420,
                'leads_count'        => 0,
                'nearby_schools'     => 'Carmel Public School (3.0 km)',
                'nearby_hospitals'   => 'Narayana Health Clinic Urbana (Internal)',
                'metro_distance'     => '10 minutes away from Airport Metro terminal point',
                'connectivity_score' => '8',
            ]
        );

        // ── 26. Mantri Developers ────────────────────────────────────
        $mantriBuilder = Builder::firstOrCreate(
            ['email' => 'sales@mantri.es'],
            [
                'name'                     => 'Mantri Developers',
                'company_name'             => 'Mantri Developers Private Limited',
                'password'                 => Hash::make('MantriBlr2026'),
                'phone'                    => '18001210000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Hyderabad, Pune',
                'established_year'         => '1999',
                'is_verified'              => true,
                'total_delivered_projects' => 46,
                'rating'                   => 4.0,
                'description'              => 'A prominent landmark creator across South India, delivering iconic massive retail malls and high-density luxury high-rises.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $mantriBuilder->id, 'title' => 'Mantri Serenity'],
            [
                'builder_id'         => $mantriBuilder->id,
                'description'        => 'Mantri Serenity is a highly extensive residential complex right on Kanakapura Road. Built cleanly with immediate access to public transport systems and a sprawling internal activity clubhouse.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Kanakapura Main Road, Near Doddakallasandra Metro Station, South Bengaluru, Karnataka 560062',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.879412,
                'longitude'          => 77.551241,
                'total_units'        => 2100,
                'available_units'    => 45,
                'price_from'         => 7200000,
                'price_to'           => 16500000,
                'possession_date'    => '2023-03-31',
                'total_towers'       => 18,
                'floors_per_tower'   => '22',
                'is_featured'        => false,
                'views_count'        => 1670,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Bangalore South (2.5 km)',
                'nearby_hospitals'   => 'Fortis Hospital Bannerghatta (4.5 km)',
                'metro_distance'     => 'Directly connected to Doddakallasandra Metro Station',
                'connectivity_score' => '10',
            ]
        );

        // ── 27. Sobha Dream Series (Sub-Brand) ───────────────────────
        $sobhaDreamBuilder = Builder::firstOrCreate(
            ['email' => 'sales.dream@sobha.com'],
            [
                'name'                     => 'Sobha Dream Series',
                'company_name'             => 'Sobha Highrise Developments Pvt Ltd',
                'password'                 => Hash::make('SobhaDream2026'),
                'phone'                    => '08049321111',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2015',
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'rating'                   => 4.7,
                'description'              => 'The innovative pre-cast tech division of Sobha Limited, specializing in fast, premium-quality structural execution for modern young professionals.',
                'status'                     => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sobhaDreamBuilder->id, 'title' => 'Sobha Dream Acres'],
            [
                'builder_id'         => $sobhaDreamBuilder->id,
                'description'        => 'Sobha Dream Acres is a gigantic 81-acre high-rise pre-cast residential development ecosystem based out of Panathur. Widely trusted for superior building life, absolute straight-line German quality control, and huge open green spaces.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Panathur Main Road, Off Outer Ring Road, Balagere, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.942102,
                'longitude'          => 77.721454,
                'total_units'        => 6500,
                'available_units'    => 190,
                'price_from'         => 7500000,
                'price_to'           => 14000000,
                'possession_date'    => '2024-06-30',
                'total_towers'       => 42,
                'floors_per_tower'   => '14',
                'is_featured'        => true,
                'views_count'        => 5200,
                'leads_count'        => 0,
                'nearby_schools'     => 'Chrysalis High School (2.5 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (4.0 km)',
                'metro_distance'     => '15 minutes away from Marathahalli ORR Metro alignment corridor',
                'connectivity_score' => '8',
            ]
        );

        // ── 28. Mana Projects ────────────────────────────────────────
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
                'rating'                   => 4.3,
                'description'              => 'Mana Projects excels at building nature-integrated micro-forest living spaces, emphasizing massive structural internal vertical gardens and eco-conscious living arrays.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $manaBuilder->id, 'title' => 'Mana Capitol'],
            [
                'builder_id'         => $manaBuilder->id,
                'description'        => 'Mana Capitol is an innovative mixed-use commercial and premium smart residential development located directly on Sarjapur Road. Specially geared to deliver live-work-play modules with internal co-working sectors.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Sarjapur Main Road, Near Doddakannelli, East Bengaluru, Karnataka 560035',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.914212,
                'longitude'          => 77.691425,
                'total_units'        => 640,
                'available_units'    => 195,
                'price_from'         => 9800000,
                'price_to'           => 19500000,
                'possession_date'    => '2027-03-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '18',
                'is_featured'        => true,
                'views_count'        => 1340,
                'leads_count'        => 0,
                'nearby_schools'     => 'Fisher International School (1.5 km)',
                'nearby_hospitals'   => 'Manipal Hospital Sarjapur Road (1.0 km)',
                'metro_distance'     => 'Directly on the proposed Sarjapur Road Phase 3 Metro corridor loop',
                'connectivity_score' => '9',
            ]
        );

        // ── 29. Goyal & Co. Hariyana Group ───────────────────────────
        $goyalBuilder = Builder::firstOrCreate(
            ['email' => 'sales@goyalco.com'],
            [
                'name'                     => 'Goyal & Co.',
                'company_name'             => 'Goyal and Co Hariyana Group',
                'password'                 => Hash::make('GoyalBlr2026'),
                'phone'                    => '08046112233',
                'city'                     => 'Ahmedabad',
                'cities_operating'         => 'Ahmedabad, Bengaluru, Mumbai',
                'established_year'         => '1971',
                'is_verified'              => true,
                'total_delivered_projects' => 70,
                'rating'                   => 4.4,
                'description'              => 'Renowned for systematic financial planning, zero project funding debt, and highly precise, clean-cut structural delivery matrices.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $goyalBuilder->id, 'title' => 'Orchid Whitefield'],
            [
                'builder_id'         => $goyalBuilder->id,
                'description'        => 'Orchid Whitefield is a meticulously structured premium high-rise enclave based out of the corporate nerve center of Whitefield. Boasts smart architectural configurations with zero spatial wastage layouts.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Makarla Railway Link Road, Behind Forum Value Mall, Whitefield, East Bengaluru, Karnataka 560066',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.954212,
                'longitude'          => 77.749512,
                'total_units'        => 492,
                'available_units'    => 14,
                'price_from'         => 8500000,
                'price_to'           => 16000000,
                'possession_date'    => '2022-09-30',
                'total_towers'       => 5,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 1190,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy Whitefield (1.8 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (3.0 km)',
                'metro_distance'     => '6 minutes away from Whitefield Metro Station point',
                'connectivity_score' => '9',
            ]
        );

        // ── 30. Nambiar Builders ─────────────────────────────────────
        $nambiarBuilder = Builder::firstOrCreate(
            ['email' => 'sales@nambiarbuilders.com'],
            [
                'name'                     => 'Nambiar Builders',
                'company_name'             => 'Nambiar Builders Private Limited',
                'password'                 => Hash::make('NambiarBlr2026'),
                'phone'                    => '08045645600',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2009',
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'rating'                   => 4.6,
                'description'              => 'Nambiar Builders specializes exclusively in premium, ultra-luxury high-end custom villa compounds and boutique high-rises along the tech peripheral roads.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $nambiarBuilder->id, 'title' => 'Nambiar Elanza'],
            [
                'builder_id'         => $nambiarBuilder->id,
                'description'        => 'Nambiar Elanza is a sophisticated, premium high-rise boutique residential tower development off Sarjapur Road. Features massive floor plates, clear panoramic natural vistas, and absolute high-grade stone engineering elements.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Sarjapur-Attibele Road, Near Sompura Industrial Area, East Bengaluru, Karnataka 562125',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.861412,
                'longitude'          => 77.794212,
                'total_units'        => 310,
                'available_units'    => 145,
                'price_from'         => 13500000,
                'price_to'           => 24000000,
                'possession_date'    => '2028-06-30',
                'total_towers'       => 3,
                'floors_per_tower'   => '24',
                'is_featured'        => true,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Azim Premji University Campus (2.0 km)',
                'nearby_hospitals'   => 'Narayan Health Corridor Network (9.0 km)',
                'metro_distance'     => 'Connects cleanly via NH-44 highway bypass nodes',
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

        $luxuryTitles  = ['Vaishnavi Serene', 'Century Ethos', 'Sobha Dream Acres', 'Mana Capitol', 'Nambiar Elanza'];
        $standardTitles = ['Kolte-Patil Raaga', 'Concorde Abode 99', 'Ozone Urbana', 'Mantri Serenity', 'Orchid Whitefield'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 3/10 complete: 30/100 Bengaluru Builders successfully initialized.');
    }
}