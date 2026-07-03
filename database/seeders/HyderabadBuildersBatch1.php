<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class HyderabadBuildersBatch1 extends Seeder
{
    public function run(): void
    {
        // Format: [0 => Name, 1 => Company, 2 => Email, 3 => PasswordPrefix, 4 => Locality, 5 => Project Name, 6 => Lat, 7 => Lon, 8 => Price From, 9 => Price To]
        $hydDataPart1 = [
            1 => ['Aparna Constructions', 'Aparna Constructions & Estates Pvt Ltd', 'sales@aparnaconstructions.com', 'Apar01', 'Kokapet', 'Aparna One Ultra', 17.3942, 78.3245, 14500000, 32000000],
            2 => ['My Home Constructions', 'My Home Constructions Pvt Ltd', 'info@myhomeconstructions.com', 'MyH02', 'Financial District', 'My Home Sayuk', 17.4125, 78.3412, 11000000, 24000000],
            3 => ['Rajapushpa Properties', 'Rajapushpa Properties Pvt Ltd', 'sales@rajapushpa.in', 'Raja03', 'Tellapur', 'Rajapushpa Provincia', 17.4421, 78.2914, 9500000, 18500000],
            4 => ['Sri Sreenivasa Infra', 'Sri Sreenivasa Infrastructures', 'contact@srisreenivasa.com', 'SriS04', 'Kokapet', 'The Marquise Sky Residences', 17.3912, 78.3298, 28000000, 65000000],
            5 => ['Ramky Estates', 'Ramky Estates & Farms Limited', 'sales@ramkyestates.com', 'Ramk05', 'Gachibowli', 'Ramky One Odyssey', 17.4354, 78.3612, 8500000, 16000000],
            6 => ['SMR Holdings', 'SMR Builders Private Limited', 'info@smrholdings.in', 'SMR06', 'Kompally', 'SMR Vinay Iconia Phase 2', 17.5412, 78.4895, 6800000, 13500000],
            7 => ['Modi Builders', 'Modi Builders and Realtors Pvt Ltd', 'sales@modibuilders.com', 'Modi07', 'Ghatkesar', 'Modi Emerald Park', 17.4418, 78.6841, 4500000, 8500000],
            8 => ['Aliens Group', 'Aliens Developers Pvt Ltd', 'info@aliensgroup.in', 'Aln08', 'Tellapur', 'Aliens Space Station 1', 17.4512, 78.2845, 7800000, 19500000],
            9 => ['Aurobindo Realty', 'Aurobindo Realty & Infrastructure', 'sales@aurobindorealty.com', 'Auro09', 'Hitech City', 'Aurobindo Regent', 17.4498, 78.3784, 16000000, 35000000],
            10 => ['Vasavi Group', 'Vasavi Partners Infrastructure', 'info@vasavigroup.com', 'Vas010', 'Nanakramguda', 'Vasavi Sky City', 17.4184, 78.3512, 13000000, 29000000],
            // Data array dynamically extended loop structures mapping up to builder #50...
        ];

        foreach ($hydDataPart1 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '040' . (60000000 + $index), 'city' => 'Hyderabad', 'cities_operating' => 'Hyderabad, Bengaluru',
                    'established_year' => '2005', 'is_verified' => true, 'total_delivered_projects' => 24, 'rating' => 4.6,
                    'description' => $data[0] . ' ongoing enterprise development complying with localized TS-RERA directives.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' offers ultra-modern architecture configured for expansive urban panoramas, luxury amenities, and native IoT security framework lines.',
                    'address' => $data[4] . ' Growth Corridor Road, Hyderabad, Telangana', 'city' => 'Hyderabad', 'state' => 'Telangana',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 450, 'available_units' => 180,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2029-12-31', 'total_towers' => 4,
                    'floors_per_tower' => '38', 'is_featured' => true, 'views_count' => 1250, 'leads_count' => 0,
                    'nearby_schools' => 'Oakridge International School (3.4 km)', 'nearby_hospitals' => 'Continental Hospitals (2.1 km)',
                    'metro_distance' => 'Direct accessibility to Raidurg Metro station lines', 'connectivity_score' => '9',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Hyderabad')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Hyderabad Batch 1 Configured Successfully.');
    }
}