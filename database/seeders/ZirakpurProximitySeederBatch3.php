<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * ZirakpurProximitySeederBatch3
 *
 * Completes full 50 km coverage from Srishti Avenue, Dhakoli (lat 30.6400, lng 76.8190)
 *
 * Gap fills (missed in Batch 2):
 *   Manimajra/Daria (9.9 km NNE), Mohali IT Park Sec 66-68 (10.5 km NW), Pinjore (20.4 km NNE)
 *
 * Ring 10 : 25–35 km  (Kalka, Rajpura, CU Gharuan, Ambala, Kurali, Barotiwala)
 * Ring 11 : 35–50 km  (Baddi, Morinda, Solan, Fatehgarh Sahib, Nalagarh, Ropar)
 * Bonus    : 50–55 km  (Patiala — major city just beyond radius)
 */
class ZirakpurProximitySeederBatch3 extends Seeder
{
    const HOME_LAT = 30.6400;
    const HOME_LNG = 76.8190;

    public function run(): void
    {
        $this->command->info('🏠 Seeding Batch 3: completing 50 km coverage from Srishti Avenue, Dhakoli...');
        $this->seedDealers();
        $this->seedBuilders();
        $this->seedProperties();
        $this->command->info('✅ ZirakpurProximitySeederBatch3 complete!');
    }

    // =========================================================================
    // DEALERS — 60 records covering all 50 km zones
    // =========================================================================
    private function seedDealers(): void
    {
        $this->command->info('  → Seeding 60 dealers across 50 km radius...');

        $dealers = [

            // ── GAP FILL: Manimajra / Daria Chandigarh (9.9 km NNE) ─────────
            ['first_name'=>'Arun','last_name'=>'Gupta','company_name'=>'Gupta Properties Manimajra','phone'=>'+91 98151 36001','email'=>'arun.gupta@guptapropertiesmanimajra.com','bio'=>'Gupta Properties is the leading agency in Manimajra, the eastern extension of Chandigarh UT. Expert in CHB flats, PUDA colony homes and resale properties on the UT–Punjab border at ~10 km from Dhakoli.','specializations'=>'Manimajra CHB,PUDA Colony,Chandigarh UT Border','operating_cities'=>'Chandigarh,Panchkula,Zirakpur'],
            ['first_name'=>'Prem','last_name'=>'Nath','company_name'=>'Nath Estate Manimajra Chandigarh','phone'=>'+91 97800 36002','email'=>'prem.nath@nathestatemanimajra.in','bio'=>'Nath Estate has served Manimajra and Daria residents for 16 years. Expert in government-allotted flats, DDA-type housing and builder floors in this transitional UT zone.','specializations'=>'Govt Allotted Flats,Builder Floors,Manimajra','operating_cities'=>'Chandigarh,Panchkula'],
            ['first_name'=>'Vikesh','last_name'=>'Kapoor','company_name'=>'Kapoor Realty Daria Chandigarh','phone'=>'+91 99143 36003','email'=>'vikesh.kapoor@kapoorealrty-daria.in','bio'=>'Kapoor Realty covers Daria and the Chandigarh–Panchkula industrial belt. Specialist in commercial properties, workshop units and affordable housing near Daria bypass.','specializations'=>'Daria,Commercial,Affordable Housing,Chandigarh','operating_cities'=>'Chandigarh,Panchkula'],

            // ── GAP FILL: Mohali IT Park / Sector 66-68 (10.5 km NW) ─────────
            ['first_name'=>'Saurabh','last_name'=>'Jain','company_name'=>'Jain Properties IT Park Mohali','phone'=>'+91 95010 36004','email'=>'saurabh.jain@jainproperties-itpark.com','bio'=>'Jain Properties is the top-rated agency near Mohali IT Park. Expert in studio to 3 BHK apartments and co-living spaces catering to IT professionals working in the STPI/IT City complex.','specializations'=>'IT Park Housing,Studio Apartments,Mohali Sector 66','operating_cities'=>'Mohali,Chandigarh'],
            ['first_name'=>'Amit','last_name'=>'Verma','company_name'=>'Verma Homes Mohali Sector 66','phone'=>'+91 98726 36005','email'=>'amit.verma@vermahomes-sec66.com','bio'=>'Verma Homes specialises in Sectors 66–68 of Mohali adjacent to the IT Park. Expert in premium apartments, branded-township units and SCO plots for IT companies and their employees.','specializations'=>'Sector 66-68 Mohali,Premium Apartments,SCO Plots','operating_cities'=>'Mohali,Chandigarh'],
            ['first_name'=>'Nitin','last_name'=>'Garg','company_name'=>'Garg Estate IT City Mohali','phone'=>'+91 98763 36006','email'=>'nitin.garg@gargestate-itcity.in','bio'=>'Garg Estate covers Mohali IT City and the GMADA IT Park zone comprehensively. Expert in GMADA-approved plots, under-construction IT township apartments and resale premium flats.','specializations'=>'GMADA IT Park,IT City Mohali,GMADA Plots','operating_cities'=>'Mohali,Chandigarh,Zirakpur'],

            // ── GAP FILL: Pinjore (20.4 km NNE, Haryana) ────────────────────
            ['first_name'=>'Suresh','last_name'=>'Verma','company_name'=>'Verma Properties Pinjore','phone'=>'+91 98765 36007','email'=>'suresh.verma@vermapropertiespinjore.com','bio'=>'Verma Properties is the most trusted agency in Pinjore, ~20 km from Dhakoli. Expert in hill-adjacent residential plots, builder floors and affordable apartments on the Pinjore–Kalka highway.','specializations'=>'Pinjore Properties,Hill Adjacent,Builder Floors','operating_cities'=>'Pinjore,Kalka,Panchkula'],
            ['first_name'=>'Mohan','last_name'=>'Kumar','company_name'=>'Kumar Estate Pinjore Kalka Road','phone'=>'+91 98157 36008','email'=>'mohan.kumar@kumarestatepinjore.in','bio'=>'Kumar Estate covers the Pinjore–Kalka belt and Yadavindra Gardens vicinity. Expert in weekend homes, plotted colonies and holiday cottages in this scenic hill-approach zone.','specializations'=>'Weekend Homes,Pinjore-Kalka Belt,Holiday Cottages','operating_cities'=>'Pinjore,Kalka,Chandigarh'],

            // ── RING 10: Kalka (26 km NNE, Haryana) ──────────────────────────
            ['first_name'=>'Ramesh','last_name'=>'Kumar','company_name'=>'Kumar Properties Kalka','phone'=>'+91 98761 36009','email'=>'ramesh.kumar@kumarproperties-kalka.com','bio'=>'Kumar Properties is the leading real estate firm in Kalka, ~26 km from Dhakoli. Expert in hill properties, residential plots and affordable apartments in this gateway town to Shimla.','specializations'=>'Kalka Properties,Hill Town,Residential Plots','operating_cities'=>'Kalka,Pinjore,Panchkula'],
            ['first_name'=>'Sunil','last_name'=>'Thakur','company_name'=>'Thakur Estate Kalka','phone'=>'+91 98760 36010','email'=>'sunil.thakur@thakurestate-kalka.in','bio'=>'Thakur Estate is a full-service property firm in Kalka with 14 years of local expertise. Covering independent houses, construction plots and affordable flats for buyers seeking Chandigarh proximity.','specializations'=>'Independent Houses,Construction Plots,Kalka','operating_cities'=>'Kalka,Chandigarh,Pinjore'],
            ['first_name'=>'Vikas','last_name'=>'Kapoor','company_name'=>'Kapoor Hill Realty Kalka','phone'=>'+91 98158 36011','email'=>'vikas.kapoor@kapoorhillrealty.com','bio'=>'Kapoor Hill Realty specialises in hill-adjacent properties in Kalka and Parwanoo belt. Expert in weekend homes, holiday cottages and investment plots with Shivalik views.','specializations'=>'Weekend Homes,Shivalik View Plots,Kalka-Parwanoo','operating_cities'=>'Kalka,Pinjore,Chandigarh'],
            ['first_name'=>'Anil','last_name'=>'Sood','company_name'=>'Sood Properties Kalka Pinjore','phone'=>'+91 98140 36012','email'=>'anil.sood@soodproperties-kalkap.com','bio'=>'Sood Properties covers the full Kalka–Pinjore corridor. Expert in HUDA plots, industrial zone properties and affordable residential developments in this fast-growing Haryana belt.','specializations'=>'HUDA Plots,Industrial Zone,Kalka-Pinjore Corridor','operating_cities'=>'Kalka,Pinjore,Haryana'],
            ['first_name'=>'Naveen','last_name'=>'Sharma','company_name'=>'Sharma Hill Homes Kalka','phone'=>'+91 98143 36013','email'=>'naveen.sharma@sharmahillhomes-kalka.in','bio'=>'Sharma Hill Homes is a boutique agency in Kalka catering to Chandigarh professionals seeking hill proximity. Expert in gated complexes, villa plots and ready-to-move builder floors.','specializations'=>'Gated Complexes,Villa Plots,Hill Proximity','operating_cities'=>'Kalka,Chandigarh,Panchkula'],

            // ── RING 10: Chandigarh University Gharuan (28 km WNW, Punjab) ────
            ['first_name'=>'Harpreet','last_name'=>'Mann','company_name'=>'Mann Properties Gharuan','phone'=>'+91 98145 36014','email'=>'harpreet.mann@mannpropertiesgharuan.com','bio'=>'Mann Properties is the market leader near Chandigarh University, Gharuan. Expert in student housing, faculty residences and investment apartments in the booming university belt ~28 km from Dhakoli.','specializations'=>'Student Housing,Faculty Residences,CU Gharuan Belt','operating_cities'=>'Gharuan,Kharar,Mohali'],
            ['first_name'=>'Sukhwinder','last_name'=>'Brar','company_name'=>'Brar Homes Chandigarh University Area','phone'=>'+91 98141 36015','email'=>'sukhwinder.brar@brarhomes-cuarea.in','bio'=>'Brar Homes covers the Chandigarh University Gharuan zone comprehensively. Expert in hostel-style apartments, affordable 1 BHK investments and plot colonies in the CU peripheral area.','specializations'=>'CU Adjacent Housing,1 BHK Investments,Gharuan Plots','operating_cities'=>'Gharuan,Kharar,Chandigarh'],
            ['first_name'=>'Gursharan','last_name'=>'Grewal','company_name'=>'Grewal Estate Gharuan Kharar','phone'=>'+91 97801 36016','email'=>'gursharan.grewal@grewalegharuan.com','bio'=>'Grewal Estate serves the extended Kharar–Gharuan corridor. Expert in bulk property purchase for hostels, student PG accommodations and affordable flats near Chandigarh University.','specializations'=>'Bulk Purchase,Hostel Properties,PG Accommodations','operating_cities'=>'Gharuan,Kharar,Mohali'],
            ['first_name'=>'Parminder','last_name'=>'Sran','company_name'=>'Sran Properties University Belt','phone'=>'+91 99145 36017','email'=>'parminder.sran@sranproperties-unibelt.in','bio'=>'Sran Properties covers the wider university belt from Kharar to Gharuan. Expert in land acquisition for hostel development and residential colony planning near educational institutes.','specializations'=>'University Belt,Land Acquisition,Hostel Development','operating_cities'=>'Gharuan,Kharar,Chandigarh'],

            // ── RING 10: Rajpura (28 km SW, Punjab) ──────────────────────────
            ['first_name'=>'Gurjant','last_name'=>'Singh','company_name'=>'Singh Properties Rajpura','phone'=>'+91 98151 36018','email'=>'gurjant.singh@singhpropertiesrajpura.com','bio'=>'Singh Properties is the top agency in Rajpura, ~28 km from Dhakoli. Expert in GT Road commercial properties, industrial plots and affordable residential colonies in this thriving industrial town.','specializations'=>'GT Road Commercial,Industrial Plots,Rajpura','operating_cities'=>'Rajpura,Patiala,Zirakpur'],
            ['first_name'=>'Harvinder','last_name'=>'Malhotra','company_name'=>'Malhotra Realty Rajpura','phone'=>'+91 98720 36019','email'=>'harvinder.malhotra@malhotrarealtyrajpura.in','bio'=>'Malhotra Realty has 18 years of expertise in Rajpura\'s real estate market. Expert in factory worker housing, affordable plots and 2 BHK apartments for the town\'s growing industrial workforce.','specializations'=>'Worker Housing,Affordable Plots,Rajpura Industrial','operating_cities'=>'Rajpura,Derabassi'],
            ['first_name'=>'Satnam','last_name'=>'Mehta','company_name'=>'Mehta Estate Rajpura','phone'=>'+91 98763 36020','email'=>'satnam.mehta@mehtaestate-rajpura.com','bio'=>'Mehta Estate is a full-service agency in Rajpura covering residential and commercial properties. Expert in pre-launch bookings for new township projects near the Rajpura–Chandigarh expressway.','specializations'=>'New Launches,Township Projects,Rajpura-Chandigarh','operating_cities'=>'Rajpura,Patiala,Derabassi'],
            ['first_name'=>'Baldev','last_name'=>'Sharma','company_name'=>'Sharma Properties GT Road Rajpura','phone'=>'+91 95010 36021','email'=>'baldev.sharma@sharmaproperties-gtroad.in','bio'=>'Sharma Properties is the specialist for GT Road commercial frontage in Rajpura. Expert in showroom plots, SCO units and industrial land along the high-traffic GT Road corridor.','specializations'=>'GT Road Frontage,SCO Units,Industrial Land','operating_cities'=>'Rajpura,Patiala'],
            ['first_name'=>'Amarjit','last_name'=>'Kang','company_name'=>'Kang Real Estate Rajpura','phone'=>'+91 98726 36022','email'=>'amarjit.kang@kangrealestate-rajpura.in','bio'=>'Kang Real Estate is a trusted Rajpura agency known for honest dealings in residential plots and affordable apartments. 12 years of expertise in Rajpura\'s developing colony zones.','specializations'=>'Residential Plots,Affordable Apartments,Rajpura Colonies','operating_cities'=>'Rajpura,Derabassi,Zirakpur'],

            // ── RING 10: Ambala Cantt / Ambala City (29-30 km S, Haryana) ────
            ['first_name'=>'Rajendra','last_name'=>'Goel','company_name'=>'Goel Properties Ambala Cantt','phone'=>'+91 98765 36023','email'=>'rajendra.goel@goelproperties-ambalacantt.com','bio'=>'Goel Properties is the most respected agency in Ambala Cantonment, ~29 km from Dhakoli. Expert in defence housing, premium independent houses and cantonment-adjacent residential properties.','specializations'=>'Defence Housing,Ambala Cantt,Premium Independent Houses','operating_cities'=>'Ambala,Chandigarh,Panchkula'],
            ['first_name'=>'Suresh','last_name'=>'Bansal','company_name'=>'Bansal Estate Ambala','phone'=>'+91 98155 36024','email'=>'suresh.bansal@bansalestate-ambala.in','bio'=>'Bansal Estate is a veteran Ambala agency with 24 years of market experience. Expert in HUDA plots, sector-wise residential properties and commercial SCO shops across Ambala city.','specializations'=>'HUDA Plots,Ambala Sectors,Commercial SCO','operating_cities'=>'Ambala,Haryana'],
            ['first_name'=>'Vinod','last_name'=>'Kukreja','company_name'=>'Kukreja Realty Ambala','phone'=>'+91 98157 36025','email'=>'vinod.kukreja@kukrejarealtyambala.com','bio'=>'Kukreja Realty is a mid-segment specialist in Ambala covering Sectors 1–9. Expert in affordable 2 & 3 BHK apartments and HUDA-allotted residential plots for first-time buyers.','specializations'=>'Affordable 2-3 BHK,HUDA Allotments,Ambala Sectors','operating_cities'=>'Ambala,Chandigarh'],
            ['first_name'=>'Ramesh','last_name'=>'Aggarwal','company_name'=>'Aggarwal Properties Ambala City','phone'=>'+91 98728 36026','email'=>'ramesh.aggarwal@aggarwalproperties-ambala.in','bio'=>'Aggarwal Properties covers Ambala City and its commercial belt. Expert in commercial plots on Ambala–Chandigarh National Highway, showroom spaces and industrial-adjacent residential zones.','specializations'=>'NH Commercial Plots,Showroom Spaces,Ambala City','operating_cities'=>'Ambala,Haryana'],
            ['first_name'=>'Naresh','last_name'=>'Singhal','company_name'=>'Singhal Estate Ambala','phone'=>'+91 98729 36027','email'=>'naresh.singhal@singhalestate-ambala.in','bio'=>'Singhal Estate is a premium advisory firm in Ambala serving HNI buyers and investors. Expert in luxury independent houses and large-format commercial plots in Ambala\'s prime sectors.','specializations'=>'HNI Buyers,Luxury Houses,Large Commercial Plots','operating_cities'=>'Ambala,Chandigarh,Panchkula'],

            // ── RING 10: Kurali (32 km NW, Punjab) ───────────────────────────
            ['first_name'=>'Baljinder','last_name'=>'Cheema','company_name'=>'Cheema Estate Kurali','phone'=>'+91 98762 36028','email'=>'baljinder.cheema@cheemaestate-kurali.com','bio'=>'Cheema Estate is the leading agency in Kurali town, ~32 km from Dhakoli on NH-7. Expert in affordable plotted colonies and residential projects popular with industrial workers and commuters.','specializations'=>'Kurali Plots,NH-7 Corridor,Affordable Housing','operating_cities'=>'Kurali,Kharar,Mohali'],
            ['first_name'=>'Jagtar','last_name'=>'Singh','company_name'=>'Singh Properties Kurali','phone'=>'+91 95927 36029','email'=>'jagtar.singh@singhproperties-kurali.in','bio'=>'Singh Properties has deep expertise in Kurali\'s expanding real estate market. Expert in land acquisition, farm-to-colony conversion projects and investment-grade plots near the Kurali bypass.','specializations'=>'Land Acquisition,Farm Conversion,Kurali Bypass','operating_cities'=>'Kurali,Morinda,Kharar'],
            ['first_name'=>'Harjit','last_name'=>'Sohal','company_name'=>'Sohal Realty Kurali','phone'=>'+91 98724 36030','email'=>'harjit.sohal@sohalrealty-kurali.com','bio'=>'Sohal Realty is a trusted name in Kurali with strong connectivity to Chandigarh University students and faculty. Offering hostel-type investments and affordable family homes in growing colonies.','specializations'=>'Affordable Homes,Kurali Colonies,Investment Plots','operating_cities'=>'Kurali,Kharar,Chandigarh'],
            ['first_name'=>'Gurjinder','last_name'=>'Brar','company_name'=>'Brar Properties Kurali','phone'=>'+91 98725 36031','email'=>'gurjinder.brar@brarproperties-kurali.in','bio'=>'Brar Properties covers Kurali and the Morinda Road belt. Full-service agency for residential, commercial and agricultural land transactions in this under-radar growth corridor.','specializations'=>'Kurali-Morinda Belt,Agricultural Land,Residential','operating_cities'=>'Kurali,Morinda,Punjab'],

            // ── RING 10: Barotiwala / Baddi near (34 km N, HP) ───────────────
            ['first_name'=>'Deepak','last_name'=>'Negi','company_name'=>'Negi Real Estate Baddi HP','phone'=>'+91 98766 36032','email'=>'deepak.negi@negirealestate-baddi.com','bio'=>'Negi Real Estate is the top residential property agency in Baddi, HP. Expert in worker housing for Baddi\'s 1,000+ manufacturing companies and mid-segment apartments for supervisory staff.','specializations'=>'Worker Housing,Industrial Housing,Baddi HP','operating_cities'=>'Baddi,Barotiwala,Nalagarh'],
            ['first_name'=>'Suresh','last_name'=>'Rana','company_name'=>'Rana Properties Baddi','phone'=>'+91 98769 36033','email'=>'suresh.rana@ranaproperties-baddi.in','bio'=>'Rana Properties has 17 years of experience in Baddi\'s property market. Expert in company leasing, staff-quarter management and affordable apartments for the growing industrial workforce.','specializations'=>'Company Leasing,Staff Quarters,Baddi Industrial','operating_cities'=>'Baddi,Nalagarh,HP'],
            ['first_name'=>'Vinod','last_name'=>'Kashyap','company_name'=>'Kashyap Estate Baddi Barotiwala','phone'=>'+91 99149 36034','email'=>'vinod.kashyap@kashyapestate-baddi.com','bio'=>'Kashyap Estate covers both Baddi and Barotiwala comprehensively. Expert in industrial plots, warehouse spaces and premium residential apartments in HP\'s largest industrial belt.','specializations'=>'Industrial Plots,Warehouse Spaces,Baddi-Barotiwala','operating_cities'=>'Baddi,Barotiwala,HP'],
            ['first_name'=>'Anil','last_name'=>'Sharma','company_name'=>'Sharma Homes Barotiwala','phone'=>'+91 98728 36035','email'=>'anil.sharma@sharmahomes-barotiwala.in','bio'=>'Sharma Homes is the preferred agency for Barotiwala residential properties. Covering affordable flats and plots for the 50,000+ industrial workers in the Barotiwala corridor.','specializations'=>'Barotiwala Residences,Affordable Flats,Industrial Workers','operating_cities'=>'Barotiwala,Baddi,HP'],
            ['first_name'=>'Rajesh','last_name'=>'Chauhan','company_name'=>'Chauhan Properties Baddi HP','phone'=>'+91 98729 36036','email'=>'rajesh.chauhan@chauhan-properties-baddi.in','bio'=>'Chauhan Properties is a Baddi-based agency with deep connections to HP\'s pharmaceutical and FMCG industries. Expert in premium housing for corporate executives and senior management.','specializations'=>'Corporate Housing,Executive Apartments,Baddi Premium','operating_cities'=>'Baddi,Nalagarh,Chandigarh'],

            // ── RING 11: Morinda (36 km WNW, Punjab) ─────────────────────────
            ['first_name'=>'Gurbax','last_name'=>'Dhaliwal','company_name'=>'Dhaliwal Properties Morinda','phone'=>'+91 98762 36037','email'=>'gurbax.dhaliwal@dhaliwalproperties-morinda.com','bio'=>'Dhaliwal Properties is the most active agency in Morinda, ~36 km from Dhakoli. Expert in affordable residential plots, 2 BHK apartments and agricultural land in this peaceful Punjab town.','specializations'=>'Morinda Plots,Affordable Housing,Agricultural Land','operating_cities'=>'Morinda,Kurali,Fatehgarh Sahib'],
            ['first_name'=>'Jaswant','last_name'=>'Rana','company_name'=>'Rana Realty Morinda','phone'=>'+91 98152 36038','email'=>'jaswant.rana@ranarealty-morinda.in','bio'=>'Rana Realty has served Morinda\'s property buyers for 13 years. Expert in connecting NRIs with their ancestral village properties and facilitating smooth land transactions in rural Punjab.','specializations'=>'NRI Ancestral Properties,Village Land,Morinda','operating_cities'=>'Morinda,Fatehgarh Sahib,Punjab'],
            ['first_name'=>'Paramjit','last_name'=>'Sidhu','company_name'=>'Sidhu Estate Morinda','phone'=>'+91 98154 36039','email'=>'paramjit.sidhu@sidhu-estate-morinda.com','bio'=>'Sidhu Estate is a comprehensive property firm in Morinda covering plots, flats and commercial shops. Known for transparent land measurement and clear title verification services.','specializations'=>'Land Verification,Plots,Commercial Shops,Morinda','operating_cities'=>'Morinda,Kurali,Punjab'],
            ['first_name'=>'Daljit','last_name'=>'Toor','company_name'=>'Toor Properties Morinda','phone'=>'+91 97801 36040','email'=>'daljit.toor@toorproperties-morinda.in','bio'=>'Toor Properties covers Morinda and surrounding villages. Expert in agricultural land advisory, land acquisition for industrial clients and affordable colony development in Morinda tehsil.','specializations'=>'Agricultural Land,Industrial Acquisition,Colony Development','operating_cities'=>'Morinda,Punjab'],

            // ── RING 11: Solan HP (40 km NE) ─────────────────────────────────
            ['first_name'=>'Rajesh','last_name'=>'Sood','company_name'=>'Sood Hills Realty Solan','phone'=>'+91 98760 36041','email'=>'rajesh.sood@soodhillsrealty-solan.com','bio'=>'Sood Hills Realty is the premier property agency in Solan, HP — ~40 km from Dhakoli. Expert in hill villas, apple orchard lands, weekend cottages and luxury apartments with Himalayan views.','specializations'=>'Hill Villas,Apple Orchard Land,Weekend Cottages,Solan','operating_cities'=>'Solan,Chandigarh,HP'],
            ['first_name'=>'Naveen','last_name'=>'Thakur','company_name'=>'Thakur Estate Solan HP','phone'=>'+91 98155 36042','email'=>'naveen.thakur@thakurestate-solan.in','bio'=>'Thakur Estate is a trusted Solan property firm with 20 years of hill real estate experience. Expert in HIMUDA-approved plots, government housing estates and independent villa construction in Solan.','specializations'=>'HIMUDA Plots,Govt Housing,Solan Villas','operating_cities'=>'Solan,Baddi,HP'],
            ['first_name'=>'Vikas','last_name'=>'Sharma','company_name'=>'Sharma Properties Solan Hills','phone'=>'+91 98158 36043','email'=>'vikas.sharma@sharmaproperties-solanhills.in','bio'=>'Sharma Properties is a premium hill real estate specialist in Solan. Expert in bungalow plots, forest-adjacent land parcels and investment properties for HNI clients seeking quiet hill living.','specializations'=>'Premium Hill Plots,Forest Adjacent Land,HNI Buyers Solan','operating_cities'=>'Solan,HP,Chandigarh'],
            ['first_name'=>'Dinesh','last_name'=>'Verma','company_name'=>'Verma Hills Properties Solan','phone'=>'+91 98140 36044','email'=>'dinesh.verma@vermahillsproperties-solan.com','bio'=>'Verma Hills Properties covers Solan district comprehensively including Parwanoo, Kasauli Road and Dagshai. Specialist in holiday home development and retirement property advisory in HP hills.','specializations'=>'Holiday Homes,Retirement Properties,Solan District','operating_cities'=>'Solan,Parwanoo,Kasauli,HP'],

            // ── RING 11: Fatehgarh Sahib (41 km W, Punjab) ───────────────────
            ['first_name'=>'Kuldeep','last_name'=>'Gill','company_name'=>'Gill Properties Fatehgarh Sahib','phone'=>'+91 98143 36045','email'=>'kuldeep.gill@gillproperties-fgs.com','bio'=>'Gill Properties is the leading agency in Fatehgarh Sahib, ~41 km from Dhakoli. Expert in affordable plots, residential colonies and commercial properties in this historically significant Punjab district.','specializations'=>'Fatehgarh Sahib Properties,Affordable Plots,Residential','operating_cities'=>'Fatehgarh Sahib,Morinda,Punjab'],
            ['first_name'=>'Ranjit','last_name'=>'Mann','company_name'=>'Mann Estate Fatehgarh Sahib','phone'=>'+91 98141 36046','email'=>'ranjit.mann@mannestate-fgs.in','bio'=>'Mann Estate is a family-run agency in Fatehgarh Sahib with deep community trust. Expert in NRI-owned properties, inheritance land settlements and new residential project advisory.','specializations'=>'NRI Properties,Inheritance Settlements,FGS Residential','operating_cities'=>'Fatehgarh Sahib,Punjab'],
            ['first_name'=>'Sukhjinder','last_name'=>'Sandhu','company_name'=>'Sandhu Realty Fatehgarh Sahib','phone'=>'+91 98145 36047','email'=>'sukhjinder.sandhu@sandhurealty-fgs.in','bio'=>'Sandhu Realty covers Fatehgarh Sahib and the Sirhind industrial belt. Expert in industrial plot advisory, warehouse development and affordable worker housing in this manufacturing zone.','specializations'=>'Industrial Plots,Sirhind Belt,Worker Housing','operating_cities'=>'Fatehgarh Sahib,Sirhind,Punjab'],

            // ── RING 11: Nalagarh HP (45 km NNW) ─────────────────────────────
            ['first_name'=>'Raj','last_name'=>'Kumar','company_name'=>'Kumar Properties Nalagarh HP','phone'=>'+91 98729 36048','email'=>'raj.kumar@kumarproperties-nalagarh.com','bio'=>'Kumar Properties is the go-to agency in Nalagarh, HP\'s industrial powerhouse ~45 km from Dhakoli. Expert in HIMUDA plots, industrial estate properties and affordable residential colonies for industrial workers.','specializations'=>'HIMUDA Plots,Industrial Estate,Nalagarh HP','operating_cities'=>'Nalagarh,Baddi,HP'],
            ['first_name'=>'Sunil','last_name'=>'Negi','company_name'=>'Negi Estate Nalagarh','phone'=>'+91 99143 36049','email'=>'sunil.negi@negiestate-nalagarh.in','bio'=>'Negi Estate is a veteran agency in Nalagarh with 19 years of property transaction history. Expert in HPRIDC industrial plots, pharmaceutical company staff housing and affordable builder floors.','specializations'=>'HPRIDC Plots,Staff Housing,Builder Floors Nalagarh','operating_cities'=>'Nalagarh,Baddi,Solan'],
            ['first_name'=>'Arun','last_name'=>'Thakur','company_name'=>'Thakur Realty Nalagarh HP','phone'=>'+91 98726 36050','email'=>'arun.thakur@thakurrealty-nalagarh.in','bio'=>'Thakur Realty is a dynamic agency in Nalagarh covering residential, commercial and industrial properties. Expert in bulk land acquisition for industrial clients setting up operations in HP\'s SIDCO zones.','specializations'=>'Bulk Land Acquisition,SIDCO Zones,Industrial HP','operating_cities'=>'Nalagarh,Baddi,HP'],

            // ── RING 11: Ropar / Rupnagar (46 km NW, Punjab) ─────────────────
            ['first_name'=>'Nirmal','last_name'=>'Sidhu','company_name'=>'Sidhu Properties Ropar','phone'=>'+91 98762 36051','email'=>'nirmal.sidhu@sidhuproperties-ropar.com','bio'=>'Sidhu Properties is the leading agency in Ropar (Rupnagar), ~46 km from Dhakoli. Expert in Sutlej-view residential properties, HUDA sector plots and affordable apartments in this historic Punjab town.','specializations'=>'Ropar Properties,Sutlej View,HUDA Plots','operating_cities'=>'Ropar,Chandigarh,Morinda'],
            ['first_name'=>'Gurdit','last_name'=>'Dhindsa','company_name'=>'Dhindsa Realty Ropar','phone'=>'+91 97800 36052','email'=>'gurdit.dhindsa@dhindsa-realty-ropar.in','bio'=>'Dhindsa Realty covers Rupnagar and the Anandpur Sahib belt. Expert in pilgrimage zone tourism properties, heritage havelis and agricultural land transactions in Rupnagar district.','specializations'=>'Rupnagar,Pilgrimage Zone,Heritage Properties,Agricultural Land','operating_cities'=>'Ropar,Anandpur Sahib,Punjab'],
            ['first_name'=>'Kanwaljit','last_name'=>'Brar','company_name'=>'Brar Estate Rupnagar','phone'=>'+91 99145 36053','email'=>'kanwaljit.brar@brarestate-rupnagar.in','bio'=>'Brar Estate is a trusted family firm in Rupnagar with 21 years of local expertise. Expert in residential plots, HUDA-allotted properties and affordable independent houses for Rupnagar\'s growing population.','specializations'=>'HUDA Allotments,Residential Plots,Affordable Houses','operating_cities'=>'Ropar,Morinda,Punjab'],
            ['first_name'=>'Gurpreet','last_name'=>'Sangha','company_name'=>'Sangha Properties Ropar Punjab','phone'=>'+91 98151 36054','email'=>'gurpreet.sangha@sanghaproperties-ropar.com','bio'=>'Sangha Properties is a modern agency in Ropar combining online listings with deep local knowledge. Specialists in student and faculty housing near IIT Ropar and the emerging university belt.','specializations'=>'IIT Ropar Housing,Student Belt,University Housing','operating_cities'=>'Ropar,Mohali,Chandigarh'],

            // ── BONUS: Patiala (53 km SW, Punjab) ────────────────────────────
            ['first_name'=>'Harpreet','last_name'=>'Kamboj','company_name'=>'Kamboj Properties Patiala','phone'=>'+91 98765 36055','email'=>'harpreet.kamboj@kambojproperties-patiala.com','bio'=>'Kamboj Properties is a leading agency in Patiala, ~53 km from Dhakoli. Expert in urban development authority properties, luxury independent houses and commercial investments in the royal city of Punjab.','specializations'=>'Patiala UDA Properties,Luxury Houses,Commercial','operating_cities'=>'Patiala,Rajpura,Punjab'],
            ['first_name'=>'Amarjit','last_name'=>'Arora','company_name'=>'Arora Estate Patiala','phone'=>'+91 98760 36056','email'=>'amarjit.arora@aroraepatiala.in','bio'=>'Arora Estate has 25 years of Patiala real estate heritage. Expert in the old city\'s haveli conversions, Model Town residential properties and investment-grade commercial plots along Sirhind Road.','specializations'=>'Model Town,Haveli Conversions,Sirhind Road Patiala','operating_cities'=>'Patiala,Punjab'],
            ['first_name'=>'Gurmail','last_name'=>'Dhaliwal','company_name'=>'Dhaliwal Properties Patiala','phone'=>'+91 98157 36057','email'=>'gurmail.dhaliwal@dhaliwalproperties-patiala.in','bio'=>'Dhaliwal Properties is Patiala\'s trusted agency for mid-budget buyers. Expert in Urban Estate Phase 1 & 2 properties, builder floors and plotted developments near AIIMS Patiala.','specializations'=>'Urban Estate Patiala,AIIMS Zone,Builder Floors','operating_cities'=>'Patiala,Rajpura,Punjab'],
            ['first_name'=>'Tejinder','last_name'=>'Sidhu','company_name'=>'Sidhu Realty Patiala','phone'=>'+91 95011 36058','email'=>'tejinder.sidhu@sidhu-realty-patiala.com','bio'=>'Sidhu Realty is a premium advisory firm in Patiala specialising in luxury villas, farmhouses and high-value commercial properties. Trusted by Patiala\'s elite families for 18 years.','specializations'=>'Luxury Villas,Farmhouses,Commercial Premium Patiala','operating_cities'=>'Patiala,Chandigarh'],
            ['first_name'=>'Balwinder','last_name'=>'Sehgal','company_name'=>'Sehgal Real Estate Patiala','phone'=>'+91 98762 36059','email'=>'balwinder.sehgal@sehgalrealestate-patiala.in','bio'=>'Sehgal Real Estate is the fastest-growing agency in Patiala with a strong digital presence. Expert in under-construction projects, pre-launch bookings and investor-grade properties across Patiala.','specializations'=>'Pre-Launch,Investor Properties,Digital Platform Patiala','operating_cities'=>'Patiala,Rajpura,Chandigarh'],
            ['first_name'=>'Ranjit','last_name'=>'Sharma','company_name'=>'Sharma Properties Patiala City','phone'=>'+91 98763 36060','email'=>'ranjit.sharma@sharmaproperties-patiala.in','bio'=>'Sharma Properties covers the entire Patiala city belt including Tripuri, Sanaur Road and Rajpura Road corridors. Expert in 2-4 BHK apartments, independent floors and large commercial plots.','specializations'=>'All Patiala Zones,2-4 BHK,Commercial Plots','operating_cities'=>'Patiala,Punjab'],
        ];

        foreach ($dealers as $d) {
            $baseSlug = Str::slug($d['company_name']);
            $slug = $baseSlug;
            $i = 1;
            while (DB::table('property_dealers')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            if (DB::table('property_dealers')->where('email', $d['email'])->exists()) {
                $this->command->line("    [skip] {$d['company_name']}");
                continue;
            }
            DB::table('property_dealers')->insert([
                'first_name'       => $d['first_name'],
                'last_name'        => $d['last_name'],
                'company_name'     => $d['company_name'],
                'phone'            => $d['phone'],
                'email'            => $d['email'],
                'password'         => Hash::make('Dealer@2024'),
                'slug'             => $slug,
                'bio'              => $d['bio'],
                'specializations'  => $d['specializations'],
                'operating_cities' => $d['operating_cities'],
                'status'           => 'active',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
        $this->command->info('    ✔ 60 dealers seeded.');
    }

    // =========================================================================
    // BUILDERS — 15 records + 28 projects
    // =========================================================================
    private function seedBuilders(): void
    {
        $this->command->info('  → Seeding 15 builders + 28 projects across 50 km radius...');

        $builders = [
            [
                'name'                     => 'Milestone Realtors Mohali',
                'company_name'             => 'Milestone Realtors (Mohali IT Park)',
                'email'                    => 'info@milestonerealtors-itpark.com',
                'phone'                    => '+91 98151 46001',
                'website'                  => 'https://www.milestonerealtors-itpark.com',
                'city'                     => 'Mohali',
                'established_year'         => '2009',
                'rera_registration'        => 'PBRERA-SAS79-PR2900',
                'cities_operating'         => 'Mohali,Chandigarh',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Milestone Realtors is a Mohali-based developer specialising in IT Park adjacent residences in Sectors 66–68. Known for smart studio and 1 BHK apartments preferred by IT professionals.',
            ],
            [
                'name'                     => 'Shivalik Buildcon Kalka',
                'company_name'             => 'Shivalik Buildcon Pvt. Ltd. (Kalka)',
                'email'                    => 'info@shivalik-buildcon-kalka.in',
                'phone'                    => '+91 97800 46002',
                'website'                  => 'https://www.shivalik-buildcon-kalka.in',
                'city'                     => 'Kalka',
                'established_year'         => '2004',
                'rera_registration'        => 'HRERA-PKL-2004-0180',
                'cities_operating'         => 'Kalka,Pinjore,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 11,
                'description'              => 'Shivalik Buildcon is a Kalka-based developer specialising in hill-adjacent residential projects. Their Shivalik Hill View series is popular with Chandigarh professionals seeking hill proximity without Shimla prices.',
            ],
            [
                'name'                     => 'Pinjore Garden Estates',
                'company_name'             => 'Pinjore Garden Estates Pvt. Ltd.',
                'email'                    => 'info@pinjoregardenestate.in',
                'phone'                    => '+91 99143 46003',
                'website'                  => 'https://www.pinjoregardenestate.in',
                'city'                     => 'Pinjore',
                'established_year'         => '2007',
                'rera_registration'        => 'HRERA-PKL-2007-0220',
                'cities_operating'         => 'Pinjore,Kalka,Chandigarh',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'Pinjore Garden Estates develops premium plotted colonies and villa townships in the scenic Pinjore valley. Known for Yadavindra Gardens proximity and excellent Shivalik hill views.',
            ],
            [
                'name'                     => 'Punjab Gateway Developers',
                'company_name'             => 'Punjab Gateway Developers Pvt. Ltd.',
                'email'                    => 'info@punjabgatewaydevelopers.com',
                'phone'                    => '+91 95010 46004',
                'website'                  => 'https://www.punjabgatewaydevelopers.com',
                'city'                     => 'Rajpura',
                'established_year'         => '2011',
                'rera_registration'        => 'PBRERA-SAS79-PR3000',
                'cities_operating'         => 'Rajpura,Derabassi,Patiala',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Punjab Gateway Developers is a Rajpura-based builder known for GT Road Township — affordable residential and commercial development targeting industrial workers and commuters on the Chandigarh–Ambala corridor.',
            ],
            [
                'name'                     => 'Ambala Heights Builders',
                'company_name'             => 'Ambala Heights Builders & Developers',
                'email'                    => 'info@ambalaheightsbuilders.in',
                'phone'                    => '+91 98726 46005',
                'website'                  => 'https://www.ambalaheightsbuilders.in',
                'city'                     => 'Ambala',
                'established_year'         => '2006',
                'rera_registration'        => 'HARERA-AMB-2006-0040',
                'cities_operating'         => 'Ambala,Chandigarh,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'Ambala Heights Builders is a reputed developer in Ambala with quality mid-segment apartment projects. Their Ambala Heights Tower series offers 2 & 3 BHK apartments near Ambala Cantonment at competitive pricing.',
            ],
            [
                'name'                     => 'Kurali Homes Developers',
                'company_name'             => 'Kurali Homes Developers Pvt. Ltd.',
                'email'                    => 'info@kurali-homes.in',
                'phone'                    => '+91 98763 46006',
                'website'                  => 'https://www.kurali-homes.in',
                'city'                     => 'Kurali',
                'established_year'         => '2012',
                'rera_registration'        => 'PBRERA-SAS79-PR3100',
                'cities_operating'         => 'Kurali,Kharar,Morinda',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Kurali Homes Developers focuses on affordable plotted colonies and builder floors near Kurali town on NH-7. Known for clear land titles and transparent pricing in this NH corridor growth zone.',
            ],
            [
                'name'                     => 'Baddi Housing Developers',
                'company_name'             => 'Baddi Housing Developers Pvt. Ltd.',
                'email'                    => 'info@baddi-housing.in',
                'phone'                    => '+91 98765 46007',
                'website'                  => 'https://www.baddi-housing.in',
                'city'                     => 'Baddi',
                'established_year'         => '2003',
                'rera_registration'        => 'HPRERA-SOL-2003-0050',
                'cities_operating'         => 'Baddi,Barotiwala,Nalagarh',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Baddi Housing Developers is HP\'s largest residential developer for the industrial belt. Their projects cater to 50,000+ industrial workers in Baddi — providing affordable, quality housing with essential amenities.',
            ],
            [
                'name'                     => 'Himalayan Heights Builders',
                'company_name'             => 'Himalayan Heights Builders (Nalagarh)',
                'email'                    => 'info@himalayan-heights-nalagarh.in',
                'phone'                    => '+91 98760 46008',
                'website'                  => 'https://www.himalayan-heights-nalagarh.in',
                'city'                     => 'Nalagarh',
                'established_year'         => '2008',
                'rera_registration'        => 'HPRERA-SOL-2008-0090',
                'cities_operating'         => 'Nalagarh,Baddi,Solan',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Himalayan Heights Builders develops quality mid-segment housing in Nalagarh and the HP industrial corridor. Known for HIMUDA-compliant projects with mountain views at prices accessible to the industrial workforce.',
            ],
            [
                'name'                     => 'Solan Hills Properties',
                'company_name'             => 'Solan Hills Properties Pvt. Ltd.',
                'email'                    => 'info@solanhillsproperties.com',
                'phone'                    => '+91 98762 46009',
                'website'                  => 'https://www.solanhillsproperties.com',
                'city'                     => 'Solan',
                'established_year'         => '2001',
                'rera_registration'        => 'HPRERA-SOL-2001-0025',
                'cities_operating'         => 'Solan,Kasauli,Parwanoo,Chandigarh',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'Solan Hills Properties is HP\'s premier luxury hill property developer. Known for Solan Valley Villas — an award-winning project with panoramic Himalayan views. Catering to HNI buyers, retired professionals and corporate retreat developers.',
            ],
            [
                'name'                     => 'Morinda Green Developers',
                'company_name'             => 'Morinda Green Developers Pvt. Ltd.',
                'email'                    => 'info@morinda-green-developers.in',
                'phone'                    => '+91 98769 46010',
                'website'                  => 'https://www.morinda-green-developers.in',
                'city'                     => 'Morinda',
                'established_year'         => '2013',
                'rera_registration'        => 'PBRERA-SAS79-PR3200',
                'cities_operating'         => 'Morinda,Kurali,Fatehgarh Sahib',
                'rating'                   => 3.6,
                'is_verified'              => true,
                'total_delivered_projects' => 4,
                'description'              => 'Morinda Green Developers is an emerging builder in Morinda offering affordable plotted colonies and 2 BHK apartments for the town\'s growing workforce. Strong focus on green spaces and clean colony development.',
            ],
            [
                'name'                     => 'Rupnagar Heritage Promoters',
                'company_name'             => 'Rupnagar Heritage Promoters Pvt. Ltd.',
                'email'                    => 'info@rupnagarheritage.in',
                'phone'                    => '+91 99149 46011',
                'website'                  => 'https://www.rupnagarheritage.in',
                'city'                     => 'Ropar',
                'established_year'         => '2009',
                'rera_registration'        => 'PBRERA-SAS79-PR3300',
                'cities_operating'         => 'Ropar,Morinda,Anandpur Sahib',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Rupnagar Heritage Promoters is a respected builder in Ropar offering riverside residential plots and independent houses near the Sutlej. Known for quality construction and transparent NRI-friendly documentation.',
            ],
            [
                'name'                     => 'Chandigarh University Housing',
                'company_name'             => 'Chandigarh University Housing Pvt. Ltd.',
                'email'                    => 'info@cu-housing-gharuan.in',
                'phone'                    => '+91 98728 46012',
                'website'                  => 'https://www.cu-housing-gharuan.in',
                'city'                     => 'Gharuan',
                'established_year'         => '2015',
                'rera_registration'        => 'PBRERA-SAS79-PR3400',
                'cities_operating'         => 'Gharuan,Kharar,Mohali',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Chandigarh University Housing develops student-centric residential complexes near CU Gharuan campus. Their University Township offers hostel-style apartments, shared PGs and faculty housing with all amenities.',
            ],
            [
                'name'                     => 'Royal Enclave Patiala',
                'company_name'             => 'Royal Enclave Developers Patiala',
                'email'                    => 'info@royalenclave-patiala.com',
                'phone'                    => '+91 98724 46013',
                'website'                  => 'https://www.royalenclave-patiala.com',
                'city'                     => 'Patiala',
                'established_year'         => '1999',
                'rera_registration'        => 'PBRERA-PTL-1999-0015',
                'cities_operating'         => 'Patiala,Rajpura,Sangrur',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'description'              => 'Royal Enclave Developers is Patiala\'s most trusted builder with 25 years of residential excellence. Known for Royal Enclave Phase 1-3 — Patiala\'s most sought-after gated communities with premium amenities.',
            ],
            [
                'name'                     => 'Barotiwala Eco Homes',
                'company_name'             => 'Barotiwala Eco Homes Pvt. Ltd.',
                'email'                    => 'info@barotiwala-ecohomes.in',
                'phone'                    => '+91 98725 46014',
                'website'                  => 'https://www.barotiwala-ecohomes.in',
                'city'                     => 'Barotiwala',
                'established_year'         => '2010',
                'rera_registration'        => 'HPRERA-SOL-2010-0110',
                'cities_operating'         => 'Barotiwala,Baddi,Nalagarh',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'Barotiwala Eco Homes is an HP-based developer offering eco-friendly residential apartments for the Barotiwala–Baddi industrial belt. Solar-powered common areas, rainwater harvesting and green building certification.',
            ],
            [
                'name'                     => 'Fatehgarh Sahib Builders',
                'company_name'             => 'Fatehgarh Sahib Builders & Developers',
                'email'                    => 'info@fgs-builders.in',
                'phone'                    => '+91 98766 46015',
                'website'                  => 'https://www.fgs-builders.in',
                'city'                     => 'Fatehgarh Sahib',
                'established_year'         => '2014',
                'rera_registration'        => 'PBRERA-SAS79-PR3500',
                'cities_operating'         => 'Fatehgarh Sahib,Sirhind,Morinda',
                'rating'                   => 3.7,
                'is_verified'              => true,
                'total_delivered_projects' => 4,
                'description'              => 'Fatehgarh Sahib Builders is a growing developer in Fatehgarh Sahib district. Their projects near Sirhind historical zone cater to buyers seeking quiet, clean environments with excellent GT Road connectivity.',
            ],
        ];

        $projectMap = [
            'info@milestonerealtors-itpark.com' => [
                ['title'=>'Milestone IT Residences Sector 66 Mohali','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 66, Mohali, Punjab','city'=>'Mohali','state'=>'Punjab','total_units'=>320,'available_units'=>18,'price_from'=>2800000,'price_to'=>7500000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS79-PR2900','total_towers'=>8,'floors_per_tower'=>'14','latitude'=>30.7152,'longitude'=>76.7538,'amenities'=>'IT Park View,Smart Home,Co-Working Lounge,Gymnasium,EV Charging,24x7 Security,Power Backup','nearby_schools'=>'DPS (2 km)','nearby_hospitals'=>'Fortis (4 km)','metro_distance'=>'IT Park 0.5 km','is_featured'=>true,'description'=>'Milestone IT Residences in Sector 66 Mohali — smart apartments for IT professionals with co-working lounge, EV charging and seamless connectivity to Mohali IT Park.'],
                ['title'=>'Milestone Compact Studio Sector 68 Mohali','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 68, Mohali, Punjab','city'=>'Mohali','state'=>'Punjab','total_units'=>180,'available_units'=>10,'price_from'=>1800000,'price_to'=>3500000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS79-PR2901','total_towers'=>4,'floors_per_tower'=>'12','latitude'=>30.7165,'longitude'=>76.7530,'amenities'=>'Smart Home,High-Speed Internet,Security,Power Backup,Gym,EV Charging','nearby_schools'=>'Nearby school (1 km)','nearby_hospitals'=>'Fortis (4 km)','metro_distance'=>'IT Park 1 km','is_featured'=>false,'description'=>'Milestone Compact Studios in Sector 68 — ultra-compact smart studios ideal for IT professionals and investors seeking rental income near Mohali IT Park.'],
            ],
            'info@shivalik-buildcon-kalka.in' => [
                ['title'=>'Shivalik Hill View Apartments Kalka','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Kalka, Haryana','city'=>'Kalka','state'=>'Haryana','total_units'=>240,'available_units'=>14,'price_from'=>3200000,'price_to'=>7500000,'possession_date'=>'2021-09-30','rera_id'=>'HRERA-PKL-2004-0180','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.8466,'longitude'=>76.9480,'amenities'=>'Shivalik View,Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track','nearby_schools'=>'DAV Kalka (0.5 km)','nearby_hospitals'=>'Govt. Hospital Kalka (1 km)','metro_distance'=>'26 km from Chandigarh','is_featured'=>true,'description'=>'Shivalik Hill View Apartments in Kalka offer spectacular views of the Shivalik range. Premium 2 & 3 BHK apartments at the gateway to the hills, ideal for Chandigarh professionals.'],
                ['title'=>'Shivalik Mountain Estates Pinjore','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Pinjore, Haryana','city'=>'Pinjore','state'=>'Haryana','total_units'=>320,'available_units'=>22,'price_from'=>2500000,'price_to'=>12000000,'possession_date'=>'2019-06-30','rera_id'=>'HRERA-PKL-2004-0181','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.8012,'longitude'=>76.9196,'amenities'=>'Gated Society,24x7 Security,Park,Jogging Track,Yadavindra Gardens Proximity','nearby_schools'=>'Govt. School (1 km)','nearby_hospitals'=>'Civil Hospital Pinjore (2 km)','metro_distance'=>'20 km from Chandigarh','is_featured'=>true,'description'=>'Shivalik Mountain Estates in Pinjore — premium self-build plots near Yadavindra Gardens with Shivalik hill backdrop. Ideal for weekend homes and holiday villas.'],
            ],
            'info@pinjoregardenestate.in' => [
                ['title'=>'Pinjore Garden Estate Phase 1','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'Pinjore Garden Estate, Pinjore, Haryana','city'=>'Pinjore','state'=>'Haryana','total_units'=>480,'available_units'=>28,'price_from'=>2800000,'price_to'=>9000000,'possession_date'=>'2021-03-31','rera_id'=>'HRERA-PKL-2007-0220','total_towers'=>12,'floors_per_tower'=>'8','latitude'=>30.8018,'longitude'=>76.9200,'amenities'=>'Garden Theme,Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Path','nearby_schools'=>'St. Mary (1 km)','nearby_hospitals'=>'Govt. Hospital (2 km)','metro_distance'=>'20 km from Chandigarh','is_featured'=>true,'description'=>'Pinjore Garden Estate Phase 1 is a lush residential township near the famous Yadavindra Gardens. Premium 2, 3 & 4 BHK apartments with garden views in Haryana\'s most scenic township location.'],
                ['title'=>'Heritage Heights Pinjore','project_type'=>'Residential','status'=>'Under Construction','address'=>'Pinjore Hill Road, Pinjore, Haryana','city'=>'Pinjore','state'=>'Haryana','total_units'=>160,'available_units'=>90,'price_from'=>3500000,'price_to'=>8000000,'possession_date'=>'2026-06-30','rera_id'=>'HRERA-PKL-2007-0221','total_towers'=>6,'floors_per_tower'=>'10','latitude'=>30.8020,'longitude'=>76.9188,'amenities'=>'Heritage Theme,Landscaped Garden,Gymnasium,24x7 Security,EV Charging,Yoga Deck','nearby_schools'=>'Heritage School (1 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'20 km from Chandigarh','is_featured'=>true,'description'=>'Heritage Heights Pinjore — a boutique heritage-themed residential project blending Mughal garden aesthetics with modern amenities near the iconic Yadavindra Gardens.'],
            ],
            'info@punjabgatewaydevelopers.com' => [
                ['title'=>'GT Road Township Rajpura','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'GT Road, Rajpura, Punjab','city'=>'Rajpura','state'=>'Punjab','total_units'=>640,'available_units'=>40,'price_from'=>1800000,'price_to'=>5000000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR3000','total_towers'=>16,'floors_per_tower'=>'8','latitude'=>30.4786,'longitude'=>76.5953,'amenities'=>'GT Road Frontage,Security,Power Backup,Kids Play Area,Community Hall,CCTV','nearby_schools'=>'DAV Rajpura (0.5 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'28 km from Chandigarh','is_featured'=>false,'description'=>'GT Road Township Rajpura is an integrated development with affordable 1, 2 & 3 BHK apartments and commercial spaces along the GT Road corridor in Rajpura industrial town.'],
                ['title'=>'Green Fields Rajpura','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Rajpura Extension, Punjab','city'=>'Rajpura','state'=>'Punjab','total_units'=>280,'available_units'=>18,'price_from'=>1500000,'price_to'=>3800000,'possession_date'=>'2019-09-30','rera_id'=>'PBRERA-SAS79-PR3001','total_towers'=>8,'floors_per_tower'=>'6','latitude'=>30.4792,'longitude'=>76.5965,'amenities'=>'Security,Power Backup,CCTV,Kids Play Area,Parking','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'CHC Rajpura (1 km)','metro_distance'=>'28 km from Chandigarh','is_featured'=>false,'description'=>'Green Fields Rajpura offers affordable 2 BHK apartments for first-time buyers and industrial workers in Rajpura\'s expanding residential zones.'],
            ],
            'info@ambalaheightsbuilders.in' => [
                ['title'=>'Ambala Heights Tower 1','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Ambala City, Haryana','city'=>'Ambala','state'=>'Haryana','total_units'=>360,'available_units'=>20,'price_from'=>2800000,'price_to'=>7000000,'possession_date'=>'2021-12-31','rera_id'=>'HARERA-AMB-2006-0040','total_towers'=>10,'floors_per_tower'=>'11','latitude'=>30.3782,'longitude'=>76.7767,'amenities'=>'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,CCTV','nearby_schools'=>'DAV Ambala (1 km)','nearby_hospitals'=>'ESIC Hospital (2 km)','metro_distance'=>'29 km from Chandigarh','is_featured'=>true,'description'=>'Ambala Heights Tower 1 is the flagship residential project by Ambala Heights Builders — premium 2 & 3 BHK apartments with swimming pool and clubhouse facilities near Ambala Cantonment.'],
                ['title'=>'Ambala City Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 1, Ambala City, Haryana','city'=>'Ambala','state'=>'Haryana','total_units'=>220,'available_units'=>12,'price_from'=>2200000,'price_to'=>5500000,'possession_date'=>'2020-06-30','rera_id'=>'HARERA-AMB-2006-0041','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.3764,'longitude'=>76.7720,'amenities'=>'Gymnasium,24x7 Security,Power Backup,CCTV,Lift,Kids Play Area','nearby_schools'=>'St. Thomas School (1 km)','nearby_hospitals'=>'Civil Hospital (2.5 km)','metro_distance'=>'29 km from Chandigarh','is_featured'=>false,'description'=>'Ambala City Residency offers quality 2 & 3 BHK apartments in a gated complex near the commercial heart of Ambala City.'],
            ],
            'info@kurali-homes.in' => [
                ['title'=>'Kurali Green Enclave','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'NH-7, Kurali, Punjab','city'=>'Kurali','state'=>'Punjab','total_units'=>280,'available_units'=>18,'price_from'=>1200000,'price_to'=>4500000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS79-PR3100','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.8340,'longitude'=>76.5768,'amenities'=>'Gated Colony,Security,Park,Water Supply,Electricity,Roads','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'CHC Kurali (1 km)','metro_distance'=>'32 km from Chandigarh','is_featured'=>false,'description'=>'Kurali Green Enclave is a premium plotted colony on NH-7 offering affordable residential plots near Kurali town for buyers seeking value investment near Chandigarh periphery.'],
                ['title'=>'Kurali Township Plots Phase 2','project_type'=>'Plotted','status'=>'Under Construction','address'=>'Kurali Bypass, Kurali, Punjab','city'=>'Kurali','state'=>'Punjab','total_units'=>200,'available_units'=>130,'price_from'=>1500000,'price_to'=>6000000,'possession_date'=>'2026-09-30','rera_id'=>'PBRERA-SAS79-PR3102','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.8338,'longitude'=>76.5775,'amenities'=>'Gated Colony,Security,Park,Internal Roads,Water Supply,Street Lights','nearby_schools'=>'Upcoming school (on-site)','nearby_hospitals'=>'CHC Kurali (2 km)','metro_distance'=>'32 km from Chandigarh','is_featured'=>false,'description'=>'Kurali Township Plots Phase 2 — expanding the popular Kurali Green Enclave with additional residential plot options in this NH-7 growth corridor.'],
            ],
            'info@baddi-housing.in' => [
                ['title'=>'Baddi Workers Premium Enclave','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Baddi Industrial Area, Baddi, HP','city'=>'Baddi','state'=>'Himachal Pradesh','total_units'=>480,'available_units'=>28,'price_from'=>1500000,'price_to'=>4000000,'possession_date'=>'2020-09-30','rera_id'=>'HPRERA-SOL-2003-0050','total_towers'=>12,'floors_per_tower'=>'8','latitude'=>30.9545,'longitude'=>76.7899,'amenities'=>'Security,Power Backup,Canteen,Gymnasium,CCTV,Parking','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'ESIC Hospital Baddi (1 km)','metro_distance'=>'35 km from Chandigarh','is_featured'=>false,'description'=>'Baddi Workers Premium Enclave provides quality, affordable residential apartments for the industrial workforce of Baddi — HP\'s largest manufacturing hub — with clean, safe living conditions.'],
                ['title'=>'Baddi Eco Apartments','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Baddi, Himachal Pradesh','city'=>'Baddi','state'=>'Himachal Pradesh','total_units'=>240,'available_units'=>15,'price_from'=>2200000,'price_to'=>5000000,'possession_date'=>'2021-06-30','rera_id'=>'HPRERA-SOL-2003-0051','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.9538,'longitude'=>76.7905,'amenities'=>'Solar Power,Rainwater Harvesting,Garden,Security,Gymnasium,EV Charging','nearby_schools'=>'DAV Baddi (1 km)','nearby_hospitals'=>'Govt. Hospital Baddi (1.5 km)','metro_distance'=>'35 km from Chandigarh','is_featured'=>true,'description'=>'Baddi Eco Apartments is an eco-certified residential project in Baddi with solar panels and rainwater harvesting — quality mid-segment housing for supervisory and managerial staff.'],
            ],
            'info@himalayan-heights-nalagarh.in' => [
                ['title'=>'Himalayan Valley Nalagarh','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Nalagarh, Himachal Pradesh','city'=>'Nalagarh','state'=>'Himachal Pradesh','total_units'=>320,'available_units'=>20,'price_from'=>1800000,'price_to'=>4500000,'possession_date'=>'2021-03-31','rera_id'=>'HPRERA-SOL-2008-0090','total_towers'=>10,'floors_per_tower'=>'8','latitude'=>31.0402,'longitude'=>76.7161,'amenities'=>'Mountain View,Security,Power Backup,Lift,Kids Play Area,CCTV,Garden','nearby_schools'=>'Govt. School Nalagarh (0.5 km)','nearby_hospitals'=>'Civil Hospital Nalagarh (1 km)','metro_distance'=>'45 km from Chandigarh','is_featured'=>false,'description'=>'Himalayan Valley Nalagarh offers quality 2 & 3 BHK apartments with Himalayan foothills views in Nalagarh — an emerging industrial and residential town in HP.'],
                ['title'=>'Nalagarh Heights Phase 2','project_type'=>'Residential','status'=>'Under Construction','address'=>'Nalagarh Industrial Estate, HP','city'=>'Nalagarh','state'=>'Himachal Pradesh','total_units'=>200,'available_units'=>130,'price_from'=>2200000,'price_to'=>5500000,'possession_date'=>'2026-12-31','rera_id'=>'HPRERA-SOL-2008-0091','total_towers'=>8,'floors_per_tower'=>'10','latitude'=>31.0410,'longitude'=>76.7168,'amenities'=>'Mountain View,Swimming Pool,Gymnasium,24x7 Security,EV Charging,Power Backup','nearby_schools'=>'Govt. School (1 km)','nearby_hospitals'=>'Civil Hospital (1 km)','metro_distance'=>'45 km from Chandigarh','is_featured'=>true,'description'=>'Nalagarh Heights Phase 2 expands the successful Himalayan Valley project with modern amenities and upgraded finishes in the growing Nalagarh industrial township.'],
            ],
            'info@solanhillsproperties.com' => [
                ['title'=>'Solan Valley Villas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Solan, Himachal Pradesh','city'=>'Solan','state'=>'Himachal Pradesh','total_units'=>80,'available_units'=>8,'price_from'=>8000000,'price_to'=>25000000,'possession_date'=>'2021-12-31','rera_id'=>'HPRERA-SOL-2001-0025','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.9045,'longitude'=>77.0985,'amenities'=>'Private Garden,Swimming Pool,Gymnasium,24x7 Security,Himalayan View,EV Charging,Home Theatre','nearby_schools'=>'DAV Solan (2 km)','nearby_hospitals'=>'Zonal Hospital Solan (3 km)','metro_distance'=>'40 km from Chandigarh','is_featured'=>true,'description'=>'Solan Valley Villas are ultra-premium hill villas with breathtaking Himalayan views. 3, 4 & 5 BHK private villas with landscaped gardens — Chandigarh\'s most prestigious weekend and retirement destination.'],
                ['title'=>'Solan Heights Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Solan Hill Road, Solan, HP','city'=>'Solan','state'=>'Himachal Pradesh','total_units'=>160,'available_units'=>10,'price_from'=>4500000,'price_to'=>12000000,'possession_date'=>'2022-03-31','rera_id'=>'HPRERA-SOL-2001-0026','total_towers'=>6,'floors_per_tower'=>'8','latitude'=>30.9050,'longitude'=>76.9982,'amenities'=>'Hill View,Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area','nearby_schools'=>'DAV (2 km)','nearby_hospitals'=>'Govt. Hospital (2 km)','metro_distance'=>'40 km from Chandigarh','is_featured'=>true,'description'=>'Solan Heights Residency offers premium hill apartments with Shivalik and Himalayan views — a quality mid-to-luxury development popular with Chandigarh IT professionals seeking a second home.'],
            ],
            'info@morinda-green-developers.in' => [
                ['title'=>'Morinda Green Colony','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Morinda, Punjab','city'=>'Morinda','state'=>'Punjab','total_units'=>240,'available_units'=>18,'price_from'=>900000,'price_to'=>3500000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR3200','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7990,'longitude'=>76.4980,'amenities'=>'Gated Colony,Security,Park,Water Supply,Electricity','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'Civil Hospital (1 km)','metro_distance'=>'36 km from Chandigarh','is_featured'=>false,'description'=>'Morinda Green Colony offers affordable residential plots in a clean, planned gated colony near Morinda town. Ideal for self-construction and budget plot investment in rural Punjab.'],
                ['title'=>'Morinda Township Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Morinda, Punjab','city'=>'Morinda','state'=>'Punjab','total_units'=>120,'available_units'=>8,'price_from'=>1200000,'price_to'=>2800000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR3201','total_towers'=>4,'floors_per_tower'=>'6','latitude'=>30.7985,'longitude'=>76.4975,'amenities'=>'Security,Power Backup,CCTV,Lift,Basic Amenities','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'CHC (1 km)','metro_distance'=>'36 km from Chandigarh','is_featured'=>false,'description'=>'Morinda Township Residency provides affordable 2 BHK apartments for Morinda\'s growing population with clean facilities and good connectivity.'],
            ],
            'info@rupnagarheritage.in' => [
                ['title'=>'Rupnagar River View Colony','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Ropar, Punjab','city'=>'Ropar','state'=>'Punjab','total_units'=>320,'available_units'=>22,'price_from'=>1500000,'price_to'=>5000000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR3300','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.9643,'longitude'=>76.5186,'amenities'=>'River View,Gated Colony,Security,Park,Water Supply,Internal Roads','nearby_schools'=>'Govt. School (1 km)','nearby_hospitals'=>'Civil Hospital Ropar (1.5 km)','metro_distance'=>'46 km from Chandigarh','is_featured'=>true,'description'=>'Rupnagar River View Colony offers scenic Sutlej riverside residential plots in Ropar — one of Punjab\'s most beautiful towns. Ideal for self-construction near IIT Ropar and Bhakra canal belt.'],
                ['title'=>'Ropar Heritage Homes','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Ropar, Punjab','city'=>'Ropar','state'=>'Punjab','total_units'=>160,'available_units'=>10,'price_from'=>2000000,'price_to'=>5000000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS79-PR3301','total_towers'=>6,'floors_per_tower'=>'7','latitude'=>30.9650,'longitude'=>76.5190,'amenities'=>'Security,Power Backup,Gymnasium,Kids Play Area,CCTV,Lift','nearby_schools'=>'IIT Ropar nearby','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'46 km from Chandigarh','is_featured'=>false,'description'=>'Ropar Heritage Homes offers comfortable 2 & 3 BHK apartments in Ropar near IIT Ropar campus. Popular with IIT faculty and Ropar\'s educated professional community.'],
            ],
            'info@cu-housing-gharuan.in' => [
                ['title'=>'University Township Phase 1 Gharuan','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Gharuan, Mohali, Punjab','city'=>'Gharuan','state'=>'Punjab','total_units'=>560,'available_units'=>30,'price_from'=>1800000,'price_to'=>4500000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR3400','total_towers'=>14,'floors_per_tower'=>'10','latitude'=>30.7762,'longitude'=>76.5715,'amenities'=>'High-Speed WiFi,Study Lounge,Gymnasium,24x7 Security,Power Backup,Canteen,CCTV','nearby_schools'=>'Chandigarh University (0.5 km)','nearby_hospitals'=>'CU Medical Centre (0.5 km)','metro_distance'=>'28 km from Chandigarh','is_featured'=>true,'description'=>'University Township Phase 1 near Chandigarh University Gharuan campus — purpose-built residential complex with student amenities, high-speed WiFi and cafeteria for CU students and faculty.'],
            ],
            'info@royalenclave-patiala.com' => [
                ['title'=>'Royal Enclave Phase 3 Patiala','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Urban Estate, Patiala, Punjab','city'=>'Patiala','state'=>'Punjab','total_units'=>480,'available_units'=>22,'price_from'=>3500000,'price_to'=>9500000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-PTL-1999-0015','total_towers'=>12,'floors_per_tower'=>'12','latitude'=>30.3398,'longitude'=>76.3869,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Tennis Court,Kids Play Area,Jogging Track','nearby_schools'=>'Govt. School (1 km)','nearby_hospitals'=>'Rajindra Hospital (3 km)','metro_distance'=>'53 km from Chandigarh','is_featured'=>true,'description'=>'Royal Enclave Phase 3 in Patiala is the latest chapter in the flagship township. Premium 2, 3 & 4 BHK apartments in a gated community with resort-style amenities in the royal city of Punjab.'],
                ['title'=>'Patiala Urban Heights','project_type'=>'Residential','status'=>'Under Construction','address'=>'Rajpura Road, Patiala, Punjab','city'=>'Patiala','state'=>'Punjab','total_units'=>360,'available_units'=>210,'price_from'=>2800000,'price_to'=>7000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-PTL-1999-0016','total_towers'=>10,'floors_per_tower'=>'11','latitude'=>30.3405,'longitude'=>76.3878,'amenities'=>'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,EV Charging,Jogging Track','nearby_schools'=>'GNDU (2 km)','nearby_hospitals'=>'AIIMS Patiala (3 km)','metro_distance'=>'53 km from Chandigarh','is_featured'=>true,'description'=>'Patiala Urban Heights is a modern residential project near AIIMS Patiala — 2 & 3 BHK apartments for the city\'s growing medical professional community and Rajpura corridor buyers.'],
            ],
            'info@barotiwala-ecohomes.in' => [
                ['title'=>'Barotiwala Eco Homes Phase 1','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Barotiwala, Himachal Pradesh','city'=>'Barotiwala','state'=>'Himachal Pradesh','total_units'=>280,'available_units'=>16,'price_from'=>1800000,'price_to'=>4200000,'possession_date'=>'2021-12-31','rera_id'=>'HPRERA-SOL-2010-0110','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.9498,'longitude'=>76.8356,'amenities'=>'Solar Power,Rainwater Harvesting,Gymnasium,Security,Green Garden,EV Charging','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'ESIC Hospital (2 km)','metro_distance'=>'34 km from Chandigarh','is_featured'=>false,'description'=>'Barotiwala Eco Homes Phase 1 — green-certified residential complex with solar energy and rainwater harvesting, offering affordable 2 BHK apartments for the Barotiwala industrial workforce.'],
            ],
            'info@fgs-builders.in' => [
                ['title'=>'Fatehgarh Heritage Homes','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Fatehgarh Sahib, Punjab','city'=>'Fatehgarh Sahib','state'=>'Punjab','total_units'=>180,'available_units'=>12,'price_from'=>1600000,'price_to'=>3800000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR3500','total_towers'=>6,'floors_per_tower'=>'7','latitude'=>30.6483,'longitude'=>76.3946,'amenities'=>'Security,Power Backup,CCTV,Kids Play Area,Car Parking','nearby_schools'=>'Govt. School (0.5 km)','nearby_hospitals'=>'Civil Hospital FGS (1 km)','metro_distance'=>'41 km from Chandigarh','is_featured'=>false,'description'=>'Fatehgarh Heritage Homes offers affordable 2 BHK apartments in the historically significant Fatehgarh Sahib district. Clean, well-designed homes in a gated compound near the town centre.'],
                ['title'=>'FGS Green Enclave Sirhind Road','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Sirhind Road, Fatehgarh Sahib, Punjab','city'=>'Fatehgarh Sahib','state'=>'Punjab','total_units'=>240,'available_units'=>18,'price_from'=>1200000,'price_to'=>3500000,'possession_date'=>'2019-09-30','rera_id'=>'PBRERA-SAS79-PR3501','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.6490,'longitude'=>76.3938,'amenities'=>'Gated Colony,Security,Park,Water Supply,Electricity,Roads','nearby_schools'=>'Nearby school (1 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'41 km from Chandigarh','is_featured'=>false,'description'=>'FGS Green Enclave on Sirhind Road offers residential plots in a clean, gated colony near the historically significant Sirhind town — good connectivity to GT Road and Punjab highways.'],
            ],
        ];

        foreach ($builders as $b) {
            $baseSlug = Str::slug($b['name']);
            $slug = $baseSlug;
            $i = 1;
            while (DB::table('builders')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }

            if (DB::table('builders')->where('email', $b['email'])->exists()) {
                $builderId = DB::table('builders')->where('email', $b['email'])->value('id');
                $this->command->line("    [skip] {$b['company_name']}");
            } else {
                $builderId = DB::table('builders')->insertGetId([
                    'name'                     => $b['name'],
                    'company_name'             => $b['company_name'],
                    'email'                    => $b['email'],
                    'password'                 => Hash::make('Builder@2024'),
                    'phone'                    => $b['phone'],
                    'website'                  => $b['website'],
                    'city'                     => $b['city'],
                    'established_year'         => $b['established_year'],
                    'rera_registration'        => $b['rera_registration'],
                    'cities_operating'         => $b['cities_operating'],
                    'rating'                   => $b['rating'],
                    'is_verified'              => $b['is_verified'],
                    'total_delivered_projects' => $b['total_delivered_projects'],
                    'description'              => $b['description'],
                    'slug'                     => $slug,
                    'status'                   => 'active',
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
                $this->command->line("    ✓ Builder: {$b['company_name']}");
            }

            $projects = $projectMap[$b['email']] ?? [];
            foreach ($projects as $project) {
                if (DB::table('builder_projects')->where('builder_id', $builderId)->where('title', $project['title'])->exists()) {
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
                    'views_count'      => rand(80, 1800),
                    'leads_count'      => rand(5, 80),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
                $this->command->line("      ✓ Project: {$project['title']}");
            }
        }
        $this->command->info('    ✔ 15 builders + 28 projects seeded.');
    }

    // =========================================================================
    // PROPERTIES — 250 records covering gap fills + Ring 10 + Ring 11 + Patiala
    // =========================================================================
    private function seedProperties(): void
    {
        $this->command->info('  → Seeding 250 properties completing 50 km coverage...');

        $dealerIds = DB::table('property_dealers')->pluck('id')->toArray();
        if (empty($dealerIds)) { $this->command->error('No dealers!'); return; }

        $amenityPool = [
            'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Clubhouse',
            'Park,Kids Play Area,Security,Power Backup,Car Parking,CCTV',
            'Gymnasium,24x7 Security,Power Backup,Lift,Intercom,CCTV',
            'Clubhouse,Swimming Pool,Kids Play Area,Jogging Track,Power Backup,Security',
            'Car Parking,CCTV,Security,Power Backup,Intercom,Visitor Parking',
            'Mountain View,Gymnasium,Swimming Pool,Yoga Deck,24x7 Security,EV Charging',
            'Community Hall,Kids Play Area,Power Backup,Security,Water Supply,Lift',
            'Sports Facility,Indoor Games,Gymnasium,Swimming Pool,24x7 Security,CCTV',
            'Solar Power,Rainwater Harvesting,Garden,Security,Gymnasium,EV Charging',
            'Hill View,Private Garden,24x7 Security,Power Backup,Jogging Path,Kids Play Area',
        ];
        $furnishings    = ['Furnished','Semi-Furnished','Unfurnished','Semi-Furnished','Unfurnished'];
        $facings        = ['North','South','East','West','North-East','North-West','South-East'];
        $propAges       = ['Under Construction','0-1 Year','1-3 Years','3-5 Years','5-10 Years'];
        $pick           = fn($arr) => $arr[array_rand($arr)];

        $zones = [

            // ── GAP FILLS (missed zones from earlier batches) ────────────────

            ['label'=>'Manimajra / Daria Chandigarh (9.9 km NNE)','dist'=>9.9,
             'lat'=>30.7218,'lng'=>76.8605,'jitter'=>0.0025,
             'state'=>'Chandigarh','city'=>'Chandigarh','pincode'=>'160101',
             'locality'=>'Manimajra','count'=>15,
             'societies'=>['CHB Manimajra Flats','PUDA Colony Daria','Manimajra Sector Apartments','Daria Green Heights','Sun City Manimajra'],
             'landmark'=>'Near Manimajra Chandigarh'],

            ['label'=>'Mohali IT Park Sector 66-68 (10.5 km NW)','dist'=>10.5,
             'lat'=>30.7152,'lng'=>76.7538,'jitter'=>0.0025,
             'state'=>'Punjab','city'=>'Mohali','pincode'=>'160066',
             'locality'=>'Sector 66','count'=>15,
             'societies'=>['Milestone IT Residences Sec 66','GMADA IT City Apartments','Sector 66 Premium Flats','Sector 68 Smart Homes','IT Park View Residency'],
             'landmark'=>'Near Mohali IT Park Sector 66-68'],

            ['label'=>'Pinjore (20.4 km NNE)','dist'=>20.4,
             'lat'=>30.8012,'lng'=>76.9196,'jitter'=>0.0030,
             'state'=>'Haryana','city'=>'Pinjore','pincode'=>'134102',
             'locality'=>'Pinjore','count'=>15,
             'societies'=>['Pinjore Garden Estate','Heritage Heights Pinjore','Shivalik Mountain Estates','HUDA Pinjore Plots','Yadavindra Adjacent Society'],
             'landmark'=>'Near Yadavindra Gardens Pinjore'],

            // ── RING 10 : 25–35 km ──────────────────────────────────────────

            ['label'=>'Kalka (26 km NNE)','dist'=>26.1,
             'lat'=>30.8466,'lng'=>76.9480,'jitter'=>0.0030,
             'state'=>'Haryana','city'=>'Kalka','pincode'=>'133302',
             'locality'=>'Kalka','count'=>20,
             'societies'=>['Shivalik Hill View Kalka','HUDA Kalka Plots','Kalka Independent Houses','Hill View Residency Kalka','Kalka Green Enclave'],
             'landmark'=>'Near Kalka Town Centre'],

            ['label'=>'Chandigarh University Gharuan (28 km WNW)','dist'=>28.3,
             'lat'=>30.7762,'lng'=>76.5715,'jitter'=>0.0030,
             'state'=>'Punjab','city'=>'Gharuan','pincode'=>'140413',
             'locality'=>'Gharuan','count'=>15,
             'societies'=>['University Township Gharuan','CU Faculty Housing','Gharuan Residential Colony','Student Homes Gharuan','CU Adjacent Flats'],
             'landmark'=>'Near Chandigarh University Gharuan'],

            ['label'=>'Rajpura (28 km SW)','dist'=>28.1,
             'lat'=>30.4786,'lng'=>76.5953,'jitter'=>0.0035,
             'state'=>'Punjab','city'=>'Rajpura','pincode'=>'140401',
             'locality'=>'Rajpura','count'=>20,
             'societies'=>['GT Road Township Rajpura','Green Fields Rajpura','Rajpura Industrial Colony','Krishna Nagar Rajpura','Ambika Residency Rajpura'],
             'landmark'=>'Near Rajpura Bus Stand'],

            ['label'=>'Ambala Cantonment / Ambala City (29-30 km S)','dist'=>29.3,
             'lat'=>30.3782,'lng'=>76.7767,'jitter'=>0.0035,
             'state'=>'Haryana','city'=>'Ambala','pincode'=>'133001',
             'locality'=>'Ambala Cantt','count'=>20,
             'societies'=>['Ambala Heights Tower','HUDA Sector Ambala','Ambala City Residency','Defence Colony Ambala','Ambala Cantt Independent Houses'],
             'landmark'=>'Near Ambala Cantonment'],

            ['label'=>'Kurali (32 km NW)','dist'=>31.8,
             'lat'=>30.8340,'lng'=>76.5768,'jitter'=>0.0030,
             'state'=>'Punjab','city'=>'Kurali','pincode'=>'140103',
             'locality'=>'Kurali','count'=>15,
             'societies'=>['Kurali Green Enclave','Kurali Township Plots','NH-7 Residency Kurali','Kurali Garden Colony','New Kurali Heights'],
             'landmark'=>'Near Kurali NH-7 Bypass'],

            ['label'=>'Barotiwala / Baddi near (34 km N)','dist'=>34.4,
             'lat'=>30.9498,'lng'=>76.8356,'jitter'=>0.0030,
             'state'=>'Himachal Pradesh','city'=>'Barotiwala','pincode'=>'174103',
             'locality'=>'Barotiwala','count'=>15,
             'societies'=>['Barotiwala Eco Homes','Industrial Belt Residency','Barotiwala Green Apartments','HP Industrial Housing','Barotiwala Township'],
             'landmark'=>'Near Barotiwala Industrial Estate'],

            // ── RING 11 : 35–50 km ──────────────────────────────────────────

            ['label'=>'Baddi HP (35 km N)','dist'=>35.0,
             'lat'=>30.9545,'lng'=>76.7899,'jitter'=>0.0030,
             'state'=>'Himachal Pradesh','city'=>'Baddi','pincode'=>'173205',
             'locality'=>'Baddi','count'=>15,
             'societies'=>['Baddi Workers Enclave','Baddi Eco Apartments','HIMUDA Baddi Housing','Baddi Premium Residency','Industrial Homes Baddi'],
             'landmark'=>'Near Baddi Industrial Area HP'],

            ['label'=>'Morinda (36 km WNW)','dist'=>35.7,
             'lat'=>30.7990,'lng'=>76.4980,'jitter'=>0.0030,
             'state'=>'Punjab','city'=>'Morinda','pincode'=>'140101',
             'locality'=>'Morinda','count'=>15,
             'societies'=>['Morinda Green Colony','Morinda Township Residency','Morinda Garden Enclave','NH-7 Morinda Heights','New Morinda Plots'],
             'landmark'=>'Near Morinda Town Centre'],

            ['label'=>'Solan HP (40 km NE)','dist'=>39.9,
             'lat'=>30.9045,'lng'=>77.0985,'jitter'=>0.0035,
             'state'=>'Himachal Pradesh','city'=>'Solan','pincode'=>'173212',
             'locality'=>'Solan','count'=>15,
             'societies'=>['Solan Valley Villas','Solan Heights Residency','HIMUDA Solan Plots','Solan Hill Apartments','Kasauli Road Residency'],
             'landmark'=>'Near Solan Town HP'],

            ['label'=>'Fatehgarh Sahib (41 km W)','dist'=>41.0,
             'lat'=>30.6483,'lng'=>76.3946,'jitter'=>0.0035,
             'state'=>'Punjab','city'=>'Fatehgarh Sahib','pincode'=>'140407',
             'locality'=>'Fatehgarh Sahib','count'=>15,
             'societies'=>['Fatehgarh Heritage Homes','FGS Green Enclave','Sirhind Road Plots','Fatehgarh Sahib Colony','New Anandpur FGS'],
             'landmark'=>'Near Fatehgarh Sahib Town'],

            ['label'=>'Nalagarh HP (45 km NNW)','dist'=>45.5,
             'lat'=>31.0402,'lng'=>76.7161,'jitter'=>0.0035,
             'state'=>'Himachal Pradesh','city'=>'Nalagarh','pincode'=>'174101',
             'locality'=>'Nalagarh','count'=>15,
             'societies'=>['Himalayan Valley Nalagarh','Nalagarh Heights','HIMUDA Nalagarh Plots','Industrial Housing Nalagarh','Nalagarh Green Colony'],
             'landmark'=>'Near Nalagarh Industrial Estate HP'],

            ['label'=>'Ropar / Rupnagar (46 km NW)','dist'=>46.2,
             'lat'=>30.9643,'lng'=>76.5186,'jitter'=>0.0035,
             'state'=>'Punjab','city'=>'Ropar','pincode'=>'140001',
             'locality'=>'Ropar','count'=>15,
             'societies'=>['Rupnagar River View Colony','Ropar Heritage Homes','IIT Ropar Adjacent Housing','Sutlej View Apartments','Rupnagar HUDA Plots'],
             'landmark'=>'Near Ropar / IIT Rupnagar'],

            // ── BONUS: Patiala (53 km SW) ────────────────────────────────────

            ['label'=>'Patiala (53 km SW — bonus beyond 50 km)','dist'=>53.4,
             'lat'=>30.3398,'lng'=>76.3869,'jitter'=>0.0040,
             'state'=>'Punjab','city'=>'Patiala','pincode'=>'147001',
             'locality'=>'Patiala','count'=>20,
             'societies'=>['Royal Enclave Phase 3 Patiala','Patiala Urban Heights','Model Town Patiala','Urban Estate Phase 2','AIIMS Patiala Adjacent Society'],
             'landmark'=>'Near Patiala Urban Estate'],
        ];

        $propTypes      = ['Apartment','Builder Floor','Independent Floor','Villa','Plot','Penthouse','Studio Apartment','Shop','Office Space'];
        $lookingForPool = ['Sale','Sale','Sale','Rent','Sale'];
        $totalInserted  = 0;

        foreach ($zones as $zone) {
            $this->command->info("    Zone: {$zone['label']} — {$zone['count']} properties");
            for ($i = 0; $i < $zone['count']; $i++) {
                $dealerId  = $pick($dealerIds);
                $ptype     = $pick($propTypes);
                $lfor      = $pick($lookingForPool);
                $society   = $pick($zone['societies']);
                $amenities = $pick($amenityPool);
                $furnish   = $pick($furnishings);
                $facing    = $pick($facings);
                $propAge   = $pick($propAges);

                [$bedrooms, $bathrooms, $balconies, $area, $price, $bhkType] = $this->getConfig($ptype, $zone['city'], $lfor);

                $title    = $this->makeTitle($ptype, $bedrooms, $bhkType, $society, $zone['locality'], $zone['city'], $totalInserted + $i + 1);
                $baseSlug = Str::slug($title . '-' . $zone['city'] . '-' . ($totalInserted + $i + 1));
                $slug     = $baseSlug;
                $sc       = 1;
                while (DB::table('properties')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $sc++;
                }

                $lat  = round($zone['lat'] + (rand(-100, 100) / 100000 * ($zone['jitter'] / 0.001)), 6);
                $lng  = round($zone['lng'] + (rand(-100, 100) / 100000 * ($zone['jitter'] / 0.001)), 6);

                $totalFloors = in_array($ptype, ['Villa','Plot']) ? null : rand(3, 18);
                $floorNumber = $totalFloors ? rand(1, $totalFloors) : null;
                $ppsqft      = $area > 0 ? round($price / $area, 2) : null;
                $possession  = ($propAge === 'Under Construction') ? 'Under Construction' : 'Ready to Move';
                $rent        = ($lfor === 'Rent') ? $this->getRent($ptype, $bedrooms, $zone['city']) : null;
                $status      = ($lfor === 'Rent') ? 'Available' : $pick(['Available','Available','Available','Sold']);

                DB::table('properties')->insert([
                    'property_dealer_id'  => $dealerId,
                    'title'               => $title,
                    'slug'                => $slug,
                    'description'         => $this->makeDesc($ptype, $bedrooms, $society, $zone['locality'], $zone['city'], $amenities, $possession),
                    'property_type'       => $ptype,
                    'bhk_type'            => $bhkType,
                    'looking_for'         => $lfor,
                    'option_type'         => $lfor === 'Rent' ? 'Rent' : 'Sell',
                    'listing_type'        => $pick(['Owner','Broker','Broker','Builder']),
                    'address'             => $society . ', ' . $zone['locality'] . ', ' . $zone['city'],
                    'city'                => $zone['city'],
                    'state'               => $zone['state'],
                    'country'             => 'India',
                    'pincode'             => $zone['pincode'],
                    'locality'            => $zone['locality'],
                    'society_name'        => $society,
                    'landmark'            => $zone['landmark'],
                    'price'               => $price,
                    'expected_price'      => $price,
                    'price_per_sqft'      => $ppsqft,
                    'monthly_rent'        => $rent,
                    'negotiable'          => rand(0, 1),
                    'maintenance_charges' => ($ptype !== 'Plot') ? rand(300, 4000) : null,
                    'bedrooms'            => $bedrooms,
                    'bathrooms'           => $bathrooms,
                    'balconies'           => $balconies,
                    'area'                => $area,
                    'furnishing'          => $furnish,
                    'furnishing_status'   => $furnish,
                    'facing'              => $facing,
                    'floor'               => $floorNumber,
                    'floor_number'        => $floorNumber,
                    'total_floors'        => $totalFloors,
                    'property_age'        => $propAge,
                    'possession_status'   => $possession,
                    'amenities'           => in_array($ptype, ['Plot','Shop','Office Space']) ? null : $amenities,
                    'status'              => $status,
                    'parking'             => rand(0, 2),
                    'is_featured'         => rand(0, 10) > 8 ? 1 : 0,
                    'is_premium'          => rand(0, 10) > 9 ? 1 : 0,
                    'views_count'         => rand(5, 2500),
                    'isreal'              => 1,
                    'latitude'            => $lat,
                    'longitude'           => $lng,
                    'created_at'          => now()->subDays(rand(0, 365)),
                    'updated_at'          => now(),
                ]);
            }
            $totalInserted += $zone['count'];
        }
        $this->command->info("    ✔ {$totalInserted} properties seeded.");
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function getConfig(string $ptype, string $city, string $lfor): array
    {
        $multiplier = match($city) {
            'Solan','Kasauli'             => 1.10, // hill premium
            'Chandigarh'                  => 1.50,
            'Mohali'                      => 1.20,
            'Pinjore','Kalka'             => 0.95, // hill-adjacent
            'Patiala'                     => 0.85,
            'Mullanpur'                   => 1.30,
            'Panchkula'                   => 1.10,
            'Ambala'                      => 0.75,
            'Ropar','Rupnagar'            => 0.65,
            'Barotiwala','Baddi'          => 0.65, // HP industrial
            'Nalagarh'                    => 0.60,
            'Gharuan','Landran'           => 0.75,
            'Kharar'                      => 0.90,
            'Derabassi'                   => 0.80,
            'Rajpura','Kurali'            => 0.60,
            'Morinda','Fatehgarh Sahib'   => 0.60,
            'Banur'                       => 0.75,
            default                       => 0.70,
        };

        return match($ptype) {
            'Apartment','Studio Apartment' => (function() use ($ptype, $multiplier, $lfor) {
                $beds  = $ptype === 'Studio Apartment' ? 1 : rand(1, 4);
                $area  = match($beds) { 1=>rand(450,700), 2=>rand(900,1300), 3=>rand(1350,1800), default=>rand(2000,3200) };
                $price = $lfor === 'Rent' ? 0 : (int)round($area * rand(3500, 6500) * $multiplier / 10000) * 10000;
                return [$beds, max(1,$beds-1), max(1,$beds-1), $area, $price, $beds.' BHK'];
            })(),
            'Builder Floor','Independent Floor' => (function() use ($multiplier, $lfor) {
                $beds  = rand(2, 4);
                $area  = rand(900, 2200);
                $price = $lfor === 'Rent' ? 0 : (int)round($area * rand(3000, 5500) * $multiplier / 10000) * 10000;
                return [$beds, $beds-1, 1, $area, $price, $beds.' BHK'];
            })(),
            'Villa' => (function() use ($multiplier, $lfor) {
                $beds  = rand(3, 5);
                $area  = rand(2500, 5000);
                $price = $lfor === 'Rent' ? 0 : (int)round($area * rand(4500, 8000) * $multiplier / 10000) * 10000;
                return [$beds, $beds, 2, $area, $price, $beds.' BHK'];
            })(),
            'Penthouse' => (function() use ($multiplier) {
                $beds  = rand(3, 5);
                $area  = rand(3000, 6000);
                $price = (int)round($area * rand(6000, 12000) * $multiplier / 10000) * 10000;
                return [$beds, $beds, 3, $area, $price, $beds.' BHK'];
            })(),
            'Plot' => (function() use ($multiplier) {
                $area  = rand(100, 500) * 9;
                $price = (int)round($area * rand(1500, 4500) * $multiplier / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            'Shop' => (function() use ($multiplier) {
                $area  = rand(150, 800);
                $price = (int)round($area * rand(4000, 12000) * $multiplier / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            'Office Space' => (function() use ($multiplier) {
                $area  = rand(400, 2000);
                $price = (int)round($area * rand(3500, 9000) * $multiplier / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            default => [2, 2, 1, 1100, 3500000, '2 BHK'],
        };
    }

    private function getRent(string $ptype, ?int $beds, string $city): int
    {
        $base = match($city) {
            'Chandigarh'        => 18000,
            'Solan'             => 15000,
            'Mullanpur'         => 16000,
            'Mohali'            => 14000,
            'Panchkula'         => 12000,
            'Patiala'           => 10000,
            'Kalka','Pinjore'   => 9000,
            'Kharar','Gharuan'  => 9000,
            'Ambala'            => 8000,
            'Baddi','Barotiwala'=> 7000,
            'Nalagarh'          => 6000,
            'Ropar'             => 7000,
            'Rajpura','Kurali'  => 6000,
            'Morinda'           => 5500,
            'Fatehgarh Sahib'   => 5500,
            'Derabassi'         => 8000,
            default             => 6000,
        };
        return match($ptype) {
            'Villa'        => $base * 3 + rand(0, 8000),
            'Penthouse'    => $base * 4 + rand(0, 12000),
            'Shop'         => $base + rand(0, 6000),
            'Office Space' => $base + rand(3000, 15000),
            'Plot'         => 0,
            default        => $base * ($beds ?? 1) + rand(0, 4000),
        };
    }

    private function makeTitle(string $ptype, ?int $beds, ?string $bhk, string $society, string $locality, string $city, int $idx): string
    {
        $prefixes = ['Spacious','Modern','Well-Ventilated','Prime','Elegant','Luxurious','Cosy','Bright','Premium','Ready-to-Move'];
        $prefix   = $prefixes[$idx % count($prefixes)];
        if ($ptype === 'Plot') return "{$prefix} Plot in {$society}, {$locality}, {$city}";
        if (in_array($ptype, ['Shop','Office Space'])) return "{$prefix} {$ptype} in {$society}, {$locality}, {$city}";
        return "{$prefix} {$bhk} {$ptype} in {$society}, {$locality}, {$city}";
    }

    private function makeDesc(string $ptype, ?int $beds, string $society, string $locality, string $city, string $amenities, string $possession): string
    {
        $bhkStr = $beds ? "{$beds} BHK " : '';
        return "A well-maintained {$bhkStr}{$ptype} located in {$society}, {$locality}, {$city}. "
             . "Possession status: {$possession}. Key amenities include: {$amenities}. "
             . "The property offers excellent connectivity to major landmarks and is surrounded by established infrastructure. "
             . "Ideal for families, professionals and investors looking for quality living in {$city}'s prime locations.";
    }
}
