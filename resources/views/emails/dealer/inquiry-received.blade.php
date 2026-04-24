@extends('emails.layouts.base')

@section('hero_title', '🔔 New Inquiry Received!')
@section('hero_sub', 'A potential buyer is interested in your property. Respond within 1 hour for best results.')

@section('content')
<p>Hi <strong>{{ $inquiry->broker->first_name ?? 'there' }}</strong>,</p>
<p>You have received a new property inquiry on <strong>{{ config('app.name') }}</strong>. Here are the details:</p>

{{-- Property Card --}}
<div class="pc">
  <div class="pc-h"><span class="ptype">📍 Your Property</span></div>
  <div class="pc-b">
    <div class="pc-title">{{ $property->title }}</div>
    <div class="pc-loc">{{ $property->location ?? $property->city }}</div>
    <div class="pc-price">
      @if($property->price)
        ₹{{ number_format($property->price) }}
      @else
        Price on Request
      @endif
    </div>
  </div>
</div>

{{-- Buyer Details --}}
<h2>Buyer Details</h2>
<table class="dt">
  <tr><td>Name</td><td><strong>{{ $inquiry->name }}</strong></td></tr>
  <tr><td>Phone</td><td><a href="tel:+91{{ $inquiry->phone }}">+91 {{ $inquiry->phone }}</a></td></tr>
  <tr><td>Email</td><td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td></tr>
  <tr><td>Message</td><td>{{ $inquiry->message }}</td></tr>
  <tr><td>Received</td><td>{{ $inquiry->created_at->format('d M Y, h:i A') }}</td></tr>
</table>

<div class="wb"><p>⏱ <strong>Tip:</strong> Buyers who get a response within 60 minutes are 7× more likely to convert. Call or WhatsApp them now!</p></div>

<div class="bw">
  <a href="https://wa.me/91{{ $inquiry->phone }}?text=Hi {{ urlencode($inquiry->name) }}, I'm calling from {{ config('app.name') }} regarding your inquiry for {{ urlencode($property->title) }}." class="btn btn-wa">💬 WhatsApp Buyer Now</a>
  &nbsp;
  <a href="{{ route('dealer.inquiries') }}" class="btn-o">View All Inquiries</a>
</div>
@endsection
