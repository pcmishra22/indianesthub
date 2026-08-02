<?php

namespace App\Services;

class AiPropertyDescriptionService
{
    protected GeminiClient $client;

    public function __construct(GeminiClient $client)
    {
        $this->client = $client;
    }

    public function isConfigured(): bool
    {
        return $this->client->isConfigured();
    }

    /**
     * @param array $attrs  Raw property form fields (title, property_type, bhk_type, bedrooms,
     *                       bathrooms, city, locality, sub_locality, area, area_unit, price,
     *                       furnishing_status, facing, listing_type, amenities[], floor_number,
     *                       total_floors, tone)
     * @return array{description:?string, meta_description:?string, error:bool, message:string}
     */
    public function generate(array $attrs): array
    {
        if (!$this->client->isConfigured()) {
            return [
                'description' => null,
                'meta_description' => null,
                'error' => true,
                'message' => 'AI description generator is not configured yet.',
            ];
        }

        $facts = $this->buildFactSheet($attrs);
        $tone = $attrs['tone'] ?? 'professional';

        $systemPrompt = <<<PROMPT
You write property listing descriptions for IndianEstHub, a real estate platform in the Chandigarh Tricity region (Chandigarh, Mohali, Zirakpur, Panchkula).

Rules:
- Use ONLY the facts given below — never invent amenities, certifications, nearby landmarks, or claims not provided.
- Write in a {$tone} tone, 120-180 words, in flowing paragraphs (no bullet points, no markdown, no headings).
- Highlight the property's strongest genuine selling points first (location, size, furnishing).
- End with a soft, natural call-to-action inviting buyers/tenants to inquire — no phone numbers, no "Book Now" buttons.
- After the description, on a new line starting with "META:", write a single SEO meta description under 155 characters summarizing the listing.

Facts about this property:
{$facts}
PROMPT;

        $result = $this->client->generateOnce(
            $systemPrompt,
            'Write the property description now.',
            ['temperature' => 0.7, 'maxOutputTokens' => 500]
        );

        if ($result['error']) {
            return [
                'description' => null,
                'meta_description' => null,
                'error' => true,
                'message' => $result['message'],
            ];
        }

        [$description, $metaDescription] = $this->splitOutput($result['text']);

        return [
            'description' => $description,
            'meta_description' => $metaDescription,
            'error' => false,
            'message' => '',
        ];
    }

    protected function buildFactSheet(array $a): string
    {
        $lines = [];

        $put = function ($label, $value) use (&$lines) {
            if ($value !== null && $value !== '' && $value !== []) {
                $lines[] = "- {$label}: " . (is_array($value) ? implode(', ', $value) : $value);
            }
        };

        $put('Listing type', ($a['listing_type'] ?? null) === 'rent' ? 'For Rent' : 'For Sale');
        $put('Title', $a['title'] ?? null);
        $put('Property type', $a['property_type'] ?? null);
        $put('Configuration', $a['bhk_type'] ?? null);
        $put('Bedrooms', $a['bedrooms'] ?? null);
        $put('Bathrooms', $a['bathrooms'] ?? null);
        $put('Area', isset($a['area']) && $a['area'] !== '' ? $a['area'] . ' ' . ($a['area_unit'] ?? 'sq.ft.') : null);
        $put('Furnishing', $a['furnishing_status'] ?? null);
        $put('Facing', $a['facing'] ?? null);
        $put('Floor', isset($a['floor_number']) && $a['floor_number'] !== ''
            ? $a['floor_number'] . (isset($a['total_floors']) && $a['total_floors'] !== '' ? ' of ' . $a['total_floors'] : '')
            : null);
        $put('City', $a['city'] ?? null);
        $put('Locality', trim(($a['locality'] ?? '') . ' ' . ($a['sub_locality'] ?? '')));
        $put('Price', isset($a['price']) && $a['price'] !== '' ? '₹' . number_format((float) $a['price']) : null);
        $put('Amenities', $a['amenities'] ?? null);

        return $lines ? implode("\n", $lines) : '- (no specific details provided — write a short generic but honest description)';
    }

    /**
     * Splits the model's raw output into [description, metaDescription] using the
     * "META:" marker requested in the prompt. Falls back gracefully if missing.
     */
    protected function splitOutput(string $raw): array
    {
        if (preg_match('/^(.*?)\n+META:\s*(.+)$/is', trim($raw), $m)) {
            return [trim($m[1]), trim($m[2])];
        }

        return [trim($raw), null];
    }
}
