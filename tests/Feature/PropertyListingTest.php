<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Property;
use App\Models\Dealer;


class PropertyListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_properties_for_dealer()
    {
        $dealer = Dealer::factory()->create();
        $properties = Property::factory()->count(3)->create([
            'property_dealer_id' => $dealer->id,
        ]);

        $response = $this->get('/dealer/' . $dealer->id . '/properties');

        $response->assertStatus(200);
        foreach ($properties as $property) {
            $response->assertSee($property->title);
        }
    }
}
