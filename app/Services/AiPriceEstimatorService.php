<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Facades\DB;

class AiPriceEstimatorService
{
    protected GeminiClient $client;

    protected const SALE_VALUES = ['Sale', 'Sell', 'Buy', 'sell', 'buy', 'sale'];

    public function __construct(GeminiClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $attrs  ['city'=>string, 'locality'=>?string, 'property_type'=>?string,
     *                       'bhk_type'=>?string, 'area'=>?float, 'area_unit'=>?string, 'furnishing_status'=>?string]
     * @return array
     */
    public function estimate(array $attrs): array
    {
        $city = trim($attrs['city'] ?? '');
        if ($city === '') {
            return $this->errorResult('Please enter a city to get an estimate.');
        }

        // Progressively broaden the comp search until we have enough data points
        // to be meaningfully accurate — never guess with fewer than 3 comps.
        [$comps, $matchLevel] = $this->findComps($attrs);

        if ($comps->count() < 3) {
            return $this->errorResult(
                "We don't have enough listed properties matching this in {$city} yet to give a reliable estimate. Try a nearby city, or contact our team for a manual valuation."
            );
        }

        $stats = $this->computeStats($comps, $attrs);

        $explanation = $this->explainWithAi($attrs, $stats, $matchLevel, $comps->count());

        return [
            'error' => false,
            'estimated_low' => $stats['estimated_low'],
            'estimated_high' => $stats['estimated_high'],
            'avg_price_per_sqft' => $stats['avg_price_per_sqft'],
            'comp_count' => $comps->count(),
            'match_level' => $matchLevel,
            'explanation' => $explanation,
        ];
    }

    /**
     * Tries narrow-to-broad matching: locality+BHK+type -> city+BHK+type -> city+type -> city only.
     * Returns [Collection $comps, string $matchLevel].
     */
    protected function findComps(array $attrs): array
    {
        $city = $attrs['city'];
        $locality = $attrs['locality'] ?? null;
        $bhk = $attrs['bhk_type'] ?? null;
        $propertyType = $attrs['property_type'] ?? null;

        $levels = [];

        if ($locality) {
            $levels[] = ['label' => 'locality + type match', 'locality' => $locality, 'bhk' => $bhk, 'type' => $propertyType];
        }
        $levels[] = ['label' => 'city + type match', 'locality' => null, 'bhk' => $bhk, 'type' => $propertyType];
        $levels[] = ['label' => 'city-wide match (broader)', 'locality' => null, 'bhk' => null, 'type' => null];

        foreach ($levels as $level) {
            $query = Property::query()
                ->where('status', 'Available')
                ->whereIn('listing_status', ['active', 'published'])
                ->whereIn('looking_for', self::SALE_VALUES)
                ->where('city', 'like', "%{$city}%")
                ->whereNotNull('price')
                ->where('price', '>', 0);

            if ($level['locality']) {
                $query->where(function ($q) use ($level) {
                    $q->where('locality', 'like', "%{$level['locality']}%")
                      ->orWhere('sub_locality', 'like', "%{$level['locality']}%");
                });
            }
            if ($level['bhk']) {
                $query->where('bhk_type', $level['bhk']);
            }
            if ($level['type']) {
                $query->where('property_type', $level['type']);
            }

            $comps = $query->limit(50)->get(['id', 'price', 'price_per_sqft', 'super_builtup_area', 'carpet_area', 'builtup_area', 'bhk_type', 'city', 'locality']);

            if ($comps->count() >= 3) {
                return [$comps, $level['label']];
            }
        }

        // Return whatever the broadest search found, even if under 3 — caller handles the "not enough" case.
        return [$comps ?? collect(), 'city-wide match (broader)'];
    }

    protected function computeStats($comps, array $attrs): array
    {
        // Derive price-per-sqft per comp, preferring stored value, else compute from price/area.
        $pricesPerSqft = $comps->map(function ($p) {
            if ($p->price_per_sqft && $p->price_per_sqft > 0) {
                return (float) $p->price_per_sqft;
            }
            $area = $p->super_builtup_area ?: $p->builtup_area ?: $p->carpet_area;
            if ($area && $area > 0 && $p->price > 0) {
                return (float) $p->price / (float) $area;
            }
            return null;
        })->filter()->values();

        $avgPsf = $pricesPerSqft->isNotEmpty() ? $pricesPerSqft->avg() : null;
        $minPsf = $pricesPerSqft->isNotEmpty() ? $pricesPerSqft->min() : null;
        $maxPsf = $pricesPerSqft->isNotEmpty() ? $pricesPerSqft->max() : null;

        $userArea = isset($attrs['area']) && $attrs['area'] !== '' ? (float) $attrs['area'] : null;

        if ($userArea && $avgPsf) {
            // Use the actual comp range (min/max psf) applied to the user's area for a realistic band.
            $low = round(($minPsf ?: $avgPsf * 0.9) * $userArea);
            $high = round(($maxPsf ?: $avgPsf * 1.1) * $userArea);
        } else {
            // No area given — fall back to the comps' own price range.
            $low = round($comps->min('price'));
            $high = round($comps->max('price'));
        }

        return [
            'estimated_low' => $low,
            'estimated_high' => $high,
            'avg_price_per_sqft' => $avgPsf ? round($avgPsf) : null,
        ];
    }

    protected function explainWithAi(array $attrs, array $stats, string $matchLevel, int $compCount): ?string
    {
        if (!$this->client->isConfigured()) {
            return null; // Numbers still work without AI — explanation is a nice-to-have, not required.
        }

        $systemPrompt = <<<PROMPT
You explain property price estimates for IndianEstHub, a real estate platform in the Chandigarh Tricity region.

You are given an ALREADY-COMPUTED price estimate based on real comparable listings — you must NOT change, recalculate, or contradict these numbers. Your only job is to explain them in 2-3 warm, plain-English sentences a non-expert would understand.

Rules:
- Use the exact numbers given below, formatted naturally (e.g. "₹85 lakh" or "₹1.2 crore" for Indian readers).
- Mention how many comparable listings this is based on, and be honest that it's an estimate, not a formal valuation.
- Do not invent additional facts, locations, or market trends not given below.
- No markdown, no bullet points — 2-3 sentences of plain text only.

Computed data:
- City: {$attrs['city']}
- Property type / BHK: {$this->describeType($attrs)}
- Match basis: {$matchLevel}
- Number of comparable listings used: {$compCount}
- Estimated price range: ₹{$stats['estimated_low']} to ₹{$stats['estimated_high']}
- Average price per sq.ft. in comps: {$stats['avg_price_per_sqft']}
PROMPT;

        $result = $this->client->generateOnce(
            $systemPrompt,
            'Write the explanation now.',
            ['temperature' => 0.5, 'maxOutputTokens' => 200]
        );

        return $result['error'] ? null : $result['text'];
    }

    protected function describeType(array $attrs): string
    {
        return trim(($attrs['bhk_type'] ?? '') . ' ' . ($attrs['property_type'] ?? '')) ?: 'Residential property';
    }

    protected function errorResult(string $message): array
    {
        return [
            'error' => true,
            'message' => $message,
            'estimated_low' => null,
            'estimated_high' => null,
            'avg_price_per_sqft' => null,
            'comp_count' => 0,
            'match_level' => null,
            'explanation' => null,
        ];
    }
}
