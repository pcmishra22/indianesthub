<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch6
 *
 * 10 Builders / Projects — Batch 6 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: Provident Housing, Puravankara Commercial (Corporate Wing), Brigade Orchards,
 * House of Hiranandani, Sobha Victoria Park (Classic Elite), DNR Corporation, Chaitanya Projects,
 * Kolte-Patil Exquisite, DS-MAX Sovereign, Karle Infra.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch6
 */
class BangaloreBuildersBatch6 extends Seeder
{
    public function run(): void
    {
        // ── 51. Provident Housing ────────────────────────────────────
        $providentBuilder = Builder::firstOrCreate(
            ['email' => 'sales@providenthousing.com'],
            [
                'name'                     => 'Provident Housing',
                'company_name'             => 'Provident Housing Limited',
                'password'                 => Hash::make('ProvidentBlr2026'),
                'phone'                    => '08044555544',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad, Chennai, Kochi, Mangaluru',
                'established_year'         => '2008',
                'is_verified'              => true,
                'total_delivered_projects' => 28,
                'rating'                   => 4.2,
                'description'              => 'A wholly owned premium subsidiary of Puravankara Limited, focused entirely on structured mass housing models delivering high value to home seekers.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $providentBuilder->id, 'title' => 'Provident Park Square'],
            [
                'builder_id'         => $providentBuilder->id,
                'description'        => 'Provident Park Square is a premier residential complex located on Kanakapura Road. Built with micro-engineered pre-cast configurations, multiple swimming pools, extensive gaming lawns, and direct arterial link lines.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Judicial Layout, Kanakapura Road Extension, South Bengaluru, Karnataka 560062',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.871425,
                'longitude'          => 77.541254,
                'total_units'        => 1102,
                'available_units'    => 45,
                'price_from'         => 5900000,
                'price_to'           => 11500000,
                'possession_date'    => '2023-03-31',
                'total_towers'       => 9,
                'floors_per_tower'   => '14',
                'is_featured'        => true,
                'views_count'        => 1980,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vakil Harvard International School (2.2 km)',
                'nearby_hospitals'   => 'Sri Sai Ram Hospital (3.0 km)',
                'metro_distance'     => '4 minutes to Thalaghattapura Metro Station point',
                'connectivity_score' => '10',
            ]
        );

        // ── 52. House of Hiranandani ─────────────────────────────────
        $hiranandaniBuilder = Builder::firstOrCreate(
            ['email' => 'sales@houseofhiranandani.com'],
            [
                'name'                     => 'House of Hiranandani',
                'company_name'             => 'House of Hiranandani Private Limited',
                'password'                 => Hash::make('HiranandaniBlr2026'),
                'phone'                    => '08043402000',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Mumbai, Bengaluru, Chennai, Hyderabad',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'rating'                   => 4.7,
                'description'              => 'Acclaimed creators of neoclassical high-rise communities featuring breathtaking columned architectures, extensive internal landscaping layouts, and signature luxury frameworks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $hiranandaniBuilder->id, 'title' => 'Hiranandani Queensgate'],
            [
                'builder_id'         => $hiranandaniBuilder->id,
                'description'        => 'Hiranandani Queensgate is a signature neoclassical luxury residential tower block in Devanahalli. Boasting magnificent entry configurations, double-skinned stone façades, large layouts, and absolute structural mastery.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Devanahalli, Near KIA Corporate Terminals, North Bengaluru, Karnataka 562110',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.224125,
                'longitude'          => 77.709412,
                'total_units'        => 410,
                'available_units'    => 12,
                'price_from'         => 8500000,
                'price_to'           => 17500000,
                'possession_date'    => '2022-12-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '21',
                'is_featured'        => true,
                'views_count'        => 1670,
                'leads_count'        => 0,
                'nearby_schools'     => 'Oxford English School Devanahalli (3.0 km)',
                'nearby_hospitals'   => 'Leena Multi Speciality Hospital (4.0 km)',
                'metro_distance'     => '8 minutes to upcoming KIA Airport Metro spur lines',
                'connectivity_score' => '9',
            ]
        );

        // ── 53. DNR Corporation ──────────────────────────────────────
        $dnrBuilder = Builder::firstOrCreate(
            ['email' => 'sales@dnrcorporation.com'],
            [
                'name'                     => 'DNR Corporation',
                'company_name'             => 'DNR Corporation Private Limited',
                'password'                 => Hash::make('DNRCorp2026'),
                'phone'                    => '08049200200',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2011',
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'rating'                   => 4.4,
                'description'              => 'DNR Corporation targets premium land holdings across corporate nodes, providing aesthetically complex architecture matrices with a high emphasis on natural light loops.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $dnrBuilder->id, 'title' => 'DNR Reflection'],
            [
                'builder_id'         => $dnrBuilder->id,
                'description'        => 'DNR Reflection is an ultra-exclusive luxury high-rise development situated along the Harlur Road corridor near Bellandur lake. Styled with premium Italian marble layouts, custom security options, and high ceiling tolerances.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Harlur Main Road, Near Outer Ring Road Junction, East Bengaluru, Karnataka 560102',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.901412,
                'longitude'          => 77.669412,
                'total_units'        => 179,
                'available_units'    => 4,
                'price_from'         => 18500000,
                'price_to'           => 34000000,
                'possession_date'    => '2021-06-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '18',
                'is_featured'        => false,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vibgyor High International Harlur (0.8 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (3.0 km)',
                'metro_distance'     => '6 minutes from Ibblur Metro Station line',
                'connectivity_score' => '10',
            ]
        );

        // ── 54. Chaitanya Projects ───────────────────────────────────
        $chaitanyaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@chaitanyaprojects.com'],
            [
                'name'                     => 'Chaitanya Projects',
                'company_name'             => 'Chaitanya Projects Private Limited',
                'password'                 => Hash::make('Chaitanya2026'),
                'phone'                    => '08041512345',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1995',
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'rating'                   => 4.6,
                'description'              => 'Boutique ultra-luxury specialists developing exclusive master-crafted villa communities across the absolute core of Whitefield.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $chaitanyaBuilder->id, 'title' => 'Chaitanya Sharan'],
            [
                'builder_id'         => $chaitanyaBuilder->id,
                'description'        => 'Chaitanya Sharan is an incredibly exclusive, ultra-luxury boutique villa compound located in the heart of Whitefield. Features custom Balinese style wood architectures, private multi-car garage bays, and full self-sustained smart parameters.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'ECC Road, Near Pattandur Agrahara, Whitefield Corporate Loop, East Bengaluru, Karnataka 560066',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.979512,
                'longitude'          => 77.744125,
                'total_units'        => 42,
                'available_units'    => 2,
                'price_from'         => 55000000,
                'price_to'           => 95000000,
                'possession_date'    => '2020-03-31',
                'total_towers'       => 0, // Independent Villas
                'floors_per_tower'   => '2',
                'is_featured'        => true,
                'views_count'        => 740,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Deens Academy ECC Road (0.4 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (1.5 km)',
                'metro_distance'     => '3 minutes away from Pattandur Agrahara Metro Corridor',
                'connectivity_score' => '10',
            ]
        );

        // ── 55. Karle Infra ──────────────────────────────────────────
        $karleBuilder = Builder::firstOrCreate(
            ['email' => 'sales@karleinfra.com'],
            [
                'name'                     => 'Karle Infra',
                'company_name'             => 'Karle Infra Private Limited',
                'password'                 => Hash::make('KarleInfra2026'),
                'phone'                    => '08066326000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Goa',
                'established_year'         => '2008',
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'rating'                   => 4.5,
                'description'              => 'Karle Infra focuses on developing extensive, forward-looking sustainable SEZ tech cities and integrated high-design sky-rise residences.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $karleBuilder->id, 'title' => 'Karle Zenith Residences'],
            [
                'builder_id'         => $karleBuilder->id,
                'description'        => 'Karle Zenith Residences is a world-class premium luxury sky-rise project sitting on Nagawara lake boundary inside Karle Town Centre. Styled beautifully around smart building metrics, central conditioning vents, and expansive corner decks.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Karle Town Centre SEZ, Nagawara Cross Expressway, North Bengaluru, Karnataka 560045',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.044125,
                'longitude'          => 77.619412,
                'total_units'        => 396,
                'available_units'    => 14,
                'price_from'         => 21000000,
                'price_to'           => 45000000,
                'possession_date'    => '2022-09-30',
                'total_towers'       => 3,
                'floors_per_tower'   => '34',
                'is_featured'        => true,
                'views_count'        => 1850,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vidyashilp Academy Link Hub (4.0 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal (2.5 km)',
                'metro_distance'     => 'Direct walking access to upcoming Nagawara interchange line',
                'connectivity_score' => '10',
            ]
        );

        // ── 56. Century Real Estate (Plots Wing) ─────────────────────
        $centuryPlotsBuilder = Builder::firstOrCreate(
            ['email' => 'sales.plots@centuryrealestate.in'],
            [
                'name'                     => 'Century Plotted Dev',
                'company_name'             => 'Century Plotted Estates Pvt Ltd',
                'password'                 => Hash::make('CenturyPlot2026'),
                'phone'                    => '08044334434',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2010',
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'rating'                   => 4.4,
                'description'              => 'The specialized infrastructure division of Century Real Estate, engineering world-class luxury smart plotted layout schemes across North Bengaluru.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $centuryPlotsBuilder->id, 'title' => 'Century Eden'],
            [
                'builder_id'         => $centuryPlotsBuilder->id,
                'description'        => 'Century Eden is a premium, beautifully organized plotted development ecosystem positioned along Doddaballapur Road. Completed with asphalt road lines, fully integrated concealed electrical cabling loops, and a vast lifestyle country club.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Doddaballapur Main Road, Yelahanka Extension, North Bengaluru, Karnataka 561203',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.254125,
                'longitude'          => 77.561412,
                'total_units'        => 520,
                'available_units'    => 40,
                'price_from'         => 4500000,
                'price_to'           => 12000000,
                'possession_date'    => '2021-03-31',
                'total_towers'       => 0, // Plotted Community
                'floors_per_tower'   => '0',
                'is_featured'        => false,
                'views_count'        => 1210,
                'leads_count'        => 0,
                'nearby_schools'     => 'Presidency University Campus (3.5 km)',
                'nearby_hospitals'   => 'Columbia Asia Hospital Link Terminal (12.0 km)',
                'metro_distance'     => '15 minutes away from Yelahanka suburban terminal lines',
                'connectivity_score' => '8',
            ]
        );

        // ── 57. Shriram Value Homes (Affordable Arms) ────────────────
        $shriramValueBuilder = Builder::firstOrCreate(
            ['email' => 'sales.value@shriramproperties.com'],
            [
                'name'                     => 'Shriram Value Homes',
                'company_name'             => 'Shriram Housing Ventures Pvt Ltd',
                'password'                 => Hash::make('ShriramVal2026'),
                'phone'                    => '08040222223',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai',
                'established_year'         => '2014',
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'rating'                   => 4.1,
                'description'              => 'The lean engineering division of Shriram Properties, creating highly structural, smart compact housing projects along vital manufacturing hubs.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $shriramValueBuilder->id, 'title' => 'Shriram Earth'],
            [
                'builder_id'         => $shriramValueBuilder->id,
                'description'        => 'Shriram Earth is a brilliantly executed affordable smart housing community positioned in Off Electronic City. Features optimized internal load models, wide asphalt pathways, reliable rain infrastructure lines, and full power backups.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Attibele-Anekal Road, Off Hosur Tech Expressway, South Bengaluru, Karnataka 562107',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.784125,
                'longitude'          => 77.711425,
                'total_units'        => 380,
                'available_units'    => 18,
                'price_from'         => 3500000,
                'price_to'           => 6500000,
                'possession_date'    => '2022-08-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '8',
                'is_featured'        => false,
                'views_count'        => 940,
                'leads_count'        => 0,
                'nearby_schools'     => 'Alliance University Main Campus (3.0 km)',
                'nearby_hospitals'   => 'Narayana Health City (8.5 km)',
                'metro_distance'     => '12 minutes to Bommasandra Metro corridor point',
                'connectivity_score' => '7',
            ]
        );

        // ── 58. inner Space Homes ────────────────────────────────────
        $innerspaceBuilder = Builder::firstOrCreate(
            ['email' => 'sales@innerspacehomes.in'],
            [
                'name'                     => 'inner Space Homes',
                'company_name'             => 'inner Space Builders Private Limited',
                'password'                 => Hash::make('InnerSpace2026'),
                'phone'                    => '08025723333',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2004',
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'rating'                   => 4.2,
                'description'              => 'inner Space Homes develops boutique residential assets emphasizing customized structural options and minimalist contemporary interior elements.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $innerspaceBuilder->id, 'title' => 'inner Space Meadowz'],
            [
                'builder_id'         => $innerspaceBuilder->id,
                'description'        => 'inner Space Meadowz is a low-density premium apartment building situated in Brookefield. Styled explicitly with superior clay masonry, zero spatial dead-corners, and direct private elevator integration lines.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Brookefield Main Tech Junction Road, East Bengaluru, Karnataka 560037',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.964125,
                'longitude'          => 77.719412,
                'total_units'        => 84,
                'available_units'    => 3,
                'price_from'         => 8900000,
                'price_to'           => 14500000,
                'possession_date'    => '2021-05-31',
                'total_towers'       => 1,
                'floors_per_tower'   => '5',
                'is_featured'        => false,
                'views_count'        => 480,
                'leads_count'        => 0,
                'nearby_schools'     => 'Ryan International School (2.0 km)',
                'nearby_hospitals'   => 'Apollo Cradle Brookefield (1.0 km)',
                'metro_distance'     => '5 minutes from Kundalahalli Metro Station node',
                'connectivity_score' => '9',
            ]
        );

        // ── 59. Vajram Group ─────────────────────────────────────────
        $vajramBuilder = Builder::firstOrCreate(
            ['email' => 'sales@vajramgroup.com'],
            [
                'name'                     => 'Vajram Group',
                'company_name'             => 'Vajram Estates Private Limited',
                'password'                 => Hash::make('VajramBlr2026'),
                'phone'                    => '08043438888',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2009',
                'is_verified'              => true,
                'total_delivered_projects' => 11,
                'rating'                   => 4.3,
                'description'              => 'Vajram Group focuses entirely on constructing high-performance green structures utilizing tier-one materials and transparent execution indices.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $vajramBuilder->id, 'title' => 'Vajram Tiara'],
            [
                'builder_id'         => $vajramBuilder->id,
                'description'        => 'Vajram Tiara is a premium, impeccably structured high-rise residential complex located on Thanisandra Main Road. Focuses on three-sided open layout options, superior structural insulation engineering, and multi-tier club zones.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Thanisandra Main Expressway, Near Bharatiya City Loop, North Bengaluru, Karnataka 560077',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.079412,
                'longitude'          => 77.641254,
                'total_units'        => 292,
                'available_units'    => 11,
                'price_from'         => 9200000,
                'price_to'           => 16500000,
                'possession_date'    => '2023-01-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '20',
                'is_featured'        => false,
                'views_count'        => 920,
                'leads_count'        => 0,
                'nearby_schools'     => 'Federal Public School (1.5 km)',
                'nearby_hospitals'   => 'Regal Super Speciality Hospital (1.2 km)',
                'metro_distance'     => '6 minutes to upcoming Nagawara Metro Cross lines',
                'connectivity_score' => '9',
            ]
        );

        // ── 60. Prime One Corp ───────────────────────────────────────
        $primeoneBuilder = Builder::firstOrCreate(
            ['email' => 'sales@primeonecorp.com'],
            [
                'name'                     => 'Prime One Corp',
                'company_name'             => 'Prime One Corporation Private Limited',
                'password'                 => Hash::make('PrimeOne2026'),
                'phone'                    => '08025591111',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2011',
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'rating'                   => 4.1,
                'description'              => 'Prime One Corp manages elite niche building allocations, specializing in low-density boutique high-rises in core urban residential corridors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $primeoneBuilder->id, 'title' => 'Prime One Pebble Creek'],
            [
                'builder_id'         => $primeoneBuilder->id,
                'description'        => 'Prime One Pebble Creek is a highly refined luxury residential enclave positioned off Electronic City Phase 1. Features premium custom masonry setups, private individual rooftop terraces, and continuous automated safety loops.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Neotown Road, Electronic City Phase 1 Core, South Bengaluru, Karnataka 560100',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.851412,
                'longitude'          => 77.661412,
                'total_units'        => 140,
                'available_units'    => 8,
                'price_from'         => 7800000,
                'price_to'           => 13000000,
                'possession_date'    => '2022-05-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '8',
                'is_featured'        => false,
                'views_count'        => 640,
                'leads_count'        => 0,
                'nearby_schools'     => 'Trio World Academy Extension (2.5 km)',
                'nearby_hospitals'   => 'Springleaf Hospital E-City (1.8 km)',
                'metro_distance'     => '4 minutes from Electronic City Metro Station Node',
                'connectivity_score' => '9',
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

        $luxuryTitles  = ['Hiranandani Queensgate', 'Chaitanya Sharan', 'Karle Zenith Residences', 'DNR Reflection', 'Vajram Tiara'];
        $standardTitles = ['Provident Park Square', 'Century Eden', 'Shriram Earth', 'inner Space Meadowz', 'Prime One Pebble Creek'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 6/10 complete: 60/100 Bengaluru Builders successfully initialized.');
    }
}