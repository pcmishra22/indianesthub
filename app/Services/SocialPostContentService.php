<?php

namespace App\Services;

use App\Models\Property;

class SocialPostContentService
{
    /**
     * Build a ready-to-edit Instagram/Facebook caption for the property.
     */
    public function caption(Property $property, ?string $publicUrl = null): string
    {
        $listing = $this->listingLabel($property);
        $priceLine = $this->priceLine($property);
        $specLine = $this->specLine($property);
        $location = collect([$property->locality, $property->city])->filter()->implode(', ');

        $lines = [];
        $lines[] = "✨ {$listing}: {$property->title}";
        if ($specLine) $lines[] = $specLine;
        if ($location) $lines[] = "📍 {$location}";
        if ($priceLine) $lines[] = $priceLine;
        $lines[] = '';
        $lines[] = '📲 DM or WhatsApp us to schedule a visit!';
        if ($publicUrl) {
            $lines[] = "🔗 {$publicUrl}";
        }
        $lines[] = '';
        $lines[] = implode(' ', $this->hashtags($property));

        return implode("\n", array_filter($lines, fn ($l) => $l !== null));
    }

    /**
     * Suggested hashtags based on city, property type and listing type.
     */
    public function hashtags(Property $property): array
    {
        $tags = ['#RealEstate', '#PropertyForSale'];

        if ($property->listing_type) {
            $tags[] = '#' . str_replace(' ', '', ucwords(strtolower($property->listing_type)));
        }
        if ($property->property_type) {
            $tags[] = '#' . str_replace(' ', '', ucwords(strtolower($property->property_type)));
        }
        if ($property->bhk_type) {
            $tags[] = '#' . str_replace(' ', '', $property->bhk_type);
        }
        if ($property->city) {
            $tags[] = '#' . str_replace(' ', '', ucwords(strtolower($property->city))) . 'RealEstate';
            $tags[] = '#' . str_replace(' ', '', ucwords(strtolower($property->city))) . 'Properties';
        }
        $tags[] = '#DreamHome';
        $tags[] = '#HomeForSale';

        return array_values(array_unique($tags));
    }

    protected function listingLabel(Property $property): string
    {
        $type = strtolower((string) $property->listing_type);
        return str_contains($type, 'rent') ? 'FOR RENT' : 'FOR SALE';
    }

    protected function priceLine(Property $property): ?string
    {
        $price = $property->price ?: $property->expected_price;
        if (!$price) return null;

        $suffix = str_contains(strtolower((string) $property->listing_type), 'rent') ? '/month' : '';
        return '💰 ₹' . number_format((float) $price) . $suffix;
    }

    protected function specLine(Property $property): ?string
    {
        $parts = array_filter([
            $property->bhk_type,
            $property->area ? $property->area . ' sqft' : null,
        ]);
        return $parts ? '🏠 ' . implode(' · ', $parts) : null;
    }
}
