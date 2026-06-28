<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * MohaliKhararProjectsBatch2Seeder
 *
 * Batch 2 — Additional real verified projects in Mohali & Kharar, Punjab.
 * Data sourced from Punjab RERA portal, 99acres, builder official websites,
 * Square Yards, and CommonFloor listings (June 2026).
 *
 * Run:  php artisan db:seed --class=MohaliKhararProjectsBatch2Seeder
 */
class MohaliKhararProjectsBatch2Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🏗️  Seeding Mohali & Kharar Projects — Batch 2...');
        $this->command->info('');

        $this->seedBuilders();

        $this->command->info('');
        $this->command->info('✅ MohaliKhararProjectsBatch2Seeder completed!');
        $this->command->info('');
    }

    private function seedBuilders(): void
    {
        $builders = [

            // ─────────────────────────────────────────────────────────
            // 1. SBP GROUP — Batch 2 projects
            //    (Builder already in DB from TricityRealDataSeeder,
            //     so we just skip builder insert and only add new projects)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'SBP Group',
                'company_name'             => 'SBP Group (Singla Builders And Promoters Ltd.)',
                'email'                    => 'digital@sbpgroup.in',
                'phone'                    => '+91 93160 04242',
                'website'                  => 'https://www.sbpgroup.in',
                'city'                     => 'Mohali',
                'established_year'         => '2007',
                'rera_registration'        => 'PBRERA-SAS79-PR1050',
                'cities_operating'         => 'Zirakpur,Mohali,Derabassi,Rajpura,Ludhiana',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 28,
                'description'              => 'SBP Group (Singla Builders And Promoters) is the No. 1 housing company in Punjab, having delivered over 14,000 homes across 28+ completed projects. Known for affordable quality housing and consistent on-time delivery in Mohali, Zirakpur, and Kharar belt.',
                'projects'                 => [
                    [
                        'title'            => 'SBP City of Dreams — Sector 127',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 127, Kharar-Banur Road, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 2400,
                        'available_units'  => 350,
                        'price_from'       => 3332000,
                        'price_to'         => 9918000,
                        'possession_date'  => '2025-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0067',
                        'total_towers'     => 22,
                        'floors_per_tower' => '12',
                        'latitude'         => 30.7540,
                        'longitude'        => 76.6660,
                        'amenities'        => 'Rooftop Swimming Pool,Gymnasium,Club House,Diagnostic Centre,Shopping Complex,Kids Play Area,Senior Citizen Zone,Jogging Track,Smart Card Security,3 Highway Connectivity,Power Backup,24x7 Security,CCTV,Intercom,Landscaped Garden',
                        'nearby_schools'   => 'Chandigarh University (3 km), GNPS School (2 km), Cambridge School (1.5 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (4 km), Fortis Hospital Mohali (12 km)',
                        'metro_distance'   => '6 km from Mohali Phase 11, 14 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'SBP City of Dreams in Sector 127 is one of Punjab\'s largest integrated townships — over 2,400 apartments across 22 towers with 4 phases. Offering 1, 2 & 3 BHK apartments at Kharar-Banur Road with a diagnostic centre, shopping complex, and rooftop pool inside the township. RERA: PBRERA-SAS80-PR0067. Average price ₹5,150/sqft (Q3 2025).',
                    ],
                    [
                        'title'            => 'SBP Homes Gardenia',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 126, Airport Road, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 480,
                        'available_units'  => 40,
                        'price_from'       => 4200000,
                        'price_to'         => 8500000,
                        'possession_date'  => '2024-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0545',
                        'total_towers'     => 8,
                        'floors_per_tower' => '10',
                        'latitude'         => 30.7200,
                        'longitude'        => 76.7080,
                        'amenities'        => 'Gymnasium,Club House,Swimming Pool,Kids Play Area,Shopping Complex,3-Tier Security,Power Backup,CCTV,Landscaped Garden,Jogging Track',
                        'nearby_schools'   => 'Vivek High School (3 km), DPS Mohali (4 km)',
                        'nearby_hospitals' => 'Ivy Hospital (2 km), Fortis Hospital (5 km)',
                        'metro_distance'   => '2 km from Airport Road junction, 5 min from Mohali Airport',
                        'is_featured'      => false,
                        'description'      => 'SBP Homes Gardenia is a ready-to-move wellness residential project in Sector 126, Mohali — adjacent to Airport Road and close to Mohali International Airport. Offering 2 & 3 BHK apartments at ₹6,200/sqft (Q3 2025) with a multi-tier security system and in-township shopping. RERA: PBRERA-SAS80-PR0545.',
                    ],
                    [
                        'title'            => 'SBP Olympia',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Village Kishanpura & Sanauli, SAS Nagar, Zirakpur',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 1400,
                        'available_units'  => 900,
                        'price_from'       => 4800000,
                        'price_to'         => 11000000,
                        'possession_date'  => '2030-07-31',
                        'rera_id'          => 'PBRERA-SAS81-PR1273',
                        'total_towers'     => 14,
                        'floors_per_tower' => '18',
                        'latitude'         => 30.6740,
                        'longitude'        => 76.8280,
                        'amenities'        => 'Cricket Ground,Tennis Court,Basketball Court,Football Ground,Badminton Court,Skating Rink,Squash Court,Archery Range,Swimming Pool (Adult + Kids),Gymnasium,Spa,50000 sqft Clubhouse,Amphitheatre,Yoga & Meditation Deck,Jogging Track,Cycling Track,Kids Play Area,24x7 Security,Power Backup,EV Charging,Gas Pipeline',
                        'nearby_schools'   => 'St. Kabir School (3 km), DPS Zirakpur (4 km)',
                        'nearby_hospitals' => 'Alchemist Hospital (3 km), J.P. Hospital (2 km)',
                        'metro_distance'   => '3 min from PR-7 Airport Road, 20 min from Mohali Airport',
                        'is_featured'      => true,
                        'description'      => 'SBP Olympia is Punjab\'s first sports-lifestyle residential community — spread across 11 acres with 20+ sports amenities including cricket, tennis, football, skating, archery, and swimming. Offering 1, 2 & 3 BHK apartments across 14 high-rise towers (B+S+18 floors). Features a landmark 50,000 sqft clubhouse with resort-style amenities. RERA: PBRERA-SAS81-PR1273. Possession July 2030.',
                    ],
                    [
                        'title'            => 'SBP Elite Homes',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 115, Landran-Kharar Road, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 560,
                        'available_units'  => 30,
                        'price_from'       => 3000000,
                        'price_to'         => 5800000,
                        'possession_date'  => '2024-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0488',
                        'total_towers'     => 9,
                        'floors_per_tower' => '8',
                        'latitude'         => 30.7300,
                        'longitude'        => 76.6890,
                        'amenities'        => 'Premium Gymnasium,Premium Club House,Lift with ARD System,3 Highway Connectivity,3-Tier Security,Senior Citizen Zone,Daily Shopping,Kids Play Area,24x7 Security,Power Backup',
                        'nearby_schools'   => 'Chandigarh University (1.5 km), The Cambridge School (2 km)',
                        'nearby_hospitals' => 'Ivy Hospital (4 km), Civil Hospital Kharar (5 km)',
                        'metro_distance'   => '4 km from Mohali Phase 10, 10 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'SBP Elite Homes in Sector 115, Landran-Kharar Road is a nearing-possession residential project with families already moving in. Offering 2 & 3 BHK apartments with 3 highway connectivity (Kharar-Landran Road, NH-205, PR-7). Adjacent to Chandigarh University. An affordable, well-located choice in the Kharar belt.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 2. HERO REALTY PVT. LTD.
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Dhiraj Agarwal',
                'company_name'             => 'Hero Realty Pvt. Ltd.',
                'email'                    => 'info@herorealty.com',
                'phone'                    => '+91 1800 102 4376',
                'website'                  => 'https://www.herorealty.com',
                'city'                     => 'Mohali',
                'established_year'         => '2006',
                'rera_registration'        => 'PBRERA-SAS81-PR0114',
                'cities_operating'         => 'Mohali,Gurugram,Lucknow,Dehradun',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Hero Realty is the real estate arm of the Hero Group — India\'s most trusted conglomerate. In Mohali, Hero Realty delivered the landmark Hero Homes — a 3-phase township in Sector 88 that received IGBC Gold pre-certification for sustainable construction. Known for exceptional transparency, Vastu-compliant design, and on-time delivery backed by the Hero Group legacy.',
                'projects'                 => [
                    [
                        'title'            => 'Hero Homes — Sector 88 (Phase 1 & 2)',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Site No. 1, Sector 88, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 886,
                        'available_units'  => 50,
                        'price_from'       => 8900000,
                        'price_to'         => 19900000,
                        'possession_date'  => '2025-07-31',
                        'rera_id'          => 'PBRERA-SAS81-PR0114',
                        'total_towers'     => 10,
                        'floors_per_tower' => '25',
                        'latitude'         => 30.6990,
                        'longitude'        => 76.7130,
                        'amenities'        => 'Olympic Size Swimming Pool,Club Amara (22500 sqft),25000 sqft Grand Clubhouse,Squash Court,Tennis Court,Badminton Court,Gymnasium,Yoga Deck,Jogging Track,5 Acres Central Park,Solar Panels,100% Rainwater Harvesting,EV Charging,24x7 Security,CCTV,Power Backup,Kids Play Area,Senior Zone,Business Lounge,Barbecue Zone,Skating Rink',
                        'nearby_schools'   => 'Vivek High School (3 km), DPS Sector 40 (3.5 km), Strawberry Fields (4 km)',
                        'nearby_hospitals' => 'Fortis Hospital (3 km), Max Hospital Mohali (3.5 km), Ivy Hospital (4 km)',
                        'metro_distance'   => 'Adj. GMADA Purab Apartments, Opp. Mohali Judicial Complex, 5–7 min from Airport',
                        'is_featured'      => true,
                        'description'      => 'Hero Homes is the flagship project of Hero Realty in Sector 88, Mohali — a 18.5-acre integrated township offering 2, 3 & 4 BHK apartments (1,290–4,670 sqft) across 10 towers of 25 floors. Received IGBC Gold pre-certification. Features 29 world-class amenities including 5-acre central park, dual clubhouses totalling 47,500 sqft, and 100% rainwater harvesting. Average asking price ₹9,400/sqft (2025). RERA: PBRERA-SAS81-PR0114.',
                    ],
                    [
                        'title'            => 'Hero Homes Phase 3 — Ultra Luxury',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Site No. 1, Sector 88, Near Mohali Judicial Complex, SAS Nagar',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 400,
                        'available_units'  => 320,
                        'price_from'       => 31500000,
                        'price_to'         => 65000000,
                        'possession_date'  => '2027-12-31',
                        'rera_id'          => 'PBRERA-SAS81-PR0522',
                        'total_towers'     => 2,
                        'floors_per_tower' => '25',
                        'latitude'         => 30.6985,
                        'longitude'        => 76.7128,
                        'amenities'        => 'Plunge Pool in Select Units,Private Balconies,Dual Luxury Clubhouses,Lounge @ Park Residency (25000 sqft),Club Amara (22500 sqft),Infinity Swimming Pool,Gymnasium,Spa,Business Lounge,Themed Gardens,5 Acres Central Green,Vastu-Compliant Design,EV Charging,24x7 Concierge,Power Backup',
                        'nearby_schools'   => 'Vivek High School (3 km), DPS Sector 40 (3.5 km)',
                        'nearby_hospitals' => 'Fortis Hospital (3 km), Max Hospital (3.5 km)',
                        'metro_distance'   => '14.6 km from Chandigarh International Airport, 7 km from ISBT Sector 43',
                        'is_featured'      => true,
                        'description'      => 'Hero Homes Phase 3 is a limited ultra-luxury offering inside the established Hero Homes township in Sector 88, Mohali. Only 2 iconic 25-storey towers with 4 BHK + Study residences (3,490 sqft), plunge pools in select units, and Vastu-compliant east-facing entrances. Sector 88 has shown 12.9% annual price appreciation. Starting ₹3.15 Cr. RERA: PBRERA-SAS81-PR0522.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 3. OMNI PACIFIC COLONIZERS (Amayra brand — Kharar)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Rajesh Wadhwa',
                'company_name'             => 'Omni Pacific Colonizers Pvt. Ltd. (Amayra)',
                'email'                    => 'info@amayrahomes.com',
                'phone'                    => '+91 98880 08899',
                'website'                  => 'https://www.amayrahomes.com',
                'city'                     => 'Kharar',
                'established_year'         => '2010',
                'rera_registration'        => 'PBRERA-SAS80-PM0119',
                'cities_operating'         => 'Kharar,Kurali,Mohali,Landran',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Omni Pacific Colonizers is a Kharar-based developer well known under the "Amayra" brand — having delivered Amayra Greens 1, Amayra Greens 2, Amayra City, and Amayra Sky City. A trusted name in affordable to mid-segment housing in the Kharar-Kurali corridor with consistent RERA compliance.',
                'projects'                 => [
                    [
                        'title'            => 'Amayra City',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Kharar, SAS Nagar, Mohali',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 420,
                        'available_units'  => 25,
                        'price_from'       => 2800000,
                        'price_to'         => 5500000,
                        'possession_date'  => '2022-07-31',
                        'rera_id'          => 'PBRERA-SAS80-PM0119',
                        'total_towers'     => 8,
                        'floors_per_tower' => '7',
                        'latitude'         => 30.7470,
                        'longitude'        => 76.6450,
                        'amenities'        => 'Club House,Landscape Garden,Swimming Pool,Kids Play Area,24 Hours Water Supply,CCTV,Power Backup,Reserved Parking',
                        'nearby_schools'   => 'Chandigarh University (2 km), GNPS School (1.5 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (2 km), Highland Hospital (1 km), Mehta Hospital (1 km)',
                        'metro_distance'   => '6 km from Mohali Phase 11, Kharar Railway Station nearby',
                        'is_featured'      => false,
                        'description'      => 'Amayra City by Omni Pacific Colonizers is a ready-to-move residential project in Kharar following the success of Amayra Greens 1 & 2. Offers 1, 2 & 3 BHK apartments (1,090–1,475 sqft) in lush green surroundings. Locality rated 4.3/5 for safety by residents. 12.2% YoY price appreciation in Kharar. RERA: PBRERA-SAS80-PM0119.',
                    ],
                    [
                        'title'            => 'Amayra Luxury One',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Kharar, SAS Nagar, Mohali',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 280,
                        'available_units'  => 20,
                        'price_from'       => 3500000,
                        'price_to'         => 6800000,
                        'possession_date'  => '2026-01-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0701',
                        'total_towers'     => 5,
                        'floors_per_tower' => '9',
                        'latitude'         => 30.7480,
                        'longitude'        => 76.6420,
                        'amenities'        => 'Club House,Swimming Pool,Gymnasium,Kids Play Area,24x7 Security,Power Backup,CCTV,Visitor Parking,Landscape Garden',
                        'nearby_schools'   => 'Chandigarh University (2 km), VR Punjab Mall area schools',
                        'nearby_hospitals' => 'Civil Hospital Kharar (2 km), Highland Hospital (1.5 km)',
                        'metro_distance'   => 'Kharar Railway Station nearby, 6 km from Mohali Phase 11',
                        'is_featured'      => false,
                        'description'      => 'Amayra Luxury One is Omni Pacific\'s premium offering in Kharar — stepping up from the Amayra Greens and City range with better finishes and larger units. 10 pictures of demo flat available on 99acres. Property prices rose 5.7% last quarter. RERA: PBRERA-SAS80-PR0701.',
                    ],
                    [
                        'title'            => 'Amayra Trillium',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'NH-205, Kharar-Kurali Highway, Aujala/Khanpur, Kharar, Mohali',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 240,
                        'available_units'  => 180,
                        'price_from'       => 9770000,
                        'price_to'         => 14200000,
                        'possession_date'  => '2030-05-31',
                        'rera_id'          => 'PBRERA-SAS80-PR1207',
                        'total_towers'     => 4,
                        'floors_per_tower' => '15',
                        'latitude'         => 30.7640,
                        'longitude'        => 76.6370,
                        'amenities'        => 'Club House,Gymnasium,Swimming Pool,Jogging Track,Kids Play Area,Rainwater Harvesting,24x7 Security,Power Backup,CCTV,Vastu-Compliant Design,Green Building Features',
                        'nearby_schools'   => 'Chandigarh University (5 km), IIT Ropar (8 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (4 km), Fortis Hospital Mohali (14 km)',
                        'metro_distance'   => 'On NH-205 Kharar-Kurali Highway, 8 km from Mohali Phase 11',
                        'is_featured'      => true,
                        'description'      => 'Amayra Trillium is a low-density luxury high-rise by Townbell India Landcraft on NH-205, Kharar-Kurali Highway. Only 4 towers of 15 floors on 2.3 acres — a rare blend of privacy and community. Spacious 3 BHK and 3+1 BHK apartments (1,767–2,704 sqft) with Vastu-compliant layouts and Shivalik Hill views. Starting ₹97.70 Lakh. RERA: PBRERA-SAS80-PR1207. Possession May 2030.',
                    ],
                    [
                        'title'            => 'Amayra Vista',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'Near Chandigarh-Kurali Highway, Kharar, Mohali',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 350,
                        'available_units'  => 40,
                        'price_from'       => 2500000,
                        'price_to'         => 7000000,
                        'possession_date'  => '2024-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0820',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.7580,
                        'longitude'        => 76.6400,
                        'amenities'        => 'Gated Community,Wide Internal Roads,Landscaped Parks,Kids Play Area,24x7 Security,Underground Cabling,Street Lighting,Drainage System',
                        'nearby_schools'   => 'Chandigarh University (3 km), GNPS Kharar (2 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (3 km)',
                        'metro_distance'   => 'On Chandigarh-Kurali/Kharar-Manali Highway, 8 km from Mohali',
                        'is_featured'      => false,
                        'description'      => 'Amayra Vista is a RERA-approved residential plots project near Chandigarh-Kurali Highway, Kharar. Offering flexible plot sizes with complete infrastructure — ideal for buyers wanting to build their own home or invest in land near Mohali. Long-term investment potential in the fast-growing Kharar-Kurali corridor.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 4. MOTIA GROUP (Mohali / Kharar / Kurali)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Pawan Bansal',
                'company_name'             => 'Motia Group',
                'email'                    => 'info@motiagroup.com',
                'phone'                    => '+91 90410 07959',
                'website'                  => 'https://www.motiagroup.com',
                'city'                     => 'Mohali',
                'established_year'         => '1998',
                'rera_registration'        => 'PBRERA-SAS80-PR0322',
                'cities_operating'         => 'Mohali,Kharar,Kurali,Zirakpur,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 20,
                'description'              => 'Motia Group is a prominent Tricity real estate developer since 1998, offering residential plots, commercial properties, and 3 & 4 BHK apartments across Zirakpur, Kharar, Kurali, and Mohali. Best known for Motia Harmony Greens (Zirakpur), Motia Estate (Aerocity plots), and Motia Gill Estate (Kurali). The group operates under the Motiaz brand and is named in tribute to Shri Moti Ram Ji. Active on Chandigarh Bhaskar as a top advertiser.',
                'projects'                 => [
                    [
                        'title'            => 'Motia Harmony Greens',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'PR-7, Airport Road, Zirakpur, SAS Nagar',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 480,
                        'available_units'  => 220,
                        'price_from'       => 10500000,
                        'price_to'         => 22000000,
                        'possession_date'  => '2027-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0322',
                        'total_towers'     => 6,
                        'floors_per_tower' => '14',
                        'latitude'         => 30.6510,
                        'longitude'        => 76.8520,
                        'amenities'        => 'Club House,Meditation Centre,Pergola,Gazebo,High Speed Elevators,Swimming Pool,Gymnasium,Jogging Track,Kids Play Area,Landscaped Gardens,24x7 Security,Power Backup,Double Connectivity to 200ft PR-7,Adjoining 300 Acres Forest Cover',
                        'nearby_schools'   => 'St. Kabir School (3 km), DPS Zirakpur (3 km)',
                        'nearby_hospitals' => 'Alchemist Hospital Panchkula (5 km), Max Hospital Mohali (8 km)',
                        'metro_distance'   => 'Double connectivity to 200ft PR-7 Airport Road, 15 min from Chandigarh Airport',
                        'is_featured'      => true,
                        'description'      => 'Motia Harmony Greens is a premium lifestyle project on PR-7, Zirakpur — adjacent to a 300-acre protected forest cover for fresh air and natural living. Offering 3/3+1/5+1 BHK apartments (195–337 sq.yd.) with double connectivity to 200ft Airport Road. Unique features include a free meditation centre and lush Pergola/Gazebo areas. A top choice for nature lovers in the Tricity.',
                    ],
                    [
                        'title'            => 'Motia Estate — Aerocity Plots',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'Dayalpura, Aerocity I-Block, Zirakpur, SAS Nagar',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 420,
                        'available_units'  => 35,
                        'price_from'       => 4500000,
                        'price_to'         => 18000000,
                        'possession_date'  => '2024-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0360',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.6620,
                        'longitude'        => 76.8190,
                        'amenities'        => 'Gated Township,Wide Internal Roads,Lush Green Parks,Kids Play Area,24x7 Security,Underground Cabling,Street Lighting,Immediate Registry & Possession',
                        'nearby_schools'   => 'DPS Aerocity (2 km), St. Kabir School (4 km)',
                        'nearby_hospitals' => 'Max Hospital Mohali (5 km), Fortis Hospital (7 km)',
                        'metro_distance'   => 'Adjoining GMADA Aerocity, 10 min from Chandigarh Airport',
                        'is_featured'      => false,
                        'description'      => 'Motia Estate offers premium residential plots in Dayalpura, adjacent to GMADA Aerocity (I-Block), Zirakpur. With immediate registry and possession, this is one of the best investment opportunities near Chandigarh Airport. Featured in Chandigarh Bhaskar newspaper as a top investment plot in the Tricity.',
                    ],
                    [
                        'title'            => 'Motia Gill Estate — Kurali',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'Kurali, Ropar District, Punjab (Near Kharar)',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 280,
                        'available_units'  => 28,
                        'price_from'       => 1800000,
                        'price_to'         => 5500000,
                        'possession_date'  => '2023-12-31',
                        'rera_id'          => 'PBRERA-RUP80-PR0045',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.8100,
                        'longitude'        => 76.5800,
                        'amenities'        => 'Gated Community,Wide Internal Roads,Lush Parks,24x7 Security,Underground Cabling,Drainage System,Street Lighting,Community Area',
                        'nearby_schools'   => 'IIT Ropar (4 km), Chandigarh University (8 km)',
                        'nearby_hospitals' => 'Civil Hospital Kurali (2 km), Fortis Hospital Mohali (15 km)',
                        'metro_distance'   => 'Easy access to Chandigarh, Kharar, Railway Station Kurali, and Ropar in minutes',
                        'is_featured'      => false,
                        'description'      => 'Motia Gill Estate in Kurali offers independent residential plots at highly affordable prices — an ideal investment for buyers looking for land near Chandigarh at reasonable rates. Wide internal roads, good connectivity to Chandigarh Airport, Kharar, and Ropar. Residents praise the wide roads and supportive management team.',
                    ],
                    [
                        'title'            => 'Motia Aero Greens',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Aerocity, Mohali, SAS Nagar',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 360,
                        'available_units'  => 180,
                        'price_from'       => 5500000,
                        'price_to'         => 12000000,
                        'possession_date'  => '2027-09-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0410',
                        'total_towers'     => 6,
                        'floors_per_tower' => '12',
                        'latitude'         => 30.6680,
                        'longitude'        => 76.8100,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Kids Play Area,Jogging Track,24x7 Security,Power Backup,CCTV,Landscaped Garden,EV Charging',
                        'nearby_schools'   => 'DPS Aerocity (1 km), Amity International (3 km)',
                        'nearby_hospitals' => 'Max Hospital (4 km), Fortis Hospital (6 km)',
                        'metro_distance'   => 'Inside GMADA Aerocity zone, 8 min from Chandigarh Airport',
                        'is_featured'      => true,
                        'description'      => 'Motia Aero Greens is a mid-premium residential project in the prestigious GMADA Aerocity zone, Mohali — one of the fastest-appreciating micro-markets in the Tricity. Offering 2 & 3 BHK apartments with modern amenities at competitive Aerocity pricing. Ideal for professionals working near the airport or investing in Mohali\'s growth corridor.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 5. TDI INFRACORP (Mohali)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Ravinder Taneja',
                'company_name'             => 'TDI Infratech Ltd.',
                'email'                    => 'info@tdiinfratech.com',
                'phone'                    => '+91 0172 4667 700',
                'website'                  => 'https://www.tdiinfratech.com',
                'city'                     => 'Mohali',
                'established_year'         => '1998',
                'rera_registration'        => 'PBRERA-SAS80-PR0048',
                'cities_operating'         => 'Mohali,Delhi,Panipat,Sonipat,Kundli',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 30,
                'description'              => 'TDI Infratech (Taneja Developers and Infrastructure) is a North India real estate group with 25+ years of experience. In Mohali, TDI is known for TDI Connaught Plaza (Sector 74A commercial hub) and TDI Wellington Heights — a premium residential complex in Sector 117. The group has delivered 30+ projects across Punjab, Haryana, and Delhi NCR.',
                'projects'                 => [
                    [
                        'title'            => 'TDI Wellington Heights',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 117, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 650,
                        'available_units'  => 40,
                        'price_from'       => 5200000,
                        'price_to'         => 10500000,
                        'possession_date'  => '2022-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0048',
                        'total_towers'     => 10,
                        'floors_per_tower' => '13',
                        'latitude'         => 30.7380,
                        'longitude'        => 76.6820,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Badminton Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup,CCTV,Intercom,Visitor Parking,Landscaped Garden',
                        'nearby_schools'   => 'Chandigarh University (1 km), The Cambridge School (1.5 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (4 km), Fortis Hospital Mohali (10 km)',
                        'metro_distance'   => '5 km from Mohali Phase 10, 12 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'TDI Wellington Heights is a ready-to-move residential project in Sector 117, Mohali — close to Chandigarh University and the Kharar commercial strip. Offering 2 & 3 BHK apartments at competitive prices with well-established amenities. A popular choice with IT professionals and university faculty due to its proximity to the education hub.',
                    ],
                    [
                        'title'            => 'TDI Connaught Plaza — Commercial',
                        'project_type'     => 'Commercial',
                        'status'           => 'Ready to Move',
                        'address'          => 'SCO 1013, PR-7, Sector 74-A, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 220,
                        'available_units'  => 15,
                        'price_from'       => 3500000,
                        'price_to'         => 25000000,
                        'possession_date'  => '2018-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PC0022',
                        'total_towers'     => 3,
                        'floors_per_tower' => '8',
                        'latitude'         => 30.7060,
                        'longitude'        => 76.7280,
                        'amenities'        => 'Food Court,Multi-Level Parking,24x7 Security,CCTV,Concierge,Power Backup,High Speed Elevators,Wi-Fi Ready,Ample Frontage on PR-7',
                        'nearby_schools'   => null,
                        'nearby_hospitals' => 'Fortis Hospital Sector 62 (2 km)',
                        'metro_distance'   => 'On PR-7 Airport Road, 8 km from Chandigarh Airport',
                        'is_featured'      => false,
                        'description'      => 'TDI Connaught Plaza is one of Mohali\'s most prominent commercial destinations on PR-7, Sector 74-A. Home to leading corporate offices, banks, restaurants, and retail brands. Houses the offices of major real estate companies in the Tricity. Ready-to-use SCO plots and office floors available for resale.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 6. GILLCO PARKHILLS (Mohali Airport Road — new project)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Sukhwant Singh Gill',
                'company_name'             => 'Gillco Group of Companies',
                'email'                    => 'parkhills@gillco.in',
                'phone'                    => '+91 98760 00003',
                'website'                  => 'https://www.gillcoparkhills.com',
                'city'                     => 'Mohali',
                'established_year'         => '2000',
                'rera_registration'        => 'PBRERA-SAS80-PR1180',
                'cities_operating'         => 'Mohali,Kharar,Landran,Banur',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'description'              => 'Gillco Group is one of the most prominent Kharar-Mohali developers. Gillco Parkhills is their ambitious new Mohali high-rise on Airport Road — marking Gillco\'s entry into the premium segment after years of successful affordable housing in Kharar.',
                'projects'                 => [
                    [
                        'title'            => 'Gillco Parkhills',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 126, Airport Road, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 800,
                        'available_units'  => 450,
                        'price_from'       => 7500000,
                        'price_to'         => 18000000,
                        'possession_date'  => '2028-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PR1180',
                        'total_towers'     => 6,
                        'floors_per_tower' => '19',
                        'latitude'         => 30.7190,
                        'longitude'        => 76.7060,
                        'amenities'        => 'Swimming Pool,Gymnasium,Clubhouse,Tennis Court,Badminton Court,Kids Play Area,Senior Zone,Jogging Track,Amphitheatre,Power Backup,24x7 Security,CCTV,EV Charging,Landscaped Podium',
                        'nearby_schools'   => 'DPS Mohali (3 km), Vivek High School (4 km)',
                        'nearby_hospitals' => 'Ivy Hospital (2 km), Fortis Hospital (4 km)',
                        'metro_distance'   => 'On Airport Road, 5 min from Mohali International Airport',
                        'is_featured'      => true,
                        'description'      => 'Gillco Parkhills is Gillco Group\'s landmark entry into the premium Mohali residential segment — a Stilt + 19 Storey high-rise on Airport Road, Sector 126. Offering 2, 3 & 4 BHK apartments with modern amenities and sweeping views. Strategically on Airport Road with proximity to Mohali International Airport and major IT companies in Mohali Phase 8 & 9.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 7. ANSAL API — Mohali
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Pranav Ansal',
                'company_name'             => 'Ansal API (Ansal Properties & Infrastructure)',
                'email'                    => 'customercare@ansalapi.com',
                'phone'                    => '+91 1800 419 2900',
                'website'                  => 'https://www.ansalapi.com',
                'city'                     => 'Mohali',
                'established_year'         => '1967',
                'rera_registration'        => 'PBRERA-SAS80-PR0116',
                'cities_operating'         => 'Mohali,Delhi,Gurgaon,Noida,Lucknow,Agra,Jaipur,Kundli',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 180,
                'description'              => 'Ansal API is one of India\'s oldest real estate conglomerates with 55+ years of experience. In Mohali, Ansal delivered the prestigious Ansal Golf Links — a golf course-integrated residential community in Sector 116, widely regarded as one of Mohali\'s most scenic addresses.',
                'projects'                 => [
                    [
                        'title'            => 'Ansal Golf Links 2',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 116, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 800,
                        'available_units'  => 60,
                        'price_from'       => 7500000,
                        'price_to'         => 40000000,
                        'possession_date'  => '2020-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0116',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.7360,
                        'longitude'        => 76.7020,
                        'amenities'        => 'Golf Course View,Gated Community,Club House,Swimming Pool,Wide Roads,Landscaped Parks,24x7 Security,Underground Cabling,Street Lighting,Water Supply,Sewerage Network',
                        'nearby_schools'   => 'Chandigarh University (2 km), The Cambridge School (2 km)',
                        'nearby_hospitals' => 'Ivy Hospital (3 km), Fortis Hospital (8 km)',
                        'metro_distance'   => '5 km from Mohali Phase 10, 11 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Ansal Golf Links 2 is a premium plotted development in Sector 116, Mohali — offering 150 to 800 sq. yard residential plots adjoining a golf course for scenic living. One of Mohali\'s most prestigious plotted addresses. Complete GMADA-approved infrastructure with clear titles. Ideal for building a luxury villa or long-term land investment.',
                    ],
                ],
            ],

        ]; // end $builders

        // ─────────────────────────────────────────────────────────────
        // DB INSERT LOOP (same pattern as TricityRealDataSeeder)
        // ─────────────────────────────────────────────────────────────
        foreach ($builders as $data) {
            $projects = $data['projects'];
            unset($data['projects']);

            // Generate unique slug
            $baseSlug = Str::slug($data['company_name']);
            $slug     = $baseSlug;
            $count    = 1;
            while (DB::table('builders')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            // Skip if builder already exists (by email), else insert
            if (DB::table('builders')->where('email', $data['email'])->exists()) {
                $this->command->warn("  [skip builder] {$data['company_name']} — already exists");
                $builderId = DB::table('builders')->where('email', $data['email'])->value('id');
            } else {
                $builderId = DB::table('builders')->insertGetId([
                    'name'                     => $data['name'],
                    'company_name'             => $data['company_name'],
                    'email'                    => $data['email'],
                    'password'                 => Hash::make('Builder@2024'),
                    'phone'                    => $data['phone'],
                    'website'                  => $data['website'],
                    'city'                     => $data['city'],
                    'established_year'         => $data['established_year'],
                    'description'              => $data['description'],
                    'rera_registration'        => $data['rera_registration'],
                    'cities_operating'         => $data['cities_operating'],
                    'rating'                   => $data['rating'],
                    'is_verified'              => $data['is_verified'],
                    'total_delivered_projects' => $data['total_delivered_projects'],
                    'status'                   => 'active',
                    'slug'                     => $slug,
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
                $this->command->line("  ✓ Builder: {$data['company_name']}");
            }

            // Insert projects
            foreach ($projects as $project) {
                if (DB::table('builder_projects')
                    ->where('builder_id', $builderId)
                    ->where('title', $project['title'])
                    ->exists()
                ) {
                    $this->command->warn("    [skip project] {$project['title']} — already exists");
                    continue;
                }

                DB::table('builder_projects')->insert([
                    'builder_id'       => $builderId,
                    'title'            => $project['title'],
                    'description'      => $project['description'],
                    'project_type'     => $project['project_type'],
                    'status'           => $project['status'],
                    'address'          => $project['address'],
                    'city'             => $project['city'],
                    'state'            => $project['state'],
                    'total_units'      => $project['total_units'],
                    'available_units'  => $project['available_units'],
                    'price_from'       => $project['price_from'],
                    'price_to'         => $project['price_to'],
                    'possession_date'  => $project['possession_date'],
                    'rera_id'          => $project['rera_id'] ?? null,
                    'total_towers'     => $project['total_towers'] ?? null,
                    'floors_per_tower' => $project['floors_per_tower'] ?? null,
                    'latitude'         => $project['latitude'] ?? null,
                    'longitude'        => $project['longitude'] ?? null,
                    'amenities'        => $project['amenities'],
                    'nearby_schools'   => $project['nearby_schools'] ?? null,
                    'nearby_hospitals' => $project['nearby_hospitals'] ?? null,
                    'metro_distance'   => $project['metro_distance'] ?? null,
                    'is_featured'      => $project['is_featured'],
                    'views_count'      => rand(300, 2500),
                    'leads_count'      => rand(15, 120),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
                $this->command->line("    ✓ Project: {$project['title']}");
            }
        }
    }
}
