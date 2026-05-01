@extends('emails.layouts.base')

@section('hero_title', '📬 New Contact Form Submission')
@section('hero_sub', 'Someone has contacted you through the website.')

@section('content')
<p>Hello Admin,</p>
<p>You have received a new contact form submission on <strong>{{ config('app.name') }}</strong>. Here are the details:</p>

{{-- Contact Details --}}
<h2>Contact Details</h2>
<table class="dt">
  <tr><td>Name</td><td><strong>{{ $contact->name }}</strong></td></tr>
  <tr><td>Email</td><td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td></tr>
  <tr><td>Subject</td><td><strong>{{ $contact->subject }}</strong></td></tr>
  <tr><td>Message</td><td>{{ $contact->message }}</td></tr>
  <tr><td>Received</td><td>{{ $contact->created_at->format('d M Y, h:i A') }}</td></tr>
</table>

<div class="wb">
  <p>💡 <strong>Tip:</strong> Respond to inquiries within 24 hours to provide the best user experience.</p>
</div>

<div class="bw">
  <a href="{{ route('admin.contacts') }}" class="btn">View All Contacts</a>
</div>
@endsection