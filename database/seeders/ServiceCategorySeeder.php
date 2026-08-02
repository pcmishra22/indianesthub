<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

/**
 * Seeds the professional/service-provider categories — people who come do a
 * job (electrician, painter, architect...), as opposed to Marketplace
 * categories which are for browsing a product catalog with prices
 * (Furniture, Building Materials). See MarketplaceCategorySeeder for that
 * half of the Phase 1 category list.
 *
 * Safe to re-run — upserts on slug.
 */
class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Interior Designers',
                'slug'        => 'interior-designers',
                'icon'        => 'bi-palette',
                'description' => 'Full home and room interior design, from concept to execution',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Architects',
                'slug'        => 'architects',
                'icon'        => 'bi-compass',
                'description' => 'Building design, floor plans, and structural planning',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Civil Contractors',
                'slug'        => 'civil-contractors',
                'icon'        => 'bi-building',
                'description' => 'End-to-end construction and civil work contractors',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Electricians',
                'slug'        => 'electricians',
                'icon'        => 'bi-lightning-charge',
                'description' => 'Wiring, repairs, and electrical installation',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Plumbers',
                'slug'        => 'plumbers',
                'icon'        => 'bi-droplet-half',
                'description' => 'Pipe fitting, leak repair, and bathroom/kitchen plumbing',
                'sort_order'  => 5,
            ],
            [
                'name'        => 'Painters',
                'slug'        => 'painters',
                'icon'        => 'bi-brush',
                'description' => 'Interior and exterior painting services',
                'sort_order'  => 6,
            ],
            [
                'name'        => 'Home Cleaning',
                'slug'        => 'home-cleaning',
                'icon'        => 'bi-stars',
                'description' => 'Deep cleaning, move-in/move-out cleaning, and regular housekeeping',
                'sort_order'  => 7,
            ],
            [
                'name'        => 'Packers & Movers',
                'slug'        => 'packers-movers',
                'icon'        => 'bi-truck',
                'description' => 'Home relocation, packing, and moving services',
                'sort_order'  => 8,
            ],
            [
                'name'        => 'Solar Installation',
                'slug'        => 'solar-installation',
                'icon'        => 'bi-sun',
                'description' => 'Rooftop solar panel installation and setup',
                'sort_order'  => 9,
            ],
            [
                'name'        => 'Home Automation',
                'slug'        => 'home-automation',
                'icon'        => 'bi-house-gear',
                'description' => 'Smart home devices, automation, and integration setup',
                'sort_order'  => 10,
            ],
            [
                'name'        => 'CCTV Installation',
                'slug'        => 'cctv-installation',
                'icon'        => 'bi-camera-video',
                'description' => 'Security camera installation and setup',
                'sort_order'  => 11,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category + ['is_active' => true]
            );
        }
    }
}
