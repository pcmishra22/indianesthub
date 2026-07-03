<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class HyderabadBuildersBatch2 extends Seeder
{
    public function run(): void
    {
        $hydDataPart2 = [
            11 => ['Prestige Group Hyd', 'Prestige Garden Estates Private Ltd', 'sales.hyd@prestigeconstructions.com', 'PresH11', 'Narsingi', 'Prestige Clarens', 17.3892, 78.3514, 11500000, 26000000],
            12 => ['Cybercity Builders', 'Cybercity Projects Private Limited', 'info@cybercity.in', 'Cybe12', 'Miyapur', 'Cybercity Marina Skies', 17.4952, 78.3698, 8200000, 16500000],
            13 => ['Poulomi Estates', 'Poulomi Estates Private Limited', 'sales@poulomi.in', 'Pou13', 'Kokapet', 'Poulomi Palazzo Ultra', 17.3989, 78.3212, 32000000, 75000000],
            14 => ['Incor Infrastructure', 'Incor Lakeshore Developments', 'info@incor.in', 'Inc14', 'Gachibowli', 'Incor One City', 17.4425, 78.3584, 8900000, 17800000],
            15 => ['Aditya Construction Co', 'Aditya Construction Company India', 'sales@adityacc.com', 'Adi15', 'Manikonda', 'Aditya Empress Towers', 17.3994, 78.3812, 7400000, 15000000],
            16 => ['Vertex Homes', 'Vertex Homes Private Limited', 'sales@vertexhomes.com', 'Vert16', 'Bachupally', 'Vertex Vibe', 17.5341, 78.3512, 5800000, 11000000],
            17 => ['Sakarmenta Infra', 'Saket Engineers Private Ltd', 'info@saket.co.in', 'Sak17', 'Kompally', 'Saket Bhuvi', 17.5492, 78.4721, 6200000, 12000000],
            18 => ['Sattva Group Hyd', 'Sattva Salarpuria Group Sub', 'hyd@sattvagroup.com', 'SatH18', 'Shaikpet', 'Sattva Magnus', 17.4012, 78.3984, 13500000, 29000000],
            19 => ['Mu値a Group', 'Muppa Projects India Pvt Ltd', 'sales@muppa.com', 'Mup19', 'Tellapur', 'Muppa Melody', 17.4561, 78.2714, 7900000, 14800000],
            20 => ['Jain Housing Hyd', 'Jain Housing & Constructions Sub', 'saleshyd@jainhousing.com', 'Jain20', 'Tolichowki', 'Jain Carlton Creek', 17.4045, 78.4012, 8800000, 18000000],
        ];

        foreach ($hydDataPart2 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '040' . (60001000 + $index), 'city' => 'Hyderabad', 'cities_operating' => 'Hyderabad',
                    'established_year' => '2008', 'is_verified' => true, 'total_delivered_projects' => 18, 'rating' => 4.4,
                    'description' => $data[0] . ' premium phase integration built alongside contemporary landscape design metrics.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' delivers strategic transit access loops combined with luxury double-height lobbies, dedicated co-working modules, and EV charging points.',
                    'address' => $data[4] . ' Tech Center Link, Hyderabad, Telangana', 'city' => 'Hyderabad', 'state' => 'Telangana',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 310, 'available_units' => 125,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2029-09-30', 'total_towers' => 3,
                    'floors_per_tower' => '34', 'is_featured' => false, 'views_count' => 840, 'leads_count' => 0,
                    'nearby_schools' => 'Chirec International School (3.8 km)', 'nearby_hospitals' => 'AIG Hospitals (4.2 km)',
                    'metro_distance' => 'Accessible through regional ring corridors', 'connectivity_score' => '8',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Hyderabad')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Hyderabad Batch 2 Configured Successfully.');
    }
}