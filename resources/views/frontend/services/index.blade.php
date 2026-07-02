@extends('frontend.layout')

@section('title', 'Services – Home Loans, Interior Design, Electricians & More | ' . config('app.name'))
@section('meta_description', 'Buy, sell, rent properties and find every home service — electricians, plumbers, interior designers, loan providers and more across Chandigarh, Mohali, Zirakpur & Panchkula.')
@section('canonical', route('services'))

@section('schema')
<script type="application/ld+json">
{
  "@@context":"https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Services","item":"{{ route('services') }}"}
  ]
}
</script>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════
     INCLUDE THE ORIGINAL RICH SERVICES CONTENT
     (hero, stats strip, 6 core cards, additional
      services grid, CTA etc — unchanged)
══════════════════════════════════════════════════ --}}
@include('frontend.partials.services')

{{-- ══════════════════════════════════════════════════
     HOME SERVICES MARKETPLACE — same card style
══════════════════════════════════════════════════ --}}
<section style="background:#f8faff;padding:60px 0 70px;border-top:1px solid #e2e8f0;">
  <div class="container">

    <div class="text-center mb-5">
      <span style="background:#dbeafe;color:#1d4ed8;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 14px;border-radius:20px;">Home Services Marketplace</span>
      <h2 style="font-size:1.9rem;font-weight:800;color:#0a2d5e;margin-top:12px;">Find Verified Home Service Professionals</h2>
      <p style="color:#64748b;max-width:600px;margin:8px auto 0;">Electricians, plumbers, interior designers, loan providers and more — all verified, all in Tricity. Click any category to browse professionals near you.</p>
    </div>

    <div class="row g-4">
      @php
        $colors = [
          '#dbeafe|#1d4ed8', '#dcfce7|#15803d', '#fce7f3|#be185d', '#fef3c7|#d97706',
          '#ede9fe|#7c3aed', '#ccfbf1|#0f766e', '#fee2e2|#dc2626', '#fff7ed|#ea580c',
          '#f0fdf4|#16a34a', '#fdf4ff|#a21caf', '#eff6ff|#1d4ed8', '#fefce8|#ca8a04',
        ];
      @endphp

      @forelse($categories as $i => $category)
        @php [$bg, $ic] = explode('|', $colors[$i % count($colors)]); @endphp
        <div class="col-xl-3 col-lg-4 col-md-6">
          <a href="{{ route('services.category', $category) }}"
             class="svc-card d-block h-100 text-decoration-none"
             style="background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(10,45,94,.08);transition:transform .25s,box-shadow .25s;border:1px solid #f1f5f9;"
             onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 12px 32px rgba(10,45,94,.15)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(10,45,94,.08)'">

            {{-- Colour accent header --}}
            <div style="height:90px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;position:relative;">
              <i class="bi {{ $category->icon ?? 'bi-tools' }}" style="font-size:2.6rem;color:{{ $ic }};"></i>
              @if($category->providers_count > 0)
                <span style="position:absolute;top:12px;right:14px;background:{{ $ic }};color:#fff;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                  {{ $category->providers_count }} {{ Str::plural('pro', $category->providers_count) }}
                </span>
              @endif
            </div>

            <div style="padding:18px 20px 22px;">
              <h4 style="font-weight:700;color:#0a2d5e;margin-bottom:6px;font-size:1rem;">{{ $category->name }}</h4>
              <p style="color:#64748b;font-size:.83rem;line-height:1.6;margin-bottom:14px;">
                @switch($category->slug)
                  @case('electrician') Wiring, fuse boxes, fans, AC installation & electrical repairs. @break
                  @case('plumber') Pipe fitting, leakage repair, bathroom fixtures & water tanks. @break
                  @case('interior-designer') Full home interiors, modular kitchens, false ceilings & décor. @break
                  @case('mistry-mason') Civil construction, plastering, tiling & structural repairs. @break
                  @case('painter') Wall painting, waterproofing, texture & exterior finishes. @break
                  @case('carpenter') Furniture, cabinets, doors, windows & wooden flooring. @break
                  @case('packers-movers') Safe home & office shifting with packing & transport. @break
                  @case('home-loan-provider') Best home loan rates from top banks & NBFCs in minutes. @break
                  @case('insurance-agent') Property & home insurance — structure, contents & more. @break
                  @case('legal-advisor') Property title, sale deed, registry & legal compliance. @break
                  @case('vastu-consultant') Vastu shastra advice for home, office & new construction. @break
                  @case('pest-control') Cockroach, termite, rodent & general pest control services. @break
                  @default Find verified professionals near you. @break
                @endswitch
              </p>
              <div style="display:inline-flex;align-items:center;gap:6px;background:{{ $bg }};color:{{ $ic }};padding:7px 16px;border-radius:8px;font-size:.82rem;font-weight:700;">
                Browse {{ $category->name }}s <i class="bi bi-arrow-right"></i>
              </div>
            </div>

          </a>
        </div>
      @empty
        <p class="text-muted col-12 text-center">No service categories found.</p>
      @endforelse
    </div>

    {{-- Provider CTA --}}
    <div class="text-center mt-5 p-4 p-md-5" style="background:linear-gradient(135deg,#0a2d5e 0%,#0078d4 100%);border-radius:18px;color:#fff;">
      <h3 style="font-weight:800;margin-bottom:8px;">Are you a service professional?</h3>
      <p style="opacity:.88;margin-bottom:20px;max-width:520px;margin-left:auto;margin-right:auto;">
        Join hundreds of electricians, plumbers, designers and loan providers already getting leads on {{ config('app.name') }}. Registration is 100% free.
      </p>
      <a href="{{ route('service-provider.register') }}"
         style="display:inline-flex;align-items:center;gap:8px;background:#ffcc00;color:#0a2d5e;padding:13px 30px;border-radius:9px;font-weight:800;text-decoration:none;font-size:.95rem;">
        <i class="bi bi-tools"></i> Register as a Service Provider — Free
      </a>
    </div>

  </div>
</section>

@endsection
