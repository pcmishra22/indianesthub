@extends('frontend.layout')

@section('title', $category->name . ' in Chandigarh Tricity | Verified ' . $category->name . 's | ' . config('app.name'))
@section('meta_description', 'Find verified ' . strtolower($category->name) . 's in Chandigarh, Mohali, Zirakpur & Panchkula. Compare profiles, experience and pricing on ' . config('app.name') . '.')
@section('canonical', route('services.category', $category))

@section('schema')
<script type="application/ld+json">
{
  "@@context":"https://schema.org","@type":"BreadcrumbList",
  "itemListElement":[
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Services","item":"{{ route('services') }}"},
    {"@type":"ListItem","position":3,"name":"{{ $category->name }}","item":"{{ route('services.category', $category) }}"}
  ]
}
</script>
@endsection

@section('content')
<style>
.sp-tab{padding:7px 14px;border-radius:20px;font-size:.8rem;font-weight:600;cursor:pointer;border:1.5px solid #e2e8f0;background:#fff;color:#475569;text-decoration:none;transition:all .15s;white-space:nowrap;}
.sp-tab.active,.sp-tab:hover{background:#0078d4;color:#fff;border-color:#0078d4;text-decoration:none;}
.sp-loading{text-align:center;padding:32px;display:none;}
.sp-end-msg{text-align:center;padding:24px;color:#94a3b8;font-size:.85rem;display:none;}
</style>

<main class="dl-page">

  <div class="dl-banner">
    <div class="container">
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0" style="background:none;padding:0;font-size:.82rem;">
          <li class="breadcrumb-item"><a href="/" style="color:rgba(255,255,255,.8);text-decoration:none;">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('services') }}" style="color:rgba(255,255,255,.8);text-decoration:none;">Services</a></li>
          <li class="breadcrumb-item active" style="color:#fff;">{{ $category->name }}</li>
        </ol>
      </nav>
      <h1 class="dl-banner-h1"><i class="bi {{ $category->icon ?? 'bi-tools' }} me-2"></i>{{ $category->name }}s in Chandigarh Tricity</h1>
      <p>Browse {{ $providers->total() }} verified {{ strtolower($category->name) }}s across Chandigarh, Mohali, Zirakpur &amp; Panchkula</p>
      <div class="dl-stats-row">
        <div class="dl-stat"><div class="val">{{ $providers->total() }}</div><div class="lbl">Providers</div></div>
        <div class="dl-stat"><div class="val">10+</div><div class="lbl">Cities</div></div>
        <div class="dl-stat"><div class="val">100%</div><div class="lbl">Verified</div></div>
      </div>
    </div>
  </div>

  <div class="dl-search-bar">
    <div class="container">
      <div class="dl-search-inner">
        <input type="text" id="sp-search" class="dl-input" placeholder="Search by name or city..." value="{{ request('search') }}">
        <button class="dl-search-btn" onclick="spDoSearch()"><i class="bi bi-search me-1"></i>Search</button>
      </div>
    </div>
  </div>

  <div style="background:#fff;border-bottom:1px solid #e2e8f0;padding:12px 0;">
    <div class="container">
      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('services.category', $category) }}" class="sp-tab active">All Cities</a>
        @foreach(\App\Http\Controllers\Frontend\ServicePublicController::getCityMap() as $slug => $label)
          <a href="{{ route('services.category.city', ['category'=>$category,'city'=>$slug]) }}" class="sp-tab">{{ $label }}</a>
        @endforeach
      </div>
    </div>
  </div>

  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
      <div class="dl-section-title" style="margin-bottom:0;">
        <span id="sp-result-label">{{ $providers->total() }} {{ strtolower($category->name) }}{{ $providers->total() != 1 ? 's' : '' }} found</span>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        @foreach($allCats->where('slug', '!=', $category->slug)->take(5) as $oc)
          <a href="{{ route('services.category', $oc) }}" class="sp-tab"><i class="bi {{ $oc->icon }} me-1"></i>{{ $oc->name }}</a>
        @endforeach
      </div>
    </div>

    <div class="row g-3 mb-4" id="sp-grid">
      @if($providers->count())
        @include('frontend.services.partials.provider-cards', ['providers'=>$providers,'category'=>$category])
      @else
        <div class="col-12">
          <div class="dl-empty">
            <i class="bi bi-people"></i>
            <h4>No {{ strtolower($category->name) }}s found</h4>
            <p>Be the first — <a href="{{ route('service-provider.register') }}">register as a {{ $category->name }}</a></p>
          </div>
        </div>
      @endif
    </div>

    <div class="sp-loading" id="sp-loader">
      <div class="spinner-border" style="color:#0078d4;" role="status"></div>
      <p class="mt-2 text-muted small">Loading more...</p>
    </div>
    <div class="sp-end-msg" id="sp-end">— You've seen all {{ strtolower($category->name) }}s —</div>
  </div>
</main>

<script>
(function(){
  const grid=document.getElementById('sp-grid'),loader=document.getElementById('sp-loader'),
        endMsg=document.getElementById('sp-end'),label=document.getElementById('sp-result-label'),
        searchEl=document.getElementById('sp-search');
  let page=2,loading=false,hasMore={{ $providers->hasMorePages() ? 'true' : 'false' }},curSearch='{{ addslashes(request('search','')) }}';

  function spDoSearch(){curSearch=searchEl.value.trim();page=2;hasMore=true;grid.innerHTML='';endMsg.style.display='none';load(true);}
  window.spDoSearch=spDoSearch;
  searchEl.addEventListener('keydown',e=>{if(e.key==='Enter')spDoSearch();});

  async function load(reset=false){
    if(loading||!hasMore)return;
    loading=true;loader.style.display='block';
    const url=new URL('{{ route('services.category', $category) }}',location.origin);
    url.searchParams.set('page',page);
    if(curSearch)url.searchParams.set('search',curSearch);
    try{
      const r=await fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}});
      const d=await r.json();
      if(d.html)grid.insertAdjacentHTML('beforeend',d.html);
      hasMore=d.has_more;
      if(!hasMore)endMsg.style.display='block';
      if(reset)label.textContent=d.total+' provider'+(d.total!==1?'s':'')+' found';
      page++;
    }catch(e){console.error(e);}
    loader.style.display='none';loading=false;
  }

  let ticking=false;
  window.addEventListener('scroll',()=>{
    if(ticking)return;ticking=true;
    requestAnimationFrame(()=>{
      if(window.scrollY+window.innerHeight>=document.documentElement.scrollHeight-400)load();
      ticking=false;
    });
  });
})();
</script>
@endsection
