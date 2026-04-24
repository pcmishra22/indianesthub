@extends('frontend.layout')

{{-- ════════════════════════ SEO META ════════════════════════ --}}
@section('title', $blog->title . ' | ' . config('app.name') . ' Blog')
@section('meta_description', $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 155, '...'))
@section('meta_keywords', $blog->category . ', real estate ' . ($blog->category ?? 'tips') . ', ' . config('app.name') . ' blog, property guide tricity')
@section('canonical', route('blog.show', $blog->slug))
@section('robots', 'index, follow')
@section('og_type', 'article')
@section('og_title', $blog->title . ' | ' . config('app.name') . ' Blog')
@section('og_description', $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 155, '...'))
@section('og_url', route('blog.show', $blog->slug))
@section('og_image', $blog->image ? asset('storage/' . $blog->image) : asset('assets/img/og-default.jpg'))
@section('twitter_title', $blog->title)
@section('twitter_description', $blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 130, '...'))
@section('twitter_image', $blog->image ? asset('storage/' . $blog->image) : asset('assets/img/og-default.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ addslashes($blog->title) }}",
  "description": "{{ addslashes($blog->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($blog->content ?? ''), 155)) }}",
  "image": "{{ $blog->image ? asset('storage/'.$blog->image) : asset('assets/img/og-default.jpg') }}",
  "url": "{{ route('blog.show', $blog->slug) }}",
  "datePublished": "{{ $blog->published_at?->toIso8601String() ?? $blog->created_at->toIso8601String() }}",
  "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $blog->author ?? config('app.name') . ' Team' }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('assets/img/logo.png') }}"
    }
  },
  "articleSection": "{{ ucfirst($blog->category ?? 'Real Estate') }}",
  "wordCount": {{ str_word_count(strip_tags($blog->content ?? '')) }},
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('blog.show', $blog->slug) }}"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@type":"ListItem","position":2,"name":"Blog","item":"{{ route('blog') }}"},
    {"@type":"ListItem","position":3,"name":"{{ ucfirst($blog->category ?? 'General') }}","item":"{{ route('blog') }}?category={{ $blog->category }}"},
    {"@type":"ListItem","position":4,"name":"{{ addslashes($blog->title) }}","item":"{{ route('blog.show', $blog->slug) }}"}
  ]
}
</script>
@endsection

@section('content')
<main class="main">

  {{-- Page Title + Breadcrumb --}}
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0" style="font-size:1.1rem;font-weight:600;">{{ $blog->title }}</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ route('blog') }}">Blog</a></li>
          @if($blog->category)
            <li><a href="{{ route('blog') }}?category={{ $blog->category }}">{{ ucfirst($blog->category) }}</a></li>
          @endif
          <li class="current">{{ \Illuminate\Support\Str::limit($blog->title, 40) }}</li>
        </ol>
      </nav>
    </div>
  </div>

  {{-- Blog Details --}}
  <section class="blog-details section">
    <div class="container" data-aos="fade-up">
      <div class="row">
        <div class="col-lg-8">
          <article class="article">

            {{-- Featured Image --}}
            @if($blog->image)
            <div class="post-img mb-4">
              <img src="{{ asset('storage/'.$blog->image) }}" alt="{{ $blog->title }}" class="img-fluid rounded-3" loading="lazy">
            </div>
            @endif

            {{-- Meta --}}
            <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
              @if($blog->category)
                <span class="badge bg-primary">{{ ucfirst($blog->category) }}</span>
              @endif
              <span class="text-muted small"><i class="bi bi-calendar me-1"></i>{{ $blog->published_at?->format('d M Y') ?? $blog->created_at->format('d M Y') }}</span>
              <span class="text-muted small"><i class="bi bi-person me-1"></i>{{ $blog->author ?? config('app.name') . ' Team' }}</span>
              <span class="text-muted small"><i class="bi bi-eye me-1"></i>{{ number_format($blog->views_count ?? 0) }} views</span>
            </div>

            {{-- Title --}}
            <h1 style="font-size:1.8rem;font-weight:800;color:#0a2d5e;line-height:1.3;margin-bottom:1rem;">
              {{ $blog->title }}
            </h1>

            {{-- Excerpt --}}
            @if($blog->excerpt)
            <p class="lead text-muted" style="font-size:1.05rem;line-height:1.7;border-left:4px solid #0078d4;padding-left:16px;background:#f8fafc;padding:14px 16px;border-radius:0 8px 8px 0;">
              {{ $blog->excerpt }}
            </p>
            @endif

            {{-- Content --}}
            <div class="article-content mt-4" style="line-height:1.85;color:#334155;font-size:1rem;">
              {!! $blog->content !!}
            </div>

            {{-- Tags / Internal Links --}}
            <div class="mt-5 pt-4" style="border-top:1px solid #e2e8f0;">
              <strong style="color:#0a2d5e;">Explore Properties:</strong>
              <div class="mt-2 d-flex flex-wrap gap-2">
                <a href="{{ url('/flats-in-zirakpur') }}" class="badge bg-light text-primary border" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">Flats in Zirakpur</a>
                <a href="{{ url('/flats-in-mohali') }}" class="badge bg-light text-primary border" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">Flats in Mohali</a>
                <a href="{{ url('/new-projects-in-chandigarh') }}" class="badge bg-light text-primary border" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">New Projects Chandigarh</a>
                <a href="{{ url('/3bhk-flats-in-mohali') }}" class="badge bg-light text-primary border" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">3 BHK Flats Mohali</a>
                <a href="{{ route('properties') }}" class="badge bg-primary text-white" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">All Properties</a>
              </div>
            </div>

          </article>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4 mt-4 mt-lg-0">

          {{-- Related Posts --}}
          @if($relatedPosts->isNotEmpty())
          <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
              <h5 class="fw-700 mb-3" style="color:#0a2d5e;font-size:1rem;">Related Articles</h5>
              @foreach($relatedPosts as $related)
              <div class="d-flex gap-3 mb-3 pb-3" style="{{ !$loop->last ? 'border-bottom:1px solid #f1f5f9' : '' }}">
                @if($related->image)
                  <img src="{{ asset('storage/'.$related->image) }}" alt="{{ $related->title }}" style="width:70px;height:55px;object-fit:cover;border-radius:6px;" loading="lazy">
                @endif
                <div>
                  <a href="{{ route('blog.show', $related->slug) }}" style="color:#0a2d5e;font-weight:600;font-size:.85rem;text-decoration:none;line-height:1.3;display:block;">{{ $related->title }}</a>
                  <small class="text-muted">{{ $related->published_at?->format('d M Y') }}</small>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          {{-- Quick Search CTA --}}
          <div class="card border-0 rounded-3" style="background:linear-gradient(135deg,#0a2d5e,#0078d4);color:#fff;">
            <div class="card-body p-4 text-center">
              <i class="bi bi-houses" style="font-size:2.5rem;opacity:.8;"></i>
              <h5 class="fw-800 mt-2 mb-1">Find Your Dream Property</h5>
              <p style="opacity:.8;font-size:.85rem;">Browse thousands of verified listings in Chandigarh Tricity</p>
              <a href="{{ route('properties') }}" class="btn btn-warning btn-sm fw-700 mt-1 px-4">Search Now</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

</main>
@endsection
