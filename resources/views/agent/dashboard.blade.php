@extends('agent.layout')

@section('page-title', 'Performance Dashboard')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-3">Performance Dashboard</h4>
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Listings</h5>
                    <div class="display-5 fw-bold">{{ $listingsCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Views</h5>
                    <div class="display-5 fw-bold">{{ $viewsCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Inquiries</h5>
                    <div class="display-5 fw-bold">{{ $inquiriesCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5>Avg. Rating</h5>
                    <div class="display-5 fw-bold">{{ number_format($avgRating, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
