<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 100; $i++) {
            Property::create([
                'property_dealer_id' => 1, // You can randomize or assign as needed
                'title' => 'Sample Property ' . $i,
                'slug' => 'sample-property-' . $i,
                'description' => 'Description for property ' . $i,
                'property_type' => 'Apartment',
                'address' => 'Address ' . $i,
                'city' => 'City ' . rand(1, 10),
                'state' => 'State ' . rand(1, 5),
                'price' => rand(100000, 1000000),
                'bedrooms' => rand(1, 5),
                'bathrooms' => rand(1, 4),
                'area' => rand(500, 5000),
                'furnishing' => 'Furnished',
                'amenities' => 'Pool,Gym,Parking',
                'status' => 'Available',
            ]);
        }
    }
}
