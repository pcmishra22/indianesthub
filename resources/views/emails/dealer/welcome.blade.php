@extends('emails.layouts.base')

@section('hero_title', 'Welcome, {{ $dealer->first_name }}! 🎉')
@section('hero_sub', 'Your dealer account is live. Start posting properties and getting leads today.')

@section('content')
<p>Hi <strong>{{ $dealer->first_name }} {{ $dealer->last_name }}</strong>,</p>
<p>Congratulations on joining <strong>{{ config('app.name') }}</strong> as a verified real estate dealer. You now have access to one of the most active property buyer communities in Chandigarh Tricity.</p>

<div class="sb"><p>✅ Dealer account activated for <strong>{{ $dealer->email }}</strong></p></div>

<h2>Your 3-Step Quick Start</h2>
<table class="dt">
  <tr><td>Step 1</td><td><strong>Complete your profile</strong> — Add your photo, bio, and areas of expertise to build buyer trust</td></tr>
  <tr><td>Step 2</td><td><strong>Post your first property</strong> — Listings with photos get 5× more inquiries</td></tr>
  <tr><td>Step 3</td><td><strong>Activate WhatsApp alerts</strong> — Get notified instantly when a buyer contacts you</td></tr>
</table>

<div class="bw">
  <a href="{{ route('dealer.dashboard') }}" class="btn btn-p">Go to Dashboard →</a>
</div>

<hr class="dv">

<div class="ss">
  <table><tr>
    <td><span class="sv">3,000+</span><span class="sl">Active Listings</span></td>
    <td><span class="sv">340+</span><span class="sl">Dealers</span></td>
    <td><span class="sv">25+</span><span class="sl">Localities</span></td>
  </tr></table>
</div>

<hr class="dv">

<h2>💡 Pro Tips to Get More Leads</h2>
<ul class="cl">
  <li>Add <strong>10+ high-quality photos</strong> per listing — listings with photos get 5× more views</li>
  <li>Write a <strong>detailed description</strong> mentioning nearby landmarks, schools, and metro/highway access</li>
  <li>Set <strong>competitive pricing</strong> — check similar listings in your area first</li>
  <li>Respond to inquiries within <strong>1 hour</strong> — fast response = higher conversion</li>
  <li>Use <strong>Featured Listing</strong> (₹299) for priority placement</li>
</ul>

<div class="bw">
  <a href="{{ route('dealer.dashboard') }}" class="btn btn-p">Post Your First Property →</a>
  &nbsp;&nbsp;
  <a href="{{ url('/pricing') }}" class="btn-o">View Pricing Plans</a>
</div>

<p style="font-size:13px;color:#94a3b8;text-align:center;">
  Questions? <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}">WhatsApp our support team</a> anytime.
</p>
@endsection
