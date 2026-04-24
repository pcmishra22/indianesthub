@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', $project->title . ' by ' . ($project->builder->company_name ?? $project->builder->name ?? 'Builder') . ' | ' . config('app.name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 155, '...') ?: ($project->title . ' – New residential project in ' . ($project->city ?? 'Tricity') . '. Starting from ₹' . number_format($project->price_from ?? 0) . '. Explore floor plans, amenities & more on ' . config('app.name') . '.'))
@section('canonical', route('projects.show', $project->id))
@section('og_title', $project->title . ' | New Project | ' . config('app.name'))
@section('og_description', 'Explore ' . $project->title . ' in ' . ($project->city ?? 'Tricity') . '. Starting from ₹' . number_format($project->price_from ?? 0) . '. RERA registered project.')
@section('og_url', route('projects.show', $project->id))
@section('og_image', $project->cover_image ? asset('storage/' . $project->cover_image) : asset('assets/img/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Residence",
  "name": "{{ addslashes($project->title) }}",
  "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 200)) }}",
  "url": "{{ route('projects.show', $project->id) }}",
  "image": "{{ $project->cover_image ? asset('storage/'.$project->cover_image) : asset('assets/img/og-default.jpg') }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($project->address ?? '') }}",
    "addressLocality": "{{ $project->city ?? '' }}",
    "addressRegion": "{{ $project->state ?? 'Punjab' }}",
    "addressCountry": "IN"
  },
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "{{ $project->price_from ?? 0 }}",
    "highPrice": "{{ $project->price_to ?? 0 }}",
    "priceCurrency": "INR"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Builders","item":"{{ route('builders.index') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $project->builder->company_name ?? $project->builder->name ?? 'Builder' }}","item":"{{ $project->builder->slug ? route('builders.show', $project->builder->slug) : route('builders.index') }}"},
    {"@type":"ListItem","position":4,"name":"{{ addslashes($project->title) }}","item":"{{ route('projects.show', $project->id) }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.project-detail')
@endsection
