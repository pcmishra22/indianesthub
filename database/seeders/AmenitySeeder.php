<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            // Lifestyle
            ['name' => 'Swimming Pool',        'icon' => 'bi-water',          'category' => 'Lifestyle'],
            ['name' => 'Gymnasium / Fitness',   'icon' => 'bi-bicycle',        'category' => 'Lifestyle'],
            ['name' => 'Clubhouse',             'icon' => 'bi-building',       'category' => 'Lifestyle'],
            ['name' => 'Spa & Sauna',           'icon' => 'bi-droplet-half',   'category' => 'Lifestyle'],
            ['name' => 'Yoga / Meditation',     'icon' => 'bi-person-arms-up', 'category' => 'Lifestyle'],
            ['name' => 'Party Hall / Banquet',  'icon' => 'bi-balloon-heart',  'category' => 'Lifestyle'],
            ['name' => 'Mini Theatre',          'icon' => 'bi-camera-video',   'category' => 'Lifestyle'],
            ['name' => 'Rooftop Garden',        'icon' => 'bi-flower1',        'category' => 'Lifestyle'],

            // Sports & Recreation
            ['name' => 'Cricket Net',           'icon' => 'bi-trophy',         'category' => 'Sports'],
            ['name' => 'Basketball Court',      'icon' => 'bi-dribbble',       'category' => 'Sports'],
            ['name' => 'Badminton Court',       'icon' => 'bi-activity',       'category' => 'Sports'],
            ['name' => 'Tennis Court',          'icon' => 'bi-activity',       'category' => 'Sports'],
            ['name' => 'Jogging Track',         'icon' => 'bi-person-walking', 'category' => 'Sports'],
            ['name' => 'Indoor Games',          'icon' => 'bi-controller',     'category' => 'Sports'],
            ['name' => 'Children\'s Play Area', 'icon' => 'bi-emoji-smile',    'category' => 'Sports'],
            ['name' => 'Cycling Track',         'icon' => 'bi-bicycle',        'category' => 'Sports'],

            // Security
            ['name' => '24×7 Security',        'icon' => 'bi-shield-check',   'category' => 'Security'],
            ['name' => 'CCTV Surveillance',     'icon' => 'bi-camera',         'category' => 'Security'],
            ['name' => 'Video Door Phone',      'icon' => 'bi-phone',          'category' => 'Security'],
            ['name' => 'Gated Community',       'icon' => 'bi-lock',           'category' => 'Security'],
            ['name' => 'Biometric Entry',       'icon' => 'bi-fingerprint',    'category' => 'Security'],
            ['name' => 'Fire Safety System',    'icon' => 'bi-fire',           'category' => 'Security'],

            // Convenience
            ['name' => 'Power Backup',          'icon' => 'bi-lightning-charge','category' => 'Convenience'],
            ['name' => 'High-Speed Elevators',  'icon' => 'bi-arrow-up-square','category' => 'Convenience'],
            ['name' => 'Covered Parking',       'icon' => 'bi-car-front',      'category' => 'Convenience'],
            ['name' => 'EV Charging Point',     'icon' => 'bi-plug',           'category' => 'Convenience'],
            ['name' => 'Rainwater Harvesting',  'icon' => 'bi-cloud-rain',     'category' => 'Convenience'],
            ['name' => 'Solar Panels',          'icon' => 'bi-sun',            'category' => 'Convenience'],
            ['name' => 'Sewage Treatment Plant','icon' => 'bi-recycle',        'category' => 'Convenience'],
            ['name' => 'Intercom Facility',     'icon' => 'bi-telephone',      'category' => 'Convenience'],
            ['name' => 'Piped Gas',             'icon' => 'bi-fire',           'category' => 'Convenience'],
            ['name' => 'Supermarket / Store',   'icon' => 'bi-bag',            'category' => 'Convenience'],
            ['name' => 'ATM / Bank',            'icon' => 'bi-bank',           'category' => 'Convenience'],
            ['name' => 'Café / Restaurant',     'icon' => 'bi-cup-hot',        'category' => 'Convenience'],
            ['name' => 'Concierge Services',    'icon' => 'bi-bell',           'category' => 'Convenience'],
            ['name' => 'Waste Management',      'icon' => 'bi-trash',          'category' => 'Convenience'],

            // Green / Eco
            ['name' => 'Landscaped Gardens',    'icon' => 'bi-tree',           'category' => 'Green'],
            ['name' => 'Amphitheatre',          'icon' => 'bi-easel',          'category' => 'Green'],
            ['name' => 'Reflexology Path',      'icon' => 'bi-footprint',      'category' => 'Green'],
            ['name' => 'Pet-Friendly Zones',    'icon' => 'bi-heart',          'category' => 'Green'],
            ['name' => 'Organic Farm Area',     'icon' => 'bi-flower2',        'category' => 'Green'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(['name' => $amenity['name']], $amenity);
        }

        $this->command->info('✅ ' . count($amenities) . ' amenities seeded successfully.');
    }
}
