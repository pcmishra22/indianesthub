<main class="main">

  <!-- Page Title -->
  <div class="page-title light-background">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Real Estate Blog</h1>
      <nav class="breadcrumbs">
        <ol>
          <li><a href="{{ url('/') }}">Home</a></li>
          <li class="current">Blog</li>
        </ol>
      </nav>
    </div>
  </div><!-- End Page Title -->

  <!-- Category Filter Section -->
  @if(isset($categories) && $categories->isNotEmpty())
  <section id="blog-filters" class="blog-filters section py-4">
    <div class="container">
      <div class="d-flex flex-wrap gap-2 justify-content-center">
        <a href="{{ route('blog') }}" class="btn btn-sm {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }}">All</a>
        @foreach($categories as $cat)
          <a href="{{ route('blog', ['category' => $cat]) }}" class="btn btn-sm {{ request('category') == $cat ? 'btn-primary' : 'btn-outline-primary' }}">
            {{ ucfirst($cat) }}
          </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- Blog Section -->
  <section id="blog" class="blog section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4 posts-list">
        @foreach($posts as $post)
          <div class="col-xl-4 col-md-6">
            <article>
              <div class="post-img">
                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid" loading="lazy">
              </div>
              <p class="post-category">{{ $post->category }}</p>
              <h2 class="title">
                <a href="{{ route('blog.show', $post->slug) }}">{{ str_replace(' | indianesthub.com', '', $post->title) }}</a>
              </h2>
              <div class="d-flex align-items-center">
                <div class="post-meta">
                  <p class="post-author-list">By Admin</p>
                  <p class="post-date">
                    <time datetime="{{ $post->created_at?->format('Y-m-d') }}">{{ $post->created_at?->format('M d, Y') }}</time>
                  </p>
                </div>
              </div>
              <div class="content mt-3">
                <p>{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                <a href="{{ route('blog.show', $post->slug) }}" class="readmore stretched-link"><span>Read More</span><i class="bi bi-arrow-right"></i></a>
              </div>
            </article>
          </div><!-- End post list item -->
        @endforeach
      </div>
    </div>
  </section><!-- /Blog Section -->

  <!-- Pagination Section -->
  @if($posts->hasPages())
  <section id="blog-pagination" class="blog-pagination section">
    <div class="container">
      <div class="d-flex justify-content-center">
        {{ $posts->links('vendor.pagination.indianesthub') }}
      </div>
    </div>
  </section>
  @endif

</main>