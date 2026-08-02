<?php

namespace Database\Seeders;

use App\Models\MarketplaceCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds the 8 core Home Marketplace categories.
 * Safe to re-run — upserts on slug.
 */
class MarketplaceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'       => 'Curtains & Blinds',
                'slug'       => 'curtains-blinds',
                'icon'       => 'bi-columns-gap',
                'tagline'    => 'Custom and ready-made curtains, blinds & window dressing',
                'sort_order' => 1,
            ],
            [
                'name'       => 'Lights & Fixtures',
                'slug'       => 'lights-fixtures',
                'icon'       => 'bi-lightbulb',
                'tagline'    => 'Ceiling, wall & outdoor lighting for every room',
                'sort_order' => 2,
            ],
            [
                'name'       => 'Furniture',
                'slug'       => 'furniture',
                'icon'       => 'bi-house-door',
                'tagline'    => 'Sofas, beds, dining sets & wardrobes',
                'sort_order' => 3,
            ],
            [
                'name'       => 'Kitchen Products',
                'slug'       => 'kitchen-products',
                'icon'       => 'bi-egg-fried',
                'tagline'    => 'Modular kitchens, storage & appliances',
                'sort_order' => 4,
            ],
            [
                'name'       => 'Bathroom Fittings',
                'slug'       => 'bathroom-fittings',
                'icon'       => 'bi-droplet',
                'tagline'    => 'Taps, showers, sanitaryware & accessories',
                'sort_order' => 5,
            ],
            [
                'name'       => 'Home Décor',
                'slug'       => 'home-decor',
                'icon'       => 'bi-flower1',
                'tagline'    => 'Wall art, rugs, plants & décor accents',
                'sort_order' => 6,
            ],
            [
                'name'       => 'Paint & Hardware',
                'slug'       => 'paint-hardware',
                'icon'       => 'bi-brush',
                'tagline'    => 'Paints, tools & construction materials',
                'sort_order' => 7,
            ],
            [
                'name'       => 'Smart Home',
                'slug'       => 'smart-home',
                'icon'       => 'bi-cpu',
                'tagline'    => 'Cameras, smart locks & home automation',
                'sort_order' => 8,
            ],
            [
                'name'       => 'Building Materials',
                'slug'       => 'building-materials',
                'icon'       => 'bi-bricks',
                'tagline'    => 'Cement, bricks, tiles, sand & core construction supplies',
                'sort_order' => 9,
            ],
        ];

        foreach ($categories as $category) {
            MarketplaceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true]
            );
        }
    }
}
