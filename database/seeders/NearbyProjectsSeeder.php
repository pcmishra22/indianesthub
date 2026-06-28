<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * NearbyProjectsSeeder
 *
 * Seeds real residential projects located within ~1 km of
 * Srishti Avenue, Dhakoli, Zirakpur, Punjab 160104.
 *
 * Run with:
 *   php artisan db:seed --class=NearbyProjectsSeeder
 */
class NearbyProjectsSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. GVT Builders → Beliston Avenue
        // ─────────────────────────────────────────────
        $gvt = Builder::firstOrCreate(
            ['email' => 'gvt.builders@zirakpur.com'],
            [
                'name'                     => 'GVT Builders',
                'company_name'             => 'GVT Builders & Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9855573332',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Mohali, Chandigarh',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 3,
                'rating'                   => 3.8,
                'description'              => 'GVT Builders is a Zirakpur-based developer known for mid-segment residential apartments in the Dhakoli–Zirakpur corridor.',
                'status'                   => 'active',
            ]
        );

        $beliston = BuilderProject::firstOrCreate(
            ['builder_id' => $gvt->id, 'title' => 'Beliston Avenue'],
            [
                'builder_id'         => $gvt->id,
                'description'        => 'Beliston Avenue is a residential apartment project by GVT Builders situated opposite Delhi World Public School, Dhakoli. The project offers 2 & 3 BHK apartments with modern amenities at an affordable price point in the rapidly developing Zirakpur micro-market.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Opposite Delhi World Public School, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6426623,
                'longitude'          => 76.8454920,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 3500000,
                'price_to'           => 6500000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 71,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (opposite), Ryan International (2 km)',
                'nearby_hospitals'   => 'Paras Hospital Panchkula (3 km), Alchemist Hospital (4 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '6',
            ]
        );

        // ─────────────────────────────────────────────
        // 2. Palash Homes Developers → The Palash Homes
        // ─────────────────────────────────────────────
        $palash = Builder::firstOrCreate(
            ['email' => 'palash.homes@zirakpur.com'],
            [
                'name'                     => 'Palash Homes',
                'company_name'             => 'Palash Homes Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000001',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Dhakoli',
                'established_year'         => '2010',
                'is_verified'              => true,
                'total_delivered_projects' => 2,
                'rating'                   => 5.0,
                'description'              => 'Palash Homes Developers is a boutique real estate developer focused on budget-friendly residential projects in Dhakoli, Zirakpur. Known for cooperative staff and easy access.',
                'status'                   => 'active',
            ]
        );

        $palashHomes = BuilderProject::firstOrCreate(
            ['builder_id' => $palash->id, 'title' => 'The Palash Homes'],
            [
                'builder_id'         => $palash->id,
                'description'        => 'The Palash Homes is a well-connected residential project in Dhakoli, located near Delhi World Public School and Lake View Complex on Peer Muchalla Road. Offers budget-friendly 2 & 3 BHK apartments with excellent connectivity to Chandigarh, Panchkula and Mohali. Staff is cooperative and the location is easy to access.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'New Peer Muchalla Road, adjoining Lake View Complex, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6453574,
                'longitude'          => 76.8491033,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 3000000,
                'price_to'           => 5500000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 11,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (0.5 km), St. Soldier International (2 km)',
                'nearby_hospitals'   => 'Paras Hospital Panchkula (3 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '7',
            ]
        );

        // ─────────────────────────────────────────────
        // 3. Jubilant Group → Jubilant Residency
        // ─────────────────────────────────────────────
        $jubilant = Builder::firstOrCreate(
            ['email' => 'jubilant.group@zirakpur.com'],
            [
                'name'                     => 'Jubilant Group',
                'company_name'             => 'Jubilant Infrastructure Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000002',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Chandigarh Tricity',
                'established_year'         => '2008',
                'is_verified'              => true,
                'total_delivered_projects' => 2,
                'rating'                   => 5.0,
                'description'              => 'Jubilant Infrastructure Group is a Zirakpur-based developer delivering quality residential projects near the Chandigarh tricity region.',
                'status'                   => 'active',
            ]
        );

        $jubilantRes = BuilderProject::firstOrCreate(
            ['builder_id' => $jubilant->id, 'title' => 'Jubilant Residency'],
            [
                'builder_id'         => $jubilant->id,
                'description'        => 'Jubilant Residency is a residential project by the Jubilant Group located near Delhi World Public School, Zirakpur. Strategically positioned in the Dhakoli area with good connectivity to Chandigarh, Panchkula and Mohali via the Sanoli Road corridor.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Near Delhi World Public School, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6408540,
                'longitude'          => 76.8501070,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 3200000,
                'price_to'           => 6000000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 1,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (0.3 km)',
                'nearby_hospitals'   => 'Paras Hospital (3 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '7',
            ]
        );

        // ─────────────────────────────────────────────
        // 4. Hi Greens Developer → Hi Greens
        // ─────────────────────────────────────────────
        $hiGreenBuilder = Builder::firstOrCreate(
            ['email' => 'higreens.developer@zirakpur.com'],
            [
                'name'                     => 'Hi Greens Developer',
                'company_name'             => 'Hi Greens Developer Pvt. Ltd.',
                'password'                 => Hash::make('password'),
                'phone'                    => '7696700155',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Mohali',
                'established_year'         => '2012',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.0,
                'description'              => 'Hi Greens Developer is a mid-segment builder offering 3 & 3+1 BHK apartments with long balconies in the Kishanpura–Dhakoli belt of Zirakpur.',
                'status'                   => 'active',
            ]
        );

        $hiGreens = BuilderProject::firstOrCreate(
            ['builder_id' => $hiGreenBuilder->id, 'title' => 'Hi Greens'],
            [
                'builder_id'         => $hiGreenBuilder->id,
                'description'        => 'Hi Greens is a well-planned residential society on Sanoli Road, Kishanpura, Zirakpur. The project offers spacious 3 BHK and 3+1 BHK apartments with large balconies adjoining living areas and bedrooms. The society includes a gym, club house, and landscaped areas. Fixtures are of premium quality and the location provides easy connectivity to Chandigarh.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sanoli Road, Kishanpura, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6431875,
                'longitude'          => 76.8546875,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 4500000,
                'price_to'           => 7000000,
                'possession_date'    => '2023-12-31',
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 99,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (0.8 km), St. Soldier (1.5 km)',
                'nearby_hospitals'   => 'Paras Hospital Panchkula (3 km), Civil Hospital Zirakpur (2 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '7',
            ]
        );

        // ─────────────────────────────────────────────
        // 5. Motia Group → Motia Huys
        // ─────────────────────────────────────────────
        $motia = Builder::firstOrCreate(
            ['email' => 'motia.group@zirakpur.com'],
            [
                'name'                     => 'Motia Group',
                'company_name'             => 'Motia Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9875915774',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Panchkula, Mohali, Chandigarh',
                'established_year'         => '2000',
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'rating'                   => 4.3,
                'description'              => 'Motia Group is one of the well-known builders in the Chandigarh tricity region. Known for community-focused residential projects with good amenities, CCTV, parks and maintenance standards.',
                'website'                  => 'https://www.motiagroup.com',
                'status'                   => 'active',
            ]
        );

        $motiaHuys = BuilderProject::firstOrCreate(
            ['builder_id' => $motia->id, 'title' => 'Motia Huys'],
            [
                'builder_id'         => $motia->id,
                'description'        => 'Motia Huys is a well-regarded residential complex on Peer Muchalla Road, Dhakoli. The project offers independent 2 and 3 BHK floors in a gated community with 24×7 security, CCTV, children\'s park, and well-maintained common areas. Proximity to good schools and hospitals in Panchkula Sector 20 makes it ideal for families.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Peer Muchalla Road, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6549523,
                'longitude'          => 76.8519997,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 3800000,
                'price_to'           => 7500000,
                'possession_date'    => '2022-06-30',
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 152,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (1 km), Ryan International (2 km)',
                'nearby_hospitals'   => 'Paras Hospital Panchkula Sector 20 (2 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '8',
            ]
        );

        // ─────────────────────────────────────────────
        // 6. Skytouch Builders → Skytouch
        // ─────────────────────────────────────────────
        $skytouch = Builder::firstOrCreate(
            ['email' => 'skytouch.builders@peermuchalla.com'],
            [
                'name'                     => 'Skytouch Builders',
                'company_name'             => 'Skytouch Builders & Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9516795267',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Panchkula',
                'established_year'         => '2015',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 5.0,
                'description'              => 'Skytouch Builders is an emerging premium developer in the Peer Muchalla area. Their projects are known for luxurious concepts, modern elevations, and spacious room layouts.',
                'status'                   => 'active',
            ]
        );

        $skytouchProj = BuilderProject::firstOrCreate(
            ['builder_id' => $skytouch->id, 'title' => 'Skytouch'],
            [
                'builder_id'         => $skytouch->id,
                'description'        => 'Skytouch is an upcoming luxury residential project by Skytouch Builders, adjacent to Panchkula Shopping Complex on Peer Muchalla Road, Dhakoli. The project offers premium 4 BHK and 5 BHK flats with a luxurious concept, modern elevation and spacious, airy rooms with all facilities — giving the feel of a five-star environment.',
                'project_type'       => 'Residential',
                'status'             => 'Upcoming',
                'address'            => 'Adjoining Panchkula Shopping Complex, Peer Muchalla Road, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6536058,
                'longitude'          => 76.8506009,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 8000000,
                'price_to'           => 15000000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => true,
                'views_count'        => 17,
                'leads_count'        => 0,
                'nearby_schools'     => 'Panchkula Schools Sector 20 (1 km)',
                'nearby_hospitals'   => 'Paras Hospital Panchkula (2 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '8',
            ]
        );

        // ─────────────────────────────────────────────
        // 7. Merlionn → Merlionn Park
        // ─────────────────────────────────────────────
        $merlionn = Builder::firstOrCreate(
            ['email' => 'info@merlionn.com'],
            [
                'name'                     => 'Merlionn Developers',
                'company_name'             => 'Merlionn Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000003',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Dhakoli, Mohali',
                'established_year'         => '2014',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
'rating'                   => 0,
                'description'              => 'Merlionn Developers is a real estate developer based in Dhakoli, Zirakpur focused on delivering quality residential apartments in the Chandigarh tricity area.',
                'website'                  => 'https://merlionn.com',
                'status'                   => 'active',
            ]
        );

        $merlionnPark = BuilderProject::firstOrCreate(
            ['builder_id' => $merlionn->id, 'title' => 'Merlionn Park'],
            [
                'builder_id'         => $merlionn->id,
                'description'        => 'Merlionn Park is a residential project by Merlionn Developers located in Dhakoli, Zirakpur near Motia Pacific Centre. The project offers well-designed apartments in a prime location with easy connectivity to Chandigarh, Panchkula and Mohali.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Near Motia Pacific Centre, SCO-314, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6513958,
                'longitude'          => 76.8531518,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 3500000,
                'price_to'           => 7000000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 0,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (1 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (2 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '7',
            ]
        );

        // ─────────────────────────────────────────────
        // 8. Bristol Homes → Bristol Homes
        // ─────────────────────────────────────────────
        $bristolBuilder = Builder::firstOrCreate(
            ['email' => 'info@bristolhomes.in'],
            [
                'name'                     => 'Bristol Homes',
                'company_name'             => 'Bristol Homes Construction Company',
                'password'                 => Hash::make('password'),
                'phone'                    => '9115670007',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Panchkula, Mohali',
                'established_year'         => '2018',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 5.0,
                'description'              => 'Bristol Homes Construction Company is a premium residential developer in Peer Muchalla, Dhakoli. Known for sophisticated interiors, modern elevations, and a focus on quality construction with transparent dealing.',
                'status'                   => 'active',
            ]
        );

        $bristolHomes = BuilderProject::firstOrCreate(
            ['builder_id' => $bristolBuilder->id, 'title' => 'Bristol Homes'],
            [
                'builder_id'         => $bristolBuilder->id,
                'description'        => 'Bristol Homes is a premium residential project by Bristol Homes Construction Company on SCO 5, Peer Muchalla Road, Dhakoli. The project blends class and refinement with a sophisticated neutral-toned palette, modern architecture, and high-end detailing. Offering a luxurious living experience with attention to detail at every stage of construction.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'SCO 5, Peer Muchalla Road, Dhakoli, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6545813,
                'longitude'          => 76.8518877,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 6000000,
                'price_to'           => 12000000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => true,
                'views_count'        => 68,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (1 km), Panchkula Schools (2 km)',
                'nearby_hospitals'   => 'Paras Hospital Panchkula (2 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '8',
            ]
        );

        // ─────────────────────────────────────────────
        // 9. SBP Group → SBP Olympia
        // ─────────────────────────────────────────────
        $sbp = Builder::firstOrCreate(
            ['email' => 'sbpgroup@zirakpur.com'],
            [
                'name'                     => 'SBP Group',
                'company_name'             => 'SBP Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000004',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Mohali, Chandigarh',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'rating'                   => 4.6,
                'description'              => 'SBP Group is an established real estate developer in the Chandigarh tricity region offering sport-inspired residential communities with positive investment returns.',
                'status'                   => 'active',
            ]
        );

        $sbpOlympia = BuilderProject::firstOrCreate(
            ['builder_id' => $sbp->id, 'title' => 'SBP Olympia'],
            [
                'builder_id'         => $sbp->id,
                'description'        => 'SBP Olympia is a Sport Life Residential project by SBP Group located in Dhakoli, Zirakpur. Inspired by an active lifestyle, the project offers a strong investment proposition with expected positive returns. Construction is on a fast track and the project is expected to be a landmark in this rapidly developing area.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Dhakoli, Daffarpur, Zirakpur',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6474705,
                'longitude'          => 76.8544384,
                'total_units'        => null,
                'available_units'    => null,
                'price_from'         => 4000000,
                'price_to'           => 8000000,
                'possession_date'    => null,
                'total_towers'       => null,
                'floors_per_tower'   => null,
                'is_featured'        => false,
                'views_count'        => 25,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi World Public School (0.5 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (2 km)',
                'metro_distance'     => '~18 km from Chandigarh Railway Station',
                'connectivity_score' => '7',
            ]
        );

        // ─────────────────────────────────────────────
        // Attach standard amenities to all projects
        // ─────────────────────────────────────────────
        $standardAmenities = Amenity::whereIn('name', [
            'Swimming Pool',
            'Gymnasium / Fitness',
            'Clubhouse',
            'Children\'s Play Area',
            'Jogging Track',
            '24×7 Security',
            'CCTV Surveillance',
            'Power Backup',
            'High-Speed Elevators',
            'Covered Parking',
            'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $premiumAmenities = Amenity::whereIn('name', [
            'Swimming Pool',
            'Gymnasium / Fitness',
            'Clubhouse',
            'Spa & Sauna',
            'Children\'s Play Area',
            'Jogging Track',
            '24×7 Security',
            'CCTV Surveillance',
            'Video Door Phone',
            'Gated Community',
            'Power Backup',
            'High-Speed Elevators',
            'Covered Parking',
            'Rainwater Harvesting',
            'Landscaped Gardens',
        ])->pluck('id')->toArray();

        foreach ([$skytouchProj, $bristolHomes] as $proj) {
            if (! empty($premiumAmenities)) {
                $proj->amenityItems()->sync($premiumAmenities);
            }
        }

        foreach ([$beliston, $palashHomes, $jubilantRes, $hiGreens, $motiaHuys, $merlionnPark, $sbpOlympia] as $proj) {
            if (! empty($standardAmenities)) {
                $proj->amenityItems()->sync($standardAmenities);
            }
        }

        $this->command->info('✅ Nearby Projects seeded: 9 builders, 9 projects near Srishti Avenue, Dhakoli, Zirakpur.');
        $this->command->info('   Projects: Beliston Avenue, The Palash Homes, Jubilant Residency, Hi Greens,');
        $this->command->info('             Motia Huys, Skytouch, Merlionn Park, Bristol Homes, SBP Olympia');
    }
}
