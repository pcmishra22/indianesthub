<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\PropertyImage;

class PropertyImageSeeder extends Seeder
{
    public function run(): void
    {
        $sampleImages = [
            'property1.jpg',
            'property2.jpg',
            'property3.jpg',
            'property4.jpg',
            'property5.jpg',
        ];
        $properties = Property::all();
        foreach ($properties as $i => $property) {
            $imgName = $sampleImages[$i % count($sampleImages)];
            PropertyImage::updateOrCreate([
                'property_id' => $property->id,
                'image_path' => $imgName,
            ]);
            // Set cover_image on property (just filename)
            $property->cover_image = $imgName;
            $property->save();
        }
    }
}
