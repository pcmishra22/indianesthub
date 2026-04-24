@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', ($property->meta_title ?? $property->title) . ' | ' . config('app.name'))
@section('meta_description', $property->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($property->description ?? ''), 155, '...'))
@section('meta_keywords', ($property->search_tags ?? '') . ', ' . ($property->bhk_type ?? '') . ' ' . ($property->property_type ?? '') . ' in ' . ($property->city ?? '') . ', ' . ($property->locality ?? '') . ', ' . config('app.name'))
@section('canonical', route('property-details', $property->slug))
@section('og_type', 'product')
@section('og_title', ($property->meta_title ?? $property->title) . ' | ' . config('app.name'))
@section('og_description', $property->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($property->description ?? ''), 155, '...'))
@section('og_url', route('property-details', $property->slug))
@section('og_image', $property->cover_image ? asset('storage/' . $property->cover_image) : asset('assets/img/og-default.jpg'))
@section('twitter_title', ($property->meta_title ?? $property->title) . ' | ' . config('app.name'))
@section('twitter_description', $property->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($property->description ?? ''), 155, '...'))
@section('twitter_image', $property->cover_image ? asset('storage/' . $property->cover_image) : asset('assets/img/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "RealEstateListing",
  "name": "{{ addslashes($property->meta_title ?? $property->title) }}",
  "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($property->description ?? ''), 200)) }}",
  "url": "{{ route('property-details', $property->slug) }}",
  "image": "{{ $property->cover_image ? asset('storage/'.$property->cover_image) : asset('assets/img/og-default.jpg') }}",
  "datePosted": "{{ $property->created_at->toIso8601String() }}",
  "dateModified": "{{ $property->updated_at->toIso8601String() }}",
  "offers": {
    "@type": "Offer",
    "price": "{{ $property->price ?? 0 }}",
    "priceCurrency": "INR",
    "availability": "https://schema.org/InStock"
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($property->address ?? '') }}",
    "addressLocality": "{{ $property->locality ?? $property->city ?? '' }}",
    "addressRegion": "{{ $property->city ?? '' }}",
    "postalCode": "{{ $property->pincode ?? '' }}",
    "addressCountry": "IN"
  }@if($property->latitude && $property->longitude),
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "{{ $property->latitude }}",
    "longitude": "{{ $property->longitude }}"
  }@endif,
  "numberOfRooms": {{ $property->bedrooms ?? 'null' }},
  "floorSize": {
    "@type": "QuantitativeValue",
    "value": "{{ $property->area ?? $property->builtup_area ?? '' }}",
    "unitCode": "FTK"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Properties","item":"{{ route('properties') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $property->city ?? 'Tricity' }}","item":"{{ $property->city ? url('/properties?city='.urlencode($property->city)) : route('properties') }}"},
    {"@type":"ListItem","position":4,"name":"{{ addslashes($property->title) }}","item":"{{ route('property-details', $property->slug) }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.property-details')
@endsection
