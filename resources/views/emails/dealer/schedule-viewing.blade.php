@extends('emails.layouts.base')

@section('hero_title', '📅 New Property Viewing Scheduled!')
@section('hero_sub', 'A buyer wants to visit your property. Confirm the appointment today.')

@section('content')
<p>Hi <strong>{{ $schedule->dealer->first_name ?? 'there' }}</strong>,</p>
<p>Great news! A buyer has scheduled a viewing for your property listed on <strong>{{ config('app.name') }}</strong>.</p>

{{-- Property --}}
<div class="pc">
  <div class="pc-h"><span class="ptype">🏠 Your Property</span></div>
  <div class="pc-b">
    <div class="pc-title">{{ $property->title }}</div>
    <div class="pc-loc">📍 {{ $property->location ?? $property->city }}</div>
  </div>
</div>

{{-- Appointment --}}
<h2>Viewing Appointment</h2>
<table class="dt">
  <tr><td>Date</td><td><strong>{{ \Carbon\Carbon::parse($schedule->date)->format('l, d M Y') }}</strong></td></tr>
  <tr><td>Time</td><td><strong>{{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}</strong></td></tr>
  <tr><td>Buyer Name</td><td>{{ $schedule->name }}</td></tr>
  <tr><td>Phone</td><td><a href="tel:+91{{ $schedule->phone }}">+91 {{ $schedule->phone }}</a></td></tr>
  <tr><td>Email</td><td><a href="mailto:{{ $schedule->email }}">{{ $schedule->email }}</a></td></tr>
  @if($schedule->message)
  <tr><td>Note</td><td><em>{{ $schedule->message }}</em></td></tr>
  @endif
</table>

<div class="ib"><p>📌 Please call or WhatsApp the buyer to confirm the appointment time and share property address.</p></div>

<div class="bw">
  <a href="https://wa.me/91{{ $schedule->phone }}?text=Hi {{ urlencode($schedule->name) }}, confirming your property viewing on {{ \Carbon\Carbon::parse($schedule->date)->format('d M') }} at {{ \Carbon\Carbon::parse($schedule->time)->format('h:i A') }}. Looking forward to meeting you!" class="btn btn-wa">✅ Confirm via WhatsApp</a>
  &nbsp;
  <a href="{{ route('dealer.schedule-viewings') }}" class="btn-o">View All Viewings</a>
</div>
@endsection
