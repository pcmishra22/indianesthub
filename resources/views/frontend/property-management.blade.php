@extends('frontend.layout')

@section('title', 'Property Management Services | ' . config('app.name'))
@section('meta_description', 'Full-service property management — tenant finding, rent collection, and maintenance handled for you. Own a rental property without the hassle.')
@section('canonical', route('property-management.index'))

@push('styles')
<style>
  .pm-hero { background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%); color:#fff; padding:60px 0; }
  .pm-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:24px; height:100%; }
  .pm-icon { width:52px; height:52px; border-radius:12px; background:#eef4fb; color:#0078d4; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:14px; }
  .pm-form-card { background:#fff; border-radius:16px; padding:28px; box-shadow:0 10px 40px rgba(10,45,94,.15); }
</style>
@endpush

@section('content')

<div class="pm-hero">
  <div class="container text-center">
    <h1 style="font-weight:800;font-size:2rem;">Own a Rental Property? We'll Manage It For You.</h1>
    <p style="opacity:.9;font-size:1.05rem;max-width:600px;margin:12px auto 0;">
      Tenant finding, rent collection, and maintenance — handled end-to-end, so you don't have to be.
    </p>
  </div>
</div>

<section style="padding:50px 0;">
  <div class="container">
    <div class="row g-4">

      {{-- LEFT: what we offer --}}
      <div class="col-lg-7">
        <h2 style="font-weight:800;color:#0a2d5e;font-size:1.4rem;margin-bottom:20px;">What's Included</h2>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="pm-card">
              <div class="pm-icon"><i class="bi bi-people-fill"></i></div>
              <h5 class="fw-bold">Tenant Finding</h5>
              <p class="text-muted small mb-0">Verified tenant screening, background checks, and lease agreement drafting.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="pm-card">
              <div class="pm-icon"><i class="bi bi-cash-coin"></i></div>
              <h5 class="fw-bold">Rent Collection</h5>
              <p class="text-muted small mb-0">On-time rent collection with digital receipts — no more chasing tenants.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="pm-card">
              <div class="pm-icon"><i class="bi bi-tools"></i></div>
              <h5 class="fw-bold">Maintenance &amp; Upkeep</h5>
              <p class="text-muted small mb-0">Regular inspections and coordinated repairs through our verified service providers.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="pm-card">
              <div class="pm-icon"><i class="bi bi-file-earmark-text"></i></div>
              <h5 class="fw-bold">Legal &amp; Compliance</h5>
              <p class="text-muted small mb-0">Lease renewals, notices, and dispute support handled correctly.</p>
            </div>
          </div>
        </div>

        <h2 style="font-weight:800;color:#0a2d5e;font-size:1.4rem;margin-bottom:16px;">Why Owners Choose Us</h2>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Dedicated property manager for your portfolio</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Transparent monthly reports — see everything, always</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Access to our verified service provider network for repairs</li>
          <li class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>No lock-in — cancel anytime</li>
        </ul>
      </div>

      {{-- RIGHT: lead form --}}
      <div class="col-lg-5">
        <div class="pm-form-card" id="pm-form-wrap">
          <h4 class="fw-bold mb-1" style="color:#0a2d5e;">Get a Free Consultation</h4>
          <p class="text-muted small mb-3">Our team will call you within 24 hours.</p>

          <form id="pm-lead-form">
            @csrf
            <input type="hidden" name="source" value="property-management-landing">
            <input type="hidden" name="source_page" value="{{ url()->current() }}">

            <div class="mb-3">
              <input type="text" name="name" class="form-control" placeholder="Your Full Name *" required>
            </div>
            <div class="mb-3">
              <input type="tel" name="phone" class="form-control" placeholder="Mobile Number *" pattern="[0-9]{10}" required>
            </div>
            <div class="mb-3">
              <input type="email" name="email" class="form-control" placeholder="Email (optional)">
            </div>
            <div class="mb-3">
              <select name="service_type" class="form-select">
                @foreach(\App\Models\PropertyManagementLead::serviceTypeOptions() as $key => $label)
                  <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <select name="property_type" class="form-select">
                <option value="">Property Type</option>
                <option value="Apartment">Apartment</option>
                <option value="Villa">Villa / Independent House</option>
                <option value="Commercial">Commercial</option>
              </select>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <input type="text" name="city" class="form-control" placeholder="City">
              </div>
              <div class="col-6">
                <input type="number" name="num_properties" class="form-control" placeholder="No. of properties" min="1">
              </div>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" name="currently_rented" value="1" class="form-check-input" id="pm-rented">
              <label class="form-check-label small" for="pm-rented">This property is already rented out</label>
            </div>

            <button type="submit" id="pm-submit-btn" data-label="Request Free Consultation" class="btn btn-primary w-100 py-2 fw-bold">
              Request Free Consultation
            </button>
          </form>
        </div>

        <div id="pm-success" class="pm-form-card text-center" style="display:none;">
          <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
          <h5 class="fw-bold mt-3">Thank you!</h5>
          <p class="text-muted mb-0">Our property management team will contact you within 24 hours.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('pm-lead-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('pm-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

    fetch('{{ route("property-management.lead.store") }}', {
      method: 'POST',
      body: new FormData(form),
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        document.getElementById('pm-form-wrap').style.display = 'none';
        document.getElementById('pm-success').style.display = 'block';
      } else {
        btn.disabled = false;
        btn.innerHTML = btn.getAttribute('data-label');
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.innerHTML = btn.getAttribute('data-label');
    });
  });
});
</script>
@endsection
