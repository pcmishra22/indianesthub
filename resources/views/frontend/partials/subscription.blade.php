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

<main class="main">
  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1>Subscription Plans</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="/">Home</a></li>
          <li class="current">Subscription</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Subscription Section -->
  <section class="about section">
    <div class="container">

      <div class="section-title text-center">
        <h2>Choose Your Property Plan</h2>
        <p>Select a plan based on your property type</p>
      </div>

      <!-- Tabs -->
      <ul class="nav nav-tabs justify-content-center mb-5" id="planTabs">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sale" type="button">For Sale</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rent" type="button">For Rent</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pg" type="button">For PG</button>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content" id="planTabContent">
        @foreach($plans as $type => $items)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $type }}" tabindex="0">
          <div class="plan-box">
            <div class="row gy-4">
              @foreach($items as $plan)
              <div class="col-lg-3 col-md-6 d-flex">
                <div class="feature-box text-center plan-card plan-rect flex-fill">
                  <div class="feature-icon">
                    <i class="bi bi-{{ $plan['icon'] }}"></i>
                  </div>
                  <h4>{{ $plan['title'] }}</h4>
                  <h3>₹{{ number_format($plan['price']) }}</h3>
                  <p>{{ $plan['days'] }} Days</p>
                  <ul class="list-unstyled">
                    @foreach($plan['features'] as $feature)
                      <li>✔ {{ $feature }}</li>
                    @endforeach
                  </ul>
                  <a href="#" class="btn btn-success w-100">Subscribe</a>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endforeach
      </div>

    </div>
  </section>

</main>

@push('styles')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/frontend/pages.css') }}">
@endpush
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Use Bootstrap's event system for tab transitions
  const tabEls = document.querySelectorAll('#planTabs [data-bs-toggle="tab"]');
  tabEls.forEach(tab => {
    tab.addEventListener('show.bs.tab', function (e) {
      // Remove custom-fadein from all tab panes before showing new one
      document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('custom-fadein');
      });
    });
    tab.addEventListener('shown.bs.tab', function (e) {
      // Add custom-fadein to the newly shown tab pane
      const targetSelector = e.target.getAttribute('data-bs-target');
      if (targetSelector) {
        const target = document.querySelector(targetSelector);
        if (target) {
          target.classList.add('custom-fadein');
          setTimeout(() => target.classList.remove('custom-fadein'), 500);
        }
      }
    });
  });
});
</script>
@endpush