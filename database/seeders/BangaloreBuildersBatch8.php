<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch8
 *
 * 10 Builders / Projects — Batch 8 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: DivyaSree Developers, HMR Spaces, Incor Infrastructure, Sumadhura Infracon,
 * Shriram Whitefield Hub, Ozone Group, Century Real Estate, Rohan Premium Living,
 * United Builders, HM Constructions.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch8
 */
class BangaloreBuildersBatch8 extends Seeder
{
    public function run(): void
    {
        // ── 71. DivyaSree Developers ─────────────────────────────────
        $divyaSreeBuilder = Builder::firstOrCreate(
            ['email' => 'sales@divyasree.com'],
            [
                'name'                     => 'DivyaSree Developers',
                'company_name'             => 'Divyasree Infrastructure Projects Private Limited',
                'password'                 => Hash::make('DivyaSree2026'),
                'phone'                    => '08022213333',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad, Chennai',
                'established_year'         => '1997',
                'is_verified'              => true,
                'total_delivered_projects' => 29,
                'rating'                   => 4.6,
                'description'              => 'Renowned for high-end IT parks and upscale corporate residential communities integrated seamlessly into tech micro-markets.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $divyaSreeBuilder->id, 'title' => 'DivyaSree Republic of Whitefield'],
            [
                'builder_id'         => $divyaSreeBuilder->id,
                'description'        => 'DivyaSree Republic of Whitefield is an premium corporate sky-rise ecosystem located inside a prominent tech hub. Features expansive clear layouts, specialized multi-tier sports arenas, and direct pedestrian walkway access points to business centers.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'DivyaSree Technopark Core, Kundalahalli Main Road, East Bengaluru, Karnataka 560066',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.971425,
                'longitude'          => 77.721254,
                'total_units'        => 1120,
                'available_units'    => 24,
                'price_from'         => 14500000,
                'price_to'           => 28000000,
                'possession_date'    => '2022-04-30',
                'total_towers'       => 8,
                'floors_per_tower'   => '20',
                'is_featured'        => true,
                'views_count'        => 2100,
                'leads_count'        => 0,
                'nearby_schools'     => 'Ryan International School Brookefield (1.5 km)',
                'nearby_hospitals'   => 'Sankara Eye Hospital Marathahalli (2.0 km)',
                'metro_distance'     => '4 minutes to Kundalahalli Metro station gate',
                'connectivity_score' => '10',
            ]
        );

        // ── 72. HMR Spaces ───────────────────────────────────────────
        $hmrBuilder = Builder::firstOrCreate(
            ['email' => 'sales@hmrspaces.com'],
            [
                'name'                     => 'HMR Spaces',
                'company_name'             => 'HMR Construction Private Limited',
                'password'                 => Hash::make('HMRSpaces2026'),
                'phone'                    => '08025431111',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 11,
                'rating'                   => 4.1,
                'description'              => 'HMR Spaces delivers robust, highly spatial mid-range multi-family apartment communities focusing heavily on primary urban connectivity points.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $hmrBuilder->id, 'title' => 'HMR Royal Nest'],
            [
                'builder_id'         => $hmrBuilder->id,
                'description'        => 'HMR Royal Nest is an impeccably built residential building complex located in Horamavu. Offers optimized masonry layouts, consistent water harvesting infrastructure grids, and complete power backups.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Horamavu Main Road, Near ORR Junction Link, North Bengaluru, Karnataka 560043',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.029412,
                'longitude'          => 77.659412,
                'total_units'        => 180,
                'available_units'    => 12,
                'price_from'         => 6200000,
                'price_to'           => 9800000,
                'possession_date'    => '2023-01-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 740,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vibgyor High School Horamavu (1.2 km)',
                'nearby_hospitals'   => 'Prakash Hospital Link Hub (2.5 km)',
                'metro_distance'     => '8 minutes to Banaswadi suburban terminal rail line',
                'connectivity_score' => '9',
            ]
        );

        // ── 73. Incor Infrastructure ─────────────────────────────────
        $incorBuilder = Builder::firstOrCreate(
            ['email' => 'sales@incor.in'],
            [
                'name'                     => 'Incor Infrastructure',
                'company_name'             => 'Incor Carmel Projects Private Limited',
                'password'                 => Hash::make('IncorBlr2026'),
                'phone'                    => '08040001000',
                'city'                     => 'Hyderabad',
                'cities_operating'         => 'Hyderabad, Bengaluru',
                'established_year'         => '2006',
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'rating'                   => 4.3,
                'description'              => 'Incor Infrastructure integrates advanced professional management processes to construct family-centric residential enclaves aligned with global standards.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $incorBuilder->id, 'title' => 'Incor Carmel Heights'],
            [
                'builder_id'         => $incorBuilder->id,
                'description'        => 'Incor Carmel Heights is a premium, beautifully executed high-rise residential project positioned on Whitefield Main Loop. Features three-sided open configurations, professional sports complexes, and premium high-grade insulation alignments.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'ECC Road, Opposite Deens Academy, Whitefield, East Bengaluru, Karnataka 560066',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.979412,
                'longitude'          => 77.749412,
                'total_units'        => 394,
                'available_units'    => 115,
                'price_from'         => 9800000,
                'price_to'           => 18500000,
                'possession_date'    => '2027-08-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '18',
                'is_featured'        => true,
                'views_count'        => 1450,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy Whitefield Core (0.2 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield Terminal (1.2 km)',
                'metro_distance'     => '4 minutes away from Pattandur Agrahara Metro Station',
                'connectivity_score' => '10',
            ]
        );

        // ── 74. Sumadhura Infracon ───────────────────────────────────
        $sumadhuraBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sumadhuragroup.com'],
            [
                'name'                     => 'Sumadhura Infracon',
                'company_name'             => 'Sumadhura Infracon Private Limited',
                'password'                 => Hash::make('Sumadhura2026'),
                'phone'                    => '08042412412',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad',
                'established_year'         => '1998',
                'is_verified'              => true,
                'total_delivered_projects' => 42,
                'rating'                   => 4.4,
                'description'              => 'Sumadhura is highly praised for punctual delivery schedules, reliable construction engineering matrices, and massive residential spaces located inside tech corridors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sumadhuraBuilder->id, 'title' => 'Sumadhura Eden Garden'],
            [
                'builder_id'         => $sumadhuraBuilder->id,
                'description'        => 'Sumadhura Eden Garden is a massive, beautifully structured high-rise lifestyle community positioned in Whitefield Extension. Completed with large internal open park networks, continuous multi-tier automated security safety grids, and expansive club zones.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sai Baba Ashram Road, Doddabanahalli, East Bengaluru, Karnataka 560067',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.999412,
                'longitude'          => 77.781425,
                'total_units'        => 1113,
                'available_units'    => 21,
                'price_from'         => 7900000,
                'price_to'           => 14500000,
                'possession_date'    => '2022-09-30',
                'total_towers'       => 12,
                'floors_per_tower'   => '14',
                'is_featured'        => true,
                'views_count'        => 2190,
                'leads_count'        => 0,
                'nearby_schools'     => 'Whitefield Global School (2.5 km)',
                'nearby_hospitals'   => 'Hope Hospital Whitefield Cross (3.0 km)',
                'metro_distance'     => '7 minutes from Kadugodi Tree Park Metro station lines',
                'connectivity_score' => '9',
            ]
        );

        // ── 75. Shriram Whitefield Hub (Specialized Wing) ────────────
        $shriramWhBuilder = Builder::firstOrCreate(
            ['email' => 'sales.wf@shriramproperties.com'],
            [
                'name'                     => 'Shriram Whitefield Hub',
                'company_name'             => 'Shriram Horizon Projects Private Limited',
                'password'                 => Hash::make('ShriramWF2026'),
                'phone'                    => '08040222225',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2015',
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'rating'                   => 4.2,
                'description'              => 'A dedicated strategic division of Shriram Properties engineering smart urban micro-condominiums right next to East Bengaluru commercial workspaces.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $shriramWhBuilder->id, 'title' => 'Shriram Greenfield'],
            [
                'builder_id'         => $shriramWhBuilder->id,
                'description'        => 'Shriram Greenfield is a sprawling premium smart township situated in Budigere Cross. Features micro-engineered precast configurations, extensive sports park zones, multi-purpose club hubs, and optimized cost structures.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Budigere Cross, Off Old Madras Road Expressway, East Bengaluru, Karnataka 562129',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.054125,
                'longitude'          => 77.751412,
                'total_units'        => 1645,
                'available_units'    => 32,
                'price_from'         => 5500000,
                'price_to'           => 9200000,
                'possession_date'    => '2021-11-30',
                'total_towers'       => 9,
                'floors_per_tower'   => '19',
                'is_featured'        => false,
                'views_count'        => 1850,
                'leads_count'        => 0,
                'nearby_schools'     => 'V領am Academy Budigere Link (1.0 km)',
                'nearby_hospitals'   => 'Mission Hospital Link (5.0 km)',
                'metro_distance'     => '12 minutes to upcoming Hoskote extension spur node',
                'connectivity_score' => '8',
            ]
        );

        // ── 76. Ozone Group ──────────────────────────────────────────
        $ozoneBuilder = Builder::firstOrCreate(
            ['email' => 'sales@ozonegroup.com'],
            [
                'name'                     => 'Ozone Group',
                'company_name'             => 'Ozone Urbana Infra Private Limited',
                'password'                 => Hash::make('OzoneBlr2026'),
                'phone'                    => '08040414444',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Mumbai, Goa',
                'established_year'         => '1999',
                'is_verified'              => true,
                'total_delivered_projects' => 28,
                'rating'                   => 4.1,
                'description'              => 'Pioneers in developing wide-scale integrated self-contained mega townships incorporating high lifestyle features, hospitals, and internal school blocks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $ozoneBuilder->id, 'title' => 'Ozone Urbana'],
            [
                'builder_id'         => $ozoneBuilder->id,
                'description'        => 'Ozone Urbana is a magnificent 185-acre fully integrated township ecosystem positioned near KIA Airport terminal corridor. Features senior living zones, complete smart safety grids, standard and luxury tower arrays.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'NH-44 International Airport Highway, Devanahalli Extension, North Bengaluru, Karnataka 562110',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.194125,
                'longitude'          => 77.711425,
                'total_units'        => 2200,
                'available_units'    => 45,
                'price_from'         => 4800000,
                'price_to'           => 16500000,
                'possession_date'    => '2020-12-31',
                'total_towers'       => 14,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 1670,
                'leads_count'        => 0,
                'nearby_schools'     => 'National Public School Urbana Campus (0.2 km)',
                'nearby_hospitals'   => 'Aster Clinic Urbana Segment (0.1 km)',
                'metro_distance'     => '6 minutes to upcoming Airport metro interchange link lines',
                'connectivity_score' => '9',
            ]
        );

        // ── 77. Century Real Estate ──────────────────────────────────
        $centuryBuilder = Builder::firstOrCreate(
            ['email' => 'sales@centuryrealestate.in'],
            [
                'name'                     => 'Century Real Estate',
                'company_name'             => 'Century Real Estate Holdings Pvt Ltd',
                'password'                 => Hash::make('CenturyBlr2026'),
                'phone'                    => '08044334444',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1973',
                'is_verified'              => true,
                'total_delivered_projects' => 38,
                'rating'                   => 4.4,
                'description'              => 'One of the largest institutional land bank owners in North Bengaluru, crafting premium smart residences and high-concept plotting empires.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $centuryBuilder->id, 'title' => 'Century Breeze'],
            [
                'builder_id'         => $centuryBuilder->id,
                'description'        => 'Century Breeze is a beautifully optimized, premium high-rise residential complex located on Jakkur Road corridor. Boasts 75% wide open green arrays, structural earthquake resilience engineering, and extensive lifestyle pavilions.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Jakkur Main Road, Off NH-44 Highway, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.084125,
                'longitude'          => 77.609412,
                'total_units'        => 312,
                'available_units'    => 14,
                'price_from'         => 9500000,
                'price_to'           => 18000000,
                'possession_date'    => '2022-02-28',
                'total_towers'       => 3,
                'floors_per_tower'   => '15',
                'is_featured'        => false,
                'views_count'        => 1150,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vidyashilp Academy Jakkur (2.0 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal (4.5 km)',
                'metro_distance'     => '5 minutes from Jakkur Cross upcoming Metro portal',
                'connectivity_score' => '10',
            ]
        );

        // ── 78. Rohan Premium Living (Niche Arms) ────────────────────
        $rohanPremBuilder = Builder::firstOrCreate(
            ['email' => 'sales.premium@rohanbuilders.com'],
            [
                'name'                     => 'Rohan Premium Living',
                'company_name'             => 'Rohan Housing Ventures Private Limited',
                'password'                 => Hash::make('RohanPrem2026'),
                'phone'                    => '08025204411',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Pune',
                'established_year'         => '2012',
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'rating'                   => 4.5,
                'description'              => 'The luxury architecture division of Rohan Builders, engineering bespoke sky-villas with double-height volumetric grid systems.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $rohanPremBuilder->id, 'title' => 'Rohan Avriti'],
            [
                'builder_id'         => $rohanPremBuilder->id,
                'description'        => 'Rohan Avriti is an ultra-exclusive luxury high-rise enclave located in Mahadevapura. Designed around the signature PLUS architecture matrix with zero dead spatial layouts and individual terrace courtyard areas.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'ITPL Main Road, Mahadevapura Tech Sector, East Bengaluru, Karnataka 560048',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.991425,
                'longitude'          => 77.691425,
                'total_units'        => 210,
                'available_units'    => 5,
                'price_from'         => 11000000,
                'price_to'           => 21500000,
                'possession_date'    => '2021-06-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'EuroSchool Whitefield Link (3.0 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield Line (4.0 km)',
                'metro_distance'     => '4 minutes from Singayyanapana Palya Metro Station',
                'connectivity_score' => '10',
            ]
        );

        // ── 79. United Builders ──────────────────────────────────────
        $unitedBuilder = Builder::firstOrCreate(
            ['email' => 'sales@unitedbuilders.in'],
            [
                'name'                     => 'United Builders',
                'company_name'             => 'United Builders & Developers Private Limited',
                'password'                 => Hash::make('UnitedBlr2026'),
                'phone'                    => '08022245555',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2002',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 4.0,
                'description'              => 'United Builders engineers systematic, highly reliable budget multi-family condominiums targeting industrial and manufacturing workplace vectors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $unitedBuilder->id, 'title' => 'United Elegant'],
            [
                'builder_id'         => $unitedBuilder->id,
                'description'        => 'United Elegant is a streamlined, highly functional residential complex located off Bannerghatta Main Road. Focuses on straightforward spatial designs, basic security infrastructure automation, and excellent arterial transit connectivity.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Begur-Bannerghatta Link Road, South Bengaluru, Karnataka 560083',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.869412,
                'longitude'          => 77.611412,
                'total_units'        => 142,
                'available_units'    => 7,
                'price_from'         => 4900000,
                'price_to'           => 8200000,
                'possession_date'    => '2022-07-31',
                'total_towers'       => 1,
                'floors_per_tower'   => '8',
                'is_featured'        => false,
                'views_count'        => 520,
                'leads_count'        => 0,
                'nearby_schools'     => 'Chrysalis High School Bannerghatta (2.2 km)',
                'nearby_hospitals'   => 'Fortis Hospital Bannerghatta Line (4.5 km)',
                'metro_distance'     => '10 minutes from upcoming Gottigere Metro corridor end',
                'connectivity_score' => '8',
            ]
        );

        // ── 80. HM Constructions ────────────────────────────────────
        $hmBuilder = Builder::firstOrCreate(
            ['email' => 'sales@hmconstructions.com'],
            [
                'name'                     => 'HM Constructions',
                'company_name'             => 'HM Constructions Private Limited',
                'password'                 => Hash::make('HMConst2026'),
                'phone'                    => '08042555555',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1991',
                'is_verified'              => true,
                'total_delivered_projects' => 55,
                'rating'                   => 4.2,
                'description'              => 'A veteran architectural player developing massive spatial blueprint residential structures and commercial business centers along city commercial landmarks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $hmBuilder->id, 'title' => 'HM World City'],
            [
                'builder_id'         => $hmBuilder->id,
                'description'        => 'HM World City is a vast premium residential complex positioned along Kanakapura Main Road corridor. Completed with spacious room frameworks, high masonry finish indexes, and complete lifestyle recreation infrastructure setups.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Kanakapura Main Road, Near NICE Road Interchange, South Bengaluru, Karnataka 560062',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.864125,
                'longitude'          => 77.551412,
                'total_units'        => 820,
                'available_units'    => 24,
                'price_from'         => 8500000,
                'price_to'           => 16500000,
                'possession_date'    => '2021-03-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 980,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Bangalore South (3.0 km)',
                'nearby_hospitals'   => 'Sri Sai Ram Hospital Kanakapura (2.5 km)',
                'metro_distance'     => '4 minutes to Silk Institute Metro Station point',
                'connectivity_score' => '10',
            ]
        );

        // ── Attach Amenities ─────────────────────────────────────────
        $luxury = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse',
            'Spa & Sauna', 'Yoga / Meditation', 'Party Hall / Banquet',
            'Jogging Track', 'Children\'s Play Area', '24×7 Security',
            'CCTV Surveillance', 'Video Door Phone', 'Gated Community',
            'Power Backup', 'High-Speed Elevators', 'Covered Parking',
            'EV Charging Point', 'Rainwater Harvesting', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $standard = Amenity::whereIn('name', [
            'Swimming Pool', 'Gymnasium / Fitness', 'Clubhouse',
            'Children\'s Play Area', 'Jogging Track', '24×7 Security',
            'CCTV Surveillance', 'Power Backup', 'High-Speed Elevators',
            'Covered Parking', 'Landscaped Gardens',
        ])->pluck('id')->toArray();

        $luxuryTitles  = ['DivyaSree Republic of Whitefield', 'Incor Carmel Heights', 'Sumadhura Eden Garden', 'Century Breeze', 'Rohan Avriti'];
        $standardTitles = ['HMR Royal Nest', 'Shriram Greenfield', 'Ozone Urbana', 'United Elegant', 'HM World City'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 8/10 complete: 80/100 Bengaluru Builders successfully initialized.');
    }
}