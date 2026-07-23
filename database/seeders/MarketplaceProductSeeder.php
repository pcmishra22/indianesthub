<?php

namespace Database\Seeders;

use App\Models\MarketplaceCategory;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceVendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds ~36 representative Home Marketplace products spread across all
 * 8 categories, sourced from the 7 vendors in MarketplaceVendorSeeder.
 *
 * Each product row maps to a category-by-slug and a vendor-by-slug; rows
 * are idempotent (updateOrCreate on slug) so this is safe to re-run.
 *
 * Cover images point at pre-bundled public assets under
 * assets/img/real-estate/ — the model's getCoverUrlAttribute() recognises
 * the "assets/" prefix and skips the storage/ wrap for these.
 *
 * Prices are realistic Tricity ranges (₹ INR) and `bhk_fit` is a JSON
 * array of strings like ["2","3"] — null means "fits all BHKs".
 */
class MarketplaceProductSeeder extends Seeder
{
    public function run(): void
    {
        // Pre-resolve vendor and category IDs by slug so we don't query
        // inside the loop. Missing lookups fall through to first() as a
        // safety net for the dev environment.
        $vendorSlugs = [
            'chandigarh-lighting-co',
            'tricity-modular-kitchens',
            'zirakpur-furniture-house',
            'panchkula-bath-studio',
            'decor-studio-mohali',
            'sahibzada-paint-hardware',
            'smart-tricity-automation',
        ];

        $categorySlugs = [
            'curtains-blinds',
            'lights-fixtures',
            'furniture',
            'kitchen-products',
            'bathroom-fittings',
            'home-decor',
            'paint-hardware',
            'smart-home',
        ];

        $vendorIds = MarketplaceVendor::whereIn('slug', $vendorSlugs)
            ->pluck('id', 'slug');
        $categoryIds = MarketplaceCategory::whereIn('slug', $categorySlugs)
            ->pluck('id', 'slug');

        // Cycle through 8 interior images so every category gets variety
        // without repeating the same photo on every card.
        $coverImages = [
            'assets/img/real-estate/property-interior-1.webp',
            'assets/img/real-estate/property-interior-2.webp',
            'assets/img/real-estate/property-interior-4.webp',
            'assets/img/real-estate/property-interior-5.webp',
            'assets/img/real-estate/property-interior-6.webp',
            'assets/img/real-estate/property-interior-8.webp',
            'assets/img/real-estate/property-interior-9.webp',
            'assets/img/real-estate/property-interior-7.webp',
        ];

        $products = [
            // ─── Curtains & Blinds (5) ────────────────────────────────
            [
                'category' => 'curtains-blinds', 'vendor' => 'decor-studio-mohali',
                'name' => 'Eyelet Blackout Curtains',
                'description' => 'Triple-weave polyester blackout curtains with stainless-steel eyelets. Blocks ~95% sunlight and insulates against heat — a strong fit for west-facing bedrooms and AC rooms.',
                'bhk_fit' => ['1', '2', '3'], 'price_min' => 1200, 'price_max' => 4500,
                'price_unit' => 'per panel', 'tags' => ['blackout', 'eyelet', 'polyester', 'thermal'],
                'is_featured' => true, 'cover' => 0,
            ],
            [
                'category' => 'curtains-blinds', 'vendor' => 'decor-studio-mohali',
                'name' => 'Sheer Voile Curtains',
                'description' => 'Lightweight sheer voile for living and dining rooms. Filters harsh sunlight while keeping the view open. Machine-washable, anti-crease finish.',
                'bhk_fit' => ['1', '2', '3', '4'], 'price_min' => 600, 'price_max' => 1800,
                'price_unit' => 'per panel', 'tags' => ['sheer', 'voile', 'light-filter'],
                'is_featured' => false, 'cover' => 1,
            ],
            [
                'category' => 'curtains-blinds', 'vendor' => 'decor-studio-mohali',
                'name' => 'Roman Blinds (Custom-Sized)',
                'description' => 'Made-to-measure Roman blinds in cotton and linen blends. Cordless lift mechanism — child-safe. Free in-home measurement across Mohali and Chandigarh.',
                'bhk_fit' => null, 'price_min' => 2200, 'price_max' => 5800,
                'price_unit' => 'per window', 'tags' => ['roman', 'custom', 'cordless'],
                'is_featured' => true, 'cover' => 4,
            ],
            [
                'category' => 'curtains-blinds', 'vendor' => 'chandigarh-lighting-co',
                'name' => 'Motorised Curtain Tracks',
                'description' => 'Whisper-quiet motorised curtain track with remote and app control. Fits existing curtains — no rod change needed. Compatible with Alexa and Google Home.',
                'bhk_fit' => null, 'price_min' => 8500, 'price_max' => 14500,
                'price_unit' => 'per track (up to 4m)', 'tags' => ['motorised', 'smart', 'app-control'],
                'is_featured' => false, 'cover' => 5,
            ],
            [
                'category' => 'curtains-blinds', 'vendor' => 'decor-studio-mohali',
                'name' => 'Zebra Roller Blinds',
                'description' => 'Day-night zebra blinds alternating sheer and opaque fabric stripes. Adjust light and privacy in a single pull. Dust-resistant coated fabric.',
                'bhk_fit' => ['1', '2', '3'], 'price_min' => 1400, 'price_max' => 3600,
                'price_unit' => 'per window', 'tags' => ['zebra', 'roller', 'day-night'],
                'is_featured' => false, 'cover' => 7,
            ],

            // ─── Lights & Fixtures (6) ────────────────────────────────
            [
                'category' => 'lights-fixtures', 'vendor' => 'chandigarh-lighting-co',
                'name' => 'LED Ceiling Panel (18W)',
                'description' => 'Slim round LED ceiling panel with high-CRI diffuser — even light, no dark spots. 2-year replacement warranty. 18W replaces a 100W incandescent.',
                'bhk_fit' => null, 'price_min' => 650, 'price_max' => 1100,
                'price_unit' => 'per piece', 'tags' => ['led', 'ceiling', 'energy-efficient'],
                'is_featured' => true, 'cover' => 0,
            ],
            [
                'category' => 'lights-fixtures', 'vendor' => 'chandigarh-lighting-co',
                'name' => 'Decorative Chandelier (6-arm)',
                'description' => 'Brass-finish 6-arm chandelier with crystal drops. Height-adjustable drop rod. Best for dining rooms and double-height foyers.',
                'bhk_fit' => ['2', '3', '4'], 'price_min' => 4800, 'price_max' => 12500,
                'price_unit' => 'per piece', 'tags' => ['chandelier', 'decorative', 'dining'],
                'is_featured' => true, 'cover' => 2,
            ],
            [
                'category' => 'lights-fixtures', 'vendor' => 'chandigarh-lighting-co',
                'name' => 'Wall-Mounted Reading Light',
                'description' => 'Swing-arm wall sconce with warm-white LED. Ideal beside beds and study desks. Switch on base, no rewiring needed.',
                'bhk_fit' => null, 'price_min' => 950, 'price_max' => 2400,
                'price_unit' => 'per piece', 'tags' => ['wall-light', 'reading', 'bedside'],
                'is_featured' => false, 'cover' => 3,
            ],
            [
                'category' => 'lights-fixtures', 'vendor' => 'chandigarh-lighting-co',
                'name' => 'Outdoor LED Floodlight (50W)',
                'description' => 'IP65 weatherproof floodlight for driveways and building exteriors. Die-cast aluminium housing, 5000K daylight. 30,000-hour rated life.',
                'bhk_fit' => null, 'price_min' => 1800, 'price_max' => 3200,
                'price_unit' => 'per piece', 'tags' => ['outdoor', 'flood', 'ip65'],
                'is_featured' => false, 'cover' => 4,
            ],
            [
                'category' => 'lights-fixtures', 'vendor' => 'chandigarh-lighting-co',
                'name' => 'Designer Ceiling Fan (BLDC 28W)',
                'description' => '5-star rated BLDC motor ceiling fan with remote. 28W power consumption — saves ~₹1500/year vs conventional fans. Inverter-friendly.',
                'bhk_fit' => null, 'price_min' => 3200, 'price_max' => 5400,
                'price_unit' => 'per piece', 'tags' => ['fan', 'bldc', 'energy-saving'],
                'is_featured' => true, 'cover' => 5,
            ],
            [
                'category' => 'lights-fixtures', 'vendor' => 'smart-tricity-automation',
                'name' => 'Smart Downlight (RGBCW)',
                'description' => 'Wi-Fi enabled downlight with 16M colours, dimmable, and tunable white. Schedule via app, voice control via Alexa/Google.',
                'bhk_fit' => null, 'price_min' => 1100, 'price_max' => 1900,
                'price_unit' => 'per piece', 'tags' => ['smart', 'rgb', 'wifi'],
                'is_featured' => false, 'cover' => 6,
            ],

            // ─── Furniture (5) ────────────────────────────────────────
            [
                'category' => 'furniture', 'vendor' => 'zirakpur-furniture-house',
                'name' => '3+2+1 Sofa Set (Fabric)',
                'description' => 'Solid sheesham frame, high-density foam cushions, stain-resistant fabric upholstery. 5-year warranty on frame and foam.',
                'bhk_fit' => ['2', '3', '4'], 'price_min' => 32000, 'price_max' => 58000,
                'price_unit' => 'per set', 'tags' => ['sofa', 'living-room', 'fabric'],
                'is_featured' => true, 'cover' => 0,
            ],
            [
                'category' => 'furniture', 'vendor' => 'zirakpur-furniture-house',
                'name' => 'Queen Storage Bed (Engineered Wood)',
                'description' => 'Queen-size bed with 4-drawer hydraulic storage. Pre-laminated engineered wood, easy-clean surfaces. Mattress sold separately.',
                'bhk_fit' => ['1', '2', '3'], 'price_min' => 18500, 'price_max' => 32000,
                'price_unit' => 'per piece', 'tags' => ['bed', 'storage', 'queen'],
                'is_featured' => true, 'cover' => 1,
            ],
            [
                'category' => 'furniture', 'vendor' => 'zirakpur-furniture-house',
                'name' => '6-Seater Dining Table Set',
                'description' => 'Solid-wood 6-seater dining table with cushioned chairs. Seats 6 comfortably. Marble-look tempered glass top option available.',
                'bhk_fit' => ['2', '3', '4'], 'price_min' => 24000, 'price_max' => 48000,
                'price_unit' => 'per set', 'tags' => ['dining', 'wood', '6-seater'],
                'is_featured' => false, 'cover' => 2,
            ],
            [
                'category' => 'furniture', 'vendor' => 'zirakpur-furniture-house',
                'name' => '3-Door Wardrobe with Mirror',
                'description' => '3-door sliding wardrobe with full-length mirror panel. Soft-close hinges, multiple internal shelves and hanging rods.',
                'bhk_fit' => ['1', '2', '3'], 'price_min' => 22000, 'price_max' => 38000,
                'price_unit' => 'per piece', 'tags' => ['wardrobe', 'mirror', 'sliding'],
                'is_featured' => false, 'cover' => 3,
            ],
            [
                'category' => 'furniture', 'vendor' => 'zirakpur-furniture-house',
                'name' => 'Bookshelf / Display Unit (6ft)',
                'description' => '6ft tall open bookshelf with adjustable shelves. Solid-wood edge banding, anti-tip wall-mount kit included.',
                'bhk_fit' => null, 'price_min' => 9500, 'price_max' => 16500,
                'price_unit' => 'per piece', 'tags' => ['bookshelf', 'storage', 'display'],
                'is_featured' => false, 'cover' => 7,
            ],

            // ─── Kitchen Products (5) ─────────────────────────────────
            [
                'category' => 'kitchen-products', 'vendor' => 'tricity-modular-kitchens',
                'name' => 'L-Shape Modular Kitchen (8x10 ft)',
                'description' => 'Complete L-shape modular kitchen in BWR plywood with soft-close hinges, granite countertop, and SS sink. Includes free 3D design and site visit.',
                'bhk_fit' => ['2', '3', '4'], 'price_min' => 145000, 'price_max' => 280000,
                'price_unit' => 'per kitchen', 'tags' => ['modular', 'l-shape', 'granite'],
                'is_featured' => true, 'cover' => 0,
            ],
            [
                'category' => 'kitchen-products', 'vendor' => 'tricity-modular-kitchens',
                'name' => 'Parallel Kitchen (10x6 ft)',
                'description' => 'Space-efficient parallel kitchen layout with overhead cabinets on both sides. Acrylic matte finish, soft-close channels.',
                'bhk_fit' => ['1', '2', '3'], 'price_min' => 110000, 'price_max' => 195000,
                'price_unit' => 'per kitchen', 'tags' => ['modular', 'parallel', 'compact'],
                'is_featured' => true, 'cover' => 1,
            ],
            [
                'category' => 'kitchen-products', 'vendor' => 'tricity-modular-kitchens',
                'name' => 'Kitchen Chimney (60cm, Auto-clean)',
                'description' => '60cm auto-clean kitchen chimney with baffle filters and gesture control. 1200 m³/hr suction. 7-year motor warranty.',
                'bhk_fit' => null, 'price_min' => 8500, 'price_max' => 18500,
                'price_unit' => 'per piece', 'tags' => ['chimney', 'auto-clean', 'baffle'],
                'is_featured' => false, 'cover' => 4,
            ],
            [
                'category' => 'kitchen-products', 'vendor' => 'tricity-modular-kitchens',
                'name' => 'Built-in Hob (4 Burner)',
                'description' => '4-burner built-in gas hob in toughened glass. Auto-ignition, brass burners, ISI-marked. Fits 60cm cut-out.',
                'bhk_fit' => null, 'price_min' => 6500, 'price_max' => 14500,
                'price_unit' => 'per piece', 'tags' => ['hob', 'built-in', 'gas'],
                'is_featured' => false, 'cover' => 5,
            ],
            [
                'category' => 'kitchen-products', 'vendor' => 'tricity-modular-kitchens',
                'name' => 'Pull-Out Pantry Unit (Tall)',
                'description' => 'Floor-to-ceiling pull-out pantry with 6 baskets. Soft-close rails, full-extension. Fits 600mm wide cabinet opening.',
                'bhk_fit' => null, 'price_min' => 12500, 'price_max' => 22000,
                'price_unit' => 'per piece', 'tags' => ['pantry', 'pull-out', 'storage'],
                'is_featured' => false, 'cover' => 6,
            ],

            // ─── Bathroom Fittings (4) ────────────────────────────────
            [
                'category' => 'bathroom-fittings', 'vendor' => 'panchkula-bath-studio',
                'name' => 'Wall-Mounted Shower Set (Jaquar)',
                'description' => 'Authentic Jaquar wall-mounted shower set with 3-flow hand shower and overhead rain shower. Single-lever diverter, 8-year warranty.',
                'bhk_fit' => null, 'price_min' => 4800, 'price_max' => 11500,
                'price_unit' => 'per set', 'tags' => ['shower', 'jaquar', 'rain'],
                'is_featured' => true, 'cover' => 2,
            ],
            [
                'category' => 'bathroom-fittings', 'vendor' => 'panchkula-bath-studio',
                'name' => 'Single-Lever Basin Mixer',
                'description' => 'Pillar-cock basin mixer in chrome finish. 35mm ceramic cartridge, anti-calc aerator. Authorised Jaquar dealer stock.',
                'bhk_fit' => null, 'price_min' => 1800, 'price_max' => 4200,
                'price_unit' => 'per piece', 'tags' => ['basin', 'mixer', 'jaquar'],
                'is_featured' => false, 'cover' => 3,
            ],
            [
                'category' => 'bathroom-fittings', 'vendor' => 'panchkula-bath-studio',
                'name' => 'Wall-Hung WC with Soft-Close Seat',
                'description' => 'Hindware wall-hung toilet with concealed cistern frame and soft-close seat. Rimless flush, dual-flush 3/6L. Frame included.',
                'bhk_fit' => null, 'price_min' => 14500, 'price_max' => 28000,
                'price_unit' => 'per set', 'tags' => ['toilet', 'wall-hung', 'hindware'],
                'is_featured' => true, 'cover' => 6,
            ],
            [
                'category' => 'bathroom-fittings', 'vendor' => 'panchkula-bath-studio',
                'name' => 'Shower Enclosure (8mm Toughened)',
                'description' => 'Custom-fitted corner shower enclosure in 8mm toughened glass with nano-coating for easy-clean. SS 304 hardware, magnetic seal.',
                'bhk_fit' => null, 'price_min' => 18500, 'price_max' => 36000,
                'price_unit' => 'per enclosure', 'tags' => ['enclosure', 'glass', 'custom'],
                'is_featured' => false, 'cover' => 7,
            ],

            // ─── Home Décor (4) ───────────────────────────────────────
            [
                'category' => 'home-decor', 'vendor' => 'decor-studio-mohali',
                'name' => 'Canvas Wall Art Set (3-piece)',
                'description' => 'Set of 3 gallery-wrapped canvas prints (botanical, abstract, typography). Ready to hang — sawtooth hangers pre-fitted.',
                'bhk_fit' => null, 'price_min' => 1800, 'price_max' => 4800,
                'price_unit' => 'per set', 'tags' => ['wall-art', 'canvas', 'set'],
                'is_featured' => true, 'cover' => 1,
            ],
            [
                'category' => 'home-decor', 'vendor' => 'decor-studio-mohali',
                'name' => 'Indoor Plant Set (4 plants)',
                'description' => 'Set of 4 low-maintenance indoor plants (snake plant, money plant, jade, ZZ) in self-watering pots. Air-purifying, pet-friendly picks.',
                'bhk_fit' => null, 'price_min' => 1200, 'price_max' => 3200,
                'price_unit' => 'per set', 'tags' => ['plants', 'indoor', 'air-purifying'],
                'is_featured' => false, 'cover' => 0,
            ],
            [
                'category' => 'home-decor', 'vendor' => 'decor-studio-mohali',
                'name' => 'Hand-Woollen Rug (5x7 ft)',
                'description' => 'Hand-tufted wool rug in neutral tones. Soft underfoot, naturally hypoallergenic. Anti-slip backing included.',
                'bhk_fit' => null, 'price_min' => 4500, 'price_max' => 11500,
                'price_unit' => 'per piece', 'tags' => ['rug', 'wool', 'handmade'],
                'is_featured' => false, 'cover' => 3,
            ],
            [
                'category' => 'home-decor', 'vendor' => 'decor-studio-mohali',
                'name' => 'Decorative Cushion Set (5-piece)',
                'description' => 'Set of 5 cotton cushion covers with hidden zip closure. Mix of textures — linen, velvet, woven. Fits 16x16 inch inserts.',
                'bhk_fit' => null, 'price_min' => 950, 'price_max' => 2400,
                'price_unit' => 'per set', 'tags' => ['cushion', 'decor', 'cotton'],
                'is_featured' => false, 'cover' => 4,
            ],

            // ─── Paint & Hardware (4) ─────────────────────────────────
            [
                'category' => 'paint-hardware', 'vendor' => 'sahibzada-paint-hardware',
                'name' => 'Asian Paints Ace Exterior (20L)',
                'description' => 'Asian Paints Ace Exterior emulsion — 4-year performance warranty. Covers ~1200 sqft with 2 coats. Free colour consultation.',
                'bhk_fit' => null, 'price_min' => 5800, 'price_max' => 7200,
                'price_unit' => 'per 20L bucket', 'tags' => ['paint', 'exterior', 'asian-paints'],
                'is_featured' => true, 'cover' => 2,
            ],
            [
                'category' => 'paint-hardware', 'vendor' => 'sahibzada-paint-hardware',
                'name' => 'Berger Easy Clean Interior (4L)',
                'description' => 'Berger Easy Clean interior emulsion with anti-bacterial and stain-resistant finish. Low-odour, 1500+ shades available.',
                'bhk_fit' => null, 'price_min' => 1450, 'price_max' => 1900,
                'price_unit' => 'per 4L bucket', 'tags' => ['paint', 'interior', 'berger'],
                'is_featured' => false, 'cover' => 5,
            ],
            [
                'category' => 'paint-hardware', 'vendor' => 'sahibzada-paint-hardware',
                'name' => 'Waterproofing Kit (Dr. Fixit)',
                'description' => 'Dr. Fixit LW+ waterproofing kit — covers bathroom and balcony (~300 sqft). 2-coat system, 10-year warranty on application.',
                'bhk_fit' => null, 'price_min' => 3200, 'price_max' => 4800,
                'price_unit' => 'per kit', 'tags' => ['waterproofing', 'dr-fixit', 'bathroom'],
                'is_featured' => false, 'cover' => 6,
            ],
            [
                'category' => 'paint-hardware', 'vendor' => 'sahibzada-paint-hardware',
                'name' => 'Power Drill Machine (Bosch 13mm)',
                'description' => 'Bosch GBM 13 RE 600W corded power drill with reverse, variable speed, and keyless chuck. Includes bit set and carry case.',
                'bhk_fit' => null, 'price_min' => 3850, 'price_max' => 4500,
                'price_unit' => 'per piece', 'tags' => ['drill', 'bosch', 'tools'],
                'is_featured' => false, 'cover' => 7,
            ],

            // ─── Smart Home (3) ───────────────────────────────────────
            [
                'category' => 'smart-home', 'vendor' => 'smart-tricity-automation',
                'name' => 'Smart Door Lock (Fingerprint + App)',
                'description' => '5-in-1 smart lock: fingerprint, PIN, RFID card, mechanical key, and app unlock. Stores 100 fingerprints. Fits standard 35-55mm doors.',
                'bhk_fit' => null, 'price_min' => 8500, 'price_max' => 18500,
                'price_unit' => 'per piece', 'tags' => ['smart-lock', 'fingerprint', 'wifi'],
                'is_featured' => true, 'cover' => 0,
            ],
            [
                'category' => 'smart-home', 'vendor' => 'smart-tricity-automation',
                'name' => 'Wi-Fi Video Doorbell (1080p)',
                'description' => '1080p HD video doorbell with two-way audio, night vision, and motion alerts. Stores 7 days of footage on local SD card. No subscription required.',
                'bhk_fit' => null, 'price_min' => 4200, 'price_max' => 7800,
                'price_unit' => 'per piece', 'tags' => ['doorbell', 'camera', 'wifi'],
                'is_featured' => true, 'cover' => 1,
            ],
            [
                'category' => 'smart-home', 'vendor' => 'smart-tricity-automation',
                'name' => 'Whole-Home Automation Hub',
                'description' => 'Zigbee + Wi-Fi home automation hub. Controls lights, fans, AC, and curtains from one app. Free in-home setup within Tricity.',
                'bhk_fit' => null, 'price_min' => 12000, 'price_max' => 24000,
                'price_unit' => 'per hub (setup incl.)', 'tags' => ['hub', 'automation', 'zigbee'],
                'is_featured' => false, 'cover' => 6,
            ],
        ];

        $now = now();
        $count = 0;

        foreach ($products as $i => $row) {
            $vendorId = $vendorIds[$row['vendor']] ?? null;
            $categoryId = $categoryIds[$row['category']] ?? null;

            if (!$vendorId || !$categoryId) {
                $this->command?->warn("Skipping '{$row['name']}': missing vendor '{$row['vendor']}' or category '{$row['category']}'.");
                continue;
            }

            $slug = Str::slug($row['name']);

            MarketplaceProduct::updateOrCreate(
                ['slug' => $slug],
                [
                    'vendor_id'   => $vendorId,
                    'category_id' => $categoryId,
                    'name'        => $row['name'],
                    'description' => $row['description'],
                    'bhk_fit'     => $row['bhk_fit'] ?? null,
                    'price_min'   => $row['price_min'],
                    'price_max'   => $row['price_max'],
                    'price_unit'  => $row['price_unit'],
                    'tags'        => $row['tags'],
                    'cover_image' => $coverImages[$row['cover']] ?? end($coverImages),
                    'is_featured' => $row['is_featured'],
                    'is_active'   => true,
                    'sort_order'  => $i + 1,
                    'leads_count' => 0,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );

            $count++;
        }

        $this->command?->info("Marketplace products: {$count} upserted.");
    }
}
