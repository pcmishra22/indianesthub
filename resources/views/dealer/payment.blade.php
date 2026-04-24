@extends('dealer.layout')
@section('title', 'Payment')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">UPI Payment for Subscription</h3>
                </div>
                <div class="card-body">
                    <p class="lead">You are subscribing to the <strong>{{ $plan }}</strong> plan ({{ ucfirst($type) }})</p>
                    <h4 class="mb-3">Amount: <span class="text-success">₹{{ number_format($amount) }}</span></h4>
                    <hr>
                    <p>Scan the QR code below or click the button to pay via UPI:</p>
                    <div class="text-center mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($upi_url) }}&amp;size=200x200" alt="UPI QR Code" class="img-fluid" style="max-width:200px;">
                    </div>
                    <div class="text-center mb-4">
                        <a href="{{ $upi_url }}" class="btn btn-success btn-lg" target="_blank">Pay with UPI App</a>
                    </div>
                    <form method="POST" action="{{ route('dealer.subscription.payment.markPaid', $payment->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">I Have Paid</button>
                    </form>
                    <p class="text-muted small mt-3">After payment, click the button above to mark your payment as completed. Admin may verify before activating your subscription.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
