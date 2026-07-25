@extends('emails.layouts.base')

@section('hero_title', $property->title)
@section('hero_sub', collect([$property->locality, $property->city])->filter()->implode(', '))

@section('content')

    <div style="white-space: pre-wrap; margin-bottom: 20px;">{{ $message }}</div>

    <div class="pc">
        <div class="pc-h">
            <span class="ptype">{{ $property->listing_type ?? 'Property' }}</span>
        </div>
        <div class="pc-b">
            @if($property->cover_image)
                <img src="{{ asset('storage/' . $property->cover_image) }}" alt="{{ $property->title }}"
                     style="width:100%;height:220px;object-fit:cover;border-radius:8px;margin-bottom:14px;">
            @endif
            <div class="pc-title">{{ $property->title }}</div>
            <div class="pc-loc">📍 {{ collect([$property->locality, $property->city])->filter()->implode(', ') ?: $property->address }}</div>
            @if($property->price ?: $property->expected_price)
                <div class="pc-price">₹{{ number_format($property->price ?: $property->expected_price) }}</div>
            @endif
            <div class="pc-meta">
                {{ collect([$property->bhk_type, $property->area ? $property->area.' sqft' : null])->filter()->implode(' · ') }}
            </div>
        </div>
    </div>

    <div class="bw">
        <a href="{{ $publicUrl }}" class="btn btn-p">View Full Listing</a>
    </div>

    @if($senderName || $senderPhone)
        <hr class="dv">
        <p>
            @if($senderName) <strong>{{ $senderName }}</strong><br> @endif
            @if($senderPhone) 📞 {{ $senderPhone }} @endif
        </p>
    @endif

@endsection
