<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Review;
use App\Models\User;
use App\Models\Property;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_can_be_created()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create();
        $review = Review::create([
            'user_id' => $user->id,
            'property_id' => $property->id,
            'agent_id' => null,
            'rating' => 5,
            'review_text' => 'Great property!',
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('reviews', ['id' => $review->id]);
    }
}
