<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Support\Str;

class BannerService
{
    /**
     * Get a banner for a given placement.
     *
     * Selection rules (ad-serving style):
     * - banner must be active (legacy `status` OR new `is_active`)
     * - banner must match `placement`
     * - banner must be within optional start/end date window
     * - pick top by priority, then randomly among equals
     */
    public static function getBanner(string $placement): ?Banner
    {
        $placement = Str::slug($placement, '_');

        $query = Banner::query()
            ->where('placement', $placement);

        // handle legacy status + new is_active
        $query->where(function ($q) {
            $q->where('status', true)
                ->orWhere(function ($qq) {
                    $qq->whereNotNull('is_active')->where('is_active', true);
                });
        });

        // date window
        $query->where(function ($q) {
            $q->whereNull('start_date')
                ->orWhere('start_date', '<=', now());
        });

        $query->where(function ($q) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', now());
        });

        // Priority-first selection with random tie-break
        // Approach: select banners with max priority, then random one.
        $topPriority = (clone $query)->max('priority');
        if (!$topPriority) {
            return null;
        }

        return (clone $query)
            ->where('priority', $topPriority)
            ->inRandomOrder()
            ->first();
    }
}

/**
 * Small internal helper to avoid adding Schema checks everywhere.
 */
class SchemaHasColumn
{
    public static function bannerIsActive(): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn('banners', 'is_active');
        } catch (\Throwable $e) {
            return false;
        }
    }
}

