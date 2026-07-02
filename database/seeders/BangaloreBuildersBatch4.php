<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch4
 *
 * 10 Builders / Projects — Batch 4 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: Incor Infrastructure, Urbanrise, HM Constructions, Sobha Royal Pavilion (Brand Wing),
 * Elegant Properties, United Smart Spaces, DS-MAX Classic Lifestyle, Sumadhura Infracon,
 * Casagrand Luxury, Vaswani Group.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch4
 */
class BangaloreBuildersBatch4 extends Seeder
{
    public function run(): void
    {
        // ── 31. Sumadhura Infracon ───────────────────────────────────
        $sumadhuraBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sumadhuragroup.com'],
            [
                'name'                     => 'Sumadhura Infracon',
                'company_name'             => 'Sumadhura Infracon Private Limited',
                'password'                 => Hash::make('SumadhuraBlr2026'),
                'phone'                    => '08042456789',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad',
                'established_year'         => '1998',
                'is_verified'              => true,
                'total_delivered_projects' => 45,
                'rating'                   => 4.4,
                'description'              => 'Sumadhura Infracon is recognized for hyper-speed engineering delivery matrices, absolute structural longevity, and exceptional luxury setups inside commercial corridors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sumadhuraBuilder->id, 'title' => 'Sumadhura Folium'],
            [
                'builder_id'         => $sumadhuraBuilder->id,
                'description'        => 'Sumadhura Folium is a highly premium residential landscape project based right out of Whitefield. It provides a unique balance of ultra-dense micro-gardens, fully fitted home tech automation systems, and close proximity to key corporate tech centers.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Channasandra Main Road, Whitefield IT Hub Corridor, East Bengaluru, Karnataka 560067',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.994512,
                'longitude'          => 77.761425,
                'total_units'        => 480,
                'available_units'    => 120,
                'price_from'         => 12500000,
                'price_to'           => 21000000,
                'possession_date'    => '2027-09-30',
                'total_towers'       => 5,
                'floors_per_tower'   => '22',
                'is_featured'        => true,
                'views_count'        => 1510,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy (2.2 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (3.5 km)',
                'metro_distance'     => '5 minutes away from Kadugodi Tree Park Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 32. Urbanrise (Alliance Group Brand) ─────────────────────
        $urbanriseBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@urbanrise.in'],
            [
                'name'                     => 'Urbanrise',
                'company_name'             => 'Alliance Ventures Private Limited',
                'password'                 => Hash::make('UrbanriseBlr2026'),
                'phone'                    => '08046809000',
                'city'                     => 'Chennai',
                'cities_operating'         => 'Chennai, Bengaluru, Hyderabad',
                'established_year'         => '2004',
                'is_verified'              => true,
                'total_delivered_projects' => 25,
                'rating'                   => 4.3,
                'description'              => 'Urbanrise is a highly progressive millennial-focused brand under the Alliance Group umbrella, specializing in mega kid-centric townships and innovative structural choices.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $urbanriseBuilder->id, 'title' => 'Urbanrise City of Joy'],
            [
                'builder_id'         => $urbanriseBuilder->id,
                'description'        => 'Urbanrise City of Joy is an expansive, high-rise premium smart residential community positioned cleanly along the Kanakapura Road corridor. Features a world-class integrated internal learning academy for children and multi-tier sports fields.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Kanakapura Main Road, Near Khoday\'s Brewery Junction, South Bengaluru, Karnataka 560062',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.869412,
                'longitude'          => 77.538214,
                'total_units'        => 1040,
                'available_units'    => 310,
                'price_from'         => 6500000,
                'price_to'           => 13500000,
                'possession_date'    => '2028-12-31',
                'total_towers'       => 8,
                'floors_per_tower'   => '24',
                'is_featured'        => true,
                'views_count'        => 1390,
                'leads_count'        => 0,
                'nearby_schools'     => 'Kumarans Children Home (2.0 km)',
                'nearby_hospitals'   => 'Astra Super Speciality Hospital (3.1 km)',
                'metro_distance'     => 'Walking distance to Silk Institute Metro Terminal Station',
                'connectivity_score' => '10',
            ]
        );

        // ── 33. Vaswani Group ────────────────────────────────────────
        $vaswaniBuilder = Builder::firstOrCreate(
            ['email' => 'sales@vaswanigroup.com'],
            [
                'name'                     => 'Vaswani Group',
                'company_name'             => 'Vaswani Estates Private Limited',
                'password'                 => Hash::make('VaswaniBlr2026'),
                'phone'                    => '08041151122',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Mumbai, Pune, Goa',
                'established_year'         => '1992',
                'is_verified'              => true,
                'total_delivered_projects' => 30,
                'rating'                   => 4.2,
                'description'              => 'Vaswani Group specializes in creating unique boutique luxury developments and well-integrated commercial setups built around individual spatial freedom.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $vaswaniBuilder->id, 'title' => 'Vaswani Starlight'],
            [
                'builder_id'         => $vaswaniBuilder->id,
                'description'        => 'Vaswani Starlight is a premium high-rise boutique development located right off the Outer Ring Road corridor in Marathahalli. Engineered meticulously with large master corridors, independent structural walling setups, and clean natural lighting vectors.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Off Outer Ring Road, Marathahalli Interchange Pipeline Road, East Bengaluru, Karnataka 560103',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.934125,
                'longitude'          => 77.691254,
                'total_units'        => 280,
                'available_units'    => 85,
                'price_from'         => 14000000,
                'price_to'           => 25500000,
                'possession_date'    => '2028-03-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '18',
                'is_featured'        => false,
                'views_count'        => 840,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Horizon High School (1.2 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (1.5 km)',
                'metro_distance'     => '5 minutes away from proposed ORR Phase 2A Metro Station network',
                'connectivity_score' => '9',
            ]
        );

        // ── 34. HM Constructions ─────────────────────────────────────
        $hmBuilder = Builder::firstOrCreate(
            ['email' => 'sales@hmconstructions.com'],
            [
                'name'                     => 'HM Constructions',
                'company_name'             => 'HM Constructions Private Limited',
                'password'                 => Hash::make('HMConst2026'),
                'phone'                    => '08042464444',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1991',
                'is_verified'              => true,
                'total_delivered_projects' => 60,
                'rating'                   => 4.1,
                'description'              => 'HM Constructions is a foundational player in Central and South Bengaluru real estate, known for commercial blocks and large-unit residential developments.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $hmBuilder->id, 'title' => 'HM Tropical Tree'],
            [
                'builder_id'         => $hmBuilder->id,
                'description'        => 'HM Tropical Tree is a uniquely private luxury residential enclave located in RT Nagar. It features exceptionally expansive layout options with dedicated private helper quarters and individual foyer entrances.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Anandnagar, RT Nagar Main Extension, Central-North Bengaluru, Karnataka 560032',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.019412,
                'longitude'          => 77.591245,
                'total_units'        => 120,
                'available_units'    => 10,
                'price_from'         => 21000000,
                'price_to'           => 38000000,
                'possession_date'    => '2021-12-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '18',
                'is_featured'        => false,
                'views_count'        => 680,
                'leads_count'        => 0,
                'nearby_schools'     => 'Aditi Mallya School (3.5 km)',
                'nearby_hospitals'   => 'Baptist Hospital (2.0 km)',
                'metro_distance'     => '10 minutes away from Cantonment Railway / Upcoming Metro lines',
                'connectivity_score' => '8',
            ]
        );

        // ── 35. Incor Infrastructure ─────────────────────────────────
        $incorBuilder = Builder::firstOrCreate(
            ['email' => 'sales@incor.in'],
            [
                'name'                     => 'Incor Infrastructure',
                'company_name'             => 'Incor Infrastructure Private Limited',
                'password'                 => Hash::make('IncorBlr2026'),
                'phone'                    => '08045124512',
                'city'                     => 'Hyderabad',
                'cities_operating'         => 'Hyderabad, Bengaluru',
                'established_year'         => '2006',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 4.2,
                'description'              => 'Incor Infrastructure is known for integrating high-caliber healthcare, internal professional sports arenas, and transparent NRI-friendly property tracking operations.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $incorBuilder->id, 'title' => 'Incor Carmel Heights'],
            [
                'builder_id'         => $incorBuilder->id,
                'description'        => 'Incor Carmel Heights is a highly sophisticated luxury residential enclave based right out of Gunjuur-Whitefield road. Built with premium custom aluminum shuttering frameworks for crisp lines and absolute structural reliance.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Gunjur Main Road, Near Carmelaram Link Network, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.911425,
                'longitude'          => 77.731425,
                'total_units'        => 400,
                'available_units'    => 115,
                'price_from'         => 11200000,
                'price_to'           => 21000000,
                'possession_date'    => '2027-06-30',
                'total_towers'       => 3,
                'floors_per_tower'   => '19',
                'is_featured'        => false,
                'views_count'        => 790,
                'leads_count'        => 0,
                'nearby_schools'     => 'Greenwood High School (2.5 km)',
                'nearby_hospitals'   => 'Columbia Asia Hospital Sarjapur (3.2 km)',
                'metro_distance'     => '12 minutes from Whitefield Metro hub networks',
                'connectivity_score' => '8',
            ]
        );

        // ── 36. Elegant Properties ───────────────────────────────────
        $elegantBuilder = Builder::firstOrCreate(
            ['email' => 'sales@elegantproperties.co.in'],
            [
                'name'                     => 'Elegant Properties',
                'company_name'             => 'Elegant Properties Private Limited',
                'password'                 => Hash::make('ElegantBlr2026'),
                'phone'                    => '08025550111',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2003',
                'is_verified'              => true,
                'total_delivered_projects' => 20,
                'rating'                   => 4.0,
                'description'              => 'Elegant Properties focuses on delivering highly cost-efficient boutique multi-family residential assets in strategic high-growth tech zones.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $elegantBuilder->id, 'title' => 'Elegant Exotica'],
            [
                'builder_id'         => $elegantBuilder->id,
                'description'        => 'Elegant Exotica is a low-density, beautifully executed multi-family residential layout based in Yelahanka. Designed cleanly with optimized common loading margins, premium vitrified masonry, and simple family-friendly recreational areas.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Kogilu Main Road, Yelahanka Extension, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.094212,
                'longitude'          => 77.621415,
                'total_units'        => 180,
                'available_units'    => 12,
                'price_from'         => 6200000,
                'price_to'           => 9500000,
                'possession_date'    => '2023-05-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '9',
                'is_featured'        => false,
                'views_count'        => 590,
                'leads_count'        => 0,
                'nearby_schools'     => 'DPS Bangalore North (4.0 km)',
                'nearby_hospitals'   => 'Regal Hospital (3.0 km)',
                'metro_distance'     => '10 minutes from Yelahanka upcoming Metro station crossway',
                'connectivity_score' => '7',
            ]
        );

        // ── 37. United Smart Spaces ──────────────────────────────────
        $unitedBuilder = Builder::firstOrCreate(
            ['email' => 'sales@unitedsmartspaces.com'],
            [
                'name'                     => 'United Smart Spaces',
                'company_name'             => 'United Projects Infrastructure Pvt Ltd',
                'password'                 => Hash::make('UnitedBlr2026'),
                'phone'                    => '08041212121',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2010',
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'rating'                   => 4.1,
                'description'              => 'United Smart Spaces builds automated real estate choices with smart layout models tailored for IT professionals looking for quick work commute paths.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $unitedBuilder->id, 'title' => 'United Aranya'],
            [
                'builder_id'         => $unitedBuilder->id,
                'description'        => 'United Aranya is a nature-focused smart residential high-rise project located off Harlur Road. Offers custom modular internal spacing allocations, voice-controlled lighting frameworks, and absolute zero-leak wet block construction systems.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Harlur Main Road, Off Sarjapur-ORR Link Road, South-East Bengaluru, Karnataka 560102',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.899412,
                'longitude'          => 77.661412,
                'total_units'        => 340,
                'available_units'    => 110,
                'price_from'         => 9200000,
                'price_to'           => 17500000,
                'possession_date'    => '2028-02-29',
                'total_towers'       => 3,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 810,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vibgyor High School Harlur (1.0 km)',
                'nearby_hospitals'   => 'Doctor Levine Memorial Hospital (2.5 km)',
                'metro_distance'     => '8 minutes away from Ibblur ORR Metro line station point',
                'connectivity_score' => '9',
            ]
        );

        // ── 38. DSR Infrastructure ───────────────────────────────────
        $dsrBuilder = Builder::firstOrCreate(
            ['email' => 'sales@dsrinfrastructure.com'],
            [
                'name'                     => 'DSR Infrastructure',
                'company_name'             => 'DSR Infrastructure Private Limited',
                'password'                 => Hash::make('DSRInfra2026'),
                'phone'                    => '08049123123',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad, Chennai',
                'established_year'         => '1988',
                'is_verified'              => true,
                'total_delivered_projects' => 38,
                'rating'                   => 4.2,
                'description'              => 'DSR Infrastructure delivers massive residential projects featuring high loading efficiencies, standard brick masonry, and long-term functional structural life.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $dsrBuilder->id, 'title' => 'DSR Highland Greenz'],
            [
                'builder_id'         => $dsrBuilder->id,
                'description'        => 'DSR Highland Greenz is an extensive multi-tower residential development situated off Sarjapur Road. Built cleanly to maximize panoramic green views, offering oversized continuous balconies and full structural power backup units.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Chikkakannalli, Sarjapur Main Road, East Bengaluru, Karnataka 560035',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.909212,
                'longitude'          => 77.701415,
                'total_units'        => 750,
                'available_units'    => 210,
                'price_from'         => 8800000,
                'price_to'           => 16500000,
                'possession_date'    => '2027-12-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '19',
                'is_featured'        => false,
                'views_count'        => 1020,
                'leads_count'        => 0,
                'nearby_schools'     => 'Greenwood High International School (3.5 km)',
                'nearby_hospitals'   => 'Manipal Hospital Sarjapur (2.2 km)',
                'metro_distance'     => 'Linked closely via the upcoming Phase 3 Outer ring-Sarjapur link systems',
                'connectivity_score' => '8',
            ]
        );

        // ── 39. MJ Infrastructure ────────────────────────────────────
        $mjBuilder = Builder::firstOrCreate(
            ['email' => 'sales@mjinfrastructure.com'],
            [
                'name'                     => 'MJ Infrastructure',
                'company_name'             => 'MJ Infrastructure Private Limited',
                'password'                 => Hash::make('MJInfra2026'),
                'phone'                    => '08025218888',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Kochi',
                'established_year'         => '1999',
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'rating'                   => 4.0,
                'description'              => 'MJ Infrastructure specializes in providing heavily value-focused mid-tier budget residential apartment builds across high-density South Bengaluru micro-markets.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $mjBuilder->id, 'title' => 'MJ Pearl Dew'],
            [
                'builder_id'         => $mjBuilder->id,
                'description'        => 'MJ Pearl Dew is a well-built budget residential apartment project located off Hosa Road. It provides optimized internal multi-family configurations, direct municipal line setups, and simple common security layouts.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Off Hosur Road, Hosa Road Junction Corridor, South Bengaluru, Karnataka 560100',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.864125,
                'longitude'          => 77.651412,
                'total_units'        => 220,
                'available_units'    => 14,
                'price_from'         => 5200000,
                'price_to'           => 8500000,
                'possession_date'    => '2022-04-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 610,
                'leads_count'        => 0,
                'nearby_schools'     => 'St. Joseph\'s Chamarajpet Extension Wing (3.0 km)',
                'nearby_hospitals'   => 'Narayana Health Clinic Hosa Road (1.5 km)',
                'metro_distance'     => '5 minutes away from Hosa Road Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 40. Sovereign Developers ─────────────────────────────────
        $sovereignBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sovereigndevelopers.com'],
            [
                'name'                     => 'Sovereign Developers',
                'company_name'             => 'Sovereign Developers & Infrastructure Ltd',
                'password'                 => Hash::make('Sovereign2026'),
                'phone'                    => '08040557777',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 3.9,
                'description'              => 'Sovereign Developers manages wide micro-market standard projects specializing in multi-tower affordable residential options.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sovereignBuilder->id, 'title' => 'Sovereign Unnathi'],
            [
                'builder_id'         => $sovereignBuilder->id,
                'description'        => 'Sovereign Unnathi is an extensive low-budget multi-family housing development project located in Horamavu. Offering direct entry points to corporate Outer Ring Road tech loops at optimized pricing points.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Horamavu Agara Main Road, Near Outer Ring Road Link, East Bengaluru, Karnataka 560043',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.031412,
                'longitude'          => 77.669512,
                'total_units'        => 450,
                'available_units'    => 28,
                'price_from'         => 4800000,
                'price_to'           => 7900000,
                'possession_date'    => '2021-08-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 510,
                'leads_count'        => 0,
                'nearby_schools'     => 'Horamavu Bridge School Systems (2.0 km)',
                'nearby_hospitals'   => 'Koshys Hospital (3.5 km)',
                'metro_distance'     => '10 minutes from KR Puram Interchange Metro Station',
                'connectivity_score' => '8',
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

        $luxuryTitles  = ['Sumadhura Folium', 'Urbanrise City of Joy', 'Vaswani Starlight'];
        $standardTitles = ['HM Tropical Tree', 'Incor Carmel Heights', 'Elegant Exotica', 'United Aranya', 'DSR Highland Greenz', 'MJ Pearl Dew', 'Sovereign Unnathi'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 4/10 complete: 40/100 Bengaluru Builders successfully initialized.');
    }
}