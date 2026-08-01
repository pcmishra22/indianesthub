<?php

namespace App\Services;

use App\Models\Property;

class AiInvestmentAdvisorService
{
    protected GeminiClient $client;

    protected const SALE_VALUES = ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale'];
    protected const RENT_VALUES = ['Rent', 'rent', 'For Rent', 'for rent'];

    public function __construct(GeminiClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $attrs  ['city'=>string,'budget'=>float,'bhk_type'=>?string,
     *                       'goal'=>string ('rental'|'appreciation'|'both'), 'horizon'=>?string]
     */
    public function analyze(array $attrs): array
    {
        $city = trim($attrs['city'] ?? '');
        $budget = (float) ($attrs['budget'] ?? 0);

        if ($city === '' || $budget <= 0) {
            return $this->errorResult('Please enter a city and budget.');
        }

        $saleComps = $this->findSaleComps($city, $attrs['bhk_type'] ?? null, $budget);

        if ($saleComps->count() < 3) {
            return $this->errorResult(
                "We don't have enough listed properties matching this budget in {$city} yet for a reliable analysis. Try a wider budget range or a nearby city."
            );
        }

        $rentComps = $this->findRentComps($city, $attrs['bhk_type'] ?? null);

        $avgSalePrice = round($saleComps->avg('price'));
        $withinBudgetCount = $saleComps->where('price', '<=', $budget)->count();

        $rentalYield = null;
        if ($rentComps->count() >= 3 && $avgSalePrice > 0) {
            $avgMonthlyRent = $rentComps->avg('price');
            $rentalYield = round((($avgMonthlyRent * 12) / $avgSalePrice) * 100, 2);
        }

        $matchingListings = $saleComps
            ->where('price', '<=', $budget)
            ->sortByDesc('price')
            ->take(3)
            ->map(fn ($p) => [
                'id' => $p->id, 'title' => $p->title, 'slug' => $p->slug,
                'price' => $p->price, 'city' => $p->city, 'bhk_type' => $p->bhk_type,
            ])->values();

        $narrative = $this->explainWithAi($attrs, [
            'comp_count' => $saleComps->count(),
            'within_budget_count' => $withinBudgetCount,
            'avg_sale_price' => $avgSalePrice,
            'rental_yield' => $rentalYield,
            'rent_comp_count' => $rentComps->count(),
        ]);

        return [
            'error' => false,
            'comp_count' => $saleComps->count(),
            'within_budget_count' => $withinBudgetCount,
            'avg_sale_price' => $avgSalePrice,
            'rental_yield' => $rentalYield,
            'rent_comp_count' => $rentComps->count(),
            'matching_listings' => $matchingListings,
            'narrative' => $narrative,
        ];
    }

    protected function findSaleComps(string $city, ?string $bhk, float $budget)
    {
        // Widen the price band around budget so there's enough data to compare against,
        // not just properties strictly under budget.
        $query = Property::query()
            ->where('status', 'Available')
            ->whereIn('listing_status', ['active', 'published'])
            ->whereIn('looking_for', self::SALE_VALUES)
            ->where('city', 'like', "%{$city}%")
            ->whereNotNull('price')
            ->where('price', '>', 0)
            ->whereBetween('price', [$budget * 0.5, $budget * 1.5]);

        if ($bhk) {
            $query->where('bhk_type', $bhk);
        }

        $comps = $query->limit(50)->get(['id', 'title', 'slug', 'price', 'city', 'bhk_type']);

        // If narrowing by BHK left too little data, drop that filter and retry city-wide.
        if ($comps->count() < 3 && $bhk) {
            $comps = Property::query()
                ->where('status', 'Available')
                ->whereIn('listing_status', ['active', 'published'])
                ->whereIn('looking_for', self::SALE_VALUES)
                ->where('city', 'like', "%{$city}%")
                ->whereNotNull('price')
                ->where('price', '>', 0)
                ->whereBetween('price', [$budget * 0.5, $budget * 1.5])
                ->limit(50)
                ->get(['id', 'title', 'slug', 'price', 'city', 'bhk_type']);
        }

        return $comps;
    }

    protected function findRentComps(string $city, ?string $bhk)
    {
        $query = Property::query()
            ->where('status', 'Available')
            ->whereIn('listing_status', ['active', 'published'])
            ->whereIn('looking_for', self::RENT_VALUES)
            ->where('city', 'like', "%{$city}%")
            ->whereNotNull('price')
            ->where('price', '>', 0);

        if ($bhk) {
            $query->where('bhk_type', $bhk);
        }

        return $query->limit(50)->get(['id', 'price', 'bhk_type']);
    }

    protected function explainWithAi(array $attrs, array $stats): ?string
    {
        if (!$this->client->isConfigured()) {
            return null;
        }

        $goalLabel = match ($attrs['goal'] ?? 'both') {
            'rental' => 'rental income',
            'appreciation' => 'capital appreciation',
            default => 'both rental income and capital appreciation',
        };

        $rentalYieldLine = $stats['rental_yield']
            ? "Estimated gross rental yield in this area: {$stats['rental_yield']}% per year (based on {$stats['rent_comp_count']} comparable rental listings)."
            : "Not enough rental listings currently on the platform in this area to estimate rental yield.";

        $systemPrompt = <<<PROMPT
You explain property investment data for IndianEstHub, a real estate platform in the Chandigarh Tricity region.

You are given ALREADY-COMPUTED market data based on real current listings — do NOT change, recalculate, or contradict these numbers, and do NOT give a buy/don't-buy recommendation or a numeric "investment score". Your job is only to narrate the data in 3-4 plain-English sentences and note one or two general, non-personalized considerations relevant to the stated goal.

Rules:
- Format money naturally for Indian readers (e.g. "₹85 lakh", "₹1.2 crore").
- Be honest about data limitations (small comp counts, no rental data, etc.) rather than glossing over them.
- Do not claim to know future price trends — only describe the current snapshot given.
- No markdown, no bullet points — plain paragraph text only.
- End by noting this is general market information, not personalized financial advice.

Computed data:
- City: {$attrs['city']}
- Budget: ₹{$attrs['budget']}
- Investment goal: {$goalLabel}
- Comparable listings found in this budget range: {$stats['comp_count']}
- Listings actually within budget: {$stats['within_budget_count']}
- Average sale price among comps: ₹{$stats['avg_sale_price']}
- {$rentalYieldLine}
PROMPT;

        $result = $this->client->generateOnce(
            $systemPrompt,
            'Write the market insight now.',
            ['temperature' => 0.5, 'maxOutputTokens' => 300]
        );

        return $result['error'] ? null : $result['text'];
    }

    protected function errorResult(string $message): array
    {
        return [
            'error' => true, 'message' => $message,
            'comp_count' => 0, 'within_budget_count' => 0, 'avg_sale_price' => null,
            'rental_yield' => null, 'rent_comp_count' => 0,
            'matching_listings' => [], 'narrative' => null,
        ];
    }
}
