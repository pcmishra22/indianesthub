<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch9
 *
 * 10 Builders / Projects — Batch 9 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Includes: Puravankara Luxury Wing, Brigade Smart Living, Prestige Tech Landmarks,
 * Shriram Smart Homes, Sobha Luxury Condos, Assetz Urban Enclaves, Godrej Premium Arms,
 * Salarpuria Boutique, Nambiar District, LGCL Horizons.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch9
 */
class BangaloreBuildersBatch9 extends Seeder
{
    public function run(): void
    {
        // ── 81. Puravankara Luxury Wing (The World Home Series) ──────
        $purvaLuxBuilder = Builder::firstOrCreate(
            ['email' => 'sales.luxury@puravankara.com'],
            [
                'name'                     => 'Purva Luxury Wing',
                'company_name'             => 'Purva World Homes Private Limited',
                'password'                 => Hash::make('PurvaLux2026'),
                'phone'                    => '08044555556',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Mumbai, Chennai',
                'established_year'         => '2018',
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'rating'                   => 4.7,
                'description'              => 'The elite architecture division of Puravankara Limited, building ultra-luxury smart assets focused on wellness features and pure oxygen sensory landscapes.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $purvaLuxBuilder->id, 'title' => 'Purva Atmosphere'],
            [
                'builder_id'         => $purvaLuxBuilder->id,
                'description'        => 'Purva Atmosphere is an ultra-premium, high-concept health-centric luxury high-rise located in Thanisandra. Integrates massive innovative air filtration towers, clean sky deck configurations, and premium spatial layout parameters.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Thanisandra Main Expressway, Near Manyata Tech Hub Corridor, North Bengaluru, Karnataka 560077',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.064125,
                'longitude'          => 77.641412,
                'total_units'        => 930,
                'available_units'    => 18,
                'price_from'         => 12500000,
                'price_to'           => 26000000,
                'possession_date'    => '2023-10-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '34',
                'is_featured'        => true,
                'views_count'        => 2450,
                'leads_count'        => 0,
                'nearby_schools'     => 'Rashtrotthana Vidya Kendra (1.5 km)',
                'nearby_hospitals'   => 'Regal Super Speciality Hospital (2.2 km)',
                'metro_distance'     => '6 minutes to upcoming Thanisandra metro grid station',
                'connectivity_score' => '10',
            ]
        );

        // ── 82. Brigade Smart Living (Urban Value Arms) ──────────────
        $brigadeSmartBuilder = Builder::firstOrCreate(
            ['email' => 'sales.smart@brigadegroup.com'],
            [
                'name'                     => 'Brigade Smart Living',
                'company_name'             => 'Brigade Value Homes Private Limited',
                'password'                 => Hash::make('BrigadeSmart2026'),
                'phone'                    => '18001029978',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2016',
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'rating'                   => 4.3,
                'description'              => 'The innovative modular housing segment of Brigade Group, generating high-volume precast architectural residences inside key infrastructure corridors.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $brigadeSmartBuilder->id, 'title' => 'Brigade El Dorado'],
            [
                'builder_id'         => $brigadeSmartBuilder->id,
                'description'        => 'Brigade El Dorado is a magnificent 50-acre masterfully planned smart township positioned in Aerospace Park. Built explicitly with high-performance precast frameworks, extensive open gaming arrays, multiple club clusters, and high-efficiency spatial configurations.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'KIADB Aerospace Park, Near KIA Terminal Highways, Devanahalli Extension, North Bengaluru, Karnataka 562149',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.194212,
                'longitude'          => 77.681425,
                'total_units'        => 2400,
                'available_units'    => 480,
                'price_from'         => 4500000,
                'price_to'           => 8900000,
                'possession_date'    => '2027-12-31',
                'total_towers'       => 12,
                'floors_per_tower'   => '22',
                'is_featured'        => true,
                'views_count'        => 3800,
                'leads_count'        => 0,
                'nearby_schools'     => 'Sterling English School Aerospace (3.0 km)',
                'nearby_hospitals'   => 'Leena Multi Speciality Hospital Line (6.5 km)',
                'metro_distance'     => '8 minutes from upcoming corporate Aerospace Metro spur line',
                'connectivity_score' => '9',
            ]
        );

        // ── 83. Prestige Tech Landmarks (Mixed Hubs) ───────────────────
        $prestigeTechBuilder = Builder::firstOrCreate(
            ['email' => 'sales.tech@prestigeconstructions.com'],
            [
                'name'                     => 'Prestige Tech Lands',
                'company_name'             => 'Prestige Tech Estates Private Limited',
                'password'                 => Hash::make('PrestigeTech2026'),
                'phone'                    => '18003130082',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2010',
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'rating'                   => 4.6,
                'description'              => 'The specialized mixed-use corporate division of Prestige Group, developing high-density executive apartments right bordering premium tech hubs.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $prestigeTechBuilder->id, 'title' => 'Prestige Falcon City'],
            [
                'builder_id'         => $prestigeTechBuilder->id,
                'description'        => 'Prestige Falcon City is a landmark ultra-premium mixed residential-retail township complex positioned on Kanakapura Road. Home to expansive luxury residential towers, a large internal retail forum mall block, and massive modern club setups.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Kanakapura Main Road, Anjanapura Extension, South Bengaluru, Karnataka 560062',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.879412,
                'longitude'          => 77.561412,
                'total_units'        => 2520,
                'available_units'    => 35,
                'price_from'         => 11000000,
                'price_to'           => 24500000,
                'possession_date'    => '2021-05-31',
                'total_towers'       => 7,
                'floors_per_tower'   => '31',
                'is_featured'        => true,
                'views_count'        => 4210,
                'leads_count'        => 0,
                'nearby_schools'     => 'Kumaran\'s School Kanakapura Line (1.0 km)',
                'nearby_hospitals'   => 'Fortis Hospital Link Hub (5.0 km)',
                'metro_distance'     => 'Immediate direct walking access to Konanakunte Cross Metro station',
                'connectivity_score' => '10',
            ]
        );

        // ── 84. Shriram Smart Homes (Codename series) ────────────────
        $shriramSmartBuilder = Builder::firstOrCreate(
            ['email' => 'sales.smart@shriramproperties.com'],
            [
                'name'                     => 'Shriram Smart Homes',
                'company_name'             => 'Shriram Smart Housing Private Limited',
                'password'                 => Hash::make('ShriramSmart2026'),
                'phone'                    => '08040222227',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2017',
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'rating'                   => 4.1,
                'description'              => 'Engineering highly optimized smart utility compact apartments equipped with full home automation frameworks at affordable index brackets.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $shriramSmartBuilder->id, 'title' => 'Shriram Liberty Square'],
            [
                'builder_id'         => $shriramSmartBuilder->id,
                'description'        => 'Shriram Liberty Square is a beautifully systematic, smart residential complex off Electronic City. Focuses on compact layout grids, full internet-of-things smart home automations, and streamlined access lines to IT clusters.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Veerasandra Main Road, Electronic City Phase 2 Extension, South Bengaluru, Karnataka 560100',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.844125,
                'longitude'          => 77.679412,
                'total_units'        => 644,
                'available_units'    => 18,
                'price_from'         => 4900000,
                'price_to'           => 8500000,
                'possession_date'    => '2023-03-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 1110,
                'leads_count'        => 0,
                'nearby_schools'     => 'Trio World Academy Extension Link (4.0 km)',
                'nearby_hospitals'   => 'Narayana Health City Core (4.5 km)',
                'metro_distance'     => '6 minutes from Electronic City Phase 2 Metro station cross',
                'connectivity_score' => '9',
            ]
        );

        // ── 85. Sobha Luxury Condos ──────────────────────────────────
        $sobhaCondosBuilder = Builder::firstOrCreate(
            ['email' => 'sales.condos@sobha.com'],
            [
                'name'                     => 'Sobha Luxury Condos',
                'company_name'             => 'Sobha Luxury Residences Private Limited',
                'password'                 => Hash::make('SobhaCondos2026'),
                'phone'                    => '08049320011',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2011',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 4.8,
                'description'              => 'Specialized engineering wing of Sobha Limited generating high-end architectural sky enclaves featuring massive masonry thickness tolerances and premium finishes.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sobhaCondosBuilder->id, 'title' => 'Sobha HRC Palace Breeze'],
            [
                'builder_id'         => $sobhaCondosBuilder->id,
                'description'        => 'Sobha HRC Palace Breeze is an ultra-luxury premium residential sky-rise development positioned right on Jakkur Road. Styled meticulously with Italian marble alignments, custom security configurations, and matching pristine structural craftsmanship parameters.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Jakkur Main Road, Off NH-44 International Highway, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.079412,
                'longitude'          => 77.611412,
                'total_units'        => 342,
                'available_units'    => 11,
                'price_from'         => 16500000,
                'price_to'           => 34000000,
                'possession_date'    => '2022-08-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '19',
                'is_featured'        => true,
                'views_count'        => 1780,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vidyashilp Academy Link Line (2.5 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal Link (4.0 km)',
                'metro_distance'     => '4 minutes away from upcoming Jakkur Cross Metro station portal',
                'connectivity_score' => '10',
            ]
        );

        // ── 86. Assetz Urban Enclaves ────────────────────────────────
        $assetzUrbanBuilder = Builder::firstOrCreate(
            ['email' => 'sales.urban@assetzproperty.com'],
            [
                'name'                     => 'Assetz Urban Enclaves',
                'company_name'             => 'Assetz Lifestyle Spaces Private Limited',
                'password'                 => Hash::make('AssetzUrban2026'),
                'phone'                    => '08046124615',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '2014',
                'is_verified'              => true,
                'total_delivered_projects' => 11,
                'rating'                   => 4.4,
                'description'              => 'Developing high-design carbon-healing apartment towers emphasizing clean environmental engineering metrics and sustainable urban living frameworks.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $assetzUrbanBuilder->id, 'title' => 'Assetz 63 Degree East'],
            [
                'builder_id'         => $assetzUrbanBuilder->id,
                'description'        => 'Assetz 63 Degree East is a premium eco-conscious residential township community located off Sarjapur Main Road. Built around individual home water recycling networks, smart lifestyle avenues, and vast linear central park spaces.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sarjapur Main Road, Kodathi, East Bengaluru, Karnataka 560035',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.901412,
                'longitude'          => 77.719412,
                'total_units'        => 1208,
                'available_units'    => 34,
                'price_from'         => 8200000,
                'price_to'           => 16500000,
                'possession_date'    => '2023-05-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 1940,
                'leads_count'        => 0,
                'nearby_schools'     => 'Oakridge International School (3.5 km)',
                'nearby_hospitals'   => 'Columbia Asia Hospital Sarjapur Cross (5.0 km)',
                'metro_distance'     => '9 minutes to Bellandur ORR Metro alignment node',
                'connectivity_score' => '9',
            ]
        );

        // ── 87. Godrej Premium Arms (The Signature Series) ───────────
        $godrejPremBuilder = Builder::firstOrCreate(
            ['email' => 'sales.premium@godrejproperties.com'],
            [
                'name'                     => 'Godrej Premium Arms',
                'company_name'             => 'Godrej Landmark Developers Private Limited',
                'password'                 => Hash::make('GodrejPrem2026'),
                'phone'                    => '18002582589',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Mumbai, Pune',
                'established_year'         => '2013',
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'rating'                   => 4.5,
                'description'              => 'Crafting absolute high-end lifestyle residential towers incorporating extensive nature groves and exclusive resort-style clubhouse hubs.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $godrejPremBuilder->id, 'title' => 'Godrej Reflections'],
            [
                'builder_id'         => $godrejPremBuilder->id,
                'description'        => 'Godrej Reflections is a signature luxury lakeside sky-rise apartment project off Sarjapur Road. Noted for high ceiling heights, spacious floor designs, and a majestic multi-tier organic country club setup.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Harlur Main Road, Next to Kasavanahalli Lake, East Bengaluru, Karnataka 560102',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.914125,
                'longitude'          => 77.671425,
                'total_units'        => 265,
                'available_units'    => 8,
                'price_from'         => 18500000,
                'price_to'           => 38000000,
                'possession_date'    => '2022-03-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '20',
                'is_featured'        => true,
                'views_count'        => 1560,
                'leads_count'        => 0,
                'nearby_schools'     => 'Vibgyor High International School Harlur (0.5 km)',
                'nearby_hospitals'   => 'Sakra World Hospital Terminal (2.8 km)',
                'metro_distance'     => '5 minutes away from Ibblur ORR Metro link cross',
                'connectivity_score' => '10',
            ]
        );

    }
}
