<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
          http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

  {{-- ═══════════════════════════════════════════════
       1. STATIC HIGH-PRIORITY PAGES  (priority 0.9)
  ═══════════════════════════════════════════════ --}}
  <url>
    <loc>{{ url('/') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>{{ url('/properties') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{{ url('/builders') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{{ url('/agents') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{{ url('/blog') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/services') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/about') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <url>
    <loc>{{ url('/contact') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>

  {{-- ═══════════════════════════════════════════════
       2. LOCATION PAGES  /properties/in/{slug}  (0.8)
  ═══════════════════════════════════════════════ --}}
  @foreach($locations as $locSlug)
  <url>
    <loc>{{ url('/properties/in/' . $locSlug) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  @endforeach

  {{-- ═══════════════════════════════════════════════
       3. HYPERLOCAL SEO LANDING PAGES  (0.85)
         /flats-in-{city}
         /plots-in-{city}
         /villas-in-{city}
         /rent-flats-in-{city}
         /new-projects-in-{city}
         /ready-to-move-flats-{city}
         /1bhk-flats-in-{city}  …  /4bhk-flats-in-{city}
  ═══════════════════════════════════════════════ --}}
  @foreach($landingCities as $city)
  <url>
    <loc>{{ url('/flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.85</priority>
  </url>
  <url>
    <loc>{{ url('/plots-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/villas-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/rent-flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/new-projects-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.85</priority>
  </url>
  <url>
    <loc>{{ url('/ready-to-move-flats-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  @foreach([1,2,3,4] as $bhk)
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.82</priority>
  </url>
  @endforeach

  {{-- Extended property types --}}
  <url>
    <loc>{{ url('/independent-house-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  <url>
    <loc>{{ url('/duplex-house-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>
  <url>
    <loc>{{ url('/commercial-property-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>
  <url>
    <loc>{{ url('/shops-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>
  <url>
    <loc>{{ url('/office-space-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>
  <url>
    <loc>{{ url('/sco-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>

  {{-- Special filter pages --}}
  <url>
    <loc>{{ url('/gated-society-flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  <url>
    <loc>{{ url('/luxury-flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  <url>
    <loc>{{ url('/affordable-flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  <url>
    <loc>{{ url('/resale-flats-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>

  {{-- Buyer intent pages --}}
  <url>
    <loc>{{ url('/property-dealers-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.83</priority>
  </url>
  <url>
    <loc>{{ url('/real-estate-agents-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.82</priority>
  </url>
  <url>
    <loc>{{ url('/upcoming-projects-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.83</priority>
  </url>
  <url>
    <loc>{{ url('/rera-approved-projects-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.82</priority>
  </url>
  <url>
    <loc>{{ url('/investment-property-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  <url>
    <loc>{{ url('/best-residential-projects-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.82</priority>
  </url>

  {{-- Extended rental pages --}}
  <url>
    <loc>{{ url('/house-for-rent-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>
  <url>
    <loc>{{ url('/commercial-shop-for-rent-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>
  @foreach([1,2,3] as $bhk)
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-flat-for-rent-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>
  @endforeach
  @foreach([2,3] as $bhk)
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-house-for-rent-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-villa-for-rent-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>
  @endforeach

  {{-- BHK × Property Type (Sale) --}}
  @foreach([2,3,4] as $bhk)
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-house-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-villa-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-duplex-for-sale-in-' . $city) }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.76</priority>
  </url>
  @endforeach

  {{-- Budget-based pages (high-conversion keywords) --}}
  @foreach([30, 40, 50, 60] as $lakh)
  <url>
    <loc>{{ url('/flats-in-' . $city . '-under-' . $lakh . '-lakh') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.82</priority>
  </url>
  @endforeach
  @foreach([25, 30, 50] as $lakh)
  <url>
    <loc>{{ url('/plots-in-' . $city . '-under-' . $lakh . '-lakh') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  @endforeach
  @foreach([2, 3] as $bhk)
  @foreach([40, 50, 60] as $lakh)
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-flats-in-' . $city . '-under-' . $lakh . '-lakh') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.82</priority>
  </url>
  @endforeach
  @endforeach
  <url>
    <loc>{{ url('/villa-in-' . $city . '-under-1-cr') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.78</priority>
  </url>
  @foreach([2,3] as $bhk)
  @foreach([50, 75] as $lakh)
  <url>
    <loc>{{ url('/' . $bhk . 'bhk-house-in-' . $city . '-under-' . $lakh . '-lakh') }}</loc>
    <lastmod>{{ now()->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.80</priority>
  </url>
  @endforeach
  @endforeach
  @endforeach

  {{-- ═══════════════════════════════════════════════
       4. INDIVIDUAL PROPERTY PAGES  /properties/{slug}  (0.7)
  ═══════════════════════════════════════════════ --}}
  @foreach($properties as $property)
  <url>
    <loc>{{ url('/properties/' . $property->slug) }}</loc>
    <lastmod>{{ $property->updated_at->toAtomString() }}</lastmod>
    <changefreq>daily</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach

  {{-- ═══════════════════════════════════════════════
       5. DEALER / AGENT PROFILE PAGES  /agent-profile/{slug}  (0.6)
  ═══════════════════════════════════════════════ --}}
  @foreach($dealers as $dealer)
  <url>
    <loc>{{ url('/agent-profile/' . $dealer->slug) }}</loc>
    <lastmod>{{ $dealer->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
  </url>
  @endforeach

  {{-- ═══════════════════════════════════════════════
       6. BUILDER PROFILE PAGES  /builders/{slug}  (0.7)
  ═══════════════════════════════════════════════ --}}
  @foreach($builders as $builder)
  <url>
    <loc>{{ url('/builders/' . $builder->slug) }}</loc>
    <lastmod>{{ $builder->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  @endforeach

  {{-- ═══════════════════════════════════════════════
       7. BUILDER PROJECT PAGES  /projects/{id}  (0.75)
  ═══════════════════════════════════════════════ --}}
  @foreach($projects as $project)
  <url>
    <loc>{{ url('/projects/' . $project->id) }}</loc>
    <lastmod>{{ $project->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.75</priority>
  </url>
  @endforeach

  {{-- ═══════════════════════════════════════════════
       8. BLOG POST PAGES  /blog/{slug}  (0.6)
  ═══════════════════════════════════════════════ --}}
  @foreach($blogs as $blog)
  <url>
    <loc>{{ url('/blog/' . $blog->slug) }}</loc>
    <lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
  </url>
  @endforeach

</urlset>
