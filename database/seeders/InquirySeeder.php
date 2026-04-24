<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Support\Str;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::take(3)->get();
        foreach ($properties as $i => $property) {
            Inquiry::updateOrCreate([
                'property_id' => $property->id,
                'email' => 'inquirer' . ($i+1) . '@example.com',
            ], [
                'property_id' => $property->id,
                'broker_id' => 1,
                'name' => 'Inquirer ' . ($i+1),
                'email' => 'inquirer' . ($i+1) . '@example.com',
                'phone' => '99900000' . ($i+1),
                'message' => 'I am interested in this property.',
                'status' => 1,
                'notes' => 'Seeded inquiry',
                'tags' => 'demo,seed',
                'assigned_agent_id' => null,
            ]);
        }
    }
}
