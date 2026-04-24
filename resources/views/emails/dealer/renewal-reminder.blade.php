@extends('emails.layouts.base')

@section('hero_title', '⚠️ Your Subscription Expires Soon!')
@section('hero_sub', 'Renew now to keep your listings live and leads flowing.')

@section('content')
<p>Hi <strong>{{ $dealer->first_name }}</strong>,</p>
<p>Your {{ config('app.name') }} subscription is expiring in <strong>{{ $daysLeft }} days</strong> on <strong>{{ $expiryDate }}</strong>. Renew today to ensure your listings stay active and you don't miss any buyer leads.</p>

<div class="wb"><p>⚠️ If not renewed by <strong>{{ $expiryDate }}</strong>, your listings will be moved to standard placement and lead priority will be reduced.</p></div>

<h2>What Happens If You Don't Renew?</h2>
<table class="dt">
  <tr><td>Listings</td><td>Moved to standard (unpaid) placement</td></tr>
  <tr><td>Visibility</td><td>Reduced — no featured/priority ranking</td></tr>
  <tr><td>Lead Alerts</td><td>Delayed — no instant WhatsApp/email alerts</td></tr>
  <tr><td>Inquiries</td><td>Lower volume from reduced visibility</td></tr>
</table>

<h2>Available Plans</h2>
<table class="dt">
  <tr><td><strong>Basic</strong></td><td>₹999/month · 20 listings</td></tr>
  <tr><td><strong>Pro</strong> ⭐</td><td>₹2,499/month · 50 listings + 5 featured slots</td></tr>
  <tr><td><strong>Elite</strong></td><td>₹5,999/month · Unlimited + dedicated manager</td></tr>
</table>

<div class="bw">
  <a href="{{ route('dealer.subscription') }}" class="btn btn-p">Renew Subscription Now →</a>
</div>

<p style="text-align:center;">Or <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}?text=Hi, I want to renew my {{ config('app.name') }} dealer subscription.">WhatsApp us</a> for a special renewal offer.</p>
@endsection
