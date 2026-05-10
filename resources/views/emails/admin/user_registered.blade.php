@extends('emails.layouts.base')

@section('hero_title', 'New User Registration Alert')
@section('hero_sub', 'A new user has registered on the platform.')

@section('content')
<p>A new user has just registered on the platform.</p>

<div class="sb">
  <p>✅ <strong>User:</strong> {{ $user->name }}</p>
  <p>📧 <strong>Email:</strong> {{ $user->email }}</p>
  @if(!empty($user->phone))
    <p>📱 <strong>Phone:</strong> {{ $user->phone }}</p>
  @endif
</div>

<hr class="dv" />

<p style="font-size:13px;color:#94a3b8;">
  Please review the new registration in the admin panel.
</p>
@endsection

