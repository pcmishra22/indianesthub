<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class NcrBuildersBatch2 extends Seeder
{
    public function run(): void
    {
        $ncrDataPart2 = [
            11 => ['Eldeco Group', 'Eldeco Infrastructure & Properties', 'sales@eldecohousing.com', 'Eld11', 'Sector 150 Noida', 'Eldeco Live By The Greens', 28.4594, 77.4812, 11000000, 24000000],
            12 => ['Gaurs Group', 'Gaursons India Private Limited', 'sales@gaursonsindia.com', 'Gaur12', 'Greater Noida West', 'Gaur City 2 Luxury', 28.6094, 77.4398, 6500000, 14000000],
            13 => ['Ats Infrastructure', 'ATS Infrastructure Limited', 'info@atsgreens.com', 'ATS13', 'Sector 152 Noida', 'ATS Picturesque Repos', 28.4484, 77.4912, 12500000, 29000000],
            14 => ['Mahagun Group', 'Mahagun India Private Limited', 'sales@mahagunindia.com', 'Maha14', 'Sector 128 Noida', 'Mahagun Manorialle Luxe', 28.5312, 77.3894, 32000000, 85000000],
            15 => ['Supertech Sub', 'Supertech Corporate Real Estate', 'care@supertechlimited.com', 'Supr15', 'Greater Noida', 'Supertech Eco Village 4', 28.5812, 77.4584, 5200000, 9800000],
            16 => ['Ashiana Housing', 'Ashiana Housing Limited', 'sales@ashianahousing.com', 'Ash16', 'Sohna Road', 'Ashiana Anmol Kid Centric', 28.2612, 77.0745, 8500000, 16500000],
            17 => ['Hero Realty', 'Hero Realty Private Limited', 'info@herorealty.in', 'Hero17', 'Sector 104 Gurugram', 'Hero Homes World', 28.4942, 76.9845, 13000000, 27000000],
            18 => ['Whiteland Corp', 'Whiteland Developers Private Ltd', 'sales@whiteland.in', 'White18', 'Sector 76 Gurugram', 'Whiteland Blissville', 28.3912, 77.0142, 19500000, 45000000],
            19 => ['Central Park India', 'St. Angelo CC Developers Sub', 'info@centralpark.in', 'Cent19', 'Sohna Road', 'Central Park Flower Valley', 28.2841, 77.0512, 16000000, 39000000],
            20 => ['SS Group', 'SS Group Private Limited', 'sales@ssgroup-india.com', 'SSG20', 'Sector 84 Gurugram', 'SS The Leaf Premium', 28.3998, 76.9451, 14000000, 31000000],
        ];

        foreach ($ncrDataPart2 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '0124' . (4001000 + $index), 'city' => 'Delhi-NCR', 'cities_operating' => 'Gurugram, Noida, Greater Noida',
                    'established_year' => '2002', 'is_verified' => true, 'total_delivered_projects' => 31, 'rating' => 4.5,
                    'description' => $data[0] . ' premium regional structural footprint mapped against dynamic state zoning laws.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' offers carefully optimized thermal performance mechanics, spacious dual balconies, multi-tier sports arenas, and seamless links to surrounding commercial hubs.',
                    'address' => $data[4] . ' Expressway Belt Infrastructure, Delhi-NCR', 'city' => 'Delhi-NCR', 'state' => 'Haryana',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 400, 'available_units' => 165,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2029-08-31', 'total_towers' => 6,
                    'floors_per_tower' => '28', 'is_featured' => false, 'views_count' => 1120, 'leads_count' => 0,
                    'nearby_schools' => 'DPS International Edge (2.9 km)', 'nearby_hospitals' => 'Artemis Hospital Gurugram (5.5 km)',
                    'metro_distance' => 'Positioned along high-growth arterial rapid metro lanes', 'connectivity_score' => '8',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Delhi-NCR')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Delhi-NCR Batch 2 Configured Successfully.');
    }
}