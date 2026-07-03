<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class MmrBuildersBatch2 extends Seeder
{
    public function run(): void
    {
        $mmrDataPart2 = [
            11 => ['Wadhwa Group', 'The Wadhwa Group Real Estate', 'sales@wadhwagroup.com', 'Wadh11', 'Ghodbunder Road', 'Wadhwa Courtyard', 19.2451, 72.9612, 11500000, 24000000],
            12 => ['Indiabulls Real Estate', 'Indiabulls Infra Ventures', 'sales@indiabulls.com', 'IBRE12', 'Panvel', 'Indiabulls Park Meadows', 18.9912, 73.1245, 6800000, 15500000],
            13 => ['Raymond Realty', 'Raymond Limited Real Estate Div', 'info@raymondrealty.in', 'Ray13', 'Pokhran Road Thane', 'Raymond Ten X Habitat', 19.2142, 72.9689, 13500000, 28000000],
            14 => ['Vicco Developers', 'Vihi Properties & Infrastructure', 'sales@viccodevelopers.in', 'Vic14', 'Mulund West', 'Vicco Atmosphere', 19.1694, 72.9412, 17500000, 39000000],
            15 => ['Dosti Realty', 'Dosti Enterprises Real Estate', 'sales@dostirealty.com', 'Dost15', 'Balkum Thane', 'Dosti West County', 19.2241, 72.9912, 9200000, 19000000],
            16 => ['Sheth Creators', 'Sheth Creators Private Limited', 'info@shethcreators.com', 'Sheth16', 'Malad West', 'Sheth Auris Serenity', 19.1845, 72.8312, 21000000, 48000000],
            17 => ['Paradise Group', 'Paradise Lifespaces Developers', 'sales@paradisegroup.co.in', 'Para17', 'Kharghar', 'Paradise Sai World Empire', 19.0412, 73.0784, 12800000, 29000000],
            18 => [' Marathon Realty', 'Marathon Nextgen Realty Ltd', 'sales@marathon.in', 'Mara18', 'Bhandup West', 'Marathon Emblem', 19.1512, 72.9345, 14200000, 32000000],
            19 => ['Ajmera Realty', 'Ajmera Realty & Infra India Ltd', 'info@ajmera.com', 'Ajm19', 'Wadala', 'Ajmera Manhattan', 19.0298, 72.8741, 26000000, 62000000],
            20 => ['Sunteck Realty', 'Sunteck Realty Limited Corporate', 'sales@sunteckindia.com', 'Sun20', 'Naigaon East', 'Sunteck WestWorld', 19.3412, 72.8589, 4500000, 9200000],
        ];

        foreach ($mmrDataPart2 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '022' . (70001000 + $index), 'city' => 'Mumbai', 'cities_operating' => 'Mumbai, Thane, Navi Mumbai',
                    'established_year' => '1996', 'is_verified' => true, 'total_delivered_projects' => 38, 'rating' => 4.6,
                    'description' => $data[0] . ' premium vertical tower configuration adhering strictly to MahaRERA bylaws.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' implements cross-ventilation floor layouts, advanced wind-resistant engineering, high-capacity fire suppression pipelines, and structural podium parking systems.',
                    'address' => $data[4] . ' Macro Transit Node Loop, Mumbai, Maharashtra', 'city' => 'Mumbai', 'state' => 'Maharashtra',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 340, 'available_units' => 140,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2030-03-31', 'total_towers' => 3,
                    'floors_per_tower' => '42', 'is_featured' => false, 'views_count' => 1650, 'leads_count' => 0,
                    'nearby_schools' => 'Singhania High School Thane (3.2 km)', 'nearby_hospitals' => 'Jupiter Hospital (2.5 km)',
                    'metro_distance' => 'Walking distance to upcoming suburban urban rail intersections', 'connectivity_score' => '9',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Mumbai')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Mumbai (MMR) Batch 2 Configured Successfully.');
    }
}