@extends('emails.layouts.base')

@section('hero_title', '🔥 Hot Lead Alert!')
@section('hero_sub', 'A buyer just expressed interest. Contact them now before they move on.')

@section('content')
<p>Hi <strong>{{ $dealer->first_name }}</strong>,</p>
<p>You have a <strong>new lead</strong> on {{ config('app.name') }}! A potential buyer is actively looking — here are their details:</p>

<div class="sb"><p>🟢 Lead received at {{ now()->format('h:i A, d M Y') }}</p></div>

<h2>Lead Details</h2>
<table class="dt">
  <tr><td>Name</td><td><strong>{{ $lead['name'] }}</strong></td></tr>
  <tr><td>Phone</td><td><a href="tel:+91{{ $lead['phone'] }}"><strong>+91 {{ $lead['phone'] }}</strong></a></td></tr>
  <tr><td>Email</td><td><a href="mailto:{{ $lead['email'] }}">{{ $lead['email'] }}</a></td></tr>
  <tr><td>Property</td><td>{{ $lead['property_title'] ?? 'General Inquiry' }}</td></tr>
  <tr><td>Source</td><td>{{ ucfirst($lead['source'] ?? config('app.name')) }}</td></tr>
  @if(!empty($lead['message']))
  <tr><td>Message</td><td><em>"{{ $lead['message'] }}"</em></td></tr>
  @endif
</table>

<div class="ib"><p>📊 <strong>Data Point:</strong> Dealers who respond within 15 minutes convert leads at 3× the average rate. Call now!</p></div>

<div class="bw">
  <a href="tel:+91{{ $lead['phone'] }}" class="btn btn-p">📞 Call Lead Now</a>
  &nbsp;
  <a href="https://wa.me/91{{ $lead['phone'] }}?text=Hi {{ urlencode($lead['name']) }}, I'm calling from {{ config('app.name') }} regarding your property inquiry." class="btn btn-wa">💬 WhatsApp</a>
</div>

<div class="bw" style="margin-top:0;">
  <a href="{{ route('dealer.inquiries') }}" class="btn-o">View All Leads in Dashboard</a>
</div>
@endsection
