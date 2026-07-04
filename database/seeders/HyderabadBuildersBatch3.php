<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

class HyderabadBuildersBatch3 extends Seeder
{
    public function run(): void
    {
        $hydDataPart3 = [
            21 => ['SMR Infra Structures', 'SMR Builders & Holdings Corp', 'sales@smrstructures.in', 'SMRStruct21', 'Khajaguda', 'SMR Vinay Boulder Woods', 17.4114, 78.3751, 14500000, 28000000],
            22 => ['Janapriya Nile Valley', 'Janapriya Engineers Syndicate', 'info@janapriya.com', 'Jana22', 'Pragathi Nagar', 'Janapriya Nile Valley', 17.5121, 78.3842, 5200000, 9500000],
            23 => ['Praneeth Group', 'Praneeth Media & Constructions Pvt Ltd', 'sales@praneeth.com', 'Pran23', 'Bowrampet', 'Praneeth Pranav Leaf', 17.5641, 78.3712, 6400000, 11500000],
            24 => ['Candeur Developers', 'Candeur Constructions Pvt Ltd', 'sales@candeur.in', 'Cand24', 'Miyapur', 'Candeur Crescent Towers', 17.4912, 78.3541, 7800000, 15500000],
            25 => ['GHR Infra', 'GHR Infra Projects LLP', 'contact@ghrinfra.com', 'GHR25', 'Alwal', 'GHR Callisto', 17.5112, 78.5042, 5900000, 10800000],
            26 => ['Sadhguru Homes', 'Sadhguru Housing Private Limited', 'sales@sadhguruhomes.com', 'Sadh26', 'Tellapur', 'Sadhguru Mythri Hills', 17.4489, 78.2645, 8200000, 16000000],
            27 => ['Ark Infra', 'Ark Infra Projects India Private Ltd', 'info@arkinfra.com', 'Ark27', 'Kapra', 'Ark Homes Elite', 17.4851, 78.5614, 4900000, 9200000],
            28 => ['Summit Spaces', 'Summit Real Estate Enterprises', 'sales@summitspaces.in', 'Sum28', 'Financial District', 'Summit One Vista', 17.4145, 78.3312, 19000000, 41000000],
            29 => ['GMR Urban', 'GMR Urban Infrastructure Sub', 'sales@gmrurban.com', 'GMR29', 'Shamshabad', 'GMR Aerocity Edge', 17.2412, 78.4314, 11000000, 25000000],
            30 => ['Saket Engineers', 'Saket Sriyam Developers Division', 'info@saket.co.in', 'Skt30', 'Kompally', 'Saket Sriyam Towers', 17.5451, 78.4812, 6800000, 13000000],
        ];

        foreach ($hydDataPart3 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '040' . (60002000 + $index), 'city' => 'Hyderabad', 'cities_operating' => 'Hyderabad',
                    'established_year' => '2012', 'is_verified' => true, 'total_delivered_projects' => 14, 'rating' => 4.3,
                    'description' => $data[0] . ' localized urban micro-community adhering to modern environment footprint clearings.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' implements smart layout frameworks maximizing vertical spatial efficiency, standard structural solar water lines, and dual clubhouse clusters.',
                    'address' => $data[4] . ' Regional Transit Connection Avenue, Hyderabad, Telangana', 'city' => 'Hyderabad', 'state' => 'Telangana',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 290, 'available_units' => 115,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2029-10-31', 'total_towers' => 2,
                    'floors_per_tower' => '28', 'is_featured' => false, 'views_count' => 620, 'leads_count' => 0,
                    'nearby_schools' => 'Silver Oaks International School (3.1 km)', 'nearby_hospitals' => 'Continental Hospitals Extension (4.0 km)',
                    'metro_distance' => 'Approx. 15 minutes to nearest metro corridor link', 'connectivity_score' => '8',
                ]
            );
        }

        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Hyderabad')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Hyderabad Batch 3 Configured Successfully.');
    }
}
