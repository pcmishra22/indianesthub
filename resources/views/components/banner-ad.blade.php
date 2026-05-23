@props(['placement'])

@php
    $banner = null;
    if (isset($servedBanners) && $servedBanners instanceof \Illuminate\Support\Collection) {
        // not expected, but keep safe
    }

    if (isset($servedBanners) && is_array($servedBanners)) {
        $banner = $servedBanners[$placement] ?? null;
    }

    // Fallback: direct variable for known placements
    if (!$banner) {
        $map = [
            'homepage_top' => $homepageTopBanner ?? null,
            'homepage_middle' => $homepageMiddleBanner ?? null,
            'property_sidebar' => $propertySidebarBanner ?? null,
            'blog_inline' => $blogInlineBanner ?? null,
        ];
        $banner = $map[$placement] ?? null;
    }
@endphp

@if($banner)
    <a
        href="{{ route('banner.click', $banner->id) }}"
        target="_blank"
        rel="noopener"
        class="track-banner d-block"
        data-banner="{{ $banner->id }}"
        style="text-decoration:none;"
    >
        <img
            src="{{ asset('storage/' . $banner->image) }}"
            alt="{{ $banner->title }}"
            loading="lazy"
            style="width:100%;height:auto;display:block;"
        />
    </a>
@endif

