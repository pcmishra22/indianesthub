    <main class="main">

        <!-- Page Title -->
        <div class="page-title light-background">
          <div class="container d-lg-flex justify-content-between align-items-center">
            <h1 class="mb-2 mb-lg-0">Real Estate Blog – Tips, Guides &amp; Market Updates</h1>
            <nav class="breadcrumbs">
              <ol>
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="current">Blog</li>
              </ol>
            </nav>
          </div>
        </div><!-- End Page Title -->

        {{-- ════════ DYNAMIC BLOG POSTS FROM DATABASE ════════ --}}
        @if(isset($posts) && $posts->isNotEmpty())
        <section class="py-5" style="background:#f4f6f9;">
          <div class="container">
            <div class="row mb-4 align-items-center">
              <div class="col">
                <h2 style="font-size:1.4rem;font-weight:800;color:#0a2d5e;">Latest Articles</h2>
              </div>
              @if(isset($categories) && $categories->isNotEmpty())
              <div class="col-auto d-flex flex-wrap gap-2">
                <a href="{{ route('blog') }}" class="badge {{ !request('category') ? 'bg-primary' : 'bg-light text-dark border' }}" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">All</a>
                @foreach($categories as $cat)
                <a href="{{ route('blog') }}?category={{ $cat }}" class="badge {{ request('category') == $cat ? 'bg-primary' : 'bg-light text-dark border' }}" style="font-size:.8rem;padding:6px 12px;text-decoration:none;">{{ ucfirst($cat) }}</a>
                @endforeach
              </div>
              @endif
            </div>
            <div class="row g-4">
              @foreach($posts as $post)
              <div class="col-md-6 col-lg-4">
                <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none d-block h-100">
                  <div class="card border-0 h-100" style="border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.07);transition:transform .2s,box-shadow .2s;" onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.12)'" onmouseleave="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'">
                    @if($post->image)
                      <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" style="width:100%;height:200px;object-fit:cover;" loading="lazy">
                    @else
                      <div style="width:100%;height:200px;background:linear-gradient(135deg,#0a2d5e,#0078d4);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-file-text" style="font-size:3rem;color:rgba(255,255,255,.4);"></i>
                      </div>
                    @endif
                    <div class="card-body p-4">
                      @if($post->category)
                        <span class="badge bg-primary mb-2" style="font-size:.72rem;">{{ ucfirst($post->category) }}</span>
                      @endif
                      <h3 style="font-size:1rem;font-weight:700;color:#0a2d5e;line-height:1.4;margin-bottom:.5rem;">{{ $post->title }}</h3>
                      @if($post->excerpt)
                        <p class="text-muted mb-2" style="font-size:.85rem;line-height:1.6;">{{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}</p>
                      @endif
                      <div class="d-flex align-items-center justify-content-between mt-auto pt-2" style="border-top:1px solid #f1f5f9;">
                        <small class="text-muted"><i class="bi bi-calendar me-1"></i>{{ $post->published_at?->format('d M Y') }}</small>
                        <small class="text-primary fw-600">Read More →</small>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              @endforeach
            </div>
            {{-- Pagination --}}
            @if($posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
              {{ $posts->links('vendor.pagination.indianesthub') }}
            </div>
            @endif
          </div>
        </section>
        @endif
        {{-- ════════ END DYNAMIC POSTS ════════ --}}

        <!-- Blog Hero Section -->
        <section id="blog-hero" class="blog-hero section">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <x-banner-ad placement="blog_inline" />
              </div>
            </div>
          </div>
          <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-4">
              <!-- Main Content Area -->
              <div class="col-lg-8">
                <!-- Featured Article -->
                <article class="featured-post position-relative mb-4" data-aos="fade-up">
                  <img src="/assets/img/blog/blog-hero-9.webp" alt="Featured post" class="img-fluid">
                  <div class="post-overlay">
                    <div class="post-content">
                      <div class="post-meta">
                        <span class="category">Politics</span>
                        <span class="date">02/15/2024</span>
                      </div>
                      <h2 class="post-title">
                        <a href="#">Optimizing Strategic Initiatives Through Cross-Functional Collaboration</a>
                      </h2>
                      <p class="post-excerpt">Leveraging core competencies to drive sustainable growth and maximize stakeholder value through innovative solutions and market-driven approaches.</p>
                      <div class="post-author">
                        <span>by</span>
                        <a href="#">Jennifer Mitchell</a>
                      </div>
                    </div>
                  </div>
                </article>
                <!-- Secondary Articles -->
                <div class="row g-4">
                  <!-- ...secondary articles as in blog.html... -->
                </div>
              </div><!-- End Main Content Area -->
              <!-- Sidebar with Tabs -->
              <div class="col-lg-4">
                <!-- ...sidebar tabs as in blog.html... -->
              </div>
            </div>
          </div>
        </section><!-- /Blog Hero Section -->

        <!-- Blog Posts Section -->
        <section id="blog-posts" class="blog-posts section">
          <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-4">
              <!-- ...blog post list items as in blog.html... -->
            </div>
          </div>
        </section><!-- /Blog Posts Section -->

        <!-- Pagination 2 Section -->
        <section id="pagination-2" class="pagination-2 section">
          <div class="container">
            <nav class="d-flex justify-content-center" aria-label="Page navigation">
              <ul>
                <li>
                  <a href="#" aria-label="Previous page">
                    <i class="bi bi-arrow-left"></i>
                    <span class="d-none d-sm-inline">Previous</span>
                  </a>
                </li>
                <li><a href="#" class="active">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li class="ellipsis">...</li>
                <li><a href="#">8</a></li>
                <li><a href="#">9</a></li>
                <li><a href="#">10</a></li>
                <li>
                  <a href="#" aria-label="Next page">
                    <span class="d-none d-sm-inline">Next</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </section><!-- /Pagination 2 Section -->

    </main>
    <section id="recent-blog-posts" class="recent-blog-posts section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Recent Blog Posts</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">

          <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
            <article class="featured-post">
              <div class="featured-img">
                <img src="{{ asset('frontend/img/blog/blog-post-7.webp') }}" alt="" class="img-fluid" loading="lazy">
                <div class="featured-badge">Featured</div>
              </div>

              <div class="featured-content">
                <div class="post-header">
                  <a href="#" class="category">Technology</a>
                  <span class="post-date">Dec 18, 2024</span>
                </div>

                <h2 class="post-title">
                  <a href="#">Lorem ipsum dolor sit amet consectetur adipiscing elit mauris</a>
                </h2>

                <p class="post-excerpt">
                  Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.
                </p>

                <div class="post-footer">
                  <div class="author-info">
                    <img src="{{ asset('frontend/img/person/person-m-8.webp') }}" alt="" class="author-avatar">
                    <div class="author-details">
                      <span class="author-name">Marcus Johnson</span>
                      <span class="read-time">5 min read</span>
                    </div>
                  </div>
                  <a href="#" class="read-more">Read More</a>
                </div>
              </div>
            </article>

            <article class="featured-post" data-aos="fade-up" data-aos-delay="400">
              <div class="featured-img">
                <img src="{{ asset('frontend/img/blog/blog-post-3.webp') }}" alt="" class="img-fluid" loading="lazy">
                <div class="featured-badge">Featured</div>
              </div>

              <div class="featured-content">
                <div class="post-header">
                  <a href="#" class="category">Innovation</a>
                  <span class="post-date">Dec 16, 2024</span>
                </div>

                <h2 class="post-title">
                  <a href="#">Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse</a>
                </h2>

                <p class="post-excerpt">
                  At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.
                </p>

                <div class="post-footer">
                  <div class="author-info">
                    <img src="{{ asset('frontend/img/person/person-f-7.webp') }}" alt="" class="author-avatar">
                    <div class="author-details">
                      <span class="author-name">Emma Rodriguez</span>
                      <span class="read-time">7 min read</span>
                    </div>
                  </div>
                  <a href="#" class="read-more">Read More</a>
                </div>
              </div>
            </article>
          </div><!-- End featured post -->

          <div class="col-lg-4">

            <article class="recent-post" data-aos="fade-up" data-aos-delay="200">
              <div class="recent-img">
                <img src="{{ asset('frontend/img/blog/blog-post-5.webp') }}" alt="" class="img-fluid" loading="lazy">
              </div>
              <div class="recent-content">
                <a href="#" class="category">Business</a>
                <h3 class="recent-title">
                  <a href="#">Excepteur sint occaecat cupidatat non proident sunt</a>
                </h3>
                <div class="recent-meta">
                  <span class="author">By Jessica Kim</span>
                  <span class="date">Dec 15, 2024</span>
                </div>
              </div>
            </article><!-- End recent post -->

            <article class="recent-post" data-aos="fade-up" data-aos-delay="250">
              <div class="recent-img">
                <img src="{{ asset('frontend/img/blog/blog-post-9.webp') }}" alt="" class="img-fluid" loading="lazy">
              </div>
              <div class="recent-content">
                <a href="#" class="category">Marketing</a>
                <h3 class="recent-title">
                  <a href="#">Voluptate velit esse cillum dolore eu fugiat nulla</a>
                </h3>
                <div class="recent-meta">
                  <span class="author">By David Park</span>
                  <span class="date">Dec 12, 2024</span>
                </div>
              </div>
            </article><!-- End recent post -->

            <article class="recent-post" data-aos="fade-up" data-aos-delay="300">
              <div class="recent-img">
                <img src="{{ asset('frontend/img/blog/blog-post-6.webp') }}" alt="" class="img-fluid" loading="lazy">
              </div>
              <div class="recent-content">
                <a href="#" class="category">Design</a>
                <h3 class="recent-title">
                  <a href="#">Pariatur consectetur adipiscing elit sed do eiusmod</a>
                </h3>
                <div class="recent-meta">
                  <span class="author">By Sarah Miller</span>
                  <span class="date">Dec 10, 2024</span>
                </div>
              </div>
            </article><!-- End recent post -->

            <article class="recent-post" data-aos="fade-up" data-aos-delay="350">
              <div class="recent-img">
                <img src="{{ asset('frontend/img/blog/blog-post-8.webp') }}" alt="" class="img-fluid" loading="lazy">
              </div>
              <div class="recent-content">
                <a href="#" class="category">Tech</a>
                <h3 class="recent-title">
                  <a href="#">Magna aliquam erat volutpat consectetur adipiscing</a>
                </h3>
                <div class="recent-meta">
                  <span class="author">By Alex Chen</span>
                  <span class="date">Dec 8, 2024</span>
                </div>
              </div>
            </article><!-- End recent post -->

          </div>

        </div>

      </div>

    </section><!-- /Recent Blog Posts Section -->