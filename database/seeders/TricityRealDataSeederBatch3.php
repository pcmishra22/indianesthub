<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TricityRealDataSeederBatch3 extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding Tricity real estate data — Batch 3 (30 more builders)...');
        $this->seedBuilders();
        $this->command->info('✅ Batch 3 done!');
    }

    private function seedBuilders(): void
    {
        $this->command->info('  → Seeding builders (batch 3)...');

        $builders = [

            /* ── 1. IREO Private Limited ─────────────────────────────── */
            [
                'name'                     => 'IREO',
                'company_name'             => 'IREO Private Limited',
                'email'                    => 'customercare@ireo.in',
                'phone'                    => '+91 98183 12345',
                'website'                  => 'https://www.ireo.in',
                'city'                     => 'Mohali',
                'established_year'         => '2004',
                'rera_registration'        => 'PBRERA-SAS81-PR0090',
                'cities_operating'         => 'Mohali,Gurugram,Delhi,Ludhiana',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'IREO Private Limited is a premium real estate developer with a strong presence in Mohali. Their flagship IREO City in Sector 98, Mohali is a 500-acre integrated township offering residential plots, villas, floors and apartments with world-class amenities. IREO brings international-grade real estate to Tricity.',
                'projects'                 => [
                    ['title'=>'IREO City Mohali — Residential Plots','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Sector 98, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>2000,'available_units'=>120,'price_from'=>4500000,'price_to'=>25000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS81-PR0090','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7250,'longitude'=>76.7120,'amenities'=>'Wide Roads,Underground Drainage,24x7 Security,Parks,Community Centre,Cricket Ground','nearby_schools'=>'IREO School (upcoming)','nearby_hospitals'=>'Max Hospital (6 km)','metro_distance'=>'10 km from Chandigarh','is_featured'=>true,'description'=>'IREO City residential plots in Sector 98, Mohali within a 500-acre gated township. Plots from 100 to 500 sq yards with wide tree-lined roads, underground utilities and 24x7 security. Ideal for investment and self-construction.'],
                    ['title'=>'IREO Hamlet','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 98, IREO City, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>350,'available_units'=>30,'price_from'=>8000000,'price_to'=>18000000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS81-PR0095','total_towers'=>7,'floors_per_tower'=>'14','latitude'=>30.7252,'longitude'=>76.7122,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Tennis Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup','nearby_schools'=>'IREO School (on campus)','nearby_hospitals'=>'Max Hospital (6 km)','metro_distance'=>'10 km from Chandigarh','is_featured'=>true,'description'=>'IREO Hamlet offers premium 3 & 4 BHK apartments in the heart of IREO City township. Elegant architecture, spacious layouts and world-class amenities in a fully gated 500-acre community.'],
                    ['title'=>'IREO Waterfront','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 99, IREO City, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>288,'available_units'=>20,'price_from'=>12000000,'price_to'=>28000000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS81-PR0100','total_towers'=>6,'floors_per_tower'=>'18','latitude'=>30.7255,'longitude'=>76.7125,'amenities'=>'Lake View,Infinity Pool,Clubhouse,Gymnasium,Spa,Concierge,High-Speed Elevators,24x7 Security,Power Backup','nearby_schools'=>'IREO School (1 km)','nearby_hospitals'=>'Max Hospital (6 km)','metro_distance'=>'10 km from Chandigarh','is_featured'=>true,'description'=>'IREO Waterfront is a super-luxury high-rise in IREO City with lake-facing 3, 4 & 5 BHK apartments. Premium fittings, infinity pool and concierge services make this Mohali\'s finest address.'],
                    ['title'=>'IREO City Villas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 98-99, IREO City, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>120,'available_units'=>15,'price_from'=>25000000,'price_to'=>75000000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS81-PR0098','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7248,'longitude'=>76.7118,'amenities'=>'Private Garden,Private Pool Option,Clubhouse Access,Security,Power Backup,CCTV','nearby_schools'=>'IREO School (1 km)','nearby_hospitals'=>'Max Hospital (6 km)','metro_distance'=>'10 km from Chandigarh','is_featured'=>false,'description'=>'IREO City Villas offer ultra-luxury freestanding villas within the gated IREO City township. Spacious 4 & 5 BHK villas with private gardens, premium fittings and exclusive township amenities.'],
                ],
            ],

            /* ── 2. DLF Limited ─────────────────────────────────────── */
            [
                'name'                     => 'DLF',
                'company_name'             => 'DLF Limited',
                'email'                    => 'customercare.north@dlf.in',
                'phone'                    => '+91 124 4769 000',
                'website'                  => 'https://www.dlf.in',
                'city'                     => 'Chandigarh',
                'established_year'         => '1946',
                'rera_registration'        => 'PBRERA-SAS80-PR0010',
                'cities_operating'         => 'Chandigarh,Mohali,Gurugram,Delhi,Mumbai',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 80,
                'description'              => 'DLF Limited is India\'s largest real estate developer. In the Tricity region, they have delivered DLF Garden City in Mullanpur (New Chandigarh) — a mega township featuring premium apartments, villas and plots. DLF brings its 75+ years of expertise and international-grade construction to Punjab\'s fastest-growing region.',
                'projects'                 => [
                    ['title'=>'DLF Garden City — Villas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Mullanpur, New Chandigarh','city'=>'Chandigarh','state'=>'Punjab','total_units'=>400,'available_units'=>40,'price_from'=>35000000,'price_to'=>90000000,'possession_date'=>'2022-12-31','rera_id'=>'PBRERA-SAS80-PR0012','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.8260,'longitude'=>76.7690,'amenities'=>'Private Garden,Clubhouse,Swimming Pool,Gymnasium,Golf Course Access,Tennis Court,24x7 Security,Power Backup,Concierge','nearby_schools'=>'DPS Mullanpur (2 km)','nearby_hospitals'=>'PGI Chandigarh (10 km)','metro_distance'=>'12 km from Chandigarh','is_featured'=>true,'description'=>'DLF Garden City Villas at New Chandigarh offer ultra-luxury 4 & 5 BHK independent villas. Set in a lush green environment with private gardens, world-class club facilities and DLF\'s signature construction quality.'],
                    ['title'=>'DLF Garden City — Independent Floors','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Mullanpur, New Chandigarh','city'=>'Chandigarh','state'=>'Punjab','total_units'=>600,'available_units'=>50,'price_from'=>12000000,'price_to'=>22000000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS80-PR0013','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.8258,'longitude'=>76.7688,'amenities'=>'Clubhouse Access,Swimming Pool,Gymnasium,Landscaped Garden,24x7 Security,Power Backup,Car Parking','nearby_schools'=>'DPS Mullanpur (2 km)','nearby_hospitals'=>'PGI Chandigarh (10 km)','metro_distance'=>'12 km from Chandigarh','is_featured'=>true,'description'=>'DLF Garden City Independent Floors offer 3 BHK luxury floors in New Chandigarh. Premium finishes, modular kitchens and access to all DLF Garden City township amenities.'],
                    ['title'=>'DLF Garden City — Plots','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Mullanpur, New Chandigarh','city'=>'Chandigarh','state'=>'Punjab','total_units'=>800,'available_units'=>80,'price_from'=>6000000,'price_to'=>40000000,'possession_date'=>'2020-01-01','rera_id'=>'PBRERA-SAS80-PR0010','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.8255,'longitude'=>76.7685,'amenities'=>'Wide Roads,Underground Utilities,24x7 Security,Parks,Commercial Zone,Sports Facilities','nearby_schools'=>'DPS Mullanpur (2 km)','nearby_hospitals'=>'PGI Chandigarh (10 km)','metro_distance'=>'12 km from Chandigarh','is_featured'=>false,'description'=>'DLF Garden City residential plots at New Chandigarh in sizes from 100 to 500 sq yards. Part of a 200-acre planned township with wide roads, landscaped parks and premium infrastructure.'],
                ],
            ],

            /* ── 3. Whiteland Corporation ────────────────────────────── */
            [
                'name'                     => 'Whiteland Corporation',
                'company_name'             => 'Whiteland Corporation Pvt. Ltd.',
                'email'                    => 'info@whiteland.in',
                'phone'                    => '+91 99100 99100',
                'website'                  => 'https://www.whiteland.in',
                'city'                     => 'Mohali',
                'established_year'         => '2010',
                'rera_registration'        => 'PBRERA-SAS81-PR0320',
                'cities_operating'         => 'Mohali,Gurugram,Delhi NCR',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Whiteland Corporation is a modern real estate developer known for innovative architectural design and premium amenities. Their projects in Mohali bring a fresh contemporary approach to residential development with smart home features, sustainability initiatives and curated lifestyle offerings.',
                'projects'                 => [
                    ['title'=>'Whiteland The Aspen','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 76, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>480,'available_units'=>280,'price_from'=>9500000,'price_to'=>18000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS81-PR0320','total_towers'=>10,'floors_per_tower'=>'18','latitude'=>30.7070,'longitude'=>76.7260,'amenities'=>'Smart Home Features,Swimming Pool,Gymnasium,Kids Pool,Yoga Deck,EV Charging,Rooftop Lounge,24x7 Security,Power Backup,High-Speed Elevators','nearby_schools'=>'Strawberry Fields (3 km)','nearby_hospitals'=>'Fortis Hospital (4 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Whiteland The Aspen in Sector 76, Mohali is a contemporary residential project offering 3 & 4 BHK smart apartments. Integrated IoT features, rooftop lounge, EV charging and premium design make it Mohali\'s most forward-thinking development.'],
                    ['title'=>'Whiteland Westin Residences','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 103, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>320,'available_units'=>35,'price_from'=>7500000,'price_to'=>14000000,'possession_date'=>'2023-09-30','rera_id'=>'PBRERA-SAS81-PR0290','total_towers'=>8,'floors_per_tower'=>'15','latitude'=>30.7620,'longitude'=>76.7060,'amenities'=>'Club House,Swimming Pool,Gymnasium,Tennis Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Landscaped Gardens','nearby_schools'=>'Delhi Public School (4 km)','nearby_hospitals'=>'Fortis Hospital (8 km)','metro_distance'=>'14 km from Chandigarh','is_featured'=>false,'description'=>'Whiteland Westin Residences in Sector 103, Mohali offers premium 2 & 3 BHK apartments with exceptional architecture and lifestyle amenities. Ready to move.'],
                    ['title'=>'Whiteland Bliss','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 113, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>240,'available_units'=>160,'price_from'=>5500000,'price_to'=>9500000,'possession_date'=>'2027-03-31','rera_id'=>'PBRERA-SAS81-PR0350','total_towers'=>6,'floors_per_tower'=>'14','latitude'=>30.7580,'longitude'=>76.7100,'amenities'=>'Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden','nearby_schools'=>'Chandigarh Group of Colleges (2 km)','nearby_hospitals'=>'Fortis Hospital (10 km)','metro_distance'=>'14 km from Chandigarh','is_featured'=>false,'description'=>'Whiteland Bliss in Sector 113, Mohali is an upcoming premium residential project offering 2 & 3 BHK apartments at attractive prices in a fast-developing corridor.'],
                ],
            ],

            /* ── 4. Sarvottam Group ──────────────────────────────────── */
            [
                'name'                     => 'Sarvottam Group',
                'company_name'             => 'Sarvottam Group of Companies',
                'email'                    => 'info@sarvottamgroup.in',
                'phone'                    => '+91 98726 26262',
                'website'                  => 'https://www.sarvottamgroup.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2006',
                'rera_registration'        => 'PBRERA-SAS79-PR0470',
                'cities_operating'         => 'Zirakpur,Mohali,Panchkula',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Sarvottam Group is a trusted real estate developer based in Zirakpur with 8 completed residential projects. Known for quality construction, timely delivery and affordable pricing. Their developments in Zirakpur and Mohali cater to middle-income families seeking modern, well-equipped homes.',
                'projects'                 => [
                    ['title'=>'Sarvottam Homes','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>450,'available_units'=>40,'price_from'=>3200000,'price_to'=>6000000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR0470','total_towers'=>10,'floors_per_tower'=>'9','latitude'=>30.6455,'longitude'=>76.8185,'amenities'=>'Clubhouse,Gymnasium,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Landscaped Garden,Car Parking','nearby_schools'=>'Satluj Public School (2 km)','nearby_hospitals'=>'Mukat Hospital (4 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Sarvottam Homes offers well-planned 2 & 3 BHK apartments on VIP Road, Zirakpur. Quality construction with essential amenities at affordable prices. Ready to move.'],
                    ['title'=>'Sarvottam Heights','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Airport Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>320,'available_units'=>25,'price_from'=>4000000,'price_to'=>7500000,'possession_date'=>'2022-12-31','rera_id'=>'PBRERA-SAS79-PR0505','total_towers'=>8,'floors_per_tower'=>'11','latitude'=>30.6570,'longitude'=>76.8230,'amenities'=>'Swimming Pool,Gymnasium,Kids Play Area,Power Backup,24x7 Security,Jogging Track,Clubhouse','nearby_schools'=>'Innocent Hearts School (1 km)','nearby_hospitals'=>'Civil Hospital Zirakpur (3 km)','metro_distance'=>'5 km from Airport','is_featured'=>false,'description'=>'Sarvottam Heights on Airport Road, Zirakpur offers spacious 2 & 3 BHK apartments with modern amenities and excellent connectivity. Ready to move.'],
                    ['title'=>'Sarvottam Garden','project_type'=>'Township','status'=>'Ready to Move','address'=>'Baltana, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>800,'available_units'=>60,'price_from'=>2500000,'price_to'=>5000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR0440','total_towers'=>20,'floors_per_tower'=>'8','latitude'=>30.6350,'longitude'=>76.8050,'amenities'=>'Park,Kids Play Area,24x7 Security,Power Backup,Community Hall,Car Parking','nearby_schools'=>'Government Senior Secondary School (1 km)','nearby_hospitals'=>'Homecare Hospital (4 km)','metro_distance'=>'10 km from Chandigarh','is_featured'=>false,'description'=>'Sarvottam Garden is an affordable housing township in Baltana, Zirakpur. Offering budget-friendly 1 & 2 BHK apartments for first-time home buyers. Ready to move.'],
                ],
            ],

            /* ── 5. Godrej Properties ────────────────────────────────── */
            [
                'name'                     => 'Godrej Properties',
                'company_name'             => 'Godrej Properties Limited',
                'email'                    => 'customercare@godrejproperties.com',
                'phone'                    => '+91 1800 103 0101',
                'website'                  => 'https://www.godrejproperties.com',
                'city'                     => 'Mohali',
                'established_year'         => '1990',
                'rera_registration'        => 'PBRERA-SAS80-PR0080',
                'cities_operating'         => 'Mohali,Mumbai,Pune,Bangalore,Delhi,Hyderabad',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 35,
                'description'              => 'Godrej Properties, part of the 125-year-old Godrej Group, is one of India\'s most trusted real estate developers. In Mohali, their projects bring the Godrej signature of quality, innovation and sustainability. Backed by the Godrej brand\'s unmatched reputation, these projects offer world-class amenities and assured construction quality.',
                'projects'                 => [
                    ['title'=>'Godrej Meridien','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 88, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>560,'available_units'=>45,'price_from'=>7500000,'price_to'=>16000000,'possession_date'=>'2023-03-31','rera_id'=>'PBRERA-SAS80-PR0082','total_towers'=>12,'floors_per_tower'=>'16','latitude'=>30.7150,'longitude'=>76.7050,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Tennis Court,Squash Court,Kids Pool,Jogging Track,24x7 Security,Power Backup,EV Charging,Concierge','nearby_schools'=>'Delhi Public School (3 km)','nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>true,'description'=>'Godrej Meridien in Sector 88, Mohali is a landmark residential project offering premium 2, 3 & 4 BHK apartments. Inspired by Godrej\'s legacy of quality with a lavish clubhouse, multiple sports facilities and modern interiors.'],
                    ['title'=>'Godrej Evoq','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 92, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>420,'available_units'=>220,'price_from'=>9000000,'price_to'=>19000000,'possession_date'=>'2026-06-30','rera_id'=>'PBRERA-SAS80-PR0088','total_towers'=>10,'floors_per_tower'=>'18','latitude'=>30.7200,'longitude'=>76.7060,'amenities'=>'Infinity Pool,Smart Home Features,Gymnasium,Yoga Deck,Kids Play Area,Jogging Track,EV Charging,24x7 Security,Power Backup,High-Speed Elevators','nearby_schools'=>'Strawberry Fields (4 km)','nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>true,'description'=>'Godrej Evoq in Sector 92, Mohali is a premium under-construction project offering ultra-luxury 3 & 4 BHK smart homes. Featuring IoT integration, infinity pool and rooftop amenities. Possession June 2026.'],
                    ['title'=>'Godrej Woods','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 85, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>360,'available_units'=>200,'price_from'=>12000000,'price_to'=>24000000,'possession_date'=>'2027-06-30','rera_id'=>'PBRERA-SAS80-PR0095','total_towers'=>8,'floors_per_tower'=>'20','latitude'=>30.7180,'longitude'=>76.7090,'amenities'=>'Forest Theme,Walking Trails,Swimming Pool,Gymnasium,Yoga Deck,Kids Pool,Party Lawn,24x7 Security,Power Backup','nearby_schools'=>'Wave International School (1 km)','nearby_hospitals'=>'Max Hospital (4 km)','metro_distance'=>'9 km from Chandigarh','is_featured'=>true,'description'=>'Godrej Woods in Sector 85, Mohali is a nature-inspired luxury project adjacent to the wave estate. Ultra-premium 3, 4 & 5 BHK apartments amidst lush greenery with forest-themed landscaping.'],
                ],
            ],

            /* ── 6. Mahindra Lifespaces ──────────────────────────────── */
            [
                'name'                     => 'Mahindra Lifespaces',
                'company_name'             => 'Mahindra Lifespace Developers Ltd.',
                'email'                    => 'customercare@mahindralifespaces.com',
                'phone'                    => '+91 22 6813 8000',
                'website'                  => 'https://www.mahindralifespaces.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '1994',
                'rera_registration'        => 'PBRERA-SAS80-PR0120',
                'cities_operating'         => 'Chandigarh,Mumbai,Chennai,Bangalore,Pune',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 25,
                'description'              => 'Mahindra Lifespace Developers Ltd. is the real estate arm of the Mahindra Group. In the Tricity area they have delivered Mahindra Happinest at Mohali — affordable yet quality homes designed for first-time buyers and young professionals. Known for green, sustainable development.',
                'projects'                 => [
                    ['title'=>'Mahindra Happinest Mohali','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 103, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>624,'available_units'=>50,'price_from'=>4200000,'price_to'=>7500000,'possession_date'=>'2022-12-31','rera_id'=>'PBRERA-SAS80-PR0120','total_towers'=>12,'floors_per_tower'=>'14','latitude'=>30.7640,'longitude'=>76.7050,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Yoga Area,24x7 Security,Power Backup,Rainwater Harvesting,Solar Lighting','nearby_schools'=>'Delhi Public School (5 km)','nearby_hospitals'=>'Fortis Hospital (9 km)','metro_distance'=>'14 km from Chandigarh','is_featured'=>true,'description'=>'Mahindra Happinest Mohali is an affordable yet premium residential project in Sector 103 offering 1 & 2 BHK smartly designed apartments. Sustainable features including rainwater harvesting and solar lighting. Great connectivity via National Highway.'],
                    ['title'=>'Mahindra Windchimes','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 67, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>272,'available_units'=>20,'price_from'=>9000000,'price_to'=>17000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS80-PR0122','total_towers'=>6,'floors_per_tower'=>'18','latitude'=>30.7030,'longitude'=>76.6930,'amenities'=>'Infinity Pool,Club House,Gymnasium,Spa,Tennis Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Concierge','nearby_schools'=>'Strawberry Fields High School (2 km)','nearby_hospitals'=>'Fortis Hospital Mohali (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Mahindra Windchimes in Sector 67, Mohali is a premium luxury residential project with 3 & 4 BHK high-rise apartments. Featuring an infinity pool, spa and concierge services — a truly luxurious address in the heart of Mohali.'],
                ],
            ],

            /* ── 7. Tata Housing ─────────────────────────────────────── */
            [
                'name'                     => 'Tata Housing',
                'company_name'             => 'Tata Housing Development Company Ltd.',
                'email'                    => 'connect@tatahousing.in',
                'phone'                    => '+91 22 6655 6655',
                'website'                  => 'https://www.tatahousing.in',
                'city'                     => 'Chandigarh',
                'established_year'         => '1984',
                'rera_registration'        => 'PBRERA-SAS80-PR0130',
                'cities_operating'         => 'Chandigarh,Mumbai,Bangalore,Chennai,Goa,Kolkata',
                'rating'                   => 4.4,
                'is_verified'              => true,
                'total_delivered_projects' => 30,
                'description'              => 'Tata Housing Development Company is a subsidiary of the Tata Sons Group — one of India\'s most trusted brands. In the Tricity region, Tata Housing brings its hallmark quality, ethical business practices and customer-centric approach. Known for sustainable development and innovative design.',
                'projects'                 => [
                    ['title'=>'Tata Primanti','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 72, SAS Nagar, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>600,'available_units'=>40,'price_from'=>12000000,'price_to'=>35000000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS80-PR0130','total_towers'=>10,'floors_per_tower'=>'14','latitude'=>30.7050,'longitude'=>76.7020,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Spa,Tennis Court,Squash Court,Kids Play Area,Jogging Track,Party Lawn,24x7 Security,Power Backup,High-Speed Elevators','nearby_schools'=>'Strawberry Fields (1 km)','nearby_hospitals'=>'Fortis Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Tata Primanti in Sector 72, Mohali is one of Tricity\'s most prestigious addresses. Premium 3, 4 & 5 BHK residences and penthouses across 10 towers with world-class amenities including spa, tennis court and squash court. Backed by Tata\'s 100% construction quality commitment.'],
                    ['title'=>'Tata Myst','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Kasauli Hills Road, Chail, near Chandigarh','city'=>'Chandigarh','state'=>'Punjab','total_units'=>200,'available_units'=>30,'price_from'=>8500000,'price_to'=>22000000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS80-PR0132','total_towers'=>5,'floors_per_tower'=>'8','latitude'=>30.8350,'longitude'=>76.7750,'amenities'=>'Mountain View,Club House,Swimming Pool,Gymnasium,Trekking Trails,Kids Play Area,24x7 Security,Power Backup','nearby_schools'=>'N/A (Hill resort)','nearby_hospitals'=>'Community Health Center (5 km)','metro_distance'=>'35 km from Chandigarh','is_featured'=>false,'description'=>'Tata Myst is a hillside retreat near Chandigarh offering luxurious weekend homes and permanent residences. Premium 2, 3 & 4 BHK apartments with stunning mountain and valley views. A unique property in the Tricity portfolio.'],
                    ['title'=>'Tata Carnaval','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 72, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>320,'available_units'=>160,'price_from'=>10000000,'price_to'=>22000000,'possession_date'=>'2026-09-30','rera_id'=>'PBRERA-SAS80-PR0138','total_towers'=>8,'floors_per_tower'=>'16','latitude'=>30.7045,'longitude'=>76.7018,'amenities'=>'Rooftop Club,Infinity Pool,Gymnasium,Smart Home Features,EV Charging,Kids Pool,Yoga Deck,24x7 Security,Power Backup','nearby_schools'=>'Strawberry Fields (1 km)','nearby_hospitals'=>'Fortis Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Tata Carnaval in Sector 72, Mohali is an upcoming ultra-luxury residential project with 3 & 4 BHK smart homes. Rooftop clubhouse, infinity pool and Tata\'s guaranteed quality. Possession September 2026.'],
                ],
            ],

            /* ── 8. Vatika Group ─────────────────────────────────────── */
            [
                'name'                     => 'Vatika Group',
                'company_name'             => 'Vatika Group',
                'email'                    => 'info@vatikagroup.com',
                'phone'                    => '+91 124 471 1000',
                'website'                  => 'https://www.vatikagroup.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '1986',
                'rera_registration'        => 'PBRERA-SAS80-PR0045',
                'cities_operating'         => 'Chandigarh,Gurugram,Bangalore,Kolkata,Jaipur',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 22,
                'description'              => 'Vatika Group has been delivering quality real estate since 1986. In the Tricity area, they developed Vatika City — a premium integrated township in Chandigarh Sector 49. Known for their award-winning architecture and premium residential and commercial developments.',
                'projects'                 => [
                    ['title'=>'Vatika City','project_type'=>'Township','status'=>'Ready to Move','address'=>'Sector 49, Chandigarh','city'=>'Chandigarh','state'=>'Chandigarh','total_units'=>1200,'available_units'=>80,'price_from'=>8000000,'price_to'=>22000000,'possession_date'=>'2019-12-31','rera_id'=>'PBRERA-SAS80-PR0045','total_towers'=>20,'floors_per_tower'=>'14','latitude'=>30.6700,'longitude'=>76.7450,'amenities'=>'Golf Course,Club House,Swimming Pool,Gymnasium,Tennis Court,Kids Play Area,Jogging Track,24x7 Security,Power Backup,Concierge','nearby_schools'=>'Strawberry Fields (3 km)','nearby_hospitals'=>'PGIMER (6 km)','metro_distance'=>'4 km from Chandigarh center','is_featured'=>true,'description'=>'Vatika City in Sector 49, Chandigarh is one of the most prestigious integrated townships in Tricity. Premium 2, 3 & 4 BHK apartments and penthouses with a 9-hole golf course, lavish club facilities and world-class amenities.'],
                    ['title'=>'Vatika Premium Floors','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 49, Chandigarh','city'=>'Chandigarh','state'=>'Chandigarh','total_units'=>350,'available_units'=>30,'price_from'=>9500000,'price_to'=>18000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS80-PR0047','total_towers'=>null,'floors_per_tower'=>'4','latitude'=>30.6702,'longitude'=>76.7452,'amenities'=>'Golf Course Access,Club House Access,24x7 Security,Power Backup,Car Parking,Landscaped Garden','nearby_schools'=>'Strawberry Fields (3 km)','nearby_hospitals'=>'PGIMER (6 km)','metro_distance'=>'4 km from Chandigarh center','is_featured'=>false,'description'=>'Vatika Premium Floors in Sector 49, Chandigarh offer luxury independent floors within Vatika City township. 3 BHK floors with premium fittings, modular kitchens and access to golf course and club facilities.'],
                    ['title'=>'Vatika INXT','project_type'=>'Commercial','status'=>'Ready to Move','address'=>'Sector 49, Chandigarh','city'=>'Chandigarh','state'=>'Chandigarh','total_units'=>200,'available_units'=>25,'price_from'=>12000000,'price_to'=>40000000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS80-PR0050','total_towers'=>2,'floors_per_tower'=>'18','latitude'=>30.6705,'longitude'=>76.7455,'amenities'=>'Grade A Office,High-Speed Elevators,Power Backup,24x7 Security,Ample Parking,Conference Rooms,Food Court,ATM','nearby_schools'=>null,'nearby_hospitals'=>'PGIMER (6 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>false,'description'=>'Vatika INXT is a Grade-A commercial development in Sector 49, Chandigarh offering modern office spaces for IT companies, MNCs and businesses. Premium infrastructure with fiber optic connectivity.'],
                ],
            ],

            /* ── 9. Navraj Infratech ─────────────────────────────────── */
            [
                'name'                     => 'Navraj Group',
                'company_name'             => 'Navraj Infratech Pvt. Ltd.',
                'email'                    => 'info@navrajgroup.com',
                'phone'                    => '+91 98760 98760',
                'website'                  => 'https://www.navrajgroup.com',
                'city'                     => 'Panchkula',
                'established_year'         => '2005',
                'rera_registration'        => 'HRERA-PKL-330-2018',
                'cities_operating'         => 'Panchkula,Chandigarh,Mohali,Zirakpur',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'Navraj Infratech is one of Panchkula\'s leading real estate developers with 9 completed projects. Known for developing quality residential townships in Panchkula\'s extension sectors and delivering homes to thousands of families across the Tricity region. Strong expertise in plotted and apartment developments.',
                'projects'                 => [
                    ['title'=>'Navraj The Antalyas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 20, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>480,'available_units'=>40,'price_from'=>6500000,'price_to'=>13000000,'possession_date'=>'2021-12-31','rera_id'=>'HRERA-PKL-330-2018','total_towers'=>10,'floors_per_tower'=>'14','latitude'=>30.7120,'longitude'=>76.8620,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden,Squash Court','nearby_schools'=>'St. Johns High School (2 km)','nearby_hospitals'=>'Civil Hospital Panchkula (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Navraj The Antalyas in Sector 20, Panchkula offers premium 3 & 4 BHK apartments with Mediterranean-inspired architecture. Club house, squash court and landscaped green spaces make this one of Panchkula\'s finest addresses.'],
                    ['title'=>'Navraj The Ultimas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 22, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>360,'available_units'=>30,'price_from'=>9000000,'price_to'=>18000000,'possession_date'=>'2022-06-30','rera_id'=>'HRERA-PKL-340-2019','total_towers'=>8,'floors_per_tower'=>'16','latitude'=>30.7140,'longitude'=>76.8640,'amenities'=>'Infinity Pool,Spa,Gymnasium,Tennis Court,Kids Pool,Rooftop Lounge,24x7 Security,Power Backup,High-Speed Elevators','nearby_schools'=>'DAV Public School (2 km)','nearby_hospitals'=>'Civil Hospital Panchkula (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Navraj The Ultimas in Sector 22, Panchkula is a luxury high-rise featuring 3, 4 & 5 BHK apartments with infinity pool, spa and rooftop lounge. Finest address in Panchkula Extension.'],
                    ['title'=>'Navraj Plots Sector 23','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Sector 23, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>300,'available_units'=>50,'price_from'=>5000000,'price_to'=>18000000,'possession_date'=>'2020-12-31','rera_id'=>'HRERA-PKL-300-2017','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7150,'longitude'=>76.8650,'amenities'=>'Wide Internal Roads,Underground Drainage,24x7 Security,Parks,Street Lighting','nearby_schools'=>'DPS Panchkula (2 km)','nearby_hospitals'=>'Civil Hospital Panchkula (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Navraj residential plots in Sector 23, Panchkula Extension. Plots from 100 to 300 sq yards in a planned layout with wide roads and underground utilities. Ideal investment in Panchkula\'s premium sector.'],
                ],
            ],

            /* ── 10. Pearl Homes Developers ─────────────────────────── */
            [
                'name'                     => 'Pearl City',
                'company_name'             => 'Pearl City Developers Ltd.',
                'email'                    => 'info@pearlcitydevelopers.com',
                'phone'                    => '+91 98152 00555',
                'website'                  => 'https://www.pearlcitydevelopers.com',
                'city'                     => 'Mohali',
                'established_year'         => '1998',
                'rera_registration'        => 'PBRERA-SAS81-PR0060',
                'cities_operating'         => 'Mohali,Chandigarh,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Pearl City Developers is a trusted name in Mohali\'s real estate market with 12 completed projects. Their Pearl City Township in Sectors 100-102 is a 250-acre planned township with comprehensive amenities. Known for delivering quality homes at competitive prices.',
                'projects'                 => [
                    ['title'=>'Pearl City Township','project_type'=>'Township','status'=>'Ready to Move','address'=>'Sector 100-102, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>5000,'available_units'=>200,'price_from'=>2800000,'price_to'=>9000000,'possession_date'=>'2018-12-31','rera_id'=>'PBRERA-SAS81-PR0060','total_towers'=>45,'floors_per_tower'=>'12','latitude'=>30.7550,'longitude'=>76.7150,'amenities'=>'Club House,Swimming Pool,Gymnasium,School,Shopping Mall,Hospital,Cricket Ground,Football Ground,24x7 Security,Power Backup,Jogging Track','nearby_schools'=>'Pearl International School (on campus)','nearby_hospitals'=>'Pearl Health Clinic (on campus)','metro_distance'=>'13 km from Chandigarh','is_featured'=>true,'description'=>'Pearl City Township in Sectors 100-102, Mohali is a comprehensive 250-acre integrated township with 5000+ homes. Complete township with school, shopping mall and hospital — all within the campus.'],
                    ['title'=>'Pearl Business Park','project_type'=>'Commercial','status'=>'Ready to Move','address'=>'Sector 101, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>180,'available_units'=>25,'price_from'=>5000000,'price_to'=>20000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS81-PR0065','total_towers'=>2,'floors_per_tower'=>'14','latitude'=>30.7552,'longitude'=>76.7152,'amenities'=>'Grade A Office,High-Speed Elevators,Power Backup,24x7 Security,Ample Parking,Conference Rooms,Food Court','nearby_schools'=>null,'nearby_hospitals'=>'Pearl Health Clinic (1 km)','metro_distance'=>'13 km from Chandigarh','is_featured'=>false,'description'=>'Pearl Business Park in Sector 101, Mohali is a modern commercial complex within Pearl City Township. Premium office and retail spaces with excellent infrastructure and connectivity.'],
                    ['title'=>'Pearl Floors Premium','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 102, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>240,'available_units'=>20,'price_from'=>5500000,'price_to'=>10000000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS81-PR0068','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.7553,'longitude'=>76.7153,'amenities'=>'Township Access,Clubhouse,Swimming Pool,24x7 Security,Power Backup,Car Parking','nearby_schools'=>'Pearl International School (0.5 km)','nearby_hospitals'=>'Pearl Health Clinic (0.5 km)','metro_distance'=>'13 km from Chandigarh','is_featured'=>false,'description'=>'Pearl Premium Floors in Sector 102, Mohali offer 3 BHK independent floors within the Pearl City township. Premium fittings and full access to township amenities.'],
                ],
            ],

            /* ── 11. Orris Infrastructure ───────────────────────────── */
            [
                'name'                     => 'Orris Infrastructure',
                'company_name'             => 'Orris Infrastructure Pvt. Ltd.',
                'email'                    => 'info@orrisinfrastructure.com',
                'phone'                    => '+91 99109 00900',
                'website'                  => 'https://www.orrisinfrastructure.com',
                'city'                     => 'Mohali',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS81-PR0170',
                'cities_operating'         => 'Mohali,Gurugram,Delhi',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'Orris Infrastructure is a Delhi-NCR based developer that has brought its expertise to Mohali. Known for delivering quality residential apartments at competitive price points. Their Orris Aster Court project in Sector 85 has been well received by home buyers in the Tricity region.',
                'projects'                 => [
                    ['title'=>'Orris Aster Court','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 85, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>480,'available_units'=>40,'price_from'=>4500000,'price_to'=>9000000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS81-PR0170','total_towers'=>10,'floors_per_tower'=>'14','latitude'=>30.7185,'longitude'=>76.7095,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden,Indoor Games','nearby_schools'=>'Wave International School (2 km)','nearby_hospitals'=>'Max Hospital (4 km)','metro_distance'=>'9 km from Chandigarh','is_featured'=>false,'description'=>'Orris Aster Court in Sector 85, Mohali offers well-designed 2 & 3 BHK apartments at competitive prices. Quality construction with modern amenities in a prime Mohali location.'],
                    ['title'=>'Orris Carnation Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 86, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>350,'available_units'=>30,'price_from'=>3800000,'price_to'=>7000000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS81-PR0175','total_towers'=>8,'floors_per_tower'=>'12','latitude'=>30.7190,'longitude'=>76.7100,'amenities'=>'Swimming Pool,Clubhouse,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,CCTV','nearby_schools'=>'Wave International School (2 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'9 km from Chandigarh','is_featured'=>false,'description'=>'Orris Carnation Residency in Sector 86, Mohali offers affordable 2 & 3 BHK apartments with quality construction. A peaceful gated community ideal for families.'],
                ],
            ],

            /* ── 12. JMD Group ───────────────────────────────────────── */
            [
                'name'                     => 'JMD Group',
                'company_name'             => 'JMD Group India',
                'email'                    => 'info@jmdgroup.in',
                'phone'                    => '+91 124 453 0000',
                'website'                  => 'https://www.jmdgroup.in',
                'city'                     => 'Mohali',
                'established_year'         => '1987',
                'rera_registration'        => 'PBRERA-SAS80-PR0250',
                'cities_operating'         => 'Mohali,Gurugram,Noida,Delhi',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'description'              => 'JMD Group has been a prominent name in real estate since 1987. They have delivered quality residential and commercial spaces across North India. In Mohali, their projects offer premium apartments at competitive prices with modern amenities.',
                'projects'                 => [
                    ['title'=>'JMD Megapolis','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 78, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>600,'available_units'=>55,'price_from'=>4000000,'price_to'=>8500000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS80-PR0250','total_towers'=>12,'floors_per_tower'=>'14','latitude'=>30.7090,'longitude'=>76.7270,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden','nearby_schools'=>'Delhi Public School (4 km)','nearby_hospitals'=>'Fortis Hospital (6 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'JMD Megapolis in Sector 78, Mohali offers spacious 2 & 3 BHK apartments with quality construction and essential amenities. Ready to move.'],
                    ['title'=>'JMD Regent Square','project_type'=>'Commercial','status'=>'Ready to Move','address'=>'Sector 74, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>150,'available_units'=>20,'price_from'=>8000000,'price_to'=>25000000,'possession_date'=>'2020-01-01','rera_id'=>'PBRERA-SAS80-PR0255','total_towers'=>1,'floors_per_tower'=>'16','latitude'=>30.7060,'longitude'=>76.7240,'amenities'=>'High-Speed Elevators,Power Backup,24x7 Security,Ample Parking,Food Court,Conference Rooms,Retail on Ground Floor','nearby_schools'=>null,'nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'JMD Regent Square is a premium commercial development in Sector 74, Mohali offering modern office and retail spaces. Ideal for IT companies, corporates and retail brands.'],
                ],
            ],

            /* ── 13. OSB Group ───────────────────────────────────────── */
            [
                'name'                     => 'OSB Group',
                'company_name'             => 'OSB Group Pvt. Ltd.',
                'email'                    => 'info@osbgroup.in',
                'phone'                    => '+91 98763 33000',
                'website'                  => 'https://www.osbgroup.in',
                'city'                     => 'Mohali',
                'established_year'         => '2004',
                'rera_registration'        => 'PBRERA-SAS81-PR0110',
                'cities_operating'         => 'Mohali,Kharar,Zirakpur',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'OSB Group is a Mohali-based real estate developer known for delivering quality residential townships in sectors 114-115. They cater to the mid-income segment with well-designed apartments and basic amenities. A trusted local builder with multiple successful project deliveries.',
                'projects'                 => [
                    ['title'=>'OSB Aashiana Greens','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 114, Kharar, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>800,'available_units'=>60,'price_from'=>2500000,'price_to'=>5000000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS81-PR0110','total_towers'=>20,'floors_per_tower'=>'10','latitude'=>30.7510,'longitude'=>76.7120,'amenities'=>'Park,Kids Play Area,24x7 Security,Power Backup,Community Hall,Car Parking,CCTV','nearby_schools'=>'Government Senior Secondary School (1 km)','nearby_hospitals'=>'Civil Hospital Kharar (3 km)','metro_distance'=>'13 km from Chandigarh','is_featured'=>false,'description'=>'OSB Aashiana Greens in Sector 114, Kharar offers affordable 2 & 3 BHK apartments for budget-conscious home buyers. A well-maintained gated community with essential amenities.'],
                    ['title'=>'OSB Golf Heights','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 115, Kharar, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>500,'available_units'=>40,'price_from'=>3500000,'price_to'=>6500000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS81-PR0115','total_towers'=>12,'floors_per_tower'=>'12','latitude'=>30.7520,'longitude'=>76.7125,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Jogging Track,Kids Play Area,Power Backup,24x7 Security,Landscaped Garden','nearby_schools'=>'Ansal University (3 km)','nearby_hospitals'=>'Fortis Hospital (10 km)','metro_distance'=>'14 km from Chandigarh','is_featured'=>false,'description'=>'OSB Golf Heights in Sector 115, Kharar offers 2 & 3 BHK apartments with modern amenities and good road connectivity. Affordable entry into the Kharar residential market.'],
                ],
            ],

            /* ── 14. Rashi Builders ──────────────────────────────────── */
            [
                'name'                     => 'Rashi Builders',
                'company_name'             => 'Rashi Builders & Developers',
                'email'                    => 'info@rashibuilders.in',
                'phone'                    => '+91 98150 55555',
                'website'                  => 'https://www.rashibuilders.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2001',
                'rera_registration'        => 'HRERA-PKL-200-2016',
                'cities_operating'         => 'Panchkula,Chandigarh,Mohali',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'Rashi Builders & Developers is a Panchkula-based developer known for quality residential and commercial constructions. With 7 completed projects, they have established a reputation for honest dealings and timely delivery in Panchkula and surrounding areas.',
                'projects'                 => [
                    ['title'=>'Rashi Sapphire','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 17, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>200,'available_units'=>20,'price_from'=>5500000,'price_to'=>10000000,'possession_date'=>'2021-03-31','rera_id'=>'HRERA-PKL-200-2016','total_towers'=>5,'floors_per_tower'=>'12','latitude'=>30.7080,'longitude'=>76.8590,'amenities'=>'Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden','nearby_schools'=>'DAV Public School (2 km)','nearby_hospitals'=>'Civil Hospital Panchkula (3 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>false,'description'=>'Rashi Sapphire in Sector 17, Panchkula offers premium 2 & 3 BHK apartments at an affordable price point. Quality construction with essential amenities in a well-connected location.'],
                    ['title'=>'Rashi Pearl Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 18, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>150,'available_units'=>15,'price_from'=>4500000,'price_to'=>8000000,'possession_date'=>'2020-09-30','rera_id'=>'HRERA-PKL-210-2017','total_towers'=>4,'floors_per_tower'=>'10','latitude'=>30.7085,'longitude'=>76.8595,'amenities'=>'Club House,Gymnasium,Kids Play Area,24x7 Security,Power Backup,Landscaped Garden,Car Parking','nearby_schools'=>'St. Anne\'s School (1.5 km)','nearby_hospitals'=>'Civil Hospital Panchkula (3 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>false,'description'=>'Rashi Pearl Residency in Sector 18, Panchkula offers well-designed 2 & 3 BHK apartments in a peaceful residential environment. Reasonable pricing and honest dealings.'],
                ],
            ],

            /* ── 15. Landmark Group Chandigarh ──────────────────────── */
            [
                'name'                     => 'Landmark Group',
                'company_name'             => 'Landmark Group Chandigarh',
                'email'                    => 'info@landmarkgroupchd.com',
                'phone'                    => '+91 98726 11111',
                'website'                  => 'https://www.landmarkgroupchd.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '1995',
                'rera_registration'        => 'PBRERA-SAS79-PR0199',
                'cities_operating'         => 'Chandigarh,Mohali,Zirakpur,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'description'              => 'Landmark Group Chandigarh is one of the city\'s most established real estate developers with 10+ years of delivering quality projects across Tricity. Known for innovation, transparency and customer satisfaction, they have helped thousands of families find their dream homes.',
                'projects'                 => [
                    ['title'=>'Landmark The Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 52, Chandigarh','city'=>'Chandigarh','state'=>'Chandigarh','total_units'=>240,'available_units'=>20,'price_from'=>8500000,'price_to'=>16000000,'possession_date'=>'2021-12-31','rera_id'=>'PBRERA-SAS79-PR0199','total_towers'=>6,'floors_per_tower'=>'12','latitude'=>30.6890,'longitude'=>76.7280,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Car Parking','nearby_schools'=>'Carmel Convent School (1.5 km)','nearby_hospitals'=>'PGIMER (4 km)','metro_distance'=>'3 km from Chandigarh center','is_featured'=>false,'description'=>'Landmark The Residency in Sector 52, Chandigarh offers premium 3 BHK apartments in a prime location. Quality construction with modern amenities and excellent connectivity to the city center.'],
                    ['title'=>'Landmark Cyber Park','project_type'=>'Commercial','status'=>'Ready to Move','address'=>'Sector 67, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>120,'available_units'=>15,'price_from'=>9000000,'price_to'=>30000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS80-PR0202','total_towers'=>1,'floors_per_tower'=>'15','latitude'=>30.7038,'longitude'=>76.6928,'amenities'=>'Grade A Office,High-Speed Internet,Power Backup,24x7 Security,Conference Rooms,Food Court,Ample Parking','nearby_schools'=>null,'nearby_hospitals'=>'Fortis Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Landmark Cyber Park in Sector 67, Mohali is a premium IT commercial complex offering modern office spaces for tech companies and startups in Mohali\'s growing IT corridor.'],
                ],
            ],

            /* ── 16. Imperia Structures ──────────────────────────────── */
            [
                'name'                     => 'Imperia Structures',
                'company_name'             => 'Imperia Structures Ltd.',
                'email'                    => 'info@imperiastructures.com',
                'phone'                    => '+91 99109 55555',
                'website'                  => 'https://www.imperiastructures.com',
                'city'                     => 'Mohali',
                'established_year'         => '2001',
                'rera_registration'        => 'PBRERA-SAS81-PR0190',
                'cities_operating'         => 'Mohali,Kharar,Gurugram',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Imperia Structures Ltd. has been developing quality residential projects for over two decades. In Mohali and Kharar, their projects offer competitive pricing and good construction quality making them popular among first-time buyers and investors.',
                'projects'                 => [
                    ['title'=>'Imperia Esfera','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 109, Kharar, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>420,'available_units'=>35,'price_from'=>3200000,'price_to'=>6000000,'possession_date'=>'2021-12-31','rera_id'=>'PBRERA-SAS81-PR0190','total_towers'=>10,'floors_per_tower'=>'12','latitude'=>30.7550,'longitude'=>76.7090,'amenities'=>'Clubhouse,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden,Car Parking','nearby_schools'=>'Government High School (2 km)','nearby_hospitals'=>'Civil Hospital Kharar (4 km)','metro_distance'=>'13 km from Chandigarh','is_featured'=>false,'description'=>'Imperia Esfera in Sector 109, Kharar is an affordable residential project offering 2 & 3 BHK apartments with quality construction. Ideal for budget-conscious buyers seeking a gated community lifestyle.'],
                    ['title'=>'Imperia Grand','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 77, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>280,'available_units'=>25,'price_from'=>4500000,'price_to'=>8000000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS81-PR0195','total_towers'=>7,'floors_per_tower'=>'13','latitude'=>30.7080,'longitude'=>76.7270,'amenities'=>'Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Clubhouse,Power Backup,24x7 Security','nearby_schools'=>'Strawberry Fields (3 km)','nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Imperia Grand in Sector 77, Mohali offers 2 & 3 BHK apartments with modern amenities and good connectivity to Chandigarh. A reliable choice for home buyers.'],
                ],
            ],

            /* ── 17. Pioneer Developers ──────────────────────────────── */
            [
                'name'                     => 'Pioneer Urban',
                'company_name'             => 'Pioneer Urban Land & Infrastructure Ltd.',
                'email'                    => 'customercare@pioneerurban.in',
                'phone'                    => '+91 124 666 5555',
                'website'                  => 'https://www.pioneerurban.in',
                'city'                     => 'Mohali',
                'established_year'         => '2001',
                'rera_registration'        => 'PBRERA-SAS81-PR0210',
                'cities_operating'         => 'Mohali,Gurugram,Delhi NCR',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'Pioneer Urban is a leading real estate developer known for thoughtfully designed and high-quality residential projects. In Mohali, they bring their experience from delivering premium projects across NCR with contemporary architecture and lifestyle amenities.',
                'projects'                 => [
                    ['title'=>'Pioneer Presidia','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 69, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>480,'available_units'=>40,'price_from'=>7000000,'price_to'=>14000000,'possession_date'=>'2022-09-30','rera_id'=>'PBRERA-SAS81-PR0210','total_towers'=>10,'floors_per_tower'=>'16','latitude'=>30.7010,'longitude'=>76.7010,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Tennis Court,Squash Court,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Party Lawn','nearby_schools'=>'Strawberry Fields (2 km)','nearby_hospitals'=>'Fortis Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Pioneer Presidia in Sector 69, Mohali is a premium residential project offering well-designed 3 & 4 BHK apartments. Contemporary architecture with a clubhouse, tennis court and squash court.'],
                    ['title'=>'Pioneer Araya','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 62, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>360,'available_units'=>200,'price_from'=>8500000,'price_to'=>18000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS81-PR0218','total_towers'=>8,'floors_per_tower'=>'18','latitude'=>30.6990,'longitude'=>76.7000,'amenities'=>'Rooftop Club,Infinity Pool,Gymnasium,Smart Features,EV Charging,Kids Pool,24x7 Security,Power Backup','nearby_schools'=>'Strawberry Fields (1 km)','nearby_hospitals'=>'Fortis Hospital (2 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>true,'description'=>'Pioneer Araya in Sector 62, Mohali is an upcoming ultra-luxury project offering 3 & 4 BHK apartments with rooftop club and infinity pool. Possession December 2026.'],
                ],
            ],

            /* ── 18. Kensville Developers ────────────────────────────── */
            [
                'name'                     => 'Kensville',
                'company_name'             => 'Kensville Golf & Country Club Developers',
                'email'                    => 'info@kensville.com',
                'phone'                    => '+91 98720 45645',
                'website'                  => 'https://www.kensville.com',
                'city'                     => 'Mohali',
                'established_year'         => '2007',
                'rera_registration'        => 'PBRERA-SAS81-PM0010',
                'cities_operating'         => 'Mohali,Chandigarh',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Kensville Golf & Country Club is one of the most exclusive developments in Punjab. This 200-acre premium development in Sector 73, Mohali features a championship 18-hole golf course, luxury villas, independent floors and a world-class club — catering to the ultra-high-net-worth segment in Tricity.',
                'projects'                 => [
                    ['title'=>'Kensville Golf Villas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 73, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>120,'available_units'=>15,'price_from'=>50000000,'price_to'=>200000000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS81-PM0010','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7055,'longitude'=>76.7030,'amenities'=>'18-Hole Golf Course,Golf Club,Swimming Pool,Spa,Restaurant,Tennis Court,Gymnasium,Concierge,24x7 Security','nearby_schools'=>'Strawberry Fields (2 km)','nearby_hospitals'=>'Fortis Hospital (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Kensville Golf Villas offers ultra-luxury standalone villas overlooking an 18-hole championship golf course in Sector 73, Mohali. The most prestigious address in Tricity with world-class golf club facilities.'],
                    ['title'=>'Kensville Independent Floors','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 73, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>80,'available_units'=>10,'price_from'=>20000000,'price_to'=>45000000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS81-PM0012','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.7057,'longitude'=>76.7032,'amenities'=>'Golf Course Access,Club Access,Swimming Pool,Gymnasium,Security,Power Backup,Parking','nearby_schools'=>'Strawberry Fields (2 km)','nearby_hospitals'=>'Fortis Hospital (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Kensville Independent Floors offer 4 BHK luxury floors within the prestigious Kensville Golf & Country Club. Enjoy golf course views, club access and an exclusive lifestyle at a more accessible price point than the villas.'],
                ],
            ],

            /* ── 19. Surya Builders ───────────────────────────────────── */
            [
                'name'                     => 'Surya Builders',
                'company_name'             => 'Surya Builders & Developers',
                'email'                    => 'info@suryabuilderschd.com',
                'phone'                    => '+91 98883 55555',
                'website'                  => 'https://www.suryabuilderschd.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '1999',
                'rera_registration'        => 'PBRERA-SAS79-PR0299',
                'cities_operating'         => 'Chandigarh,Mohali,Panchkula,Zirakpur',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Surya Builders & Developers has been active in Tricity real estate since 1999. Known for developing residential floors and apartments across Chandigarh periphery areas. Honest dealings and quality construction have earned them a loyal client base.',
                'projects'                 => [
                    ['title'=>'Surya Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 20, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>180,'available_units'=>18,'price_from'=>4800000,'price_to'=>9500000,'possession_date'=>'2021-06-30','rera_id'=>'HRERA-PKL-380-2019','total_towers'=>5,'floors_per_tower'=>'10','latitude'=>30.7100,'longitude'=>76.8610,'amenities'=>'Gym,Kids Play Area,Power Backup,Security,Parking,Landscaped Garden','nearby_schools'=>'DAV School (1 km)','nearby_hospitals'=>'Civil Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Surya Residency in Sector 20, Panchkula offers 2 & 3 BHK apartments at competitive prices. Quality construction with essential amenities in Panchkula Extension.'],
                    ['title'=>'Surya Heights Zirakpur','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Dhakoli, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>220,'available_units'=>20,'price_from'=>3500000,'price_to'=>6000000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR0299','total_towers'=>6,'floors_per_tower'=>'8','latitude'=>30.6340,'longitude'=>76.8120,'amenities'=>'Gym,Kids Play Area,Power Backup,Security,Parking','nearby_schools'=>'Ryan International School (2 km)','nearby_hospitals'=>'Mukat Hospital (4 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'Surya Heights in Dhakoli, Zirakpur offers budget-friendly 2 & 3 BHK apartments at an affordable price. Good connectivity to Chandigarh via the Ambala-Chandigarh Highway.'],
                ],
            ],

            /* ── 20. Krishna Buildtech ───────────────────────────────── */
            [
                'name'                     => 'Krishna Buildtech',
                'company_name'             => 'Krishna Buildtech Pvt. Ltd.',
                'email'                    => 'info@krishnabuildtech.in',
                'phone'                    => '+91 98760 55500',
                'website'                  => 'https://www.krishnabuildtech.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS79-PR0356',
                'cities_operating'         => 'Zirakpur,Patiala,Mohali',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Krishna Buildtech has been developing residential projects across Zirakpur since 2008. Focusing on the mid-income segment, they deliver affordable 2 & 3 BHK apartments with essential amenities. Known for transparent dealings and quality construction.',
                'projects'                 => [
                    ['title'=>'Krishna Greens','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Patiala Highway, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>350,'available_units'=>30,'price_from'=>2800000,'price_to'=>5500000,'possession_date'=>'2020-09-30','rera_id'=>'PBRERA-SAS79-PR0356','total_towers'=>9,'floors_per_tower'=>'8','latitude'=>30.6410,'longitude'=>76.8130,'amenities'=>'Park,Kids Play Area,24x7 Security,Power Backup,Car Parking,CCTV,Community Hall','nearby_schools'=>'Air Force School (2 km)','nearby_hospitals'=>'Homecare Hospital (4 km)','metro_distance'=>'9 km from Chandigarh','is_featured'=>false,'description'=>'Krishna Greens on Patiala Highway, Zirakpur offers budget-friendly 2 & 3 BHK apartments. Quality construction and essential amenities for comfortable community living.'],
                    ['title'=>'Krishna Apartments','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Gazipur Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>240,'available_units'=>20,'price_from'=>3200000,'price_to'=>5800000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR0370','total_towers'=>6,'floors_per_tower'=>'10','latitude'=>30.6490,'longitude'=>76.8240,'amenities'=>'Gymnasium,Kids Play Area,Power Backup,24x7 Security,Jogging Track,Car Parking','nearby_schools'=>'Shivalik Public School (2 km)','nearby_hospitals'=>'Healing Hospital (4 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Krishna Apartments on Gazipur Road, Zirakpur offers 2 & 3 BHK apartments with modern amenities. A reliable residential option in one of Zirakpur\'s growing corridors.'],
                ],
            ],

            /* ── 21. Himalaya Infratech ──────────────────────────────── */
            [
                'name'                     => 'Himalaya Infratech',
                'company_name'             => 'Himalaya Infratech Pvt. Ltd.',
                'email'                    => 'info@himalayainfratech.in',
                'phone'                    => '+91 98762 44444',
                'website'                  => 'https://www.himalayainfratech.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2003',
                'rera_registration'        => 'HRERA-PKL-280-2018',
                'cities_operating'         => 'Panchkula,Chandigarh,Mohali,Zirakpur',
                'rating'                   => 3.6,
                'is_verified'              => false,
                'total_delivered_projects' => 6,
                'description'              => 'Himalaya Infratech has been delivering residential and commercial properties in Panchkula and Chandigarh since 2003. Known for mid-segment residential apartments and builder floors. Serving the Tricity market with quality constructions across multiple projects.',
                'projects'                 => [
                    ['title'=>'Himalaya Homes Panchkula','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 21, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>180,'available_units'=>18,'price_from'=>4200000,'price_to'=>8500000,'possession_date'=>'2021-03-31','rera_id'=>'HRERA-PKL-280-2018','total_towers'=>5,'floors_per_tower'=>'9','latitude'=>30.7110,'longitude'=>76.8615,'amenities'=>'Park,Kids Play Area,Power Backup,Security,Parking,Community Hall','nearby_schools'=>'DPS Panchkula (2 km)','nearby_hospitals'=>'Civil Hospital Panchkula (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Himalaya Homes in Sector 21, Panchkula offers well-designed 2 & 3 BHK apartments at affordable prices. Essential amenities with good connectivity to Chandigarh.'],
                    ['title'=>'Himalaya Builder Floors','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 15, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>80,'available_units'=>8,'price_from'=>5500000,'price_to'=>9000000,'possession_date'=>'2020-06-30','rera_id'=>'HRERA-PKL-290-2019','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.7060,'longitude'=>76.8580,'amenities'=>'Power Backup,Security,Parking,Individual Terrace','nearby_schools'=>'Government Model School (1 km)','nearby_hospitals'=>'Civil Hospital Panchkula (4 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>false,'description'=>'Himalaya Builder Floors in Sector 15, Panchkula offer 3 BHK independent floors with individual terraces. Premium finishes and essential amenities at an affordable price.'],
                ],
            ],

            /* ── 22. R.M. Realtech ───────────────────────────────────── */
            [
                'name'                     => 'RM Realtech',
                'company_name'             => 'R.M. Realtech Pvt. Ltd.',
                'email'                    => 'info@rmrealtech.in',
                'phone'                    => '+91 98726 00300',
                'website'                  => 'https://www.rmrealtech.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2010',
                'rera_registration'        => 'PBRERA-SAS79-PR0395',
                'cities_operating'         => 'Zirakpur,Derabassi,Mohali',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'R.M. Realtech is a growing real estate developer in Zirakpur focusing on affordable and mid-segment housing. Their projects in Zirakpur and Derabassi have delivered quality homes to hundreds of families. Known for transparent dealings and good construction quality.',
                'projects'                 => [
                    ['title'=>'RM Royale Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Peer Mushalla, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>280,'available_units'=>25,'price_from'=>3400000,'price_to'=>6500000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS79-PR0395','total_towers'=>7,'floors_per_tower'=>'10','latitude'=>30.6535,'longitude'=>76.8295,'amenities'=>'Clubhouse,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Landscaped Garden','nearby_schools'=>'Satluj Public School (2 km)','nearby_hospitals'=>'Civil Hospital Zirakpur (4 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>false,'description'=>'RM Royale Residency in Peer Mushalla, Zirakpur offers 2 & 3 BHK apartments with modern amenities. Quality construction and peaceful surroundings in a gated community.'],
                    ['title'=>'RM Green Villas Derabassi','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Derabassi, SAS Nagar','city'=>'Derabassi','state'=>'Punjab','total_units'=>120,'available_units'=>15,'price_from'=>4500000,'price_to'=>8000000,'possession_date'=>'2021-12-31','rera_id'=>'PBRERA-SAS79-PR0405','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.5680,'longitude'=>76.8150,'amenities'=>'Private Garden,Parking,Security,Power Backup,Community Park','nearby_schools'=>'Government School (2 km)','nearby_hospitals'=>'Civil Hospital Derabassi (3 km)','metro_distance'=>'18 km from Chandigarh','is_featured'=>false,'description'=>'RM Green Villas in Derabassi offers independent villas with private gardens. Affordable luxury with quality construction in the fast-developing Derabassi corridor.'],
                ],
            ],

            /* ── 23. Pacific Infrahousing ────────────────────────────── */
            [
                'name'                     => 'Pacific Infrahousing',
                'company_name'             => 'Pacific Infrahousing Pvt. Ltd.',
                'email'                    => 'info@pacificinfra.in',
                'phone'                    => '+91 98880 22200',
                'website'                  => 'https://www.pacificinfra.in',
                'city'                     => 'Mohali',
                'established_year'         => '2009',
                'rera_registration'        => 'PBRERA-SAS81-PR0220',
                'cities_operating'         => 'Mohali,Kharar,Zirakpur',
                'rating'                   => 3.6,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Pacific Infrahousing delivers affordable and mid-segment residential projects across Mohali and Kharar. Their focus is on providing quality homes to first-time buyers and families seeking value for money. A growing developer with a track record of timely deliveries.',
                'projects'                 => [
                    ['title'=>'Pacific Blue Sapphire','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 116, Kharar, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>400,'available_units'=>35,'price_from'=>2800000,'price_to'=>5500000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS81-PR0220','total_towers'=>10,'floors_per_tower'=>'10','latitude'=>30.7500,'longitude'=>76.7130,'amenities'=>'Park,Kids Play Area,Security,Power Backup,Car Parking,Community Hall','nearby_schools'=>'Government High School (1 km)','nearby_hospitals'=>'Civil Hospital Kharar (5 km)','metro_distance'=>'14 km from Chandigarh','is_featured'=>false,'description'=>'Pacific Blue Sapphire in Sector 116, Kharar offers budget-friendly 2 & 3 BHK apartments. Ideal for first-time buyers seeking an affordable home in the Mohali-Kharar corridor.'],
                    ['title'=>'Pacific Hills','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 82, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>300,'available_units'=>200,'price_from'=>4500000,'price_to'=>8000000,'possession_date'=>'2026-09-30','rera_id'=>'PBRERA-SAS81-PR0228','total_towers'=>8,'floors_per_tower'=>'12','latitude'=>30.7165,'longitude'=>76.7065,'amenities'=>'Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security','nearby_schools'=>'DAV School (2 km)','nearby_hospitals'=>'Fortis Hospital (6 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>false,'description'=>'Pacific Hills in Sector 82, Mohali is an upcoming residential project offering 2 & 3 BHK apartments with modern amenities. Good location with connectivity to Chandigarh International Airport.'],
                ],
            ],

            /* ── 24. Mayfair Housing ─────────────────────────────────── */
            [
                'name'                     => 'Mayfair Housing',
                'company_name'             => 'Mayfair Housing Pvt. Ltd.',
                'email'                    => 'info@mayfairhousing.in',
                'phone'                    => '+91 98156 77777',
                'website'                  => 'https://www.mayfairhousing.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2007',
                'rera_registration'        => 'PBRERA-SAS79-PR0415',
                'cities_operating'         => 'Zirakpur,Mohali,Panchkula',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'Mayfair Housing is a well-established developer in Zirakpur offering affordable residential apartments and builder floors. Their projects emphasize quality construction, essential amenities and strategic locations to deliver maximum value to home buyers.',
                'projects'                 => [
                    ['title'=>'Mayfair Greens','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Lohgarh Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>300,'available_units'=>25,'price_from'=>3000000,'price_to'=>5800000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS79-PR0415','total_towers'=>8,'floors_per_tower'=>'8','latitude'=>30.6488,'longitude'=>76.8228,'amenities'=>'Park,Kids Play Area,Security,Power Backup,Car Parking,Gym','nearby_schools'=>'Greenfield International School (1.5 km)','nearby_hospitals'=>'Mukat Hospital (5 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Mayfair Greens on Lohgarh Road, Zirakpur offers affordable 2 & 3 BHK apartments in a green environment. Quality construction with essential amenities at competitive prices.'],
                    ['title'=>'Mayfair Royal Apartments','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>200,'available_units'=>18,'price_from'=>3800000,'price_to'=>6800000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS79-PR0425','total_towers'=>5,'floors_per_tower'=>'10','latitude'=>30.6458,'longitude'=>76.8195,'amenities'=>'Gymnasium,Swimming Pool,Kids Play Area,Power Backup,24x7 Security,Jogging Track','nearby_schools'=>'Satluj Public School (1.5 km)','nearby_hospitals'=>'Civil Hospital Zirakpur (3 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Mayfair Royal Apartments on VIP Road, Zirakpur offers 2 & 3 BHK apartments with swimming pool and gymnasium. Prime VIP Road location with easy access to Chandigarh.'],
                ],
            ],

            /* ── 25. Omkar Developers ────────────────────────────────── */
            [
                'name'                     => 'Omkar Developers',
                'company_name'             => 'Omkar Developers & Builders',
                'email'                    => 'info@omkardevelopers.in',
                'phone'                    => '+91 98725 11111',
                'website'                  => 'https://www.omkardevelopers.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2004',
                'rera_registration'        => 'HRERA-PKL-310-2018',
                'cities_operating'         => 'Panchkula,Chandigarh,Zirakpur',
                'rating'                   => 3.7,
                'is_verified'              => false,
                'total_delivered_projects' => 5,
                'description'              => 'Omkar Developers has been building affordable residential properties in Panchkula since 2004. Known for honest dealings and quality construction, they serve the middle-income segment in Panchkula\'s sectors with well-designed apartments and builder floors.',
                'projects'                 => [
                    ['title'=>'Omkar Residency Panchkula','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 19, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>160,'available_units'=>15,'price_from'=>4000000,'price_to'=>7500000,'possession_date'=>'2020-12-31','rera_id'=>'HRERA-PKL-310-2018','total_towers'=>4,'floors_per_tower'=>'10','latitude'=>30.7095,'longitude'=>76.8605,'amenities'=>'Park,Kids Play Area,Security,Power Backup,Parking','nearby_schools'=>'St. Thomas School (2 km)','nearby_hospitals'=>'Civil Hospital Panchkula (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Omkar Residency in Sector 19, Panchkula offers affordable 2 & 3 BHK apartments. Quality construction with essential amenities in a peaceful residential location.'],
                    ['title'=>'Omkar Builder Floors','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 12, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>60,'available_units'=>6,'price_from'=>5000000,'price_to'=>8500000,'possession_date'=>'2020-03-31','rera_id'=>'HRERA-PKL-315-2018','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.7040,'longitude'=>76.8560,'amenities'=>'Security,Power Backup,Parking,Individual Terrace','nearby_schools'=>'DAV School (1 km)','nearby_hospitals'=>'General Hospital (3 km)','metro_distance'=>'3 km from Chandigarh','is_featured'=>false,'description'=>'Omkar Builder Floors in Sector 12, Panchkula offer premium 3 BHK independent floors with individual terraces. Quality finishes at competitive prices.'],
                ],
            ],

            /* ── 26. Jai Durga Builders ──────────────────────────────── */
            [
                'name'                     => 'Jai Durga Builders',
                'company_name'             => 'Jai Durga Builders & Developers',
                'email'                    => 'info@jaidurgabuilders.com',
                'phone'                    => '+91 98768 00700',
                'website'                  => 'https://www.jaidurgabuilders.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2006',
                'rera_registration'        => 'PBRERA-SAS79-PR0430',
                'cities_operating'         => 'Zirakpur,Derabassi,Rajpura',
                'rating'                   => 3.6,
                'is_verified'              => false,
                'total_delivered_projects' => 5,
                'description'              => 'Jai Durga Builders & Developers is a local builder in Zirakpur specializing in affordable residential apartments and plots. Known for transparent transactions and quality construction for the middle-income segment in Zirakpur and Derabassi.',
                'projects'                 => [
                    ['title'=>'Jai Durga Apartments','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Ambala Highway, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>200,'available_units'=>20,'price_from'=>2600000,'price_to'=>5000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR0430','total_towers'=>5,'floors_per_tower'=>'8','latitude'=>30.6620,'longitude'=>76.8440,'amenities'=>'Security,Power Backup,Car Parking,Community Park','nearby_schools'=>'Government School (2 km)','nearby_hospitals'=>'Fortis Hospital (6 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'Jai Durga Apartments on Ambala Highway, Zirakpur offers budget-friendly 2 & 3 BHK apartments. Affordable housing with essential amenities in a well-connected location.'],
                    ['title'=>'Jai Durga Green Homes','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Gazipur Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>150,'available_units'=>12,'price_from'=>2800000,'price_to'=>4800000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS79-PR0438','total_towers'=>4,'floors_per_tower'=>'8','latitude'=>30.6495,'longitude'=>76.8242,'amenities'=>'Security,Power Backup,Parking,Community Hall','nearby_schools'=>'Greenfield School (2 km)','nearby_hospitals'=>'Healing Hospital (4 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Jai Durga Green Homes on Gazipur Road, Zirakpur offers affordable 2 BHK apartments for budget buyers. Good connectivity and peaceful surroundings.'],
                ],
            ],

            /* ── 27. Capital Greens ───────────────────────────────────── */
            [
                'name'                     => 'Capital Greens',
                'company_name'             => 'Capital Greens Developers Pvt. Ltd.',
                'email'                    => 'info@capitalgreens.in',
                'phone'                    => '+91 98882 66666',
                'website'                  => 'https://www.capitalgreens.in',
                'city'                     => 'Mohali',
                'established_year'         => '2011',
                'rera_registration'        => 'PBRERA-SAS80-PR0270',
                'cities_operating'         => 'Mohali,Chandigarh,Zirakpur',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Capital Greens Developers is a Mohali-based developer specializing in eco-friendly residential developments. Their projects incorporate green building practices, energy-efficient systems and landscaped environments to deliver healthy, sustainable living spaces in prime Mohali locations.',
                'projects'                 => [
                    ['title'=>'Capital Greens Phase 1','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 79, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>350,'available_units'=>30,'price_from'=>4200000,'price_to'=>8000000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS80-PR0270','total_towers'=>8,'floors_per_tower'=>'12','latitude'=>30.7115,'longitude'=>76.7245,'amenities'=>'Green Landscaping,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Rainwater Harvesting','nearby_schools'=>'Strawberry Fields (4 km)','nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Capital Greens Phase 1 in Sector 79, Mohali offers eco-friendly 2 & 3 BHK apartments with extensive landscaping and green amenities. Rainwater harvesting and energy-efficient systems.'],
                    ['title'=>'Capital Greens Phase 2','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 80, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>280,'available_units'=>180,'price_from'=>5000000,'price_to'=>9500000,'possession_date'=>'2026-06-30','rera_id'=>'PBRERA-SAS80-PR0278','total_towers'=>7,'floors_per_tower'=>'14','latitude'=>30.7120,'longitude'=>76.7250,'amenities'=>'Green Landscaping,Swimming Pool,Gymnasium,Kids Play Area,Jogging Track,Solar Power,Power Backup,24x7 Security','nearby_schools'=>'Strawberry Fields (4 km)','nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Capital Greens Phase 2 in Sector 80, Mohali is an upcoming eco-friendly residential project. Solar power integration, green landscaping and modern amenities. Possession June 2026.'],
                ],
            ],

            /* ── 28. Krisumi Corporation ─────────────────────────────── */
            [
                'name'                     => 'Krisumi Corporation',
                'company_name'             => 'Krisumi Corporation Pvt. Ltd.',
                'email'                    => 'info@krisumi.in',
                'phone'                    => '+91 124 496 6789',
                'website'                  => 'https://www.krisumi.in',
                'city'                     => 'Mohali',
                'established_year'         => '2015',
                'rera_registration'        => 'PBRERA-SAS81-PR0360',
                'cities_operating'         => 'Mohali,Gurugram',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 3,
                'description'              => 'Krisumi Corporation is a joint venture between Krishna Group (India) and Sumitomo Corporation (Japan). This Indo-Japanese partnership brings Japanese precision and craftsmanship to Indian real estate. Their projects are known for exceptional build quality, modern design and international-grade amenities.',
                'projects'                 => [
                    ['title'=>'Krisumi Waterfall Residences','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 36A, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>480,'available_units'=>280,'price_from'=>11000000,'price_to'=>25000000,'possession_date'=>'2027-03-31','rera_id'=>'PBRERA-SAS81-PR0360','total_towers'=>10,'floors_per_tower'=>'20','latitude'=>30.6960,'longitude'=>76.6970,'amenities'=>'Japanese Design,Waterfall Feature,Swimming Pool,Gymnasium,Spa,Kids Pool,EV Charging,Smart Home,Concierge,24x7 Security','nearby_schools'=>'Strawberry Fields (2 km)','nearby_hospitals'=>'Fortis Hospital (2 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>true,'description'=>'Krisumi Waterfall Residences is a premium Indo-Japanese collaboration project in Sector 36A, Mohali. Ultra-luxury 3 & 4 BHK apartments with Japanese precision engineering, a spectacular waterfall feature, spa and world-class amenities. A truly international address in Tricity.'],
                    ['title'=>'Krisumi The Waterfall Suites','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 36A, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>200,'available_units'=>140,'price_from'=>18000000,'price_to'=>45000000,'possession_date'=>'2027-09-30','rera_id'=>'PBRERA-SAS81-PR0365','total_towers'=>4,'floors_per_tower'=>'22','latitude'=>30.6962,'longitude'=>76.6972,'amenities'=>'Private Terrace,Japanese Spa,Butler Service,Infinity Pool,Private Dining,Smart Home,High-Speed Elevators,24x7 Security','nearby_schools'=>'Strawberry Fields (2 km)','nearby_hospitals'=>'Fortis Hospital (2 km)','metro_distance'=>'4 km from Chandigarh','is_featured'=>true,'description'=>'Krisumi The Waterfall Suites offers the pinnacle of luxury living in Tricity — ultra-premium 4 & 5 BHK penthouses and sky suites with Japanese spa, butler service and infinity pool overlooking a curated Japanese landscape.'],
                ],
            ],

            /* ── 29. Tricity Infratech ───────────────────────────────── */
            [
                'name'                     => 'Tricity Infratech',
                'company_name'             => 'Tricity Infratech Developers Pvt. Ltd.',
                'email'                    => 'info@tricityinfratech.in',
                'phone'                    => '+91 98720 77777',
                'website'                  => 'https://www.tricityinfratech.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS79-PR0455',
                'cities_operating'         => 'Zirakpur,Mohali,Chandigarh,Panchkula',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Tricity Infratech is a local Zirakpur developer with comprehensive knowledge of the Tricity real estate market. They have delivered residential apartments across Zirakpur at competitive prices. Their projects are popular among working professionals and families seeking affordable homes near Chandigarh.',
                'projects'                 => [
                    ['title'=>'Tricity Heights','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Airport Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>300,'available_units'=>25,'price_from'=>3500000,'price_to'=>6800000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS79-PR0455','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.6568,'longitude'=>76.8232,'amenities'=>'Gymnasium,Kids Play Area,Power Backup,24x7 Security,Landscaped Garden,Car Parking,CCTV','nearby_schools'=>'Innocent Hearts School (1 km)','nearby_hospitals'=>'Civil Hospital Zirakpur (3 km)','metro_distance'=>'5 km from Airport','is_featured'=>false,'description'=>'Tricity Heights on Airport Road, Zirakpur offers 2 & 3 BHK apartments at competitive prices. Well-connected location near Chandigarh International Airport with essential amenities.'],
                    ['title'=>'Tricity Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>220,'available_units'=>18,'price_from'=>3000000,'price_to'=>5500000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR0460','total_towers'=>6,'floors_per_tower'=>'8','latitude'=>30.6460,'longitude'=>76.8190,'amenities'=>'Park,Kids Play Area,Security,Power Backup,Parking','nearby_schools'=>'Ryan International School (2 km)','nearby_hospitals'=>'Mukat Hospital (5 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Tricity Residency on VIP Road, Zirakpur offers budget-friendly 2 BHK apartments in a safe, gated community.'],
                    ['title'=>'Tricity Green Enclave','project_type'=>'Residential','status'=>'Under Construction','address'=>'Baltana, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>180,'available_units'=>120,'price_from'=>2500000,'price_to'=>4500000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS79-PR0465','total_towers'=>5,'floors_per_tower'=>'7','latitude'=>30.6358,'longitude'=>76.8058,'amenities'=>'Park,Kids Play Area,Security,Power Backup,Parking,Community Hall','nearby_schools'=>'Government School (1.5 km)','nearby_hospitals'=>'Homecare Hospital (5 km)','metro_distance'=>'10 km from Chandigarh','is_featured'=>false,'description'=>'Tricity Green Enclave in Baltana is an upcoming affordable housing project. Budget-friendly 1 & 2 BHK apartments for first-time buyers.'],
                ],
            ],

            /* ── 30. Conscient Infrastructure ───────────────────────── */
            [
                'name'                     => 'Conscient Infrastructure',
                'company_name'             => 'Conscient Infrastructure Pvt. Ltd.',
                'email'                    => 'info@conscient.in',
                'phone'                    => '+91 124 458 4800',
                'website'                  => 'https://www.conscient.in',
                'city'                     => 'Mohali',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS81-PR0280',
                'cities_operating'         => 'Mohali,Gurugram,Delhi',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'description'              => 'Conscient Infrastructure is known for delivering quality residential projects with a focus on thoughtful design and customer satisfaction. In Mohali, their projects Habitat and Hines are among the most acclaimed residential developments. They emphasize green building practices and a superior post-sales experience.',
                'projects'                 => [
                    ['title'=>'Conscient Habitat','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 82, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>400,'available_units'=>35,'price_from'=>7000000,'price_to'=>15000000,'possession_date'=>'2022-09-30','rera_id'=>'PBRERA-SAS81-PR0280','total_towers'=>9,'floors_per_tower'=>'16','latitude'=>30.7162,'longitude'=>76.7060,'amenities'=>'Clubhouse,Swimming Pool,Gymnasium,Tennis Court,Kids Play Area,Jogging Track,Power Backup,24x7 Security,Amphitheatre,Landscaped Gardens','nearby_schools'=>'Wave International School (1 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'9 km from Chandigarh','is_featured'=>true,'description'=>'Conscient Habitat in Sector 82, Mohali is a well-designed residential project offering 2, 3 & 4 BHK apartments. Quality construction, premium finishes and comprehensive amenities in a green, thoughtfully planned community.'],
                    ['title'=>'Conscient Hines Elevate','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 80, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>360,'available_units'=>200,'price_from'=>9500000,'price_to'=>22000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS81-PR0288','total_towers'=>8,'floors_per_tower'=>'18','latitude'=>30.7158,'longitude'=>76.7055,'amenities'=>'Rooftop Club,Infinity Pool,Gymnasium,Smart Home,EV Charging,Kids Pool,Yoga Deck,24x7 Security,Power Backup,High-Speed Elevators','nearby_schools'=>'Wave International School (1 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'9 km from Chandigarh','is_featured'=>true,'description'=>'Conscient Hines Elevate in Sector 80, Mohali is a joint venture with Hines — the global real estate firm. Ultra-premium 3 & 4 BHK smart apartments with rooftop club, infinity pool and international-grade construction. Possession December 2026.'],
                    ['title'=>'Conscient Business Tower','project_type'=>'Commercial','status'=>'Ready to Move','address'=>'Sector 74, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>100,'available_units'=>12,'price_from'=>10000000,'price_to'=>35000000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS81-PR0285','total_towers'=>1,'floors_per_tower'=>'16','latitude'=>30.7058,'longitude'=>76.7042,'amenities'=>'Grade A Office,High-Speed Elevators,Power Backup,24x7 Security,Conference Rooms,Food Court,Ample Parking,High-Speed Internet','nearby_schools'=>null,'nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'Conscient Business Tower in Sector 74, Mohali is a premium commercial development offering Grade-A office spaces. Ideal for IT companies, startups and corporates seeking a prestigious Mohali business address.'],
                ],
            ],

        ]; // end $builders

        foreach ($builders as $data) {
            $projects = $data['projects'];
            unset($data['projects']);

            $baseSlug = Str::slug($data['company_name']);
            $slug = $baseSlug; $cnt = 1;
            while (DB::table('builders')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $cnt++;
            }

            if (DB::table('builders')->where('email', $data['email'])->exists()) {
                $this->command->warn("  [skip] {$data['company_name']} — already exists");
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

            foreach ($projects as $project) {
                if (DB::table('builder_projects')
                    ->where('builder_id', $builderId)
                    ->where('title', $project['title'])
                    ->exists()) {
                    $this->command->warn("    [skip] {$project['title']} — already exists");
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
                    'views_count'      => rand(80, 2000),
                    'leads_count'      => rand(5, 100),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
                $this->command->line("    ✓ Project: {$project['title']}");
            }
        }
    }
}
