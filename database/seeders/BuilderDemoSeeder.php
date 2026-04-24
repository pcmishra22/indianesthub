<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Property;
use App\Models\Amenity;

class BuilderDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. Prestige Group
        // ─────────────────────────────────────────────
        $prestige = Builder::firstOrCreate(
            ['email' => 'prestige@demo.com'],
            [
                'name'                    => 'Venkat Narayana',
                'company_name'            => 'Prestige Group',
                'password'                => Hash::make('password'),
                'phone'                   => '9876540001',
                'city'                    => 'Bengaluru',
                'cities_operating'        => 'Bengaluru, Chennai, Hyderabad, Mumbai',
                'established_year'        => '1986',
                'rera_registration'       => 'PRM/KA/RERA/1251/309/PR/171017/000001',
                'is_verified'             => true,
                'total_delivered_projects'=> 285,
                'rating'                  => 4.6,
                'description'             => 'Prestige Group is one of South India\'s leading real estate developers with over 285 completed projects spanning residential, commercial, retail, leisure and hospitality segments. With a legacy of 35+ years, we deliver world-class spaces across 12 cities.',
                'website'                 => 'https://prestigeconstructions.com',
                'status'                  => 'active',
            ]
        );

        // Project 1 – Prestige
        $p1 = BuilderProject::firstOrCreate(
            ['builder_id' => $prestige->id, 'title' => 'Prestige Lakeside Habitat'],
            [
                'builder_id'        => $prestige->id,
                'description'       => 'A sprawling township spanning 271 acres with 80% open spaces, direct lake frontage, and world-class amenities. Features premium 2, 3 & 4 BHK apartments with panoramic views.',
                'project_type'      => 'Residential',
                'status'            => 'Ready to Move',
                'address'           => 'Whitefield Road, Varthur',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'total_units'       => 3426,
                'available_units'   => 120,
                'price_from'        => 8500000,
                'price_to'          => 28000000,
                'possession_date'   => '2024-06-30',
                'total_towers'      => 18,
                'floors_per_tower'  => 22,
                'rera_id'           => 'PRM/KA/RERA/1251/001',
                'is_featured'       => true,
                'views_count'       => 1850,
                'leads_count'       => 42,
                'metro_distance'    => '3 km from Whitefield Metro',
                'connectivity_score'=> 8,
                'nearby_schools'    => 'DPS Whitefield (1.2km), Ryan International (2km), Inventure Academy (3km)',
                'nearby_hospitals'  => 'Columbia Asia (2.5km), Manipal Hospital (4km)',
                'video_url'         => 'https://www.youtube.com/watch?v=demo1',
            ]
        );

        // Project 2 – Prestige
        $p2 = BuilderProject::firstOrCreate(
            ['builder_id' => $prestige->id, 'title' => 'Prestige Elysian'],
            [
                'builder_id'        => $prestige->id,
                'description'       => 'Ultra-luxury high-rise residences at Bannerghatta Road with breathtaking city skyline views. Premium 3 & 4 BHK apartments with Italian marble flooring, modular kitchen, and smart home features.',
                'project_type'      => 'Residential',
                'status'            => 'Under Construction',
                'address'           => 'Bannerghatta Road, JP Nagar',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'total_units'       => 816,
                'available_units'   => 384,
                'price_from'        => 12000000,
                'price_to'          => 35000000,
                'possession_date'   => '2026-12-31',
                'total_towers'      => 6,
                'floors_per_tower'  => 28,
                'rera_id'           => 'PRM/KA/RERA/1251/002',
                'is_featured'       => true,
                'views_count'       => 2100,
                'leads_count'       => 68,
                'metro_distance'    => '1.8 km from Jayadeva Metro',
                'connectivity_score'=> 9,
                'nearby_schools'    => 'National Public School (500m), DPS South (1.5km)',
                'nearby_hospitals'  => 'Fortis Hospital (1km), Apollo (3km)',
            ]
        );

        // ─────────────────────────────────────────────
        // 2. DLF Limited
        // ─────────────────────────────────────────────
        $dlf = Builder::firstOrCreate(
            ['email' => 'dlf@demo.com'],
            [
                'name'                    => 'Rajiv Singh',
                'company_name'            => 'DLF Limited',
                'password'                => Hash::make('password'),
                'phone'                   => '9876540002',
                'city'                    => 'Gurugram',
                'cities_operating'        => 'Gurugram, Delhi, Noida, Chennai, Kolkata, Chandigarh',
                'established_year'        => '1946',
                'rera_registration'       => 'HRERA-PKL-FBD-2021-001',
                'is_verified'             => true,
                'total_delivered_projects'=> 175,
                'rating'                  => 4.4,
                'description'             => 'DLF Limited is India\'s largest real estate developer with a 75-year legacy. Known for iconic developments across residential, commercial and retail segments. We have delivered over 340 million sq ft of real estate across 15 states.',
                'website'                 => 'https://www.dlf.in',
                'status'                  => 'active',
            ]
        );

        $p3 = BuilderProject::firstOrCreate(
            ['builder_id' => $dlf->id, 'title' => 'DLF The Crest'],
            [
                'builder_id'        => $dlf->id,
                'description'       => 'DLF The Crest is an ultra-luxury residential project in DLF 5, Sector 54, Gurgaon. Offering expansive 4 & 5 BHK residences with 9ft high ceilings, floor-to-ceiling windows and premium imported finishes.',
                'project_type'      => 'Residential',
                'status'            => 'Ready to Move',
                'address'           => 'Sector 54, DLF Phase 5',
                'city'              => 'Gurugram',
                'state'             => 'Haryana',
                'total_units'       => 530,
                'available_units'   => 45,
                'price_from'        => 45000000,
                'price_to'          => 120000000,
                'possession_date'   => '2023-03-31',
                'total_towers'      => 5,
                'floors_per_tower'  => 35,
                'rera_id'           => 'HRERA-GGM-2019-001',
                'is_featured'       => true,
                'views_count'       => 3200,
                'leads_count'       => 95,
                'metro_distance'    => '800m from Sector 54 Chowk Metro',
                'connectivity_score'=> 9,
                'nearby_schools'    => 'DPS International (1km), Shri Ram School (2km)',
                'nearby_hospitals'  => 'Medanta Hospital (3km), Artemis (4km)',
            ]
        );

        $p4 = BuilderProject::firstOrCreate(
            ['builder_id' => $dlf->id, 'title' => 'DLF Privana West'],
            [
                'builder_id'        => $dlf->id,
                'description'       => 'DLF Privana West is a luxury residential enclave in Sector 76-77 Gurugram. Exclusive 4 BHK sky villas with private terraces, curated interiors, and 360° green views.',
                'project_type'      => 'Residential',
                'status'            => 'Upcoming',
                'address'           => 'Sector 76-77',
                'city'              => 'Gurugram',
                'state'             => 'Haryana',
                'total_units'       => 1113,
                'available_units'   => 1113,
                'price_from'        => 35000000,
                'price_to'          => 65000000,
                'possession_date'   => '2028-12-31',
                'total_towers'      => 8,
                'floors_per_tower'  => 40,
                'rera_id'           => 'HRERA-GGM-2024-007',
                'is_featured'       => false,
                'views_count'       => 1560,
                'leads_count'       => 48,
                'metro_distance'    => '2.5 km from Sector 76 Metro (planned)',
                'connectivity_score'=> 7,
                'nearby_schools'    => 'GD Goenka World School (2km)',
                'nearby_hospitals'  => 'Fortis Memorial (5km)',
            ]
        );

        // ─────────────────────────────────────────────
        // 3. Lodha Group
        // ─────────────────────────────────────────────
        $lodha = Builder::firstOrCreate(
            ['email' => 'lodha@demo.com'],
            [
                'name'                    => 'Abhishek Lodha',
                'company_name'            => 'Lodha Group',
                'password'                => Hash::make('password'),
                'phone'                   => '9876540003',
                'city'                    => 'Mumbai',
                'cities_operating'        => 'Mumbai, Pune, Hyderabad, London, Dubai',
                'established_year'        => '1980',
                'rera_registration'       => 'P51800021519',
                'is_verified'             => true,
                'total_delivered_projects'=> 92,
                'rating'                  => 4.5,
                'description'             => 'Lodha Group is India\'s #1 real estate developer by sales. Creating extraordinary environments that enrich the lives of people. With a presence in India and internationally, we deliver uncompromising quality in every project.',
                'website'                 => 'https://www.lodhagroup.in',
                'status'                  => 'active',
            ]
        );

        $p5 = BuilderProject::firstOrCreate(
            ['builder_id' => $lodha->id, 'title' => 'Lodha Malabar'],
            [
                'builder_id'        => $lodha->id,
                'description'       => 'The most expensive residential address in India — Lodha Malabar at Walkeshwar, South Mumbai. Ultra-exclusive residences offering panoramic views of the Arabian Sea and the iconic Rajabai Clock Tower.',
                'project_type'      => 'Residential',
                'status'            => 'Under Construction',
                'address'           => 'Walkeshwar Road, Malabar Hill',
                'city'              => 'Mumbai',
                'state'             => 'Maharashtra',
                'total_units'       => 55,
                'available_units'   => 22,
                'price_from'        => 300000000,
                'price_to'          => 1200000000,
                'possession_date'   => '2027-06-30',
                'total_towers'      => 2,
                'floors_per_tower'  => 45,
                'rera_id'           => 'P51800045001',
                'is_featured'       => true,
                'views_count'       => 5400,
                'leads_count'       => 32,
                'metro_distance'    => '2 km from Marine Lines Metro',
                'connectivity_score'=> 8,
                'nearby_schools'    => 'Cathedral & John Connon School (1km)',
                'nearby_hospitals'  => 'Jaslok Hospital (1.5km), Breach Candy (2km)',
            ]
        );

        $p6 = BuilderProject::firstOrCreate(
            ['builder_id' => $lodha->id, 'title' => 'Lodha Bellevue'],
            [
                'builder_id'        => $lodha->id,
                'description'       => 'Lodha Bellevue at Mahalaxmi is redefining luxury living in Central Mumbai. 2, 3 & 4 BHK residences offering sweeping views of the Mahalaxmi Racecourse and the sea.',
                'project_type'      => 'Residential',
                'status'            => 'Ready to Move',
                'address'           => 'Dr. E Moses Road, Mahalaxmi',
                'city'              => 'Mumbai',
                'state'             => 'Maharashtra',
                'total_units'       => 328,
                'available_units'   => 56,
                'price_from'        => 35000000,
                'price_to'          => 120000000,
                'possession_date'   => '2023-09-30',
                'total_towers'      => 3,
                'floors_per_tower'  => 52,
                'rera_id'           => 'P51800038722',
                'is_featured'       => false,
                'views_count'       => 2890,
                'leads_count'       => 74,
                'metro_distance'    => '400m from Mahalaxmi Metro',
                'connectivity_score'=> 10,
                'nearby_schools'    => 'St. Xavier\'s High School (800m)',
                'nearby_hospitals'  => 'KEM Hospital (2km), Hinduja (3km)',
            ]
        );

        // ─────────────────────────────────────────────
        // 4. Sobha Limited
        // ─────────────────────────────────────────────
        $sobha = Builder::firstOrCreate(
            ['email' => 'sobha@demo.com'],
            [
                'name'                    => 'PNC Menon',
                'company_name'            => 'Sobha Limited',
                'password'                => Hash::make('password'),
                'phone'                   => '9876540004',
                'city'                    => 'Bengaluru',
                'cities_operating'        => 'Bengaluru, Gurugram, Thrissur, Chennai, Pune',
                'established_year'        => '1995',
                'rera_registration'       => 'PRM/KA/RERA/1251/309/PR/180110/002222',
                'is_verified'             => true,
                'total_delivered_projects'=> 160,
                'rating'                  => 4.7,
                'description'             => 'Sobha Limited is a multinational real estate company headquartered in Bengaluru. Known for its backward integration model, Sobha does everything in-house — from civil work to interior design, ensuring exceptional quality control.',
                'website'                 => 'https://www.sobha.com',
                'status'                  => 'active',
            ]
        );

        $p7 = BuilderProject::firstOrCreate(
            ['builder_id' => $sobha->id, 'title' => 'Sobha City'],
            [
                'builder_id'        => $sobha->id,
                'description'       => 'Sobha City is a 75-acre integrated township at Thanisandra Main Road, North Bengaluru. With over 4,000 residences, it is designed as a complete ecosystem with retail, schools, and healthcare within the campus.',
                'project_type'      => 'Township',
                'status'            => 'Under Construction',
                'address'           => 'Thanisandra Main Road, Hegde Nagar',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'total_units'       => 4000,
                'available_units'   => 1200,
                'price_from'        => 7500000,
                'price_to'          => 22000000,
                'possession_date'   => '2027-03-31',
                'total_towers'      => 24,
                'floors_per_tower'  => 18,
                'rera_id'           => 'PRM/KA/RERA/1251/007',
                'is_featured'       => true,
                'views_count'       => 3100,
                'leads_count'       => 112,
                'metro_distance'    => '2.5 km from Nagawara Metro',
                'connectivity_score'=> 8,
                'nearby_schools'    => 'Canadian International School (1km), Vidyaniketan (2km)',
                'nearby_hospitals'  => 'Columbia Asia (3km), Aster CMI (4km)',
            ]
        );

        $p8 = BuilderProject::firstOrCreate(
            ['builder_id' => $sobha->id, 'title' => 'Sobha Dream Acres'],
            [
                'builder_id'        => $sobha->id,
                'description'       => 'One of Bengaluru\'s most successful projects with 80% sold out. Sobha Dream Acres offers compact luxury 1 & 2 BHK apartments at Panathur Road with excellent connectivity to Whitefield IT corridor.',
                'project_type'      => 'Residential',
                'status'            => 'Ready to Move',
                'address'           => 'Panathur Road, Marathahalli',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'total_units'       => 5947,
                'available_units'   => 250,
                'price_from'        => 4200000,
                'price_to'          => 9800000,
                'possession_date'   => '2024-01-31',
                'total_towers'      => 32,
                'floors_per_tower'  => 15,
                'rera_id'           => 'PRM/KA/RERA/1251/008',
                'is_featured'       => false,
                'views_count'       => 6200,
                'leads_count'       => 220,
                'metro_distance'    => '3 km from Kadubeesanahalli Metro',
                'connectivity_score'=> 9,
                'nearby_schools'    => 'Ryan International (1km), Orchids International (2km)',
                'nearby_hospitals'  => 'Motherhood Hospital (1.5km), Manipal (5km)',
            ]
        );

        // ─────────────────────────────────────────────
        // 5. Godrej Properties
        // ─────────────────────────────────────────────
        $godrej = Builder::firstOrCreate(
            ['email' => 'godrej@demo.com'],
            [
                'name'                    => 'Pirojsha Godrej',
                'company_name'            => 'Godrej Properties',
                'password'                => Hash::make('password'),
                'phone'                   => '9876540005',
                'city'                    => 'Mumbai',
                'cities_operating'        => 'Mumbai, Pune, Bengaluru, Delhi, Hyderabad, Ahmedabad',
                'established_year'        => '1990',
                'rera_registration'       => 'P51900027141',
                'is_verified'             => true,
                'total_delivered_projects'=> 95,
                'rating'                  => 4.3,
                'description'             => 'Godrej Properties brings the Godrej Group philosophy of innovation, sustainability, and excellence to the real estate industry. Each Godrej Properties development combines a 125-year legacy with a commitment to cutting-edge design and technology.',
                'website'                 => 'https://www.godrejproperties.com',
                'status'                  => 'active',
            ]
        );

        $p9 = BuilderProject::firstOrCreate(
            ['builder_id' => $godrej->id, 'title' => 'Godrej Reserve'],
            [
                'builder_id'        => $godrej->id,
                'description'       => 'Godrej Reserve is a nature-inspired luxury township at Devanahalli, North Bengaluru. Set amidst a 4-acre curated forest, it offers 3 & 4 BHK homes with biophilic design principles and sustainable living spaces.',
                'project_type'      => 'Township',
                'status'            => 'Under Construction',
                'address'           => 'Devanahalli, North Bengaluru',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'total_units'       => 1800,
                'available_units'   => 890,
                'price_from'        => 9500000,
                'price_to'          => 24000000,
                'possession_date'   => '2027-09-30',
                'total_towers'      => 12,
                'floors_per_tower'  => 16,
                'rera_id'           => 'PRM/KA/RERA/1251/009',
                'is_featured'       => true,
                'views_count'       => 1900,
                'leads_count'       => 65,
                'metro_distance'    => '5 km from Kempegowda Airport Metro',
                'connectivity_score'=> 7,
                'nearby_schools'    => 'Stonehill International (3km)',
                'nearby_hospitals'  => 'Columbia Asia Hebbal (12km)',
            ]
        );

        $p10 = BuilderProject::firstOrCreate(
            ['builder_id' => $godrej->id, 'title' => 'Godrej Nurture'],
            [
                'builder_id'        => $godrej->id,
                'description'       => 'Godrej Nurture at Electronic City offers thoughtfully designed 2 & 3 BHK apartments with smart home features. Close to major IT parks like Infosys, HCL, and TCS campuses.',
                'project_type'      => 'Residential',
                'status'            => 'Ready to Move',
                'address'           => 'Electronic City Phase 1',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'total_units'       => 642,
                'available_units'   => 88,
                'price_from'        => 5800000,
                'price_to'          => 11500000,
                'possession_date'   => '2023-12-31',
                'total_towers'      => 6,
                'floors_per_tower'  => 20,
                'rera_id'           => 'PRM/KA/RERA/1251/010',
                'is_featured'       => false,
                'views_count'       => 2400,
                'leads_count'       => 88,
                'metro_distance'    => '500m from Electronic City Metro',
                'connectivity_score'=> 9,
                'nearby_schools'    => 'Ebenezer International (1km), Delhi Public School (2km)',
                'nearby_hospitals'  => 'Narayana Health (2km), Sakra World (6km)',
            ]
        );

        // ─────────────────────────────────────────────
        // Attach amenities to all projects
        // ─────────────────────────────────────────────
        $allAmenityIds  = Amenity::pluck('id')->toArray();
        $luxuryAmenities = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Spa & Sauna',
            'Yoga / Meditation', 'Party Hall / Banquet', 'Mini Theatre', 'Rooftop Garden',
            'Jogging Track', 'Children\'s Play Area', '24×7 Security', 'CCTV Surveillance',
            'Video Door Phone', 'Gated Community', 'Power Backup', 'High-Speed Elevators',
            'Covered Parking', 'EV Charging Point', 'Rainwater Harvesting', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $standardAmenities = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Children\'s Play Area',
            'Jogging Track', '24×7 Security', 'CCTV Surveillance', 'Power Backup',
            'High-Speed Elevators', 'Covered Parking', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        foreach ([$p1, $p2, $p3, $p5, $p6, $p7] as $proj) {
            $proj->amenityItems()->sync($luxuryAmenities);
        }
        foreach ([$p4, $p8, $p9, $p10] as $proj) {
            $proj->amenityItems()->sync($standardAmenities);
        }

        // ─────────────────────────────────────────────
        // Add sample units/properties to projects
        // ─────────────────────────────────────────────
        $this->seedUnits($prestige, $p1, [
            ['2 BHK', 'Apartment', 1150, 8500000],
            ['3 BHK', 'Apartment', 1650, 14500000],
            ['3 BHK', 'Apartment', 1850, 16800000],
            ['4 BHK', 'Apartment', 2400, 24000000],
        ]);

        $this->seedUnits($prestige, $p2, [
            ['3 BHK', 'Apartment', 2100, 15000000],
            ['4 BHK', 'Penthouse', 3200, 32000000],
        ]);

        $this->seedUnits($dlf, $p3, [
            ['4 BHK', 'Apartment', 3800, 55000000],
            ['5 BHK', 'Penthouse', 6200, 110000000],
        ]);

        $this->seedUnits($dlf, $p4, [
            ['4 BHK', 'Apartment', 3200, 42000000],
            ['4 BHK', 'Sky Villa', 4500, 62000000],
        ]);

        $this->seedUnits($lodha, $p5, [
            ['5 BHK', 'Penthouse', 8000, 350000000],
        ]);

        $this->seedUnits($lodha, $p6, [
            ['2 BHK', 'Apartment', 900, 38000000],
            ['3 BHK', 'Apartment', 1400, 65000000],
            ['4 BHK', 'Apartment', 2200, 115000000],
        ]);

        $this->seedUnits($sobha, $p7, [
            ['2 BHK', 'Apartment', 1200, 9200000],
            ['3 BHK', 'Apartment', 1600, 13500000],
            ['3 BHK', 'Villa', 2200, 21000000],
        ]);

        $this->seedUnits($sobha, $p8, [
            ['1 BHK', 'Apartment', 580, 4500000],
            ['2 BHK', 'Apartment', 950, 7800000],
        ]);

        $this->seedUnits($godrej, $p9, [
            ['3 BHK', 'Apartment', 1480, 12000000],
            ['4 BHK', 'Apartment', 2100, 22000000],
        ]);

        $this->seedUnits($godrej, $p10, [
            ['2 BHK', 'Apartment', 1050, 6500000],
            ['3 BHK', 'Apartment', 1420, 10800000],
        ]);

        $this->command->info('✅ Builder demo data seeded: 5 builders, 10 projects, multiple units.');
    }

    private function seedUnits($builder, $project, array $units): void
    {
        $statuses = ['Available', 'Available', 'Available', 'Booked'];

        foreach ($units as $i => [$bhk, $type, $area, $price]) {
            $citySlug = strtolower(str_replace(' ', '-', $project->city));
            Property::firstOrCreate(
                [
                    'builder_id'         => $builder->id,
                    'builder_project_id' => $project->id,
                    'title'              => "{$bhk} {$type} – {$project->title}",
                ],
                [
                    'property_dealer_id' => null,
                    'builder_id'         => $builder->id,
                    'builder_project_id' => $project->id,
                    'title'              => "{$bhk} {$type} – {$project->title}",
                    'description'        => "Spacious {$bhk} {$type} in {$project->title}, {$project->city}. {$area} sq.ft super built-up area with premium finishes, modular kitchen, and all amenities.",
                    'property_type'      => $type,
                    'bhk_type'           => $bhk,
                    'option_type'        => 'Sell',
                    'looking_for'        => 'Sell',
                    'status'             => $statuses[$i % count($statuses)],
                    'area'               => $area,
                    'bedrooms'           => (int) $bhk[0],
                    'bathrooms'          => (int) $bhk[0],
                    'balconies'          => 2,
                    'floor_number'       => rand(5, 25),
                    'furnishing_status'  => 'Semi-Furnished',
                    'price'              => $price,
                    'city'               => $project->city,
                    'state'              => $project->state,
                    'address'            => $project->address,
                    'views_count'        => rand(50, 500),
                ]
            );
        }
    }
}
