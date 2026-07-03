<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class MmrBuildersBatch1 extends Seeder
{
    public function run(): void
    {
        $mmrDataPart1 = [
            1 => ['Lodha Developers', 'Lodha Developers Limited', 'sales@lodhagroup.com', 'Lod01', 'Lower Parel', 'The World Towers Luxe', 18.9942, 72.8291, 45000000, 220000000],
            2 => ['Godrej Properties MMR', 'Godrej Properties Mumbai Division', 'mumbai@godrejproperties.com', 'Godm02', 'Vikhroli', 'Godrej The Trees', 19.0984, 72.9241, 21000000, 68000000],
            3 => ['Oberoi Realty', 'Oberoi Realty Private Limited', 'sales@oberoirealty.com', 'Obe03', 'Goregaon East', 'Oberoi Elysian', 19.1684, 72.8612, 38000000, 85000000],
            4 => ['K Raheja Corp', 'K Raheja Corp Homes Division', 'info@krahejacorp.com', 'KRh04', 'Wadala', 'Raheja Viva Residences', 19.0214, 72.8698, 29000000, 75000000],
            5 => ['Shapoorji Pallonji', 'Shapoorji Pallonji Real Estate', 'sales@shapoorjirealty.com', 'SP05', 'Kandivali East', 'Shapoorji Pallonji BKC 28', 19.2112, 72.8714, 16500000, 39000000],
            6 => ['Hiranandani Group', 'Hiranandani Communities Corp', 'care@hiranandani.net', 'Hir06', 'Thane West', 'Hiranandani Meadows Phase 3', 19.2198, 72.9721, 19500000, 51000000],
            7 => ['Rustomjee Spaces', 'Rustomjee Developers Private Ltd', 'sales@rustomjee.com', 'Rust07', 'Juhu', 'Rustomjee Elements', 19.1042, 72.8245, 85000000, 240000000],
            8 => ['Kalpataru Group', 'Kalpataru Limited Corporate', 'info@kalpataru.com', 'Kalp08', 'Panvel', 'Kalpataru Parkside', 18.9894, 73.1124, 7200000, 18500000],
            9 => ['Runwal Group', 'Runwal Developers Private Limited', 'sales@runwal.com', 'Run09', 'Kanjurmarg', 'Runwal Bliss Towers', 19.1245, 72.9384, 13800000, 31000000],
            10 => ['Piramal Realty', 'Piramal Realty Ventures', 'sales@piramalrealty.com', 'Pira10', 'Mahalakshmi', 'Piramal Mahalakshmi', 18.9812, 72.8284, 42000000, 145000000],
        ];

        foreach ($mmrDataPart1 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '022' . (70000000 + $index), 'city' => 'Mumbai', 'cities_operating' => 'Mumbai, Thane, Navi Mumbai',
                    'established_year' => '1992', 'is_verified' => true, 'total_delivered_projects' => 58, 'rating' => 4.8,
                    'description' => $data[0] . ' premium architectural skyscraper framework integrated with 2026 MahaRERA parameters.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' redefines macro-metropolitan skyline engineering, providing optimized custom floor layouts, zero space wastage, and multi-tier sea-deck club access points.',
                    'address' => $data[4] . ' Sea-Link Proximity Loop, Mumbai, Maharashtra', 'city' => 'Mumbai', 'state' => 'Maharashtra',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 280, 'available_units' => 95,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2030-06-30', 'total_towers' => 2,
                    'floors_per_tower' => '55', 'is_featured' => true, 'views_count' => 2450, 'leads_count' => 0,
                    'nearby_schools' => 'Dhirubhai Ambani International School (5.1 km)', 'nearby_hospitals' => 'Kokilaben Dhirubhai Ambani Hospital (3.8 km)',
                    'metro_distance' => 'Direct overhead skywalk link paths matching active city lines', 'connectivity_score' => '10',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Mumbai')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Mumbai (MMR) Batch 1 Configured Successfully.');
    }
}