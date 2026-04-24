@extends('dealer.layout')
@section('title', 'Subscription Plans')
@section('content')
@php
$plans = [
  'sale' => [
    ['icon' => 'house', 'title' => 'Starter', 'price' => 1499, 'days' => 30, 'features' => ['1 Property', 'Standard Visibility', 'Buyer Contact']],
    ['icon' => 'building', 'title' => 'Value', 'price' => 3499, 'days' => 90, 'features' => ['3 Properties', 'Higher Ranking', 'Verified Leads']],
    ['icon' => 'graph-up-arrow', 'title' => 'Premium', 'price' => 6999, 'days' => 180, 'features' => ['Unlimited Listings', 'Top Placement', 'RM Support']],
    ['icon' => 'shield-check', 'title' => 'Ultimate', 'price' => 11999, 'days' => 365, 'features' => ['Featured Badge', 'Priority Buyers', 'Dedicated Manager']],
  ],
  'rent' => [
    ['icon' => 'key', 'title' => 'Rent Starter', 'price' => 999, 'days' => 60, 'features' => ['1 Rental Listing', 'Tenant Leads', 'Contact Access']],
    ['icon' => 'house', 'title' => 'Rent Value', 'price' => 2499, 'days' => 90, 'features' => ['3 Listings', 'Higher Visibility', 'Verified Leads']],
    ['icon' => 'graph-up-arrow', 'title' => 'Rent Premium', 'price' => 4999, 'days' => 180, 'features' => ['Unlimited Listings', 'Top Search Rank', 'Priority Leads']],
    ['icon' => 'shield-check', 'title' => 'Rent Ultimate', 'price' => 8999, 'days' => 365, 'features' => ['Featured Property', 'Priority Tenants', 'Dedicated Support']],
  ],
  'pg' => [
    ['icon' => 'people', 'title' => 'PG Basic', 'price' => 799, 'days' => 30, 'features' => ['1 PG Listing', 'Student Leads', 'Chat Access']],
    ['icon' => 'house-heart', 'title' => 'PG Value', 'price' => 1999, 'days' => 90, 'features' => ['Multiple PGs', 'Featured Listing', 'Lead Priority']],
    ['icon' => 'graph-up', 'title' => 'PG Premium', 'price' => 3999, 'days' => 180, 'features' => ['Top Visibility', 'Verified Leads', 'RM Support']],
    ['icon' => 'shield-check', 'title' => 'PG Ultimate', 'price' => 6999, 'days' => 365, 'features' => ['Featured Badge', 'Priority Students', 'Dedicated Manager']],
  ],
];
@endphp
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0"><i class="fas fa-credit-card me-2 text-primary"></i>Subscription Plans</h4>
        <p class="text-muted small mb-0">Select a plan based on your property type</p>
    </div>
</div>

  <!-- Tabs -->
  <ul class="nav nav-pills dealer-subscription-tabs mb-4" id="dealerPlanTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="sale-tab" data-bs-toggle="pill" data-bs-target="#sale" type="button" role="tab" aria-controls="sale" aria-selected="true">For Sale</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="rent-tab" data-bs-toggle="pill" data-bs-target="#rent" type="button" role="tab" aria-controls="rent" aria-selected="false">For Rent</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="pg-tab" data-bs-toggle="pill" data-bs-target="#pg" type="button" role="tab" aria-controls="pg" aria-selected="false">For PG</button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="dealerPlanTabsContent">
    @foreach(['sale', 'rent', 'pg'] as $type)
    <div class="tab-pane fade @if($loop->first) show active custom-fadein @endif" id="{{ $type }}" role="tabpanel" aria-labelledby="{{ $type }}-tab">
      <div class="row">
        @foreach($plans[$type] as $plan)
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="plan-card plan-rect p-4 d-flex flex-column justify-content-between h-100">
            <div class="text-center mb-3">
              <div class="feature-icon mb-2">
                <i class="bi bi-{{ $plan['icon'] }}"></i>
              </div>
              <h5 class="plan-title mb-2">{{ $plan['title'] }}</h5>
              <h3 class="plan-price mb-0">₹{{ number_format($plan['price']) }}</h3>
              <p class="plan-duration text-muted">Valid for {{ $plan['days'] }} days</p>
            </div>
            <ul class="list-unstyled mb-4">
              @foreach($plan['features'] as $feature)
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>{{ $feature }}</li>
              @endforeach
            </ul>
            <div class="text-center mt-auto">
              <a href="{{ route('dealer.subscription.subscribe', ['type' => $type, 'plan' => $plan['title']]) }}" class="btn btn-outline-primary w-100">Subscribe Now</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endforeach
  </div>

@endsection

@push('styles')
<style>
.dealer-content {
  background: #f4f6fa;
  padding: 2rem;
  border-radius: 12px;
}
.dealer-header h2 {
  font-weight: 700;
  color: #222;
}
.dealer-subscription-tabs .nav-pills .nav-link {
  background: #e9ecef;
  color: #222;
  margin-right: 8px;
  border-radius: 8px;
  font-weight: 500;
}
.dealer-subscription-tabs .nav-pills .nav-link.active {
  background: #0d6efd;
  color: #fff;
}
.plan-card.plan-rect {
  border: 1.5px solid #e0e0e0;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 2px 10px rgba(0,0,0,0.04);
  margin-bottom: 1rem;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: stretch;
  transition: box-shadow 0.2s;
}
.plan-card.plan-rect:hover {
  box-shadow: 0 8px 24px rgba(13,110,253,0.10);
  border-color: #0d6efd;
}
.feature-icon i {
  font-size: 2rem;
  color: #0d6efd;
}
.btn-outline-primary {
  font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tabEls = document.querySelectorAll('#dealerPlanTabs [data-bs-toggle="tab"]');
  tabEls.forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (e) {
      document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('custom-fadein');
      });
      const target = document.querySelector(e.target.dataset.bsTarget);
      if (target) {
        target.classList.add('custom-fadein');
        setTimeout(() => target.classList.remove('custom-fadein'), 500);
      }
    });
  });
});
</script>
@endpush




