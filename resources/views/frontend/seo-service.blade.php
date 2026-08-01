@extends('frontend.layout')

@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', $h1 . ', ' . ($cityLabel ? $cityLabel . ', ' : '') . config('app.name'))
@section('canonical', url()->current())
@section('og_title', $h1 . ' | ' . config('app.name'))
@section('og_description', $seoDesc)
@section('og_url', url()->current())

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    @if($serviceType === 'loan')
    {"@type":"ListItem","position":2,"name":"Home Loan","item":"{{ url('/home-loan') }}"}
    @elseif($serviceType === 'insurance')
    {"@type":"ListItem","position":2,"name":"Property Insurance","item":"{{ url('/property-insurance') }}"}
    @else
    {"@type":"ListItem","position":2,"name":"Legal Help","item":"{{ url('/property-legal-help') }}"}
    @endif
    @if($cityLabel)
    ,{"@type":"ListItem","position":3,"name":"{{ $h1 }}","item":"{{ url()->current() }}"}
    @endif
  ]
}
</script>
@if(count($faqs) > 0)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($faqs as $i => $faq)
    {
      "@type": "Question",
      "name": "{{ addslashes($faq['q']) }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ addslashes($faq['a']) }}"
      }
    }{{ $i < count($faqs)-1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Service",
  "name": "{{ $h1 }}",
  "provider": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}"
  },
  "description": "{{ $seoDesc }}",
  "areaServed": "{{ $cityLabel ?? 'India' }}"
}
</script>
@endsection

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/frontend/pages.css') }}">
{{-- The block below depends on the $serviceType variable and must stay
     inline (a static .css file can't evaluate Blade conditionals). --}}
<style>
.svc-hero {
  background: linear-gradient(135deg,
    @if($serviceType === 'loan') #061830 0%, #0a2d5e 50%, #0078d4 100%
    @elseif($serviceType === 'insurance') #062010 0%, #064d20 50%, #16a34a 100%
    @else #1a0533 0%, #3b0764 50%, #6b21a8 100%
    @endif
  );
  padding: 60px 0 40px;
  color: #fff;
}
.svc-hero h1 { font-size: clamp(1.4rem, 3.5vw, 2.2rem); font-weight: 800; line-height: 1.25; }
.svc-hero .subtitle { opacity: .85; font-size: .95rem; margin-top: 8px; }
.trust-pill {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.12); color: #fff;
  border-radius: 20px; padding: 5px 14px;
  font-size: .78rem; font-weight: 600;
}
.svc-form-card {
  border-radius: 16px; overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.18);
}
.svc-form-header {
  padding: 18px 24px 14px;
  @if($serviceType === 'loan')
    background: linear-gradient(135deg,#0a2d5e,#0078d4);
  @elseif($serviceType === 'insurance')
    background: linear-gradient(135deg,#064d20,#16a34a);
  @else
    background: linear-gradient(135deg,#3b0764,#6b21a8);
  @endif
  color: #fff;
}
.step-circle {
  width: 38px; height: 38px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 1rem; flex-shrink: 0;
  @if($serviceType === 'loan') background: #eff6ff; color: #0078d4;
  @elseif($serviceType === 'insurance') background: #f0fdf4; color: #16a34a;
  @else background: #f5f3ff; color: #6b21a8;
  @endif
}
.faq-item { border-bottom: 1px solid #f1f5f9; }
.faq-q { cursor: pointer; padding: 16px 0; font-weight: 600; user-select: none; }
.faq-a { display: none; padding: 0 0 16px; color: #475569; line-height: 1.7; }
.faq-item.open .faq-a { display: block; }
.city-chip {
  display: inline-block;
  padding: 5px 12px; border-radius: 20px; font-size: .78rem; font-weight: 600;
  border: 1px solid #e2e8f0; color: #475569; text-decoration: none;
  transition: all .15s;
}
.city-chip:hover { border-color: #0078d4; color: #0078d4; background: #eff6ff; }
</style>
@endsection

@section('content')

{{-- ── HERO ─────────────────────────────────────────────────────── --}}
<section class="svc-hero">
  <div class="container">
    <div class="row gy-4 align-items-center">

      {{-- Left: heading + trust pills + steps --}}
      <div class="col-lg-6">
        <div class="d-flex flex-wrap gap-2 mb-3">
          @if($serviceType === 'loan')
            <span class="trust-pill"><i class="bi bi-bank me-1"></i> 20+ Banks</span>
            <span class="trust-pill"><i class="bi bi-lightning-charge-fill me-1"></i> 48-hr Approval</span>
            <span class="trust-pill"><i class="bi bi-currency-rupee me-1"></i> Free Assistance</span>
          @elseif($serviceType === 'insurance')
            <span class="trust-pill"><i class="bi bi-shield-check me-1"></i> 10+ Insurers</span>
            <span class="trust-pill"><i class="bi bi-patch-check me-1"></i> IRDAI Regulated</span>
            <span class="trust-pill"><i class="bi bi-chat-dots me-1"></i> Free Quote</span>
          @else
            <span class="trust-pill"><i class="bi bi-briefcase me-1"></i> Verified Lawyers</span>
            <span class="trust-pill"><i class="bi bi-shield-lock me-1"></i> 100% Confidential</span>
            <span class="trust-pill"><i class="bi bi-chat-dots me-1"></i> Free First Consult</span>
          @endif
        </div>

        <h1>{{ $h1 }}</h1>
        <p class="subtitle">{{ $subTitle ?? '' }}</p>

        {{-- How it works --}}
        <div class="mt-4 d-flex flex-column gap-3">
          @if($serviceType === 'loan')
            @php $steps = [
              ['1', 'Fill your details', 'Name, phone & loan amount — takes 60 seconds'],
              ['2', 'Our expert calls you', 'Within 2 hours — free eligibility check'],
              ['3', 'Get bank offers', 'Compare rates from 20+ banks, choose the best'],
            ]; @endphp
          @elseif($serviceType === 'insurance')
            @php $steps = [
              ['1', 'Share property details', 'Type, value, city — takes 60 seconds'],
              ['2', 'Get instant quotes', 'Compare 10+ insurers side by side — free'],
              ['3', 'Buy your policy', 'Pay online, get policy instantly via email'],
            ]; @endphp
          @else
            @php $steps = [
              ['1', 'Describe your issue', 'Select issue type and add a brief note'],
              ['2', 'Expert contacts you', 'Verified lawyer calls within 24 hours'],
              ['3', 'Get legal guidance', 'Free first consultation — no upfront fee'],
            ]; @endphp
          @endif

          @foreach($steps as $step)
            <div class="d-flex gap-3 align-items-start">
              <div class="step-circle">{{ $step[0] }}</div>
              <div>
                <div class="fw-bold text-white">{{ $step[1] }}</div>
                <div style="color:rgba(255,255,255,.7);font-size:.83rem;">{{ $step[2] }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Right: inline form --}}
      <div class="col-lg-5 offset-lg-1">
        <div class="svc-form-card bg-white">
          <div class="svc-form-header">
            <h5 class="fw-bold mb-0">
              @if($serviceType === 'loan') 🏦 Check Home Loan Eligibility Free
              @elseif($serviceType === 'insurance') 🛡️ Get Free Insurance Quote
              @else ⚖️ Request Free Legal Consultation
              @endif
            </h5>
            <div style="font-size:.78rem;opacity:.85;margin-top:4px;">No charges · No spam · Expert callback guaranteed</div>
          </div>
          <div class="p-4">
            <div id="svc-form-wrap">
              <form id="svc-lead-form">
                @csrf
                @if($serviceType === 'loan')
                  <input type="hidden" name="source" value="seo-loan-page">
                  <input type="hidden" name="source_page" value="{{ url()->current() }}">
                  @if(isset($employmentType))
                    <input type="hidden" name="employment_type" value="{{ $employmentType }}">
                  @endif
                @elseif($serviceType === 'insurance')
                  <input type="hidden" name="source" value="seo-insurance-page">
                  <input type="hidden" name="source_page" value="{{ url()->current() }}">
                  @if($cityLabel) <input type="hidden" name="property_city" value="{{ $cityLabel }}"> @endif
                @else
                  <input type="hidden" name="source" value="seo-legal-page">
                  <input type="hidden" name="source_page" value="{{ url()->current() }}">
                  <input type="hidden" name="legal_issue_type" value="{{ $issueType ?? 'other' }}">
                  @if($cityLabel) <input type="hidden" name="city" value="{{ $cityLabel }}"> @endif
                @endif

                <div class="mb-3">
                  <input type="text" name="name" class="form-control" placeholder="Your Full Name *" required>
                </div>
                <div class="mb-3">
                  <div class="input-group">
                    <span class="input-group-text">+91</span>
                    <input type="tel" name="phone" class="form-control" placeholder="Mobile Number *"
                           pattern="[0-9]{10}" required>
                  </div>
                </div>
                <div class="mb-3">
                  <input type="email" name="email" class="form-control" placeholder="Email (optional)">
                </div>

                @if($serviceType === 'loan')
                  <div class="mb-3">
                    <input type="number" name="loan_amount" class="form-control" placeholder="Loan Amount Needed (₹)" min="100000" step="100000">
                  </div>
                  @if(!isset($employmentType))
                  <div class="mb-3">
                    <select name="employment_type" class="form-select">
                      <option value="">Employment Type (optional)</option>
                      <option value="salaried">Salaried</option>
                      <option value="self-employed">Self-Employed</option>
                      <option value="business">Business Owner</option>
                    </select>
                  </div>
                  @endif
                @elseif($serviceType === 'insurance')
                  <div class="mb-3">
                    <input type="number" name="property_value" class="form-control" placeholder="Property Value (₹) e.g. 5000000" min="100000" step="50000">
                  </div>
                @else
                  <div class="mb-3">
                    <textarea name="description" class="form-control" rows="2"
                              placeholder="Briefly describe your issue (optional)" style="resize:none;"></textarea>
                  </div>
                  <div class="mb-3">
                    <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}" placeholder="Preferred consultation date">
                  </div>
                @endif

                <button type="submit" id="svc-submit-btn" class="btn w-100 fw-bold py-3"
                        style="@if($serviceType==='loan') background:linear-gradient(135deg,#0a2d5e,#0078d4);
                               @elseif($serviceType==='insurance') background:linear-gradient(135deg,#064d20,#16a34a);
                               @else background:linear-gradient(135deg,#3b0764,#6b21a8);
                               @endif color:#fff;border-radius:10px;font-size:1rem;">
                  @if($serviceType === 'loan') <i class="bi bi-bank me-2"></i> Check Eligibility Free
                  @elseif($serviceType === 'insurance') <i class="bi bi-shield-check me-2"></i> Get Free Quote
                  @else <i class="bi bi-send me-2"></i> Request Free Consultation
                  @endif
                </button>
                <p class="text-center text-muted mt-2 mb-0" style="font-size:.72rem;">
                  🔒 100% Free · No hidden charges · Expert calls within
                  @if($serviceType === 'loan') 2 hours @elseif($serviceType === 'insurance') 2 hours @else 24 hours @endif
                </p>
              </form>
            </div>
            <div id="svc-success" style="display:none;" class="text-center py-3">
              <div style="font-size:2.5rem;">
                @if($serviceType === 'loan') 🏦 @elseif($serviceType === 'insurance') 🛡️ @else ⚖️ @endif
              </div>
              <h5 class="fw-bold mt-2">Request Submitted!</h5>
              <p class="text-muted small">Our expert will contact you within
                @if($serviceType === 'loan' || $serviceType === 'insurance') 2 hours @else 24 hours @endif.
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── MAIN CONTENT ─────────────────────────────────────────────── --}}
<section class="py-5">
  <div class="container">
    <div class="row g-5">

      {{-- Left: content + FAQs --}}
      <div class="col-lg-8">

        {{-- Service info cards --}}
        <h2 class="fw-bold mb-4" style="font-size:1.35rem;">
          @if($serviceType === 'loan') Why Get Home Loan Assistance from {{ $appName }}?
          @elseif($serviceType === 'insurance') Why Compare Property Insurance via {{ $appName }}?
          @else Why Get Legal Help via {{ $appName }}?
          @endif
        </h2>
        <div class="row g-3 mb-5">
          @if($serviceType === 'loan')
            @php $benefits = [
              ['bi-bank','Compare 20+ Banks','Get offers from SBI, HDFC, ICICI, Axis, PNB and 15+ more lenders in one place.'],
              ['bi-percent','Lowest Interest Rates','Our tie-ups with banks help you get preferential rates — often 0.1–0.25% below standard.'],
              ['bi-file-earmark-check','100% Free Service','We charge nothing. Our service is completely free for home loan applicants.'],
              ['bi-headset','Dedicated Expert','One expert handles your case end-to-end — from eligibility to disbursement.'],
            ]; @endphp
          @elseif($serviceType === 'insurance')
            @php $benefits = [
              ['bi-shield-check','IRDAI Regulated Insurers','All insurers on our platform are approved by IRDAI — your claim is always protected.'],
              ['bi-bar-chart','Instant Comparison','Compare premiums, coverage, and claim ratios of 10+ insurers in seconds.'],
              ['bi-currency-rupee','Save Up to 40%','Our comparison tool finds the same coverage at the lowest price available.'],
              ['bi-telephone','Claims Assistance','We help you through the claim process, not just the purchase.'],
            ]; @endphp
          @else
            @php $benefits = [
              ['bi-patch-check','Verified Lawyers','All legal experts on our platform are verified advocates with property law specialisation.'],
              ['bi-chat-dots','Free First Consultation','First consultation is completely free — no commitment, no upfront fees.'],
              ['bi-shield-lock','100% Confidential','Your case details and documents are strictly confidential.'],
              ['bi-geo-alt','Local Expertise','Lawyers with specific expertise in ' . ($cityLabel ?? 'your city') . ' property laws, courts, and sub-registrar offices.'],
            ]; @endphp
          @endif
          @foreach($benefits as $b)
          <div class="col-sm-6">
            <div class="d-flex gap-3 p-3 border rounded-3">
              <i class="bi bi-{{ $b[0] }} fs-4 flex-shrink-0
                @if($serviceType==='loan') text-primary
                @elseif($serviceType==='insurance') text-success
                @else text-purple @endif"
                 style="@if($serviceType==='legal') color:#6b21a8 !important; @endif"></i>
              <div>
                <div class="fw-semibold">{{ $b[1] }}</div>
                <div class="text-muted small">{{ $b[2] }}</div>
              </div>
            </div>
          </div>
          @endforeach
        </div>

        {{-- AI Legal Checklist (legal pages only) --}}
        @if($serviceType === 'legal')
        @include('frontend.partials.legal-checklist', ['cityLabel' => $cityLabel ?? null])
        @endif

        {{-- FAQs --}}
        @if(count($faqs))
        <h2 class="fw-bold mb-3" style="font-size:1.35rem;">
          Frequently Asked Questions
          @if($cityLabel) — {{ $cityLabel }} @endif
        </h2>
        <div id="faq-list">
          @foreach($faqs as $faq)
          <div class="faq-item">
            <div class="faq-q d-flex justify-content-between align-items-center gap-2"
                 onclick="toggleFaq(this)">
              <span>{{ $faq['q'] }}</span>
              <i class="bi bi-plus-circle flex-shrink-0"
                 style="@if($serviceType==='loan') color:#0078d4
                        @elseif($serviceType==='insurance') color:#16a34a
                        @else color:#6b21a8 @endif"></i>
            </div>
            <div class="faq-a">{{ $faq['a'] }}</div>
          </div>
          @endforeach
        </div>
        @endif

      </div>

      {{-- Right: sidebar --}}
      <div class="col-lg-4">

        {{-- EMI Calculator (loan pages only) --}}
        @if($serviceType === 'loan')
        <div class="mb-4">
          @include('frontend.partials.emi-calculator')
        </div>
        @endif

        {{-- Related cities --}}
        @if($cityLabel)
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h6 class="fw-bold mb-3">
              @if($serviceType === 'loan') Home Loan in Other Cities
              @elseif($serviceType === 'insurance') Property Insurance in Other Cities
              @else Legal Help in Other Cities
              @endif
            </h6>
            <div class="d-flex flex-wrap gap-2">
              @foreach($cities as $slug => $name)
                @if($slug !== $citySlug)
                <a href="{{ $serviceType === 'loan' ? url('/home-loan-in-'.strtolower($slug))
                          : ($serviceType === 'insurance' ? url('/property-insurance-in-'.strtolower($slug))
                          : url('/property-legal-help-in-'.strtolower($slug))) }}"
                   class="city-chip">{{ $name }}</a>
                @endif
              @endforeach
            </div>
          </div>
        </div>
        @endif

        {{-- Related services --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Related Services</h6>
            <div class="d-flex flex-column gap-2">
              @if($serviceType !== 'loan')
              <a href="{{ url('/home-loan' . ($cityLabel ? '-in-'.$citySlug : '')) }}"
                 class="d-flex align-items-center gap-2 text-decoration-none text-dark p-2 rounded hover-bg">
                <i class="bi bi-bank text-primary"></i>
                <span class="small fw-semibold">Home Loan{{ $cityLabel ? ' in '.$cityLabel : '' }}</span>
              </a>
              @endif
              @if($serviceType !== 'insurance')
              <a href="{{ url('/property-insurance' . ($cityLabel ? '-in-'.$citySlug : '')) }}"
                 class="d-flex align-items-center gap-2 text-decoration-none text-dark p-2 rounded hover-bg">
                <i class="bi bi-shield-check text-success"></i>
                <span class="small fw-semibold">Property Insurance{{ $cityLabel ? ' in '.$cityLabel : '' }}</span>
              </a>
              @endif
              @if($serviceType !== 'legal')
              <a href="{{ url('/property-legal-help' . ($cityLabel ? '-in-'.$citySlug : '')) }}"
                 class="d-flex align-items-center gap-2 text-decoration-none text-dark p-2 rounded hover-bg"
                 style="color:inherit;">
                <i class="bi bi-briefcase" style="color:#6b21a8;"></i>
                <span class="small fw-semibold">Legal Help{{ $cityLabel ? ' in '.$cityLabel : '' }}</span>
              </a>
              @endif
            </div>
          </div>
        </div>

        {{-- Legal subtypes (only on legal pages) --}}
        @if($serviceType === 'legal' && $citySlug)
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6 class="fw-bold mb-3">Legal Services in {{ $cityLabel }}</h6>
            <div class="d-flex flex-column gap-1">
              @foreach([
                ['title-verification-in-'.$citySlug,       '🔍 Title Verification'],
                ['sale-deed-registration-in-'.$citySlug,   '📝 Sale Deed Registration'],
                ['property-dispute-lawyer-in-'.$citySlug,  '⚖️ Property Dispute'],
                ['rental-agreement-in-'.$citySlug,         '🏠 Rental Agreement'],
                ['will-registration-in-'.$citySlug,        '📜 Will / Succession'],
              ] as [$path, $label])
              <a href="{{ url('/'.$path) }}"
                 class="small py-1 text-decoration-none"
                 style="color:#6b21a8;">
                {{ $label }}
              </a>
              @endforeach
            </div>
          </div>
        </div>
        @endif

      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script>
function toggleFaq(el) {
  const item = el.closest('.faq-item');
  const isOpen = item.classList.contains('open');
  document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
  if (!isOpen) item.classList.add('open');
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('svc-lead-form');
  if (!form) return;

  @if($serviceType === 'loan')
    const endpoint = '{{ route("loan.lead.store") }}';
  @elseif($serviceType === 'insurance')
    const endpoint = '{{ route("insurance.lead.store") }}';
  @else
    const endpoint = '{{ route("legal.lead.store") }}';
  @endif

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('svc-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

    fetch(endpoint, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        document.getElementById('svc-form-wrap').style.display = 'none';
        document.getElementById('svc-success').style.display   = 'block';
      } else {
        btn.disabled = false;
        btn.innerHTML = btn.getAttribute('data-label') || 'Submit';
      }
    })
    .catch(() => {
      btn.disabled = false;
    });
  });
});
</script>
@endsection
