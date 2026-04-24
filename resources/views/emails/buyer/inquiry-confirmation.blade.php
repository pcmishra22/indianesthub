@extends('emails.layouts.base')

@section('hero_title', '✅ Your Inquiry Has Been Received!')
@section('hero_sub', 'The agent will contact you shortly. Here\'s a summary of your request.')

@section('content')
<p>Hi <strong>{{ $inquiry->name }}</strong>,</p>
<p>Thank you for your interest! Your inquiry has been successfully submitted to the property agent on <strong>{{ config('app.name') }}</strong>. You should hear back within a few hours.</p>

<div class="sb"><p>✅ Inquiry submitted successfully at {{ $inquiry->created_at->format('d M Y, h:i A') }}</p></div>

{{-- Property Summary --}}
<h2>Property You Enquired About</h2>
<div class="pc">
  <div class="pc-h"><span class="ptype">🏠 Property Details</span></div>
  <div class="pc-b">
    <div class="pc-title">{{ $property->title }}</div>
    <div class="pc-loc">📍 {{ $property->location ?? $property->city }}</div>
    <div class="pc-price">
      @if($property->price)
        ₹{{ number_format($property->price) }}
      @else
        Price on Request
      @endif
    </div>
    <div class="pc-meta">
      @if($property->bedrooms) 🛏 {{ $property->bedrooms }} BHK &nbsp;@endif
      @if($property->property_type) · {{ $property->property_type }}@endif
    </div>
  </div>
</div>

<div class="bw">
  <a href="{{ route('property-details', $property->slug) }}" class="btn btn-p">View Property Details →</a>
</div>

<hr class="dv">

<h3>💡 While You Wait</h3>
<div class="al">
  <a href="{{ url('/flats-in-'.strtolower($property->city ?? 'zirakpur')) }}">More in {{ $property->city ?? 'Tricity' }}</a>
  <a href="{{ url('/properties') }}">Browse All Properties</a>
  <a href="{{ url('/pricing') }}">Check Loan Eligibility</a>
</div>

<p style="font-size:13px;color:#94a3b8;text-align:center;">
  In a hurry? <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}">WhatsApp our team</a> for instant help.
</p>
@endsection
