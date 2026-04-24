@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'About ' . config('app.name') . ' – Tricity\'s Most Trusted Real Estate Portal')
@section('meta_description', config('app.name') . ' is Chandigarh Tricity\'s most trusted real estate portal connecting buyers, sellers and renters with verified property listings across Chandigarh, Mohali, Zirakpur & Panchkula.')
@section('canonical', route('about'))
@section('og_title', 'About ' . config('app.name') . ' | Real Estate Portal Tricity')
@section('og_description', 'Learn about ' . config('app.name') . ' – Tricity\'s most trusted real estate platform helping thousands find their dream home.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"About Us","item":"{{ route('about') }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.about')
@endsection
