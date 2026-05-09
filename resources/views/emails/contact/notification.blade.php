@extends('emails.layouts.base')

@section('hero_title', '📬 New Contact Received')
@section('hero_sub', 'A visitor shared an inquiry on {{ config('app.name') }}. Review and respond quickly.')

@section('content')
<p>Hi <strong>Admin</strong>,</p>
<p>You’ve received a new <strong>contact form submission</strong>. Here are the details:</p>

<div class="ib">
  <p>🕒 Received at <strong>{{ $contact->created_at->format('d M Y, h:i A') }}</strong></p>
</div>

{{-- Contact Card --}}
<div class="pc">
  <div class="pc-h">
    <span class="ptype">🌐 Visitor Contact</span>
  </div>
  <div class="pc-b">
    <div class="pc-title">{{ $contact->name }}</div>
    <div class="pc-loc">{{ $contact->subject }}</div>
    <div class="pc-price">
      <a href="mailto:{{ $contact->email }}" style="color:#0078d4; text-decoration:none;">{{ $contact->email }}</a>
    </div>
  </div>
</div>

<h2>Message Details</h2>
<table class="dt">
  <tr><td>Name</td><td><strong>{{ $contact->name }}</strong></td></tr>
  <tr><td>Email</td><td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td></tr>
  <tr><td>Subject</td><td><strong>{{ $contact->subject }}</strong></td></tr>
  <tr><td>Message</td><td><em>{{ $contact->message }}</em></td></tr>
</table>

<div class="wb">
  <p>💡 <strong>Tip:</strong> Reply within <strong>24 hours</strong> to improve conversion and user satisfaction.</p>
</div>

<div class="bw">
  <a href="{{ route('admin.contacts') }}" class="btn btn-p">🗂️ View All Contacts</a>
  &nbsp;
  <a href="mailto:{{ $contact->email }}?subject={{ urlencode('Re: ' . $contact->subject) }}" class="btn btn-o">✉️ Reply Now</a>
</div>
@endsection
