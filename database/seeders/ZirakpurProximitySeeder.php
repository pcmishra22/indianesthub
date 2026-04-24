<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * ZirakpurProximitySeeder
 *
 * Seeds dealers, builders and properties ordered by distance from:
 *   Srishti Avenue, Dhakoli, Zirakpur, Punjab 160104
 *   Reference point: lat 30.6400, lng 76.8190
 *
 * Ring 1 : 0–0.5 km  (Srishti Avenue / Dhakoli immediate)
 * Ring 2 : 0.5–1 km  (VIP Road near Dhakoli, Patiala Hwy gate)
 * Ring 3 : 1–2 km    (Gazipur Road, Lohgarh Road, Baltana)
 * Ring 4 : 2–3 km    (Peer Mushalla, Airport Road, Zirakpur centre)
 * Ring 5 : 3–5 km    (Ambala Highway, VIP Road north)
 * Ring 6 : 5+ km     (Panchkula sectors, Mohali border)
 */
class ZirakpurProximitySeeder extends Seeder
{
    /** Reference home coordinates */
    const HOME_LAT = 30.6400;
    const HOME_LNG = 76.8190;

    public function run(): void
    {
        $this->command->info('🏠 Seeding proximity data from Srishti Avenue, Dhakoli, Zirakpur...');
        $this->seedDealers();
        $this->seedBuilders();
        $this->seedProperties();
        $this->command->info('✅ ZirakpurProximitySeeder complete!');
    }

    // =========================================================================
    // DEALERS  (70 records, nearest first)
    // =========================================================================
    private function seedDealers(): void
    {
        $this->command->info('  → Seeding 70 proximity-ordered dealers...');

        $dealers = [

            // ── RING 1 : 0–0.5 km  (Srishti Avenue / Dhakoli village) ──────
            ['first_name'=>'Manpreet','last_name'=>'Dhaliwal','company_name'=>'Dhakoli Prime Realty','phone'=>'+91 98151 30001','email'=>'manpreet.dhakoli@dhakoliprimerealty.com','bio'=>'Manpreet Dhaliwal runs Dhakoli Prime Realty right on Srishti Avenue, Dhakoli. Hyperlocal expert for Dhakoli village and surrounding societies with 9 years of deal-closing experience.','specializations'=>'Residential Flats,Plots,Rental','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Harkirat','last_name'=>'Bedi','company_name'=>'Bedi Properties Dhakoli','phone'=>'+91 97800 30002','email'=>'harkirat.bedi@bedipropertiesdhakoli.com','bio'=>'Bedi Properties is the go-to dealer on Srishti Avenue Extension. Specialising in ready-to-move 2 & 3 BHK flats and builder floors within Dhakoli. 7 years local expertise.','specializations'=>'Ready to Move,2 BHK,3 BHK,Builder Floors','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Jaswinder','last_name'=>'Sandhu','company_name'=>'Sandhu Homes Dhakoli','phone'=>'+91 99143 30003','email'=>'jaswinder@sandhudhakoli.in','bio'=>'Sandhu Homes is based in Dhakoli village and offers genuine property listings within walking distance of Srishti Avenue. No hidden charges, honest dealings.','specializations'=>'Residential,Plots,Rental','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Kulwinder','last_name'=>'Rangi','company_name'=>'Rangi Real Estate Dhakoli','phone'=>'+91 95010 30004','email'=>'rangi.realestate.dhakoli@gmail.com','bio'=>'Rangi Real Estate is a family-run agency serving Dhakoli residents since 2012. Expert in affordable flats and plots inside Dhakoli and the Srishti Avenue corridor.','specializations'=>'Affordable Flats,Plots,Resale','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Balwant','last_name'=>'Chahal','company_name'=>'Chahal Properties Dhakoli','phone'=>'+91 98726 30005','email'=>'balwantchahal.properties@gmail.com','bio'=>'Chahal Properties is known for its extensive local knowledge of Dhakoli micro-market. From 1 BHK studio apartments to plots, they cover it all within the Dhakoli belt.','specializations'=>'Studio Apartments,1 BHK,Plots','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Parminder','last_name'=>'Gill','company_name'=>'Gill Estate Dhakoli','phone'=>'+91 98763 30006','email'=>'parminder.gill@gillestate.in','bio'=>'Gill Estate specialises in the Dhakoli stretch of the Ambala–Patiala corridor. Trusted by 400+ clients for transparent, hassle-free property transactions.','specializations'=>'Residential Flats,Commercial,Resale','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Navneet','last_name'=>'Khanna','company_name'=>'Khanna Property Hub Dhakoli','phone'=>'+91 98152 30007','email'=>'navneet@khannapropertyhub.com','bio'=>'Khanna Property Hub operates near the Srishti Avenue chowk. Expert in newly constructed builder floors and independent houses in Dhakoli village.','specializations'=>'Builder Floors,Independent Houses,Rental','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Rajwinder','last_name'=>'Bains','company_name'=>'Bains Realtors Dhakoli','phone'=>'+91 98725 30008','email'=>'rajwinder@bainsrealtor.com','bio'=>'Bains Realtors has 11 years of experience dealing in residential and commercial properties within a 500 m radius of Srishti Avenue, Dhakoli. Fast deal closures guaranteed.','specializations'=>'Residential,Commercial Shops,Plots','operating_cities'=>'Zirakpur'],

            // ── RING 2 : 0.5–1 km  (VIP Road Dhakoli gate, Patiala Hwy) ────
            ['first_name'=>'Sukhmeet','last_name'=>'Arora','company_name'=>'Arora Realty VIP Road Dhakoli','phone'=>'+91 98769 30009','email'=>'sukhmeet@arorarealtydhakoli.com','bio'=>'Arora Realty is perfectly located at VIP Road near Dhakoli Gate. Specialising in societies along the VIP Road–Dhakoli stretch for buyers and investors.','specializations'=>'VIP Road Properties,Investor Deals,Resale','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Amanjot','last_name'=>'Kaur','company_name'=>'Amanjot Properties VIP Road','phone'=>'+91 95927 30010','email'=>'amanjot.viproad@gmail.com','bio'=>'Amanjot Properties is one of the few women-led agencies near VIP Road Dhakoli. 8 years of expertise in residential sales, rentals and property management.','specializations'=>'Residential Sales,Rentals,Property Management','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Sukhchain','last_name'=>'Mann','company_name'=>'Mann Properties Patiala Highway','phone'=>'+91 98154 30011','email'=>'sukhchain.mann@mannproperties.in','bio'=>'Mann Properties is strategically located on Patiala Highway near Dhakoli. Expert in affordable plots, independent houses and builder floors in the highway belt.','specializations'=>'Plots,Independent Houses,Builder Floors','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Gagandeep','last_name'=>'Sodhi','company_name'=>'Sodhi Estate Consultants','phone'=>'+91 98726 30012','email'=>'gagandeep.sodhi@sodhiestate.com','bio'=>'Sodhi Estate Consultants serves the VIP Road–Patiala Highway junction area. Expert in residential flats from leading builders active in this corridor since 2010.','specializations'=>'Residential Flats,New Bookings,Commercial','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Taranjit','last_name'=>'Handa','company_name'=>'Handa Property Services','phone'=>'+91 98762 30013','email'=>'taranjit.handa@handaproperties.com','bio'=>'Handa Property Services operates near VIP Road Dhakoli chowk. Providing comprehensive buyer and seller representation with deep hyperlocal knowledge.','specializations'=>'Buyer Services,Seller Advisory,Flats','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Maninder','last_name'=>'Ghai','company_name'=>'Ghai Properties Dhakoli Chowk','phone'=>'+91 98157 30014','email'=>'maninder.ghai@ghaiproperties.in','bio'=>'Ghai Properties is the preferred dealer at Dhakoli Chowk, covering properties within 0.5–1 km ring of Srishti Avenue. 200+ happy clients.','specializations'=>'Residential,Plots,Commercial','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Lovepreet','last_name'=>'Bhatia','company_name'=>'Bhatia Homes Near Dhakoli','phone'=>'+91 97801 30015','email'=>'lovepreet.bhatia@bhatiahomes.in','bio'=>'Bhatia Homes specialises in the sub-0.8 km belt of Dhakoli—ideal for buyers wanting walkable access to Dhakoli village amenities and the VIP Road artery.','specializations'=>'Residential Flats,Affordable Homes,Rental','operating_cities'=>'Zirakpur'],

            // ── RING 3 : 1–2 km  (Gazipur Road, Lohgarh Road, Baltana) ─────
            ['first_name'=>'Surinder','last_name'=>'Rana','company_name'=>'Rana Properties Gazipur Road','phone'=>'+91 98161 30016','email'=>'surinder.rana@ranapropertiesgazipur.com','bio'=>'Rana Properties covers Gazipur Road thoroughly. Expert in societies like NK Savitry Greens 2, Mona Greens 2 and Krishna Apartments with 13 years of experience.','specializations'=>'NK Savitry,Mona Greens,Gazipur Societies','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Bikram','last_name'=>'Dhindsa','company_name'=>'Dhindsa Estate Lohgarh Road','phone'=>'+91 99149 30017','email'=>'bikram@dhindsa-estate.com','bio'=>'Dhindsa Estate is the top-rated agency on Lohgarh Road, Zirakpur. Covering Maya Garden City, Mayfair Greens and Green Fields with transparent and quick transactions.','specializations'=>'Maya Garden,Mayfair Greens,Lohgarh Societies','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Daljinder','last_name'=>'Sidhu','company_name'=>'Sidhu Property Lohgarh','phone'=>'+91 98728 30018','email'=>'daljinder.sidhu@sidhupropertylohgarh.com','bio'=>'Sidhu Property is based at Lohgarh Road junction. Covering all residential societies within 1–1.5 km of Dhakoli for over 10 years with 350+ deals closed.','specializations'=>'Residential Flats,Builder Floors,Resale','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Ramneet','last_name'=>'Hora','company_name'=>'Hora Realty Gazipur','phone'=>'+91 95010 30019','email'=>'ramneet.hora@horarealty.in','bio'=>'Hora Realty specialises in the Gazipur Road corridor, known for affordable yet quality housing. Trusted by NRI clients for transparent management-free purchases.','specializations'=>'Affordable Housing,NRI Services,Gazipur Societies','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Satpal','last_name'=>'Walia','company_name'=>'Walia Properties Baltana','phone'=>'+91 98720 30020','email'=>'satpal.walia@waliapropertiesbaltana.com','bio'=>'Walia Properties focuses on Baltana—a fast-growing micro-market 1.5 km from Dhakoli. Expert in Sarvottam Garden, Tricity Green Enclave and PD Residency listings.','specializations'=>'Baltana Properties,Sarvottam Garden,Affordable Flats','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Gurtej','last_name'=>'Phull','company_name'=>'Phull Estate Baltana','phone'=>'+91 98763 30021','email'=>'gurtej.phull@phullestate.com','bio'=>'Phull Estate serves Baltana and the Zirakpur–Patiala Highway junction area. Wide inventory of 2 BHK flats starting at ₹25 lakh for first-time buyers.','specializations'=>'First-Time Buyers,Baltana,Budget Flats','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Preetinder','last_name'=>'Nagpal','company_name'=>'Nagpal Property Point Gazipur','phone'=>'+91 98729 30022','email'=>'preetinder.nagpal@nagpalpropertypoint.com','bio'=>'Nagpal Property Point covers Gazipur Chowk to Lohgarh junction area comprehensively. Known for quick property searches and zero-brokerage policy for direct owners.','specializations'=>'Direct Owner Deals,Residential,Plots','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Harvinder','last_name'=>'Buttar','company_name'=>'Buttar Realtors Near Dhakoli','phone'=>'+91 99145 30023','email'=>'harvinder.buttar@buttarrealtor.com','bio'=>'Buttar Realtors operates in the Dhakoli–Baltana–Gazipur triangle. Expert in plot investments and resale apartments in established societies.','specializations'=>'Plot Investments,Resale,Established Societies','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Varinder','last_name'=>'Matharoo','company_name'=>'Matharoo Properties Baltana','phone'=>'+91 98151 30024','email'=>'varinder.matharoo@matharooproperties.com','bio'=>'Matharoo Properties is the most active agency in Baltana with a verified inventory of 80+ listings. First-time homebuyers are given personalised guided site tours.','specializations'=>'Baltana,Residential,Investment Plots','operating_cities'=>'Zirakpur'],
            ['first_name'=>'Jasbir','last_name'=>'Uppal','company_name'=>'Uppal Real Estate Lohgarh','phone'=>'+91 98726 30025','email'=>'jasbir.uppal@uppalrealestate.in','bio'=>'Uppal Real Estate is based at Lohgarh Road and handles premium listings in Maya Garden City and Mayfair Royale. 15 years of market know-how and 600+ deals.','specializations'=>'Maya Garden City,Mayfair Royale,Premium Listings','operating_cities'=>'Zirakpur,Mohali'],

            // ── RING 4 : 2–3 km  (Peer Mushalla, Airport Road, centre) ─────
            ['first_name'=>'Rajdeep','last_name'=>'Setia','company_name'=>'Setia Properties Peer Mushalla','phone'=>'+91 98760 30026','email'=>'rajdeep.setia@setiaproperties.com','bio'=>'Setia Properties is the leading agency at Peer Mushalla, 1.7 km from Dhakoli. Expert in Savitry Green Avenue, RM Royale Residency and Manglam Heights with 250+ sales.','specializations'=>'Peer Mushalla,Savitry Green,RM Royale','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Amrik','last_name'=>'Dhall','company_name'=>'Dhall Realty Peer Mushalla','phone'=>'+91 98765 30027','email'=>'amrik.dhall@dhallrealty.in','bio'=>'Dhall Realty covers the Peer Mushalla stretch comprehensively. Expert in both residential and SCO commercial properties along the Zirakpur–Panchkula link road.','specializations'=>'Residential,SCO Shops,Peer Mushalla','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Sarabjit','last_name'=>'Hora','company_name'=>'Hora Estate Airport Road','phone'=>'+91 95927 30028','email'=>'sarabjit.hora@horaestate.com','bio'=>'Hora Estate focuses on the Airport Road corridor. Expert in Sushma Grande NXT, Green Lotus Saksham and GBP Athens—popular with IT professionals and investors.','specializations'=>'Airport Road,Sushma Grande,Green Lotus','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Harpinder','last_name'=>'Nagra','company_name'=>'Nagra Properties Airport Road','phone'=>'+91 98729 30029','email'=>'harpinder.nagra@nagraproperties.com','bio'=>'Nagra Properties is the premium agency for Airport Road societies in Zirakpur. From studios to 4 BHK penthouses, every budget and preference is catered to.','specializations'=>'Airport Road Societies,Penthouses,Investment','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Inderpal','last_name'=>'Sobti','company_name'=>'Sobti Property Consultants','phone'=>'+91 98724 30030','email'=>'inderpal.sobti@sobticonsultants.in','bio'=>'Sobti Property Consultants is based at Zirakpur centre and serves clients within a 3 km radius efficiently. Offering end-to-end services from listing to registration.','specializations'=>'Full Service,Residential,Commercial','operating_cities'=>'Zirakpur,Panchkula,Mohali'],
            ['first_name'=>'Gurmukh','last_name'=>'Bhinder','company_name'=>'Bhinder Property Group','phone'=>'+91 97800 30031','email'=>'gurmukh.bhinder@bhinderproperty.com','bio'=>'Bhinder Property Group serves the Peer Mushalla to Airport Road belt. Known for exclusive listings and investor-oriented advisory in Zirakpur\'s hottest corridors.','specializations'=>'Exclusive Listings,Investment Advisory,Investor Deals','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Paramveer','last_name'=>'Gill','company_name'=>'Gill & Sons Properties','phone'=>'+91 98727 30032','email'=>'paramveer.gill@gillandsonsproperties.com','bio'=>'Gill & Sons Properties is a second-generation real estate firm covering Airport Road and Peer Mushalla. 20+ years of family legacy in Zirakpur real estate.','specializations'=>'Airport Road,Peer Mushalla,Family Legacy Service','operating_cities'=>'Zirakpur,Chandigarh,Panchkula'],
            ['first_name'=>'Mandeep','last_name'=>'Sethi','company_name'=>'Sethi Homes Zirakpur Centre','phone'=>'+91 98728 30033','email'=>'mandeep.sethi@sethihomes.in','bio'=>'Sethi Homes is located at Zirakpur\'s heart, 2.5 km from Dhakoli. Experts in all major builder projects active in Zirakpur with a strong investor connect.','specializations'=>'All Major Builders,Residential,Commercial','operating_cities'=>'Zirakpur,Mohali,Panchkula'],
            ['first_name'=>'Gurjinder','last_name'=>'Toor','company_name'=>'Toor Estate Airport Road','phone'=>'+91 99143 30034','email'=>'gurjinder.toor@toorestate.com','bio'=>'Toor Estate is a trusted name on Airport Road, Zirakpur. Specialising in high-rise apartments, commercial plots and co-working space properties.','specializations'=>'High-Rise Apartments,Commercial Plots,Co-Working','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Karamjit','last_name'=>'Bhullar','company_name'=>'Bhullar Properties Zirakpur','phone'=>'+91 98766 30035','email'=>'karamjit.bhullar@bhullarproperties.com','bio'=>'Bhullar Properties has been active near Zirakpur bus stand for 14 years. Expert in resale and new bookings within the 2–3 km radius of Dhakoli.','specializations'=>'Resale,New Bookings,Residential','operating_cities'=>'Zirakpur'],

            // ── RING 5 : 3–5 km  (Ambala Highway, VIP Road north) ───────────
            ['first_name'=>'Harbhajan','last_name'=>'Maan','company_name'=>'Maan Properties Ambala Highway','phone'=>'+91 98769 30036','email'=>'harbhajan.maan@maanproperties.com','bio'=>'Maan Properties covers the Ambala Highway stretch near Zirakpur exit. Expert in Motia Royal Citi, GBP Athens and Savitry Heights with strong builder connects.','specializations'=>'Ambala Highway,Motia Royal,GBP Athens','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Amanpreet','last_name'=>'Bajaj','company_name'=>'Bajaj Realty VIP Road North','phone'=>'+91 98767 30037','email'=>'amanpreet.bajaj@bajajrealty.in','bio'=>'Bajaj Realty serves VIP Road north—the premium end of Zirakpur. Expert in SBP City of Dreams, Maya Garden City Phase 2 and NK Savitry Greens with 500+ deals.','specializations'=>'SBP Group,Maya Garden,NK Savitry','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Tejpal','last_name'=>'Riar','company_name'=>'Riar Properties VIP Road','phone'=>'+91 98154 30038','email'=>'tejpal.riar@riarproperties.com','bio'=>'Riar Properties is among Zirakpur\'s oldest agencies on VIP Road with 22 years of experience. Helping buyers choose the right society on the premium VIP Road corridor.','specializations'=>'VIP Road Societies,Premium Flats,Investment','operating_cities'=>'Zirakpur,Chandigarh'],
            ['first_name'=>'Nirmal','last_name'=>'Kang','company_name'=>'Kang Estate Ambala Highway','phone'=>'+91 97801 30039','email'=>'nirmal.kang@kangestate.com','bio'=>'Kang Estate operates from Ambala Highway junction. Expert in large-format residential townships and investment-grade plots in the Zirakpur outskirts.','specializations'=>'Residential Townships,Investment Plots,Ambala Highway','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Jagjit','last_name'=>'Dhillon','company_name'=>'Dhillon Property Services','phone'=>'+91 95010 30040','email'=>'jagjit.dhillon@dhillonpropertyservices.com','bio'=>'Dhillon Property Services covers VIP Road north and Ambala Highway with a portfolio of 200+ active listings. Excellent for investors seeking high-appreciation zones.','specializations'=>'High-Appreciation Zones,Investor Advisory,Premium','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Baljit','last_name'=>'Sekhon','company_name'=>'Sekhon Homes VIP Road','phone'=>'+91 99145 30041','email'=>'baljit.sekhon@sekhhonhomes.in','bio'=>'Sekhon Homes has been a pillar on VIP Road for 17 years. From budget 1 BHK to 5 BHK luxury penthouses, they handle the entire VIP Road property spectrum.','specializations'=>'Budget to Luxury,VIP Road,All Segments','operating_cities'=>'Zirakpur,Mohali,Chandigarh'],
            ['first_name'=>'Opinder','last_name'=>'Cheema','company_name'=>'Cheema Realtors Zirakpur','phone'=>'+91 98767 30042','email'=>'opinder.cheema@cheemarealtor.com','bio'=>'Cheema Realtors is a data-driven agency on VIP Road north. Using market analytics to give buyers the best price discovery in a 3–5 km band from Dhakoli.','specializations'=>'Market Analytics,Price Discovery,VIP Road','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Gurjot','last_name'=>'Kalra','company_name'=>'Kalra Properties Zirakpur','phone'=>'+91 98726 30043','email'=>'gurjot.kalra@kalraproperties.in','bio'=>'Kalra Properties is trusted by Chandigarh relocators moving to Zirakpur. Expert in comparing societies along VIP Road and Ambala Highway to find the best fit.','specializations'=>'Chandigarh Relocators,Society Comparison,Residential','operating_cities'=>'Zirakpur,Chandigarh'],
            ['first_name'=>'Shaminder','last_name'=>'Grewal','company_name'=>'Grewal Estate Ambala Highway','phone'=>'+91 98763 30044','email'=>'shaminder.grewal@grewalestatezirakpur.com','bio'=>'Grewal Estate covers the Zirakpur–Ambala National Highway 7 belt. Expert in plotted developments and township schemes off the highway that offer high ROI.','specializations'=>'Highway Plots,Township Schemes,ROI Advisory','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Harjinder','last_name'=>'Bhatti','company_name'=>'Bhatti Properties Zirakpur North','phone'=>'+91 98761 30045','email'=>'harjinder.bhatti@bhattipropertieszirakpur.com','bio'=>'Bhatti Properties serves the northern reaches of Zirakpur towards Chandigarh border. Expert in builder-floor investments and independent house portfolio expansion.','specializations'=>'Builder Floors,Independent Houses,Chandigarh Border','operating_cities'=>'Zirakpur,Chandigarh'],

            // ── RING 6 : 5+ km  (Panchkula Sectors, Mohali Aerocity) ────────
            ['first_name'=>'Arvind','last_name'=>'Kapoor','company_name'=>'Kapoor Properties Panchkula','phone'=>'+91 98152 30046','email'=>'arvind.kapoor@kapoorpropertiespkl.com','bio'=>'Kapoor Properties is a prominent agency in Panchkula Sector 20, ~5.5 km from Dhakoli. Expert in Navraj Antalyas, Surya Residency and HUDA society transactions.','specializations'=>'Panchkula Sectors,HUDA Flats,Navraj Projects','operating_cities'=>'Panchkula,Zirakpur'],
            ['first_name'=>'Rajinder','last_name'=>'Anand','company_name'=>'Anand Estate Panchkula','phone'=>'+91 98155 30047','email'=>'rajinder.anand@anandestatezirakpur.com','bio'=>'Anand Estate covers Panchkula Sectors 5–20, ~5–7 km from Dhakoli. Trusted by over 300 government employees for HUDA allotments and independent house transactions.','specializations'=>'HUDA Allotments,Independent Houses,Panchkula Sectors','operating_cities'=>'Panchkula,Zirakpur,Chandigarh'],
            ['first_name'=>'Mukesh','last_name'=>'Vohra','company_name'=>'Vohra Properties Mani Majra','phone'=>'+91 98158 30048','email'=>'mukesh.vohra@vohraproperties.in','bio'=>'Vohra Properties serves Mani Majra and Dhanas, ~6–7 km from Dhakoli. Expert in PUDA colonies, CHB flats and resale properties near UT–Punjab border.','specializations'=>'Mani Majra,PUDA Colony,CHB Flats','operating_cities'=>'Chandigarh,Zirakpur,Panchkula'],
            ['first_name'=>'Suresh','last_name'=>'Nanda','company_name'=>'Nanda Realty Panchkula','phone'=>'+91 98760 30049','email'=>'suresh.nanda@nandarealtypkl.com','bio'=>'Nanda Realty is a well-established name in Panchkula Sector 10–15. Serving buyers and investors with deep sector-level market knowledge in Panchkula.','specializations'=>'Panchkula Sector 10-15,Residential,Commercial','operating_cities'=>'Panchkula,Chandigarh'],
            ['first_name'=>'Puneet','last_name'=>'Bhasin','company_name'=>'Bhasin Property Advisors Panchkula','phone'=>'+91 98140 30050','email'=>'puneet.bhasin@bhasinpropertyadvisors.com','bio'=>'Bhasin Property Advisors serves Panchkula Sector 14–25 with focus on TDI Rosewood City, Rashi Sapphire and Navraj developments. 12 years of Panchkula expertise.','specializations'=>'TDI Rosewood,Rashi Sapphire,Navraj Panchkula','operating_cities'=>'Panchkula,Zirakpur,Chandigarh'],
            ['first_name'=>'Dinesh','last_name'=>'Mehra','company_name'=>'Mehra Group Properties Panchkula','phone'=>'+91 98141 30051','email'=>'dinesh.mehra@mehragrouppkl.in','bio'=>'Mehra Group Properties is among the most trusted in Panchkula, covering Sectors 1–25 comprehensively. Special focus on high-value luxury floors and HUDA plots.','specializations'=>'Luxury Floors,HUDA Plots,All Panchkula Sectors','operating_cities'=>'Panchkula,Chandigarh,Zirakpur'],
            ['first_name'=>'Rajat','last_name'=>'Verma','company_name'=>'Verma Estates Mohali Aerocity','phone'=>'+91 98143 30052','email'=>'rajat.verma@vermaestatesaero.com','bio'=>'Verma Estates is the leading agency near Mohali Aerocity, ~6 km from Dhakoli. Expert in GMADA Aerocity plots, IT City offices and Airport Road apartments.','specializations'=>'GMADA Aerocity,IT City,Airport Zone','operating_cities'=>'Mohali,Zirakpur,Chandigarh'],
            ['first_name'=>'Naveen','last_name'=>'Rai','company_name'=>'Rai Property Solutions Mohali','phone'=>'+91 98145 30053','email'=>'naveen.rai@raipropertysolutions.com','bio'=>'Rai Property Solutions covers New Chandigarh (Mullanpur) and Mohali peripheral zones from ~7 km out. Expert in DLF Garden City, Omaxe New Chandigarh projects.','specializations'=>'New Chandigarh,DLF Garden City,Omaxe Projects','operating_cities'=>'Mohali,Chandigarh'],
            ['first_name'=>'Shivam','last_name'=>'Khurana','company_name'=>'Khurana Properties Kharar','phone'=>'+91 98763 30054','email'=>'shivam.khurana@khuranapropertieskharar.com','bio'=>'Khurana Properties is the market leader in Kharar, ~10 km from Dhakoli. Expert in OSB Golf Heights, Pacific Blue Sapphire and Imperia Esfera for budget buyers.','specializations'=>'Kharar Societies,Budget Buyers,Affordable Flats','operating_cities'=>'Mohali,Kharar'],
            ['first_name'=>'Akshay','last_name'=>'Puri','company_name'=>'Puri Estate Derabassi','phone'=>'+91 98766 30055','email'=>'akshay.puri@puriestate-dbs.com','bio'=>'Puri Estate covers Derabassi and the southern Zirakpur outskirts, ~8 km from Dhakoli. Expert in SBP Housing Park, Green Villas and plotted developments off NH.','specializations'=>'Derabassi,SBP Housing Park,Plotted Development','operating_cities'=>'Zirakpur,Derabassi,Panchkula'],

            // ── BONUS dealers (Zirakpur mixed proximity) ──────────────────────
            ['first_name'=>'Jaskaran','last_name'=>'Soni','company_name'=>'Soni Real Estate Zirakpur','phone'=>'+91 99146 30056','email'=>'jaskaran.soni@sonirealestate.in','bio'=>'Soni Real Estate offers full-spectrum property advisory across Zirakpur. Known for quick property valuation and accurate market pricing within all Zirakpur sub-markets.','specializations'=>'Property Valuation,Full Spectrum,Zirakpur','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Kulbir','last_name'=>'Bajaj','company_name'=>'Bajaj Property Hub Zirakpur','phone'=>'+91 98762 30057','email'=>'kulbir.bajaj@bajajpropertyhub.in','bio'=>'Bajaj Property Hub has a proven track record in Zirakpur covering all zones. Specialising in investment-grade properties with assured returns and rental income potential.','specializations'=>'Investment Grade,Assured Returns,Rental Income','operating_cities'=>'Zirakpur,Panchkula,Mohali'],
            ['first_name'=>'Gurvinder','last_name'=>'Basra','company_name'=>'Basra Properties Zirakpur','phone'=>'+91 95011 30058','email'=>'gurvinder.basra@basraproperties.com','bio'=>'Basra Properties is a new-generation agency in Zirakpur using digital platforms for property discovery. Excellent for NRI buyers seeking transparent remote transactions.','specializations'=>'NRI Deals,Digital Listings,Transparency','operating_cities'=>'Zirakpur,Mohali,Chandigarh'],
            ['first_name'=>'Jasdeep','last_name'=>'Grover','company_name'=>'Grover Estate Zirakpur','phone'=>'+91 98161 30059','email'=>'jasdeep.grover@groverestate.in','bio'=>'Grover Estate is a full-service property agency in Zirakpur. Expert in commercial SCO shops, office spaces and residential apartments across all price segments.','specializations'=>'Commercial SCO,Office Spaces,Residential','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Amarjit','last_name'=>'Kapila','company_name'=>'Kapila Real Estate Zirakpur','phone'=>'+91 98724 30060','email'=>'amarjit.kapila@kapilarealestate.in','bio'=>'Kapila Real Estate is a respected name in Zirakpur for 16 years. Expert in luxury apartment sales, resale flats and long-term investment advisory for Tricity region.','specializations'=>'Luxury Apartments,Resale,Long-Term Investment','operating_cities'=>'Zirakpur,Mohali,Chandigarh,Panchkula'],
            ['first_name'=>'Prabhjot','last_name'=>'Bedi','company_name'=>'Bedi Properties Zirakpur','phone'=>'+91 98726 30061','email'=>'prabhjot.bedi@bedipropertieszirakpur.com','bio'=>'Bedi Properties Zirakpur is focused on customer-first service. Offering free property legal check, site tour coordination and negotiation support to every buyer.','specializations'=>'Legal Check,Site Tours,Negotiation Support','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Rohit','last_name'=>'Kalsi','company_name'=>'Kalsi Property Group','phone'=>'+91 98762 30062','email'=>'rohit.kalsi@kalsiproperty.in','bio'=>'Kalsi Property Group is known for premium listings and white-collar client services in Zirakpur. Dedicated relationship managers for every buyer and seller.','specializations'=>'Premium Listings,White Collar Clients,Relationship Mgmt','operating_cities'=>'Zirakpur,Chandigarh'],
            ['first_name'=>'Tarsem','last_name'=>'Dhatt','company_name'=>'Dhatt Realty Zirakpur','phone'=>'+91 98157 30063','email'=>'tarsem.dhatt@dhattrealty.com','bio'=>'Dhatt Realty has 19 years of experience in Zirakpur. Expert in pre-launch bookings with leading developers and resale properties with strong price appreciation history.','specializations'=>'Pre-Launch Bookings,Resale,Price Appreciation','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Charanjit','last_name'=>'Saini','company_name'=>'Saini Property Zone Zirakpur','phone'=>'+91 98728 30064','email'=>'charanjit.saini@sainipropertyzone.com','bio'=>'Saini Property Zone is a high-volume agency in Zirakpur closing 100+ transactions annually. Comprehensive listing database for every pincode in and around Dhakoli.','specializations'=>'High Volume,All Pincodes,Comprehensive Listings','operating_cities'=>'Zirakpur,Panchkula,Mohali'],
            ['first_name'=>'Deepinder','last_name'=>'Chawla','company_name'=>'Chawla Estate Agents Zirakpur','phone'=>'+91 98727 30065','email'=>'deepinder.chawla@chawlaestates.in','bio'=>'Chawla Estate Agents is a premium property firm in Zirakpur with an office opposite SBP City of Dreams. Specialising in premium project bookings and investor advisory.','specializations'=>'SBP Projects,Premium Bookings,Investor Advisory','operating_cities'=>'Zirakpur,Mohali'],
            ['first_name'=>'Kanwarjit','last_name'=>'Brar','company_name'=>'Brar Homes Zirakpur','phone'=>'+91 95927 30066','email'=>'kanwarjit.brar@brarhomes.in','bio'=>'Brar Homes provides friendly, jargon-free property advice across Zirakpur. Perfect for senior citizens, retired professionals and first-time buyers in Zirakpur.','specializations'=>'Senior Citizen Buyers,First-Time Buyers,Friendly Service','operating_cities'=>'Zirakpur,Panchkula'],
            ['first_name'=>'Rachhpal','last_name'=>'Sran','company_name'=>'Sran Property Advisors','phone'=>'+91 98761 30067','email'=>'rachhpal.sran@sranpropertyzirakpur.com','bio'=>'Sran Property Advisors is a boutique firm in Zirakpur offering personalised property consulting for premium residential and commercial buyers in the Tricity belt.','specializations'=>'Boutique Consulting,Premium Residential,Commercial','operating_cities'=>'Zirakpur,Chandigarh,Panchkula'],
            ['first_name'=>'Gurdeep','last_name'=>'Dhami','company_name'=>'Dhami Real Estate Zirakpur','phone'=>'+91 98764 30068','email'=>'gurdeep.dhami@dhamirealestate.in','bio'=>'Dhami Real Estate serves the complete Zirakpur market with offices at both VIP Road and Ambala Highway. Full-service model covering listings, valuation and documentation.','specializations'=>'Full-Service,VIP Road,Ambala Highway','operating_cities'=>'Zirakpur,Mohali,Panchkula'],
            ['first_name'=>'Navjot','last_name'=>'Bhangoo','company_name'=>'Bhangoo Properties Zirakpur','phone'=>'+91 98765 30069','email'=>'navjot.bhangoo@bhangoozirakpur.com','bio'=>'Bhangoo Properties is a 2nd-generation real estate firm with 24+ years in Zirakpur. Comprehensive knowledge of land acquisition, plotted development and apartment markets.','specializations'=>'Land Acquisition,Plots,Apartment Markets','operating_cities'=>'Zirakpur,Mohali,Chandigarh'],
            ['first_name'=>'Simarjit','last_name'=>'Randhawa','company_name'=>'Randhawa Property Hub Zirakpur','phone'=>'+91 97801 30070','email'=>'simarjit.randhawa@randhawahub.in','bio'=>'Randhawa Property Hub is a modern, tech-enabled agency in Zirakpur. Using 3D virtual tours and AI-assisted property matching to make buying easy for out-of-town clients.','specializations'=>'Virtual Tours,AI Property Matching,Tech-Enabled','operating_cities'=>'Zirakpur,Mohali,Chandigarh,Panchkula'],
        ];

        foreach ($dealers as $d) {
            $baseSlug = Str::slug($d['company_name']);
            $slug = $baseSlug;
            $i = 1;
            while (DB::table('property_dealers')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            if (DB::table('property_dealers')->where('email', $d['email'])->exists()) {
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
        $this->command->info('    ✔ 70 dealers seeded.');
    }

    // =========================================================================
    // BUILDERS  (25 records, nearest first)
    // =========================================================================
    private function seedBuilders(): void
    {
        $this->command->info('  → Seeding 25 proximity-ordered builders...');

        $builders = [

            // ── 0–2 km : Dhakoli / Zirakpur-based builders ───────────────────
            [
                'name'                     => 'Srishti Infra Developers',
                'company_name'             => 'Srishti Infra Developers Pvt. Ltd.',
                'email'                    => 'info@srishtiinfra.com',
                'phone'                    => '+91 98151 40001',
                'website'                  => 'https://www.srishtiinfra.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2011',
                'rera_registration'        => 'PBRERA-SAS79-PR2100',
                'cities_operating'         => 'Zirakpur,Dhakoli,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Srishti Infra Developers is the creator of Srishti Avenue in Dhakoli, Zirakpur. Since 2011 they have delivered 8 residential projects covering 1,200+ units in the Dhakoli–Zirakpur belt. Known for timely delivery, quality construction and transparent pricing.',
            ],
            [
                'name'                     => 'Dhakoli Heights Builders',
                'company_name'             => 'Dhakoli Heights Builders & Developers',
                'email'                    => 'contact@dhakoliheights.in',
                'phone'                    => '+91 97800 40002',
                'website'                  => 'https://www.dhakoliheights.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2015',
                'rera_registration'        => 'PBRERA-SAS79-PR2200',
                'cities_operating'         => 'Zirakpur,Panchkula',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 5,
                'description'              => 'Dhakoli Heights Builders specialises in mid-segment residential apartments and builder floors within Dhakoli village and its immediate surrounds. 5 completed projects, 600+ satisfied families.',
            ],
            [
                'name'                     => 'NK Sharma Group',
                'company_name'             => 'NK Sharma Construction Pvt. Ltd.',
                'email'                    => 'info@nksharmagroup.com',
                'phone'                    => '+91 98726 40003',
                'website'                  => 'https://www.nksharmagroup.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2003',
                'rera_registration'        => 'PBRERA-SAS79-PR0400',
                'cities_operating'         => 'Zirakpur,Mohali,Panchkula',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 18,
                'description'              => 'NK Sharma Group is among Zirakpur\'s most prolific builders with flagship projects NK Savitry Greens 1 & 2 near VIP Road. 18+ delivered projects, 4,000+ homes handed over across Tricity since 2003.',
            ],
            [
                'name'                     => 'Mayfair Developers',
                'company_name'             => 'Mayfair Developers & Promoters',
                'email'                    => 'sales@mayfairdevelopers.com',
                'phone'                    => '+91 98765 40004',
                'website'                  => 'https://www.mayfairdevelopers.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS79-PR0620',
                'cities_operating'         => 'Zirakpur,Mohali',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Mayfair Developers is the builder behind Mayfair Royal Apartments and Mayfair Greens in Zirakpur. Known for quality finishing and on-time delivery with 12 completed residential projects.',
            ],
            [
                'name'                     => 'Mona Builders',
                'company_name'             => 'Mona Builders & Infrastructure',
                'email'                    => 'info@monabuilders.in',
                'phone'                    => '+91 98729 40005',
                'website'                  => 'https://www.monabuilders.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2006',
                'rera_registration'        => 'PBRERA-SAS79-PR0510',
                'cities_operating'         => 'Zirakpur,Mohali,Panchkula',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'description'              => 'Mona Builders is the creator of Mona Greens and Mona Greens 2 in Zirakpur, popular for their spacious layouts and prime location near Gazipur Road and VIP Road.',
            ],
            [
                'name'                     => 'Savitry Developers',
                'company_name'             => 'Savitry Developers Pvt. Ltd.',
                'email'                    => 'info@savitrydevelopers.com',
                'phone'                    => '+91 99143 40006',
                'website'                  => 'https://www.savitrydevelopers.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2004',
                'rera_registration'        => 'PBRERA-SAS79-PR0380',
                'cities_operating'         => 'Zirakpur,Panchkula,Mohali',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 15,
                'description'              => 'Savitry Developers builds premium residential societies across Peer Mushalla, VIP Road and Ambala Highway in Zirakpur. Flagship projects include Savitry Green Avenue and Savitry Heights.',
            ],
            [
                'name'                     => 'RM Infrastructure',
                'company_name'             => 'RM Infrastructure & Developers',
                'email'                    => 'info@rminfra.in',
                'phone'                    => '+91 98728 40007',
                'website'                  => 'https://www.rminfra.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2009',
                'rera_registration'        => 'PBRERA-SAS79-PR0780',
                'cities_operating'         => 'Zirakpur,Panchkula',
                'rating'                   => 3.8,
                'is_verified'              => true,
                'total_delivered_projects' => 7,
                'description'              => 'RM Infrastructure is the developer of RM Royale Residency in Peer Mushalla, Zirakpur. Their projects are known for good road connectivity, green zones and affordable pricing.',
            ],
            [
                'name'                     => 'Maya Garden Group',
                'company_name'             => 'Maya Garden Group Ltd.',
                'email'                    => 'sales@mayagardengroup.com',
                'phone'                    => '+91 95010 40008',
                'website'                  => 'https://www.mayagardengroup.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2005',
                'rera_registration'        => 'PBRERA-SAS79-PR0460',
                'cities_operating'         => 'Zirakpur,Mohali',
                'rating'                   => 4.4,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Maya Garden Group is developer of the award-winning Maya Garden City — Zirakpur\'s largest integrated township on VIP Road. Offering 2–4 BHK apartments, villas and commercial spaces in a gated ecosystem.',
            ],
            [
                'name'                     => 'Green Lotus Projects',
                'company_name'             => 'Green Lotus Projects Ltd.',
                'email'                    => 'info@greenlotusprojects.com',
                'phone'                    => '+91 98762 40009',
                'website'                  => 'https://www.greenlotusprojects.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2010',
                'rera_registration'        => 'PBRERA-SAS79-PR0890',
                'cities_operating'         => 'Zirakpur,Mohali,Panchkula',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'Green Lotus Projects is the developer of Green Lotus Saksham and related premium projects on Airport Road, Zirakpur. Known for eco-friendly construction, IGBC-rated green buildings.',
            ],
            [
                'name'                     => 'GBP Group',
                'company_name'             => 'GBP Group (Goyal Buildcon Pvt. Ltd.)',
                'email'                    => 'info@gbpgroup.in',
                'phone'                    => '+91 98161 40010',
                'website'                  => 'https://www.gbpgroup.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2002',
                'rera_registration'        => 'PBRERA-SAS79-PR0305',
                'cities_operating'         => 'Zirakpur,Mohali,Chandigarh,Panchkula',
                'rating'                   => 4.5,
                'is_verified'              => true,
                'total_delivered_projects' => 22,
                'description'              => 'GBP Group is a leading developer in the Tricity region with 22+ delivered projects. Flagship projects include GBP Athens on Ambala Highway and GBP Camellia on Patiala Road. Known for strong build quality and investor-friendly pricing.',
            ],

            // ── 2–5 km : Zirakpur / Panchkula border builders ─────────────────
            [
                'name'                     => 'Sarvottam Group',
                'company_name'             => 'Sarvottam Infrastructure Pvt. Ltd.',
                'email'                    => 'info@sarvottam.in',
                'phone'                    => '+91 98724 40011',
                'website'                  => 'https://www.sarvottam.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2007',
                'rera_registration'        => 'PBRERA-SAS79-PR0630',
                'cities_operating'         => 'Zirakpur,Panchkula,Mohali',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 11,
                'description'              => 'Sarvottam Group is developer of Sarvottam Homes, Sarvottam Heights and Sarvottam Garden across Zirakpur and Baltana. Mid-segment housing specialist with 2,500+ delivered units.',
            ],
            [
                'name'                     => 'Tricity Heights Builders',
                'company_name'             => 'Tricity Heights Builders Pvt. Ltd.',
                'email'                    => 'info@tricityheights.com',
                'phone'                    => '+91 98727 40012',
                'website'                  => 'https://www.tricityheights.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2013',
                'rera_registration'        => 'PBRERA-SAS79-PR1500',
                'cities_operating'         => 'Zirakpur,Mohali',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 4,
                'description'              => 'Tricity Heights Builders focuses on high-rise residential construction on Airport Road, Zirakpur. Their flagship Tricity Heights project offers panoramic Shivalik views and modern amenities.',
            ],
            [
                'name'                     => 'Manglam Builders',
                'company_name'             => 'Manglam Builders & Developers',
                'email'                    => 'info@manglambuilders.in',
                'phone'                    => '+91 98157 40013',
                'website'                  => 'https://www.manglambuilders.in',
                'city'                     => 'Zirakpur',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS79-PR0720',
                'cities_operating'         => 'Zirakpur,Panchkula',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 8,
                'description'              => 'Manglam Builders is developer of Manglam Heights at Peer Mushalla, Zirakpur. Specialising in 2 & 3 BHK apartments with excellent community amenities at competitive prices.',
            ],
            [
                'name'                     => 'Altus Infratech',
                'company_name'             => 'Altus Infratech Pvt. Ltd.',
                'email'                    => 'info@altusinfratech.com',
                'phone'                    => '+91 95927 40014',
                'website'                  => 'https://www.altusinfratech.com',
                'city'                     => 'Zirakpur',
                'established_year'         => '2014',
                'rera_registration'        => 'PBRERA-SAS79-PR1620',
                'cities_operating'         => 'Zirakpur,Mohali,Chandigarh',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 6,
                'description'              => 'Altus Infratech is the developer of Altus Space Towers on Airport Road, Zirakpur — a pioneering mixed-use development combining residential and commercial spaces with state-of-the-art amenities.',
            ],
            [
                'name'                     => 'Himalaya Buildcon',
                'company_name'             => 'Himalaya Buildcon Pvt. Ltd.',
                'email'                    => 'info@himalayabuildcon.in',
                'phone'                    => '+91 98152 40015',
                'website'                  => 'https://www.himalayabuildcon.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2001',
                'rera_registration'        => 'HRERA-PKL-2001-0050',
                'cities_operating'         => 'Panchkula,Zirakpur,Chandigarh',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 16,
                'description'              => 'Himalaya Buildcon is among Panchkula\'s oldest builders with 16 completed projects in Sectors 12–21. Known for quality construction, transparent pricing and timely possession.',
            ],

            // ── 5+ km : Panchkula / Mohali builders ────────────────────────────
            [
                'name'                     => 'Navraj Group',
                'company_name'             => 'Navraj Infratech Pvt. Ltd.',
                'email'                    => 'info@navrajgroup.in',
                'phone'                    => '+91 98760 40016',
                'website'                  => 'https://www.navrajgroup.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2009',
                'rera_registration'        => 'HRERA-PKL-2009-0120',
                'cities_operating'         => 'Panchkula,Zirakpur,Chandigarh',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 13,
                'description'              => 'Navraj Group is a leading developer in Panchkula famous for Navraj The Antalyas in Sector 20 and Navraj Plots in Sector 25. Known for luxury finishes and prime Panchkula locations.',
            ],
            [
                'name'                     => 'Rashi Real Estate',
                'company_name'             => 'Rashi Real Estate Pvt. Ltd.',
                'email'                    => 'info@rashirealestate.in',
                'phone'                    => '+91 98766 40017',
                'website'                  => 'https://www.rashirealestate.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2005',
                'rera_registration'        => 'HRERA-PKL-2005-0080',
                'cities_operating'         => 'Panchkula,Chandigarh',
                'rating'                   => 4.0,
                'is_verified'              => true,
                'total_delivered_projects' => 10,
                'description'              => 'Rashi Real Estate is the developer of Rashi Sapphire and Rashi Pearl Residency in Panchkula. Known for Vastu-compliant design, quality workmanship and strong community management.',
            ],
            [
                'name'                     => 'TDI Infrastructure',
                'company_name'             => 'TDI Infrastructure Ltd.',
                'email'                    => 'info@tdiinfrastructure.in',
                'phone'                    => '+91 98141 40018',
                'website'                  => 'https://www.tdiinfrastructure.in',
                'city'                     => 'Panchkula',
                'established_year'         => '1998',
                'rera_registration'        => 'HRERA-PKL-1998-0020',
                'cities_operating'         => 'Panchkula,Chandigarh,Mohali',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 20,
                'description'              => 'TDI Infrastructure is the developer of the prestigious TDI Rosewood City in Panchkula Sector 14, one of the most sought-after residential developments in the Tricity region. 20+ delivered projects across NCR, Panchkula and Chandigarh.',
            ],
            [
                'name'                     => 'Omkar Builders',
                'company_name'             => 'Omkar Builders & Developers',
                'email'                    => 'info@omkarbuilders.in',
                'phone'                    => '+91 98764 40019',
                'website'                  => 'https://www.omkarbuilders.in',
                'city'                     => 'Panchkula',
                'established_year'         => '2003',
                'rera_registration'        => 'HRERA-PKL-2003-0060',
                'cities_operating'         => 'Panchkula,Zirakpur',
                'rating'                   => 3.9,
                'is_verified'              => true,
                'total_delivered_projects' => 9,
                'description'              => 'Omkar Builders develops quality residential projects in Panchkula including Omkar Residency (Sector 19) and Omkar Builder Floors (Sector 12). Affordable mid-segment housing with strong community infrastructure.',
            ],
            [
                'name'                     => 'JLPL Group',
                'company_name'             => 'Janta Land Promoters Ltd.',
                'email'                    => 'info@jlplgroup.com',
                'phone'                    => '+91 98143 40020',
                'website'                  => 'https://www.jlplgroup.com',
                'city'                     => 'Mohali',
                'established_year'         => '2005',
                'rera_registration'        => 'PBRERA-SAS79-PR0480',
                'cities_operating'         => 'Mohali,Chandigarh,Zirakpur,Panchkula',
                'rating'                   => 4.4,
                'is_verified'              => true,
                'total_delivered_projects' => 19,
                'description'              => 'JLPL Group is one of Mohali\'s biggest developers known for JLPL Falcon View in Sector 66A and JLPL Township in Sector 90–91. 19 delivered projects, 6,000+ units handed over.',
            ],
            [
                'name'                     => 'Godrej Properties',
                'company_name'             => 'Godrej Properties Ltd.',
                'email'                    => 'tricitysales@godrejproperties.com',
                'phone'                    => '+91 98144 40021',
                'website'                  => 'https://www.godrejproperties.com',
                'city'                     => 'Mohali',
                'established_year'         => '1990',
                'rera_registration'        => 'PBRERA-SAS79-PR0290',
                'cities_operating'         => 'Mohali,Chandigarh,Panchkula,Zirakpur',
                'rating'                   => 4.6,
                'is_verified'              => true,
                'total_delivered_projects' => 35,
                'description'              => 'Godrej Properties, a pan-India premium developer, is active in Mohali with Godrej Woods, Godrej Evoq and Emaar-Godrej collaboration projects. Known for global quality, transparent pricing and on-time delivery.',
            ],
            [
                'name'                     => 'Emaar India',
                'company_name'             => 'Emaar India Pvt. Ltd.',
                'email'                    => 'tricitysales@emaarpacific.com',
                'phone'                    => '+91 98142 40022',
                'website'                  => 'https://www.emaarindiaproperties.com',
                'city'                     => 'Mohali',
                'established_year'         => '2008',
                'rera_registration'        => 'PBRERA-SAS79-PR0540',
                'cities_operating'         => 'Mohali,Chandigarh,Panchkula',
                'rating'                   => 4.5,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'Emaar India is the Indian subsidiary of Dubai-based Emaar Properties. Developing premium residential townships in Mohali including Emaar The Views, Emaar Mohali Hills and Emaar Palmview.',
            ],
            [
                'name'                     => 'Gillco Builders',
                'company_name'             => 'Gillco Builders & Developers',
                'email'                    => 'info@gillcobuilders.com',
                'phone'                    => '+91 98763 40023',
                'website'                  => 'https://www.gillcobuilders.com',
                'city'                     => 'Mohali',
                'established_year'         => '2006',
                'rera_registration'        => 'PBRERA-SAS79-PR0560',
                'cities_operating'         => 'Mohali,Kharar,Zirakpur,Panchkula',
                'rating'                   => 4.3,
                'is_verified'              => true,
                'total_delivered_projects' => 14,
                'description'              => 'Gillco Builders is a premier developer of Gillco Parkhills (Sector 126, Mohali) and Gillco Valley (Sector 127). Known for large format gated communities with excellent social infrastructure.',
            ],
            [
                'name'                     => 'Wave Estate',
                'company_name'             => 'Wave Infratech Pvt. Ltd.',
                'email'                    => 'tricitysales@waveinfra.com',
                'phone'                    => '+91 98140 40024',
                'website'                  => 'https://www.waveinfraestate.com',
                'city'                     => 'Mohali',
                'established_year'         => '2003',
                'rera_registration'        => 'PBRERA-SAS79-PR0350',
                'cities_operating'         => 'Mohali,Chandigarh,Zirakpur',
                'rating'                   => 4.1,
                'is_verified'              => true,
                'total_delivered_projects' => 12,
                'description'              => 'Wave Estate is a landmark integrated township in Sectors 85–99, Mohali spread across 450+ acres. Offering premium plots, independent floors and high-rise apartments with world-class amenities.',
            ],
            [
                'name'                     => 'Paras Buildtech',
                'company_name'             => 'Paras Buildtech India Ltd.',
                'email'                    => 'info@parasbuildtech.com',
                'phone'                    => '+91 98145 40025',
                'website'                  => 'https://www.parasbuildtech.com',
                'city'                     => 'Mohali',
                'established_year'         => '2005',
                'rera_registration'        => 'PBRERA-SAS79-PR0480',
                'cities_operating'         => 'Mohali,Kharar,Chandigarh,Panchkula',
                'rating'                   => 4.2,
                'is_verified'              => true,
                'total_delivered_projects' => 16,
                'description'              => 'Paras Buildtech is the developer behind Paras Panorama in Sector 76, Paras The Manor in Sector 115 and Paras Irene in Sector 70A Mohali. A trusted name for both end-users and investors in Tricity.',
            ],
        ];

        // Projects keyed by builder email
        $projectMap = [
            'info@srishtiinfra.com' => [
                ['title'=>'Srishti Avenue Phase 1','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Srishti Avenue, Dhakoli, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>120,'available_units'=>8,'price_from'=>2800000,'price_to'=>5200000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR2100','total_towers'=>6,'floors_per_tower'=>'7','latitude'=>30.6402,'longitude'=>76.8193,'amenities'=>'Lift,Power Backup,24x7 Security,Car Parking,CCTV,Kids Play Area','nearby_schools'=>'Ryan International School (1.5 km)','nearby_hospitals'=>'Mukat Hospital (3 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Srishti Avenue Phase 1 is a premium residential complex in Dhakoli, offering 2 & 3 BHK apartments with modern amenities. Ready to move.'],
                ['title'=>'Srishti Avenue Phase 2','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Srishti Avenue Extension, Dhakoli, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>180,'available_units'=>20,'price_from'=>3200000,'price_to'=>6000000,'possession_date'=>'2023-03-31','rera_id'=>'PBRERA-SAS79-PR2101','total_towers'=>8,'floors_per_tower'=>'9','latitude'=>30.6405,'longitude'=>76.8197,'amenities'=>'Swimming Pool,Gymnasium,Power Backup,24x7 Security,Clubhouse,Kids Play Area,Jogging Track','nearby_schools'=>'St. Xavier School (2 km)','nearby_hospitals'=>'Grecian Hospital (4 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Srishti Avenue Phase 2 offers upgraded 2, 3 & 4 BHK apartments with a swimming pool, gym and clubhouse in Dhakoli.'],
                ['title'=>'Srishti Green Residency','project_type'=>'Residential','status'=>'Under Construction','address'=>'Dhakoli Village Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>240,'available_units'=>140,'price_from'=>3500000,'price_to'=>7000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS79-PR2102','total_towers'=>10,'floors_per_tower'=>'12','latitude'=>30.6398,'longitude'=>76.8188,'amenities'=>'Infinity Pool,Gymnasium,Yoga Deck,Kids Pool,Spa,24x7 Security,EV Charging,Tennis Court','nearby_schools'=>'Delhi Public School (2 km)','nearby_hospitals'=>'Fortis Hospital (5 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Srishti Green Residency is an upcoming luxury project with world-class amenities right in the heart of Dhakoli, Zirakpur.'],
            ],
            'contact@dhakoliheights.in' => [
                ['title'=>'Dhakoli Heights Phase 1','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Dhakoli Village, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>96,'available_units'=>5,'price_from'=>2500000,'price_to'=>4500000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR2200','total_towers'=>4,'floors_per_tower'=>'6','latitude'=>30.6408,'longitude'=>76.8202,'amenities'=>'Lift,Power Backup,Security,Car Parking,CCTV','nearby_schools'=>'Modern School (1 km)','nearby_hospitals'=>'Civil Hospital Zirakpur (3 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Dhakoli Heights Phase 1 offers affordable 2 BHK apartments in the heart of Dhakoli village.'],
                ['title'=>'Dhakoli Heights Tower 2','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Near Dhakoli Chowk, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>144,'available_units'=>12,'price_from'=>2800000,'price_to'=>5000000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS79-PR2201','total_towers'=>6,'floors_per_tower'=>'8','latitude'=>30.6412,'longitude'=>76.8198,'amenities'=>'Gym,Power Backup,24x7 Security,Intercom,CCTV,Visitor Parking','nearby_schools'=>'DPS Zirakpur (2.5 km)','nearby_hospitals'=>'Mukat Hospital (3.5 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Dhakoli Heights Tower 2 — newly delivered 2 & 3 BHK apartments with modern security and parking.'],
            ],
            'info@nksharmagroup.com' => [
                ['title'=>'NK Savitry Greens 2','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road near Dhakoli, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>504,'available_units'=>35,'price_from'=>2900000,'price_to'=>6000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR0400','total_towers'=>14,'floors_per_tower'=>'11','latitude'=>30.6457,'longitude'=>76.8186,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Jogging Track,Kids Play Area,Indoor Games','nearby_schools'=>'Innocent Hearts (1 km)','nearby_hospitals'=>'Fortis Hospital (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'NK Savitry Greens 2 is a landmark residential project on VIP Road, Zirakpur. Ready to move 2 & 3 BHK apartments with premium amenities.'],
                ['title'=>'NK Savitry Greens Elite','project_type'=>'Residential','status'=>'Under Construction','address'=>'VIP Road Extension, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>360,'available_units'=>200,'price_from'=>4200000,'price_to'=>8500000,'possession_date'=>'2026-03-31','rera_id'=>'PBRERA-SAS79-PR0401','total_towers'=>10,'floors_per_tower'=>'14','latitude'=>30.6460,'longitude'=>76.8189,'amenities'=>'Rooftop Lounge,Infinity Pool,Gymnasium,Yoga Deck,EV Charging,Kids Zone,Concierge,24x7 Security','nearby_schools'=>'St. Mary\'s School (1.5 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'NK Savitry Greens Elite is the premium upgrade to the Savitry portfolio — featuring rooftop lounges and infinity pools in Zirakpur.'],
            ],
            'sales@mayfairdevelopers.com' => [
                ['title'=>'Mayfair Royal Apartments','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>288,'available_units'=>18,'price_from'=>3400000,'price_to'=>7200000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR0620','total_towers'=>8,'floors_per_tower'=>'12','latitude'=>30.6458,'longitude'=>76.8184,'amenities'=>'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Clubhouse','nearby_schools'=>'Ryan International (1.5 km)','nearby_hospitals'=>'Mukat Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Mayfair Royal Apartments on VIP Road offers spacious 2 & 3 BHK homes with a premium clubhouse and landscaped gardens.'],
                ['title'=>'Mayfair Greens','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Lohgarh Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>192,'available_units'=>10,'price_from'=>2800000,'price_to'=>5500000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS79-PR0621','total_towers'=>6,'floors_per_tower'=>'9','latitude'=>30.6490,'longitude'=>76.8226,'amenities'=>'Gymnasium,24x7 Security,Power Backup,Lift,Intercom,CCTV,Landscaped Garden','nearby_schools'=>'Delhi Public School (2 km)','nearby_hospitals'=>'Civil Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Mayfair Greens at Lohgarh Road, Zirakpur — affordable 2 & 3 BHK apartments within close proximity of Dhakoli.'],
            ],
            'info@monabuilders.in' => [
                ['title'=>'Mona Greens','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Gazipur Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>336,'available_units'=>22,'price_from'=>3000000,'price_to'=>5800000,'possession_date'=>'2019-12-31','rera_id'=>'PBRERA-SAS79-PR0510','total_towers'=>12,'floors_per_tower'=>'10','latitude'=>30.6498,'longitude'=>76.8232,'amenities'=>'Swimming Pool,Gym,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Clubhouse,Community Hall','nearby_schools'=>'Innocent Hearts (2 km)','nearby_hospitals'=>'Fortis Hospital (4 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Mona Greens at Gazipur Road, Zirakpur is a popular ready-to-move residential complex with 2 & 3 BHK apartments.'],
                ['title'=>'Mona Greens 2','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Near Gazipur Chowk, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>420,'available_units'=>30,'price_from'=>3200000,'price_to'=>6200000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS79-PR0511','total_towers'=>14,'floors_per_tower'=>'11','latitude'=>30.6502,'longitude'=>76.8237,'amenities'=>'Infinity Pool,Gymnasium,Yoga Deck,Squash Court,24x7 Security,EV Charging,Community Park','nearby_schools'=>'St. Xavier\'s (2 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Mona Greens 2 is the upgraded phase of Mona Greens with additional amenities and modern design.'],
            ],
            'info@savitrydevelopers.com' => [
                ['title'=>'Savitry Green Avenue','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Peer Mushalla, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>264,'available_units'=>15,'price_from'=>3600000,'price_to'=>7500000,'possession_date'=>'2021-03-31','rera_id'=>'PBRERA-SAS79-PR0380','total_towers'=>8,'floors_per_tower'=>'12','latitude'=>30.6532,'longitude'=>76.8292,'amenities'=>'Swimming Pool,Gymnasium,Yoga Deck,Kids Pool,24x7 Security,Power Backup,EV Charging,Tennis Court','nearby_schools'=>'Modern Senior Secondary (1 km)','nearby_hospitals'=>'Grecian Hospital (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Savitry Green Avenue at Peer Mushalla is a premium address in Zirakpur. 2, 3 & 4 BHK residences with resort-style amenities.'],
                ['title'=>'Savitry Heights','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Ambala Highway, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>312,'available_units'=>25,'price_from'=>3200000,'price_to'=>6800000,'possession_date'=>'2022-12-31','rera_id'=>'PBRERA-SAS79-PR0381','total_towers'=>10,'floors_per_tower'=>'12','latitude'=>30.6620,'longitude'=>76.8440,'amenities'=>'Swimming Pool,Gym,Clubhouse,24x7 Security,Power Backup,Jogging Track,Basketball Court','nearby_schools'=>'DPS (3 km)','nearby_hospitals'=>'Fortis (6 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>false,'description'=>'Savitry Heights on Ambala Highway offers spacious 2 & 3 BHK homes with excellent highway connectivity.'],
            ],
            'info@rminfra.in' => [
                ['title'=>'RM Royale Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Peer Mushalla Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>180,'available_units'=>12,'price_from'=>3000000,'price_to'=>5800000,'possession_date'=>'2020-09-30','rera_id'=>'PBRERA-SAS79-PR0780','total_towers'=>6,'floors_per_tower'=>'10','latitude'=>30.6528,'longitude'=>76.8289,'amenities'=>'Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,CCTV,Visitor Parking','nearby_schools'=>'Innocent Hearts (2 km)','nearby_hospitals'=>'Civil Hospital (3 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'RM Royale Residency at Peer Mushalla, Zirakpur — quality 2 & 3 BHK apartments with essential amenities.'],
            ],
            'sales@mayagardengroup.com' => [
                ['title'=>'Maya Garden City','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>2400,'available_units'=>80,'price_from'=>2700000,'price_to'=>8000000,'possession_date'=>'2018-12-31','rera_id'=>'PBRERA-SAS79-PR0460','total_towers'=>48,'floors_per_tower'=>'12','latitude'=>30.6459,'longitude'=>76.8182,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Jogging Track,Kids Play Area,Tennis Court,Basketball Court,Indoor Games,Spa,Party Lawn','nearby_schools'=>'Ryan International (0.5 km)','nearby_hospitals'=>'Fortis Hospital (4 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Maya Garden City is Zirakpur\'s most iconic township on VIP Road — 2400+ units across 48 towers with a 30,000 sq ft clubhouse.'],
                ['title'=>'Maya Garden City Phase 2','project_type'=>'Residential','status'=>'Under Construction','address'=>'VIP Road Extension, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>960,'available_units'=>600,'price_from'=>4000000,'price_to'=>9500000,'possession_date'=>'2027-06-30','rera_id'=>'PBRERA-SAS79-PR0461','total_towers'=>20,'floors_per_tower'=>'16','latitude'=>30.6462,'longitude'=>76.8187,'amenities'=>'Infinity Pool,Gymnasium,Yoga Deck,Concierge,EV Charging,Sky Lounge,Co-Working Space,24x7 Security','nearby_schools'=>'Ryan International (0.5 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Maya Garden City Phase 2 — the luxury upgrade featuring sky lounges, EV charging and co-working spaces. Booking open.'],
            ],
            'info@greenlotusprojects.com' => [
                ['title'=>'Green Lotus Saksham','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Airport Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>216,'available_units'=>18,'price_from'=>3800000,'price_to'=>7500000,'possession_date'=>'2021-12-31','rera_id'=>'PBRERA-SAS79-PR0890','total_towers'=>8,'floors_per_tower'=>'12','latitude'=>30.6572,'longitude'=>76.8232,'amenities'=>'Swimming Pool,Gymnasium,Power Backup,24x7 Security,Clubhouse,IGBC Green Rating,EV Charging,Rainwater Harvesting','nearby_schools'=>'Chandigarh Group of Colleges (1 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'4 km from Airport','is_featured'=>true,'description'=>'Green Lotus Saksham is an IGBC-rated eco-friendly high-rise on Airport Road, Zirakpur. 2, 3 & 4 BHK green residences.'],
                ['title'=>'Green Lotus Abhiraj Heights','project_type'=>'Residential','status'=>'Under Construction','address'=>'Near Airport Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>288,'available_units'=>180,'price_from'=>4500000,'price_to'=>9000000,'possession_date'=>'2026-06-30','rera_id'=>'PBRERA-SAS79-PR0891','total_towers'=>10,'floors_per_tower'=>'14','latitude'=>30.6575,'longitude'=>76.8235,'amenities'=>'Infinity Pool,Gymnasium,Yoga Deck,Kids Pool,Spa,EV Charging,Tennis Court,24x7 Security','nearby_schools'=>'DPS (2 km)','nearby_hospitals'=>'Grecian Hospital (4 km)','metro_distance'=>'4 km from Airport','is_featured'=>true,'description'=>'Green Lotus Abhiraj Heights — under-construction luxury project on Airport Road with premium amenities.'],
            ],
            'info@gbpgroup.in' => [
                ['title'=>'GBP Athens','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Ambala Highway, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>480,'available_units'=>28,'price_from'=>3100000,'price_to'=>6500000,'possession_date'=>'2020-12-31','rera_id'=>'PBRERA-SAS79-PR0305','total_towers'=>16,'floors_per_tower'=>'12','latitude'=>30.6619,'longitude'=>76.8437,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Yoga Deck,Indoor Games','nearby_schools'=>'Innocent Hearts (2.5 km)','nearby_hospitals'=>'Mukat Hospital (4 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>true,'description'=>'GBP Athens on Ambala Highway is an award-winning residential project offering 2, 3 & 4 BHK apartments with Mediterranean-inspired design.'],
                ['title'=>'GBP Camellia','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Patiala Highway, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>336,'available_units'=>20,'price_from'=>2800000,'price_to'=>5800000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR0306','total_towers'=>12,'floors_per_tower'=>'10','latitude'=>30.6412,'longitude'=>76.8132,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Jogging Track,Kids Play Area,CCTV','nearby_schools'=>'St. Mary\'s (1 km)','nearby_hospitals'=>'Civil Hospital (3 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'GBP Camellia on Patiala Highway is an established residential project near Dhakoli with easy access to VIP Road and NH-7.'],
            ],
            'info@sarvottam.in' => [
                ['title'=>'Sarvottam Homes','project_type'=>'Residential','status'=>'Ready to Move','address'=>'VIP Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>288,'available_units'=>15,'price_from'=>2900000,'price_to'=>5600000,'possession_date'=>'2020-03-31','rera_id'=>'PBRERA-SAS79-PR0630','total_towers'=>10,'floors_per_tower'=>'10','latitude'=>30.6618,'longitude'=>76.8298,'amenities'=>'Gymnasium,Power Backup,24x7 Security,Kids Play Area,CCTV,Car Parking,Jogging Track','nearby_schools'=>'Ryan International (1.5 km)','nearby_hospitals'=>'Max Hospital (5 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Sarvottam Homes on VIP Road, Zirakpur — a well-planned residential colony with 2 & 3 BHK apartments at competitive prices.'],
                ['title'=>'Sarvottam Garden Baltana','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Baltana, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>192,'available_units'=>8,'price_from'=>2400000,'price_to'=>4500000,'possession_date'=>'2019-06-30','rera_id'=>'PBRERA-SAS79-PR0631','total_towers'=>8,'floors_per_tower'=>'8','latitude'=>30.6358,'longitude'=>76.8058,'amenities'=>'Gym,Security,Power Backup,CCTV,Car Parking,Visitor Parking','nearby_schools'=>'Modern School (2 km)','nearby_hospitals'=>'Civil Hospital (4 km)','metro_distance'=>'8 km from Chandigarh','is_featured'=>false,'description'=>'Sarvottam Garden in Baltana is an affordable housing project near Dhakoli offering 2 BHK flats at budget-friendly prices.'],
            ],
            'info@tricityheights.com' => [
                ['title'=>'Tricity Heights','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Airport Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>240,'available_units'=>16,'price_from'=>3500000,'price_to'=>7000000,'possession_date'=>'2022-03-31','rera_id'=>'PBRERA-SAS79-PR1500','total_towers'=>8,'floors_per_tower'=>'14','latitude'=>30.6571,'longitude'=>76.8229,'amenities'=>'Rooftop Pool,Gymnasium,Power Backup,24x7 Security,Kids Play Area,Jogging Track,EV Charging','nearby_schools'=>'Chandigarh Group of Colleges (1 km)','nearby_hospitals'=>'Grecian Hospital (4 km)','metro_distance'=>'4 km from Airport','is_featured'=>true,'description'=>'Tricity Heights on Airport Road, Zirakpur — panoramic Shivalik-view high-rises with modern amenities and excellent connectivity.'],
            ],
            'info@manglambuilders.in' => [
                ['title'=>'Manglam Heights','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Peer Mushalla, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>168,'available_units'=>10,'price_from'=>2900000,'price_to'=>5500000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR0720','total_towers'=>6,'floors_per_tower'=>'9','latitude'=>30.6531,'longitude'=>76.8291,'amenities'=>'Gymnasium,24x7 Security,Power Backup,Lift,Kids Play Area,CCTV','nearby_schools'=>'Innocent Hearts (2 km)','nearby_hospitals'=>'Mukat Hospital (3.5 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Manglam Heights at Peer Mushalla, Zirakpur — quality 2 & 3 BHK apartments with essential amenities at reasonable prices.'],
            ],
            'info@altusinfratech.com' => [
                ['title'=>'Altus Space Towers','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'Airport Road, Zirakpur','city'=>'Zirakpur','state'=>'Punjab','total_units'=>320,'available_units'=>40,'price_from'=>4000000,'price_to'=>12000000,'possession_date'=>'2023-06-30','rera_id'=>'PBRERA-SAS79-PR1620','total_towers'=>4,'floors_per_tower'=>'20','latitude'=>30.6569,'longitude'=>76.8228,'amenities'=>'Co-Working Space,Sky Lounge,Gymnasium,Swimming Pool,EV Charging,24x7 Security,Concierge,High-Speed Elevators','nearby_schools'=>'DPS (2 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'4 km from Airport','is_featured'=>true,'description'=>'Altus Space Towers is Zirakpur\'s pioneering mixed-use development — residential + commercial with sky lounge and co-working spaces.'],
            ],
            'info@himalayabuildcon.in' => [
                ['title'=>'Himalaya Builder Floors Sector 12','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 12, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>80,'available_units'=>5,'price_from'=>4500000,'price_to'=>8500000,'possession_date'=>'2020-06-30','rera_id'=>'HRERA-PKL-2001-0050','total_towers'=>20,'floors_per_tower'=>'4','latitude'=>30.7040,'longitude'=>76.8560,'amenities'=>'Parking,Security,Power Backup,CCTV,Lift','nearby_schools'=>'DAV School (0.5 km)','nearby_hospitals'=>'PGI Chandigarh (5 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>false,'description'=>'Himalaya Builder Floors in Sector 12, Panchkula — quality independent floors with excellent social infrastructure.'],
                ['title'=>'Himalaya Homes Sector 20','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 20, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>120,'available_units'=>8,'price_from'=>5500000,'price_to'=>10000000,'possession_date'=>'2022-03-31','rera_id'=>'HRERA-PKL-2001-0051','total_towers'=>30,'floors_per_tower'=>'4','latitude'=>30.7102,'longitude'=>76.8612,'amenities'=>'Parking,Security,Power Backup,CCTV,Terrace Garden','nearby_schools'=>'St. John\'s (0.5 km)','nearby_hospitals'=>'Alchemist Hospital (3 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'Himalaya Homes in Sector 20, Panchkula — premium independent floors in a well-established sector.'],
            ],
            'info@navrajgroup.in' => [
                ['title'=>'Navraj The Antalyas','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 20, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>360,'available_units'=>20,'price_from'=>5000000,'price_to'=>12000000,'possession_date'=>'2021-12-31','rera_id'=>'HRERA-PKL-2009-0120','total_towers'=>12,'floors_per_tower'=>'12','latitude'=>30.7098,'longitude'=>76.8608,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Kids Play Area,Tennis Court,Jogging Track,Spa','nearby_schools'=>'St. John\'s High School (0.5 km)','nearby_hospitals'=>'Alchemist Hospital (2 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>true,'description'=>'Navraj The Antalyas in Sector 20, Panchkula — one of the most prestigious addresses in Tricity, offering luxury 3 & 4 BHK residences.'],
                ['title'=>'Navraj Plots Sector 25','project_type'=>'Plotted','status'=>'Ready to Move','address'=>'Sector 25, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>200,'available_units'=>15,'price_from'=>6000000,'price_to'=>20000000,'possession_date'=>'2022-06-30','rera_id'=>'HRERA-PKL-2009-0121','total_towers'=>null,'floors_per_tower'=>null,'latitude'=>30.7162,'longitude'=>76.8662,'amenities'=>'Gated Society,24x7 Security,Park,Jogging Track,Club Membership','nearby_schools'=>'DAV Sector 8 (2 km)','nearby_hospitals'=>'PGI (6 km)','metro_distance'=>'7 km from Chandigarh','is_featured'=>true,'description'=>'Navraj Plots in Sector 25, Panchkula — premium plotted development in the heart of the well-planned Panchkula sector grid.'],
            ],
            'info@rashirealestate.in' => [
                ['title'=>'Rashi Sapphire','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 14, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>192,'available_units'=>12,'price_from'=>4800000,'price_to'=>9500000,'possession_date'=>'2021-06-30','rera_id'=>'HRERA-PKL-2005-0080','total_towers'=>8,'floors_per_tower'=>'10','latitude'=>30.7047,'longitude'=>76.8638,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Jogging Track,Kids Play Area','nearby_schools'=>'Vivek High School (1 km)','nearby_hospitals'=>'Alchemist Hospital (2 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'Rashi Sapphire in Sector 14, Panchkula — premium 3 & 4 BHK apartments in Panchkula\'s most sought-after sector.'],
                ['title'=>'Rashi Pearl Residency','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 18, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>144,'available_units'=>8,'price_from'=>4200000,'price_to'=>8500000,'possession_date'=>'2020-03-31','rera_id'=>'HRERA-PKL-2005-0081','total_towers'=>6,'floors_per_tower'=>'9','latitude'=>30.7086,'longitude'=>76.8596,'amenities'=>'Gymnasium,24x7 Security,Power Backup,CCTV,Lift,Car Parking,Visitor Parking','nearby_schools'=>'DAV School (1 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'Rashi Pearl Residency in Sector 18, Panchkula — quality 3 BHK apartments with premium finishes and convenient location.'],
            ],
            'info@tdiinfrastructure.in' => [
                ['title'=>'TDI Rosewood City','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 14, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>800,'available_units'=>30,'price_from'=>4500000,'price_to'=>15000000,'possession_date'=>'2019-12-31','rera_id'=>'HRERA-PKL-1998-0020','total_towers'=>20,'floors_per_tower'=>'14','latitude'=>30.7049,'longitude'=>76.8640,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Tennis Court,Basketball Court,Kids Play Area,Jogging Track,Spa,Indoor Games','nearby_schools'=>'Vivek High School (1 km)','nearby_hospitals'=>'Alchemist Hospital (2 km)','metro_distance'=>'5 km from Chandigarh','is_featured'=>true,'description'=>'TDI Rosewood City in Sector 14 Panchkula — prestigious 2, 3 & 4 BHK luxury residences in Tricity\'s most sought-after development.'],
            ],
            'info@omkarbuilders.in' => [
                ['title'=>'Omkar Residency Sector 19','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 19, Panchkula','city'=>'Panchkula','state'=>'Haryana','total_units'=>120,'available_units'=>6,'price_from'=>3800000,'price_to'=>7500000,'possession_date'=>'2020-09-30','rera_id'=>'HRERA-PKL-2003-0060','total_towers'=>6,'floors_per_tower'=>'8','latitude'=>30.7096,'longitude'=>76.8606,'amenities'=>'Gymnasium,Security,Power Backup,CCTV,Lift,Kids Play Area,Community Hall','nearby_schools'=>'St. John\'s (1 km)','nearby_hospitals'=>'Civil Hospital (2 km)','metro_distance'=>'6 km from Chandigarh','is_featured'=>false,'description'=>'Omkar Residency in Sector 19, Panchkula — well-designed 2 & 3 BHK apartments with Vastu-compliant layout.'],
            ],
            'info@jlplgroup.com' => [
                ['title'=>'JLPL Falcon View','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 66A, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>1800,'available_units'=>50,'price_from'=>3500000,'price_to'=>9000000,'possession_date'=>'2020-06-30','rera_id'=>'PBRERA-SAS79-PR0480','total_towers'=>36,'floors_per_tower'=>'14','latitude'=>30.6995,'longitude'=>76.6920,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Tennis Court,Jogging Track,Kids Play Area,Badminton Court,Indoor Games','nearby_schools'=>'Delhi Public School (1 km)','nearby_hospitals'=>'Fortis Hospital (3 km)','metro_distance'=>'2 km from IT Park','is_featured'=>true,'description'=>'JLPL Falcon View in Sector 66A, Mohali — one of Mohali\'s largest gated communities with 1800+ units and world-class amenities.'],
                ['title'=>'JLPL Township Sector 90','project_type'=>'Mixed Use','status'=>'Under Construction','address'=>'Sector 90-91, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>3000,'available_units'=>1200,'price_from'=>2800000,'price_to'=>12000000,'possession_date'=>'2027-12-31','rera_id'=>'PBRERA-SAS79-PR0481','total_towers'=>60,'floors_per_tower'=>'16','latitude'=>30.7198,'longitude'=>76.7098,'amenities'=>'Multi-Sports Complex,Commercial Hub,School,Hospital,Hotel,Park,24x7 Security,EV Charging','nearby_schools'=>'Proposed school on-site','nearby_hospitals'=>'Proposed hospital on-site','metro_distance'=>'Proposed metro connectivity','is_featured'=>true,'description'=>'JLPL Township in Sectors 90-91, Mohali — integrated self-sustaining township spanning 1000+ acres.'],
            ],
            'tricitysales@godrejproperties.com' => [
                ['title'=>'Godrej Woods Mohali','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 85, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>600,'available_units'=>20,'price_from'=>5000000,'price_to'=>14000000,'possession_date'=>'2022-12-31','rera_id'=>'PBRERA-SAS79-PR0290','total_towers'=>12,'floors_per_tower'=>'18','latitude'=>30.7182,'longitude'=>76.7092,'amenities'=>'Infinity Pool,Gymnasium,Yoga Deck,Kids Zone,Concierge,EV Charging,Tennis Court,Squash Court,24x7 Security,Spa','nearby_schools'=>'Chandigarh Group of Colleges (2 km)','nearby_hospitals'=>'Fortis Hospital (3 km)','metro_distance'=>'3 km from IT Park','is_featured'=>true,'description'=>'Godrej Woods in Sector 85, Mohali — premium high-rise residences surrounded by 2 acres of curated green forest.'],
                ['title'=>'Godrej Evoq Mohali','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 91, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>480,'available_units'=>280,'price_from'=>6500000,'price_to'=>18000000,'possession_date'=>'2026-09-30','rera_id'=>'PBRERA-SAS79-PR0291','total_towers'=>8,'floors_per_tower'=>'22','latitude'=>30.7202,'longitude'=>76.7102,'amenities'=>'Sky Pool,Private Terrace,Gymnasium,Co-Working Lounge,EV Charging,Concierge,24x7 Security,Spa,Sports Arena','nearby_schools'=>'DPS (2 km)','nearby_hospitals'=>'Max Hospital (4 km)','metro_distance'=>'3 km from IT Park','is_featured'=>true,'description'=>'Godrej Evoq in Sector 91 — ultra-luxury sky residences in Mohali with private terraces and sky pools.'],
            ],
            'tricitysales@emaarpacific.com' => [
                ['title'=>'Emaar The Views Mohali','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 105, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>720,'available_units'=>25,'price_from'=>5500000,'price_to'=>16000000,'possession_date'=>'2022-06-30','rera_id'=>'PBRERA-SAS79-PR0540','total_towers'=>16,'floors_per_tower'=>'20','latitude'=>30.7822,'longitude'=>76.6982,'amenities'=>'Dubai-Standard Amenities,Infinity Pool,Gymnasium,Yoga Deck,Spa,24x7 Security,EV Charging,Tennis Court,Squash Court,Party Lawn','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Fortis (5 km)','metro_distance'=>'5 km from ISBT Chandigarh','is_featured'=>true,'description'=>'Emaar The Views in Sector 105 — Dubai-standard luxury towers with breathtaking Shivalik views.'],
            ],
            'info@gillcobuilders.com' => [
                ['title'=>'Gillco Parkhills','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 126, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>1200,'available_units'=>40,'price_from'=>4200000,'price_to'=>10000000,'possession_date'=>'2021-09-30','rera_id'=>'PBRERA-SAS79-PR0560','total_towers'=>24,'floors_per_tower'=>'15','latitude'=>30.7559,'longitude'=>76.7344,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Tennis Court,Kids Play Area,Jogging Track,Indoor Games,Community Hall','nearby_schools'=>'DPS (1 km)','nearby_hospitals'=>'Fortis Mohali (4 km)','metro_distance'=>'4 km from IT Park','is_featured'=>true,'description'=>'Gillco Parkhills in Sector 126 is a large gated township with 1200+ units and a dedicated 5-acre park zone.'],
                ['title'=>'Gillco Valley','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 127, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>800,'available_units'=>450,'price_from'=>4800000,'price_to'=>12000000,'possession_date'=>'2027-03-31','rera_id'=>'PBRERA-SAS79-PR0561','total_towers'=>16,'floors_per_tower'=>'18','latitude'=>30.7555,'longitude'=>76.7342,'amenities'=>'Infinity Pool,Gymnasium,Yoga Deck,Sky Lounge,EV Charging,24x7 Security,Concierge,Badminton Court','nearby_schools'=>'Chandigarh University (3 km)','nearby_hospitals'=>'Fortis (5 km)','metro_distance'=>'4 km from IT Park','is_featured'=>true,'description'=>'Gillco Valley — the premium evolution of Gillco Parkhills with sky lounges and smart home features.'],
            ],
            'tricitysales@waveinfra.com' => [
                ['title'=>'Wave Estate Sector 85','project_type'=>'Mixed Use','status'=>'Ready to Move','address'=>'Sector 85, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>5000,'available_units'=>200,'price_from'=>2500000,'price_to'=>20000000,'possession_date'=>'2019-12-31','rera_id'=>'PBRERA-SAS79-PR0350','total_towers'=>80,'floors_per_tower'=>'14','latitude'=>30.7165,'longitude'=>76.7063,'amenities'=>'Multi-Club,Sports Academy,Shopping Mall,Hotel,Hospital,School,Swimming Pool,24x7 Security,EV Charging','nearby_schools'=>'Wave School (on-site)','nearby_hospitals'=>'Wave Hospital (on-site)','metro_distance'=>'2 km from IT Park','is_featured'=>true,'description'=>'Wave Estate is Mohali\'s most ambitious integrated township — a 450-acre self-sustaining community.'],
            ],
            'info@parasbuildtech.com' => [
                ['title'=>'Paras Panorama Sector 76','project_type'=>'Residential','status'=>'Ready to Move','address'=>'Sector 76, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>432,'available_units'=>22,'price_from'=>4000000,'price_to'=>9000000,'possession_date'=>'2021-06-30','rera_id'=>'PBRERA-SAS79-PR0480','total_towers'=>12,'floors_per_tower'=>'14','latitude'=>30.7072,'longitude'=>76.7262,'amenities'=>'Swimming Pool,Gymnasium,Clubhouse,24x7 Security,Power Backup,Tennis Court,Kids Play Area,Jogging Track','nearby_schools'=>'DPS (1.5 km)','nearby_hospitals'=>'Fortis (3 km)','metro_distance'=>'3 km from IT Park','is_featured'=>true,'description'=>'Paras Panorama in Sector 76, Mohali — a well-established residential complex known for quality construction and green living.'],
                ['title'=>'Paras The Manor Sector 115','project_type'=>'Residential','status'=>'Under Construction','address'=>'Sector 115, Mohali','city'=>'Mohali','state'=>'Punjab','total_units'=>600,'available_units'=>350,'price_from'=>4500000,'price_to'=>11000000,'possession_date'=>'2026-12-31','rera_id'=>'PBRERA-SAS79-PR0481','total_towers'=>15,'floors_per_tower'=>'16','latitude'=>30.7532,'longitude'=>76.7112,'amenities'=>'Infinity Pool,Gymnasium,Spa,Tennis Court,Sky Lounge,EV Charging,24x7 Security,Co-Working Space','nearby_schools'=>'Chandigarh Group of Colleges (2 km)','nearby_hospitals'=>'Fortis (4 km)','metro_distance'=>'5 km from IT Park','is_featured'=>true,'description'=>'Paras The Manor in Sector 115, Mohali — luxury high-rises with panoramic Shivalik views and resort-style amenities.'],
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

            // Insert projects for this builder
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
        $this->command->info('    ✔ 25 builders + projects seeded.');
    }

    // =========================================================================
    // PROPERTIES  (320 records, nearest first)
    // =========================================================================
    private function seedProperties(): void
    {
        $this->command->info('  → Seeding 320 proximity-ordered properties...');

        $dealerIds = DB::table('property_dealers')->pluck('id')->toArray();
        if (empty($dealerIds)) {
            $this->command->error('No dealers found!');
            return;
        }

        // Amenity pool
        $amenityPool = [
            'Swimming Pool,Gymnasium,24x7 Security,Power Backup,Kids Play Area,Jogging Track,Clubhouse',
            'Park,Kids Play Area,Security,Power Backup,Car Parking,CCTV',
            'Gymnasium,24x7 Security,Power Backup,Lift,Intercom,CCTV',
            'Clubhouse,Swimming Pool,Kids Play Area,Jogging Track,Power Backup,Security',
            'Car Parking,CCTV,Security,Power Backup,Intercom,Visitor Parking',
            'Terrace Garden,Gymnasium,Swimming Pool,Yoga Deck,Spa,24x7 Security,EV Charging',
            'Community Hall,Kids Play Area,Power Backup,Security,Water Supply,Lift',
            'Sports Facility,Indoor Games,Gymnasium,Swimming Pool,24x7 Security,CCTV',
        ];
        $furnishings = ['Furnished','Semi-Furnished','Unfurnished','Semi-Furnished','Unfurnished'];
        $facings     = ['North','South','East','West','North-East','North-West','South-East'];
        $propAges    = ['Under Construction','0-1 Year','1-3 Years','3-5 Years','5-10 Years'];

        $pick = fn($arr) => $arr[array_rand($arr)];

        /**
         * Proximity zones — ordered nearest to farthest from Srishti Avenue, Dhakoli (30.6400, 76.8190)
         * Each zone: [label, dist_km, lat_centre, lng_centre, lat_jitter, lng_jitter, localities]
         */
        $zones = [

            // ── RING 1 : 0–0.5 km ───────────────────────────────────────────
            [
                'label'     => 'Srishti Avenue, Dhakoli (0–0.5 km)',
                'dist'      => 0.2,
                'lat'       => 30.6400,
                'lng'       => 76.8190,
                'jitter'    => 0.0010,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'Srishti Avenue',
                'societies' => ['Srishti Avenue Phase 1','Srishti Avenue Phase 2','Srishti Avenue Residency','Dhakoli Green Enclave','Dhakoli Village Heights'],
                'landmark'  => 'Near Srishti Avenue Chowk',
                'count'     => 30,
            ],

            // ── RING 2 : 0.5–1 km ──────────────────────────────────────────
            [
                'label'     => 'VIP Road near Dhakoli Gate (0.5–1 km)',
                'dist'      => 0.6,
                'lat'       => 30.6455,
                'lng'       => 76.8185,
                'jitter'    => 0.0020,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'VIP Road',
                'societies' => ['NK Savitry Greens 2','Mona Greens','Rameshwar Heights','Surya Heights','Dhakoli VIP Enclave'],
                'landmark'  => 'Near VIP Road Dhakoli Gate',
                'count'     => 30,
            ],
            [
                'label'     => 'Patiala Highway near Dhakoli (0.5–1 km)',
                'dist'      => 0.7,
                'lat'       => 30.6410,
                'lng'       => 76.8130,
                'jitter'    => 0.0015,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'Patiala Highway',
                'societies' => ['Green Valley Township','GBP Camellia','Air Force Housing','Krishna Greens','Shivalik Residency'],
                'landmark'  => 'Near Patiala Highway Dhakoli Turn',
                'count'     => 25,
            ],

            // ── RING 3 : 1–2 km ────────────────────────────────────────────
            [
                'label'     => 'Lohgarh Road (1–1.1 km)',
                'dist'      => 1.1,
                'lat'       => 30.6488,
                'lng'       => 76.8228,
                'jitter'    => 0.0020,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'Lohgarh Road',
                'societies' => ['Maya Garden City','Mayfair Greens','Green Fields','Surya Apartments','Royal Residency Lohgarh'],
                'landmark'  => 'Near Lohgarh Chowk',
                'count'     => 30,
            ],
            [
                'label'     => 'Gazipur Road (1.2 km)',
                'dist'      => 1.2,
                'lat'       => 30.6500,
                'lng'       => 76.8235,
                'jitter'    => 0.0020,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'Gazipur Road',
                'societies' => ['NK Savitry Greens 2','Mona Greens 2','Krishna Apartments','Jai Durga Green Homes','Sunrise Apartments Gazipur'],
                'landmark'  => 'Near Gazipur Chowk',
                'count'     => 25,
            ],
            [
                'label'     => 'Baltana (1.4 km)',
                'dist'      => 1.4,
                'lat'       => 30.6355,
                'lng'       => 76.8055,
                'jitter'    => 0.0025,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140604',
                'locality'  => 'Baltana',
                'societies' => ['Sarvottam Garden','Tricity Green Enclave','PD Residency','Baltana Green Homes','New Horizon Baltana'],
                'landmark'  => 'Near Baltana Chowk',
                'count'     => 25,
            ],

            // ── RING 4 : 2–3 km ────────────────────────────────────────────
            [
                'label'     => 'Peer Mushalla (1.7 km)',
                'dist'      => 1.7,
                'lat'       => 30.6530,
                'lng'       => 76.8290,
                'jitter'    => 0.0025,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'Peer Mushalla',
                'societies' => ['Savitry Green Avenue','RM Royale Residency','Manglam Heights','Peer Mushalla Greens','Zion Heights'],
                'landmark'  => 'Near Peer Mushalla Chowk',
                'count'     => 30,
            ],
            [
                'label'     => 'Airport Road (1.9 km)',
                'dist'      => 1.9,
                'lat'       => 30.6570,
                'lng'       => 76.8230,
                'jitter'    => 0.0025,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'Airport Road',
                'societies' => ['Sushma Grande NXT','Green Lotus Saksham','Altus Space Towers','Tricity Heights','GBP Athens Airport'],
                'landmark'  => 'Near Chandigarh Airport Road',
                'count'     => 25,
            ],

            // ── RING 5 : 3–5 km ────────────────────────────────────────────
            [
                'label'     => 'VIP Road Centre (2.2 km)',
                'dist'      => 2.2,
                'lat'       => 30.6618,
                'lng'       => 76.8300,
                'jitter'    => 0.0030,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140603',
                'locality'  => 'VIP Road',
                'societies' => ['SBP City of Dreams','Maya Garden City Phase 2','NK Savitry Greens','Mona Greens Phase 3','Sushma Crescent'],
                'landmark'  => 'Near VIP Road Zirakpur Centre',
                'count'     => 30,
            ],
            [
                'label'     => 'Ambala Highway (2.8 km)',
                'dist'      => 2.8,
                'lat'       => 30.6618,
                'lng'       => 76.8438,
                'jitter'    => 0.0030,
                'state'     => 'Punjab',
                'city'      => 'Zirakpur',
                'pincode'   => '140604',
                'locality'  => 'Ambala Highway',
                'societies' => ['Motia Royal Citi','GBP Athens','Savitry Heights','Jai Durga Apartments','Sunrise Valley Highway'],
                'landmark'  => 'Near Ambala Highway Junction',
                'count'     => 20,
            ],

            // ── RING 6 : 5+ km ─────────────────────────────────────────────
            [
                'label'     => 'Panchkula Sector 20 (5.5 km)',
                'dist'      => 5.5,
                'lat'       => 30.7100,
                'lng'       => 76.8610,
                'jitter'    => 0.0030,
                'state'     => 'Haryana',
                'city'      => 'Panchkula',
                'pincode'   => '134112',
                'locality'  => 'Sector 20',
                'societies' => ['Navraj The Antalyas','Surya Residency','Himalaya Homes','HUDA Sector 20','Green Valley Panchkula'],
                'landmark'  => 'Near Panchkula Sector 20',
                'count'     => 20,
            ],
            [
                'label'     => 'Panchkula Sector 10 (6.5 km)',
                'dist'      => 6.5,
                'lat'       => 30.7040,
                'lng'       => 76.8550,
                'jitter'    => 0.0030,
                'state'     => 'Haryana',
                'city'      => 'Panchkula',
                'pincode'   => '134113',
                'locality'  => 'Sector 10',
                'societies' => ['Sector 10 Houses','Type 4 Quarters HUDA','Panchkula Residency','HUDA Sector 10','Indira Colony Panchkula'],
                'landmark'  => 'Near Panchkula Sector 10',
                'count'     => 15,
            ],
            [
                'label'     => 'Mohali Aerocity (6 km)',
                'dist'      => 6.0,
                'lat'       => 30.6780,
                'lng'       => 76.7350,
                'jitter'    => 0.0035,
                'state'     => 'Punjab',
                'city'      => 'Mohali',
                'pincode'   => '160059',
                'locality'  => 'Aerocity',
                'societies' => ['GMADA Aerocity Plot','IT City Mohali','Airport Zone Apartments','Aerocity Commercial Hub','Sky View Residency'],
                'landmark'  => 'Near Mohali International Airport',
                'count'     => 15,
            ],
        ];

        $propTypes = ['Apartment','Builder Floor','Independent Floor','Villa','Plot','Penthouse','Studio Apartment','Shop','Office Space'];
        $lookingForPool = ['Sale','Sale','Sale','Rent','Sale'];

        $totalInserted = 0;

        foreach ($zones as $zone) {
            $this->command->info("    Zone: {$zone['label']} — {$zone['count']} properties");

            for ($i = 0; $i < $zone['count']; $i++) {
                $dealerId = $pick($dealerIds);
                $ptype    = $pick($propTypes);
                $lfor     = $pick($lookingForPool);
                $society  = $pick($zone['societies']);
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

                $totalFloors  = in_array($ptype, ['Villa','Plot']) ? null : rand(4, 20);
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
    // HELPERS
    // =========================================================================

    private function getConfig(string $ptype, string $city, string $lfor): array
    {
        $multiplier = match($city) {
            'Chandigarh' => 1.5,
            'Mohali'     => 1.2,
            'Panchkula'  => 1.1,
            default      => 1.0, // Zirakpur
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
                $area = rand(100, 500) * 9; // square yards to sqft approx
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
            'Mohali'     => 14000,
            'Panchkula'  => 12000,
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
