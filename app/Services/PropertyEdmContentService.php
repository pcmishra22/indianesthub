<?php

namespace App\Services;

use App\Models\Property;

class PropertyEdmContentService
{
    public function defaultSubject(Property $property): string
    {
        $listing = str_contains(strtolower((string) $property->listing_type), 'rent') ? 'For Rent' : 'For Sale';
        return "{$listing}: {$property->title}";
    }

    public function defaultMessage(Property $property): string
    {
        $location = collect([$property->locality, $property->city])->filter()->implode(', ');
        $specs = collect([$property->bhk_type, $property->area ? $property->area . ' sqft' : null])
            ->filter()->implode(' · ');

        $lines = [];
        $lines[] = "Hi,";
        $lines[] = '';
        $lines[] = "We wanted to bring your attention to this property" . ($location ? " in {$location}" : '') . ":";
        $lines[] = '';
        if ($specs) $lines[] = $specs;
        $lines[] = "Feel free to reach out if you'd like to schedule a visit or have any questions.";
        $lines[] = '';
        $lines[] = 'Best regards,';

        return implode("\n", $lines);
    }
}
