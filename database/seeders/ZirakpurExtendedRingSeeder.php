<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * ZirakpurExtendedRingSeeder
 *
 * Adds dealers, builders and FOR-SALE properties in the 10–100 km ring
 * around Srishti Avenue, Dhakoli, Zirakpur (30.6400, 76.8190) that are
 * NOT already covered by ZirakpurProximitySeeder / Batch2 / Batch3 or the
 * Tricity*/MohaliKharar* seeders (those already cover Zirakpur, Baltana,
 * Peer Mushalla, VIP Road, Ambala Highway, Panchkula, Mohali, Kharar,
 * Derabassi, Landran, Mullanpur, Gharuan, Kurali, Banur, Rajpura, Patiala,
 * Fatehgarh Sahib, Ropar, Morinda, Chandigarh, Kalka, Pinjore, Solan,
 * Nalagarh, Baddi, Barotiwala, Ambala City).
 *
 * New areas added here (all within 100 km, none seeded elsewhere):
 *   Ring A : Parwanoo, Himachal Pradesh   (~26 km)
 *   Ring B : Khanna, Punjab               (~58 km)
 *   Ring C : Ludhiana, Punjab             (~96 km)
 *
 * Safe to re-run: every insert checks for an existing email/slug first.
 */
class ZirakpurExtendedRingSeeder extends Seeder
{
    const HOME_LAT = 30.6400;
    const HOME_LNG = 76.8190;

    public function run(): void
    {
        $this->command->info('🏠 Seeding extended 10-100km ring (Parwanoo / Khanna / Ludhiana)...');
        $this->seedDealers();
        $this->seedBuilders();
        $this->seedProperties();
        $this->command->info('✅ ZirakpurExtendedRingSeeder complete!');
    }

    // =========================================================================
    // DEALERS (12 records)
    // =========================================================================
    private function seedDealers(): void
    {
        $this->command->info('  → Seeding 12 dealers (Parwanoo / Khanna / Ludhiana)...');

        $dealers = [
            // ── Parwanoo, HP (~26 km) ───────────────────────────────────
            ['first_name'=>'Ramesh','last_name'=>'Thakur','company_name'=>'Thakur Hill Properties Parwanoo','phone'=>'+91 98160 50001','email'=>'ramesh.thakur@thakurhillproperties.com','bio'=>'Thakur Hill Properties is based in Sector 4, Parwanoo, ~26 km from Zirakpur on the Kalka-Shimla highway. Specialising in industrial-belt housing and hill-view apartments.','specializations'=>'Hill View Apartments,Industrial Belt Housing,Plots','operating_cities'=>'Parwanoo,Kalka'],
            ['first_name'=>'Anita','last_name'=>'Chauhan','company_name'=>'Chauhan Realty Parwanoo','phone'=>'+91 98164 50002','email'=>'anita.chauhan@chauhanrealtyparwanoo.com','bio'=>'Chauhan Realty serves Parwanoo\'s industrial and residential sectors. 10 years of experience helping factory-belt employees find affordable housing near HP-Haryana border.','specializations'=>'Affordable Housing,Factory Belt,Rental','operating_cities'=>'Parwanoo'],
            ['first_name'=>'Vikram','last_name'=>'Katoch','company_name'=>'Katoch Estate Sector 4 Parwanoo','phone'=>'+91 98170 50003','email'=>'vikram.katoch@katochestateparwanoo.com','bio'=>'Katoch Estate operates from Parwanoo Sector 4 covering the entire Timber Trail-Solan Road belt. Known for weekend home and retirement plot advisory.','specializations'=>'Weekend Homes,Retirement Plots,Residential','operating_cities'=>'Parwanoo,Solan'],
            ['first_name'=>'Suresh','last_name'=>'Bhandari','company_name'=>'Bhandari Homes Parwanoo','phone'=>'+91 98172 50004','email'=>'suresh.bhandari@bhandarihomesparwanoo.com','bio'=>'Bhandari Homes has been active in Parwanoo since 2009, offering builder floors and independent houses close to the Chandigarh-Shimla highway.','specializations'=>'Builder Floors,Independent Houses,Highway Properties','operating_cities'=>'Parwanoo,Kalka'],

            // ── Khanna, Punjab (~58 km) ──────────────────────────────────
            ['first_name'=>'Amanpreet','last_name'=>'Sidhu','company_name'=>'Sidhu Property Khanna','phone'=>'+91 98551 50005','email'=>'amanpreet.sidhu@sidhupropertykhanna.com','bio'=>'Sidhu Property is Khanna\'s most established agency on GT Road, ~58 km from Zirakpur. Specialising in grain-market adjacent residential and commercial deals.','specializations'=>'GT Road Properties,Commercial,Residential','operating_cities'=>'Khanna,Ludhiana'],
            ['first_name'=>'Rajesh','last_name'=>'Goyal','company_name'=>'Goyal Real Estate Khanna','phone'=>'+91 98554 50006','email'=>'rajesh.goyal@goyalrealestatekhanna.com','bio'=>'Goyal Real Estate covers Khanna town and Model Town Khanna with 14 years of experience in residential plots and builder floors.','specializations'=>'Plots,Builder Floors,Model Town Khanna','operating_cities'=>'Khanna'],
            ['first_name'=>'Baljeet','last_name'=>'Cheema','company_name'=>'Cheema Properties GT Road Khanna','phone'=>'+91 98557 50007','email'=>'baljeet.cheema@cheemapropertieskhanna.com','bio'=>'Cheema Properties operates directly on GT Road Khanna, serving transporters and grain traders looking for commercial shops and warehousing plots.','specializations'=>'Commercial Shops,Warehousing Plots,GT Road','operating_cities'=>'Khanna,Ludhiana'],
            ['first_name'=>'Simran','last_name'=>'Oberoi','company_name'=>'Oberoi Estate Khanna','phone'=>'+91 98559 50008','email'=>'simran.oberoi@oberoiestatekhanna.com','bio'=>'Oberoi Estate is a family-run agency in Khanna focused on residential flats and independent houses for local families and NRI investors.','specializations'=>'Residential Flats,Independent Houses,NRI Services','operating_cities'=>'Khanna'],

            // ── Ludhiana, Punjab (~96 km) ─────────────────────────────────
            ['first_name'=>'Deepak','last_name'=>'Bansal','company_name'=>'Bansal Properties Ludhiana','phone'=>'+91 98140 50009','email'=>'deepak.bansal@bansalpropertiesludhiana.com','bio'=>'Bansal Properties is a leading agency in Ludhiana with 18 years of experience across Ferozepur Road and Pakhowal Road corridors.','specializations'=>'Ferozepur Road,Pakhowal Road,Residential Flats','operating_cities'=>'Ludhiana,Khanna'],
            ['first_name'=>'Harpreet','last_name'=>'Sandhu','company_name'=>'Sandhu Realty Ferozepur Road Ludhiana','phone'=>'+91 98143 50010','email'=>'harpreet.sandhu@sandhurealtyludhiana.com','bio'=>'Sandhu Realty is based on Ferozepur Road, Ludhiana, ~96 km from Zirakpur. Expert in premium apartments and SCO plots for the industrial city\'s growing middle class.','specializations'=>'Premium Apartments,SCO Plots,Ferozepur Road','operating_cities'=>'Ludhiana'],
            ['first_name'=>'Naveen','last_name'=>'Chopra','company_name'=>'Chopra Estate Pakhowal Road Ludhiana','phone'=>'+91 98147 50011','email'=>'naveen.chopra@choprapakhowalroad.com','bio'=>'Chopra Estate covers Pakhowal Road and BRS Nagar in Ludhiana. 12 years of expertise in mid-segment flats and independent floors.','specializations'=>'Mid-Segment Flats,Independent Floors,BRS Nagar','operating_cities'=>'Ludhiana'],
            ['first_name'=>'Kiran','last_name'=>'Malhotra','company_name'=>'Malhotra Homes Ludhiana','phone'=>'+91 98150 50012','email'=>'kiran.malhotra@malhotrahomesludhiana.com','bio'=>'Malhotra Homes is a trusted name in Ludhiana\'s Model Town and Sarabha Nagar belts, offering full-service buying, selling and rental advisory.','specializations'=>'Model Town,Sarabha Nagar,Full Service','operating_cities'=>'Ludhiana'],
        ];

        foreach ($dealers as $d) {
            if (DB::table('property_dealers')->where('email', $d['email'])->exists()) {
                $this->command->line("    [skip dealer] {$d['company_name']} — already exists");
                continue;
            }
            $baseSlug = Str::slug($d['company_name']);
            $slug = $baseSlug;
            $i = 1;
            while (DB::table('property_dealers')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            DB::table('property_dealers')->insert([
                'first_name'       => $d['first_name'],
                'last_name'        => $d['last_name'],
                'company_name'     => $d['company_name'],
                'phone'            => $d['phone'],
                'email'            => $d['email'],
                'password'         => Hash::make('Dealer@2024'),
                'slug'             => $slug,
                'bio'              => $d['bio'],
                'specializations'  => $d['specializations'],
                'operating_cities' => $d['operating_cities'],
                'status'           => 'active',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
        $this->command->info('    ✔ dealers done.');
    }

    // =========================================================================
    // BUILDERS (6 records)
    // =========================================================================
    private function seedBuilders(): void
    {
        $this->command->info('  → Seeding 6 builders (Parwanoo / Khanna / Ludhiana)...');

        $builders = [
            [
                'name'                     => 'Shivalik Hill Developers',
                'company_name'             => 'Shivalik Hill Developers Pvt. Ltd.',
                'email'                    => 'info@shivalikhilldevelopers.com',
                'phone'                    => '+91 98160 60001',
                'website'                  => 'https://www.shivalikhilldevelopers.com',
                'city'                     => 'Parwanoo',
                'established_year'         => '2010',
                'rera_registration'        => 'HPRERA-SOL-PR0100',
                'cities_operating'         => 'Parwanoo,Kalka,Solan',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Shivalik Hill Developers builds hill-view residential floors and plotted colonies in Parwanoo, catering to industrial-belt professionals and weekend-home buyers from Chandigarh.',
            ],
            [
                'name'                     => 'Solan Hills Infra',
                'company_name'             => 'Solan Hills Infra Pvt. Ltd.',
                'email'                    => 'contact@solanhillsinfra.com',
                'phone'                    => '+91 98164 60002',
                'website'                  => 'https://www.solanhillsinfra.com',
                'city'                     => 'Parwanoo',
                'established_year'         => '2013',
                'rera_registration'        => 'HPRERA-SOL-PR0180',
                'cities_operating'         => 'Parwanoo,Solan',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 4,
                'description'              => 'Solan Hills Infra develops gated plotted townships along the Parwanoo-Solan stretch, popular with buyers looking for retirement and second-home plots.',
            ],
            [
                'name'                     => 'GT Road Builders Khanna',
                'company_name'             => 'GT Road Builders & Developers',
                'email'                    => 'info@gtroadbuilderskhanna.com',
                'phone'                    => '+91 98551 60003',
                'website'                  => 'https://www.gtroadbuilderskhanna.com',
                'city'                     => 'Khanna',
                'established_year'         => '2007',
                'rera_registration'        => 'PBRERA-LDH-PR0210',
                'cities_operating'         => 'Khanna,Ludhiana',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'GT Road Builders is Khanna\'s most active developer, with residential colonies and commercial complexes fronting the Grand Trunk Road.',
            ],
            [
                'name'                     => 'Khanna Infra Developers',
                'company_name'             => 'Khanna Infra Developers Pvt. Ltd.',
                'email'                    => 'sales@khannainfradevelopers.com',
                'phone'                    => '+91 98554 60004',
                'website'                  => 'https://www.khannainfradevelopers.com',
                'city'                     => 'Khanna',
                'established_year'         => '2011',
                'rera_registration'        => 'PBRERA-LDH-PR0245',
                'cities_operating'         => 'Khanna',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Khanna Infra Developers focuses on affordable builder floors and plotted colonies for Khanna\'s grain-market and transport-business families.',
            ],
            [
                'name'                     => 'Ferozepur Road Developers',
                'company_name'             => 'Ferozepur Road Developers Ludhiana',
                'email'                    => 'info@ferozepurroaddevelopers.com',
                'phone'                    => '+91 98140 60005',
                'website'                  => 'https://www.ferozepurroaddevelopers.com',
                'city'                     => 'Ludhiana',
                'established_year'         => '2005',
                'rera_registration'        => 'PBRERA-LDH-PR0310',
                'cities_operating'         => 'Ludhiana,Khanna',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'Ferozepur Road Developers is a well-known Ludhiana builder with high-rise apartments and SCO complexes along Ferozepur Road and Pakhowal Road.',
            ],
            [
                'name'                     => 'Pakhowal Greens',
                'company_name'             => 'Pakhowal Greens Pvt. Ltd.',
                'email'                    => 'contact@pakhowalgreens.com',
                'phone'                    => '+91 98143 60006',
                'website'                  => 'https://www.pakhowalgreens.com',
                'city'                     => 'Ludhiana',
                'established_year'         => '2016',
                'rera_registration'        => 'PBRERA-LDH-PR0355',
                'cities_operating'         => 'Ludhiana',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 3,
                'description'              => 'Pakhowal Greens is a newer Ludhiana developer building mid-rise apartment communities on Pakhowal Road with modern amenities.',
            ],
        ];

        foreach ($builders as $b) {
            if (DB::table('builders')->where('email', $b['email'])->exists()) {
                $this->command->line("    [skip builder] {$b['company_name']} — already exists");
                continue;
            }
            $baseSlug = Str::slug($b['name']);
            $slug = $baseSlug;
            $i = 1;
            while (DB::table('builders')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            DB::table('builders')->insert([
                'name'                     => $b['name'],
                'company_name'             => $b['company_name'],
                'email'                    => $b['email'],
                'password'                 => Hash::make('Builder@2024'),
                'phone'                    => $b['phone'],
                'website'                  => $b['website'],
                'city'                     => $b['city'],
                'established_year'         => $b['established_year'],
                'rera_registration'        => $b['rera_registration'],
                'cities_operating'         => $b['cities_operating'],
                'rating'                   => $b['rating'],
                'is_verified'              => $b['is_verified'],
                'total_delivered_projects' => $b['total_delivered_projects'],
                'description'              => $b['description'],
                'slug'                     => $slug,
                'status'                   => 'active',
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);
        }
        $this->command->info('    ✔ builders done.');
    }

    // =========================================================================
    // PROPERTIES — FOR SALE ONLY (20 records across 3 new zones)
    // =========================================================================
    private function seedProperties(): void
    {
        $dealerEmails = [
            'ramesh.thakur@thakurhillproperties.com','anita.chauhan@chauhanrealtyparwanoo.com',
            'vikram.katoch@katochestateparwanoo.com','suresh.bhandari@bhandarihomesparwanoo.com',
            'amanpreet.sidhu@sidhupropertykhanna.com','rajesh.goyal@goyalrealestatekhanna.com',
            'baljeet.cheema@cheemapropertieskhanna.com','simran.oberoi@oberoiestatekhanna.com',
            'deepak.bansal@bansalpropertiesludhiana.com','harpreet.sandhu@sandhurealtyludhiana.com',
            'naveen.chopra@choprapakhowalroad.com','kiran.malhotra@malhotrahomesludhiana.com',
        ];
        $dealerIds = DB::table('property_dealers')->whereIn('email', $dealerEmails)->pluck('id')->toArray();
        if (empty($dealerIds)) {
            // fallback so seeder still works if run standalone against an existing DB
            $dealerIds = DB::table('property_dealers')->pluck('id')->toArray();
        }

        $amenityPool = [
            'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track',
            'Park,Kids Play Area,Security,Power Backup,Car Parking,CCTV',
            'Gymnasium,24x7 Security,Power Backup,Lift,Intercom,CCTV',
            'Clubhouse,Kids Play Area,Power Backup,Security,Water Supply,Lift',
            'Car Parking,CCTV,Security,Power Backup,Intercom,Visitor Parking',
        ];
        $furnishings = ['Furnished','Semi-Furnished','Unfurnished'];
        $facings     = ['North','South','East','West','North-East','North-West'];
        $propAges    = ['Under Construction','0-1 Year','1-3 Years','3-5 Years'];
        $pick = fn($arr) => $arr[array_rand($arr)];

        $zones = [
            [
                'label'     => 'Parwanoo, Himachal Pradesh (~26 km)',
                'lat'       => 30.8383, 'lng' => 76.9608,
                'jitter'    => 0.0060,
                'state'     => 'Himachal Pradesh',
                'city'      => 'Parwanoo',
                'pincode'   => '173220',
                'locality'  => 'Sector 4',
                'societies' => ['Shivalik Hill View Floors','Timber Trail Residency','Parwanoo Green Enclave','Solan Road Homes'],
                'landmark'  => 'Near Timber Trail Ropeway',
                'count'     => 7,
                'price_mult'=> 0.85,
            ],
            [
                'label'     => 'Khanna, Punjab (~58 km)',
                'lat'       => 30.7046, 'lng' => 76.2223,
                'jitter'    => 0.0070,
                'state'     => 'Punjab',
                'city'      => 'Khanna',
                'pincode'   => '141401',
                'locality'  => 'GT Road',
                'societies' => ['GT Road Enclave','Model Town Khanna Homes','Khanna Grain Market Residency','New Khanna Township'],
                'landmark'  => 'Near Khanna Grain Market',
                'count'     => 7,
                'price_mult'=> 0.9,
            ],
            [
                'label'     => 'Ludhiana, Punjab (~96 km)',
                'lat'       => 30.9010, 'lng' => 75.8573,
                'jitter'    => 0.0090,
                'state'     => 'Punjab',
                'city'      => 'Ludhiana',
                'pincode'   => '141001',
                'locality'  => 'Ferozepur Road',
                'societies' => ['Ferozepur Road Heights','Pakhowal Greens Residency','Sarabha Nagar Homes','BRS Nagar Enclave'],
                'landmark'  => 'Near Ferozepur Road-Pakhowal Road Junction',
                'count'     => 6,
                'price_mult'=> 1.1,
            ],
        ];

        $propTypes = ['Apartment','Builder Floor','Independent Floor','Villa','Plot'];
        $totalInserted = 0;

        foreach ($zones as $zone) {
            $this->command->info("    Zone: {$zone['label']} — {$zone['count']} properties (for sale)");

            for ($i = 0; $i < $zone['count']; $i++) {
                $dealerId  = $pick($dealerIds);
                $ptype     = $pick($propTypes);
                $society   = $pick($zone['societies']);
                $amenities = $pick($amenityPool);
                $furnish   = $pick($furnishings);
                $facing    = $pick($facings);
                $propAge   = $pick($propAges);

                [$bedrooms, $bathrooms, $balconies, $area, $price, $bhkType] = $this->getConfig($ptype, $zone['price_mult']);

                $prefix = $pick(['Spacious','Modern','Prime','Elegant','Bright','Premium','Ready-to-Move']);
                if ($ptype === 'Plot') {
                    $title = "{$prefix} Plot in {$society}, {$zone['locality']}, {$zone['city']}";
                } else {
                    $title = "{$prefix} {$bhkType} {$ptype} in {$society}, {$zone['locality']}, {$zone['city']}";
                }

                $baseSlug = Str::slug($title . '-' . $zone['city'] . '-' . ($totalInserted + $i + 1));
                $slug = $baseSlug;
                $sc = 1;
                while (DB::table('properties')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $sc++;
                }

                $lat = round($zone['lat'] + (rand(-100, 100) / 100000 * ($zone['jitter'] / 0.001)), 6);
                $lng = round($zone['lng'] + (rand(-100, 100) / 100000 * ($zone['jitter'] / 0.001)), 6);

                $totalFloors = in_array($ptype, ['Villa','Plot']) ? null : rand(3, 12);
                $floorNumber = $totalFloors ? rand(1, $totalFloors) : null;
                $ppsqft      = $area > 0 ? round($price / $area, 2) : null;
                $possession  = ($propAge === 'Under Construction') ? 'Under Construction' : 'Ready to Move';

                $bhkStr = $bedrooms ? "{$bedrooms} BHK " : '';
                $description = "A well-maintained {$bhkStr}{$ptype} located in {$society}, {$zone['locality']}, {$zone['city']}. "
                    . "Possession status: {$possession}. Key amenities include: {$amenities}. "
                    . "Excellent connectivity from Zirakpur/Chandigarh via NH and good local infrastructure. "
                    . "Ideal for families, professionals and investors.";

                DB::table('properties')->insert([
                    'property_dealer_id'  => $dealerId,
                    'title'               => $title,
                    'slug'                => $slug,
                    'description'         => $description,
                    'property_type'       => $ptype,
                    'bhk_type'            => $bhkType,
                    'looking_for'         => 'Sale',
                    'option_type'         => 'Sell',
                    'listing_type'        => $pick(['Owner','Broker','Broker','Builder']),
                    'address'             => $society . ', ' . $zone['locality'] . ', ' . $zone['city'],
                    'city'                => $zone['city'],
                    'state'               => $zone['state'],
                    'country'             => 'India',
                    'pincode'             => $zone['pincode'],
                    'locality'            => $zone['locality'],
                    'society_name'        => $society,
                    'landmark'            => $zone['landmark'],
                    'price'               => $price,
                    'expected_price'      => $price,
                    'price_per_sqft'      => $ppsqft,
                    'monthly_rent'        => null,
                    'negotiable'          => rand(0, 1),
                    'maintenance_charges' => ($ptype !== 'Plot') ? rand(500, 4000) : null,
                    'bedrooms'            => $bedrooms,
                    'bathrooms'           => $bathrooms,
                    'balconies'           => $balconies,
                    'area'                => $area,
                    'furnishing'          => $furnish,
                    'furnishing_status'   => $furnish,
                    'facing'              => $facing,
                    'floor'               => $floorNumber,
                    'floor_number'        => $floorNumber,
                    'total_floors'        => $totalFloors,
                    'property_age'        => $propAge,
                    'possession_status'   => $possession,
                    'amenities'           => ($ptype !== 'Plot') ? $amenities : null,
                    'status'              => 'Available',
                    'parking'             => rand(0, 2),
                    'is_featured'         => rand(0, 10) > 8 ? 1 : 0,
                    'is_premium'          => rand(0, 10) > 9 ? 1 : 0,
                    'views_count'         => rand(10, 1500),
                    'isreal'              => 1,
                    'latitude'            => $lat,
                    'longitude'           => $lng,
                    'created_at'          => now()->subDays(rand(0, 180)),
                    'updated_at'          => now(),
                ]);
            }
            $totalInserted += $zone['count'];
        }

        $this->command->info("    ✔ {$totalInserted} for-sale properties seeded.");
    }

    private function getConfig(string $ptype, float $mult): array
    {
        return match ($ptype) {
            'Apartment' => (function () use ($mult) {
                $beds = rand(2, 4);
                $area = match ($beds) { 2 => rand(900, 1300), 3 => rand(1350, 1800), default => rand(2000, 3000) };
                $price = (int) round($area * rand(2500, 4500) * $mult / 10000) * 10000;
                return [$beds, max(1, $beds - 1), max(1, $beds - 1), $area, $price, $beds . ' BHK'];
            })(),
            'Builder Floor', 'Independent Floor' => (function () use ($mult) {
                $beds = rand(2, 4);
                $area = rand(900, 2000);
                $price = (int) round($area * rand(2200, 4000) * $mult / 10000) * 10000;
                return [$beds, $beds - 1, 1, $area, $price, $beds . ' BHK'];
            })(),
            'Villa' => (function () use ($mult) {
                $beds = rand(3, 5);
                $area = rand(2200, 4500);
                $price = (int) round($area * rand(3500, 6000) * $mult / 10000) * 10000;
                return [$beds, $beds, 2, $area, $price, $beds . ' BHK'];
            })(),
            'Plot' => (function () use ($mult) {
                $area = rand(100, 400) * 9;
                $price = (int) round($area * rand(1500, 3500) * $mult / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            default => [2, 2, 1, 1000, 3500000, '2 BHK'],
        };
    }
}
