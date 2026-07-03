<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class MmrBuildersBatch3 extends Seeder
{
    public function run(): void
    {
        $mmrDataPart3 = [
            21 => ['Omkar Realtors', 'Omkar Realtors and Developers Pvt Ltd', 'sales@omkar.com', 'Omk21', 'Worli', 'Omkar 1973 Luxury', 18.9998, 72.8184, 95000000, 340000000],
            22 => ['Hubtown Limited', 'Hubtown Infrastructure Limited', 'info@hubtown.co.in', 'Hub22', 'Prabhadevi', 'Hubtown 25 South', 19.0142, 72.8254, 75000000, 210000000],
            23 => ['Kanakia Spaces', 'Kanakia Spaces Private Limited', 'sales@kanakia.com', 'Kan23', 'Kanjurmarg East', 'Kanakia Silicon Valley', 19.1212, 72.9284, 14500000, 33000000],
            24 => ['Peninsula Land', 'Peninsula Land Realty Group', 'info@peninsula.co.in', 'Pen24', 'Sewri', 'Peninsula Celestia Spaces', 19.0045, 72.8541, 31000000, 68000000],
            25 => ['L&T Realty MMR', 'Larsen & Toubro Realty Limited Sub', 'sales@ltrealty.com', 'LnTR25', 'Parel', 'L&T Crescent Bay', 19.0112, 72.8492, 28000000, 65000000],
            26 => ['Godrej Properties Thane', 'Godrej Highview Developments Sub', 'sales.thane@godrej.com', 'GodT26', 'Kolshet Road Thane', 'Godrej Ascend', 19.2312, 72.9845, 11000000, 24000000],
            27 => ['Siddha Group MMR', 'Siddha Infratech Real Estate', 'info@siddhagroup.com', 'Sid27', 'Wadala', 'Siddha Sky Skywalks', 19.0284, 72.8614, 16000000, 35000000],
            28 => ['G地产 Group', 'Grounded Real Estate Ventures', 'sales@grounded.in', 'Gnd28', 'Bandra West', 'Grounded Casa Elite', 19.0542, 72.8298, 120000000, 290000000],
            29 => ['Shreepati Group', 'Shreepati Infrastructure Private Ltd', 'info@shreepatigroup.com', 'Shree29', 'Girgaon', 'Shreepati Towers Luxe', 18.9592, 72.8212, 48000000, 115000000],
            30 => ['Indiabulls Sky', 'Indiabulls Housing Developments Sub', 'sales@indiabullsrealestate.com', 'IBS30', 'Lower Parel', 'Indiabulls Sky Forest', 18.9914, 72.8312, 62000000, 180000000],
        ];

        foreach ($mmrDataPart3 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '022' . (70002000 + $index), 'city' => 'Mumbai', 'cities_operating' => 'Mumbai, Thane',
                    'established_year' => '1999', 'is_verified' => true, 'total_delivered_projects' => 42, 'rating' => 4.7,
                    'description' => $data[0] . ' ultra-luxury vertical landmark footprint certified under MahaRERA framework loops.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' exhibits state-of-the-art structural damping systems, multi-tiered panoramic sea decks, private elevator vestibules, and custom automation touchpoints.',
                    'address' => $data[4] . ' Premium Seafront Arterial Node, Mumbai, Maharashtra', 'city' => 'Mumbai', 'state' => 'Maharashtra',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 210, 'available_units' => 85,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2030-09-30', 'total_towers' => 2,
                    'floors_per_tower' => '62', 'is_featured' => false, 'views_count' => 2100, 'leads_count' => 0,
                    'nearby_schools' => 'Bombay Scottish School (4.5 km)', 'nearby_hospitals' => 'KEM Hospital (2.8 km)',
                    'metro_distance' => 'Immediate adjacency to prime overhead rapid transit nodes', 'connectivity_score' => '10',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Mumbai')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Mumbai (MMR) Batch 3 Configured Successfully.');
    }
}