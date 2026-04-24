@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', $dealer->first_name . ' ' . $dealer->last_name . ' – Real Estate Agent | ' . config('app.name'))
@section('meta_description', 'Connect with ' . $dealer->first_name . ' ' . $dealer->last_name . ', a verified real estate agent' . ($dealer->company_name ? ' at ' . $dealer->company_name : '') . ' in Tricity. Browse ' . $dealer->properties_count . ' verified property listings on ' . config('app.name') . '.')
@section('canonical', route('agent-profile', $dealer->slug))
@section('og_title', $dealer->first_name . ' ' . $dealer->last_name . ' – Property Agent | ' . config('app.name'))
@section('og_description', 'Verified real estate agent with ' . $dealer->properties_count . ' listings. Find the best properties through ' . config('app.name') . '.')
@section('og_url', route('agent-profile', $dealer->slug))
@section('og_image', $dealer->profile_photo ? asset('storage/' . $dealer->profile_photo) : asset('assets/img/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Person",
  "name": "{{ $dealer->first_name }} {{ $dealer->last_name }}",
  "jobTitle": "Real Estate Agent",
  "url": "{{ route('agent-profile', $dealer->slug) }}",
  "image": "{{ $dealer->profile_photo ? asset('storage/'.$dealer->profile_photo) : asset('assets/img/og-default.jpg') }}",
  "email": "{{ $dealer->email }}",
  "telephone": "{{ $dealer->phone }}",
  @if($dealer->company_name)
  "worksFor": {
    "@type": "Organization",
    "name": "{{ $dealer->company_name }}"
  },
  @endif
  "knowsAbout": ["Real Estate", "Property Investment", "Tricity Properties"]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Agents","item":"{{ route('agents') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $dealer->first_name }} {{ $dealer->last_name }}","item":"{{ route('agent-profile', $dealer->slug) }}"}
  ]
}
</script>
@endsection

@section('content')
@include('frontend.partials.agent-profile')
@endsection
