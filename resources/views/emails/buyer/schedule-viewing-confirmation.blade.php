@extends('emails.layouts.base')

@section('hero_title', '📅 Viewing Appointment Confirmed!')
@section('hero_sub', 'Your property visit has been scheduled. Save the date!')

@section('content')
<p>Hi <strong>{{ $schedule->name }}</strong>,</p>
<p>Your property viewing has been scheduled successfully on <strong>{{ config('app.name') }}</strong>. The agent will reach out to confirm the appointment details.</p>

<div class="sb"><p>✅ Viewing booked for {{ \Carbon\Carbon::parse($schedule->date)->format('l, d M Y') }} at {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</p></div>

{{-- Property Details --}}
<h2>Property You're Visiting</h2>
<div class="pc">
  <div class="pc-h"><span class="ptype">🏠 Property Details</span></div>
  <div class="pc-b">
    <div class="pc-title">{{ $property->title }}</div>
    <div class="pc-loc">📍 {{ $property->location ?? $property->city }}</div>
    <div class="pc-price">
      @if($property->price) ₹{{ number_format($property->price) }} @else Price on Request @endif
    </div>
  </div>
</div>

{{-- Appointment Details --}}
<h2>Appointment Details</h2>
<table class="dt">
  <tr><td>Date</td><td><strong>{{ \Carbon\Carbon::parse($schedule->date)->format('l, d M Y') }}</strong></td></tr>
  <tr><td>Time</td><td><strong>{{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</strong></td></tr>
  <tr><td>Your Name</td><td>{{ $schedule->name }}</td></tr>
  <tr><td>Contact</td><td>{{ $schedule->phone }}</td></tr>
</table>

<h2>✅ Checklist for Your Visit</h2>
<ul class="cl">
  <li>Carry a valid ID proof (Aadhar / PAN / Passport)</li>
  <li>Note down your questions about the property beforehand</li>
  <li>Check the neighbourhood and nearby amenities during your visit</li>
  <li>Ask about parking, water supply, power backup, and maintenance charges</li>
  <li>Request RERA registration number if it's a builder project</li>
</ul>

<div class="bw">
  <a href="{{ route('property-details', $property->slug) }}" class="btn btn-p">View Property Details →</a>
</div>

<p style="font-size:13px;color:#94a3b8;text-align:center;">
  Need to reschedule? <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}">Contact us on WhatsApp</a>.
</p>
@endsection
