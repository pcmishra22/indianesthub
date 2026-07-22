<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- 1. Static Pages --}}
    @foreach($staticPages as $page)
    <url>
        <loc>{{ route($page) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>{{ $page === 'home' ? '1.0' : '0.8' }}</priority>
    </url>
    @endforeach

    {{-- 2. Hyperlocal SEO Landing Pages --}}
    @foreach($landingCities as $city)
    <url>
        <loc>{{ route('seo.flats.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('seo.projects.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('seo.rtm.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('seo.plots.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('seo.villas.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('seo.rent.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach([2, 3] as $bhk)
    <url>
        <loc>{{ route('seo.bhk.city', ['bhk' => $bhk, 'city' => $city]) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    <url>
        <loc>{{ route('seo.affordable.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('seo.resale.city', $city) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @if($city === 'zirakpur')
    <url>
        <loc>{{ route('seo.bhk.budget', ['bhk' => 2, 'city' => 'zirakpur', 'amount' => 50]) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('seo.bhk.budget', ['bhk' => 3, 'city' => 'zirakpur', 'amount' => 80]) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif
    @endforeach

    {{-- 3. Location Search Pages --}}
    @foreach($locations as $loc)
    <url>
        <loc>{{ route('properties.location', $loc) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- 4. SEO Service Landing Pages --}}
    <url>
        <loc>{{ route('seo.loan') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('seo.insurance') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ route('seo.legal') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($landingCities as $city)
    <url>
        <loc>{{ route('seo.loan.city', $city) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('seo.insurance.city', $city) }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- 5. Property Details --}}
    @foreach($properties as $property)
    <url>
        <loc>{{ route('property-details', $property->slug) }}</loc>
        <lastmod>{{ $property->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- 6. Builder Projects --}}
    @foreach($projects as $project)
    @if(!empty($project->slug))
    <url>
        <loc>{{ route('projects.show', $project->slug) }}</loc>
        <lastmod>{{ $project->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endif
    @endforeach

    {{-- 7. Blog Posts --}}
    @foreach($blogs as $blog)
    <url>
        <loc>{{ route('blog.show', $blog->slug) }}</loc>
        <lastmod>{{ $blog->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- 8. Builder Profiles --}}
    @foreach($builders as $builder)
    <url>
        <loc>{{ route('builders.show', $builder->slug) }}</loc>
        <lastmod>{{ $builder->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- 9. Dealer/Agent Profiles --}}
    @foreach($dealers as $dealer)
    <url>
        <loc>{{ route('agent-profile', $dealer->slug) }}</loc>
        <lastmod>{{ $dealer->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

    {{-- 10. Service Provider Hub + Category Pages --}}
    <url>
        <loc>{{ route('services') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($serviceCategories as $cat)
    <url>
        <loc>{{ route('services.category', $cat) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($serviceCities as $citySlug => $cityLabel)
    <url>
        <loc>{{ route('services.category.city', ['category' => $cat, 'city' => $citySlug]) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
    @endforeach

    {{-- 11. Service Provider Public Profiles --}}
    @foreach($serviceProviders as $provider)
    <url>
        <loc>{{ route('services.profile', $provider->slug) }}</loc>
        <lastmod>{{ $provider->updated_at->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>