<?php

namespace App\Providers;

use App\Services\BannerService;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class BannerViewComposer
{
    public function compose(View $view): void
    {
        // Pre-fetch served banners for main homepage placements.
        // (You can expand this later for other pages/partials.)

        $placements = [
            'homepage_top',
            'homepage_middle',
            'property_sidebar',
            'blog_inline',
        ];

        $served = [];
        foreach ($placements as $placement) {
            $served[$placement] = Cache::remember(
                'served-banner-' . $placement,
                now()->addMinutes(5),
                function () use ($placement) {
                    return BannerService::getBanner($placement);
                }
            );
        }

        // Make available in blade
        $view->with('servedBanners', $served);
        $view->with('homepageTopBanner', $served['homepage_top'] ?? null);
        $view->with('homepageMiddleBanner', $served['homepage_middle'] ?? null);
        $view->with('propertySidebarBanner', $served['property_sidebar'] ?? null);
        $view->with('blogInlineBanner', $served['blog_inline'] ?? null);
    }
}

