@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Real Estate Agents in Chandigarh Tricity | Verified Dealers | ' . config('app.name'))
@section('meta_description', 'Find verified real estate agents and property dealers in Chandigarh, Mohali, Zirakpur & Panchkula. Connect with trusted experts for buying, selling or renting property in Tricity.')
@section('canonical', route('agents'))
@section('og_title', 'Top Real Estate Agents in Tricity | ' . config('app.name'))
@section('og_description', 'Connect with verified property dealers and real estate agents across Chandigarh, Mohali, Zirakpur & Panchkula on ' . config('app.name') . '.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Real Estate Agents","item":"{{ route('agents') }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.agents')
@endsection
