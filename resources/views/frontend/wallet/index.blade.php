@extends('frontend.layout')
@section('content')
<div class="container mt-4">
    <h2>Wallet & Billing</h2>
    @if($wallet)
        <div class="alert alert-info">Wallet Balance: {{ $wallet->balance }}</div>
    @else
        <p>No wallet data found.</p>
    @endif
</div>
@endsection
