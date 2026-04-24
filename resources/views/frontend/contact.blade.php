@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Contact ' . config('app.name') . ' – Real Estate Help in Chandigarh Tricity')
@section('meta_description', 'Contact ' . config('app.name') . ' for property queries, listing support and real estate assistance in Chandigarh, Mohali, Zirakpur & Panchkula. We\'re here to help you find your dream property.')
@section('canonical', route('contact'))
@section('og_title', 'Contact Us | ' . config('app.name') . ' – Real Estate Tricity')
@section('og_description', 'Get in touch with ' . config('app.name') . ' team for property listings, buyer assistance and real estate support in Chandigarh Tricity.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact {{ config('app.name') }}",
  "url": "{{ route('contact') }}"
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Contact","item":"{{ route('contact') }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.contact')
@endsection
