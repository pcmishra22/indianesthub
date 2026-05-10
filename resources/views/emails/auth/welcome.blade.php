@extends('emails.layouts.base')

@section('hero_title', 'Welcome to ' . config('app.name') . '! 🚀')
@section('hero_sub', 'Your account is ready. Start finding your dream property today.')

@section('content')
<p>Hi <strong>{{ $user->name }}</strong>, 👋</p>

<p>Welcome to <strong>{{ config('app.name') }}</strong> — Chandigarh Tricity's most trusted real estate platform. Your account is all set!</p>

<div class="sb"><p>✅ Account created for <strong>{{ $user->email }}</strong></p></div>

<h2>Here’s What You Can Do Now</h2>
<ul class="cl">
  <li>Browse <strong>3,000+ verified properties</strong> across Chandigarh, Mohali, Zirakpur & Panchkula</li>
  <li>Save properties to your <strong>wishlist</strong> for easy comparison</li>
  <li>Contact verified agents <strong>directly via WhatsApp</strong></li>
  <li>Schedule <strong>property viewings</strong> at your convenience</li>
  <li>Check <strong>home loan eligibility</strong> for free</li>
</ul>

<div class="bw">
  <a href="{{ url('/properties') }}" class="btn btn-p">Browse Properties Now →</a>
</div>

<hr class="dv">

<h3>Popular Searches in Tricity</h3>
<div class="al">
  <a href="{{ url('/flats-in-zirakpur') }}">Flats in Zirakpur</a>
  <a href="{{ url('/flats-in-mohali') }}">Flats in Mohali</a>
  <a href="{{ url('/new-projects-in-zirakpur') }}">New Projects</a>
  <a href="{{ url('/plots-in-mohali') }}">Plots in Mohali</a>
</div>

<hr class="dv">
<p style="font-size:13px;color:#94a3b8;text-align:center;">
  Need help? <a href="https://wa.me/91{{ config('app.whatsapp_number','7340753780') }}">WhatsApp us</a> or reply to this email.
</p>
@endsection

