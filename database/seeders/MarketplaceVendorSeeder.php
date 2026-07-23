<?php

namespace Database\Seeders;

use App\Models\MarketplaceVendor;
use Illuminate\Database\Seeder;

/**
 * Seeds 7 representative Tricity-area home-marketplace vendors.
 *
 * Real product data uses these as the seller for MarketplaceProduct rows
 * (MarketplaceProductSeeder). Mix of is_verified values so the
 * "verified" tick in the card shows up on some products and not others.
 *
 * Safe to re-run — upserts on slug.
 */
class MarketplaceVendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'business_name'    => 'Chandigarh Lighting Co.',
                'owner_name'       => 'Rakesh Verma',
                'phone'            => '9876500101',
                'email'            => 'hello@chandigarhlights.example.in',
                'city'             => 'Chandigarh',
                'area'             => 'Sector 17',
                'address'          => 'Shop 24, Sector 17 Market, Chandigarh',
                'description'      => 'Decorative and architectural lighting specialists serving Chandigarh since 2009. Ceiling fans, chandeliers, LED panels and outdoor lights.',
                'years_in_business' => 15,
                'is_verified'      => true,
            ],
            [
                'business_name'    => 'Tricity Modular Kitchens',
                'owner_name'       => 'Anita Sharma',
                'phone'            => '9876500202',
                'email'            => 'sales@tricitymodular.example.in',
                'city'             => 'Mohali',
                'area'             => 'Phase 7',
                'address'          => 'SCF 18, Phase 7, Mohali',
                'description'      => 'Custom modular kitchens, wardrobes and storage solutions. Free in-home design consultation across Mohali, Chandigarh and Zirakpur.',
                'years_in_business' => 11,
                'is_verified'      => true,
            ],
            [
                'business_name'    => 'Zirakpur Furniture House',
                'owner_name'       => 'Harpreet Singh',
                'phone'            => '9876500303',
                'email'            => 'store@zfurniture.example.in',
                'city'             => 'Zirakpur',
                'area'             => 'Main Market',
                'address'          => 'Near Shiv Mandir, Main Market, Zirakpur',
                'description'      => 'Solid-wood sofas, beds and dining sets. Direct factory pricing with 5-year warranty on frame and foam.',
                'years_in_business' => 8,
                'is_verified'      => true,
            ],
            [
                'business_name'    => 'Panchkula Bath Studio',
                'owner_name'       => 'Ritu Aggarwal',
                'phone'            => '9876500404',
                'email'            => 'showroom@bathstudio.example.in',
                'city'             => 'Panchkula',
                'area'             => 'Sector 5',
                'address'          => 'SCO 88, Sector 5, Panchkula',
                'description'      => 'Premium bathroom fittings, tiles, sanitaryware and shower enclosures. Authorised dealer for Jaquar, Hindware and Cera.',
                'years_in_business' => 6,
                'is_verified'      => false,
            ],
            [
                'business_name'    => 'Decor Studio Mohali',
                'owner_name'       => 'Mehak Kapoor',
                'phone'            => '9876500505',
                'email'            => 'hello@decorstudio.example.in',
                'city'             => 'Mohali',
                'area'             => 'Phase 5',
                'address'          => 'Booth 142, Phase 5 Market, Mohali',
                'description'      => 'Wall art, indoor plants, custom curtains and home accents. In-home styling consults available on request.',
                'years_in_business' => 4,
                'is_verified'      => true,
            ],
            [
                'business_name'    => 'Sahibzada Paint & Hardware',
                'owner_name'       => 'Manjit Singh Sahibzada',
                'phone'            => '9876500606',
                'email'            => 'orders@spaints.example.in',
                'city'             => 'Chandigarh',
                'area'             => 'Sector 26',
                'address'          => 'Shop 5, Sector 26, Chandigarh',
                'description'      => 'Asian Paints, Berger, Nerolac — interior and exterior paints, primers and waterproofing. Power tools, plumbing and electrical hardware also in stock.',
                'years_in_business' => 20,
                'is_verified'      => true,
            ],
            [
                'business_name'    => 'Smart Tricity Automation',
                'owner_name'       => 'Vikram Bansal',
                'phone'            => '9876500707',
                'email'            => 'support@smarttricity.example.in',
                'city'             => 'Panchkula',
                'area'             => 'Sector 20',
                'address'          => 'SCO 211, Sector 20, Panchkula',
                'description'      => 'Smart locks, video doorbells, security cameras and whole-home automation. Installation and AMC support across the Tricity.',
                'years_in_business' => 5,
                'is_verified'      => false,
            ],
        ];

        foreach ($vendors as $vendor) {
            $vendor['is_active'] = true;
            $vendor['commission_pct'] = 8.00;

            // Pre-compute the slug so updateOrCreate has a stable lookup key
            // (the model's booted() hook also auto-generates this from
            // business_name if we leave it blank).
            $slug = \Illuminate\Support\Str::slug($vendor['business_name']);

            MarketplaceVendor::updateOrCreate(
                ['slug' => $slug],
                $vendor
            );
        }

        $this->command?->info('Marketplace vendors: ' . count($vendors) . ' upserted.');
    }
}
