<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * ZirakpurProximitySeederBatch2
 *
 * Extends ZirakpurProximitySeeder with further distances:
 *   Reference point: lat 30.6400, lng 76.8190 (Srishti Avenue, Dhakoli, Zirakpur)
 *
 * Ring 7 : 6–8 km   (Derabassi, VIP Road Chandigarh border, Panchkula Sectors 5-7,
 *                     Chandigarh Industrial Area, NH-7 Far East)
 * Ring 8 : 8–15 km  (Chandigarh Sectors 34/35, Mohali Phase 7-8,
 *                     Chandigarh Sector 17/22, Banur)
 * Ring 9 : 15–25 km (Mullanpur New Chandigarh, Landran, Kharar, Sunny Enclave Kharar)
 */
class ZirakpurProximitySeederBatch2 extends Seeder
{
    const HOME_LAT = 30.6400;
    const HOME_LNG = 76.8190;

    public function run(): void
    {
        $this->command->info('🏠 Seeding Batch 2 proximity data (6–25 km from Srishti Avenue, Dhakoli)...');
        $this->seedDealers();
        $this->seedBuilders();
        $this->seedProperties();
        $this->command->info('✅ ZirakpurProximitySeederBatch2 complete!');
    }

    // =========================================================================
    // DEALERS  (50 records, 6–25 km from home, nearest first)
    // =========================================================================
    private function seedDealers(): void
    {
        $this->command->info('  → Seeding 50 extended proximity dealers...');

        $dealers = [

            // ── RING 7 : 6–8 km  ── Derabassi (6.2 km south) ───────────────
            ['first_name'=>'Ashok','last_name'=>'Jindal','company_name'=>'Jindal Real Estate Derabassi','phone'=>'+91 98151 35001','email'=>'ashok.jindal@jindalrealestatedbs.com','bio'=>'Jindal Real Estate is the top-rated agency in Derabassi, ~6 km south of Dhakoli. Expert in SBP Housing Park, Ansal Sushant City and plotted developments along NH-7. Trusted by 500+ buyers since 2007.','specializations'=>'Derabassi Properties,SBP Housing,Plotted Development','operating_cities'=>'Derabassi,Zirakpur,Panchkula'],
            ['first_name'=>'Suresh','last_name'=>'Goyal','company_name'=>'Goyal Properties Derabassi','phone'=>'+91 97800 35002','email'=>'suresh.goyal@goyalpropertiesdbs.in','bio'=>'Goyal Properties covers the Derabassi stretch from Baltana to Derabassi town. Expert in affordable builder floors and plots for budget buyers within the ₹20–50 lakh range.','specializations'=>'Budget Homes,Builder Floors,Derabassi Plots','operating_cities'=>'Derabassi,Zirakpur'],
            ['first_name'=>'Ramesh','last_name'=>'Verma','company_name'=>'Verma Estate Derabassi','phone'=>'+91 99143 35003','email'=>'ramesh.verma@vermaestatedbs.com','bio'=>'Verma Estate is the go-to agency for new project launches in Derabassi. Covering Ambika Florence Park, Signature Heights and Green Villas with expert buyer advisory.','specializations'=>'New Launches,Ambika Florence,Signature Heights','operating_cities'=>'Derabassi,Zirakpur,Mohali'],
            ['first_name'=>'Deepak','last_name'=>'Sood','company_name'=>'Sood Realtors Derabassi','phone'=>'+91 95010 35004','email'=>'deepak.sood@soodrealtorsdbs.com','bio'=>'Sood Realtors is an established firm covering Derabassi industrial and residential zones. Expert in commercial properties, shops and industrial plots in Derabassi\'s growing economy.','specializations'=>'Commercial,Industrial Plots,Shops,Derabassi','operating_cities'=>'Derabassi,Panchkula'],
            ['first_name'=>'Rohit','last_name'=>'Nanda','company_name'=>'Nanda Properties Derabassi','phone'=>'+91 98726 35005','email'=>'rohit.nanda@nandapropertiesdbs.in','bio'=>'Nanda Properties has 15 years of hyperlocal expertise in Derabassi. Helping buyers and investors navigate the Derabassi micro-market with verified listings and honest advice.','specializations'=>'Verified Listings,Resale,Residential,Derabassi','operating_cities'=>'Derabassi,Zirakpur'],
            ['first_name'=>'Santosh','last_name'=>'Mehta','company_name'=>'Mehta Homes Derabassi','phone'=>'+91 98763 35006','email'=>'santosh.mehta@mehtahomesdbs.com','bio'=>'Mehta Homes specialises in independent builder floors and plots in Derabassi extension areas. Known for fast closures and zero hidden charges. First-time buyer specialist.','specializations'=>'Builder Floors,Plots,First-Time Buyers','operating_cities'=>'Derabassi,Zirakpur'],
            ['first_name'=>'Gurpreet','last_name'=>'Sahota','company_name'=>'Sahota Real Estate Derabassi','phone'=>'+91 98765 35007','email'=>'gurpreet.sahota@sahota-realestate-dbs.com','bio'=>'Sahota Real Estate serves the Derabassi–Banur belt with 200+ active listings. Expert in villa and 3–4 BHK apartments for IT professionals relocating from Chandigarh.','specializations'=>'Villas,IT Professional Buyers,Derabassi-Banur','operating_cities'=>'Derabassi,Banur,Zirakpur'],
            ['first_name'=>'Manoj','last_name'=>'Chopra','company_name'=>'Chopra Estates Derabassi','phone'=>'+91 98157 35008','email'=>'manoj.chopra@chopraestates-dbs.in','bio'=>'Chopra Estates covers northern Derabassi towards the Zirakpur bypass. Expert in resale apartments and premium plotted colonies for discerning buyers.','specializations'=>'Resale Apartments,Premium Plots,Derabassi North','operating_cities'=>'Derabassi,Zirakpur'],
            ['first_name'=>'Harish','last_name'=>'Tandon','company_name'=>'Tandon Estate Agency Derabassi','phone'=>'+91 98761 35009','email'=>'harish.tandon@tandonestate-dbs.com','bio'=>'Tandon Estate Agency is a trusted name in Derabassi with consistent service since 2008. Expert in NRI property management and rental yield optimisation for investors.','specializations'=>'NRI Services,Rental Yield,Property Management','operating_cities'=>'Derabassi,Panchkula,Zirakpur'],
            ['first_name'=>'Vikram','last_name'=>'Sharma','company_name'=>'Sharma Property Hub Derabassi','phone'=>'+91 97801 35010','email'=>'vikram.sharma@sharmapropertyhub-dbs.in','bio'=>'Sharma Property Hub is the fast-growing agency in Derabassi. Tech-savvy with drone site tours and virtual 3D walkthroughs for NRI and out-of-state buyers.','specializations'=>'Tech-Driven,NRI Buyers,Derabassi New Projects','operating_cities'=>'Derabassi,Zirakpur,Chandigarh'],

            // ── RING 7 : 6–8 km  ── VIP Road Chandigarh Border (6.3 km north) ─
            ['first_name'=>'Sandeep','last_name'=>'Malhotra','company_name'=>'Malhotra Realty Chandigarh Border','phone'=>'+91 98769 35011','email'=>'sandeep.malhotra@malhotrarealty-viproad.com','bio'=>'Malhotra Realty operates at the VIP Road Chandigarh–Punjab border, ~6.3 km from Dhakoli. Expert in properties on the UT border belt, including Mani Majra sectors and CHB allotments.','specializations'=>'UT Border Properties,Mani Majra,VIP Road North','operating_cities'=>'Zirakpur,Chandigarh'],
            ['first_name'=>'Vivek','last_name'=>'Kapoor','company_name'=>'Kapoor Estate VIP Road North','phone'=>'+91 95927 35012','email'=>'vivek.kapoor@kapoorestates-viproadn.com','bio'=>'Kapoor Estate is strategically located on VIP Road\'s northern end toward Chandigarh. Covering premium properties in Mansa Devi Complex and Panchkula Sector 5 areas.','specializations'=>'Premium Flats,Mansa Devi,Panchkula Sector 5','operating_cities'=>'Panchkula,Chandigarh,Zirakpur'],
            ['first_name'=>'Rajesh','last_name'=>'Batra','company_name'=>'Batra Properties Panchkula Sector 5','phone'=>'+91 98154 35013','email'=>'rajesh.batra@batraproperties-pkls5.com','bio'=>'Batra Properties is the specialist for Panchkula Sectors 5 and 7. Expert in HUDA-allotted plots, independent houses and builder floors in these established Panchkula sectors.','specializations'=>'HUDA Plots,Independent Houses,Panchkula 5-7','operating_cities'=>'Panchkula,Chandigarh'],
            ['first_name'=>'Mukesh','last_name'=>'Arora','company_name'=>'Arora Realty Panchkula Sector 7','phone'=>'+91 99149 35014','email'=>'mukesh.arora@arorarealtypanchkulas7.com','bio'=>'Arora Realty covers Panchkula Sector 7 and adjacent sectors bordering the UT. Expert in independent house construction plots and well-planned Panchkula sector pricing.','specializations'=>'Panchkula Sectors,Construction Plots,Independent Houses','operating_cities'=>'Panchkula,Chandigarh,Zirakpur'],
            ['first_name'=>'Prabhat','last_name'=>'Sharma','company_name'=>'Sharma Homes Chandigarh Industrial Area','phone'=>'+91 98728 35015','email'=>'prabhat.sharma@sharmahomes-chdindustrial.in','bio'=>'Sharma Homes covers properties near Chandigarh\'s Industrial Area Phase 1, popular with factory workers, industrial staff and small business owners seeking affordable housing nearby.','specializations'=>'Industrial Area Housing,Affordable Flats,Chandigarh','operating_cities'=>'Chandigarh,Panchkula'],

            // ── RING 8 : 8–15 km  ── Chandigarh Sectors, Mohali Phase 7-8 ────
            ['first_name'=>'Dinesh','last_name'=>'Kapila','company_name'=>'Kapila Property Consultants Chandigarh','phone'=>'+91 98152 35016','email'=>'dinesh.kapila@kapilaconsultants-chd.com','bio'=>'Kapila Property Consultants is a premium agency in Chandigarh Sector 34-A. Handling resale CHB allotments, independent houses in Sectors 33–36 and luxury flats with 20+ years of UT expertise.','specializations'=>'CHB Allotments,Chandigarh Sectors 33-36,Resale','operating_cities'=>'Chandigarh,Panchkula'],
            ['first_name'=>'Mohan','last_name'=>'Sahni','company_name'=>'Sahni Estate Chandigarh','phone'=>'+91 98155 35017','email'=>'mohan.sahni@sahniestates-chd.in','bio'=>'Sahni Estate is among the oldest property agencies in Chandigarh Sector 35. Trusted by government employees for subsidised housing and investment advice in the Chandigarh periphery.','specializations'=>'Government Housing,Chandigarh Sectors,Subsidised Housing','operating_cities'=>'Chandigarh,Panchkula,Mohali'],
            ['first_name'=>'Rakesh','last_name'=>'Singla','company_name'=>'Singla Homes Chandigarh Sector 17','phone'=>'+91 98158 35018','email'=>'rakesh.singla@singlahomes-chandigarh.com','bio'=>'Singla Homes is the preferred choice for Chandigarh Sector 17/22 premium residential properties. Expert in high-end independent houses, SCO commercial shops and high-rise apartments.','specializations'=>'Sector 17-22 Premium,SCO Shops,High-Rise Apartments','operating_cities'=>'Chandigarh'],
            ['first_name'=>'Harinder','last_name'=>'Chopra','company_name'=>'Chopra Property Services Sector 17','phone'=>'+91 98760 35019','email'=>'harinder.chopra@choprapropertychd.com','bio'=>'Chopra Property Services is located in the heart of Sector 17 Chandigarh. Expert in commercial properties on the V2 road, office spaces and residential flats in the central sectors.','specializations'=>'Commercial V2 Road,Office Spaces,Central Sectors','operating_cities'=>'Chandigarh'],
            ['first_name'=>'Inder','last_name'=>'Vohra','company_name'=>'Vohra Realtors Chandigarh Sector 22','phone'=>'+91 98140 35020','email'=>'inder.vohra@vohrarealtor-s22.in','bio'=>'Vohra Realtors is a seasoned firm in Sector 22 Chandigarh with 18 years of experience. Specialises in CHB flats, PUDA properties and plotted development for end-users.','specializations'=>'CHB Flats,PUDA Properties,Sector 22 Chandigarh','operating_cities'=>'Chandigarh,Panchkula'],
            ['first_name'=>'Vipin','last_name'=>'Gupta','company_name'=>'Gupta Estate Mohali Phase 7','phone'=>'+91 98143 35021','email'=>'vipin.gupta@guptaestatephase7.com','bio'=>'Gupta Estate is the leading agency for Mohali Phase 7. Expert in mid-range 2 & 3 BHK flats in established Phase 7 societies and investment plots near the phase boundary.','specializations'=>'Mohali Phase 7,2-3 BHK,Investment Plots','operating_cities'=>'Mohali,Zirakpur,Chandigarh'],
            ['first_name'=>'Naveen','last_name'=>'Bansal','company_name'=>'Bansal Properties Mohali Phase 7-8','phone'=>'+91 98145 35022','email'=>'naveen.bansal@bansalproperties-phase78.com','bio'=>'Bansal Properties covers the Phase 7-8 corridor in Mohali thoroughly. Known for strong builder relationships and exclusive first-mover deals in upcoming projects.','specializations'=>'Phase 7-8 Mohali,Pre-Launch,Exclusive Deals','operating_cities'=>'Mohali,Chandigarh'],
            ['first_name'=>'Rakesh','last_name'=>'Walia','company_name'=>'Walia Realtors Mohali Sector 82','phone'=>'+91 98141 35023','email'=>'rakesh.walia@waliarealtor-sec82.in','bio'=>'Walia Realtors is a specialist for Mohali Sector 82 properties — the IT hub adjacent zone. Expert in studio to 3 BHK apartments preferred by working professionals.','specializations'=>'Sector 82 Mohali,IT Hub Housing,Studio-3BHK','operating_cities'=>'Mohali,Chandigarh'],
            ['first_name'=>'Pankaj','last_name'=>'Dhawan','company_name'=>'Dhawan Properties Mohali','phone'=>'+91 98726 35024','email'=>'pankaj.dhawan@dhawanproperties-mohali.com','bio'=>'Dhawan Properties is a full-service agency for buyers in Mohali Phases 7–11. 12 years of Mohali expertise with dedicated NRI desk and post-purchase property management.','specializations'=>'Mohali All Phases,NRI Desk,Property Management','operating_cities'=>'Mohali,Chandigarh,Panchkula'],
            ['first_name'=>'Subhash','last_name'=>'Chadha','company_name'=>'Chadha Real Estate Mohali','phone'=>'+91 98763 35025','email'=>'subhash.chadha@chadharealestatemohali.in','bio'=>'Chadha Real Estate is a veteran Mohali firm with 22 years of deal history. Expert in premium villas, SCO commercial plots and luxury apartments in Mohali\'s developed phases.','specializations'=>'Luxury Villas,SCO Commercial,Premium Mohali','operating_cities'=>'Mohali,Chandigarh'],

            // ── RING 8–9 : 12–18 km  ── Mullanpur, Landran, Banur ────────────
            ['first_name'=>'Ajay','last_name'=>'Sahota','company_name'=>'Sahota Real Estate Mullanpur','phone'=>'+91 98766 35026','email'=>'ajay.sahota@sahota-mullanpur.com','bio'=>'Sahota Real Estate is the pioneer agency in New Chandigarh (Mullanpur). Covering DLF Hyde Park, Omaxe Heritage and Bestech Park View for buyers moving out of the UT.','specializations'=>'New Chandigarh Mullanpur,DLF Hyde Park,Bestech','operating_cities'=>'Mullanpur,Mohali,Chandigarh'],
            ['first_name'=>'Tejinder','last_name'=>'Bali','company_name'=>'Bali Estate Mullanpur New Chandigarh','phone'=>'+91 97801 35027','email'=>'tejinder.bali@bali-mullanpur.com','bio'=>'Bali Estate serves Mullanpur and the adjacent Chandigarh eco-city planning zone. Expert in GMADA-approved plots, luxury villas and gated communities in New Chandigarh.','specializations'=>'GMADA Plots,Luxury Villas,New Chandigarh','operating_cities'=>'Mullanpur,Chandigarh'],
            ['first_name'=>'Amarjit','last_name'=>'Randhawa','company_name'=>'Randhawa Estate Mullanpur','phone'=>'+91 98765 35028','email'=>'amarjit.randhawa@randhawaestate-mullanpur.in','bio'=>'Randhawa Estate is a veteran in the Mullanpur real estate market. Expert in DLF, Pearl and Bestech project allocations with full documentation support.','specializations'=>'DLF,Pearl,Bestech Mullanpur,New Chandigarh','operating_cities'=>'Mullanpur,Chandigarh,Mohali'],
            ['first_name'=>'Jaspal','last_name'=>'Narang','company_name'=>'Narang Properties Landran Mohali','phone'=>'+91 98729 35029','email'=>'jaspal.narang@narangproperties-landran.com','bio'=>'Narang Properties covers Landran and the Chandigarh University corridor. Expert in student housing, faculty residences and investment properties in the Landran education belt.','specializations'=>'Student Housing,Faculty Residences,Landran Mohali','operating_cities'=>'Landran,Mohali,Chandigarh'],
            ['first_name'=>'Harbhajan','last_name'=>'Bains','company_name'=>'Bains Estate Landran','phone'=>'+91 95010 35030','email'=>'harbhajan.bains@bainsestatelandran.in','bio'=>'Bains Estate is a reliable agency near Landran village covering plotted colonies and affordable 2 BHK apartments popular with Chandigarh University staff and students.','specializations'=>'Affordable Apartments,Landran Plots,University Belt','operating_cities'=>'Landran,Mohali'],
            ['first_name'=>'Gurmail','last_name'=>'Dhaliwal','company_name'=>'Dhaliwal Properties Banur','phone'=>'+91 99145 35031','email'=>'gurmail.dhaliwal@dhaliwalpropertiesbanur.com','bio'=>'Dhaliwal Properties covers Banur and the Rajpura Road belt. Expert in affordable plotted colonies, self-build plots and investment-grade properties in this emerging zone.','specializations'=>'Banur Plots,Self-Build,Affordable Investment','operating_cities'=>'Banur,Derabassi,Zirakpur'],
            ['first_name'=>'Joginder','last_name'=>'Bhullar','company_name'=>'Bhullar Real Estate Banur','phone'=>'+91 98151 35032','email'=>'joginder.bhullar@bhullarrealestate-banur.in','bio'=>'Bhullar Real Estate is the most active agency in Banur town. Expert in flat land acquisition for farm conversions and builder-floor plots in the fast-developing Banur corridor.','specializations'=>'Land Acquisition,Farm Conversion,Builder Floors Banur','operating_cities'=>'Banur,Derabassi,Patiala'],
            ['first_name'=>'Sukhdev','last_name'=>'Toor','company_name'=>'Toor Properties Landran Banur','phone'=>'+91 98720 35033','email'=>'sukhdev.toor@toorproperties-landranbanur.com','bio'=>'Toor Properties bridges the Landran and Banur markets, helping buyers compare these two emerging hubs just 5–7 km apart. Expert in plot resale and new colony launches.','specializations'=>'Landran-Banur Corridor,Plot Resale,New Colonies','operating_cities'=>'Landran,Banur,Mohali'],

            // ── RING 9 : 18–25 km  ── Kharar, Sunny Enclave ─────────────────
            ['first_name'=>'Satinder','last_name'=>'Sandhu','company_name'=>'Sandhu Homes Kharar','phone'=>'+91 98769 35034','email'=>'satinder.sandhu@sandhuhomeskharar.com','bio'=>'Sandhu Homes is the oldest established real estate firm in Kharar, ~20 km from Dhakoli. Expert in Sunny Enclave, CHD Builders and Chandigarh University-adjacent housing projects.','specializations'=>'Kharar Properties,Sunny Enclave,CHD Builders','operating_cities'=>'Kharar,Mohali,Chandigarh'],
            ['first_name'=>'Manmohan','last_name'=>'Gill','company_name'=>'Gill Properties Kharar','phone'=>'+91 98767 35035','email'=>'manmohan.gill@gillpropertieskharar.in','bio'=>'Gill Properties is a full-service agency in Kharar town. Expert in the Sunny Enclave township which houses 10,000+ families and is one of Punjab\'s largest self-developed colonies.','specializations'=>'Sunny Enclave,Kharar Township,Family Homes','operating_cities'=>'Kharar,Mohali'],
            ['first_name'=>'Balwinder','last_name'=>'Sekhon','company_name'=>'Sekhon Estate Kharar Sector 126','phone'=>'+91 98154 35036','email'=>'balwinder.sekhon@sekhonestate-kharar.com','bio'=>'Sekhon Estate covers GMADA Sector 126 Kharar — a planned residential sector adjacent to Chandigarh. Expert in GMADA-allotted plots and builder-floor development in this sector.','specializations'=>'GMADA Sector 126,Kharar,Planned Residential','operating_cities'=>'Kharar,Chandigarh,Mohali'],
            ['first_name'=>'Amrinder','last_name'=>'Singh','company_name'=>'Singh Realtors Kharar','phone'=>'+91 98726 35037','email'=>'amrinder.singh@singhrealtorkharar.in','bio'=>'Singh Realtors is a data-driven agency in Kharar dealing in residential apartments and plots across all major societies from OSB to Pacific Blue to Imperia Esfera.','specializations'=>'OSB Kharar,Pacific Blue,Imperia Esfera','operating_cities'=>'Kharar,Mohali'],
            ['first_name'=>'Paramjit','last_name'=>'Kalra','company_name'=>'Kalra Homes Kharar','phone'=>'+91 95927 35038','email'=>'paramjit.kalra@kalrahomeskharar.com','bio'=>'Kalra Homes is a well-known agency in Kharar serving buyers from Chandigarh, Mohali and Punjab hinterland. Expert in affordable 1 & 2 BHK investments for working professionals.','specializations'=>'Affordable 1-2 BHK,Working Professionals,Kharar','operating_cities'=>'Kharar,Chandigarh'],
            ['first_name'=>'Ravinder','last_name'=>'Maan','company_name'=>'Maan Properties Sunny Enclave Kharar','phone'=>'+91 98764 35039','email'=>'ravinder.maan@maanproperties-sunnyenclave.com','bio'=>'Maan Properties is the go-to specialist inside Sunny Enclave colony, Kharar. With 14 years inside the colony, they have unmatched knowledge of plot sizes, rates and resale opportunities.','specializations'=>'Sunny Enclave Expert,Plot Resale,Colony Specialist','operating_cities'=>'Kharar,Mohali'],
            ['first_name'=>'Harbans','last_name'=>'Cheema','company_name'=>'Cheema Properties Kharar','phone'=>'+91 98728 35040','email'=>'harbans.cheema@cheemapropertieskharar.in','bio'=>'Cheema Properties covers all of Kharar\'s residential zones including Vatika India Next, CHD One Avenue and Bestech Garden Enclave. Comprehensive buyer consultancy in Kharar.','specializations'=>'Vatika India Next,CHD One Avenue,Bestech Kharar','operating_cities'=>'Kharar,Mohali,Chandigarh'],
            ['first_name'=>'Harjinder','last_name'=>'Dhillon','company_name'=>'Dhillon Estate Kharar','phone'=>'+91 98762 35041','email'=>'harjinder.dhillon@dhillonestate-kharar.com','bio'=>'Dhillon Estate is a premium agency in Kharar with strong corporate tie-ups for employee housing. Expert in bulk purchase deals for IT companies sourcing housing near Kharar township.','specializations'=>'Corporate Housing,Bulk Deals,IT Companies Kharar','operating_cities'=>'Kharar,Mohali,Chandigarh'],
            ['first_name'=>'Gurdarshan','last_name'=>'Dhindsa','company_name'=>'Dhindsa Realty Kharar','phone'=>'+91 99143 35042','email'=>'gurdarshan.dhindsa@dhindsa-realty-kharar.com','bio'=>'Dhindsa Realty is a well-established Kharar agency operating since 2005. Expert in pre-development land parcels and investor syndicates for large-scale plot acquisition in Kharar\'s growing zones.','specializations'=>'Land Parcels,Investor Syndicates,Kharar Long Term','operating_cities'=>'Kharar,Chandigarh'],
            ['first_name'=>'Navdeep','last_name'=>'Brar','company_name'=>'Brar Real Estate Kharar','phone'=>'+91 98725 35043','email'=>'navdeep.brar@brarealestate-kharar.in','bio'=>'Brar Real Estate is a modern agency in Kharar combining online listing portals with on-ground expertise. Top-rated on property portals for responsiveness and deal accuracy.','specializations'=>'Top-Rated Agency,Digital First,Kharar Residential','operating_cities'=>'Kharar,Mohali'],
            ['first_name'=>'Satvinder','last_name'=>'Grewal','company_name'=>'Grewal Estate Kharar','phone'=>'+91 95011 35044','email'=>'satvinder.grewal@grewalestatenewcity-kharar.in','bio'=>'Grewal Estate is among Kharar\'s fastest-growing agencies. Specialising in gated township properties and upcoming GMADA sectors with long-term capital appreciation potential.','specializations'=>'Gated Townships,GMADA Sectors,Capital Appreciation','operating_cities'=>'Kharar,Chandigarh'],

            // ── Additional: Chandigarh commercial & Mohali IT Park ────────────
            ['first_name'=>'Rajan','last_name'=>'Mehta','company_name'=>'Mehta Commercial Properties Chandigarh','phone'=>'+91 98729 35045','email'=>'rajan.mehta@mehtacommercial-chd.in','bio'=>'Mehta Commercial Properties is Chandigarh\'s specialist for IT Park office spaces, Sector 34 commercial plots and Sector 17/35 showroom spaces. 25 years of commercial expertise.','specializations'=>'IT Park Office,Commercial Chandigarh,Showrooms','operating_cities'=>'Chandigarh,Mohali'],
            ['first_name'=>'Atul','last_name'=>'Kakkar','company_name'=>'Kakkar Property Group Chandigarh','phone'=>'+91 98724 35046','email'=>'atul.kakkar@kakkarpropertygroup.in','bio'=>'Kakkar Property Group operates across Chandigarh sectors and the UT periphery. Trusted by corporates for relocation packages and residential campus planning for MNCs in Mohali IT Park.','specializations'=>'Corporate Relocation,MNC Housing,Chandigarh UT','operating_cities'=>'Chandigarh,Mohali,Panchkula'],
            ['first_name'=>'Sunil','last_name'=>'Bhasin','company_name'=>'Bhasin Realty Chandigarh Sectors','phone'=>'+91 98154 35047','email'=>'sunil.bhasin@bhasinrealty-chandigarh.com','bio'=>'Bhasin Realty is a high-net-worth specialist in Chandigarh\'s Sector 9 and 11 luxury independent houses. Known for discreet transactions and premium network among Chandigarh\'s elite.','specializations'=>'Luxury Independent Houses,Sector 9-11,HNI Buyers','operating_cities'=>'Chandigarh'],
            ['first_name'=>'Pankaj','last_name'=>'Gumber','company_name'=>'Gumber Properties Mohali IT Park','phone'=>'+91 98762 35048','email'=>'pankaj.gumber@gumberproperties-itpark.in','bio'=>'Gumber Properties is the preferred housing partner for Mohali IT Park employees. Expert in studio apartments, 1–2 BHK flats and co-living spaces near the IT corridor.','specializations'=>'IT Park Housing,Studio Apartments,Co-Living','operating_cities'=>'Mohali,Chandigarh'],
            ['first_name'=>'Gurpreet','last_name'=>'Sohi','company_name'=>'Sohi Properties New Chandigarh','phone'=>'+91 98768 35049','email'=>'gurpreet.sohi@sohinewchandigarh.in','bio'=>'Sohi Properties is a market leader in the New Chandigarh/Mullanpur township zone. Expert in under-construction projects with projected delivery dates and builder vetting.','specializations'=>'New Chandigarh,Under Construction,Builder Vetting','operating_cities'=>'Mullanpur,Chandigarh,Mohali'],
            ['first_name'=>'Amardeep','last_name'=>'Sohal','company_name'=>'Sohal Realty Kharar Chandigarh','phone'=>'+91 98766 35050','email'=>'amardeep.sohal@sohalrealty-kharar.com','bio'=>'Sohal Realty bridges Kharar and Chandigarh markets with a team of 12 advisors. Expert in guiding buyers who compare Kharar vs Mohali vs Zirakpur for best value per rupee.','specializations'=>'Market Comparison,Kharar vs Mohali,Value Advisory','operating_cities'=>'Kharar,Mohali,Chandigarh,Zirakpur'],
        ];

        foreach ($dealers as $d) {
            $baseSlug = Str::slug($d['company_name']);
            $slug = $baseSlug;
            $i = 1;
            while (DB::table('property_dealers')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            if (DB::table('property_dealers')->where('email', $d['email'])->exists()) {
                $this->command->line("    [skip] {$d['company_name']} — already exists");
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
        $this->command->info('    ✔ 50 extended dealers seeded.');
    }

    // =========================================================================
    // BUILDERS  (15 records, 6–25 km from home) + ~25 projects
    // =========================================================================
    private function seedBuilders(): void
    {
        $this->command->info('  → Seeding 15 extended proximity builders + projects...');

        $builders = [

            // ── 6–8 km : Derabassi builders ─────────────────────────────────
            [
                'name'                     => 'Ansal API',
                'company_name'             => 'Ansal API (Punjab Projects)',
                'email'                    => 'punjab@ansalapi.com',
                'phone'                    => '+91 98151 45001',
                'website'                  => 'https://www.ansalapi.com',
                'city'                     => 'Derabassi',
                'established_year'         => '1967',
                'rera_registration'        => 'PBRERA-SAS79-PR2800',
                'cities_operating'         => 'Derabassi,Zirakpur,Panchkula,Chandigarh',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 35,
                'description'              => 'Ansal API is one of India\'s oldest real estate groups with a 55-year legacy. In Punjab, they developed Ansal Sushant City in Derabassi — a 300-acre self-sufficient township. Known for large-format integrated townships with schools, hospitals and shopping zones.',
            ],
            [
                'name'                     => 'Ambika Realcon',
                'company_name'             => 'Ambika Realcon Pvt. Ltd.',
                'email'                    => 'info@ambikarealcon.in',
                'phone'                    => '+91 98726 45002',
                'website'                  => 'https://www.ambikarealcon.in',
                'city'                     => 'Derabassi',
                'established_year'         => '2005',
                'rera_registration'        => 'PBRERA-SAS79-PR1800',
                'cities_operating'         => 'Derabassi,Zirakpur,Panchkula',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'Ambika Realcon is a reputed Derabassi-based developer known for Ambika Florence Park and Ambika Greens residential projects. Strong focus on affordable luxury with quality construction and timely delivery since 2005.',
            ],
            [
                'name'                     => 'Signature Realtors Derabassi',
                'company_name'             => 'Signature Realtors Derabassi Pvt. Ltd.',
                'email'                    => 'info@signaturerealtor-dbs.in',
                'phone'                    => '+91 97800 45003',
                'website'                  => 'https://www.signaturerealtor-dbs.in',
                'city'                     => 'Derabassi',
                'established_year'         => '2010',
                'rera_registration'        => 'PBRERA-SAS79-PR1900',
                'cities_operating'         => 'Derabassi,Zirakpur',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Signature Realtors Derabassi is a mid-sized developer specialising in residential apartments and builder floors in the Derabassi micro-market. Known for transparent pricing and customer-first approach in the affordable segment.',
            ],

            // ── 10–18 km : Chandigarh / Mohali / Mullanpur builders ──────────
            [
                'name'                     => 'DLF Limited Tricity',
                'company_name'             => 'DLF Limited (Tricity Division)',
                'email'                    => 'tricity@dlfindia.com',
                'phone'                    => '+91 98760 45004',
                'website'                  => 'https://www.dlf.in',
                'city'                     => 'Chandigarh',
                'established_year'         => '1946',
                'rera_registration'        => 'PBRERA-SAS79-PR0120',
                'cities_operating'         => 'Mullanpur,Chandigarh,Panchkula,Mohali',
                'rating'                   => 4.5,
                'is_verified'              => true,
                'total_delivered_projects' => 180,
                'description'              => 'DLF Limited is India\'s largest real estate developer with 75+ years of history. In the Tricity region, DLF developed Hyde Park in Mullanpur (New Chandigarh) — premium 3 & 4 BHK residences spanning 250 acres, setting a new standard for luxury living near Chandigarh.',
            ],
            [
                'name'                     => 'Omaxe Limited Chandigarh',
                'company_name'             => 'Omaxe Limited (Chandigarh Extension)',
                'email'                    => 'chandigarh@omaxe.com',
                'phone'                    => '+91 98727 45005',
                'website'                  => 'https://www.omaxe.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '1989',
                'rera_registration'        => 'PBRERA-SAS79-PR0180',
                'cities_operating'         => 'Mullanpur,Chandigarh,Mohali,New Chandigarh',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 120,
                'description'              => 'Omaxe Limited is a pan-India developer with strong presence in Tricity. Their flagship Chandigarh Extension project in New Chandigarh/Mullanpur offers plotted development, builder floors and apartments in a planned eco-zone near Chandigarh.',
            ],
            [
                'name'                     => 'Bestech Group',
                'company_name'             => 'Bestech Group',
                'email'                    => 'info@bestechgroup.com',
                'phone'                    => '+91 98152 45006',
                'website'                  => 'https://www.bestechgroup.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '1998',
                'rera_registration'        => 'PBRERA-SAS79-PR0240',
                'cities_operating'         => 'Mullanpur,Kharar,Mohali,Chandigarh',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 22,
                'description'              => 'Bestech Group is a well-known Chandigarh-region developer with two decades of trust. Their Bestech Park View City in Mullanpur and Bestech Woodsville are landmark luxury villa projects. Strong focus on quality construction and green living.',
            ],
            [
                'name'                     => 'Pearl Infrastructure Projects',
                'company_name'             => 'Pearl Infrastructure Projects Pvt. Ltd.',
                'email'                    => 'info@pearlsinfrastructure.com',
                'phone'                    => '+91 98763 45007',
                'website'                  => 'https://www.pearlsinfrastructure.com',
                'city'                     => 'Chandigarh',
                'established_year'         => '2001',
                'rera_registration'        => 'PBRERA-SAS79-PR0260',
                'cities_operating'         => 'Mullanpur,Kharar,Mohali,Chandigarh',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'Pearl Infrastructure Projects is a respected Chandigarh-based developer with Pearl City in Mullanpur being its landmark township. The project covers 200+ acres with mixed-use residential, commercial and civic zones near New Chandigarh.',
            ],
            [
                'name'                     => 'APS Group Mullanpur',
                'company_name'             => 'APS Group (Mullanpur)',
                'email'                    => 'info@apsgroup-mullanpur.in',
                'phone'                    => '+91 98729 45008',
                'website'                  => 'https://www.apsgroup-mullanpur.in',
                'city'                     => 'Mullanpur',
                'established_year'         => '2007',
                'rera_registration'        => 'PBRERA-SAS79-PR1300',
                'cities_operating'         => 'Mullanpur,New Chandigarh,Mohali',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'APS Group is a Mullanpur-focused developer offering quality mid-segment apartments in the New Chandigarh growth corridor. Their APS City of Dreams is a popular mid-budget project for families transitioning from Chandigarh to the New Chandigarh periphery.',
            ],

            // ── 18–25 km : Kharar / Sunny Enclave builders ────────────────────
            [
                'name'                     => 'Sunny Real Estate Kharar',
                'company_name'             => 'Sunny Real Estate (Sunny Enclave Kharar)',
                'email'                    => 'info@sunnyenclave-kharar.com',
                'phone'                    => '+91 98725 45009',
                'website'                  => 'https://www.sunnyenclave-kharar.com',
                'city'                     => 'Kharar',
                'established_year'         => '1985',
                'rera_registration'        => 'PBRERA-SAS79-PR0090',
                'cities_operating'         => 'Kharar,Mohali,Chandigarh',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 16,
                'description'              => 'Sunny Real Estate developed Sunny Enclave in Kharar — one of Punjab\'s largest self-developed colonies with 10,000+ residential plots spread over 1,200 acres. Sunny Enclave is a landmark self-sustaining colony with its own water supply, roads and civic infrastructure.',
            ],
            [
                'name'                     => 'OSB Group Kharar',
                'company_name'             => 'OSB Group (Kharar)',
                'email'                    => 'info@osbgroup-kharar.in',
                'phone'                    => '+91 95927 45010',
                'website'                  => 'https://www.osbgroup-kharar.in',
                'city'                     => 'Kharar',
                'established_year'         => '2003',
                'rera_registration'        => 'PBRERA-SAS79-PR0850',
                'cities_operating'         => 'Kharar,Mohali,Chandigarh',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 11,
                'description'              => 'OSB Group is a Kharar-based developer known for OSB Golf Heights and OSB Sherwood residential complexes. Premium construction quality with European design elements, targeting IT professionals and Chandigarh University faculty.',
            ],
            [
                'name'                     => 'CHD Developers Kharar',
                'company_name'             => 'CHD Developers Ltd. (Kharar)',
                'email'                    => 'info@chddevelopers-kharar.com',
                'phone'                    => '+91 98724 45011',
                'website'                  => 'https://www.chddevelopers.com',
                'city'                     => 'Kharar',
                'established_year'         => '2004',
                'rera_registration'        => 'PBRERA-SAS79-PR0910',
                'cities_operating'         => 'Kharar,Mohali,Chandigarh',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'CHD Developers is a quality builder in Kharar with CHD One Avenue and CHD Vann being their flagship projects. Known for innovative architecture, sustainable design and RERA-compliant delivery timelines in the Kharar township zone.',
            ],
            [
                'name'                     => 'Vatika Group Punjab',
                'company_name'             => 'Vatika Group (Punjab)',
                'email'                    => 'punjab@vatikagroup.com',
                'phone'                    => '+91 98728 45012',
                'website'                  => 'https://www.vatikagroup.com',
                'city'                     => 'Kharar',
                'established_year'         => '1986',
                'rera_registration'        => 'PBRERA-SAS79-PR0760',
                'cities_operating'         => 'Kharar,Chandigarh,Mohali',
                'rating'                   => 4.4,
                'is_verified'              => true,
                'total_delivered_projects' => 55,
                'description'              => 'Vatika Group is a pan-India developer with 35+ years of experience. In Punjab, Vatika India Next Township in Kharar is their marquee project — a 250-acre integrated township with residential sectors, commercial zones, schools and a technology park near Chandigarh University.',
            ],
            [
                'name'                     => 'Countrywide Promoters',
                'company_name'             => 'Countrywide Promoters Pvt. Ltd.',
                'email'                    => 'info@countrywidepromoters.com',
                'phone'                    => '+91 98766 45013',
                'website'                  => 'https://www.countrywidepromoters.com',
                'city'                     => 'Landran',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS79-PR1100',
                'cities_operating'         => 'Landran,Banur,Mohali,Kharar',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Countrywide Promoters is a growing Mohali-fringe developer active in Landran and Banur. Known for affordable plotted colonies and 2 BHK residential projects targeting first-time buyers near Chandigarh University and adjacent IT campuses.',
            ],
            [
                'name'                     => 'Imperia Structures Kharar',
                'company_name'             => 'Imperia Structures Ltd. (Kharar)',
                'email'                    => 'kharar@imperiastructures.com',
                'phone'                    => '+91 99145 45014',
                'website'                  => 'https://www.imperiastructures.com',
                'city'                     => 'Kharar',
                'established_year'         => '2002',
                'rera_registration'        => 'PBRERA-SAS79-PR0970',
                'cities_operating'         => 'Kharar,Mohali,Chandigarh',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Imperia Structures is a quality residential developer with Imperia Esfera being their landmark project in Kharar. Offering 2 & 3 BHK sky-villa style apartments with rooftop amenities at competitive prices for the Chandigarh periphery market.',
            ],
            [
                'name'                     => 'GBP Group Kharar',
                'company_name'             => 'GBP Group (Kharar Division)',
                'email'                    => 'kharar@gbpgroup.com',
                'phone'                    => '+91 98767 45015',
                'website'                  => 'https://www.gbpgroup.com',
                'city'                     => 'Kharar',
                'established_year'         => '2000',
                'rera_registration'        => 'PBRERA-SAS79-PR0680',
                'cities_operating'         => 'Kharar,Zirakpur,Mohali,Chandigarh',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'GBP Group extended its successful Zirakpur portfolio to Kharar with GBP Centra and GBP Rosewood. Known for investor-friendly pricing, well-planned layouts and strong resale value in the Kharar growth corridor.',
            ],
        ];

        $projectMap = [
            'punjab@ansalapi.com' => [
                ['title'=>'Ansal Sushant City Derabassi','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'Sushant City, Derabassi, Punjab','city'=>'Derabassi','state'=>'Punjab','total_units'=>2400,'available_units'=>60,'price_from'=>2800000,'price_to'=>8500000,'possession_date'=>'2018-06-30','rera_id'=>'PBRERA-SAS79-PR2800','total_towers'=>48,'floors_per_tower'=>'9','latitude'=>30.5895,'longitude'=>76.8372,'amenities'=>'School,Hospital,Shopping Mall,Park,24x7 Security,Swimming Pool,Gymnasium,Clubhouse,Tennis Court','nearby_schools'=>'Sushant School (on-site)','nearby_hospitals'=>'Sushant Clinic (on-site)','metro_distance'=>'8 km from Chandigarh','is_featured'=>true,'description'=>'Ansal Sushant City Derabassi is a self-sufficient integrated township spread across 300 acres. One of Punjab\'s first fully-planned townships with on-site school, hospital and shopping zone.'],
                ['title'=>'Ansal API Floors Derabassi','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Ansal API Extension, Derabassi','city'=>'Derabassi','state'=>'Punjab','total_units'=>320,'available_units'=>18,'price_from'=>2200000,'price_to'=>4500000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR2801','total_towers'=>null,'floors_per_tower'=>'4','latitude'=>30.5880,'longitude'=>76.8380,'amenities'=>'Parking,Security,Power Backup,Park,CCTV','nearby_schools'=>'DAV Derabassi (1 km)','nearby_hospitals'=>'Civil Hospital Derabassi (2 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>false,'description'=>'Ansal API Floors in Derabassi Extension — quality independent builder floors with 3 & 4 BHK options at affordable rates in the Derabassi growth belt.'],
            ],
            'info@ambikarealcon.in' => [
                ['title'=>'Ambika Florence Park Derabassi','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Near Derabassi Bypass, Derabassi','city'=>'Derabassi','state'=>'Punjab','total_units'=>280,'available_units'=>15,'price_from'=>2600000,'price_to'=>5500000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS79-PR1800','total_towers'=>10,'floors_per_tower'=>'7','latitude'=>30.5885,'longitude'=>76.8388,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Kids Play Area,Jogging Track','nearby_schools'=>'DAV Public School (1.5 km)','nearby_hospitals'=>'Govt. Hospital Derabassi (2 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>true,'description'=>'Ambika Florence Park is a gated residential community in Derabassi with Florentine-inspired architecture. 2, 3 & 4 BHK apartments with premium finishes at competitive prices.'],
                ['title'=>'Ambika Greens Derabassi','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Derabassi–Zirakpur Road, Derabassi','city'=>'Derabassi','state'=>'Punjab','total_units'=>180,'available_units'=>10,'price_from'=>2000000,'price_to'=>4000000,'possession_date'=>'2019-09-30','rera_id'=>'PBRERA-SAS79-PR1801','total_towers'=>6,'floors_per_tower'=>'6','latitude'=>30.5900,'longitude'=>76.8365,'amenities'=>'Security,Power Backup,CCTV,Lift,Kids Play Area,Car Parking','nearby_schools'=>'DAV Derabassi (1 km)','nearby_hospitals'=>'Govt. Hospital (2.5 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>false,'description'=>'Ambika Greens is an affordable gated society in Derabassi offering 2 & 3 BHK apartments surrounded by lush green spaces and basic amenities.'],
            ],
            'info@signaturerealtor-dbs.in' => [
                ['title'=>'Signature Heights Derabassi','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Derabassi, Punjab','city'=>'Derabassi','state'=>'Punjab','total_units'=>160,'available_units'=>9,'price_from'=>2400000,'price_to'=>4800000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR1900','total_towers'=>6,'floors_per_tower'=>'7','latitude'=>30.5875,'longitude'=>76.8392,'amenities'=>'Security,Power Backup,Lift,Kids Play Area,Car Parking,CCTV','nearby_schools'=>'Public School (0.5 km)','nearby_hospitals'=>'Clinic (1 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>false,'description'=>'Signature Heights Derabassi offers quality 2 & 3 BHK apartments at honest prices in a secure gated complex near the Derabassi bypass.'],
                ['title'=>'Signature Greens Phase 2 Derabassi','project_type'=>'Residential','status'=>'Under Construction','address'=>'Derabassi Extension, Punjab','city'=>'Derabassi','state'=>'Punjab','total_units'=>200,'available_units'=>130,'price_from'=>2800000,'price_to'=>5500000,'possession_date'=>'2026-03-31','rera_id'=>'PBRERA-SAS79-PR1902','total_towers'=>8,'floors_per_tower'=>'8','latitude'=>30.5868,'longitude'=>76.8398,'amenities'=>'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Lift,Kids Zone,Jogging Track','nearby_schools'=>'Nearby schools (1 km)','nearby_hospitals'=>'Clinic (2 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>true,'description'=>'Signature Greens Phase 2 is an expanded residential project in Derabassi Extension with modern amenities and smart home features.'],
            ],
            'tricity@dlfindia.com' => [
                ['title'=>'DLF Hyde Park Mullanpur','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector Mullanpur, New Chandigarh','city'=>'Mullanpur','state'=>'Punjab','total_units'=>1800,'available_units'=>45,'price_from'=>8000000,'price_to'=>25000000,'possession_date'=>'2022-12-31','rera_id'=>'PBRERA-SAS79-PR0120','total_towers'=>36,'floors_per_tower'=>'19','latitude'=>30.7720,'longitude'=>76.7180,'amenities'=>'Golf Course,Spa,Infinity Pool,Sky Gym,Concierge,EV Charging,Tennis Academy,Kids World,24x7 Security,Clubhouse,Jogging Track','nearby_schools'=>'DPS (3 km)','nearby_hospitals'=>'Max Hospital (6 km)','metro_distance'=>'6 km from ISBT Chandigarh','is_featured'=>true,'description'=>'DLF Hyde Park in Mullanpur (New Chandigarh) is the most prestigious luxury residential development near Chandigarh. Set across 250 acres, it offers 3, 4 & 5 BHK premium residences with world-class lifestyle amenities.'],
                ['title'=>'DLF Mullanpur Plots','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'DLF New Chandigarh, Mullanpur','city'=>'Mullanpur','state'=>'Punjab','total_units'=>600,'available_units'=>30,'price_from'=>7500000,'price_to'=>35000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR0121','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7715,'longitude'=>76.7175,'amenities'=>'Gated Community,Park,24x7 Security,Club Membership,Jogging Track','nearby_schools'=>'DPS (3 km)','nearby_hospitals'=>'Max Hospital (6 km)','metro_distance'=>'6 km from ISBT Chandigarh','is_featured'=>true,'description'=>'DLF New Chandigarh Plots offer premium self-build opportunities in Mullanpur\'s most sought-after plotted township with DLF brand trust and Chandigarh proximity.'],
            ],
            'chandigarh@omaxe.com' => [
                ['title'=>'Omaxe New Chandigarh Extension','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'New Chandigarh, Mullanpur, Punjab','city'=>'Mullanpur','state'=>'Punjab','total_units'=>2200,'available_units'=>80,'price_from'=>3500000,'price_to'=>12000000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR0180','total_towers'=>45,'floors_per_tower'=>'14','latitude'=>30.7710,'longitude'=>76.7190,'amenities'=>'Commercial Mall,Hotel,Multiplex,Swimming Pool,Gymnasium,24x7 Security,Kids Play Area,Park','nearby_schools'=>'Chandigarh International School (2 km)','nearby_hospitals'=>'Civil Hospital (5 km)','metro_distance'=>'7 km from ISBT Chandigarh','is_featured'=>true,'description'=>'Omaxe New Chandigarh Extension is a mixed-use township in Mullanpur with residential, commercial and entertainment zones. Offers 2, 3 & 4 BHK apartments with Chandigarh proximity.'],
                ['title'=>'Omaxe Heritage Mullanpur','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Heritage City, Mullanpur, Punjab','city'=>'Mullanpur','state'=>'Punjab','total_units'=>480,'available_units'=>22,'price_from'=>6000000,'price_to'=>16000000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS79-PR0181','total_towers'=>12,'floors_per_tower'=>'16','latitude'=>30.7718,'longitude'=>76.7200,'amenities'=>'Infinity Pool,Spa,Sky Lounge,Yoga Deck,Gymnasium,24x7 Security,EV Charging,Concierge','nearby_schools'=>'Chandigarh Group of Colleges (3 km)','nearby_hospitals'=>'PGI Chandigarh (8 km)','metro_distance'=>'6 km from ISBT Chandigarh','is_featured'=>true,'description'=>'Omaxe Heritage Mullanpur is a premium high-rise development offering spacious 3 & 4 BHK luxury apartments with Shivalik hill views in the heart of New Chandigarh.'],
            ],
            'info@bestechgroup.com' => [
                ['title'=>'Bestech Park View City Mullanpur','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Park View City, Mullanpur, Punjab','city'=>'Mullanpur','state'=>'Punjab','total_units'=>1200,'available_units'=>35,'price_from'=>5500000,'price_to'=>15000000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS79-PR0240','total_towers'=>24,'floors_per_tower'=>'17','latitude'=>30.7715,'longitude'=>76.7168,'amenities'=>'5-Star Clubhouse,Olympic Pool,Gymnasium,Tennis Court,Squash Court,24x7 Security,Spa,EV Charging,Kids World,Jogging Track','nearby_schools'=>'DPS (4 km)','nearby_hospitals'=>'Fortis (7 km)','metro_distance'=>'6 km from ISBT Chandigarh','is_featured'=>true,'description'=>'Bestech Park View City in Mullanpur is an ultra-luxury township with 5-star amenities and park-facing residences. One of Chandigarh\'s most prestigious addresses for discerning home buyers.'],
                ['title'=>'Bestech Woodsville Mullanpur','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Woodsville, New Chandigarh, Mullanpur','city'=>'Mullanpur','state'=>'Punjab','total_units'=>220,'available_units'=>12,'price_from'=>12000000,'price_to'=>32000000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS79-PR0241','total_towers'=>null,'floors_per_tower'=>'3','latitude'=>30.7722,'longitude'=>76.7162,'amenities'=>'Private Garden,Swimming Pool,Gymnasium,24x7 Security,Smart Home,EV Charging,Golf Cart Service','nearby_schools'=>'DPS (3 km)','nearby_hospitals'=>'Max Hospital (7 km)','metro_distance'=>'5 km from ISBT Chandigarh','is_featured'=>true,'description'=>'Bestech Woodsville is an exclusive villa community in Mullanpur offering 4 & 5 BHK ultra-luxury villas surrounded by a manicured forest with private gardens and resort-style living.'],
            ],
            'info@pearlsinfrastructure.com' => [
                ['title'=>'Pearl City Mullanpur','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'Pearl City, Mullanpur, Punjab','city'=>'Mullanpur','state'=>'Punjab','total_units'=>3200,'available_units'=>120,'price_from'=>3200000,'price_to'=>11000000,'possession_date'=>'2020-09-30','rera_id'=>'PBRERA-SAS79-PR0260','total_towers'=>64,'floors_per_tower'=>'12','latitude'=>30.7705,'longitude'=>76.7195,'amenities'=>'Multi-Club,Swimming Pool,Gymnasium,Shopping Centre,24x7 Security,Kids World,Sports Complex,Jogging Track','nearby_schools'=>'Pearl School (on-site)','nearby_hospitals'=>'Pearl Clinic (on-site)','metro_distance'=>'7 km from ISBT Chandigarh','is_featured'=>true,'description'=>'Pearl City Mullanpur is a 200+ acre integrated township by Pearl Infrastructure. Self-sustaining with on-site school, clinic and commercial zones, offering affordable to premium apartments.'],
                ['title'=>'Pearl Floors Kharar','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Kharar–Chandigarh Road, Kharar','city'=>'Kharar','state'=>'Punjab','total_units'=>180,'available_units'=>9,'price_from'=>2800000,'price_to'=>5500000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS79-PR0261','total_towers'=>null,'floors_per_tower'=>'4','latitude'=>30.7450,'longitude'=>76.6440,'amenities'=>'Security,Power Backup,Parking,CCTV,Lift','nearby_schools'=>'Nearby school (0.5 km)','nearby_hospitals'=>'PHC (1 km)','metro_distance'=>'4 km from Kharar','is_featured'=>false,'description'=>'Pearl Floors Kharar offers independent floor options for buyers seeking self-contained living in the Kharar township with good connectivity to Chandigarh and Mohali.'],
            ],
            'info@apsgroup-mullanpur.in' => [
                ['title'=>'APS City of Dreams Mullanpur','project_type'=>'Residential','status'=>'Under Construction','address'=>'New Chandigarh Road, Mullanpur','city'=>'Mullanpur','state'=>'Punjab','total_units'=>560,'available_units'=>320,'price_from'=>4200000,'price_to'=>9500000,'possession_date'=>'2026-06-30','rera_id'=>'PBRERA-SAS79-PR1300','total_towers'=>14,'floors_per_tower'=>'14','latitude'=>30.7725,'longitude'=>76.7210,'amenities'=>'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,EV Charging','nearby_schools'=>'Chandigarh Group of Colleges (3 km)','nearby_hospitals'=>'Civil Hospital (5 km)','metro_distance'=>'7 km from ISBT Chandigarh','is_featured'=>true,'description'=>'APS City of Dreams in Mullanpur is a mid-budget residential project offering 2, 3 & 4 BHK apartments in the New Chandigarh growth corridor at accessible price points.'],
            ],
            'info@sunnyenclave-kharar.com' => [
                ['title'=>'Sunny Enclave Phase 1 Kharar','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Sunny Enclave, Kharar, Punjab','city'=>'Kharar','state'=>'Punjab','total_units'=>5000,'available_units'=>120,'price_from'=>1800000,'price_to'=>12000000,'possession_date'=>'2000-06-30','rera_id'=>'PBRERA-SAS79-PR0090','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7560,'longitude'=>76.6280,'amenities'=>'Gated Society,24x7 Security,Park,Community Hall,Water Supply,Electricity','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Govt. Hospital Kharar (2 km)','metro_distance'=>'5 km from Kharar','is_featured'=>true,'description'=>'Sunny Enclave Phase 1 is Punjab\'s most famous self-developed colony in Kharar. Over 5,000 residential plots in a fully serviced colony with Chandigarh University nearby.'],
                ['title'=>'Sunny Enclave Phase 2 Kharar','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Sunny Enclave Extension, Kharar, Punjab','city'=>'Kharar','state'=>'Punjab','total_units'=>3000,'available_units'=>80,'price_from'=>2200000,'price_to'=>15000000,'possession_date'=>'2010-03-31','rera_id'=>'PBRERA-SAS79-PR0091','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7570,'longitude'=>76.6260,'amenities'=>'Gated Society,Security,Park,Roads,Water Supply,Street Lights','nearby_schools'=>'Chandigarh University (2 km)','nearby_hospitals'=>'Govt. Hospital Kharar (2.5 km)','metro_distance'=>'4 km from Kharar','is_featured'=>true,'description'=>'Sunny Enclave Phase 2 extends the famous Kharar colony with further residential plot options in a larger layout. Premium plots near Chandigarh University with established infrastructure.'],
            ],
            'info@osbgroup-kharar.in' => [
                ['title'=>'OSB Golf Heights Kharar','project_type'=>'Residential','status'=>'Ready to Move','address'=>'OSB Golf Heights, Kharar, Punjab','city'=>'Kharar','state'=>'Punjab','total_units'=>360,'available_units'=>18,'price_from'=>3200000,'price_to'=>7500000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR0850','total_towers'=>12,'floors_per_tower'=>'10','latitude'=>30.7445,'longitude'=>76.6435,'amenities'=>'Golf View,Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Badminton Court','nearby_schools'=>'Chandigarh University (4 km)','nearby_hospitals'=>'Max Hospital (8 km)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'OSB Golf Heights in Kharar offers premium 2 & 3 BHK apartments with stunning golf course views. European-inspired architecture with resort-style amenities at competitive Kharar prices.'],
                ['title'=>'OSB Sherwood Kharar','project_type'=>'Residential','status'=>'Ready to Move','address'=>'OSB Sherwood, Kharar–Chandigarh Highway','city'=>'Kharar','state'=>'Punjab','total_units'=>240,'available_units'=>12,'price_from'=>2800000,'price_to'=>6500000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS79-PR0851','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.7452,'longitude'=>76.6428,'amenities'=>'Gymnasium,24x7 Security,Power Backup,Kids Play Area,CCTV,Lift,Car Parking','nearby_schools'=>'DAV (1 km)','nearby_hospitals'=>'Govt. Hospital (3 km)','metro_distance'=>'3 km from Kharar','is_featured'=>false,'description'=>'OSB Sherwood is a quality residential project in Kharar offering 2 & 3 BHK apartments with forest-themed common areas and clean, modern architecture.'],
            ],
            'info@chddevelopers-kharar.com' => [
                ['title'=>'CHD One Avenue Kharar','project_type'=>'Residential','status'=>'Ready to Move','address'=>'CHD One Avenue, Kharar, Punjab','city'=>'Kharar','state'=>'Punjab','total_units'=>420,'available_units'=>20,'price_from'=>3500000,'price_to'=>8000000,'possession_date'=>'2022-09-30','rera_id'=>'PBRERA-SAS79-PR0910','total_towers'=>14,'floors_per_tower'=>'12','latitude'=>30.7460,'longitude'=>76.6422,'amenities'=>'Swimming Pool,Gymnasium,Yoga Deck,24x7 Security,Power Backup,Kids Play Area,Jogging Track,EV Charging','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Govt. Hospital Kharar (3 km)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'CHD One Avenue in Kharar is a premium residential complex with smart home features and sustainable design. Ideal for Chandigarh University faculty, IT professionals and discerning investors.'],
                ['title'=>'CHD Vann Kharar','project_type'=>'Residential','status'=>'Under Construction','address'=>'CHD Vann, Kharar–Chandigarh Road','city'=>'Kharar','state'=>'Punjab','total_units'=>320,'available_units'=>200,'price_from'=>4500000,'price_to'=>10000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS79-PR0912','total_towers'=>10,'floors_per_tower'=>'14','latitude'=>30.7455,'longitude'=>76.6418,'amenities'=>'Forest Theme,Organic Garden,Sky Deck,Gymnasium,Swimming Pool,24x7 Security,EV Charging,Yoga Studio','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Civil Hospital (5 km)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'CHD Vann is a forest-inspired luxury residential concept in Kharar with an organic vegetable garden, sky deck and sustainable construction methodology.'],
            ],
            'punjab@vatikagroup.com' => [
                ['title'=>'Vatika India Next Township Kharar','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'Vatika India Next, Kharar, Punjab','city'=>'Kharar','state'=>'Punjab','total_units'=>4500,'available_units'=>180,'price_from'=>2500000,'price_to'=>18000000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR0760','total_towers'=>90,'floors_per_tower'=>'14','latitude'=>30.7462,'longitude'=>76.6410,'amenities'=>'Technology Park,School,Hospital,Shopping Mall,Hotel,Swimming Pool,24x7 Security,Sports Complex,EV Charging','nearby_schools'=>'Vatika World School (on-site)','nearby_hospitals'=>'Vatika Clinic (on-site)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'Vatika India Next in Kharar is a 250-acre integrated township — the first of its kind in Punjab — with a technology park, school, hospital and world-class residential zones. A true city within a city.'],
            ],
            'info@countrywidepromoters.com' => [
                ['title'=>'Countrywide City Landran','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Landran Road, Mohali, Punjab','city'=>'Landran','state'=>'Punjab','total_units'=>320,'available_units'=>22,'price_from'=>2200000,'price_to'=>5000000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS79-PR1100','total_towers'=>10,'floors_per_tower'=>'8','latitude'=>30.7640,'longitude'=>76.7055,'amenities'=>'Security,Power Backup,Lift,Kids Play Area,Car Parking,CCTV','nearby_schools'=>'Chandigarh University (2 km)','nearby_hospitals'=>'PHC (3 km)','metro_distance'=>'6 km from Mohali','is_featured'=>false,'description'=>'Countrywide City Landran is an affordable residential project near Chandigarh University offering 2 & 3 BHK apartments popular with university staff and students.'],
                ['title'=>'Countrywide Residency Banur','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Banur Town, SAS Nagar, Punjab','city'=>'Banur','state'=>'Punjab','total_units'=>180,'available_units'=>12,'price_from'=>1800000,'price_to'=>3800000,'possession_date'=>'2019-06-30','rera_id'=>'PBRERA-SAS79-PR1101','total_towers'=>6,'floors_per_tower'=>'6','latitude'=>30.5820,'longitude'=>76.7090,'amenities'=>'Security,Power Backup,Parking,CCTV,Basic Amenities','nearby_schools'=>'Government School (0.5 km)','nearby_hospitals'=>'PHC Banur (1 km)','metro_distance'=>'10 km from Zirakpur','is_featured'=>false,'description'=>'Countrywide Residency Banur offers affordable 2 BHK apartments in the emerging Banur township, ideal for budget buyers seeking good connectivity to Zirakpur and Patiala.'],
            ],
            'kharar@imperiastructures.com' => [
                ['title'=>'Imperia Esfera Kharar','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Imperia Esfera, Kharar, Punjab','city'=>'Kharar','state'=>'Punjab','total_units'=>480,'available_units'=>25,'price_from'=>3000000,'price_to'=>6800000,'possession_date'=>'2021-12-31','rera_id'=>'PBRERA-SAS79-PR0970','total_towers'=>16,'floors_per_tower'=>'10','latitude'=>30.7442,'longitude'=>76.6448,'amenities'=>'Rooftop Sky Villa,Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,EV Charging','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Govt. Hospital Kharar (3 km)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'Imperia Esfera is Kharar\'s most stylish residential development featuring sky-villa-style top-floor apartments with private terraces and panoramic views of the Chandigarh periphery.'],
            ],
            'kharar@gbpgroup.com' => [
                ['title'=>'GBP Centra Kharar','project_type'=>'Residential','status'=>'Ready to Move','address'=>'GBP Centra, Kharar–Chandigarh Road','city'=>'Kharar','state'=>'Punjab','total_units'=>360,'available_units'=>18,'price_from'=>2800000,'price_to'=>6200000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR0680','total_towers'=>12,'floors_per_tower'=>'9','latitude'=>30.7455,'longitude'=>76.6425,'amenities'=>'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,CCTV','nearby_schools'=>'DAV (1 km)','nearby_hospitals'=>'Govt. Hospital (3 km)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'GBP Centra Kharar extends GBP Group\'s successful Zirakpur portfolio to the Kharar market. Premium 2 & 3 BHK apartments with investor-friendly pricing and strong resale value.'],
                ['title'=>'GBP Rosewood Kharar','project_type'=>'Residential','status'=>'Under Construction','address'=>'GBP Rosewood, Kharar Township','city'=>'Kharar','state'=>'Punjab','total_units'=>280,'available_units'=>190,'price_from'=>3200000,'price_to'=>7200000,'possession_date'=>'2026-09-30','rera_id'=>'PBRERA-SAS79-PR0681','total_towers'=>10,'floors_per_tower'=>'11','latitude'=>30.7462,'longitude'=>76.6415,'amenities'=>'Swimming Pool,Gymnasium,Yoga Deck,24x7 Security,EV Charging,Kids Play Area,Clubhouse','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Govt. Hospital (3 km)','metro_distance'=>'3 km from Kharar','is_featured'=>true,'description'=>'GBP Rosewood Kharar is the latest offering from GBP Group — a nature-themed residential complex with rose gardens, landscaped walks and modern sky apartments.'],
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
                $this->command->line("    [skip builder] {$b['company_name']} — already exists");
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
                if (DB::table('builder_projects')
                    ->where('builder_id', $builderId)
                    ->where('title', $project['title'])
                    ->exists()) {
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
                    'views_count'      => rand(100, 2000),
                    'leads_count'      => rand(10, 100),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
                $this->command->line("      ✓ Project: {$project['title']}");
            }
        }
        $this->command->info('    ✔ 15 builders + projects seeded.');
    }

    // =========================================================================
    // PROPERTIES  (240 records, ordered nearest → farthest, 6–25 km)
    // =========================================================================
    private function seedProperties(): void
    {
        $this->command->info('  → Seeding 240 extended proximity properties (6–25 km)...');

        $dealerIds = DB::table('property_dealers')->pluck('id')->toArray();
        if (empty($dealerIds)) {
            $this->command->error('No dealers found!');
            return;
        }

        $amenityPool = [
            'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Clubhouse',
            'Park,Kids Play Area,Security,Power Backup,Car Parking,CCTV',
            'Gymnasium,24x7 Security,Power Backup,Lift,Intercom,CCTV',
            'Clubhouse,Swimming Pool,Kids Play Area,Jogging Track,Power Backup,Security',
            'Car Parking,CCTV,Security,Power Backup,Intercom,Visitor Parking',
            'Terrace Garden,Gymnasium,Swimming Pool,Yoga Deck,Spa,24x7 Security,EV Charging',
            'Community Hall,Kids Play Area,Power Backup,Security,Water Supply,Lift',
            'Sports Facility,Indoor Games,Gymnasium,Swimming Pool,24x7 Security,CCTV',
            'Golf View,Sky Lounge,Concierge,EV Charging,Infinity Pool,Tennis Court,Spa',
            'Forest Theme,Organic Garden,Yoga Studio,Meditation Zone,24x7 Security,EV Charging',
        ];
        $furnishings = ['Furnished','Semi-Furnished','Unfurnished','Semi-Furnished','Unfurnished'];
        $facings     = ['North','South','East','West','North-East','North-West','South-East'];
        $propAges    = ['Under Construction','0-1 Year','1-3 Years','3-5 Years','5-10 Years'];

        $pick = fn($arr) => $arr[array_rand($arr)];

        /**
         * Proximity zones — ordered nearest to farthest from Srishti Avenue, Dhakoli
         * All distances calculated from lat 30.6400, lng 76.8190
         *
         * Ring 7 : 6–8 km
         * Ring 8 : 8–15 km
         * Ring 9 : 15–25 km
         */
        $zones = [

            // ── RING 7 : 6–8 km ─────────────────────────────────────────────

            [
                'label'     => 'Derabassi (6.2 km south)',
                'dist'      => 6.2,
                'lat'       => 30.5869,
                'lng'       => 76.8379,
                'jitter'    => 0.0030,
                'state'     => 'Punjab',
                'city'      => 'Derabassi',
                'pincode'   => '140507',
                'locality'  => 'Derabassi',
                'societies' => ['Ansal Sushant City Derabassi','Ambika Florence Park','Signature Heights Derabassi','SBP Housing Park Derabassi','Derabassi Green Enclave'],
                'landmark'  => 'Near Derabassi Bus Stand',
                'count'     => 30,
            ],
            [
                'label'     => 'VIP Road Chandigarh Border (6.3 km north)',
                'dist'      => 6.3,
                'lat'       => 30.6950,
                'lng'       => 76.8350,
                'jitter'    => 0.0025,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'VIP Road North',
                'societies' => ['Sun City Residency','VIP Road North Apartments','CHB Border Heights','Mani Majra Adjacent Society','Punjab-UT Border Heights'],
                'landmark'  => 'Near VIP Road Chandigarh Entry',
                'count'     => 25,
            ],
            [
                'label'     => 'NH-7 Far East Ambala Road (6.9 km east)',
                'dist'      => 6.9,
                'lat'       => 30.6640,
                'lng'       => 76.8850,
                'jitter'    => 0.0025,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140604',
                'locality'  => 'Ambala Highway East',
                'societies' => ['Motia Blue Flag Highway','GBP Athens Highway East','Sunrise Valley NH7','Green Enclave Highway','NH7 Residency Park'],
                'landmark'  => 'Near NH-7 Zirakpur-Ambala Road',
                'count'     => 15,
            ],
            [
                'label'     => 'Panchkula Sector 5-7 (7.0 km north-east)',
                'dist'      => 7.0,
                'lat'       => 30.6985,
                'lng'       => 76.8478,
                'jitter'    => 0.0025,
                'state'     => 'Haryana',
                'city'      => 'Panchkula',
                'pincode'   => '134109',
                'locality'  => 'Sector 5',
                'societies' => ['HUDA Sector 5 Plots','Panchkula Sector 7 Apartments','Mansa Devi Complex','Sector 6 Independent Houses','Panchkula Sector 5 Housing'],
                'landmark'  => 'Near Panchkula Sector 5 Market',
                'count'     => 20,
            ],
            [
                'label'     => 'Chandigarh Industrial Area Phase 1 (7.0 km north)',
                'dist'      => 7.0,
                'lat'       => 30.7000,
                'lng'       => 76.8098,
                'jitter'    => 0.0020,
                'state'     => 'Chandigarh',
                'city'      => 'Chandigarh',
                'pincode'   => '160002',
                'locality'  => 'Industrial Area Phase 1',
                'societies' => ['Industrial Area Phase 1 Flats','PUDA Colony Chandigarh','Sector 18-B Apartments','Industrial Workers Housing','PESCO Colony Housing'],
                'landmark'  => 'Near Chandigarh Industrial Area Phase 1',
                'count'     => 20,
            ],

            // ── RING 8 : 8–15 km ─────────────────────────────────────────────

            [
                'label'     => 'Chandigarh Sector 34-35 (10.4 km north-west)',
                'dist'      => 10.4,
                'lat'       => 30.7280,
                'lng'       => 76.7810,
                'jitter'    => 0.0030,
                'state'     => 'Chandigarh',
                'city'      => 'Chandigarh',
                'pincode'   => '160022',
                'locality'  => 'Sector 34',
                'societies' => ['Sector 34-A Apartments','Chandigarh Sector 35-B Flats','Mid City Township Sector 34','CHB 2-Room DU Sector 35','PUDA Sector 34 Residency'],
                'landmark'  => 'Near Sector 34 Chandigarh',
                'count'     => 20,
            ],
            [
                'label'     => 'Mohali Phase 7-8 (11.6 km west)',
                'dist'      => 11.6,
                'lat'       => 30.7060,
                'lng'       => 76.7250,
                'jitter'    => 0.0030,
                'state'     => 'Punjab',
                'city'      => 'Mohali',
                'pincode'   => '160055',
                'locality'  => 'Phase 7',
                'societies' => ['Phase 7 Mohali Apartments','PUDA Plots Phase 8','Gulmohar Residency Phase 7','Phase 8 Green Heights','Mohali Phase 7 Builder Floors'],
                'landmark'  => 'Near Phase 7 Mohali',
                'count'     => 20,
            ],
            [
                'label'     => 'Chandigarh Sector 17-22 (11.7 km north-west)',
                'dist'      => 11.7,
                'lat'       => 30.7420,
                'lng'       => 76.7878,
                'jitter'    => 0.0030,
                'state'     => 'Chandigarh',
                'city'      => 'Chandigarh',
                'pincode'   => '160017',
                'locality'  => 'Sector 22',
                'societies' => ['Sector 22-B Society','CHB Sector 21 Apartments','Sector 19-B PUDA Colony','Sector 22 Independent Houses','Sector 17 Commercial Residency'],
                'landmark'  => 'Near Sector 22 Chandigarh',
                'count'     => 15,
            ],
            [
                'label'     => 'Banur (12.5 km south-west)',
                'dist'      => 12.5,
                'lat'       => 30.5820,
                'lng'       => 76.7080,
                'jitter'    => 0.0030,
                'state'     => 'Punjab',
                'city'      => 'Banur',
                'pincode'   => '140601',
                'locality'  => 'Banur',
                'societies' => ['Countrywide Residency Banur','Green Fields Banur','Banur Garden Enclave','New Horizon Colony Banur','Shivalik Homes Banur'],
                'landmark'  => 'Near Banur Town Chowk',
                'count'     => 15,
            ],

            // ── RING 9 : 15–25 km ────────────────────────────────────────────

            [
                'label'     => 'Mullanpur New Chandigarh (17.6 km north-west)',
                'dist'      => 17.6,
                'lat'       => 30.7720,
                'lng'       => 76.7180,
                'jitter'    => 0.0035,
                'state'     => 'Punjab',
                'city'      => 'Mullanpur',
                'pincode'   => '140901',
                'locality'  => 'New Chandigarh',
                'societies' => ['DLF Hyde Park Mullanpur','Bestech Park View City','Omaxe Heritage Mullanpur','Pearl City Mullanpur','APS City of Dreams'],
                'landmark'  => 'Near New Chandigarh Mullanpur',
                'count'     => 20,
            ],
            [
                'label'     => 'Landran Mohali (17.6 km north)',
                'dist'      => 17.6,
                'lat'       => 30.7640,
                'lng'       => 76.7044,
                'jitter'    => 0.0030,
                'state'     => 'Punjab',
                'city'      => 'Landran',
                'pincode'   => '140307',
                'locality'  => 'Landran',
                'societies' => ['Countrywide City Landran','CU Faculty Housing Landran','Landran Valley Homes','Green Park Landran','Mohali Landran Residency'],
                'landmark'  => 'Near Chandigarh University Landran',
                'count'     => 15,
            ],
            [
                'label'     => 'Kharar (20.5 km north-west)',
                'dist'      => 20.5,
                'lat'       => 30.7450,
                'lng'       => 76.6430,
                'jitter'    => 0.0035,
                'state'     => 'Punjab',
                'city'      => 'Kharar',
                'pincode'   => '140301',
                'locality'  => 'Kharar',
                'societies' => ['OSB Golf Heights Kharar','CHD One Avenue Kharar','Vatika India Next','Imperia Esfera Kharar','GBP Centra Kharar'],
                'landmark'  => 'Near Kharar Town Centre',
                'count'     => 15,
            ],
            [
                'label'     => 'Kharar Sunny Enclave (22.5 km north-west)',
                'dist'      => 22.5,
                'lat'       => 30.7555,
                'lng'       => 76.6270,
                'jitter'    => 0.0040,
                'state'     => 'Punjab',
                'city'      => 'Kharar',
                'pincode'   => '140301',
                'locality'  => 'Sunny Enclave',
                'societies' => ['Sunny Enclave Phase 1','Sunny Enclave Phase 2','Sunny Heights Kharar','Pacific Blue Kharar','Kharar Green Villas'],
                'landmark'  => 'Near Sunny Enclave Colony, Kharar',
                'count'     => 20,
            ],
        ];

        $propTypes      = ['Apartment','Builder Floor','Independent Floor','Villa','Plot','Penthouse','Studio Apartment','Shop','Office Space'];
        $lookingForPool = ['Sale','Sale','Sale','Rent','Sale'];

        $totalInserted = 0;

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

                $title = $this->makeTitle($ptype, $bedrooms, $bhkType, $society, $zone['locality'], $zone['city'], $totalInserted + $i + 1);

                $baseSlug = Str::slug($title . '-' . $zone['city'] . '-' . ($totalInserted + $i + 1));
                $slug = $baseSlug;
                $sc = 1;
                while (DB::table('properties')->where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $sc++;
                }

                $lat = round($zone['lat'] + (rand(-100, 100) / 100000 * ($zone['jitter'] / 0.001)), 6);
                $lng = round($zone['lng'] + (rand(-100, 100) / 100000 * ($zone['jitter'] / 0.001)), 6);

                $totalFloors  = in_array($ptype, ['Villa','Plot']) ? null : rand(4, 22);
                $floorNumber  = $totalFloors ? rand(1, $totalFloors) : null;
                $ppsqft       = $area > 0 ? round($price / $area, 2) : null;
                $possession   = in_array($propAge, ['Under Construction']) ? 'Under Construction' : 'Ready to Move';
                $rent         = ($lfor === 'Rent') ? $this->getRent($ptype, $bedrooms, $zone['city']) : null;
                $status       = ($lfor === 'Rent') ? 'Available' : $pick(['Available','Available','Available','Sold']);

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
                    'maintenance_charges' => ($ptype !== 'Plot') ? rand(500, 5000) : null,
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
                    'views_count'         => rand(10, 3000),
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
    // HELPERS (duplicated from ZirakpurProximitySeeder for standalone use)
    // =========================================================================

    private function getConfig(string $ptype, string $city, string $lfor): array
    {
        $multiplier = match($city) {
            'Chandigarh' => 1.5,
            'Mohali'     => 1.2,
            'Mullanpur'  => 1.3,
            'Panchkula'  => 1.1,
            'Kharar'     => 0.9,
            'Landran'    => 0.85,
            'Banur'      => 0.75,
            'Derabassi'  => 0.80,
            default      => 1.0,
        };

        return match($ptype) {
            'Apartment','Studio Apartment' => (function() use ($ptype, $multiplier, $lfor) {
                $beds = $ptype === 'Studio Apartment' ? 1 : rand(1, 4);
                $area = match($beds) { 1 => rand(450, 700), 2 => rand(900, 1300), 3 => rand(1350, 1800), default => rand(2000, 3200) };
                $price = $lfor === 'Rent'
                    ? 0
                    : (int)round($area * rand(3500, 6500) * $multiplier / 10000) * 10000;
                return [$beds, max(1, $beds - 1), max(1, $beds - 1), $area, $price, $beds . ' BHK'];
            })(),
            'Builder Floor','Independent Floor' => (function() use ($multiplier, $lfor) {
                $beds = rand(2, 4);
                $area = rand(900, 2200);
                $price = $lfor === 'Rent' ? 0 : (int)round($area * rand(3000, 5500) * $multiplier / 10000) * 10000;
                return [$beds, $beds - 1, 1, $area, $price, $beds . ' BHK'];
            })(),
            'Villa' => (function() use ($multiplier, $lfor) {
                $beds = rand(3, 5);
                $area = rand(2500, 5000);
                $price = $lfor === 'Rent' ? 0 : (int)round($area * rand(4500, 8000) * $multiplier / 10000) * 10000;
                return [$beds, $beds, 2, $area, $price, $beds . ' BHK'];
            })(),
            'Penthouse' => (function() use ($multiplier) {
                $beds = rand(3, 5);
                $area = rand(3000, 6000);
                $price = (int)round($area * rand(6000, 12000) * $multiplier / 10000) * 10000;
                return [$beds, $beds, 3, $area, $price, $beds . ' BHK'];
            })(),
            'Plot' => (function() use ($multiplier) {
                $area = rand(100, 500) * 9;
                $price = (int)round($area * rand(2000, 5000) * $multiplier / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            'Shop' => (function() use ($multiplier) {
                $area = rand(150, 800);
                $price = (int)round($area * rand(5000, 15000) * $multiplier / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            'Office Space' => (function() use ($multiplier) {
                $area = rand(400, 2000);
                $price = (int)round($area * rand(4000, 10000) * $multiplier / 10000) * 10000;
                return [null, null, null, $area, $price, null];
            })(),
            default => [2, 2, 1, 1100, 4500000, '2 BHK'],
        };
    }

    private function getRent(string $ptype, ?int $beds, string $city): int
    {
        $base = match($city) {
            'Chandigarh' => 18000,
            'Mullanpur'  => 16000,
            'Mohali'     => 14000,
            'Panchkula'  => 12000,
            'Kharar'     => 9000,
            'Landran'    => 8000,
            'Banur'      => 7000,
            'Derabassi'  => 8000,
            default      => 10000,
        };
        return match($ptype) {
            'Villa'        => $base * 3 + rand(0, 10000),
            'Penthouse'    => $base * 4 + rand(0, 15000),
            'Shop'         => $base + rand(0, 8000),
            'Office Space' => $base + rand(5000, 20000),
            'Plot'         => 0,
            default        => $base * ($beds ?? 1) + rand(0, 5000),
        };
    }

    private function makeTitle(string $ptype, ?int $beds, ?string $bhk, string $society, string $locality, string $city, int $idx): string
    {
        $prefixes = ['Spacious','Modern','Well-Ventilated','Prime','Elegant','Luxurious','Cosy','Bright','Premium','Ready-to-Move'];
        $prefix   = $prefixes[$idx % count($prefixes)];

        if ($ptype === 'Plot') {
            return "{$prefix} Plot in {$society}, {$locality}, {$city}";
        }
        if (in_array($ptype, ['Shop','Office Space'])) {
            return "{$prefix} {$ptype} in {$society}, {$locality}, {$city}";
        }
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
