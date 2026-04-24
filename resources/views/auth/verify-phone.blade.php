@extends('frontend.user.layout')

@section('page-title', 'Verify Phone')

@section('user-content')
  <div class="mb-4">
    <h4 class="fw-bold">Phone Verification</h4>
    <p class="text-muted mb-0">Enter the OTP sent to your phone to verify your number.</p>
  </div>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <form action="{{ route('phone.otp.send') }}" method="POST" class="mb-3">
    @csrf
    <div class="mb-3">
      <label for="phone" class="form-label">Phone Number</label>
      <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required>
      @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Send OTP</button>
  </form>

  <form action="{{ route('phone.otp.verify') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="otp" class="form-label">OTP</label>
      <input type="text" class="form-control @error('otp') is-invalid @enderror" id="otp" name="otp" required>
      @error('otp')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    <input type="hidden" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
    <button type="submit" class="btn btn-success">Verify OTP</button>
  </form>
@endsection
