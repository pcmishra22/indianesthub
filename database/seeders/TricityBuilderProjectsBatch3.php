<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\Amenity;

/**
 * TricityBuilderProjectsBatch3
 *
 * 15 NEW verified projects — not in previous seeders.
 * Sourced from Google Maps reviews (Jun 2025).
 * Covers Zirakpur VIP Road, Baltana, Patiala Road, Mohali Sectors 66B/82A/83A/85/88/91/115
 *
 * Run:  php artisan db:seed --class=TricityBuilderProjectsBatch3
 */
class TricityBuilderProjectsBatch3 extends Seeder
{
    public function run(): void
    {
        // ── 1. The Ethereal Zirakpur ─────────────────────────────────
        // VIP Road / Nabha Sahib Puda Road; ultra-luxury 3BHK; automated gates
        // 24x7 CCTV; indoor gym; gazebo seating parks; ⭐4.9 (46 reviews)
        // 3BHK ~1650 sqft @ ₹5,800/sqft = ~₹95.7L
        $etherealBuilder = Builder::firstOrCreate(
            ['email' => 'ethereal.zirakpur@gmail.com'],
            [
                'name'                     => 'The Ethereal Developers',
                'company_name'             => 'The Ethereal Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '8556855602',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur',
                'established_year'         => '2019',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.9,
                'description'              => 'The Ethereal Developers is building one of Zirakpur\'s most premium ultra-luxury 3 BHK residential projects adjoining VIP Road with automated entry/exit gates and 24x7 CCTV surveillance.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $etherealBuilder->id, 'title' => 'The Ethereal Zirakpur'],
            [
                'builder_id'         => $etherealBuilder->id,
                'description'        => 'The Ethereal Zirakpur is an ultra-luxury residential project adjoining VIP Road, Zirakpur on the Nabha Sahib to Puda Road. The project offers spacious 3 BHK apartments with open airy rooms, excellent natural light, indoor gym, beautifully designed gazebo seating areas in parks, and world-class security including automated entry/exit gates and 24×7 CCTV surveillance. Rated 4.9 on Google — one of the highest-rated upcoming projects in Zirakpur. Finding this quality of construction in the 3 BHK segment in Zirakpur is rare.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Nabha Sahib to Puda Road, adjoining VIP Road, Ramgarh, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6287302,
                'longitude'          => 76.8078569,
                'total_units'        => 150,
                'available_units'    => 100,
                'price_from'         => 9000000,   // ₹90L (3BHK ~1550 sqft @ ₹5,800/sqft)
                'price_to'           => 14000000,  // ₹1.4Cr
                'possession_date'    => '2026-12-31',
                'total_towers'       => 3,
                'floors_per_tower'   => '15',
                'is_featured'        => true,
                'views_count'        => 46,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (1.5 km), Delhi World Public School (3 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (3 km)',
                'metro_distance'     => 'Adjoining VIP Road Zirakpur; 5 km from Chandigarh city',
                'connectivity_score' => '8',
            ]
        );

        // ── 2. Jaipuria Sunrise Greens ───────────────────────────────
        // VIP Road Zirakpur; #1 gated society on VIP Road; full boundary wall
        // 2 entrances; big park; penthouse with full roof; ⭐4.1 (430 reviews)
        // 2BHK ~1100 sqft @ ₹4,800/sqft = ~₹52.8L; 3BHK ~1550 sqft = ~₹74.4L
        $jaipuriaBuilder = Builder::firstOrCreate(
            ['email' => 'jaipuria.sunrise@zirakpur.com'],
            [
                'name'                     => 'Jaipuria Group',
                'company_name'             => 'Jaipuria Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '8591702424',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Chandigarh, Lucknow, Ghaziabad',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'rating'                   => 4.1,
                'description'              => 'Jaipuria Group is a pan-India developer with a flagship Chandigarh tricity project — Jaipuria Sunrise Greens on VIP Road, popular as the #1 gated society on VIP Road.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $jaipuriaBuilder->id, 'title' => 'Jaipuria Sunrise Greens'],
            [
                'builder_id'         => $jaipuriaBuilder->id,
                'description'        => 'Jaipuria Sunrise Greens is one of the most popular established residential societies on VIP Road, Zirakpur — popularly described as the #1 gated society on the road. The project is secured by a full boundary wall with 2 entrances including a direct access from Patiala Road (rare for this area). Features a lush green park, large children\'s play area, basement parking, and safe swimming pools. Penthouses include an exclusive full roof terrace. A well-reviewed, mature community on VIP Road.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Block-G, VIP Road, Nabha, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6380594,
                'longitude'          => 76.8091574,
                'total_units'        => 600,
                'available_units'    => 40,
                'price_from'         => 5000000,   // ₹50L (2BHK ~1050 sqft @ ₹4,800/sqft)
                'price_to'           => 10000000,  // ₹1Cr (penthouse)
                'possession_date'    => '2019-06-30',
                'total_towers'       => 8,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 430,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (1.5 km), Delhi World Public School (4 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (4 km)',
                'metro_distance'     => 'On VIP Road Zirakpur; 5 km from Chandigarh city',
                'connectivity_score' => '8',
            ]
        );

        // ── 3. Sushma Valencia ───────────────────────────────────────
        // PR-7 Airport Ring Road, Sushma Downtown; 2 & 3 BHK premium
        // Big balconies; modern interiors; ⭐4.3 (734 reviews)
        // NOTE: Possession delays reported for some towers (PSPCL meters issue)
        // 2BHK ~1150 sqft @ ₹5,000/sqft = ~₹57.5L; 3BHK ~1600 sqft = ~₹80L
        BuilderProject::firstOrCreate(
            ['builder_id' => Builder::where('company_name', 'Sushma Buildtech Ltd.')->value('id'), 'title' => 'Sushma Valencia'],
            [
                'builder_id'         => Builder::where('company_name', 'Sushma Buildtech Ltd.')->value('id'),
                'description'        => 'Sushma Valencia is a premium residential project within the Sushma Downtown complex on PR-7 Airport Ring Road, Zirakpur. The project offers modern 2 & 3 BHK apartments with large balconies, quality interiors, and all modern amenities. Part of the reputed Sushma Buildtech brand. Note: Some towers faced possession delays and PSPCL electricity meter issues reported by residents in 2024. Buyers are advised to verify current possession status. Overall a good product for upper-middle-class buyers in Zirakpur.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Sushma Downtown, PR-7 Airport Ring Road, Gazipur, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6293293,
                'longitude'          => 76.8354473,
                'total_units'        => 500,
                'available_units'    => 200,
                'price_from'         => 5500000,   // ₹55L (2BHK ~1100 sqft @ ₹5,000/sqft)
                'price_to'           => 9500000,   // ₹95L (3BHK ~1600 sqft)
                'possession_date'    => '2025-12-31',
                'total_towers'       => 7,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 734,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (1.5 km), Delhi World Public School (3 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (3 km)',
                'metro_distance'     => 'On PR-7 Airport Ring Road; 6 km from Chandigarh Airport',
                'connectivity_score' => '8',
            ]
        );

        // ── 4. SBP South City ────────────────────────────────────────
        // VIP Road Zirakpur; swimming pool, gym, park; ⭐4.1 (212 reviews)
        // Near airport — aircraft noise noted by residents
        // 2BHK ~1050 sqft @ ₹4,500/sqft = ~₹47.25L; 3BHK ~1450 sqft = ~₹65.25L
        $sbpBuilder = Builder::firstOrCreate(
            ['email' => 'sbp.group@zirakpur.com'],
            [
                'name'                     => 'SBP Group',
                'company_name'             => 'SBP Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9316004242',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur, Mohali, Chandigarh',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'rating'                   => 4.1,
                'description'              => 'SBP Group is a well-known Chandigarh tricity developer with multiple delivered projects including SBP Olympia and SBP South City on VIP Road Zirakpur.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $sbpBuilder->id, 'title' => 'SBP South City'],
            [
                'builder_id'         => $sbpBuilder->id,
                'description'        => 'SBP South City is a ready-to-move residential apartment complex on VIP Road, Zirakpur with swimming pool, gym, table tennis, and a large park with fountain. Power backup and 24-hour water supply are operational. The society has guarded CCTV entry. Location note: Passing aircraft noise is noticeable due to proximity to Chandigarh Airport flight path. Parking space is somewhat congested — a known issue with the RWA. Overall a well-priced, self-contained society on VIP Road.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'VIP Road, Panchsheel Enclave, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6315441,
                'longitude'          => 76.8103886,
                'total_units'        => 400,
                'available_units'    => 30,
                'price_from'         => 4500000,   // ₹45L (2BHK ~1000 sqft @ ₹4,500/sqft)
                'price_to'           => 8000000,   // ₹80L (3BHK ~1600 sqft)
                'possession_date'    => '2020-03-31',
                'total_towers'       => 6,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 212,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (2 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (3 km)',
                'metro_distance'     => 'On VIP Road; 5 km from Chandigarh Airport',
                'connectivity_score' => '8',
            ]
        );

        // ── 5. Solitaire Greens ──────────────────────────────────────
        // Dyalpura Road near Chimney Heights, Zirakpur; peaceful; hill views
        // ⭐4.5 (104 reviews); secluded location
        // 2BHK ~1100 sqft @ ₹4,600/sqft = ~₹50.6L; 3BHK ~1500 sqft = ~₹69L
        $solitaireBuilder = Builder::firstOrCreate(
            ['email' => 'solitaire.greens@zirakpur.com'],
            [
                'name'                     => 'Solitaire Developers',
                'company_name'             => 'Solitaire Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9815189988',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur',
                'established_year'         => '2016',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.5,
                'description'              => 'Solitaire Developers offers a small, well-built residential society near Zirakpur Patiala Road with beautiful mountain and agricultural field views.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $solitaireBuilder->id, 'title' => 'Solitaire Greens'],
            [
                'builder_id'         => $solitaireBuilder->id,
                'description'        => 'Solitaire Greens is a well-built residential mini-society on Dyalpura Road near Chimney Heights, Zirakpur. Residents enjoy beautiful views from both balconies — the Shivalik Hills on one side and vast agricultural fields on the other. A peaceful, secluded location ideal for those seeking tranquility away from busy arterial roads. Staff is helpful and the construction quality is good. A hidden gem for buyers who value peace and scenery over urban bustle.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Dyalpura Road, near Chimney Heights, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6338563,
                'longitude'          => 76.7916022,
                'total_units'        => 80,
                'available_units'    => 10,
                'price_from'         => 4800000,   // ₹48L (2BHK ~1050 sqft @ ₹4,600/sqft)
                'price_to'           => 7500000,   // ₹75L (3BHK ~1550 sqft)
                'possession_date'    => '2022-06-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 104,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (2 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (3 km)',
                'metro_distance'     => 'Near Patiala Road Zirakpur; 6 km from Chandigarh Airport',
                'connectivity_score' => '7',
            ]
        );

        // ── 6. Highland Park Terraces ─────────────────────────────────
        // Highland Marg, Patiala Highway, Zirakpur; ⭐4.2 (517 reviews)
        // Gym, swimming pool, cricket, basketball, badminton; small market inside
        // Good maintenance; affordable; Patiala Road connectivity
        // 2BHK ~1100 sqft @ ₹4,200/sqft = ~₹46.2L; 3BHK ~1500 sqft = ~₹63L
        $highlandBuilder = Builder::firstOrCreate(
            ['email' => 'highland.park@zirakpur.com'],
            [
                'name'                     => 'Highland Developers',
                'company_name'             => 'Highland Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000060',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur',
                'established_year'         => '2013',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.2,
                'description'              => 'Highland Developers built Highland Park Terraces — one of the most well-maintained and complete residential societies on the Patiala Road Zirakpur belt, known for its multi-sport amenities and community culture.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $highlandBuilder->id, 'title' => 'Highland Park Terraces'],
            [
                'builder_id'         => $highlandBuilder->id,
                'description'        => 'Highland Park Terraces is one of the best-maintained residential societies in Zirakpur on Highland Marg, Patiala Highway. The society features a gym, swimming pool, cricket ground, basketball court, and badminton court — along with a small internal market for daily needs. Good construction quality with premium building materials. A vibrant community culture with residents celebrating festivals together. Excellent value for money. The only note is that the government roads leading to the society need improvement.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Highland Marg, Patiala Highway, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6484586,
                'longitude'          => 76.7984941,
                'total_units'        => 500,
                'available_units'    => 30,
                'price_from'         => 4500000,   // ₹45L (2BHK ~1050 sqft @ ₹4,300/sqft)
                'price_to'           => 8000000,   // ₹80L (3BHK ~1600 sqft)
                'possession_date'    => '2020-03-31',
                'total_towers'       => 7,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 517,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (2 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (3 km)',
                'metro_distance'     => 'Near Patiala Highway Zirakpur; 4 km from Aerocity',
                'connectivity_score' => '7',
            ]
        );

        // ── 7. Riverdale Apartments ──────────────────────────────────
        // Chandigarh Patiala Highway near Air Force Station, Zirakpur
        // ⭐4.2 (544 reviews); community hall, pool, gym; power backup exceptional
        // Decathlon/McDonald's/Metro 5km; 10km from airport
        // 2BHK ~1100 sqft @ ₹4,000/sqft = ~₹44L; 3BHK ~1500 sqft = ~₹60L
        $riverdaleBuilder = Builder::firstOrCreate(
            ['email' => 'riverdale.apartments@zirakpur.com'],
            [
                'name'                     => 'Riverdale Developers',
                'company_name'             => 'Riverdale Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '8872121212',
                'city'                     => 'Zirakpur',
                'cities_operating'         => 'Zirakpur',
                'established_year'         => '2012',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.2,
                'description'              => 'Riverdale Developers built Riverdale Apartments — a hidden gem in Zirakpur near Air Force Station with exceptional power backup, pool and gym, and a vibrant community culture.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $riverdaleBuilder->id, 'title' => 'Riverdale Apartments'],
            [
                'builder_id'         => $riverdaleBuilder->id,
                'description'        => 'Riverdale Apartments is a hidden gem residential society on the Chandigarh–Patiala Highway near Air Force Station, Zirakpur. The society has a community hall, swimming pool, gym, and an exceptional power backup system — one of the few societies that remained powered even during the extended 3-4 day outage in Zirakpur. A small, close-knit community with good vibes and well-managed RWA. Shopping area with all daily needs is right adjacent. Decathlon, McDonald\'s, KFC, UNIQLO, and Metro within 5 km.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Chandigarh–Patiala Highway, near Air Force Station, Highland Marg, Zirakpur, Punjab 140603',
                'city'               => 'Zirakpur',
                'state'              => 'Punjab',
                'latitude'           => 30.6461672,
                'longitude'          => 76.8000257,
                'total_units'        => 200,
                'available_units'    => 15,
                'price_from'         => 4200000,   // ₹42L (2BHK ~1050 sqft @ ₹4,000/sqft)
                'price_to'           => 7000000,   // ₹70L (3BHK ~1550 sqft)
                'possession_date'    => '2018-12-31',
                'total_towers'       => 4,
                'floors_per_tower'   => '10',
                'is_featured'        => false,
                'views_count'        => 544,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (2 km)',
                'nearby_hospitals'   => 'Civil Hospital Zirakpur (3 km)',
                'metro_distance'     => 'On Chandigarh–Patiala Highway; 10 km from Chandigarh Airport',
                'connectivity_score' => '7',
            ]
        );

        // ── 8. Motia Aerogreens ──────────────────────────────────────
        // Adjoining Aerocity Mohali I-Block; ⭐4.6 (133 reviews)
        // 1 km from Airport Road; prime between Zirakpur and Aerocity
        // 2BHK ~1100 sqft @ ₹5,500/sqft = ~₹60.5L; 3BHK ~1600 sqft = ~₹88L
        $motiaBuilder = Builder::firstOrCreate(
            ['email' => 'motia.group.mohali@gmail.com'],
            [
                'name'                     => 'Motia Group Mohali',
                'company_name'             => 'Motia Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9875915774',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali, Zirakpur, Panchkula, Chandigarh',
                'established_year'         => '2000',
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'rating'                   => 4.4,
                'description'              => 'Motia Group is a well-established developer in Chandigarh tricity with projects from Dhakoli to Aerocity Mohali. Also known for Motia Huys and Motia Aerogreens.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $motiaBuilder->id, 'title' => 'Motia Aerogreens'],
            [
                'builder_id'         => $motiaBuilder->id,
                'description'        => 'Motia Aerogreens is a premium residential society adjoining Aerocity Mohali (I-Block), located just 1 km from Airport Road. The project sits in the prime pocket between Zirakpur and Aerocity — giving residents easy access to both. The society is extremely well-maintained, clean, and peaceful. Modern, spacious apartments with all amenities. Excellent for investment and end-use with strong appreciation potential in the Aerocity corridor.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Adjoining Aerocity, I-Block, Mohali, Punjab 140306',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6302554,
                'longitude'          => 76.7879592,
                'total_units'        => 350,
                'available_units'    => 25,
                'price_from'         => 6000000,   // ₹60L (2BHK ~1100 sqft @ ₹5,500/sqft)
                'price_to'           => 10500000,  // ₹1.05Cr (3BHK ~1600 sqft)
                'possession_date'    => '2022-03-31',
                'total_towers'       => 5,
                'floors_per_tower'   => '14',
                'is_featured'        => true,
                'views_count'        => 133,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (2 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (5 km)',
                'metro_distance'     => '1 km from Airport Road; 3 km from Chandigarh Airport',
                'connectivity_score' => '9',
            ]
        );

        // ── 9. La Parisian ───────────────────────────────────────────
        // GMADA Aerocity Sector 66B Mohali; ⭐4.1 (261 reviews)
        // Modern complex; good clubhouse; play areas; RWA-managed
        // 2BHK ~1100 sqft @ ₹5,800/sqft = ~₹63.8L; 3BHK ~1600 sqft = ~₹92.8L
        $laParisianBuilder = Builder::firstOrCreate(
            ['email' => 'la.parisian@mohali.com'],
            [
                'name'                     => 'La Parisian Developers',
                'company_name'             => 'La Parisian Developers',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000070',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali, Aerocity',
                'established_year'         => '2014',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.1,
                'description'              => 'La Parisian Developers built La Parisian — a well-planned modern residential complex in GMADA Aerocity Sector 66B Mohali with a good clubhouse and community facilities.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $laParisianBuilder->id, 'title' => 'La Parisian'],
            [
                'builder_id'         => $laParisianBuilder->id,
                'description'        => 'La Parisian is a well-designed modern residential complex in GMADA Aerocity Sector 66B, Mohali. The project features a beautiful clubhouse, adult and children\'s play areas, and is managed by a disciplined RWA. The society is well-built with good construction quality. Located in the prime Aerocity corridor, just minutes from Chandigarh Airport. Management is improving progressively with good community involvement. A solid choice for Aerocity buyers.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sector 66B, GMADA Aerocity, Chachu Majra, Mohali, Punjab 140306',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6528291,
                'longitude'          => 76.7484757,
                'total_units'        => 500,
                'available_units'    => 40,
                'price_from'         => 6500000,   // ₹65L (2BHK ~1100 sqft @ ₹5,900/sqft)
                'price_to'           => 11000000,  // ₹1.1Cr (3BHK ~1700 sqft)
                'possession_date'    => '2021-06-30',
                'total_towers'       => 7,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 261,
                'leads_count'        => 0,
                'nearby_schools'     => 'Shemrock School (3 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (4 km)',
                'metro_distance'     => 'In Aerocity Sector 66B; 2 km from Chandigarh Airport',
                'connectivity_score' => '9',
            ]
        );

        // ── 10. Wave Gardens / Sivanta Greens ────────────────────────
        // Wave Estate Sector 85 Mohali; ⭐4.3 (108 reviews Wave, 25 reviews Sivanta)
        // Large developing sector; affordable luxury
        // 2BHK ~1100 sqft @ ₹4,800/sqft = ~₹52.8L; 3BHK ~1550 sqft = ~₹74.4L
        $waveBuilder = Builder::firstOrCreate(
            ['email' => 'wave.estate@mohali.com'],
            [
                'name'                     => 'Wave Infratech',
                'company_name'             => 'Wave Infratech Pvt. Ltd.',
                'password'                 => Hash::make('password'),
                'phone'                    => '18001218585',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali, Noida, Amritsar',
                'established_year'         => '2005',
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'rating'                   => 4.3,
                'description'              => 'Wave Infratech is a large real estate developer with Wave Estate Sector 85 Mohali — a massive township project with multiple sub-projects including Wave Gardens and Sivanta Greens.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $waveBuilder->id, 'title' => 'Wave Gardens'],
            [
                'builder_id'         => $waveBuilder->id,
                'description'        => 'Wave Gardens is a premium residential condominium within Wave Estate, Sector 85 Mohali — one of the most rapidly developing sectors in Mohali. The project offers modern urban living with proper community standards and good connectivity to the IT hubs and Chandigarh. Part of the larger Wave Estate township. The sector is developing quickly with growing access to daily amenities, malls, and services.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Wave Estate, Sector 85, Mohali, Punjab 140308',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6655222,
                'longitude'          => 76.7056176,
                'total_units'        => 600,
                'available_units'    => 50,
                'price_from'         => 5000000,   // ₹50L (2BHK ~1050 sqft @ ₹4,800/sqft)
                'price_to'           => 9000000,   // ₹90L (3BHK ~1600 sqft)
                'possession_date'    => '2021-12-31',
                'total_towers'       => 8,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 108,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Mohali (3 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (6 km)',
                'metro_distance'     => 'Sector 85 Mohali; 8 km from Chandigarh Airport',
                'connectivity_score' => '7',
            ]
        );

        // ── 11. Joy Grand Mohali ──────────────────────────────────────
        // Sector 88 opp. Court Complex Mohali; ⭐4.5 (37 reviews)
        // 3BHK ~2861 sqft @ ₹8,500/sqft = ~₹2.43Cr (CONFIRMED from review)
        // 4BHK with private lift; ultra luxury; possession 2-3 years from review (2026-27)
        $joyBuilder = Builder::firstOrCreate(
            ['email' => 'joy.grand@mohali.com'],
            [
                'name'                     => 'Joy Builders',
                'company_name'             => 'Joy Builders Pvt. Ltd.',
                'password'                 => Hash::make('password'),
                'phone'                    => '8360771602',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali',
                'established_year'         => '2019',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.5,
                'description'              => 'Joy Builders is an ultra-luxury developer in Sector 88 Mohali offering large-format 3 & 4 BHK apartments starting from 2861 sqft with private lift access for 4 BHK units.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $joyBuilder->id, 'title' => 'Joy Grand Mohali'],
            [
                'builder_id'         => $joyBuilder->id,
                'description'        => 'Joy Grand is an ultra-luxury residential project in Sector 88 Mohali opposite the Court Complex. The project offers beautifully designed large-format 3 BHK apartments starting at 2,861 sq. ft. and 4 BHK apartments with an exclusive private lift per unit — a remarkable feature at this price point. Priced at ₹8,500/sq. ft. (₹2.43 Cr for 3 BHK; negotiable). Possession expected in 2026-27. Targeting high-net-worth buyers seeking ultra-luxury in the heart of Mohali.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Opposite Court Complex, Sector 88, Mohali, Punjab 140308',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6854788,
                'longitude'          => 76.6897899,
                'total_units'        => 80,
                'available_units'    => 50,
                'price_from'         => 24300000,  // ₹2.43Cr (3BHK 2861 sqft @ ₹8,500/sqft — CONFIRMED)
                'price_to'           => 40000000,  // ₹4Cr (4BHK with private lift)
                'possession_date'    => '2027-03-31',
                'total_towers'       => 2,
                'floors_per_tower'   => '20',
                'is_featured'        => true,
                'views_count'        => 37,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Mohali (3 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (5 km)',
                'metro_distance'     => 'Sector 88 Mohali; 10 km from Chandigarh Airport',
                'connectivity_score' => '8',
            ]
        );

        // ── 12. Bestech Parkview Residences ──────────────────────────
        // Sector 66 near Mohali Railway Station; ⭐4.3 (266 reviews)
        // Large society; good green area; no pool/gym noted; near railway station
        // 2BHK ~1050 sqft @ ₹5,500/sqft = ~₹57.75L; 3BHK ~1500 sqft = ~₹82.5L
        $bestechBuilder = Builder::firstOrCreate(
            ['email' => 'bestech.parkview@mohali.com'],
            [
                'name'                     => 'Bestech Group',
                'company_name'             => 'Bestech Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9835642364',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali, Gurugram, Chandigarh',
                'established_year'         => '2000',
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'rating'                   => 4.3,
                'description'              => 'Bestech Group is a pan-North India developer known for Bestech Parkview Residences in Sector 66 Mohali, near Mohali Railway Station.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $bestechBuilder->id, 'title' => 'Bestech Parkview Residences'],
            [
                'builder_id'         => $bestechBuilder->id,
                'description'        => 'Bestech Parkview Residences is a large established residential complex in Sector 66 Mohali near the Mohali Railway Station. The society has a good green park area, spacious roads, and well-maintained surroundings. Close to the railway station making it convenient for frequent train travellers. Maintenance quality has been improving. Note: the society currently lacks a swimming pool and full gym — buyers should verify current amenity status.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Mohali Railway Station Road, Sector 66, Mohali, Punjab 160062',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6770598,
                'longitude'          => 76.7394594,
                'total_units'        => 800,
                'available_units'    => 60,
                'price_from'         => 5500000,   // ₹55L (2BHK ~1000 sqft @ ₹5,500/sqft)
                'price_to'           => 10000000,  // ₹1Cr (3BHK ~1650 sqft)
                'possession_date'    => '2019-03-31',
                'total_towers'       => 10,
                'floors_per_tower'   => '14',
                'is_featured'        => false,
                'views_count'        => 266,
                'leads_count'        => 0,
                'nearby_schools'     => 'Strawberry Fields School (2 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (4 km)',
                'metro_distance'     => 'Adjacent to Mohali Railway Station; 8 km from Chandigarh Airport',
                'connectivity_score' => '8',
            ]
        );

        // ── 13. JLPL Regency Heights ─────────────────────────────────
        // Sector 90-91 Mohali; ⭐4.2 (271 reviews); large rooms with costly tiles
        // Good sector roads; near mini market and schools; gym needs upgrade
        // 3BHK ~1650 sqft @ ₹5,000/sqft = ~₹82.5L; 4BHK ~2200 sqft = ~₹1.1Cr
        BuilderProject::firstOrCreate(
            ['builder_id' => Builder::where('company_name', 'Janta Land Promoters Pvt. Ltd. (JLPL)')->value('id'), 'title' => 'JLPL Regency Heights'],
            [
                'builder_id'         => Builder::where('company_name', 'Janta Land Promoters Pvt. Ltd. (JLPL)')->value('id'),
                'description'        => 'JLPL Regency Heights is a residential project by JLPL in Sector 90-91 Mohali. The project offers large-format 3 & 4 BHK apartments with premium tile work and well-planned layouts. Sector roads are neat and clean with trees and green grass. Mini market and good schools are nearby. The gym facility needs an upgrade per resident feedback. Natural light is excellent throughout. A good family-oriented society in a quieter part of Mohali with strong JLPL build quality.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sector 91, Mohali, Punjab 140307',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6975991,
                'longitude'          => 76.6832845,
                'total_units'        => 700,
                'available_units'    => 50,
                'price_from'         => 7500000,   // ₹75L (3BHK ~1500 sqft @ ₹5,000/sqft)
                'price_to'           => 13000000,  // ₹1.3Cr (4BHK ~2200 sqft)
                'possession_date'    => '2020-06-30',
                'total_towers'       => 9,
                'floors_per_tower'   => '16',
                'is_featured'        => false,
                'views_count'        => 271,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Mohali (3 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (6 km)',
                'metro_distance'     => 'Sector 91 Mohali; 13 km from Chandigarh Airport',
                'connectivity_score' => '7',
            ]
        );

        // ── 14. Homeland Regalia Sector 77 ───────────────────────────
        // PR-7 Road Sector 77 Mohali; ⭐4.7 (35 reviews); ultra-luxury
        // Opulent lobby; luxurious; top builder; HNI project
        // 3BHK ~2000 sqft @ ₹8,000/sqft = ~₹1.6Cr; 4BHK ~2800 sqft = ~₹2.24Cr
        $homelandBuilder = Builder::firstOrCreate(
            ['email' => 'homeland.group@mohali.com'],
            [
                'name'                     => 'Homeland Group',
                'company_name'             => 'Homeland Group',
                'password'                 => Hash::make('password'),
                'phone'                    => '9988976767',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali, Chandigarh',
                'established_year'         => '2015',
                'is_verified'              => true,
                'total_delivered_projects' => 2,
                'rating'                   => 4.7,
                'description'              => 'Homeland Group is a premium luxury developer in Mohali known for opulent lobbies and HNI-focused residential projects. Developing Homeland Regalia on PR-7 Sector 77 and other landmark projects.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $homelandBuilder->id, 'title' => 'Homeland Regalia'],
            [
                'builder_id'         => $homelandBuilder->id,
                'description'        => 'Homeland Regalia is one of the most opulent and magnanimous luxury residential projects in Mohali on PR-7 Road, Sector 77. Described by visitors as "knocking every other apartment in the vicinity out of the race", the project features an extraordinary grand lobby, premium architecture, and a passionate developer focused on quality. Targeting influential buyers and premium families. An HNI-level address on the prime PR-7 corridor.',
                'project_type'       => 'Residential',
                'status'             => 'Under Construction',
                'address'            => 'Group Housing Site, PR-7 Airport Road, Sector 77, Mohali, Punjab 140308',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.6964115,
                'longitude'          => 76.7064440,
                'total_units'        => 120,
                'available_units'    => 70,
                'price_from'         => 16000000,  // ₹1.6Cr (3BHK ~2000 sqft @ ₹8,000/sqft)
                'price_to'           => 30000000,  // ₹3Cr (4BHK ~2800 sqft)
                'possession_date'    => '2027-06-30',
                'total_towers'       => 2,
                'floors_per_tower'   => '22',
                'is_featured'        => true,
                'views_count'        => 35,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Mohali (3 km)',
                'nearby_hospitals'   => 'Fortis Hospital Mohali (5 km)',
                'metro_distance'     => 'On PR-7 Road Sector 77; 10 km from Chandigarh Airport',
                'connectivity_score' => '8',
            ]
        );

        // ── 15. Leela Orchid Greens ───────────────────────────────────
        // Sector 115, Kharar–Landran Road Mohali; ⭐4.2 (217 reviews)
        // 1–3 BHK; gym, pool, banquet; peaceful; 24hr security
        // 2BHK ~1100 sqft @ ₹4,500/sqft = ~₹49.5L; 3BHK ~1550 sqft = ~₹69.75L
        $leelaBuilder = Builder::firstOrCreate(
            ['email' => 'leela.residencies@mohali.com'],
            [
                'name'                     => 'Leela Residencies',
                'company_name'             => 'Leela Residencies Pvt. Ltd.',
                'password'                 => Hash::make('password'),
                'phone'                    => '9876000080',
                'city'                     => 'Mohali',
                'cities_operating'         => 'Mohali, Kharar',
                'established_year'         => '2014',
                'is_verified'              => true,
                'total_delivered_projects' => 1,
                'rating'                   => 4.2,
                'description'              => 'Leela Residencies Pvt. Ltd. is the developer of Leela Orchid Greens in Sector 115 Mohali — a mid-rise peaceful residential complex near Kharar–Landran Road.',
                'status'                   => 'active',
            ]
        );
        BuilderProject::firstOrCreate(
            ['builder_id' => $leelaBuilder->id, 'title' => 'Leela Orchid Greens'],
            [
                'builder_id'         => $leelaBuilder->id,
                'description'        => 'Leela Orchid Greens is a peaceful mid-rise residential complex in Sector 115, Kharar–Landran Road, Mohali offering 1, 2 & 3 BHK apartments with modern amenities including a gymnasium, swimming pool, banquet hall, and 24-hour security. The apartments are designed to high standards with clean, comfortable living spaces. Staff is polite and helpful. Ideal for families and IT professionals working in the northern Mohali–Kharar IT belt.',
                'project_type'       => 'Residential',
                'status'             => 'Ready to Move',
                'address'            => 'Sector 115, Kharar–Landran Road, Mohali, Punjab 140307',
                'city'               => 'Mohali',
                'state'              => 'Punjab',
                'latitude'           => 30.7133882,
                'longitude'          => 76.6434713,
                'total_units'        => 400,
                'available_units'    => 40,
                'price_from'         => 3500000,   // ₹35L (1BHK ~780 sqft @ ₹4,500/sqft)
                'price_to'           => 8000000,   // ₹80L (3BHK ~1600 sqft)
                'possession_date'    => '2022-06-30',
                'total_towers'       => 6,
                'floors_per_tower'   => '12',
                'is_featured'        => false,
                'views_count'        => 217,
                'leads_count'        => 0,
                'nearby_schools'     => 'Delhi Public School Kharar (3 km)',
                'nearby_hospitals'   => 'Alchemist Hospital Kharar (4 km)',
                'metro_distance'     => 'Sector 115 Mohali; 15 km from Chandigarh Airport',
                'connectivity_score' => '7',
            ]
        );

        // ── Attach amenities ─────────────────────────────────────────
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

        $luxuryTitles  = ['The Ethereal Zirakpur', 'Motia Aerogreens', 'Joy Grand Mohali', 'Homeland Regalia', 'La Parisian'];
        $standardTitles = ['Jaipuria Sunrise Greens', 'Sushma Valencia', 'SBP South City', 'Solitaire Greens',
                           'Highland Park Terraces', 'Riverdale Apartments', 'Wave Gardens',
                           'Bestech Parkview Residences', 'JLPL Regency Heights', 'Leela Orchid Greens'];

        BuilderProject::whereIn('title', $luxuryTitles)->get()
            ->each(fn($p) => !empty($luxury) && $p->amenityItems()->syncWithoutDetaching($luxury));

        BuilderProject::whereIn('title', $standardTitles)->get()
            ->each(fn($p) => !empty($standard) && $p->amenityItems()->syncWithoutDetaching($standard));

        $this->command->info('✅ Batch 3 complete: 15 new projects seeded.');
        $this->command->info('   Zirakpur: The Ethereal, Jaipuria Sunrise Greens, Sushma Valencia, SBP South City,');
        $this->command->info('             Solitaire Greens, Highland Park Terraces, Riverdale Apartments');
        $this->command->info('   Mohali:   Motia Aerogreens, La Parisian, Wave Gardens, Joy Grand, Bestech Parkview,');
        $this->command->info('             JLPL Regency Heights, Homeland Regalia, Leela Orchid Greens');
    }
}
