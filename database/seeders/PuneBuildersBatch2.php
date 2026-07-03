<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * PuneBuildersBatch2
 * 50 Builders / Projects — Batch 2 of 2 (Target: 100 Builders)
 */
class PuneBuildersBatch2 extends Seeder
{
    public function run(): void
    {
        $buildersDataPart2 = [
            51 => ['Ceratec Construction', 'Ceratec Group', 'sales@ceratec.com', 'Cera51', 'Wagholi', 'Ceratec Presidential Towers', 18.5798, 73.9845, 5900000, 12000000],
            52 => ['Namrata Developers', 'Namrata Group Builders', 'info@namratagroup.com', 'Nam52', 'Tathawade', 'Namrata Life 360', 18.6189, 73.7489, 6400000, 13000000],
            53 => ['Nandan Buildcon', 'Nandan Buildcon Pvt Ltd', 'sales@nandanbuildcon.com', 'Nan53', 'Baner', 'Nandan Prospera', 18.5612, 73.7789, 14000000, 28000000],
            54 => ['Amit Enterprises Housing', 'Amit Enterprises Ltd', 'sales@amitenterprises.com', 'Amit54', 'Ambegaon', 'Amit Bloomfield', 18.4512, 73.8345, 7500000, 16000000],
            55 => ['Darode Jog Properties', 'Darode Jog Real Estate', 'info@darodejog.com', 'Dar55', 'Sinhagad Road', 'Darode Jog Crossover County', 18.4898, 73.8189, 8200000, 15500000],
            56 => ['Skyline Builders Pune', 'Skyline Realty Ventures', 'sales@skylinepune.com', 'Sky56', 'Hadapsar', 'Skyline Horizon', 18.5045, 73.9389, 6100000, 11500000],
            57 => ['Prasanna Developers', 'Prasanna Construction Hub', 'sales@prasanna.com', 'Pras57', 'Kothrud', 'Prasanna Purple Space', 18.5045, 73.8012, 13000000, 29000000],
            58 => ['GK Associates', 'GK Developers India', 'sales@gkassociates.in', 'GKA58', 'Ravet', 'GK Aarav', 18.6412, 73.7498, 5600000, 10500000],
            59 => ['Siddhivinayak Groups', 'Siddhivinayak Homes Pune', 'info@siddhivinayak.com', 'Sidd59', 'Moshi', 'Siddhivinayak Vision City', 18.6812, 73.8545, 4300000, 8500000],
            60 => ['Ranjeet Developers', 'Ranjeet Infrastructure', 'sales@ranjeet.com', 'Ranj60', 'Wagholi', 'Ranjeet Gharkul', 18.5898, 73.9912, 3800000, 7500000],
            61 => ['Paranjape Schemes', 'Paranjape Schemes Construction Ltd', 'sales@pscl.in', 'Para61', 'Kothrud', 'Athashri Kothrud', 18.5089, 73.7912, 8500000, 19500000],
            62 => ['Ganesh Dasrath Dale', 'Ganesh Dale Constructions', 'sales@dale.com', 'Dale62', 'Tathawade', 'Dale Residency', 18.6212, 73.7598, 5900000, 11000000],
            63 => ['Oxford Realty', 'Oxford 1Earth Group', 'sales@oxfordrealty.com', 'Oxf63', 'Bavdhan', 'Oxford Florida Riverfront', 18.5245, 73.7612, 11000000, 32000000],
            64 => ['Yashada Realty', 'Yashada Realty Group', 'sales@yashadarealty.com', 'Yash64', 'Moshi', 'Yashada Supreme', 18.6645, 73.8412, 5100000, 9800000],
            65 => ['Shinpriya Realtors', 'Shinpriya Housing Properties', 'sales@shinpriya.com', 'Shin65', 'Dhanori', 'Shinpriya Shanti', 18.5998, 73.8945, 4800000, 9200000],
            66 => ['Hydepark Developers', 'Hydepark Real Estate Corp', 'sales@hydepark.com', 'Hyde66', 'Kondhwa', 'Hydepark Terraces', 18.4689, 73.8812, 7300000, 14500000],
            67 => ['Sanil Constructions', 'Sanil Builders', 'info@sanil.com', 'San67', 'Kiwale', 'Sanil Heights', 18.6512, 73.7389, 4400000, 8900000],
            68 => ['Sarsan Aawishkar Properties', 'Sarsan Group', 'sales@sarsan.com', 'Sar68', 'Katraj', 'Sarsan Aawishkar Hub', 18.4412, 73.8612, 5300000, 10500000],
            69 => ['Balaji Builders Pune', 'Balaji Construction Ventures', 'sales@balajipune.com', 'Bal69', 'Lohegaon', 'Balaji Vishwa', 18.6012, 73.9214, 4600000, 9000000],
            70 => ['Speciality Landmarks', 'Speciality Group Developers', 'sales@speciality.com', 'Spec70', 'Undri', 'Speciality Grandeur', 18.4589, 73.9012, 5100000, 9900000],
            71 => ['Eh Realty', 'Eh Realty Developments', 'sales@ehrealty.com', 'EhR71', 'Camp', 'Eh Landmark Towers', 18.5212, 73.8812, 16000000, 35000000],
            72 => ['Shree Parshwa Nagar Realty', 'Parshwa Nagar Hub', 'sales@parshwa.com', 'Par72', 'Chikhali', 'Parshwa Heights', 18.6612, 73.7912, 4100000, 7800000],
            73 => ['Kiwale Realty', 'Kiwale Infrastructure Partners', 'info@kiwalerealty.com', 'Kiw73', 'Kiwale', 'Kiwale Park Vista', 18.6489, 73.7456, 4900000, 9200000],
            74 => ['Casagrand Builder Pune', 'Casagrand Builder Private Ltd', 'sales.pune@casagrand.co.in', 'Casa74', 'Kharadi', 'Casagrand Woodside', 18.5589, 73.9612, 9500000, 19500000],
            75 => ['Shah Prathamesh Constructions', 'Shah Prathamesh Group', 'sales@shahprathamesh.com', 'Shah75', 'Bibwewadi', 'Prathamesh Towers', 18.4812, 73.8645, 9100000, 17000000],
            76 => ['Rajwada Developer', 'Rajwada Housing Corporate', 'sales@rajwada.com', 'Raj76', 'Undri', 'Rajwada Royal', 18.4512, 73.9145, 5000000, 9600000],
            77 => ['Mid Town Enterprise', 'Mid Town Infrastructure', 'sales@midtown.com', 'Mid77', 'Camp', 'MidTown Heritage', 18.5289, 73.8712, 12000000, 26000000],
            78 => ['Stellar Properties', 'Stellar Housing Group', 'sales@stellar.com', 'Stel78', 'Wagholi', 'Stellar Spaces', 18.5812, 73.9689, 5700000, 11000000],
            79 => ['Shitole Properties', 'Shitole Infrastructure', 'info@shitole.com', 'Shit79', 'Sangvi', 'Shitole Empire', 18.5712, 73.8112, 6300000, 12500000],
            80 => ['Suratwwala Business Group', 'Suratwwala Business Group Ltd', 'sales@suratwwala.co.in', 'Sur80', 'Hinjewadi', 'Suratwwala Business Park', 18.5912, 73.7412, 8500000, 25000000],
            81 => ['Lifecraft Realty', 'Lifecraft Real Estate Project', 'sales@lifecraft.in', 'Life81', 'Balewadi', 'Lifecraft Elementia', 18.5812, 73.7745, 8200000, 15500000],
            82 => ['Shree Umiya Buildcon', 'Umiya Buildcon Group', 'sales@umiya.com', 'Umi82', 'Dhanori', 'Umiya Oasis', 18.5945, 73.8898, 5200000, 9800000],
            83 => ['Mahalaxmi Construction', 'Mahalaxmi Buildcon Enterprise', 'sales@mahalaxmi.com', 'Maha83', 'Manjri', 'Mahalaxmi Greens', 18.5289, 73.9812, 5400000, 10500000],
            84 => ['Mainland Spaces', 'Mainland Spaces Ltd', 'sales@mainland.com', 'Main84', 'NIBM', 'Mainland Highs', 18.4612, 73.9012, 9800000, 21000000],
            85 => ['Ranawat Realtors', 'Ranawat Housing Development', 'sales@ranawat.com', 'Ran85', 'Wagholi', 'Ranawat Excellence', 18.5812, 73.9745, 4500000, 8900000],
            86 => ['Choudhary & Sons', 'Choudhary Luxury Living', 'sales@choudhary.com', 'Chou86', 'Bavdhan', 'Choudhary Elanza Vistas', 18.5112, 73.7712, 19000000, 38000000],
            87 => ['Rajdeep Buildcon', 'Rajdeep Infrastructure & Contracting', 'sales@rajdeep.com', 'Rajd87', 'Chakan', 'Rajdeep Premium Enclave', 18.7589, 73.8498, 3800000, 7900000],
            88 => ['Dreams Development', 'Dreams Construction Company', 'info@dreamsdevelopment.com', 'Dream88', 'Hadapsar', 'Dreams Elina', 18.5112, 73.9289, 5900000, 11500000],
            89 => ['Shalaka Infra-tech', 'Shalaka Housing Infrastructure', 'sales@shalaka.com', 'Shal89', 'Sinhagad Road', 'Shalaka Heights', 18.4912, 73.8245, 7100000, 14000000],
            90 => ['Tricon Infra Buildtech', 'Tricon Construction Services', 'sales@tricon.com', 'Tri90', 'Hinjewadi', 'Tricon Corporate Heights', 18.5945, 73.7312, 7800000, 15000000],
            91 => ['Bhate And Raje Construction', 'Bhate And Raje Structural Works', 'sales@bhateraje.com', 'Bha91', 'Chakan', 'Bhate Industrial Quad', 18.7712, 73.8345, 4500000, 9500000],
            92 => ['Manav Group', 'Manav Housing Infrastructure', 'sales@manavgroup.com', 'Man92', 'Wakad', 'Manav Alura', 18.6089, 73.7612, 7200000, 14500000],
            93 => ['Siddheshwar Group', 'Siddheshwar Housing Enterprise', 'sales@siddheshwar.com', 'Sidd93', 'Moshi', 'Siddheshwar Angan', 18.6712, 73.8589, 4100000, 8000000],
            94 => ['Silver Group', 'Silver Housing Builders', 'sales@silvergroup.com', 'Sil94', 'Wakad', 'Silver Estate', 18.6145, 73.7589, 7600000, 15000000],
            95 => ['Metropolitan Properties', 'Metropolitan Real Estate Developers', 'sales@metropolitan.com', 'Met95', 'Baner', 'Metropolitan Baner Hub', 18.5612, 73.7812, 13500000, 31000000],
            96 => ['Sanskriti Group', 'Sanskriti Landmarks', 'sales@sanskriti.com', 'Sans96', 'Dhanori', 'Sanskriti Vihar', 18.5989, 73.9012, 4900000, 9300000],
            97 => ['Gini Constructions', 'Gini Construction Pvt Ltd', 'sales@giniconstructions.com', 'Gini97', 'Balewadi', 'Gini Viviana', 18.5789, 73.7645, 8300000, 16000000],
            98 => ['Solitaire Group', 'Solitaire Real Estate Corp', 'sales@solitaire.co.in', 'Sol98', 'Bibwewadi', 'Solitaire World', 18.4745, 73.8589, 14500000, 45000000],
            99 => ['Kanakia Spaces Pune', 'Kanakia Group Division', 'sales@kanakia.com', 'Kan99', 'Hinjewadi', 'Kanakia Spaces Pune', 18.5912, 73.7389, 8500000, 18500000],
            100 => ['Rohan Madhuban Ventures', 'Rohan Group Sub-Division', 'sales.madhuban@rohan.com', 'Roh100', 'Bavdhan', 'Rohan Madhuban', 18.5145, 73.7689, 9900000, 21000000],
        ];

        foreach ($buildersDataPart2 as $index => $data) {
            $builder = Builder::firstOrCreate(
                ['email' => $data[2]],
                [
                    'name' => $data[0], 'company_name' => $data[1], 'password' => Hash::make($data[3] . '2026'),
                    'phone' => '020' . (40000000 + $index), 'city' => 'Pune', 'cities_operating' => 'Pune',
                    'established_year' => '2008', 'is_verified' => true, 'total_delivered_projects' => 20, 'rating' => 4.2,
                    'description' => $data[0] . ' project line engineered safely within active Pune growth micro-markets.', 'status' => 'active',
                ]
            );

            BuilderProject::firstOrCreate(
                ['builder_id' => $builder->id, 'title' => $data[5]],
                [
                    'builder_id' => $builder->id, 'project_type' => 'Residential', 'status' => 'Under Construction',
                    'description' => $data[5] . ' delivers architectural precision, optimized floor dynamics, and excellent transit integration.',
                    'address' => $data[4] . ' Bypass Corridor Area, Pune, Maharashtra', 'city' => 'Pune', 'state' => 'Maharashtra',
                    'latitude' => $data[6], 'longitude' => $data[7], 'total_units' => 400, 'available_units' => 150,
                    'price_from' => $data[8], 'price_to' => $data[9], 'possession_date' => '2029-03-31', 'total_towers' => 3,
                    'floors_per_tower' => '22', 'is_featured' => false, 'views_count' => 640, 'leads_count' => 0,
                    'nearby_schools' => 'Orchids The International School (3.2 km)', 'nearby_hospitals' => 'Noble Hospital (4.5 km)',
                    'metro_distance' => 'Accessible via local smart transport networks', 'connectivity_score' => '8',
                ]
            );
        }

        // Standard dynamic attachment logic for amenities
        $stdAmenities = Amenity::whereIn('name', ['Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse', 'Children\'s Play Area', 'Power Backup', 'Covered Parking'])->pluck('id')->toArray();
        BuilderProject::where('city', 'Pune')->get()->each(fn($p) => !empty($stdAmenities) && $p->amenityItems()->syncWithoutDetaching($stdAmenities));

        $this->command->info('✅ Batch 2/2 complete: 100/100 Pune Builders initialized successfully.');
    }
}