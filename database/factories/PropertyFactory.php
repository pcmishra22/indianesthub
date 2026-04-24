<?php
namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        $dealer = \App\Models\Dealer::factory()->create();
        return [
            'property_dealer_id' => $dealer->id,
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraph,
            'property_type' => 'Apartment',
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'price' => $this->faker->numberBetween(100000, 1000000),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'area' => $this->faker->numberBetween(500, 5000),
            'furnishing' => 'Furnished',
            'amenities' => 'Pool,Gym,Parking',
            'status' => 'Available',
            'featured' => false,
            'is_featured' => false,
            'isreal' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
