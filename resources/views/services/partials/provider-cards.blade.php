@foreach($providers as $i => $provider)
<div class="col-xl-3 col-lg-4 col-md-6 sp-card-col" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 80 }}">
  <div class="dl-card">

    {{-- Photo / Avatar --}}
    <div class="dl-card-photo">
      @if($provider->profile_photo)
        <img src="{{ asset('storage/'.$provider->profile_photo) }}" alt="{{ $provider->display_name }}">
      @else
        <div class="dl-avatar-fallback">
          {{ strtoupper(substr($provider->business_name ?? $provider->full_name ?? 'S', 0, 1)) }}
        </div>
      @endif

      @if($provider->is_verified)
        <span class="dl-badge verified"><i class="bi bi-patch-check-fill me-1"></i>Verified</span>
      @endif

      <span class="dl-prop-count">
        <i class="bi bi-briefcase"></i>
        {{ $provider->years_experience ? $provider->years_experience.'+ yrs' : 'New' }}
      </span>
    </div>

    {{-- Body --}}
    <div class="dl-card-body">
      <h4 class="dl-dealer-name">{{ $provider->display_name }}</h4>

      @if($provider->business_name && $provider->business_name !== $provider->full_name)
        <p class="dl-company-name"><i class="bi bi-person"></i> {{ $provider->full_name }}</p>
      @endif

      <div class="dl-cities">
        <i class="bi bi-geo-alt-fill"></i>
        <span>{{ $provider->city }}
          @if(!empty($provider->operating_areas) && count($provider->operating_areas))
            + {{ count($provider->operating_areas) }} more area{{ count($provider->operating_areas) > 1 ? 's' : '' }}
          @endif
        </span>
      </div>

      {{-- Service category tags --}}
      <div class="dl-spec-tags">
        @foreach($provider->categories->take(3) as $cat)
          <span class="dl-spec-tag"><i class="bi {{ $cat->icon }} me-1"></i>{{ $cat->name }}</span>
        @endforeach
      </div>

      {{-- Mini stats --}}
      <div class="dl-mini-stats">
        <div class="dl-mini-stat">
          <div class="ms-val">{{ $provider->years_experience ?? '—' }}</div>
          <div class="ms-lbl">Yrs Exp.</div>
        </div>
        <div class="dl-mini-stat">
          <div class="ms-val">
            @if($provider->starting_price)
              ₹{{ number_format($provider->starting_price) }}
            @else
              —
            @endif
          </div>
          <div class="ms-lbl">{{ $provider->price_unit ?? 'Starting' }}</div>
        </div>
        <div class="dl-mini-stat">
          <div class="ms-val">{{ $provider->categories->count() }}</div>
          <div class="ms-lbl">Services</div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="dl-card-actions">
        <a href="{{ route('services.profile', $provider) }}" class="dl-btn-profile">
          <i class="bi bi-person-lines-fill"></i> View Profile
        </a>
        @if($provider->phone)
          <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$provider->phone) }}"
             target="_blank" class="dl-btn-wa" title="WhatsApp">
            <i class="bi bi-whatsapp"></i>
          </a>
        @endif
      </div>
    </div>

  </div>
</div>
@endforeach
