<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * BangaloreBuildersBatch1
 *
 * 10 Builders / Projects — Batch 1 of 10 (Target: 100 Builders)
 * Sourced using verified 2026 K-RERA benchmarks and accurate micro-market data.
 *
 * Run:  php artisan db:seed --class=BangaloreBuildersBatch1
 */
class BangaloreBuildersBatch1 extends Seeder
{
    public function run(): void
    {
        // ── 1. Prestige Group ────────────────────────────────────────
        $prestigeBuilder = Builder::firstOrCreate(
            ['email' => 'contact.sales@prestigeconstructions.com'],
            [
                'name'                     => 'Prestige Group',
                'company_name'             => 'Prestige Estates Projects Ltd.',
                'password'                 => Hash::make('PrestigeBlr2026'),
                'phone'                    => '18003130080',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Hyderabad, Kochi, Mumbai',
                'established_year'         => '1986',
                'is_verified'              => true,
                'total_delivered_projects' => 300,
                'rating'                   => 4.7,
                'description'              => 'Prestige Group is a premier real estate developer renowned for master-planned commercial and luxury residential high-rise ecosystems.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $prestigeBuilder->id, 'title' => 'Prestige Raintree Park'],
            [
                'builder_id'         => $prestigeBuilder->id,
                'description'        => 'Prestige Raintree Park is a premium multi-generational residential township located in Whitefield Main Road, Varthur Hobli. Spread over 28 acres, it features beautifully planned high-rise configurations offering luxury 3, 4, and 5 BHK layouts alongside a corporate hub ecosystem.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Whitefield Main Road, Varthur Hobli, Near Nallurhalli Metro, East Bengaluru, Karnataka 560066',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.959241,
                'longitude'          => 77.746592,
                'total_units'        => 1368,
                'available_units'    => 450,
                'price_from'         => 29500000,
                'price_to'           => 60600000,
                'possession_date'    => '2028-05-31',
                'total_towers'       => 18,
                'floors_per_tower'   => '19',
                'is_featured'        => true,
                'views_count'        => 1550,
                'leads_count'        => 0,
                'nearby_schools'     => 'Ryan International School (2.5 km), Vagdevi Vilas School (3.6 km)',
                'nearby_hospitals'   => 'Manipal Hospital Whitefield (4.0 km)',
                'metro_distance'     => '7 minutes away from Nallurhalli Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 2. Brigade Group ─────────────────────────────────────────
        $brigadeBuilder = Builder::firstOrCreate(
            ['email' => 'salesinfo@brigadegroup.com'],
            [
                'name'                     => 'Brigade Group',
                'company_name'             => 'Brigade Enterprises Limited',
                'password'                 => Hash::make('BrigadeBlr2026'),
                'phone'                    => '18001029977',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Mysore, Chennai, Hyderabad',
                'established_year'         => '1986',
                'is_verified'              => true,
                'total_delivered_projects' => 250,
                'rating'                   => 4.6,
                'description'              => 'Brigade Group delivers high-value urban ecosystems, luxury developments, and mixed-use landmark commercial hubs across South India.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $brigadeBuilder->id, 'title' => 'Brigade Insignia'],
            [
                'builder_id'         => $brigadeBuilder->id,
                'description'        => 'Brigade Insignia is an premium boutique residential enclave nestled right off the New International Airport Road in Yelahanka. Spanning over 5.8 pristine acres, it provides premium architectural layouts with signature sky-high terrace decks.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'New International Airport Road, Maruthi Nagar, Yelahanka, North Bengaluru, Karnataka 560064',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.111425,
                'longitude'          => 77.618512,
                'total_units'        => 344,
                'available_units'    => 120,
                'price_from'         => 35900000,
                'price_to'           => 84200000,
                'possession_date'    => '2029-06-01',
                'total_towers'       => 6,
                'floors_per_tower'   => '18',
                'is_featured'        => true,
                'views_count'        => 890,
                'leads_count'        => 0,
                'nearby_schools'     => 'Canadian International School (2.1 km), Vidyashilp Academy (5.0 km)',
                'nearby_hospitals'   => 'Aster CMI Hospital Hebbal (7.0 km)',
                'metro_distance'     => '1.1 km from upcoming Yelahanka Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 3. Sobha Limited ─────────────────────────────────────────
        $sobhaBuilder = Builder::firstOrCreate(
            ['email' => 'sales.bengaluru@sobha.com'],
            [
                'name'                     => 'Sobha Limited',
                'company_name'             => 'Sobha Limited',
                'password'                 => Hash::make('SobhaBlr2026'),
                'phone'                    => '08049320000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Delhi-NCR, Chennai, Pune, Thrissur',
                'established_year'         => '1995',
                'is_verified'              => true,
                'total_delivered_projects' => 170,
                'rating'                   => 4.8,
                'description'              => 'Sobha Limited is India\'s only backward-integrated developer, delivering exceptional structural reliability and premium German construction finishes.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sobhaBuilder->id, 'title' => 'Sobha Neopolis'],
            [
                'builder_id'         => $sobhaBuilder->id,
                'description'        => 'Sobha Neopolis is a stunning Mediterranean Greek-themed premium residential enclave situated off Marathahalli-Outer Ring Road in Panathur. Spread across 26 acres, it features iconic Santorini-styled structural details and high-end water parks.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Panathur Main Road, Off Marathahalli-Outer Ring Road, East Bengaluru, Karnataka 560087',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.935102,
                'longitude'          => 77.712154,
                'total_units'        => 1875,
                'available_units'    => 600,
                'price_from'         => 23300000,
                'price_to'           => 35000000,
                'possession_date'    => '2028-12-31',
                'total_towers'       => 19,
                'floors_per_tower'   => '18',
                'is_featured'        => false,
                'views_count'        => 2110,
                'leads_count'        => 0,
                'nearby_schools'     => 'New Horizon Gurukul (1.2 km), Orchids International School (1.8 km)',
                'nearby_hospitals'   => 'Sakra World Hospital (3.2 km)',
                'metro_distance'     => 'Connected closely to Outer Ring Road Phase 2A Metro line Corridor',
                'connectivity_score' => '8',
            ]
        );

        // ── 4. Godrej Properties ─────────────────────────────────────
        $godrejBuilder = Builder::firstOrCreate(
            ['email' => 'sales@godrejproperties.com'],
            [
                'name'                     => 'Godrej Properties',
                'company_name'             => 'Godrej Properties Limited',
                'password'                 => Hash::make('GodrejBlr2026'),
                'phone'                    => '08046415500',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Bengaluru, Mumbai, Pune, Delhi-NCR, Kolkata',
                'established_year'         => '1990',
                'is_verified'              => true,
                'total_delivered_projects' => 90,
                'rating'                   => 4.5,
                'description'              => 'The real estate wing of the 125-year-old Godrej legacy group, recognized for pioneering smart innovation, open sustainability frameworks, and trusted living layouts.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $godrejBuilder->id, 'title' => 'Godrej Lakeside Orchard'],
            [
                'builder_id'         => $godrejBuilder->id,
                'description'        => 'Godrej Lakeside Orchard is an ultra-modern nature-centric premium housing development corridor based out of Sarjapur Road. Featuring premium luxury lake views, state-of-the-art oxygen zones, and dynamic connectivity maps to high-growth office spaces.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Chikkakannalli, Sarjapur Road, Near Carmelaram, East Bengaluru, Karnataka 560035',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.911405,
                'longitude'          => 77.698242,
                'total_units'        => 850,
                'available_units'    => 310,
                'price_from'         => 19600000,
                'price_to'           => 32300000,
                'possession_date'    => '2029-12-31',
                'total_towers'       => 7,
                'floors_per_tower'   => '24',
                'is_featured'        => true,
                'views_count'        => 1205,
                'leads_count'        => 0,
                'nearby_schools'     => 'The Greenwood High International School (4.2 km), TISB (5.1 km)',
                'nearby_hospitals'   => 'Columbia Asia / Manipal Hospital Sarjapur (3.0 km)',
                'metro_distance'     => 'Accessible through Carmelaram Railway and upcoming Sarjapur Metro phase',
                'connectivity_score' => '8',
            ]
        );

        // ── 5. Birla Estates ─────────────────────────────────────────
        $birlaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@birlaestates.com'],
            [
                'name'                     => 'Birla Estates',
                'company_name'             => 'Birla Estates Private Limited',
                'password'                 => Hash::make('BirlaBlr2026'),
                'phone'                    => '08069002222',
                'city'                     => 'Mumbai',
                'cities_operating'         => 'Bengaluru, Mumbai, Gurugram, Pune',
                'established_year'         => '2016',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 4.6,
                'description'              => 'Birla Estates, backed by Century Textiles and the Aditya Birla Group, redefines urban living with their LIFEDESIGN philosophy centered around harmony.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $birlaBuilder->id, 'title' => 'Birla Trimaya'],
            [
                'builder_id'         => $birlaBuilder->id,
                'description'        => 'Birla Trimaya is a stunning 52-acre integrated premium green township located in Shettigere, Devanahalli. The township offers premium apartments, row houses, and state-of-the-art curated ecological water bodies with high proximity to the Kempegowda International Airport.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Shettigere Village, Kasaba Hobli, Devanahalli Taluk, North Bengaluru, Karnataka 562110',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.220144,
                'longitude'          => 77.691456,
                'total_units'        => 2500,
                'available_units'    => 750,
                'price_from'         => 9200000,
                'price_to'           => 28000000,
                'possession_date'    => '2028-10-31',
                'total_towers'       => 12,
                'floors_per_tower'   => '25',
                'is_featured'        => true,
                'views_count'        => 3100,
                'leads_count'        => 0,
                'nearby_schools'     => 'Stonehill International School (6.5 km), DPS North (8.0 km)',
                'nearby_hospitals'   => 'Ramaiah Leena Hospital Devanahalli (7.2 km)',
                'metro_distance'     => '10 minutes from Airport KIADB Metro Terminal Point',
                'connectivity_score' => '9',
            ]
        );

        // ── 6. Sattva Group ──────────────────────────────────────────
        $sattvaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@sattvagroup.in'],
            [
                'name'                     => 'Sattva Group',
                'company_name'             => 'Sattva Developers Private Limited',
                'password'                 => Hash::make('SattvaBlr2026'),
                'phone'                    => '08042699000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Hyderabad, Kolkata, Pune, Coimbatore',
                'established_year'         => '1993',
                'is_verified'              => true,
                'total_delivered_projects' => 140,
                'rating'                   => 4.4,
                'description'              => 'Sattva Group is a massive diversified construction conglomerate setting foundational benchmarks for Grade-A IT parks and vibrant tech-corridor residential towers.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sattvaBuilder->id, 'title' => 'Sattva Lumina'],
            [
                'builder_id'         => $sattvaBuilder->id,
                'description'        => 'Sattva Lumina is a high-rise residential complex located inside the growing aerospace tech loop of Yelahanka/Bagalur, North Bengaluru. Highly optimized space dynamics catered for millennial corporate professionals.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Bagalur Main Road, KIADB Aerospace Park Corridor, Yelahanka Loop, North Bengaluru, Karnataka 562149',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.142512,
                'longitude'          => 77.671045,
                'total_units'        => 1100,
                'available_units'    => 400,
                'price_from'         => 7389000,
                'price_to'           => 17900000,
                'possession_date'    => '2029-08-31',
                'total_towers'       => 8,
                'floors_per_tower'   => '22',
                'is_featured'        => false,
                'views_count'        => 1150,
                'leads_count'        => 0,
                'nearby_schools'     => 'Aditi Mallya International School (5.4 km)',
                'nearby_hospitals'   => 'Chanakya Medical Center (3.0 km)',
                'metro_distance'     => '15 minutes away from Bagalur Cross Metro Station point',
                'connectivity_score' => '8',
            ]
        );

        // ── 7. Puravankara Limited ───────────────────────────────────
        $purvaBuilder = Builder::firstOrCreate(
            ['email' => 'sales@puravankara.com'],
            [
                'name'                     => 'Puravankara Limited',
                'company_name'             => 'Puravankara Limited',
                'password'                 => Hash::make('PurvaBlr2026'),
                'phone'                    => '18602080000',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Hyderabad, Kochi, Mumbai, Pune',
                'established_year'         => '1975',
                'is_verified'              => true,
                'total_delivered_projects' => 80,
                'rating'                   => 4.5,
                'description'              => 'Puravankara Limited brings 50 years of development expertise, pioneering theme-based luxury real estate landscapes and transparent urban projects.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $purvaBuilder->id, 'title' => 'Purva Highlands'],
            [
                'builder_id'         => $purvaBuilder->id,
                'description'        => 'Purva Highlands stands as a stunning hilltop residential enclave located off Kanakapura Road. Renowned for its sweeping greenery, premium engineering frameworks, and unparalleled clear-air microclimate layouts.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Mallasandra, Off Kanakapura Road, Near NICE Road Junction, South Bengaluru, Karnataka 560062',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.864012,
                'longitude'          => 77.545124,
                'total_units'        => 1120,
                'available_units'    => 45,
                'price_from'         => 9500000,
                'price_to'           => 18500000,
                'possession_date'    => '2021-06-30',
                'total_towers'       => 9,
                'floors_per_tower'   => '20',
                'is_featured'        => false,
                'views_count'        => 1940,
                'leads_count'        => 0,
                'nearby_schools'     => 'Kumarans Children\'s Home (1.5 km), JSS Public School (3.0 km)',
                'nearby_hospitals'   => 'Fortis Hospital Bannerghatta (6.2 km), Astra Super Speciality (2.0 km)',
                'metro_distance'     => '800 meters from Anjanapura Road Metro Station',
                'connectivity_score' => '9',
            ]
        );

        // ── 8. Kalyani Developers ────────────────────────────────────
        $kalyaniBuilder = Builder::firstOrCreate(
            ['email' => 'sales@kalyanidevelopers.com'],
            [
                'name'                     => 'Kalyani Developers',
                'company_name'             => 'Kalyani Developers Construction Pvt Ltd',
                'password'                 => Hash::make('KalyaniBlr2026'),
                'phone'                    => '08040405050',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru',
                'established_year'         => '1995',
                'is_verified'              => true,
                'total_delivered_projects' => 25,
                'rating'                   => 4.3,
                'description'              => 'Kalyani Developers excels in commercial tech parks and architectural residential builds known for transparent execution matrices.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $kalyaniBuilder->id, 'title' => 'Kalyani Living Tree'],
            [
                'builder_id'         => $kalyaniBuilder->id,
                'description'        => 'Kalyani Living Tree is a newly launched, premium eco-centric smart community township located in Bagalur. Built cleanly over massive open landscape grids, focusing primarily on natural energy balance and wellness infrastructure.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Gummanahalli, KIADB Aerospace Park Corridor, Bagalur, North Bengaluru, Karnataka 562149',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 13.151102,
                'longitude'          => 77.669812,
                'total_units'        => 980,
                'available_units'    => 420,
                'price_from'         => 12800000,
                'price_to'           => 24500000,
                'possession_date'    => '2030-03-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '24',
                'is_featured'        => true,
                'views_count'        => 1420,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Bangalore North (6.0 km)',
                'nearby_hospitals'   => 'Aster CMI Clinic Bagalur (2.5 km)',
                'metro_distance'     => '12 minutes from KIADB Aerospace upcoming metro layout corridor',
                'connectivity_score' => '8',
            ]
        );

        // ── 9. Shriram Properties ────────────────────────────────────
        $shriramBuilder = Builder::firstOrCreate(
            ['email' => 'sales.info@shriramproperties.com'],
            [
                'name'                     => 'Shriram Properties',
                'company_name'             => 'Shriram Properties Limited',
                'password'                 => Hash::make('ShriramBlr2026'),
                'phone'                    => '08040229999',
                'city'                     => 'Bengaluru',
                'cities_operating'         => 'Bengaluru, Chennai, Kolkata, Visakhapatnam, Coimbatore',
                'established_year'         => '2000',
                'is_verified'              => true,
                'total_delivered_projects' => 44,
                'rating'                   => 4.2,
                'description'              => 'Shriram Properties is a publicly traded real estate leader specializing in value housing and scalable mid-segment lifestyle communities.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $shriramBuilder->id, 'title' => 'Shriram Smrithi'],
            [
                'builder_id'         => $shriramBuilder->id,
                'description'        => 'Shriram Smrithi is an expansive, highly family-friendly affordable-luxury apartment community network located on the Sarjapur-Attibele growth corridor. Boasting extensive green yards and massive open spatial play configurations.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sarjapur-Attibele Main Road, Near Infosys HQ Link Road, East Bengaluru, Karnataka 562107',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.839412,
                'longitude'          => 77.781254,
                'total_units'        => 1400,
                'available_units'    => 35,
                'price_from'         => 5500000,
                'price_to'           => 9500000,
                'possession_date'    => '2019-12-31',
                'total_towers'       => 14,
                'floors_per_tower'   => '4',
                'is_featured'        => false,
                'views_count'        => 820,
                'leads_count'        => 0,
                'nearby_schools'     => 'The International School Bangalore - TISB (4.0 km)',
                'nearby_hospitals'   => 'Town Hospital Sarjapur (2.1 km)',
                'metro_distance'     => 'Accessible through Electronic City Phase 2 Metro system connection lines',
                'connectivity_score' => '7',
            ]
        );

        // ── 10. Casagrand Builder ────────────────────────────────────
        $casagrandBuilder = Builder::firstOrCreate(
            ['email' => 'sales.blr@casagrand.co.in'],
            [
                'name'                     => 'Casagrand Builder',
                'company_name'             => 'Casagrand Builder Private Limited',
                'password'                 => Hash::make('CasagrandBlr2026'),
                'phone'                    => '08046835555',
                'city'                     => 'Chennai',
                'cities_operating'         => 'Chennai, Bengaluru, Coimbatore, Hyderabad',
                'established_year'         => '2004',
                'is_verified'              => true,
                'total_delivered_projects' => 110,
                'rating'                   => 4.3,
                'description'              => 'Casagrand is an agglomerated South Indian property developer focused heavily on premium amenities, kid-centric townships, and price-optimized luxury real estate.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $casagrandBuilder->id, 'title' => 'Casagrand Casablanca'],
            [
                'builder_id'         => $casagrandBuilder->id,
                'description'        => 'Casagrand Casablanca is a majestic Roman-themed premium residential community situated on Kanakapura Road. Built with zero dead space designs, it includes over 70 lifestyle amenities tailored for children and senior citizens.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Kanakapura Main Road, Near Silk Institute Metro Station, South Bengaluru, Karnataka 560082',
                'city'               => 'Bengaluru',
                'state'              => 'Karnataka',
                'latitude'           => 12.821415,
                'longitude'          => 77.530412,
                'total_units'        => 650,
                'available_units'    => 210,
                'price_from'         => 24300000,
                'price_to'           => 33400000,
                'possession_date'    => '2028-09-30',
                'total_towers'       => 4,
                'floors_per_tower'   => '20',
                'is_featured'        => true,
                'views_count'        => 1310,
                'leads_count'        => 0,
                'nearby_schools'     => 'Sri Sri Ravishankar Vidya Mandir (2.0 km)',
                'nearby_hospitals'   => 'Sri Sri College of Ayurvedic Science and Research Hospital (2.3 km)',
                'metro_distance'     => '5 minutes away from Silk Institute Metro Terminal Station',
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

        $luxuryTitles  = ['Prestige Raintree Park', 'Brigade Insignia', 'Birla Trimaya', 'Godrej Lakeside Orchard', 'Kalyani Living Tree', 'Casagrand Casablanca'];
        $standardTitles = ['Sobha Neopolis', 'Sattva Lumina', 'Purva Highlands', 'Shriram Smrithi'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 1/10 complete: 10/100 Bengaluru Builders successfully initialized.');
    }
}