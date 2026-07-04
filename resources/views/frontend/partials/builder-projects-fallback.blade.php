{{-- ════════════════════════════════════════════════════════════════
     Builder Projects Fallback
     Shown instead of "No properties found" when a location has no
     individual dealer/agent listings yet but does have builder
     projects (e.g. national metro pages like Pune, Bangalore,
     Hyderabad, Delhi NCR).
     ════════════════════════════════════════════════════════════════ --}}
@if(isset($builderProjects) && $builderProjects->count())
  <div class="builder-fallback-section">

    <div class="builder-fallback-note">
      <i class="bi bi-info-circle"></i>
      <div>
        No individual dealer listings are live in {{ $locationLabel ?? 'this city' }} right now —
        here are builder projects available in {{ $locationLabel ?? 'this area' }} instead.
        @if(!empty($builderProjectsCityUrl))
          <a href="{{ $builderProjectsCityUrl }}">View all builders in {{ $locationLabel ?? 'this city' }} &rarr;</a>
        @endif
      </div>
    </div>

    <div class="row g-4">
      @foreach($builderProjects as $project)
        <div class="col-lg-3 col-md-4 col-sm-6">
          <div class="bf-proj-card">
            <div class="bf-proj-img">
              @if($project->cover_image)
                <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}">
              @else
                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:2.2rem;color:#0078d4;">
                  <i class="bi bi-buildings-fill"></i>
                </div>
              @endif
              @if($project->status)
                <span class="bf-proj-status">{{ $project->status }}</span>
              @endif
            </div>
            <div class="bf-proj-body">
              @if($project->builder)
                <div class="bf-proj-builder">{{ $project->builder->company_name ?? $project->builder->name }}</div>
              @endif
              <div class="bf-proj-title">{{ $project->title }}</div>
              @if($project->city)
                <div class="bf-proj-city"><i class="bi bi-geo-alt"></i> {{ $project->city }}{{ $project->state ? ', ' . $project->state : '' }}</div>
              @endif
              @if($project->price_from || $project->price_to)
                <div class="bf-proj-price">
                  @if($project->price_from) ₹{{ number_format($project->price_from/100000,1) }}L @endif
                  @if($project->price_from && $project->price_to) &ndash; @endif
                  @if($project->price_to) ₹{{ number_format($project->price_to/10000000,2) }}Cr @endif
                </div>
              @endif
              <a href="{{ $project->slug ? route('projects.show', $project->slug) : route('projects.show.by-id', $project->id) }}" class="bf-proj-btn">
                <i class="bi bi-eye me-1"></i> View Project
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@else
  <div class="no-results-box">
    <i class="bi bi-building-slash"></i>
    <h4>No properties found</h4>
    <p>Try broadening your search or <a href="{{ url('/properties') }}">clear all filters</a>.</p>
  </div>
@endif
