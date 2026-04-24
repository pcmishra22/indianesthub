@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', 'Builders & Developers in Chandigarh Tricity | New Projects | ' . config('app.name'))
@section('meta_description', 'Find ' . $totalBuilders . ' verified builders and developers in Chandigarh, Mohali, Zirakpur & Panchkula. Browse ' . $totalProjects . ' new residential & commercial projects on ' . config('app.name') . '.')
@section('canonical', route('builders.index'))
@section('og_title', 'Top Builders & Developers in Tricity | ' . config('app.name'))
@section('og_description', 'Explore verified builders and new launch projects in Chandigarh Tricity. Find RERA-registered developers with premium residential projects.')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Builders & Developers","item":"{{ route('builders.index') }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.builders')
@endsection
