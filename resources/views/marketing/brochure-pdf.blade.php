<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $property->title }} - Brochure</title>
    <style>
        @page { margin: 0px; }
        body { font-family: DejaVu Sans, sans-serif; color: #222; margin: 0; }

        .header-bar { background-color: #1e3a5f; color: #fff; padding: 18px 30px; }
        .header-bar .company { font-size: 12px; letter-spacing: 1px; text-transform: uppercase; opacity: 0.85; }
        .header-bar .title { font-size: 22px; font-weight: bold; margin-top: 4px; }
        .header-bar .price { font-size: 16px; margin-top: 6px; color: #ffd166; font-weight: bold; }

        .cover-img { width: 100%; height: 260px; }
        .cover-img img { width: 100%; height: 260px; object-fit: cover; }

        .content { padding: 20px 30px; }

        .section-title { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #1e3a5f;
            border-bottom: 2px solid #1e3a5f; padding-bottom: 4px; margin: 14px 0 10px 0; }

        table.specs { width: 100%; border-collapse: collapse; font-size: 11px; }
        table.specs td { padding: 5px 8px; border: 1px solid #e2e2e2; width: 25%; }
        table.specs td.label { color: #777; background: #f7f7f7; font-weight: bold; width: 25%; }

        .amenities span { display: inline-block; background: #eef3f8; color: #1e3a5f; border-radius: 3px;
            padding: 4px 10px; margin: 0 6px 6px 0; font-size: 10px; }

        table.gallery { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.gallery td { width: 20%; padding: 3px; }
        table.gallery img { width: 100%; height: 80px; object-fit: cover; border-radius: 3px; }

        .description { font-size: 11px; line-height: 1.5; color: #444; }

        .contact-box { margin-top: 16px; background: #f7f9fb; border: 1px solid #e2e2e2; border-radius: 4px;
            padding: 12px 16px; }
        .contact-box .name { font-size: 13px; font-weight: bold; color: #1e3a5f; }
        .contact-box .line { font-size: 11px; color: #444; margin-top: 2px; }
        .contact-box .link { font-size: 10px; color: #1e6fd9; margin-top: 6px; }

        .footer { font-size: 9px; color: #999; text-align: center; padding: 10px 30px; }
    </style>
</head>
<body>

    <div class="header-bar">
        @if($companyName)
            <div class="company">{{ $companyName }}</div>
        @endif
        <div class="title">{{ $property->title }}</div>
        <div class="price">
            @if($property->price)
                ₹ {{ number_format($property->price) }}
            @elseif($property->expected_price)
                ₹ {{ number_format($property->expected_price) }}
            @endif
            @if($property->listing_type) &middot; {{ $property->listing_type }} @endif
        </div>
    </div>

    @if($property->cover_image)
        <div class="cover-img">
            <img src="{{ public_path('storage/' . $property->cover_image) }}">
        </div>
    @endif

    <div class="content">

        <div class="section-title">Overview</div>
        <table class="specs">
            <tr>
                <td class="label">Type</td><td>{{ $property->property_type ?? '—' }} {{ $property->bhk_type ? '('.$property->bhk_type.')' : '' }}</td>
                <td class="label">Area</td><td>{{ $property->area ? $property->area . ' sqft' : '—' }}</td>
            </tr>
            <tr>
                <td class="label">Bedrooms</td><td>{{ $property->bedrooms ?? '—' }}</td>
                <td class="label">Bathrooms</td><td>{{ $property->bathrooms ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Floor</td><td>{{ $property->floor_number ?? '—' }}</td>
                <td class="label">Furnishing</td><td>{{ $property->furnishing_status ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Location</td>
                <td colspan="3">{{ collect([$property->locality, $property->city, $property->state])->filter()->implode(', ') ?: ($property->address ?? '—') }}</td>
            </tr>
        </table>

        @if($amenities->isNotEmpty())
            <div class="section-title">Amenities</div>
            <div class="amenities">
                @foreach($amenities as $amenity)
                    <span>{{ $amenity }}</span>
                @endforeach
            </div>
        @endif

        @if($property->description)
            <div class="section-title">Description</div>
            <div class="description">{{ \Illuminate\Support\Str::limit(strip_tags($property->description), 600) }}</div>
        @endif

        @if($galleryImages->isNotEmpty())
            <div class="section-title">Gallery</div>
            <table class="gallery">
                <tr>
                    @foreach($galleryImages as $img)
                        <td><img src="{{ public_path('storage/' . $img) }}"></td>
                    @endforeach
                </tr>
            </table>
        @endif

        <div class="contact-box">
            @if($contactName)
                <div class="name">{{ $contactName }}</div>
            @endif
            @if($contactPhone)
                <div class="line">Call/WhatsApp: {{ $contactPhone }}</div>
            @endif
            @if($publicUrl)
                <div class="link">View full listing: {{ $publicUrl }}</div>
            @endif
        </div>

    </div>

    <div class="footer">Generated on {{ $generatedAt->format('d M Y') }} &middot; {{ config('app.name') }}</div>

</body>
</html>
