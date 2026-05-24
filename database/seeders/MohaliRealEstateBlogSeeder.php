<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MohaliRealEstateBlogSeeder extends Seeder
{
    /**
     * Run the database seeds for Mohali Real Estate Blogs.
     *
     * @return void
     */
    public function run()
    {
        $blogData = [
            // Mohali Real Estate Blogs
            ['title' => 'Best Areas to Buy Property in Mohali in 2026', 'category' => 'Mohali Real Estate'],
            ['title' => 'Top Luxury Villas in Mohali for Families', 'category' => 'Mohali Real Estate'],
            ['title' => 'Is Aerocity Mohali a Good Investment in 2026?', 'category' => 'Mohali Real Estate'],
            ['title' => 'Best Residential Sectors in Mohali for Living', 'category' => 'Mohali Real Estate'],
            ['title' => 'Property Rates in Mohali – Area Wise Guide', 'category' => 'Mohali Real Estate'],
            ['title' => 'Flats vs Independent Houses in Mohali – Which is Better?', 'category' => 'Mohali Real Estate'],
            ['title' => 'Upcoming Real Estate Projects in Mohali', 'category' => 'Mohali Real Estate'],
            ['title' => 'Best Affordable Flats in Mohali Under ₹50 Lakhs', 'category' => 'Mohali Real Estate'],
            ['title' => 'Why Mohali is Becoming Punjab’s Real Estate Hub', 'category' => 'Mohali Real Estate'],
            ['title' => 'Best Commercial Property Locations in Mohali', 'category' => 'Mohali Real Estate'],

            // Chandigarh Region Blogs
            ['title' => 'Best Areas to Buy Property Near Chandigarh', 'category' => 'Chandigarh Region'],
            ['title' => 'Top Investment Hotspots Around Chandigarh', 'category' => 'Chandigarh Region'],
            ['title' => 'Luxury Apartments Near Chandigarh Airport', 'category' => 'Chandigarh Region'],
            ['title' => 'Mohali vs Chandigarh – Which is Better for Property Investment?', 'category' => 'Chandigarh Region'],
            ['title' => 'Best Family-Friendly Residential Areas Near Chandigarh', 'category' => 'Chandigarh Region'],

            // Zirakpur Blogs
            ['title' => 'Why Zirakpur is a Hotspot for Real Estate Investors', 'category' => 'Zirakpur'],
            ['title' => 'Best High-Rise Apartments in Zirakpur', 'category' => 'Zirakpur'],
            ['title' => 'Affordable Housing Projects in Zirakpur', 'category' => 'Zirakpur'],
            ['title' => 'Property Investment Guide for Zirakpur', 'category' => 'Zirakpur'],
            ['title' => 'Best Areas in Zirakpur for Rental Income', 'category' => 'Zirakpur'],

            // Investment-Focused Blogs
            ['title' => 'Best Cities in India for Real Estate Investment in 2026', 'category' => 'Real Estate Investment'],
            ['title' => 'Residential vs Commercial Property – Which Gives Better Returns?', 'category' => 'Real Estate Investment'],
            ['title' => 'How to Earn Rental Income from Property in India', 'category' => 'Real Estate Investment'],
            ['title' => 'Top Emerging Real Estate Markets in North India', 'category' => 'Real Estate Investment'],
            ['title' => 'Real Estate Investment Tips for Beginners', 'category' => 'Real Estate Investment'],
            ['title' => 'Best Property Investment Strategies in India', 'category' => 'Real Estate Investment'],
            ['title' => 'Should You Buy Plot or Flat in 2026?', 'category' => 'Real Estate Investment'],
            ['title' => 'How NRIs Can Invest in Indian Real Estate', 'category' => 'Real Estate Investment'],
            ['title' => 'Future of Indian Real Estate Market in 2026', 'category' => 'Real Estate Investment'],
            ['title' => 'How to Identify High-Growth Property Locations', 'category' => 'Real Estate Investment'],

            // Buyer Guide Blogs
            ['title' => 'First-Time Home Buyer Guide in India', 'category' => 'Buyer Guide'],
            ['title' => 'Important Documents to Check Before Buying Property', 'category' => 'Buyer Guide'],
            ['title' => 'Common Real Estate Scams in India and How to Avoid Them', 'category' => 'Buyer Guide'],
            ['title' => 'Home Loan Process Explained for Beginners', 'category' => 'Buyer Guide'],
            ['title' => 'Registry Process for Property in India', 'category' => 'Buyer Guide'],
            ['title' => 'What is CLU, RERA, Freehold & Leasehold Property?', 'category' => 'Buyer Guide'],
            ['title' => 'Checklist Before Buying a Flat in India', 'category' => 'Buyer Guide'],
            ['title' => 'Hidden Costs While Buying Property in India', 'category' => 'Buyer Guide'],
            ['title' => 'How to Verify Property Ownership Online', 'category' => 'Buyer Guide'],
            ['title' => 'Questions to Ask Before Buying Property', 'category' => 'Buyer Guide'],

            // Seller & Marketing Blogs
            ['title' => 'How to Sell Property Faster in India', 'category' => 'Seller Guide'],
            ['title' => 'Best Ways to Market Property Online', 'category' => 'Real Estate Marketing'],
            ['title' => 'How Good Photos Increase Property Sales', 'category' => 'Real Estate Marketing'],
            ['title' => 'Why Digital Marketing is Important for Builders', 'category' => 'Real Estate Marketing'],
            ['title' => 'Tips to Generate Real Estate Leads Online', 'category' => 'Real Estate Marketing'],
            ['title' => 'How Real Estate Portals Help Builders Sell Faster', 'category' => 'Real Estate Marketing'],
            ['title' => 'Best Real Estate Marketing Strategies in 2026', 'category' => 'Real Estate Marketing'],

            // SEO + Traffic Magnet Blogs
            ['title' => 'Top Real Estate Websites in India', 'category' => 'Real Estate Resources'],
            ['title' => 'Best Property Apps for Buyers in India', 'category' => 'Real Estate Resources'],
            ['title' => 'Real Estate Trends That Will Dominate 2026', 'category' => 'Market Trends'],
        ];

        foreach ($blogData as $index => $data) {
            $slug = Str::slug($data['title']);
            $titleWithSuffix = $data['title'] . ' | indianesthub.com';
            $imageNumber = ($index % 9) + 1;
            $featuredImage = "assets/img/blog/blog-post-{$imageNumber}.webp";
            
            DB::table('blog_posts')->updateOrInsert(
                ['slug' => $slug],
                [
                    'title' => $titleWithSuffix,
                    'excerpt' => 'Expert insights on ' . $data['title'] . '. Learn everything you need to know about the Indian real estate market in 2026.',
                    'content' => '<h3>' . $data['title'] . '</h3><p>The real estate landscape is shifting rapidly as we head into 2026. This comprehensive guide on ' . strtolower($data['title']) . ' explores the critical factors influencing properties today. From high-growth investment hotspots in Mohali and Zirakpur to detailed buyer guides for first-time owners, IndianEstHub is committed to providing verified, high-quality information. Stay ahead of the market with our expert analysis on current price trends, upcoming projects, and smart investment strategies tailored for the Chandigarh Tricity region.</p>',
                    'featured_image' => $featuredImage,
                    'meta_title' => $titleWithSuffix,
                    'meta_description' => 'Expert guide on ' . $data['title'] . '. Discover the latest price trends, investment opportunities, and property advice for 2026 on indianesthub.com.',
                    'status' => 'published',
                    'author_id' => 1,
                    'category' => $data['category'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}