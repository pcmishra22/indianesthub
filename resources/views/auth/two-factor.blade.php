@extends('frontend.user.layout')

@section('page-title', 'Two-Factor Authentication')

@section('user-content')
  <div class="mb-4">
    <h4 class="fw-bold">Two-Factor Authentication</h4>
    <p class="text-muted mb-0">Enter the code sent to your phone/email.</p>
  </div>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <form action="{{ route('2fa.send') }}" method="POST" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-primary">Send 2FA Code</button>
  </form>

  <form action="{{ route('2fa.verify') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="code" class="form-label">2FA Code</label>
      <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" required>
      @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-success">Verify 2FA</button>
  </form>
@endsection
