@extends('frontend.layout')

@php
    // Build a canonical URL that includes relevant filters and pagination (except page 1)
    $params = request()->query();
    
    // Remove redundant page=1 parameter
    if (isset($params['page']) && $params['page'] <= 1) {
        unset($params['page']);
    }

    // Determine the base route (SEO-friendly location path or general properties path)
    $baseCanonical = !empty($locationLabel) 
        ? route('properties.location', strtolower(str_replace([' ', '/'], '-', $locationLabel)))
        : route('properties');
        
    $canonicalUrl = count($params) ? $baseCanonical . '?' . http_build_query($params) : $baseCanonical;
@endphp

{{-- ════════════════════════ SEO META (dynamic for location pages) ════════════════════════ --}}
@if(!empty($locationLabel))
  @section('title', 'Properties in ' . $locationLabel . ' | Buy & Rent Flats | ' . config('app.name'))
  @section('meta_description', 'Browse ' . $properties->total() . ' verified properties in ' . $locationLabel . ' and nearby areas within ' . ($locationRadius ?? 10) . ' km. Find flats, villas & plots for sale & rent on ' . config('app.name') . '.')
  @section('canonical', $canonicalUrl)
  @section('og_title', 'Properties in ' . $locationLabel . ' | ' . config('app.name'))
  @section('og_description', 'Discover verified flats, villas & plots for sale and rent in ' . $locationLabel . '. Browse ' . $properties->total() . ' listings with photos and floor plans.')
@else
  @section('title', 'Properties for Sale & Rent in Chandigarh Tricity | ' . config('app.name'))
  @section('meta_description', 'Browse ' . $properties->total() . ' verified properties for sale & rent in Chandigarh, Mohali, Zirakpur & Panchkula. Filter by BHK, budget, location and property type on ' . config('app.name') . '.')
  @section('canonical', $canonicalUrl)
  @section('og_title', 'Properties for Sale & Rent in Tricity | ' . config('app.name'))
  @section('og_description', 'Find verified flats, villas, plots and commercial properties across Chandigarh Tricity. ' . $properties->total() . ' listings with photos and agent contact.')
@endif

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Properties","item":"{{ route('properties') }}"}
    @if(!empty($locationLabel))
    ,{"@type":"ListItem","position":3,"name":"{{ $locationLabel }}","item":"{{ url()->current() }}"}
    @endif
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.properties', [
    'properties' => $properties,
    'cities' => $cities,
    'propertyTypes' => $propertyTypes,
    'builderProjects' => $builderProjects ?? collect(),
    'builderProjectsCityUrl' => $builderProjectsCityUrl ?? null,
    'locationLabel' => $locationLabel ?? null,
])
@endsection
