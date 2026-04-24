<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TricityPropertiesSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding 1000 real Tricity properties...');

        // Get real dealer IDs from DB
        $dealerIds = DB::table('property_dealers')->pluck('id')->toArray();
        if (empty($dealerIds)) {
            $this->command->error('No dealers found. Please run TricityRealDataSeeder and TricityDealersSeeder first.');
            return;
        }

        $count = 0;

        // ──────────────────────────────────────────────────────────────
        // DATA POOLS
        // ──────────────────────────────────────────────────────────────

        $cities = [
            'Zirakpur' => [
                'state'     => 'Punjab',
                'pincodes'  => ['140603', '140604'],
                'localities' => [
                    ['name'=>'VIP Road','lat'=>30.6455,'lng'=>76.8185,'societies'=>['SBP City of Dreams','Maya Garden City','NK Savitry Greens 2','Mona Greens','Sarvottam Homes','Mayfair Royal Apartments','Sushma Crescent']],
                    ['name'=>'Airport Road','lat'=>30.6570,'lng'=>76.8230,'societies'=>['Sushma Grande NXT','Green Lotus Saksham','Altus Space Towers','Tricity Heights','GBP Athens','Sarvottam Heights']],
                    ['name'=>'Ambala Highway','lat'=>30.6618,'lng'=>76.8438,'societies'=>['Motia Royal Citi','GBP Athens','Savitry Heights','Jai Durga Apartments']],
                    ['name'=>'Patiala Highway','lat'=>30.6410,'lng'=>76.8130,'societies'=>['Green Valley Township','Krishna Greens','GBP Camellia','Air Force Housing']],
                    ['name'=>'Peer Mushalla','lat'=>30.6530,'lng'=>76.8290,'societies'=>['Savitry Green Avenue','RM Royale Residency','Manglam Heights']],
                    ['name'=>'Dhakoli','lat'=>30.6340,'lng'=>76.8120,'societies'=>['Sushma Crescent','Surya Heights','Rameshwar Heights']],
                    ['name'=>'Gazipur Road','lat'=>30.6500,'lng'=>76.8235,'societies'=>['NK Savitry Greens 2','Mona Greens 2','Krishna Apartments','Jai Durga Green Homes']],
                    ['name'=>'Lohgarh Road','lat'=>30.6488,'lng'=>76.8228,'societies'=>['Maya Garden City','Mayfair Greens','Green Fields']],
                    ['name'=>'Baltana','lat'=>30.6355,'lng'=>76.8055,'societies'=>['Sarvottam Garden','Tricity Green Enclave','PD Residency']],
                    ['name'=>'Derabassi','lat'=>30.5660,'lng'=>76.8130,'societies'=>['SBP Housing Park','RM Green Villas','New Chandigarh Colony']],
                ],
            ],
            'Mohali'   => [
                'state'     => 'Punjab',
                'pincodes'  => ['160055','160059','160062','160071','160104'],
                'localities' => [
                    ['name'=>'Sector 66','lat'=>30.6994,'lng'=>76.6918,'societies'=>['Bestech Park View','JMD Megapolis','Landmark Cyber Park']],
                    ['name'=>'Sector 67','lat'=>30.7030,'lng'=>76.6930,'societies'=>['Mahindra Windchimes','Kensville Residences','Wave Boulevard Floors']],
                    ['name'=>'Sector 70','lat'=>30.7040,'lng'=>76.7000,'societies'=>['Pioneer Presidia','Conscient Habitat','SBP Homes']],
                    ['name'=>'Sector 71','lat'=>30.7045,'lng'=>76.7010,'societies'=>['Tata Primanti','JLPL Falcon View','Bestech Business Tower']],
                    ['name'=>'Sector 74','lat'=>30.7060,'lng'=>76.7040,'societies'=>['JMD Regent Square','Conscient Business Tower','Pioneer Araya']],
                    ['name'=>'Sector 76','lat'=>30.7070,'lng'=>76.7260,'societies'=>['Whiteland The Aspen','Gillco Parkhills','Paras Panorama']],
                    ['name'=>'Sector 79','lat'=>30.7115,'lng'=>76.7245,'societies'=>['Capital Greens Phase 1','Orris Aster Court','Wave Estate']],
                    ['name'=>'Sector 82','lat'=>30.7162,'lng'=>76.7060,'societies'=>['Conscient Habitat','Pacific Hills','Wave Estate Sectors']],
                    ['name'=>'Sector 85','lat'=>30.7180,'lng'=>76.7090,'societies'=>['Wave Estate','Godrej Woods','Emaar The Views']],
                    ['name'=>'Sector 91','lat'=>30.7200,'lng'=>76.7100,'societies'=>['JLPL Township','Godrej Evoq','Orris Carnation']],
                    ['name'=>'Sector 98-99','lat'=>30.7250,'lng'=>76.7120,'societies'=>['IREO Hamlet','IREO Waterfront','IREO City Villas']],
                    ['name'=>'Sector 105','lat'=>30.7820,'lng'=>76.6980,'societies'=>['Emaar The Views','Emaar Mohali Hills']],
                    ['name'=>'Sector 115','lat'=>30.7530,'lng'=>76.7110,'societies'=>['Ansal Orchard County','Ansal Palm Grove','Paras The Manor']],
                    ['name'=>'Sector 127','lat'=>30.7553,'lng'=>76.7340,'societies'=>['Gillco Valley','Pearl City Township']],
                    ['name'=>'Kharar','lat'=>30.7460,'lng'=>76.7210,'societies'=>['Paras Panorama','OSB Golf Heights','Pacific Blue Sapphire','Imperia Esfera']],
                    ['name'=>'Phase 1','lat'=>30.7000,'lng'=>76.7200,'societies'=>['Phase 1 SCO','Phase 1 Residential']],
                    ['name'=>'Phase 7','lat'=>30.7100,'lng'=>76.7150,'societies'=>['Phase 7 Industrial','Phase 7 SCO']],
                    ['name'=>'Aerocity','lat'=>30.6780,'lng'=>76.7350,'societies'=>['GMADA Aerocity','IT City Mohali']],
                ],
            ],
            'Chandigarh' => [
                'state'     => 'Chandigarh',
                'pincodes'  => ['160001','160009','160011','160014','160017','160019','160022'],
                'localities' => [
                    ['name'=>'Sector 8','lat'=>30.7470,'lng'=>76.7750,'societies'=>['Sector 8 Houses','Type 4 Quarters']],
                    ['name'=>'Sector 9','lat'=>30.7460,'lng'=>76.7730,'societies'=>['Sector 9 Residential','CHB Flats']],
                    ['name'=>'Sector 15','lat'=>30.7330,'lng'=>76.7650,'societies'=>['Sector 15 Houses','Sector 15 Apartments']],
                    ['name'=>'Sector 17','lat'=>30.7400,'lng'=>76.7750,'societies'=>['Sector 17 Commercial','Market Complex']],
                    ['name'=>'Sector 19','lat'=>30.7370,'lng'=>76.7700,'societies'=>['Sector 19 Houses','CHB Colony']],
                    ['name'=>'Sector 20','lat'=>30.7300,'lng'=>76.7680,'societies'=>['Sector 20 Residential','Government Colony']],
                    ['name'=>'Sector 21','lat'=>30.7320,'lng'=>76.7720,'societies'=>['Sector 21 Houses','Type 3 Quarters']],
                    ['name'=>'Sector 22','lat'=>30.7350,'lng'=>76.7800,'societies'=>['Sector 22 Commercial','Bank Complex']],
                    ['name'=>'Sector 34','lat'=>30.7280,'lng'=>76.7850,'societies'=>['Sector 34 Apartments','CHB Flats Phase 2']],
                    ['name'=>'Sector 35','lat'=>30.7270,'lng'=>76.7830,'societies'=>['Sector 35 Houses','Residential Colony']],
                    ['name'=>'Sector 38','lat'=>30.7190,'lng'=>76.7780,'societies'=>['Sector 38 West','CHB EWS Flats']],
                    ['name'=>'Sector 40','lat'=>30.7150,'lng'=>76.7720,'societies'=>['Sector 40 Residential','Type 2 Flats']],
                    ['name'=>'Sector 44','lat'=>30.7090,'lng'=>76.7700,'societies'=>['Sector 44 Houses','Sector 44 C']],
                    ['name'=>'Sector 49','lat'=>30.6700,'lng'=>76.7450,'societies'=>['Vatika City','Vatika Premium Floors','Chandigarh Heights']],
                    ['name'=>'Mani Majra','lat'=>30.7220,'lng'=>76.8220,'societies'=>['Mani Majra Flats','PUDA Colony','Sector 7 Mani Majra']],
                    ['name'=>'Mullanpur','lat'=>30.8250,'lng'=>76.7690,'societies'=>['DLF Garden City','Omaxe New Chandigarh','Tata Myst']],
                ],
            ],
            'Panchkula' => [
                'state'     => 'Haryana',
                'pincodes'  => ['134109','134112','134113','134114','134116'],
                'localities' => [
                    ['name'=>'Sector 5','lat'=>30.7010,'lng'=>76.8490,'societies'=>['Sector 5 Houses','Type 3 Quarters']],
                    ['name'=>'Sector 6','lat'=>30.7020,'lng'=>76.8510,'societies'=>['Sector 6 Residential','Government Colony']],
                    ['name'=>'Sector 7','lat'=>30.7030,'lng'=>76.8530,'societies'=>['Sector 7 Houses','CHB Flats']],
                    ['name'=>'Sector 8','lat'=>30.7035,'lng'=>76.8540,'societies'=>['Sector 8 Apartments','Residential Area']],
                    ['name'=>'Sector 10','lat'=>30.7040,'lng'=>76.8550,'societies'=>['Sector 10 Houses','Type 4 Quarters']],
                    ['name'=>'Sector 11','lat'=>30.7046,'lng'=>76.8560,'societies'=>['Sector 11 Residential','HUDA Colony']],
                    ['name'=>'Sector 12','lat'=>30.7040,'lng'=>76.8560,'societies'=>['Himalaya Builder Floors','Omkar Builder Floors','HUDA Flats']],
                    ['name'=>'Sector 14','lat'=>30.7046,'lng'=>76.8636,'societies'=>['TDI Rosewood City','Rashi Sapphire']],
                    ['name'=>'Sector 15','lat'=>30.7060,'lng'=>76.8580,'societies'=>['Himalaya Builder Floors','Sector 15 Houses']],
                    ['name'=>'Sector 17','lat'=>30.7080,'lng'=>76.8590,'societies'=>['Rashi Sapphire','Sector 17 Residential']],
                    ['name'=>'Sector 18','lat'=>30.7085,'lng'=>76.8595,'societies'=>['Rashi Pearl Residency','HUDA Homes']],
                    ['name'=>'Sector 19','lat'=>30.7095,'lng'=>76.8605,'societies'=>['Omkar Residency','Sector 19 Houses']],
                    ['name'=>'Sector 20','lat'=>30.7100,'lng'=>76.8610,'societies'=>['Navraj The Antalyas','Surya Residency','Himalaya Homes']],
                    ['name'=>'Sector 21','lat'=>30.7110,'lng'=>76.8615,'societies'=>['Himalaya Homes','Sector 21 Colony']],
                    ['name'=>'Sector 25','lat'=>30.7160,'lng'=>76.8660,'societies'=>['Navraj Plots','Sector 25 Residential']],
                    ['name'=>'MDCR','lat'=>30.7200,'lng'=>76.8700,'societies'=>['MDCR Residency','Kalka Highway Apartments']],
                ],
            ],
        ];

        $propertyTypes = ['Apartment','Independent Floor','Builder Floor','Villa','Plot','Penthouse','Studio Apartment','Shop','Office Space'];
        $lookingFor    = ['Sale','Rent','Sale','Sale','Rent','Sale','Sale','Sale','Rent']; // weighted towards sale
        $furnishings   = ['Furnished','Semi-Furnished','Unfurnished','Semi-Furnished','Unfurnished','Furnished'];
        $facings       = ['North','South','East','West','North-East','North-West','South-East','South-West'];
        $amenitiesList = [
            'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Clubhouse',
            'Gymnasium,Power Backup,24x7 Security,Car Parking,CCTV,Landscaped Garden',
            'Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Clubhouse,Indoor Games',
            'Clubhouse,Power Backup,24x7 Security,Intercom,Car Parking,Visitor Parking',
            'Swimming Pool,Gymnasium,Tennis Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Landscaped Gardens',
            '24x7 Security,Power Backup,Car Parking,CCTV,Lift,Intercom',
            'Gymnasium,Swimming Pool,Squash Court,Kids Play Area,Jogging Track,Party Lawn,24x7 Security,Power Backup',
            'Power Backup,Security,Parking,Community Hall,Landscaped Garden',
            'High-Speed Elevators,Power Backup,24x7 Security,CCTV,Ample Parking,Conference Rooms',
            'Solar Energy,Rainwater Harvesting,Green Landscaping,Swimming Pool,Gymnasium,24x7 Security',
            'Swimming Pool,Gymnasium,Yoga Deck,Kids Pool,Spa,Concierge,24x7 Security,EV Charging',
            'Park,Kids Play Area,Security,Power Backup,Car Parking,CCTV',
        ];
        $propertyAges = ['Under Construction','0-1 Year','1-3 Years','3-5 Years','5-10 Years','10+ Years'];

        // ──────────────────────────────────────────────────────────────
        // Helper function to pick random element
        // ──────────────────────────────────────────────────────────────
        $pick = fn($arr) => $arr[array_rand($arr)];

        // ──────────────────────────────────────────────────────────────
        // Generate 1000 properties
        // ──────────────────────────────────────────────────────────────

        $cityKeys   = array_keys($cities);
        $cityWeights = ['Zirakpur'=>30,'Mohali'=>35,'Chandigarh'=>20,'Panchkula'=>15]; // percentage distribution

        $targetPerCity = [
            'Zirakpur'   => 300,
            'Mohali'     => 350,
            'Chandigarh' => 200,
            'Panchkula'  => 150,
        ];

        foreach ($targetPerCity as $cityName => $targetCount) {
            $cityData = $cities[$cityName];
            $this->command->info("  → Generating {$targetCount} properties for {$cityName}...");

            for ($i = 0; $i < $targetCount; $i++) {
                $locality   = $pick($cityData['localities']);
                $society    = $pick($locality['societies']);
                $ptype      = $pick($propertyTypes);
                $lfor       = $pick($lookingFor);
                $dealerId   = $pick($dealerIds);
                $pincode    = $pick($cityData['pincodes']);
                $amenities  = $pick($amenitiesList);
                $facing     = $pick($facings);
                $furnish    = $pick($furnishings);
                $propAge    = $pick($propertyAges);

                // Determine BHK & config based on type
                [$bedrooms, $bathrooms, $balconies, $area, $price, $bhkType] = $this->getPropertyConfig($ptype, $cityName, $lfor);

                // Generate title
                $title = $this->generateTitle($ptype, $bedrooms, $bhkType, $society, $locality['name'], $cityName, $i);

                // Slug
                $baseSlug = Str::slug($title . '-' . $cityName . '-' . ($count + 1));
                $slug = $baseSlug;
                $sc = 1;
                while (DB::table('properties')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $sc++;
                }

                // Price per sqft
                $ppsqft = $area > 0 ? round($price / $area, 2) : null;

                // Floor info
                $totalFloors  = ($ptype === 'Villa' || $ptype === 'Plot') ? null : rand(5, 20);
                $floorNumber  = $totalFloors ? rand(1, $totalFloors) : null;

                // Lat/lng with slight variation
                $lat = $locality['lat'] + (rand(-50, 50) / 10000);
                $lng = $locality['lng'] + (rand(-50, 50) / 10000);

                // Possession status
                $possessionStatus = in_array($propAge, ['Under Construction']) ? 'Under Construction' : 'Ready to Move';

                // Monthly rent if for rent
                $monthlyRent = ($lfor === 'Rent') ? $this->getMonthlyRent($ptype, $bedrooms, $cityName) : null;

                // Status
                $status = ($lfor === 'Rent') ? 'Available' : $pick(['Available','Available','Available','Sold']);

                DB::table('properties')->insert([
                    'property_dealer_id' => $dealerId,
                    'title'              => $title,
                    'slug'               => $slug,
                    'description'        => $this->generateDescription($ptype, $bedrooms, $society, $locality['name'], $cityName, $amenities, $possessionStatus),
                    'property_type'      => $ptype,
                    'bhk_type'           => $bhkType,
                    'looking_for'        => $lfor,
                    'option_type'        => $lfor === 'Rent' ? 'Rent' : 'Sell',
                    'listing_type'       => $pick(['Owner','Broker','Broker','Builder']),
                    'address'            => $society . ', ' . $locality['name'] . ', ' . $cityName,
                    'city'               => $cityName,
                    'state'              => $cityData['state'],
                    'country'            => 'India',
                    'pincode'            => $pincode,
                    'locality'           => $locality['name'],
                    'society_name'       => $society,
                    'landmark'           => 'Near ' . $this->getLandmark($cityName, $locality['name']),
                    'price'              => $price,
                    'expected_price'     => $price,
                    'price_per_sqft'     => $ppsqft,
                    'monthly_rent'       => $monthlyRent,
                    'negotiable'         => rand(0, 1),
                    'maintenance_charges'=> ($ptype !== 'Plot') ? rand(500, 5000) : null,
                    'bedrooms'           => $bedrooms,
                    'bathrooms'          => $bathrooms,
                    'balconies'          => $balconies,
                    'area'               => $area,
                    'furnishing'         => $furnish,
                    'furnishing_status'  => $furnish,
                    'facing'             => $facing,
                    'floor'              => $floorNumber,
                    'floor_number'       => $floorNumber,
                    'total_floors'       => $totalFloors,
                    'property_age'       => $propAge,
                    'possession_status'  => $possessionStatus,
                    'amenities'          => ($ptype !== 'Plot' && $ptype !== 'Shop' && $ptype !== 'Office Space') ? $amenities : null,
                    'status'             => $status,
                    'parking'            => rand(0, 2),
                    'is_featured'        => rand(0, 10) > 8 ? 1 : 0,
                    'is_premium'         => rand(0, 10) > 9 ? 1 : 0,
                    'views_count'        => rand(10, 2000),
                    'isreal'             => 1,
                    'latitude'           => $lat,
                    'longitude'          => $lng,
                    'created_at'         => now()->subDays(rand(0, 365)),
                    'updated_at'         => now(),
                ]);

                $count++;
            }
        }

        $this->command->info("✅ Seeded {$count} properties successfully!");
    }

    private function getPropertyConfig(string $ptype, string $city, string $lfor): array
    {
        $premium = in_array($city, ['Chandigarh','Mohali']);

        switch ($ptype) {
            case 'Studio Apartment':
                $area  = rand(300, 550);
                $price = $lfor === 'Rent' ? rand(8000, 18000) : rand(1500000, 3500000);
                return [1, 1, 1, $area, $price, 'Studio'];

            case 'Apartment':
                $bhkOptions = [
                    ['bed'=>1,'bath'=>1,'bal'=>1,'areaMin'=>450,'areaMax'=>700,'bhk'=>'1 BHK'],
                    ['bed'=>2,'bath'=>2,'bal'=>2,'areaMin'=>850,'areaMax'=>1300,'bhk'=>'2 BHK'],
                    ['bed'=>3,'bath'=>3,'bal'=>3,'areaMin'=>1200,'areaMax'=>2000,'bhk'=>'3 BHK'],
                    ['bed'=>4,'bath'=>4,'bal'=>4,'areaMin'=>1800,'areaMax'=>3000,'bhk'=>'4 BHK'],
                ];
                // Weight towards 2 & 3 BHK
                $weights = [5, 40, 40, 15];
                $bhk = $this->weightedRandom($bhkOptions, $weights);
                $area  = rand($bhk['areaMin'], $bhk['areaMax']);
                $basePricePerSqft = $premium ? rand(4500, 9000) : rand(3000, 6500);
                $price = $lfor === 'Rent'
                    ? ($bhk['bed'] * rand(5000, 15000))
                    : ($area * $basePricePerSqft);
                return [$bhk['bed'], $bhk['bath'], $bhk['bal'], $area, $price, $bhk['bhk']];

            case 'Independent Floor':
            case 'Builder Floor':
                $bhkOptions = [
                    ['bed'=>2,'bath'=>2,'bal'=>1,'areaMin'=>1000,'areaMax'=>1600,'bhk'=>'2 BHK'],
                    ['bed'=>3,'bath'=>3,'bal'=>2,'areaMin'=>1500,'areaMax'=>2200,'bhk'=>'3 BHK'],
                    ['bed'=>4,'bath'=>4,'bal'=>3,'areaMin'=>2000,'areaMax'=>3000,'bhk'=>'4 BHK'],
                ];
                $bhk = $this->weightedRandom($bhkOptions, [20, 55, 25]);
                $area  = rand($bhk['areaMin'], $bhk['areaMax']);
                $basePricePerSqft = $premium ? rand(4000, 8000) : rand(2800, 5500);
                $price = $lfor === 'Rent'
                    ? ($bhk['bed'] * rand(8000, 20000))
                    : ($area * $basePricePerSqft);
                return [$bhk['bed'], $bhk['bath'], $bhk['bal'], $area, $price, $bhk['bhk']];

            case 'Villa':
                $bhkOptions = [
                    ['bed'=>3,'bath'=>3,'bal'=>2,'areaMin'=>2000,'areaMax'=>3000,'bhk'=>'3 BHK'],
                    ['bed'=>4,'bath'=>4,'bal'=>3,'areaMin'=>3000,'areaMax'=>4500,'bhk'=>'4 BHK'],
                    ['bed'=>5,'bath'=>5,'bal'=>4,'areaMin'=>4000,'areaMax'=>7000,'bhk'=>'5 BHK'],
                ];
                $bhk = $this->weightedRandom($bhkOptions, [30, 45, 25]);
                $area  = rand($bhk['areaMin'], $bhk['areaMax']);
                $price = $lfor === 'Rent'
                    ? rand(30000, 150000)
                    : ($area * rand(5000, 12000));
                return [$bhk['bed'], $bhk['bath'], $bhk['bal'], $area, $price, $bhk['bhk']];

            case 'Plot':
                $plotSizes = [100, 125, 150, 200, 250, 300, 400, 500, 750, 1000]; // sq yards
                $sqyards   = $plotSizes[array_rand($plotSizes)];
                $area      = $sqyards * 9; // convert to sqft
                $pricePerSqYard = $premium ? rand(25000, 80000) : rand(12000, 40000);
                $price = $sqyards * $pricePerSqYard;
                return [null, null, null, $area, $price, 'Plot'];

            case 'Penthouse':
                $area  = rand(3000, 6000);
                $price = $lfor === 'Rent' ? rand(80000, 300000) : ($area * rand(8000, 18000));
                return [4, 5, 4, $area, $price, '4 BHK'];

            case 'Shop':
                $area  = rand(100, 800);
                $price = $lfor === 'Rent' ? rand(10000, 100000) : ($area * rand(15000, 50000));
                return [null, null, null, $area, $price, null];

            case 'Office Space':
                $area  = rand(200, 3000);
                $price = $lfor === 'Rent' ? ($area * rand(40, 120)) : ($area * rand(8000, 25000));
                return [null, null, null, $area, $price, null];

            default:
                return [2, 2, 1, 900, 4500000, '2 BHK'];
        }
    }

    private function weightedRandom(array $arr, array $weights): array
    {
        $total = array_sum($weights);
        $rand  = rand(1, $total);
        $cumulative = 0;
        foreach ($arr as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) return $item;
        }
        return $arr[0];
    }

    private function generateTitle(string $ptype, $bedrooms, $bhkType, string $society, string $locality, string $city, int $idx): string
    {
        if ($ptype === 'Plot') {
            return "{$locality} Plot for Sale in {$city}";
        }
        if ($ptype === 'Shop') {
            return "Commercial Shop in {$society}, {$locality}";
        }
        if ($ptype === 'Office Space') {
            return "Office Space for Rent/Sale in {$locality}, {$city}";
        }
        if ($ptype === 'Studio Apartment') {
            return "Studio Apartment in {$society}, {$locality}, {$city}";
        }
        $bhk = $bhkType ?? '2 BHK';
        $prefixes = ['Spacious','Premium','Well-Maintained','Modern','Luxury','Beautiful','Bright','Corner'];
        $prefix = $prefixes[$idx % count($prefixes)];
        return "{$prefix} {$bhk} {$ptype} in {$society}, {$locality}";
    }

    private function generateDescription(string $ptype, $bedrooms, string $society, string $locality, string $city, string $amenities, string $possession): string
    {
        $bhk = $bedrooms ? "{$bedrooms} BHK " : '';

        if ($ptype === 'Plot') {
            return "Residential plot available in {$locality}, {$city}. Prime location with wide roads and underground utilities. {$possession}. Ideal for investment or self-construction. Contact for more details.";
        }

        if ($ptype === 'Shop' || $ptype === 'Office Space') {
            return "Commercial {$ptype} available in {$locality}, {$city}. Prime location with excellent footfall and visibility. Good connectivity to major roads. Suitable for retail/office use. Contact for more details.";
        }

        $amenitiesShort = implode(', ', array_slice(explode(',', $amenities), 0, 5));

        return "Beautiful {$bhk}{$ptype} available in {$society}, {$locality}, {$city}. This well-designed property offers modern interiors, quality construction and excellent ventilation. The society provides premium amenities including {$amenitiesShort}. {$possession}. Located in a prime residential area with easy access to schools, hospitals and markets. Contact us for a site visit.";
    }

    private function getLandmark(string $city, string $locality): string
    {
        $landmarks = [
            'Zirakpur' => ['Fortis Hospital','Civil Hospital Zirakpur','VIP Road Chowk','Airport Road','Peer Mushalla Chowk','SBP City of Dreams','Maya Garden City','Lohgarh Chowk'],
            'Mohali'   => ['Fortis Hospital Mohali','Max Super Speciality Hospital','Chandigarh Airport','Wave Estate','GMADA Office','Phase 10 Market','Sector 70 Market','IT Park'],
            'Chandigarh' => ['PGIMER','Sector 17 Market','Elante Mall','Rock Garden','Sukhna Lake','Tribune Chowk','ISBT 43','Airport Chandigarh'],
            'Panchkula' => ['Civil Hospital Panchkula','Sector 11 Market','HUDA Complex','Piccadilly Hotel','Panchkula Bus Stand','Kalka Highway','MDC Sector 5'],
        ];

        $options = $landmarks[$city] ?? ['Local Market'];
        return $options[array_rand($options)];
    }

    private function getMonthlyRent(string $ptype, $bedrooms, string $city): int
    {
        $base = [
            'Chandigarh' => ['1BHK'=>12000,'2BHK'=>20000,'3BHK'=>30000,'4BHK'=>50000,'Villa'=>80000,'Shop'=>25000,'Office Space'=>30000,'Studio'=>8000,'default'=>18000],
            'Mohali'     => ['1BHK'=>8000,'2BHK'=>14000,'3BHK'=>22000,'4BHK'=>35000,'Villa'=>55000,'Shop'=>18000,'Office Space'=>22000,'Studio'=>6000,'default'=>14000],
            'Panchkula'  => ['1BHK'=>8000,'2BHK'=>14000,'3BHK'=>20000,'4BHK'=>32000,'Villa'=>50000,'Shop'=>15000,'Office Space'=>18000,'Studio'=>6000,'default'=>14000],
            'Zirakpur'   => ['1BHK'=>7000,'2BHK'=>12000,'3BHK'=>18000,'4BHK'=>28000,'Villa'=>45000,'Shop'=>15000,'Office Space'=>18000,'Studio'=>5500,'default'=>12000],
        ];

        $cityRents = $base[$city] ?? $base['Zirakpur'];

        if ($ptype === 'Shop') return rand($cityRents['Shop'] * 8 / 10, $cityRents['Shop'] * 12 / 10);
        if ($ptype === 'Office Space') return rand($cityRents['Office Space'] * 8 / 10, $cityRents['Office Space'] * 12 / 10);
        if ($ptype === 'Villa') return rand($cityRents['Villa'] * 8 / 10, $cityRents['Villa'] * 12 / 10);
        if ($ptype === 'Studio Apartment') return rand($cityRents['Studio'] * 8 / 10, $cityRents['Studio'] * 12 / 10);

        $key = ($bedrooms ? "{$bedrooms}BHK" : 'default');
        $rent = $cityRents[$key] ?? $cityRents['default'];
        return rand((int)($rent * 0.8), (int)($rent * 1.2));
    }
}
