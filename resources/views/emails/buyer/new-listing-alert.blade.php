@extends('emails.layouts.base')

@section('hero_title', '🏠 New Properties Match Your Search!')
@section('hero_sub', '{{ $properties->count() }} new listings found for {{ $searchLabel }}')

@section('content')
<p>Hi <strong>{{ $recipientName }}</strong>,</p>
<p>We found <strong>{{ $properties->count() }} new properties</strong> matching <strong>{{ $searchLabel }}</strong> on {{ config('app.name') }}. These are fresh listings — act fast as popular properties go quickly!</p>

@foreach($properties->take(5) as $property)
<div class="pc">
  <div class="pc-h"><span class="ptype">{{ $property->looking_for ?? 'Sale' }} · {{ $property->property_type ?? 'Property' }}</span></div>
  <div class="pc-b">
    <div class="pc-title">{{ $property->title }}</div>
    <div class="pc-loc">📍 {{ $property->location ?? $property->city }}</div>
    <div class="pc-price">
      @if($property->price) ₹{{ number_format($property->price) }} @else Price on Request @endif
    </div>
    <div class="pc-meta">
      @if($property->bedrooms)🛏 {{ $property->bedrooms }} BHK &nbsp;@endif
      @if($property->area)📐 {{ $property->area }} sq.ft@endif
      &nbsp;·&nbsp;
      <a href="{{ route('property-details', $property->slug) }}">View Details →</a>
    </div>
  </div>
</div>
@endforeach

@if($properties->count() > 5)
<p style="text-align:center; color:#64748b; font-size:14px;">+ {{ $properties->count() - 5 }} more matching properties</p>
@endif

<div class="bw">
  <a href="{{ url('/properties') }}" class="btn btn-p">View All Matching Properties →</a>
</div>

<hr class="dv">
<p style="font-size:13px;color:#94a3b8;text-align:center;">
  Don't want these alerts? <a href="{{ url('/my/dashboard') }}">Manage your preferences</a> in your account.
</p>
@endsection

@section('unsubscribe')
<div class="unsub"><a href="{{ url('/my/dashboard') }}">Unsubscribe from property alerts</a></div>
@endsection
