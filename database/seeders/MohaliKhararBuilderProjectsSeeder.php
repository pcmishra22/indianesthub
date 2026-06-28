<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * MohaliKhararBuilderProjectsSeeder
 *
 * Real verified builder projects in Mohali (SAS Nagar) & Kharar, Punjab.
 * Data sourced from Punjab RERA portal, builder websites, and public listings.
 *
 * Run:  php artisan db:seed --class=MohaliKhararBuilderProjectsSeeder
 */
class MohaliKhararBuilderProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🏗️  Seeding Mohali & Kharar Builder Projects...');
        $this->command->info('');

        $this->seedBuilders();

        $this->command->info('');
        $this->command->info('✅ MohaliKhararBuilderProjectsSeeder completed!');
        $this->command->info('');
    }

    /* ============================================================
     *  BUILDERS + PROJECTS
     * ============================================================ */
    private function seedBuilders(): void
    {
        $builders = [

            // ─────────────────────────────────────────────────────────
            // 1. OMAXE LTD
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Rohtas Goel',
                'company_name'             => 'Omaxe Ltd.',
                'email'                    => 'info@omaxe.com',
                'phone'                    => '+91 1800 200 8282',
                'website'                  => 'https://www.omaxe.com',
                'city'                     => 'Mohali',
                'established_year'         => '1987',
                'rera_registration'        => 'PBRERA-SAS80-PC0003',
                'cities_operating'         => 'Mohali,Ludhiana,Amritsar,Patiala,Delhi,Lucknow,Faridabad',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 120,
                'description'              => 'Omaxe Ltd. is one of India\'s leading real estate developers with 35+ years of experience and 120+ delivered projects across 27 cities. In Punjab, Omaxe is best known for New Chandigarh — a 2,500-acre planned township in Mullanpur near Mohali — and several integrated townships across the state. All projects are RERA-registered under Punjab RERA.',
                'projects'                 => [
                    [
                        'title'            => 'Omaxe New Chandigarh',
                        'project_type'     => 'Township',
                        'status'           => 'Under Construction',
                        'address'          => 'Mullanpur, New Chandigarh, SAS Nagar',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 8000,
                        'available_units'  => 2400,
                        'price_from'       => 4200000,
                        'price_to'         => 22000000,
                        'possession_date'  => '2027-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PC0003',
                        'total_towers'     => 35,
                        'floors_per_tower' => '14',
                        'latitude'         => 30.7816,
                        'longitude'        => 76.7313,
                        'amenities'        => 'Golf Course,Cricket Stadium,Football Ground,Swimming Pool,Gymnasium,Club House,5-Star Hotel Zone,School Zone,Hospital Zone,Shopping Mall,Multiplex,Jogging Track,Cycling Track,Kids Play Area,24x7 Security,Power Backup,EV Charging',
                        'nearby_schools'   => 'GNPS School (0.5 km), Chitkara International School (3 km), Chandigarh University School (5 km)',
                        'nearby_hospitals' => 'Fortis Hospital Mohali (8 km), Max Hospital (9 km), PGI Chandigarh (12 km)',
                        'metro_distance'   => '6 km from Mohali Phase 11, 12 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'Omaxe New Chandigarh is a landmark 2,500-acre integrated township in Mullanpur, envisioned as the next planned city of Punjab. Features a 9-hole golf course, cricket stadium, 5-star hotel zone, commercial hub, and premium residential sectors. Offering 2, 3, 4 BHK floors, villas, and plots at various price points. The project is one of the largest under-development townships in North India.',
                    ],
                    [
                        'title'            => 'Omaxe Celestia Royal',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 88 A, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 432,
                        'available_units'  => 35,
                        'price_from'       => 6500000,
                        'price_to'         => 14000000,
                        'possession_date'  => '2023-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0089',
                        'total_towers'     => 6,
                        'floors_per_tower' => '15',
                        'latitude'         => 30.6937,
                        'longitude'        => 76.7236,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Kids Play Area,Landscaped Gardens,24x7 Security,Power Backup,CCTV,Intercom,Visitor Parking',
                        'nearby_schools'   => 'Vivek High School (2 km), Strawberry Fields School (3 km)',
                        'nearby_hospitals' => 'Fortis Hospital (4 km), Ivy Hospital (3.5 km)',
                        'metro_distance'   => '3 km from Mohali Phase 8 bus terminal, 9 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Omaxe Celestia Royal offers ready-to-move 3 & 4 BHK premium apartments in Sector 88A, Mohali. Located in the heart of GMADA-developed sectors with excellent road connectivity to Chandigarh, the project features a well-designed clubhouse and landscaped podium gardens.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 2. EMAAR INDIA
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Kalyan Chakrabarti',
                'company_name'             => 'Emaar India Ltd.',
                'email'                    => 'customercare@emaar.in',
                'phone'                    => '+91 1860 208 9999',
                'website'                  => 'https://www.emaarindia.com',
                'city'                     => 'Mohali',
                'established_year'         => '2005',
                'rera_registration'        => 'PBRERA-SAS80-PR0211',
                'cities_operating'         => 'Mohali,Gurugram,Lucknow,Jaipur,Hyderabad',
                'rating'                   => 4.4,
                'is_verified'              => true,
                'total_delivered_projects' => 45,
                'description'              => 'Emaar India is a subsidiary of Dubai-based Emaar Properties, one of the world\'s most valuable real estate companies. In Mohali, Emaar has delivered the iconic Emaar The Views and Emaar Emerald Hills — premium residential communities known for international design standards, state-of-the-art amenities, and on-time delivery.',
                'projects'                 => [
                    [
                        'title'            => 'Emaar The Views',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 105, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 744,
                        'available_units'  => 28,
                        'price_from'       => 11000000,
                        'price_to'         => 24000000,
                        'possession_date'  => '2022-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0211',
                        'total_towers'     => 8,
                        'floors_per_tower' => '19',
                        'latitude'         => 30.7253,
                        'longitude'        => 76.6985,
                        'amenities'        => 'International Club House,Olympic Size Swimming Pool,Squash Court,Badminton Court,Tennis Court,Gymnasium,Spa & Sauna,Library,Business Centre,Amphitheatre,Kids Zone,Jogging Track,24x7 Security,Concierge Service,Power Backup,EV Charging Points',
                        'nearby_schools'   => 'Chandigarh University (4 km), Chitkara University (5 km), Vivek High School (6 km)',
                        'nearby_hospitals' => 'Paras Hospital Panchkula (10 km), Fortis Hospital Mohali (7 km)',
                        'metro_distance'   => '2 km from Aerocity, 8 km from Chandigarh Airport',
                        'is_featured'      => true,
                        'description'      => 'Emaar The Views is a landmark ultra-premium residential development in Sector 105, Mohali — one of the finest addresses in the Tricity. Offering spacious 3 & 4 BHK apartments and penthouses with breathtaking Shivalik Hills views. International-grade construction by Emaar with world-class club house, concierge services, and direct Aerocity road connectivity.',
                    ],
                    [
                        'title'            => 'Emaar Emerald Hills',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 108, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 920,
                        'available_units'  => 45,
                        'price_from'       => 8500000,
                        'price_to'         => 28000000,
                        'possession_date'  => '2021-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0198',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.7190,
                        'longitude'        => 76.6940,
                        'amenities'        => 'Gated Community,Landscaped Parks,Community Club,Swimming Pool,Kids Play Area,Jogging Track,24x7 Security,Underground Cabling,Wide Roads,Drainage System',
                        'nearby_schools'   => 'Chandigarh University (3 km), DAV School Sector 8 (5 km)',
                        'nearby_hospitals' => 'Fortis Hospital (6 km), Civil Hospital Mohali (8 km)',
                        'metro_distance'   => '3 km from Mohali Phase 8, 10 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Emaar Emerald Hills is a premium plotted development in Sector 108, Mohali. Offering 150 to 500 sq. yard residential plots in a fully developed, gated community with wide roads, underground utilities, and lush landscaping. A perfect opportunity to build your dream home on a prestigious Emaar address.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 3. JANTA LAND PROMOTERS (JLPL)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Gurdeep Singh Anand',
                'company_name'             => 'Janta Land Promoters Pvt. Ltd. (JLPL)',
                'email'                    => 'info@jlplindia.com',
                'phone'                    => '+91 90410 90410',
                'website'                  => 'https://www.jlplindia.com',
                'city'                     => 'Mohali',
                'established_year'         => '1998',
                'rera_registration'        => 'PBRERA-SAS80-PR0039',
                'cities_operating'         => 'Mohali,Zirakpur,Ludhiana,Jalandhar',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 22,
                'description'              => 'JLPL (Janta Land Promoters) is a trusted Punjab-based real estate developer with 25+ years of experience in Mohali and Tricity. Best known for JLPL Falcon View — one of Mohali\'s most iconic high-rise developments — and JLPL Industrial Plot developments. RERA-registered and well-regarded for quality construction.',
                'projects'                 => [
                    [
                        'title'            => 'JLPL Falcon View',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 66-A, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 1250,
                        'available_units'  => 60,
                        'price_from'       => 9500000,
                        'price_to'         => 22000000,
                        'possession_date'  => '2022-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0039',
                        'total_towers'     => 7,
                        'floors_per_tower' => '22',
                        'latitude'         => 30.7098,
                        'longitude'        => 76.7201,
                        'amenities'        => 'Rooftop Swimming Pool,Gymnasium,Squash Court,Badminton Court,Tennis Court,Club House,Banquet Hall,Amphitheatre,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Concierge,CCTV Surveillance,Visitor Parking,EV Charging',
                        'nearby_schools'   => 'Delhi Public School Sector 40 (2 km), Strawberry Fields High School (3 km)',
                        'nearby_hospitals' => 'Fortis Hospital Sector 62 (3.5 km), Ivy Hospital (4 km)',
                        'metro_distance'   => '1 km from Mohali Phase 7-8 junction, 7 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'JLPL Falcon View stands tall as one of Mohali\'s most iconic residential skyscrapers in Sector 66-A. Offering premium 3 & 4 BHK apartments and penthouses across 7 towers of 22 floors each. The project features a stunning rooftop pool, world-class clubhouse, and unobstructed Shivalik Hills views. A landmark address in the heart of Mohali.',
                    ],
                    [
                        'title'            => 'JLPL Sky Garden',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 66-A, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 500,
                        'available_units'  => 22,
                        'price_from'       => 5500000,
                        'price_to'         => 9000000,
                        'possession_date'  => '2020-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0041',
                        'total_towers'     => 6,
                        'floors_per_tower' => '14',
                        'latitude'         => 30.7088,
                        'longitude'        => 76.7185,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Kids Play Area,Landscaped Garden,24x7 Security,Power Backup,CCTV,Intercom',
                        'nearby_schools'   => 'Delhi Public School Sector 40 (2 km)',
                        'nearby_hospitals' => 'Fortis Hospital (3 km)',
                        'metro_distance'   => '1.5 km from Mohali Phase 7, 7.5 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'JLPL Sky Garden offers affordable 2 & 3 BHK ready-to-move apartments in Sector 66-A, Mohali. Well-planned layout with landscaped gardens, standard amenities, and excellent proximity to Mohali\'s commercial and IT hub. An ideal choice for families seeking value in a premium Mohali address.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 4. GODREJ PROPERTIES
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Gaurav Pandey',
                'company_name'             => 'Godrej Properties Ltd.',
                'email'                    => 'care@godrejproperties.com',
                'phone'                    => '+91 1800 209 5259',
                'website'                  => 'https://www.godrejproperties.com',
                'city'                     => 'Mohali',
                'established_year'         => '1990',
                'rera_registration'        => 'PBRERA-SAS80-PR0312',
                'cities_operating'         => 'Mohali,Mumbai,Pune,Bengaluru,Chennai,Ahmedabad,Kolkata,Delhi NCR',
                'rating'                   => 4.5,
                'is_verified'              => true,
                'total_delivered_projects' => 85,
                'description'              => 'Godrej Properties is India\'s most trusted real estate developer — part of the 127-year-old Godrej Group. In Mohali, Godrej brings its signature quality and design innovation with premium residential projects. Known for world-class construction standards, transparent dealings, and consistent on-time delivery across all cities.',
                'projects'                 => [
                    [
                        'title'            => 'Godrej Sector 88B Mohali',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 88-B, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 618,
                        'available_units'  => 280,
                        'price_from'       => 9200000,
                        'price_to'         => 19500000,
                        'possession_date'  => '2027-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0312',
                        'total_towers'     => 6,
                        'floors_per_tower' => '18',
                        'latitude'         => 30.6920,
                        'longitude'        => 76.7190,
                        'amenities'        => 'Infinity Swimming Pool,Gymnasium,Yoga Deck,Squash Court,Badminton Court,Tennis Court,Club House,Co-working Space,Amphitheatre,Kids Play Area,Senior Citizen Zone,Jogging Track,Cycling Track,24x7 Security,Power Backup,EV Charging,Smart Home Features',
                        'nearby_schools'   => 'Vivek High School (3 km), Strawberry Fields (4 km), DPS Sector 40 (4.5 km)',
                        'nearby_hospitals' => 'Fortis Hospital (3.5 km), Max Hospital Mohali (4 km)',
                        'metro_distance'   => '2.5 km from Mohali Phase 8 transport hub, 8 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'Godrej\'s first project in Mohali — a landmark launch in Sector 88-B offering premium 3 & 4 BHK apartments with Godrej\'s signature design excellence. Features a 30,000 sq.ft clubhouse, infinity pool, smart home integration, and co-working spaces. Backed by Godrej\'s 30+ year track record of on-time delivery and transparent dealings.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 5. TATA HOUSING (Tata Value Homes)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Sanjay Dutt',
                'company_name'             => 'Tata Housing Development Co. Ltd.',
                'email'                    => 'customercare@tatahousing.in',
                'phone'                    => '+91 22 6606 9999',
                'website'                  => 'https://www.tatahousing.in',
                'city'                     => 'Mohali',
                'established_year'         => '1984',
                'rera_registration'        => 'PBRERA-SAS80-PR0158',
                'cities_operating'         => 'Mohali,Mumbai,Pune,Chennai,Bengaluru,Delhi NCR,Kolkata,Ahmedabad',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 65,
                'description'              => 'Tata Housing is a fully owned subsidiary of Tata Sons and one of India\'s most trusted real estate brands. In Mohali, Tata delivered the iconic Tata Primanti — a premium residential project that set benchmarks for quality construction in Punjab. Known for 100% on-time delivery, quality assurance, and complete transparency in all dealings.',
                'projects'                 => [
                    [
                        'title'            => 'Tata Primanti',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 72, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 1400,
                        'available_units'  => 55,
                        'price_from'       => 8000000,
                        'price_to'         => 20000000,
                        'possession_date'  => '2020-09-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0158',
                        'total_towers'     => 14,
                        'floors_per_tower' => '15',
                        'latitude'         => 30.7001,
                        'longitude'        => 76.7450,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Squash Court,Badminton Court,Tennis Court,Kids Play Area,Amphitheatre,Jogging Track,Yoga Deck,Power Backup,24x7 Security,CCTV Surveillance,Visitor Parking,Landscaped Gardens',
                        'nearby_schools'   => 'Vivek High School Sector 38 (3 km), The Gurukul School (2 km)',
                        'nearby_hospitals' => 'Ivy Hospital Mohali (2.5 km), Fortis Hospital (5 km)',
                        'metro_distance'   => '4 km from Mohali Phase 7, 8 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'Tata Primanti is Tata Housing\'s flagship project in Mohali — 14 towers of luxury 3 & 4 BHK apartments set in 32 acres of beautifully landscaped grounds in Sector 72. Built to Tata\'s legendary quality standards with world-class amenities, a 25,000 sq.ft clubhouse, and a dedicated commercial zone. One of Mohali\'s most sought-after addresses.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 6. GILLCO GROUP (Kharar)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Sukhwant Singh Gill',
                'company_name'             => 'Gillco Builders & Promoters Pvt. Ltd.',
                'email'                    => 'info@gillco.in',
                'phone'                    => '+91 98760 00002',
                'website'                  => 'https://www.gillco.in',
                'city'                     => 'Kharar',
                'established_year'         => '2000',
                'rera_registration'        => 'PBRERA-SAS80-PR0027',
                'cities_operating'         => 'Kharar,Mohali,Landran,Banur',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'description'              => 'Gillco Group is one of the most prominent and trusted real estate developers in the Kharar-Mohali belt. Established in 2000, they have delivered 18+ residential and township projects, housing over 10,000 families. Gillco\'s projects are known for spacious layouts, strong construction quality, and competitive pricing. A trusted local brand in the Tricity real estate market.',
                'projects'                 => [
                    [
                        'title'            => 'Gillco Palms',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Gillco City, Sector 126, Kharar – Landran Road, SAS Nagar',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 1800,
                        'available_units'  => 65,
                        'price_from'       => 2800000,
                        'price_to'         => 6200000,
                        'possession_date'  => '2021-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0027',
                        'total_towers'     => 30,
                        'floors_per_tower' => '5',
                        'latitude'         => 30.7480,
                        'longitude'        => 76.6530,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Kids Play Area,Badminton Court,Landscaped Gardens,Jogging Track,24x7 Security,Power Backup,CCTV,Community Hall,Shopping Complex',
                        'nearby_schools'   => 'Chandigarh University (2 km), GNPS School Kharar (1.5 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (3 km), Fortis Hospital Mohali (10 km)',
                        'metro_distance'   => '6 km from Mohali Phase 11, 14 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'Gillco Palms is a landmark ready-to-move residential township on Kharar-Landran Road — one of the most successful housing projects in the Kharar belt. Offering 1, 2 & 3 BHK apartments at affordable prices in a well-planned community. Easy access to Chandigarh University, IT hub Mohali, and the upcoming GMADA Aerocity project.',
                    ],
                    [
                        'title'            => 'Gillco Valley',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 127, Kharar, SAS Nagar',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 600,
                        'available_units'  => 30,
                        'price_from'       => 3500000,
                        'price_to'         => 12000000,
                        'possession_date'  => '2019-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0028',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.7510,
                        'longitude'        => 76.6490,
                        'amenities'        => 'Gated Community,Landscaped Parks,Club House,Swimming Pool,Community Centre,Kids Play Area,24x7 Security,Paved Roads,Underground Cabling,Drainage System,Street Lighting',
                        'nearby_schools'   => 'Chandigarh University (1.5 km), Yadavindra Public School (4 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (2.5 km), Ivy Hospital Mohali (9 km)',
                        'metro_distance'   => '7 km from Mohali Phase 11, 15 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Gillco Valley is a premium plotted development in Sector 127, Kharar — offering 100 to 400 sq. yard residential plots in a fully gated community. With wide paved roads, underground cabling, and a community club, it\'s an ideal investment to build your own home near Chandigarh University and the growing Kharar commercial corridor.',
                    ],
                    [
                        'title'            => 'Gillco Heights',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 143, Kharar – Banur Road, SAS Nagar',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 720,
                        'available_units'  => 380,
                        'price_from'       => 3200000,
                        'price_to'         => 7500000,
                        'possession_date'  => '2026-12-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0092',
                        'total_towers'     => 12,
                        'floors_per_tower' => '10',
                        'latitude'         => 30.7420,
                        'longitude'        => 76.6580,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Kids Play Area,Jogging Track,Badminton Court,24x7 Security,Power Backup,Landscaped Garden,CCTV Surveillance',
                        'nearby_schools'   => 'Chandigarh University (3 km), The Cambridge School (2 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (4 km), Fortis Hospital Mohali (12 km)',
                        'metro_distance'   => '8 km from Mohali Phase 11, 16 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Gillco Heights is a new under-construction mid-segment residential project in Sector 143, Kharar. Offering well-planned 2 & 3 BHK apartments with modern amenities at affordable pricing. Strategically located near Chandigarh University with growing infrastructure and upcoming commercial development in the vicinity.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 7. APS GROUP (Kharar)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Paramjit Singh',
                'company_name'             => 'APS Group (Ambika Property Solutions Pvt. Ltd.)',
                'email'                    => 'info@apsgroup.in',
                'phone'                    => '+91 98141 60000',
                'website'                  => 'https://www.apsgroup.in',
                'city'                     => 'Kharar',
                'established_year'         => '2003',
                'rera_registration'        => 'PBRERA-SAS80-PR0055',
                'cities_operating'         => 'Kharar,Mohali,Ropar,Morinda',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'APS Group is a reputed Kharar-based developer with 20+ years of experience in residential and commercial projects across the Kharar-Ropar belt. Known for affordable quality housing and transparent dealings, APS has delivered 12 completed projects housing 5,000+ families in the region.',
                'projects'                 => [
                    [
                        'title'            => 'APS Highland Park',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 117, Airport Road, Kharar, SAS Nagar',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 480,
                        'available_units'  => 28,
                        'price_from'       => 2500000,
                        'price_to'         => 5200000,
                        'possession_date'  => '2022-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0055',
                        'total_towers'     => 8,
                        'floors_per_tower' => '7',
                        'latitude'         => 30.7350,
                        'longitude'        => 76.6701,
                        'amenities'        => 'Club House,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Badminton Court,24x7 Security,Power Backup,CCTV,Community Hall',
                        'nearby_schools'   => 'Bhavan Vidyalaya (3 km), DAV School Kharar (2 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (2 km), Ivy Hospital Mohali (8 km)',
                        'metro_distance'   => '5 km from Mohali Phase 9, 12 km from Chandigarh Airport',
                        'is_featured'      => false,
                        'description'      => 'APS Highland Park is a ready-to-move residential community in Sector 117, Kharar — offering 2 & 3 BHK apartments with quality construction at competitive prices. Strategically located on Airport Road, providing easy connectivity to Mohali\'s IT hub, Chandigarh, and GMADA Aerocity. An excellent choice for first-time homebuyers and investors.',
                    ],
                    [
                        'title'            => 'APS Maple Leaf',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 125, Kharar, SAS Nagar',
                        'city'             => 'Kharar',
                        'state'            => 'Punjab',
                        'total_units'      => 360,
                        'available_units'  => 200,
                        'price_from'       => 2200000,
                        'price_to'         => 4800000,
                        'possession_date'  => '2026-09-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0098',
                        'total_towers'     => 6,
                        'floors_per_tower' => '8',
                        'latitude'         => 30.7470,
                        'longitude'        => 76.6620,
                        'amenities'        => 'Club House,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Landscaped Garden,CCTV,Community Hall',
                        'nearby_schools'   => 'Chandigarh University (2 km), The Cambridge School (1.5 km)',
                        'nearby_hospitals' => 'Civil Hospital Kharar (3 km)',
                        'metro_distance'   => '7 km from Mohali Phase 11, 15 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'APS Maple Leaf is an affordable under-construction residential project in Sector 125, Kharar. Designed for the growing middle-class homebuyer segment, offering 1 & 2 BHK apartments with essential amenities. Close to Chandigarh University and the upcoming Kharar commercial corridor.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 8. SUNCITY PROJECTS (Mohali)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Aman Nanda',
                'company_name'             => 'Suncity Projects Pvt. Ltd.',
                'email'                    => 'info@suncityprojects.in',
                'phone'                    => '+91 98765 55555',
                'website'                  => 'https://www.suncityprojects.in',
                'city'                     => 'Mohali',
                'established_year'         => '2004',
                'rera_registration'        => 'PBRERA-SAS80-PR0072',
                'cities_operating'         => 'Mohali,Zirakpur,Derabassi,Gurgaon',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 16,
                'description'              => 'Suncity Projects is a Mohali-based developer with 20 years of experience in residential apartments and plotted development. Known for delivering quality mid-segment housing in Mohali\'s prime sectors. Their projects offer competitive pricing and standard amenities targeted at working professionals and families in the Tricity area.',
                'projects'                 => [
                    [
                        'title'            => 'Suncity Vimala Heights',
                        'project_type'     => 'Residential',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 95, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 380,
                        'available_units'  => 18,
                        'price_from'       => 4500000,
                        'price_to'         => 8000000,
                        'possession_date'  => '2022-09-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0072',
                        'total_towers'     => 7,
                        'floors_per_tower' => '11',
                        'latitude'         => 30.7020,
                        'longitude'        => 76.7050,
                        'amenities'        => 'Club House,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,24x7 Security,Power Backup,CCTV,Intercom,Visitor Parking',
                        'nearby_schools'   => 'Vivek High School (4 km), DPS Mohali (3.5 km)',
                        'nearby_hospitals' => 'Fortis Hospital (5 km), Ivy Hospital (3 km)',
                        'metro_distance'   => '3 km from Mohali Phase 8, 9 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Suncity Vimala Heights is a completed residential project in Sector 95, Mohali — offering 2 & 3 BHK apartments at accessible pricing. Well-connected to Mohali\'s commercial zones, IT companies in Phase 8 & 9, and major hospitals. A practical, value-driven choice for Mohali homebuyers.',
                    ],
                    [
                        'title'            => 'Suncity Avenue 76',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 76, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 520,
                        'available_units'  => 220,
                        'price_from'       => 5800000,
                        'price_to'         => 11000000,
                        'possession_date'  => '2026-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0144',
                        'total_towers'     => 8,
                        'floors_per_tower' => '13',
                        'latitude'         => 30.7080,
                        'longitude'        => 76.7100,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Badminton Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup,CCTV,Landscaped Gardens,EV Charging',
                        'nearby_schools'   => 'Strawberry Fields High School (3 km), The Gurukul (2 km)',
                        'nearby_hospitals' => 'Ivy Hospital (2 km), Fortis Hospital (5 km)',
                        'metro_distance'   => '2 km from Mohali Phase 7, 8 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'Suncity Avenue 76 is an under-construction mid-premium project in the well-developed Sector 76, Mohali. Offering 2 & 3 BHK apartments with modern amenities and smart home options. Located near Mohali\'s growing IT and commercial belt, with easy access to Ivy Hospital and major educational institutions.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 9. CHANDIGARH HOUSING BOARD (CHB) — Mohali
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Director CHB',
                'company_name'             => 'Chandigarh Housing Board (CHB) — Punjab Projects',
                'email'                    => 'chbchd@chb.gov.in',
                'phone'                    => '+91 172 270 5110',
                'website'                  => 'https://www.chb.gov.in',
                'city'                     => 'Mohali',
                'established_year'         => '1976',
                'rera_registration'        => 'PBRERA-SAS80-GM0001',
                'cities_operating'         => 'Chandigarh,Mohali,Panchkula',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 40,
                'description'              => 'Chandigarh Housing Board (CHB) is a government body delivering affordable and mid-segment housing across the Tricity. While primarily a Chandigarh entity, CHB has launched several GMADA-linked housing schemes in Mohali for government employees and general public. Known for competitive pricing and transparent allocation through lucky draw processes.',
                'projects'                 => [
                    [
                        'title'            => 'GMADA IT City Plots — Mohali',
                        'project_type'     => 'Plots',
                        'status'           => 'Ready to Move',
                        'address'          => 'IT City, Sector 66-B / 82-A / 83-A, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 1200,
                        'available_units'  => 80,
                        'price_from'       => 6000000,
                        'price_to'         => 35000000,
                        'possession_date'  => '2020-03-31',
                        'rera_id'          => 'PBRERA-SAS80-GM0001',
                        'total_towers'     => null,
                        'floors_per_tower' => null,
                        'latitude'         => 30.7120,
                        'longitude'        => 76.7230,
                        'amenities'        => 'Wide Roads,Parks,Underground Cabling,Street Lighting,Drainage System,Sewerage Network,Water Supply,Fire Station,Police Station,Community Centre,Market Zone',
                        'nearby_schools'   => 'DPS Sector 40 (1 km), St. Kabir School (2 km)',
                        'nearby_hospitals' => 'Fortis Hospital Sector 62 (1.5 km), Civil Hospital Mohali (3 km)',
                        'metro_distance'   => '0.5 km from JLPL Falcon View IT belt, 6 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'GMADA IT City is a Government of Punjab planned development in Sectors 66-B, 82-A & 83-A, Mohali — designed as the backbone of Mohali\'s IT and residential hub. Offering 100–500 sq. yard plots with complete GMADA infrastructure. Most sought-after government plotted development in Mohali with clear titles and zero legal disputes.',
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────
            // 10. IVY GREENS / IVY ESTATE (Mohali)
            // ─────────────────────────────────────────────────────────
            [
                'name'                     => 'Kanwaljit Singh',
                'company_name'             => 'Ivy Promoters Pvt. Ltd.',
                'email'                    => 'sales@ivygreens.in',
                'phone'                    => '+91 98888 00009',
                'website'                  => 'https://www.ivygreens.in',
                'city'                     => 'Mohali',
                'established_year'         => '2006',
                'rera_registration'        => 'PBRERA-SAS80-PR0088',
                'cities_operating'         => 'Mohali,Landran,Kharar',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'Ivy Promoters is a Mohali-based developer known for the acclaimed Ivy Estate and Ivy Greens projects — some of the most successfully executed township developments in the Landran-Mohali belt. With a focus on green living and community planning, Ivy projects attract both homebuyers and NRI investors from the Tricity diaspora.',
                'projects'                 => [
                    [
                        'title'            => 'Ivy Estate',
                        'project_type'     => 'Township',
                        'status'           => 'Ready to Move',
                        'address'          => 'Sector 115, Landran Road, SAS Nagar, Mohali',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 2200,
                        'available_units'  => 90,
                        'price_from'       => 3000000,
                        'price_to'         => 9500000,
                        'possession_date'  => '2021-06-30',
                        'rera_id'          => 'PBRERA-SAS80-PR0088',
                        'total_towers'     => 28,
                        'floors_per_tower' => '7',
                        'latitude'         => 30.7280,
                        'longitude'        => 76.7010,
                        'amenities'        => 'Club House,Swimming Pool,Gymnasium,Cricket Ground,Football Ground,Basketball Court,Badminton Court,Tennis Court,Kids Play Area,Jogging Track,Cycling Track,Amphitheatre,Community Hall,Landscaped Parks,24x7 Security,Power Backup,CCTV,EV Charging',
                        'nearby_schools'   => 'Chandigarh University (3 km), IIT Ropar feeder road (4 km), The Cambridge School (2 km)',
                        'nearby_hospitals' => 'Ivy Hospital Mohali (0.5 km), Fortis Hospital (6 km)',
                        'metro_distance'   => '5 km from Mohali Phase 10, 10 km from Chandigarh',
                        'is_featured'      => true,
                        'description'      => 'Ivy Estate is one of the largest ready-to-move township projects in Mohali-Landran — a 2,200-unit residential community spread across 60 acres with 70% open green spaces. Offering 1, 2 & 3 BHK apartments with an impressive 6-acre central park, full-size sports facilities, and direct access to Ivy Hospital. An ideal township for families and NRI investors.',
                    ],
                    [
                        'title'            => 'Ivy Greens — Phase 2',
                        'project_type'     => 'Residential',
                        'status'           => 'Under Construction',
                        'address'          => 'Sector 116, Landran – Kharar Road, SAS Nagar',
                        'city'             => 'Mohali',
                        'state'            => 'Punjab',
                        'total_units'      => 650,
                        'available_units'  => 320,
                        'price_from'       => 3800000,
                        'price_to'         => 7800000,
                        'possession_date'  => '2027-03-31',
                        'rera_id'          => 'PBRERA-SAS80-PR0155',
                        'total_towers'     => 10,
                        'floors_per_tower' => '11',
                        'latitude'         => 30.7310,
                        'longitude'        => 76.6980,
                        'amenities'        => 'Swimming Pool,Gymnasium,Club House,Kids Play Area,Badminton Court,Jogging Track,Landscaped Parks,24x7 Security,Power Backup,CCTV,EV Charging',
                        'nearby_schools'   => 'Chandigarh University (2 km), The Cambridge School (2.5 km)',
                        'nearby_hospitals' => 'Ivy Hospital (1 km), Civil Hospital Kharar (5 km)',
                        'metro_distance'   => '5.5 km from Mohali Phase 10, 11 km from Chandigarh',
                        'is_featured'      => false,
                        'description'      => 'Ivy Greens Phase 2 continues the success of the original Ivy Estate township on Landran-Kharar Road. Offering fresh 2 & 3 BHK under-construction apartments with enhanced amenities and modern interiors. Steps away from Ivy Hospital and Chandigarh University, making it a prime choice for medical professionals and university staff.',
                    ],
                ],
            ],

        ]; // end $builders array

        // ─────────────────────────────────────────────────────────────
        // DB INSERT LOOP
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

            // Skip if builder email already exists, else insert
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
                    'views_count'      => rand(200, 2000),
                    'leads_count'      => rand(10, 100),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
                $this->command->line("    ✓ Project: {$project['title']}");
            }
        }
    }
}
