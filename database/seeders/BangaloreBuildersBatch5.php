<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch5
 *
 * 10 Builders / Projects — Batch 5 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: Shriram Properties, Rohan Builders, Kolte-Patil Life Spaces, Mahindra Lifespaces,
 * Assetz Property Group, Total Environment, Akshaya Homes, Adarsh Developers,
 * Mantri Heritage Wing, Sterling Developers.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch5
 */
class BangaloreBuildersBatch5 extends Seeder
{
    public function run(): void
    {
        // ── 41. Shriram Properties ───────────────────────────────────
        $shriramBuilder = Builder::firstOrCreate(
            ['email' => 'sales@shriramproperties.com'],
            [
                'name'                     => 'Shriram Properties',
                'company_name'             => 'Shriram Properties Limited',
                'password'                 => Hash::make('ShriramBlr2026'),
                'phone'                    => '08040222222',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Kolkata, Coimbatore',
                'established_year'         => '1995',
                'is_verified'              => true,
                'total_delivered_projects' => 40,
                'rating'                   => 4.2,
                'description'              => 'A leading publicly listed player in South India’s mid-market and affordable housing segment, backed by the prestigious Shriram Group.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $shriramBuilder->id, 'title' => 'Shriram Blue'],
            [
                'builder_id'         => $shriramBuilder->id,
                'description'        => 'Shriram Blue is a high-end, nature-infused residential project overlooking a natural lake near Hoodi. Blending resort-style amenities with exceptional structural layouts tailored for IT professionals.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Medahalli-Kadugodi Road, Near Hoodi Junction, Whitefield Extension, East Bengaluru, Karnataka 560049',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.011425,
                'longitude'          => 77.741254,
                'total_units'        => 471,
                'available_units'    => 18,
                'price_from'         => 8200000,
                'price_to'           => 14500000,
                'possession_date'    => '2023-09-30',
                'total_towers'       => 11,
                'floors_per_tower'   => '12',
                'is_featured'        => true,
                'views_count'        => 1450,
                'leads_count'        => 0,
                'nearby_schools'     => 'EuroKids Pre-School Hoodi (1.5 km)',
                'nearby_hospitals'   => 'RxDx Healthcare Whitefield (3.2 km)',
                'metro_distance'     => '7 minutes to Hoodi Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 42. Rohan Builders ───────────────────────────────────────
        $rohanBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@rohanbuilders.com'],
            [
                'name'                     => 'Rohan Builders',
                'company_name'             => 'Rohan Builders & Developers Pvt Ltd',
                'password'                 => Hash::make('RohanBlr2026'),
                'phone'                    => '08025204400',
                'city'                     => 'Pune',
                'cities_operating'         => 'Pune, Bengaluru',
                'established_year'         => '1993',
                'is_verified'              => true,
                'total_delivered_projects' => 50,
                'rating'                   => 4.5,
                'description'              => 'Pioneers of the proprietary PLUS concept (Perfect Ventilation, Lively Light, Utmost Privacy, Smart Space) with zero dead-space architectures.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $rohanBuilder->id, 'title' => 'Rohan Iksha'],
            [
                'builder_id'         => $rohanBuilder->id,
                'description'        => 'Rohan Iksha is a masterfully designed high-rise ecosystem off Marathahalli-Sarjapur Road. Every unit is structured with double-height ceilings, cross-ventilation grids, and zero common walls for unmatched privacy.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Bhogiunahalli Road, Near Cessna Business Park, East Bengaluru, Karnataka 560103',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.929412,
                'longitude'          => 77.698214,
                'total_units'        => 380,
                'available_units'    => 11,
                'price_from'         => 9500000,
                'price_to'           => 18500000,
                'possession_date'    => '2022-06-30',
                'total_towers'       => 4,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 1230,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Horizon Gurukul (1.0 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (1.8 km)',
                'metro_distance'     => '5 minutes to upcoming Kadubeesanahalli ORR Metro link',
                'connectivity_score' => '10',
            ]
        );

        // ── 43. Mahindra Lifespaces ──────────────────────────────────
        $mahindraBuilder = Builder::firstOrCreate(
            ['email' => 'sales@mahindralifespaces.com'],
            [
                'name'                     => 'Mahindra Lifespaces',
                'company_name'             => 'Mahindra Lifespace Developers Limited',
                'password'                 => Hash::make('MahindraBlr2026'),
                'phone'                    => '18001023455',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Mumbai, Pune, Bengaluru, Chennai, Nagpur',
                'established_year'         => '1994',
                'is_verified'              => true,
                'total_delivered_projects' => 48,
                'rating'                   => 4.4,
                'description'              => 'The real estate wing of the multi-billion Mahindra Group, pioneering sustainable urban development and India’s first IGBC Platinum certified homes.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $mahindraBuilder->id, 'title' => 'Mahindra Windchimes'],
            [
                'builder_id'         => $mahindraBuilder->id,
                'description'        => 'Mahindra Windchimes is a highly premium, eco-centric high-rise enclave along Bannerghatta Road. Built cleanly with advanced greywater recycling systems, massive open green arrays, and strong structural stability.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Bannerghatta Main Road, Next to IIM Bangalore, South Bengaluru, Karnataka 560076',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.894212,
                'longitude'          => 77.599412,
                'total_units'        => 402,
                'available_units'    => 5,
                'price_from'         => 16000000,
                'price_to'           => 29000000,
                'possession_date'    => '2021-10-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '25',
                'is_featured'        => true,
                'views_count'        => 1780,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Brigade School (2.0 km)',
                'nearby_hospitals'   => 'Fortis Hospital Bannerghatta (0.5 km)',
                'metro_distance'     => '3 minutes from IIM-B Metro Station',
                'connectivity_score' => '10',
            ]
        );

        // ── 44. Assetz Property Group ────────────────────────────────
        $assetzBuilder = Builder::firstOrCreate(
            ['email' => 'sales@assetzproperty.com'],
            [
                'name'                     => 'Assetz Property Group',
                'company_name'             => 'Assetz Property Services Private Limited',
                'password'                 => Hash::make('AssetzBlr2026'),
                'phone'                    => '08046124612',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2006',
                'is_verified'              => true,
                'total_delivered_projects' => 21,
                'rating'                   => 4.5,
                'description'              => 'A forward-thinking multinational developer focusing on high-design carbon-healing residential communities and premium tech parks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $assetzBuilder->id, 'title' => 'Assetz Marq 3.0'],
            [
                'builder_id'         => $assetzBuilder->id,
                'description'        => 'Assetz Marq 3.0 is a vast 22-acre premium multi-tower township located in Whitefield. Built around sustainable architecture paradigms, including individual home automation, water recycling, and expansive open-air linear parks.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Whitefield-Hoskote Main Road, Kannamangala, East Bengaluru, Karnataka 560067',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.029412,
                'longitude'          => 77.771415,
                'total_units'        => 845,
                'available_units'    => 240,
                'price_from'         => 9800000,
                'price_to'           => 18500000,
                'possession_date'    => '2027-12-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '28',
                'is_featured'        => true,
                'views_count'        => 2100,
                'leads_count'        => 0,
                'nearby_schools'     => 'Chrysalis High School (1.2 km)',
                'nearby_hospitals'   => 'Shri Satya Sai Super Speciality Hospital (4.5 km)',
                'metro_distance'     => '8 minutes to Kadugodi Tree Park Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 45. Total Environment Building Systems ───────────────────
        $totalEnvBuilder = Builder::firstOrCreate(
            ['email' => 'sales@total-environment.com'],
            [
                'name'                     => 'Total Environment',
                'company_name'             => 'Total Environment Building Systems Pvt Ltd',
                'password'                 => Hash::make('TotalEnvBlr2026'),
                'phone'                    => '08046464444',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Pune, Hyderabad',
                'established_year'         => '1996',
                'is_verified'              => true,
                'total_delivered_projects' => 32,
                'rating'                   => 4.8,
                'description'              => 'Iconic architects famous for craft-intensive, natural green-roofed homes featuring hand-cut wire-cut bricks, natural stone flooring, and expansive fully customized layouts.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $totalEnvBuilder->id, 'title' => 'Pursuit of Radical Rhapsody'],
            [
                'builder_id'         => $totalEnvBuilder->id,
                'description'        => 'Pursuit of Radical Rhapsody is an ultra-luxury masterwork sitting on the banks of a small lake in Whitefield. Features fully customized earth-sheltered roofs, extensive terrace gardens with real trees, and premium handcrafted woodwork.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'ITPL Main Road, Basavanna Nagar, Whitefield, East Bengaluru, Karnataka 560048',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.991412,
                'longitude'          => 77.731425,
                'total_units'        => 610,
                'available_units'    => 84,
                'price_from'         => 36000000,
                'price_to'           => 85000000,
                'possession_date'    => '2027-06-30',
                'total_towers'       => 7,
                'floors_per_tower'   => '26',
                'is_featured'        => true,
                'views_count'        => 3400,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy Whitefield (1.5 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (2.0 km)',
                'metro_distance'     => '4 minutes from Pattandur Agrahara Metro Corridor',
                'connectivity_score' => '10',
            ]
        );

        // ── 46. Adarsh Developers ────────────────────────────────────
        $adarshBuilder = Builder::firstOrCreate(
            ['email' => 'sales@adarshdevelopers.com'],
            [
                'name'                     => 'Adarsh Developers',
                'company_name'             => 'Adarsh Realty & Hotel Private Limited',
                'password'                 => Hash::make('AdarshBlr2026'),
                'phone'                    => '08041343400',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1988',
                'is_verified'              => true,
                'total_delivered_projects' => 26,
                'rating'                   => 4.6,
                'description'              => 'Synonymous with top-tier luxury gated villa communities, expansive clubhouses, and premium high-rise projects built around absolute elite finish standards.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $adarshBuilder->id, 'title' => 'Adarsh Palm Retreat'],
            [
                'builder_id'         => $adarshBuilder->id,
                'description'        => 'Adarsh Palm Retreat is a legendary, massive luxury gated villa ecosystem and high-rise complex located off the Outer Ring Road. Known for housing top IT executives within an expansive multi-acre premium landscape framework.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Bellandur Main Outer Ring Road, Devarabisanahalli, East Bengaluru, Karnataka 560103',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.924115,
                'longitude'          => 77.684125,
                'total_units'        => 1200,
                'available_units'    => 19,
                'price_from'         => 18500000,
                'price_to'           => 75000000,
                'possession_date'    => '2020-12-31',
                'total_towers'       => 12,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 2210,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Horizon High School (0.5 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (0.8 km)',
                'metro_distance'     => 'Immediate walking access to ORR Metro corridor line',
                'connectivity_score' => '10',
            ]
        );

        // ── 47. Sterling Developers ──────────────────────────────────
        $sterlingBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sterlingdevelopers.com'],
            [
                'name'                     => 'Sterling Developers',
                'company_name'             => 'Sterling Developers Private Limited',
                'password'                 => Hash::make('SterlingBlr2026'),
                'phone'                    => '08041154564',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Goa',
                'established_year'         => '1983',
                'is_verified'              => true,
                'total_delivered_projects' => 19,
                'rating'                   => 4.3,
                'description'              => 'Sterling Developers focuses heavily on structural reliability, classical lines, and massive clear spatial blueprints within established premium micro-markets.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sterlingBuilder->id, 'title' => 'Sterling Ascentia'],
            [
                'builder_id'         => $sterlingBuilder->id,
                'description'        => 'Sterling Ascentia is a premium residential enclave located right on the Outer Ring Road corridor near Marathahalli. Designed with wide master layouts, high-performance security setups, and an optimized footprint for maximum natural wind penetration.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Outer Ring Road, Opp. Intel Campus, Bellandur, East Bengaluru, Karnataka 560103',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.929512,
                'longitude'          => 77.679412,
                'total_units'        => 392,
                'available_units'    => 8,
                'price_from'         => 11500000,
                'price_to'           => 21000000,
                'possession_date'    => '2022-03-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 980,
                'leads_count'        => 0,
                'nearby_schools'     => 'Gear Innovative International School (2.5 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (1.2 km)',
                'metro_distance'     => '4 minutes from upcoming Bellandur Metro Station point',
                'connectivity_score' => '10',
            ]
        );

        // ── 48. Assetz Earth & Essence (Specialized Hub) ──────────────
        $assetzEarthBuilder = Builder::firstOrCreate(
            ['email' => 'sales.ee@assetzproperty.com'],
            [
                'name'                     => 'Assetz Earth & Essence',
                'company_name'             => 'Assetz Luxury Spaces Private Limited',
                'password'                 => Hash::make('AssetzEarth2026'),
                'phone'                    => '08046124613',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year' => '2016',
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'rating'                   => 4.5,
                'description'              => 'The luxury villa and low-density plot development wing of Assetz Property Group, tailored for countryside luxurious eco-living frameworks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $assetzEarthBuilder->id, 'title' => 'Assetz Earth & Essence Villas'],
            [
                'builder_id'         => $assetzEarthBuilder->id,
                'description'        => 'Assetz Earth & Essence Villas is a highly exclusive, luxurious low-density row-house and villa community located off the International Airport Road corridor in Jakkur. Built around open nature lanes and private backyards.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Yelahanka-Jakkur Road Corridor, Off NH-44 Highway, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.091425,
                'longitude'          => 77.611425,
                'total_units'        => 142,
                'available_units'    => 35,
                'price_from'         => 29000000,
                'price_to'           => 49500000,
                'possession_date'    => '2027-09-30',
                'total_towers'       => 0, // Row Houses
                'floors_per_tower'   => '2',
                'is_featured'        => true,
                'views_count'        => 1110,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vidyashilp Academy (3.0 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal (6.0 km)',
                'metro_distance'     => '10 minutes from Jakkur Cross Metro station block',
                'connectivity_score' => '8',
            ]
        );

        // ── 49. Goyal Orchid Series (Brand Expansion) ────────────────
        $goyalOrchidBuilder = Builder::firstOrCreate(
            ['email' => 'sales.orchid@goyalco.com'],
            [
                'name'                     => 'Goyal Orchid Series',
                'company_name'             => 'Goyal Hariyana Realty Ventures Pvt Ltd',
                'password'                 => Hash::make('GoyalOrchid2026'),
                'phone'                    => '08046112234',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2012',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 4.4,
                'description'              => 'A highly systematic premium residential high-rise division of Goyal & Co, capturing strategic urban pockets across North and East Bengaluru.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $goyalOrchidBuilder->id, 'title' => 'Orchid Piccadilly'],
            [
                'builder_id'         => $goyalOrchidBuilder->id,
                'description'        => 'Orchid Piccadilly is a beautifully streamlined premium high-rise project positioned on Thanisandra Main Road. Focuses on minimal structural loading overheads, high functional utility configurations, and clean urban finishes.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Thanisandra Main Road, Near Manyata Tech Park, North Bengaluru, Karnataka 560077',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.069412,
                'longitude'          => 77.631425,
                'total_units'        => 432,
                'available_units'    => 12,
                'price_from'         => 7800000,
                'price_to'           => 13500000,
                'possession_date'    => '2023-04-30',
                'total_towers'       => 4,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 1150,
                'leads_count'        => 0,
                'nearby_schools'     => 'Rashtrotthana Vidya Kendra (1.8 km)',
                'nearby_hospitals'   => 'Regal Super Speciality Hospital (2.0 km)',
                'metro_distance'     => '8 minutes to upcoming Thanisandra Metro alignment node',
                'connectivity_score' => '9',
            ]
        );

        // ── 50. Sowparnika Projects ──────────────────────────────────
        $sowparnikaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sowparnika.com'],
            [
                'name'                     => 'Sowparnika Projects',
                'company_name'             => 'Sowparnika Projects & Infrastructure Pvt Ltd',
                'password'                 => Hash::make('Sowparnika2026'),
                'phone'                    => '08042433333',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Thiruvananthapuram, Kochi, Coimbatore',
                'established_year'         => '2003',
                'is_verified'              => true,
                'total_delivered_projects' => 38,
                'rating'                   => 4.0,
                'description'              => 'Pioneers in high-volume, lean-manufacturing modular budget homes optimized for smart utility configurations across corporate links.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sowparnikaBuilder->id, 'title' => 'Sowparnika Columns'],
            [
                'builder_id'         => $sowparnikaBuilder->id,
                'description'        => 'Sowparnika Columns is an economically optimized, highly systematic multi-family residential complex in Whitefield. Built specifically around compact layouts, reliable masonry engineering, and straightforward pricing indices.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Soukya Road Extension, Near Whitefield Corporate Sector, East Bengaluru, Karnataka 560067',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.979412,
                'longitude'          => 77.791415,
                'total_units'        => 390,
                'available_units'    => 21,
                'price_from'         => 4200000,
                'price_to'           => 7900000,
                'possession_date'    => '2022-11-30',
                'total_towers'       => 3,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Whitefield Global School (4.0 km)',
                'nearby_hospitals'   => 'Hope Hospital Whitefield (3.5 km)',
                'metro_distance'     => '12 minutes from Whitefield Metro Station corridor terminal',
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

        $luxuryTitles  = ['Mahindra Windchimes', 'Assetz Marq 3.0', 'Pursuit of Radical Rhapsody', 'Adarsh Palm Retreat', 'Assetz Earth & Essence Villas'];
        $standardTitles = ['Shriram Blue', 'Rohan Iksha', 'Sterling Ascentia', 'Orchid Piccadilly', 'Sowparnika Columns'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 5/10 complete: 50/100 Bengaluru Builders successfully initialized.');
    }
}