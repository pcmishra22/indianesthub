@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', ($builder->company_name ?: $builder->name) . ' – Builder & Developer | ' . config('app.name'))
@section('meta_description', ($builder->company_name ?: $builder->name) . ' is a ' . ($builder->is_verified ? 'RERA-verified' : 'verified') . ' builder in ' . ($builder->city ?? 'Tricity') . '. Browse ' . $builder->projects_count . ' projects. Find flats, villas and residential projects on ' . config('app.name') . '.')
@section('canonical', route('builders.show', $builder->slug))
@section('og_title', ($builder->company_name ?: $builder->name) . ' | Builder Profile | ' . config('app.name'))
@section('og_description', 'Explore ' . ($builder->company_name ?: $builder->name) . '\'s projects in ' . ($builder->city ?? 'Tricity') . '. ' . $builder->projects_count . ' projects listed on ' . config('app.name') . '.')
@section('og_url', route('builders.show', $builder->slug))
@section('og_image', $builder->logo ? asset('storage/' . $builder->logo) : asset('assets/img/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "{{ $builder->company_name ?: $builder->name }}",
  "description": "{{ \Illuminate\Support\Str::limit(strip_tags($builder->description ?? ''), 200) }}",
  "url": "{{ route('builders.show', $builder->slug) }}",
  "image": "{{ $builder->logo ? asset('storage/'.$builder->logo) : asset('assets/img/og-default.jpg') }}",
  "telephone": "{{ $builder->phone }}",
  "email": "{{ $builder->email }}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "{{ $builder->city ?? '' }}",
    "addressRegion": "Punjab",
    "addressCountry": "IN"
  },
  "foundingDate": "{{ $builder->established_year ?? '' }}",
  "numberOfEmployees": {
    "@type": "QuantitativeValue",
    "description": "Real Estate Developer"
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
    {"@type":"ListItem","position":3,"name":"{{ $builder->company_name ?: $builder->name }}","item":"{{ route('builders.show', $builder->slug) }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.builder-profile')
@endsection
