<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class NcrBuildersBatch1 extends Seeder
{
    public function run(): void
    {
        $ncrDataPart1 = [
            1 => ['DLF Limited', 'DLF Home Developers Group', 'sales@dlf.in', 'DLF01', 'Golf Course Extension Road', 'DLF Privana South', 28.3984, 76.9841, 65000000, 140000000],
            2 => ['M3M India', 'M3M India Private Limited', 'info@m3mindia.com', 'M3M02', 'Dwarka Expressway', 'M3M Capital Luxury', 28.5214, 76.9942, 18000000, 42000000],
            3 => ['Signature Global', 'Signature Global Developers Plc', 'sales@signatureglobal.in', 'Sig03', 'Sohna Road', 'Signature Global Park', 28.2491, 77.0614, 7500000, 16000000],
            4 => ['County Group', 'County Infrastructure Pvt Ltd', 'info@countygroup.co.in', 'Cty04', 'Sector 115 Noida', 'Ivory County Towers', 28.5841, 77.4125, 35000000, 128000000],
            5 => ['ACE Group', 'ACE Real Estate Development', 'sales@acegroupindia.com', 'ACE05', 'Sector 150 Noida', 'ACE Parkway Elite', 28.4612, 77.4698, 16000000, 38000000],
            6 => ['Gulshan Homz', 'Gulshan Homz Infrastructure', 'info@gulshanhomz.com', 'Gul06', 'Sector 144 Noida', 'Gulshan Dynasty Luxury', 28.4984, 77.4241, 90000000, 150000000],
            7 => ['Smartworld Developers', 'Smartworld Developers Private Ltd', 'sales@smartworld.com', 'Smart07', 'Dwarka Expressway', 'Smartworld One DXP', 28.5142, 76.9745, 14500000, 29000000],
            8 => ['Emaar India', 'Emaar India Communities Sub', 'care@emaar-india.com', 'Emar08', 'Sector 62 Gurugram', 'Emaar Serenity Hills', 28.4112, 77.0841, 24000000, 52000000],
            9 => ['Krisumi Corporation', 'Krisumi Indo-Japan Developers', 'sales@krisumi.com', 'Kris09', 'Sector 36A Gurugram', 'Krisumi Waterside Residences', 28.4284, 76.9612, 19000000, 38000000],
            10 => ['Godrej Properties NCR', 'Godrej Properties Limited Sub', 'sales@godrejproperties.com', 'Godn10', 'Sector 146 Noida', 'Godrej Tropical Isle', 28.4812, 77.4451, 26000000, 79000000],
        ];

        foreach ($ncrDataPart1 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '0124' . (4000000 + $index), 'city' => 'Delhi-NCR', 'cities_operating' => 'Delhi, Gurugram, Noida',
                    'established_year' => '1998', 'is_verified' => true, 'total_delivered_projects' => 45, 'rating' => 4.7,
                    'description' => $data[0] . ' continuous luxury expansion layout verified under HRERA / UPRERA tracking codes.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' offers low-density structural floorplates, integrated global clubhouse clubs, wide-perimeter green belts, and premium interior configurations.',
                    'address' => $data[4] . ' National Capital Growth Zone, Delhi-NCR', 'city' => 'Delhi-NCR', 'state' => 'Delhi',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 320, 'available_units' => 110,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2030-03-31', 'total_towers' => 5,
                    'floors_per_tower' => '32', 'is_featured' => true, 'views_count' => 1890, 'leads_count' => 0,
                    'nearby_schools' => 'The Heritage Xperiential Learning School (4.2 km)', 'nearby_hospitals' => 'Medanta The Medicity (6.0 km)',
                    'metro_distance' => 'Linked seamlessly to major upcoming Regional Rapid Transit System nodes', 'connectivity_score' => '9',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Delhi-NCR')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Delhi-NCR Batch 1 Configured Successfully.');
    }
}