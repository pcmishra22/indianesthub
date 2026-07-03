<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class NcrBuildersBatch3 extends Seeder
{
    public function run(): void
    {
        $ncrDataPart3 = [
            21 => ['Omaxe Limited', 'Omaxe Infrastructure Developers Plc', 'sales@omaxe.com', 'Omax21', 'Sector 93 Noida', 'Omaxe Grand Woods', 28.5241, 77.3912, 13500000, 29000000],
            22 => ['Supertech Luxury Line', 'Supertech Limited Premium Sub', 'luxury@supertech.in', 'SupL22', 'Sector 94 Noida', 'Supertech Supernova Spira', 28.5412, 77.3245, 24000000, 95000000],
            23 => ['Chintels India', 'Chintels India Private Limited', 'sales@chintels.com', 'Chin23', 'Sector 109 Gurugram', 'Chintels Paradiso', 28.5198, 76.9841, 14500000, 31000000],
            24 => ['Bestech Group', 'Bestech India Private Limited', 'info@bestechgroup.com', 'Best24', 'Sector 81 Gurugram', 'Bestech Park View Grand', 28.3992, 76.9384, 16500000, 34000000],
            25 => ['Supertech Eco', 'Supertech Value Housing Sub', 'sales@supertech.in', 'SupE25', 'Greater Noida West', 'Supertech Eco Village 1', 28.6142, 77.4498, 4800000, 9200000],
            26 => ['Logix Group', 'Logix Infratech Private Limited', 'sales@logixgroup.in', 'Log26', 'Sector 137 Noida', 'Logix Blossom County', 28.5084, 77.4112, 7800000, 16000000],
            27 => ['Emaar Business', 'Emaar MGF Land India Division', 'commercial@emaar.in', 'EmaB27', 'Golf Course Extension Road', 'Emaar Digital Greens', 28.4014, 77.0784, 21000000, 55000000],
            28 => ['Experion Developers', 'Experion Hospitality & Developers', 'sales@experion.co.in', 'Exp28', 'Sector 108 Gurugram', 'Experion Windchants', 28.5284, 76.9798, 26000000, 78000000],
            29 => ['AIPL Group', 'Advance India Projects Limited', 'info@aipl.com', 'AIPL29', 'Sector 66 Gurugram', 'AIPL The Peaceful Homes', 28.4045, 77.0541, 15500000, 33000000],
            30 => ['Wave Infratech', 'Wave City Mega City Center', 'sales@wavecity.in', 'Wave30', 'Ghaziabad Highway Node', 'Wave City Veridia', 28.6412, 77.4984, 8200000, 17500000],
        ];

        foreach ($ncrDataPart3 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '0124' . (4002000 + $index), 'city' => 'Delhi-NCR', 'cities_operating' => 'Gurugram, Noida, Ghaziabad',
                    'established_year' => '2004', 'is_verified' => true, 'total_delivered_projects' => 28, 'rating' => 4.4,
                    'description' => $data[0] . ' structural layout verified alongside relevant dynamic regulatory building frameworks.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' implements wide floor layout planning arrays, central heating ventilation cooling frameworks, robust backup networks, and heavy perimeter security infrastructure.',
                    'address' => $data[4] . ' Regional Transit Hubway, Delhi-NCR', 'city' => 'Delhi-NCR', 'state' => 'Uttar Pradesh',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 420, 'available_units' => 190,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2029-11-30', 'total_towers' => 5,
                    'floors_per_tower' => '30', 'is_featured' => false, 'views_count' => 1050, 'leads_count' => 0,
                    'nearby_schools' => 'Amity International School Noida (3.5 km)', 'nearby_hospitals' => 'Jaypee Hospital (4.0 km)',
                    'metro_distance' => 'Seamless links to national capital expressway lines', 'connectivity_score' => '8',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Delhi-NCR')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Delhi-NCR Batch 3 Configured Successfully.');
    }
}