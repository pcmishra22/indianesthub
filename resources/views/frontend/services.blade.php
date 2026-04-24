@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Real Estate Services – Home Loans, Insurance & More | ' . config('app.name'))
@section('meta_description', config('app.name') . ' offers end-to-end real estate services including property buying assistance, home loan eligibility, property insurance and legal guidance for Chandigarh Tricity.')
@section('canonical', route('services'))
@section('og_title', 'Real Estate Services in Tricity | ' . config('app.name'))
@section('og_description', 'Buy, sell, rent properties with expert help. Home loans, insurance, legal assistance and more from ' . config('app.name') . '.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Services","item":"{{ route('services') }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.services')
@endsection
