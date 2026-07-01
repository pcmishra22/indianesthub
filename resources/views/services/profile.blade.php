@extends('frontend.layout')

@php
  $catNames = $provider->categories->pluck('name')->join(', ');
  $initials = strtoupper(substr($provider->business_name ?? $provider->full_name ?? 'S', 0, 1));
@endphp

@section('title', $provider->display_name . ' — ' . $catNames . ' in ' . $provider->city . ' | ' . config('app.name'))
@section('meta_description', $provider->display_name . ' is a verified ' . strtolower($catNames) . ' in ' . $provider->city . '. View experience, pricing & contact on ' . config('app.name') . '.')
@section('canonical', route('services.profile', $provider))

@section('schema')
<script type="application/ld+json">
{
  "@@context":"https://schema.org","@type":"LocalBusiness",
  "name":"{{ addslashes($provider->display_name) }}",
  "image":"{{ $provider->profile_photo ? asset('storage/'.$provider->profile_photo) : asset('assets/img/real-estate/agent-1.webp') }}",
  "address":{"@type":"PostalAddress","addressLocality":"{{ $provider->city }}","addressCountry":"IN"},
  @if($provider->starting_price)"priceRange":"from ₹{{ number_format($provider->starting_price) }}",@endif
  "description":"{{ Str::limit(strip_tags($provider->bio ?? $catNames), 200) }}"
}
</script>
@endsection

@section('content')
<style>
/* ===== SERVICE PROVIDER PROFILE — mirrors agent-profile.blade.php ===== */
.dp-page{background:#f1f5f9;min-height:100vh;}
.dp-hero{background:linear-gradient(135deg,#0369a1 0%,#0284c7 60%,#0ea5e9 100%);padding:40px 0 0;color:#fff;position:relative;}
.dp-hero::after{content:'';display:block;height:40px;background:#f1f5f9;margin-top:-1px;clip-path:ellipse(55% 100% at 50% 100%);}
.dp-avatar{width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,.9);box-shadow:0 4px 20px rgba(0,0,0,.25);}
.dp-avatar-fallback{width:110px;height:110px;border-radius:50%;background:rgba(255,255,255,.2);border:4px solid rgba(255,255,255,.9);display:flex;align-items:center;justify-content:center;font-size:2.8rem;font-weight:800;color:#fff;}
.dp-verified-dot{position:absolute;bottom:6px;right:6px;width:24px;height:24px;border-radius:50%;background:#22c55e;border:2px solid #fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;color:#fff;}
.dp-dealer-name{font-size:1.75rem;font-weight:800;margin:0 0 4px;}
.dp-dealer-meta{display:flex;gap:16px;flex-wrap:wrap;font-size:.83rem;opacity:.9;margin-bottom:18px;}
.dp-dealer-meta span{display:flex;align-items:center;gap:5px;}
.dp-hero-ctas{display:flex;gap:10px;flex-wrap:wrap;padding-bottom:48px;}
.dp-hero-ctas .btn{border-radius:8px;font-weight:600;font-size:.88rem;padding:10px 20px;display:flex;align-items:center;gap:7px;}
.dp-stats-bar{background:#fff;border-bottom:1px solid #e2e8f0;margin-bottom:24px;}
.dp-stats-inner{display:flex;flex-wrap:wrap;}
.dp-stat-item{flex:1;min-width:130px;padding:16px 20px;text-align:center;border-right:1px solid #f1f5f9;}
.dp-stat-item:last-child{border-right:none;}
.dp-stat-item .s-val{font-size:1.55rem;font-weight:800;color:#0369a1;line-height:1;}
.dp-stat-item .s-lbl{font-size:.72rem;color:#64748b;margin-top:3px;text-transform:uppercase;letter-spacing:.5px;}
.dp-layout{display:flex;gap:20px;align-items:flex-start;}
.dp-main{flex:1;min-width:0;}
.dp-aside{width:300px;flex-shrink:0;position:sticky;top:80px;}
.dp-card{background:#fff;border-radius:12px;border:1px solid #e2e8f0;padding:20px 22px;margin-bottom:16px;}
.dp-card-title{font-size:1rem;font-weight:700;color:#1e293b;padding-bottom:12px;margin-bottom:16px;border-bottom:2px solid #f1f5f9;display:flex;align-items:center;gap:8px;}
.dp-card-title i{color:#0284c7;}
.dp-bio{font-size:.9rem;color:#475569;line-height:1.75;}
.dp-spec-grid{display:flex;flex-wrap:wrap;gap:8px;}
.dp-spec-chip{background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;font-size:.78rem;font-weight:500;padding:5px 12px;border-radius:20px;}
.dp-area-chip{background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;font-size:.78rem;font-weight:600;padding:5px 12px;border-radius:20px;display:flex;align-items:center;gap:4px;}
.dp-contact-card{background:linear-gradient(135deg,#0369a1 0%,#0284c7 100%);border-radius:12px;padding:20px;color:#fff;margin-bottom:16px;box-shadow:0 4px 15px rgba(3,105,161,.3);}
.dp-contact-card h5{font-size:.95rem;font-weight:700;margin-bottom:14px;}
.dp-contact-row{display:flex;flex-direction:column;gap:8px;margin-bottom:14px;}
.dp-contact-item{display:flex;align-items:center;gap:8px;font-size:.82rem;background:rgba(255,255,255,.15);border-radius:7px;padding:9px 12px;}
.dp-contact-btn{display:flex;flex-direction:column;gap:8px;}
.dp-contact-btn .btn{border-radius:7px;font-weight:600;font-size:.85rem;padding:10px;display:flex;align-items:center;justify-content:center;gap:7px;}
.dp-inq-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:18px;margin-bottom:16px;}
.dp-inq-card h5{font-size:.9rem;font-weight:700;color:#1e293b;margin-bottom:14px;}
.dp-inq-card .form-control{border:1px solid #cbd5e1;border-radius:7px;font-size:.82rem;padding:9px 12px;color:#334155;}
.dp-inq-card .form-control:focus{border-color:#0284c7;box-shadow:0 0 0 3px rgba(2,132,199,.1);}
.dp-login-gate{background:rgba(255,255,255,.12);border-radius:8px;padding:14px;text-align:center;font-size:.83rem;}
@media(max-width:991px){.dp-layout{flex-direction:column;}.dp-aside{width:100%;position:static;}}
@media(max-width:576px){.dp-dealer-name{font-size:1.35rem;}}
</style>

<main class="dp-page">

  {{-- HERO --}}
  <div class="dp-hero">
    <div class="container">
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
          <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.8);text-decoration:none;">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('services') }}" style="color:rgba(255,255,255,.8);text-decoration:none;">Services</a></li>
          @foreach($provider->categories->take(1) as $cat)
            <li class="breadcrumb-item"><a href="{{ route('services.category', $cat) }}" style="color:rgba(255,255,255,.8);text-decoration:none;">{{ $cat->name }}</a></li>
          @endforeach
          <li class="breadcrumb-item active" style="color:#fff;">{{ $provider->display_name }}</li>
        </ol>
      </nav>

      <div class="row align-items-start gy-3">
        <div class="col-lg-8">
          <div class="d-flex align-items-start gap-4 mb-3">
            <div style="position:relative;">
              @if($provider->profile_photo)
                <img src="{{ asset('storage/'.$provider->profile_photo) }}" class="dp-avatar" alt="{{ $provider->display_name }}">
              @else
                <div class="dp-avatar-fallback">{{ $initials }}</div>
              @endif
              @if($provider->is_verified)
                <div class="dp-verified-dot"><i class="bi bi-check-lg"></i></div>
              @endif
            </div>
            <div>
              <h1 class="dp-dealer-name">{{ $provider->display_name }}</h1>
              @if($provider->business_name && $provider->business_name !== $provider->full_name)
                <div style="font-size:.9rem;opacity:.88;margin-bottom:6px;"><i class="bi bi-person me-1"></i>{{ $provider->full_name }}</div>
              @endif
              <div class="dp-dealer-meta">
                <span><i class="bi bi-geo-alt-fill"></i> {{ $provider->city }}</span>
                @if($provider->years_experience)
                  <span><i class="bi bi-clock-history"></i> {{ $provider->years_experience }}+ yrs experience</span>
                @endif
                @if($provider->starting_price)
                  <span><i class="bi bi-currency-rupee"></i> From ₹{{ number_format($provider->starting_price) }} {{ $provider->price_unit }}</span>
                @endif
              </div>
              {{-- Service tags --}}
              <div class="d-flex flex-wrap gap-2">
                @foreach($provider->categories as $cat)
                  <a href="{{ route('services.category', $cat) }}"
                     style="background:rgba(255,255,255,.2);color:#fff;font-size:.75rem;font-weight:600;padding:4px 12px;border-radius:20px;text-decoration:none;border:1px solid rgba(255,255,255,.35);">
                    <i class="bi {{ $cat->icon }} me-1"></i>{{ $cat->name }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Hero CTAs --}}
          <div class="dp-hero-ctas">
            @auth
              @if($provider->phone)
                <a href="tel:+91{{ preg_replace('/[^0-9]/','',$provider->phone) }}" class="btn btn-light text-success fw-bold">
                  <i class="bi bi-telephone-fill"></i> Call Now
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$provider->phone) }}?text=Hi+{{ urlencode($provider->display_name) }},+I+found+you+on+{{ urlencode(config('app.name')) }}.+I+need+{{ urlencode(strtolower($catNames)) }}+help."
                   target="_blank" class="btn btn-success">
                  <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
              @endif
            @else
              <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-light fw-bold">
                <i class="bi bi-lock-fill me-1"></i> Login to Contact {{ $provider->display_name }}
              </a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- STATS BAR --}}
  <div class="dp-stats-bar">
    <div class="container">
      <div class="dp-stats-inner">
        <div class="dp-stat-item">
          <div class="s-val">{{ $provider->years_experience ?? '—' }}</div>
          <div class="s-lbl">Yrs Experience</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">{{ $provider->categories->count() }}</div>
          <div class="s-lbl">Services Offered</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">
            @if($provider->starting_price) ₹{{ number_format($provider->starting_price) }}
            @else —
            @endif
          </div>
          <div class="s-lbl">{{ $provider->price_unit ?? 'Starting Price' }}</div>
        </div>
        <div class="dp-stat-item">
          <div class="s-val">{{ $provider->is_verified ? 'Yes' : 'No' }}</div>
          <div class="s-lbl">Verified</div>
        </div>
      </div>
    </div>
  </div>

  {{-- MAIN LAYOUT --}}
  <div class="container pb-5">
    <div class="dp-layout">

      {{-- LEFT: MAIN CONTENT --}}
      <div class="dp-main">

        @if($provider->bio)
        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-person-fill"></i> About {{ $provider->display_name }}</div>
          <p class="dp-bio">{{ $provider->bio }}</p>
        </div>
        @endif

        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-tools"></i> Services Offered</div>
          <div class="dp-spec-grid">
            @foreach($provider->categories as $cat)
              <a href="{{ route('services.category', $cat) }}" class="dp-spec-chip" style="text-decoration:none;">
                <i class="bi {{ $cat->icon }} me-1 text-primary"></i>{{ $cat->name }}
              </a>
            @endforeach
          </div>
        </div>

        @if(!empty($provider->operating_areas) && count($provider->operating_areas))
        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-map-fill"></i> Areas Served</div>
          <div class="dp-spec-grid">
            @foreach($provider->operating_areas as $area)
              <span class="dp-area-chip"><i class="bi bi-geo-alt-fill"></i> {{ $area }}</span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Related providers --}}
        @php
          $related = collect();
          if($provider->categories->count()) {
            $related = $provider->categories->first()
              ->providers()
              ->where('service_providers.id','!=',$provider->id)
              ->limit(3)->get();
          }
        @endphp
        @if($related->count())
        <div class="dp-card">
          <div class="dp-card-title"><i class="bi bi-people-fill"></i> Other {{ $provider->categories->first()->name }}s in {{ $provider->city }}</div>
          <div class="row g-3">
            @foreach($related as $rel)
            <div class="col-md-4">
              <a href="{{ route('services.profile', $rel) }}" class="text-decoration-none">
                <div style="border:1px solid #e2e8f0;border-radius:10px;padding:14px;text-align:center;transition:all .2s;"
                     onmouseover="this.style.borderColor='#0284c7';this.style.boxShadow='0 4px 12px rgba(2,132,199,.12)'"
                     onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow=''">
                  @if($rel->profile_photo)
                    <img src="{{ asset('storage/'.$rel->profile_photo) }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;margin-bottom:8px;">
                  @else
                    <div style="width:52px;height:52px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:800;color:#1d4ed8;margin:0 auto 8px;">
                      {{ strtoupper(substr($rel->display_name,0,1)) }}
                    </div>
                  @endif
                  <div style="font-size:.85rem;font-weight:700;color:#1e293b;">{{ $rel->display_name }}</div>
                  <div style="font-size:.75rem;color:#64748b;">{{ $rel->city }}</div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- end dp-main --}}

      {{-- RIGHT: STICKY ASIDE --}}
      <div class="dp-aside">

        {{-- Contact card with login gate --}}
        <div class="dp-contact-card">
          <h5><i class="bi bi-person-badge-fill me-2"></i>Contact {{ $provider->display_name }}</h5>

          <div class="dp-contact-row">
            @auth
              @if($provider->phone)
                <div class="dp-contact-item"><i class="bi bi-telephone-fill"></i> {{ $provider->phone }}</div>
              @endif
              @if($provider->email)
                <div class="dp-contact-item"><i class="bi bi-envelope-fill"></i> {{ $provider->email }}</div>
              @endif
              <div class="dp-contact-item"><i class="bi bi-geo-alt-fill"></i> {{ $provider->city }}</div>
            @else
              <div class="dp-login-gate">
                <i class="bi bi-lock-fill" style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
                Login to view contact details
              </div>
            @endauth
          </div>

          <div class="dp-contact-btn">
            @auth
              @if($provider->phone)
                <a href="tel:+91{{ preg_replace('/[^0-9]/','',$provider->phone) }}" class="btn btn-light text-success fw-bold">
                  <i class="bi bi-telephone-fill"></i> Call Now
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$provider->phone) }}?text=Hi+{{ urlencode($provider->display_name) }},+I+found+you+on+{{ urlencode(config('app.name')) }}.+I+need+{{ urlencode(strtolower($catNames)) }}+help."
                   target="_blank" class="btn btn-success">
                  <i class="bi bi-whatsapp"></i> WhatsApp
                </a>
              @endif
            @else
              <a href="{{ route('login') }}" class="btn btn-light fw-bold w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login to Contact
              </a>
              <a href="{{ route('register') }}" class="btn btn-outline-light w-100">
                <i class="bi bi-person-plus me-2"></i> Register Free
              </a>
            @endauth
          </div>

          @if($provider->is_verified)
          <div class="d-flex gap-2 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.2);font-size:.72rem;opacity:.85;">
            <i class="bi bi-shield-check-fill"></i> Verified Service Provider
          </div>
          @endif
        </div>

        {{-- Quick inquiry form (only for logged-in users) --}}
        <div class="dp-inq-card">
          @auth
            <h5><i class="bi bi-chat-quote-fill me-2 text-primary"></i>Send Message</h5>
            <form action="{{ route('contact.store') }}" method="POST" id="sp-inq-form">
              @csrf
              <input type="hidden" name="subject" value="Service Enquiry — {{ $provider->display_name }}">
              <input type="hidden" name="provider_id" value="{{ $provider->id }}">
              <div class="mb-2">
                <input type="text" name="name" class="form-control" placeholder="Your Name *" value="{{ Auth::user()->name ?? '' }}" required>
              </div>
              <div class="mb-2">
                <input type="email" name="email" class="form-control" placeholder="Email *" value="{{ Auth::user()->email ?? '' }}" required>
              </div>
              <div class="mb-2">
                <input type="tel" name="phone" class="form-control" placeholder="Phone Number">
              </div>
              <div class="mb-3">
                <textarea name="message" class="form-control" rows="3"
                  placeholder="Describe what you need...">Hi {{ $provider->display_name }}, I need {{ strtolower($catNames) }} help. Please contact me.</textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100" style="border-radius:7px;font-weight:700;padding:10px;">
                <i class="bi bi-send-fill me-2"></i>Send Message
              </button>
            </form>
          @else
            <h5><i class="bi bi-chat-quote-fill me-2 text-primary"></i>Send a Message</h5>
            <div class="text-center py-3">
              <i class="bi bi-lock" style="font-size:2rem;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
              <p class="small text-muted mb-3">Login or register free to send a message to this provider</p>
              <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100 mb-2">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login to Message
              </a>
              <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm w-100">
                <i class="bi bi-person-plus me-1"></i> Register Free
              </a>
            </div>
          @endauth
        </div>

      </div>{{-- end dp-aside --}}

    </div>{{-- end dp-layout --}}
  </div>
</main>
@endsection
