@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Real Estate Blog – Tips, Guides & Market Updates | ' . config('app.name'))
@section('meta_description', 'Read the latest real estate news, property buying guides, investment tips, stamp duty guides and market updates for Chandigarh, Mohali, Zirakpur & Tricity on ' . config('app.name') . ' Blog.')
@section('canonical', route('blog'))
@section('og_title', config('app.name') . ' Real Estate Blog | Property News & Guides')
@section('og_description', 'Stay updated with real estate trends, home buying tips, stamp duty guides and market insights for Chandigarh Tricity region.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Blog","item":"{{ route('blog') }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.blog')
@endsection
