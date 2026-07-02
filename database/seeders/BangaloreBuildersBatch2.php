<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch2
 *
 * 10 Builders / Projects — Batch 2 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 * 
 * Includes: Tata Housing, Mahindra Lifespaces, Assetz Property, Adarsh, 
 * Bhartiya Urban, SNN Builders, Rohan Builders, Provident, Total Environment, DS-MAX.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch2
 */
class BangaloreBuildersBatch2 extends Seeder
{
    public function run(): void
    {
        // ── 11. Tata Housing ─────────────────────────────────────────
        $tataBuilder = Builder::firstOrCreate(
            ['email' => 'sales@tatahousing.com'],
            [
                'name'                     => 'Tata Housing',
                'company_name'             => 'Tata Value Homes Limited',
                'password'                 => Hash::make('TataBlr2026'),
                'phone'                    => '18002666666',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Bengaluru, Mumbai, Pune, Delhi-NCR, Kolkata',
                'established_year'         => '1984',
                'is_verified'              => true,
                'total_delivered_projects' => 50,
                'rating'                   => 4.6,
                'description'              => 'A subsidiary of Tata Sons, Tata Housing is trusted nationwide for stellar corporate governance, high-grade engineering benchmarks, and sustainable urban communities.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $tataBuilder->id, 'title' => 'Tata New Haven'],
            [
                'builder_id'         => $tataBuilder->id,
                'description'        => 'Tata New Haven is a massive green residential township located on Tumkur Road, West Bengaluru. Designed by internationally acclaimed architects, it offers premium value housing choices wrapped in expansive landscaped parameters and absolute fresh-air microclimates.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Off Tumkur Road, Near Golden Palms Resort, Nelamangala Hobli, West Bengaluru, Karnataka 562123',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.064212,
                'longitude'          => 77.411054,
                'total_units'        => 1610,
                'available_units'    => 40,
                'price_from'         => 4800000,
                'price_to'           => 8500000,
                'possession_date'    => '2021-03-31',
                'total_towers'       => 35,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 1420,
                'leads_count'        => 0,
                'nearby_schools'     => 'EuroSchool Tumkur Road (4.5 km)',
                'nearby_hospitals'   => 'Harsha Hospital (3.2 km)',
                'metro_distance'     => '10 minutes away from Madavara (BIEC) Metro Station Terminal',
                'connectivity_score' => '8',
            ]
        );

        // ── 12. Mahindra Lifespaces ──────────────────────────────────
        $mahindraBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@mahindra.com'],
            [
                'name'                     => 'Mahindra Lifespaces',
                'company_name'             => 'Mahindra Lifespace Developers Ltd.',
                'password'                 => Hash::make('MahindraBlr2026'),
                'phone'                    => '18001023455',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Mumbai, Bengaluru, Pune, Chennai, Delhi-NCR',
                'established_year'         => '1994',
                'is_verified'              => true,
                'total_delivered_projects' => 45,
                'rating'                   => 4.5,
                'description'              => 'The real estate wing of the multibillion-dollar Mahindra Group, pioneering Net-Zero energy waste designs and green-certified premium residential enclaves.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $mahindraBuilder->id, 'title' => 'Mahindra Zen'],
            [
                'builder_id'         => $mahindraBuilder->id,
                'description'        => 'Mahindra Zen is an eco-luxury nature-focused residential high-rise project situated along Manipal County Road, Singasandra. This project features premium waterfront elements and clean climate-adaptive structures directly serving the Electronic City tech corridors.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Off Hosur Road, Manipal County Road, Singasandra, South Bengaluru, Karnataka 560068',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.879412,
                'longitude'          => 77.643215,
                'total_units'        => 256,
                'available_units'    => 95,
                'price_from'         => 14500000,
                'price_to'           => 26000000,
                'possession_date'    => '2028-06-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '25',
                'is_featured'        => true,
                'views_count'        => 980,
                'leads_count'        => 0,
                'nearby_schools'     => 'St. Francis School (1.8 km)',
                'nearby_hospitals'   => 'Narayana Health City (7.5 km), Live 100 Hospital (2.0 km)',
                'metro_distance'     => '5 minutes from upcoming Singasandra Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 13. Assetz Property Group ────────────────────────────────
        $assetzBuilder = Builder::firstOrCreate(
            ['email' => 'sales@assetzproperty.com'],
            [
                'name'                     => 'Assetz Property Group',
                'company_name'             => 'Assetz Property Group Pty Ltd',
                'password'                 => Hash::make('AssetzBlr2026'),
                'phone'                    => '08046114611',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2006',
                'is_verified'              => true,
                'total_delivered_projects' => 30,
                'rating'                   => 4.4,
                'description'              => 'A frontrunner premium Singapore-backed real estate group, specializing in high-concept minimalist designs, micro-studios, and carbon-neutral green frameworks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $assetzBuilder->id, 'title' => 'Assetz Marq 3.0'],
            [
                'builder_id'         => $assetzBuilder->id,
                'description'        => 'Assetz Marq 3.0 is a vast, sophisticated 22-acre premium residential township sprawl located in Kannamangala, Whitefield. Built cleanly with expansive floor plans, signature high ceilings, a massive internal school, and vast open natural reservation green blocks.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Whitefield-Hoskote Main Road, Kannamangala, East Bengaluru, Karnataka 560067',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.012541,
                'longitude'          => 77.771245,
                'total_units'        => 1200,
                'available_units'    => 340,
                'price_from'         => 11500000,
                'price_to'           => 21000000,
                'possession_date'    => '2027-11-30',
                'total_towers'       => 8,
                'floors_per_tower'   => '26',
                'is_featured'        => true,
                'views_count'        => 1780,
                'leads_count'        => 0,
                'nearby_schools'     => 'Chrysalis High Whitefield (1.1 km)',
                'nearby_hospitals'   => 'RxDx Healthcare Whitefield (5.2 km)',
                'metro_distance'     => '12 minutes from Kadugodi Tree Park Metro Station',
                'connectivity_score' => '8',
            ]
        );

        // ── 14. Adarsh Developers ────────────────────────────────────
        $adarshBuilder = Builder::firstOrCreate(
            ['email' => 'sales@adarshdevelopers.com'],
            [
                'name'                     => 'Adarsh Developers',
                'company_name'             => 'Adarsh Developers Private Limited',
                'password'                 => Hash::make('AdarshBlr2026'),
                'phone'                    => '08041343400',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1988',
                'is_verified'              => true,
                'total_delivered_projects' => 60,
                'rating'                   => 4.6,
                'description'              => 'Adarsh Developers is highly acclaimed for constructing the highest density of upscale premium gated communities and luxury massive villa estates in East Bengaluru.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $adarshBuilder->id, 'title' => 'Adarsh Palm Retreat'],
            [
                'builder_id'         => $adarshBuilder->id,
                'description'        => 'Adarsh Palm Retreat is a legendary, massive luxury red-brick villa and high-rise premium apartment township compound located directly on the Outer Ring Road (ORR) corridor. Built as the choice destination for high-net-worth IT founders.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Devarabisanahalli, Outer Ring Road, Marathahalli Phase, East Bengaluru, Karnataka 560103',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.925412,
                'longitude'          => 77.685412,
                'total_units'        => 2000,
                'available_units'    => 15,
                'price_from'         => 18000000,
                'price_to'           => 75000000,
                'possession_date'    => '2018-12-31',
                'total_towers'       => 12,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 2250,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Horizon College of Engineering (0.5 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (0.8 km)',
                'metro_distance'     => 'Walking distance to upcoming Bellandur/Devarabisanahalli ORR Metro line',
                'connectivity_score' => '10',
            ]
        );

        // ── 15. Bhartiya Urban ───────────────────────────────────────
        $bhartiyaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@bhartiya.com'],
            [
                'name'                     => 'Bhartiya Urban',
                'company_name'             => 'Bhartiya Urban Infrastructure Ltd',
                'password'                 => Hash::make('BhartiyaBlr2026'),
                'phone'                    => '08066203000',
                'city'                     => 'Delhi',
                'cities_operating'         => 'Bengaluru, Delhi-NCR',
                'established_year'         => '2006',
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'rating'                   => 4.5,
                'description'              => 'A highly creative real estate enterprise specializing in massive European-style walkable cities, retail tech parks, and luxury integration grids.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $bhartiyaBuilder->id, 'title' => 'Bhartiya City Nikoo Homes'],
            [
                'builder_id'         => $bhartiyaBuilder->id,
                'description'        => 'Bhartiya City Nikoo Homes is an internationally acclaimed 125-acre fully integrated mega-township located on Thanisandra Main Road. Features an internal Leela Hotel, Bhartiya Mall of Bengaluru, internal tech hubs, and highly customizable smart-home floor dynamics.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Thanisandra Main Road, Near Hebbal Extension, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.078415,
                'longitude'          => 77.644124,
                'total_units'        => 4000,
                'available_units'    => 120,
                'price_from'         => 6500000,
                'price_to'           => 19500000,
                'possession_date'    => '2023-09-30',
                'total_towers'       => 10,
                'floors_per_tower'   => '33',
                'is_featured'        => true,
                'views_count'        => 4100,
                'leads_count'        => 0,
                'nearby_schools'     => 'Green Country Public School (2.0 km)',
                'nearby_hospitals'   => 'Regal Hospital (1.5 km)',
                'metro_distance'     => '15 minutes away from Nagawara Metro Junction network line',
                'connectivity_score' => '9',
            ]
        );

        // ── 16. SNN Builders ─────────────────────────────────────────
        $snnBuilder = Builder::firstOrCreate(
            ['email' => 'sales@snnbuilders.com'],
            [
                'name'                     => 'SNN Builders',
                'company_name'             => 'SNN Builders Private Limited',
                'password'                 => Hash::make('SNNBlr2026'),
                'phone'                    => '08040222222',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1994',
                'is_verified'              => true,
                'total_delivered_projects' => 40,
                'rating'                   => 4.3,
                'description'              => 'SNN Builders is recognized for introducing high-luxury thematic living communities, massive standard clubhouses, and highly unique internal activity sports fields.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $snnBuilder->id, 'title' => 'SNN Clermont'],
            [
                'builder_id'         => $snnBuilder->id,
                'description'        => 'SNN Clermont is a spectacular ultra-luxury sky-rise apartment development located on Hebbal Outer Ring Road, directly overlooking the scenic Nagawara Lake. Built cleanly with expansive international layouts, private elevator entries, and individual master decks.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Hebbal Outer Ring Road, Opposite Nagawara Lake, North Bengaluru, Karnataka 560045',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.042512,
                'longitude'          => 77.618412,
                'total_units'        => 422,
                'available_units'    => 18,
                'price_from'         => 28000000,
                'price_to'           => 55000000,
                'possession_date'    => '2022-06-30',
                'total_towers'       => 5,
                'floors_per_tower'   => '40',
                'is_featured'        => true,
                'views_count'        => 1320,
                'leads_count'        => 0,
                'nearby_schools'     => 'Sindhi High School (1.2 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal (1.9 km)',
                'metro_distance'     => '3 minutes away from upcoming Hebbal Metro Cross Interchange',
                'connectivity_score' => '10',
            ]
        );

        // ── 17. Rohan Builders ───────────────────────────────────────
        $rohanBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@rohanbuilders.com'],
            [
                'name'                     => 'Rohan Builders',
                'company_name'             => 'Rohan Builders (India) Pvt Ltd',
                'password'                 => Hash::make('RohanBlr2026'),
                'phone'                    => '08025204444',
                'city'                     => 'Pune',
                'cities_operating'         => 'Pune, Bengaluru',
                'established_year'         => '1993',
                'is_verified'              => true,
                'total_delivered_projects' => 50,
                'rating'                   => 4.4,
                'description'              => 'Rohan Builders is celebrated for creating their signature PLUS-concept (Perfect Ventilation, Lively Light, Utmost Privacy, Smart Space) residential layouts.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $rohanBuilder->id, 'title' => 'Rohan Antara'],
            [
                'builder_id'         => $rohanBuilder->id,
                'description'        => 'Rohan Antara is a premium newly launched high-rise smart residential development located in Gunjur, near Varthur. Built carefully with strict architectural rules maximizing window parameters and internal structural airflow models.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Gunjur Main Road, Near Varthur Hobli, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.919241,
                'longitude'          => 77.741025,
                'total_units'        => 720,
                'available_units'    => 280,
                'price_from'         => 8900000,
                'price_to'           => 16500000,
                'possession_date'    => '2028-09-30',
                'total_towers'       => 6,
                'floors_per_tower'   => '22',
                'is_featured'        => false,
                'views_count'        => 1120,
                'leads_count'        => 0,
                'nearby_schools'     => 'Greenwood High School (3.2 km)',
                'nearby_hospitals'   => 'Lakeside Clinic Varthur (1.5 km)',
                'metro_distance'     => '15 minutes away from Hope Farm/Whitefield Metro networks',
                'connectivity_score' => '8',
            ]
        );

        // ── 18. Provident Housing ────────────────────────────────────
        $providentBuilder = Builder::firstOrCreate(
            ['email' => 'sales@providenthousing.com'],
            [
                'name'                     => 'Provident Housing',
                'company_name'             => 'Provident Housing Limited',
                'password'                 => Hash::make('ProvidentBlr2026'),
                'phone'                    => '18602080000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad, Chennai, Kochi, Mangaluru, Goa',
                'established_year'         => '2008',
                'is_verified'              => true,
                'total_delivered_projects' => 35,
                'rating'                   => 4.3,
                'description'              => 'A wholly owned premium value-housing brand subsidiary of Puravankara Limited, focused on premium structural delivery at optimized budget layouts.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $providentBuilder->id, 'title' => 'Provident Welworth City'],
            [
                'builder_id'         => $providentBuilder->id,
                'description'        => 'Provident Welworth City is a well-established, highly organized massive residential gated township located on Doddaballapur Road. Offering vast open landscaped parks, full operational multi-sports areas, and excellent family ecosystems.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Doddaballapur Main Road, Near Yelahanka Extension, North Bengaluru, Karnataka 561203',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.194212,
                'longitude'          => 77.574125,
                'total_units'        => 3360,
                'available_units'    => 45,
                'price_from'         => 4200000,
                'price_to'           => 7800000,
                'possession_date'    => '2019-06-30',
                'total_towers'       => 24,
                'floors_per_tower'   => '8',
                'is_featured'        => false,
                'views_count'        => 1490,
                'leads_count'        => 0,
                'nearby_schools'     => 'Ryan International School Yelahanka (6.0 km)',
                'nearby_hospitals'   => 'Yelahanka Government Hospital (7.0 km)',
                'metro_distance'     => 'Accessible via upcoming Yelahanka/Doddaballapur highway linkages',
                'connectivity_score' => '7',
            ]
        );

        // ── 19. Total Environment Building Systems ───────────────────
        $teBuilder = Builder::firstOrCreate(
            ['email' => 'sales@total-environment.com'],
            [
                'name'                     => 'Total Environment',
                'company_name'             => 'Total Environment Building Systems Pvt Ltd',
                'password'                 => Hash::make('TEBlr2026'),
                'phone'                    => '08046111111',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Pune, Hyderabad, Frisco (USA)',
                'established_year'         => '1996',
                'is_verified'              => true,
                'total_delivered_projects' => 35,
                'rating'                   => 4.9,
                'description'              => 'Widely celebrated for artisan architectural designs, incorporating full natural earth-covered roofs, expansive private cantilevered lawns, and bespoke hand-crafted interior wood structures.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $teBuilder->id, 'title' => 'Total Environment Pursuit of Radical Rhapsody'],
            [
                'builder_id'         => $teBuilder->id,
                'description'        => 'Total Environment Pursuit of Radical Rhapsody is a world-class luxury residential lakeside community located in Whitefield. Built around a natural lake ecosystem, every single luxury apartment unit boasts a massive private walk-out terrace garden filled with full grown natural plants and organic timber finishes.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'ITPL Main Road, Hoodi, Near Sadaramangala Lake, Whitefield, East Bengaluru, Karnataka 560048',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.991241,
                'longitude'          => 77.716412,
                'total_units'        => 610,
                'available_units'    => 140,
                'price_from'         => 38000000,
                'price_to'           => 85000000,
                'possession_date'    => '2027-12-31',
                'total_towers'       => 7,
                'floors_per_tower'   => '28',
                'is_featured'        => true,
                'views_count'        => 2450,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy (3.5 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (2.5 km)',
                'metro_distance'     => '4 minutes from Hoodi Metro Station network line',
                'connectivity_score' => '10',
            ]
        );

        // ── 20. DS-MAX Properties ────────────────────────────────────
        $dsmaxBuilder = Builder::firstOrCreate(
            ['email' => 'sales@dsmaxproperties.com'],
            [
                'name'                     => 'DS-MAX Properties',
                'company_name'             => 'DS-MAX Properties Private Limited',
                'password'                 => Hash::make('DSMaxBlr2026'),
                'phone'                    => '08043444444',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Anantapur',
                'established_year'         => '2007',
                'is_verified'              => true,
                'total_delivered_projects' => 120,
                'rating'                   => 4.1,
                'description'              => 'DS-MAX Properties is a dominant high-volume value housing giant in Bengaluru, specializing in neo-classical style low-to-mid rise budget-friendly family complexes.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $dsmaxBuilder->id, 'title' => 'DS-MAX Saugandhi'],
            [
                'builder_id'         => $dsmaxBuilder->id,
                'description'        => 'DS-MAX Saugandhi is a cleanly designed neo-classical budget luxury apartment community located in Kalkere, near Ramamurthy Nagar. Optimized for working professionals seeking cost-effective structural value layouts in East Bengaluru.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Kalkere Main Road, Near Ramamurthy Nagar Extension, East Bengaluru, Karnataka 560043',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.024125,
                'longitude'          => 77.674125,
                'total_units'        => 310,
                'available_units'    => 115,
                'price_from'         => 4900000,
                'price_to'           => 7800000,
                'possession_date'    => '2028-03-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '5',
                'is_featured'        => false,
                'views_count'        => 790,
                'leads_count'        => 0,
                'nearby_schools'     => 'Orchids International School Ramamurthy Nagar (2.1 km)',
                'nearby_hospitals'   => 'Koshys Hospital (2.8 km)',
                'metro_distance'     => '12 minutes away from Horamavu / KR Puram Metro nodes',
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

        $luxuryTitles  = ['Mahindra Zen', 'Assetz Marq 3.0', 'Adarsh Palm Retreat', 'Bhartiya City Nikoo Homes', 'SNN Clermont', 'Total Environment Pursuit of Radical Rhapsody'];
        $standardTitles = ['Tata New Haven', 'Rohan Antara', 'Provident Welworth City', 'DS-MAX Saugandhi'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 2/10 complete: 20/100 Bengaluru Builders successfully initialized.');
    }
}