<?php

namespace App\Services;

use App\Models\MarketplaceProduct;

class AiInteriorSuggestionsService
{
    protected GeminiClient $client;

    // Fixed list the AI must choose from — prevents it from inventing
    // categories that don't exist in the actual marketplace.
    protected const CATEGORIES = [
        'Curtains & Blinds', 'Lights & Fixtures', 'Furniture',
        'Kitchen Products', 'Bathroom Fittings', 'Home Décor',
        'Paint & Hardware', 'Smart Home',
    ];

    public function __construct(GeminiClient $client)
    {
        $this->client = $client;
    }

    public function isConfigured(): bool
    {
        return $this->client->isConfigured();
    }

    /**
     * @param array $attrs  ['room_type'=>string, 'style'=>?string, 'budget'=>?string, 'bhk_type'=>?string]
     * @return array{suggestions:array, products:\Illuminate\Support\Collection, error:bool, message:string}
     */
    public function generate(array $attrs): array
    {
        if (!$this->client->isConfigured()) {
            return $this->errorResult('AI interior suggestions are not configured yet.');
        }

        $roomType = $attrs['room_type'] ?? 'Living Room';
        $style = $attrs['style'] ?? 'Modern';
        $budget = $attrs['budget'] ?? 'not specified';
        $bhk = $attrs['bhk_type'] ?? 'not specified';
        $categoryList = implode(', ', self::CATEGORIES);

        $systemPrompt = <<<PROMPT
You are an interior design assistant for IndianEstHub, helping homeowners in India plan their {$roomType}.

Respond with ONLY a valid JSON object, no markdown, no code fences, no commentary, in exactly this shape:
{
  "suggestions": [
    {"title": "short idea title", "tip": "1-2 sentence practical suggestion"}
  ],
  "shopping_categories": ["CategoryName", "CategoryName"]
}

Rules:
- "suggestions": exactly 5 practical, specific ideas for this room (colour palette, layout, lighting, furniture arrangement, materials) matching the given style and budget — avoid generic filler like "add plants" unless genuinely tailored.
- "shopping_categories": pick 1-3 categories ONLY from this exact list that best fit shopping for this room: {$categoryList}. Use the exact spelling given.
- Keep tone practical and Indian-home-context aware (e.g. modular kitchens, monsoon-proofing balconies, vaastu-neutral suggestions only if relevant — don't force it).
- Do not mention specific brands or exact prices (they vary).

Room: {$roomType}
Style preference: {$style}
Budget range: {$budget}
Home size: {$bhk}
PROMPT;

        $result = $this->client->generateOnce(
            $systemPrompt,
            'Generate the suggestions JSON now.',
            ['temperature' => 0.8, 'maxOutputTokens' => 700]
        );

        if ($result['error']) {
            return $this->errorResult($result['message']);
        }

        $parsed = $this->parseJson($result['text']);

        if (!$parsed || empty($parsed['suggestions'])) {
            return $this->errorResult("Couldn't generate suggestions this time. Please try again.");
        }

        $shoppingCategories = array_values(array_intersect(
            $parsed['shopping_categories'] ?? [],
            self::CATEGORIES
        ));

        $products = $this->findProducts($shoppingCategories, $bhk);

        return [
            'suggestions' => $parsed['suggestions'],
            'products' => $products,
            'error' => false,
            'message' => '',
        ];
    }

    protected function findProducts(array $categoryNames, string $bhk): \Illuminate\Support\Collection
    {
        if (empty($categoryNames)) {
            return collect();
        }

        return MarketplaceProduct::query()
            ->with('category:id,name,slug')
            ->whereHas('category', fn ($q) => $q->whereIn('name', $categoryNames))
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->limit(6)
            ->get(['id', 'category_id', 'name', 'slug', 'price_min', 'price_max', 'price_unit', 'cover_image', 'is_featured']);
    }

    protected function parseJson(string $raw): ?array
    {
        $raw = trim($raw);
        $raw = preg_replace('/^```(?:json)?|```$/m', '', $raw);
        $raw = trim($raw);

        $data = json_decode($raw, true);

        return (json_last_error() === JSON_ERROR_NONE && is_array($data)) ? $data : null;
    }

    protected function errorResult(string $message): array
    {
        return ['suggestions' => [], 'products' => collect(), 'error' => true, 'message' => $message];
    }
}
