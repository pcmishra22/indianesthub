@if ($paginator->hasPages())
<nav class="hs-pagination" role="navigation" aria-label="Pagination">

  {{-- Mobile: just prev / next --}}
  <div class="hs-pag-mobile">
    @if ($paginator->onFirstPage())
      <span class="hs-pag-btn disabled"><i class="bi bi-arrow-left"></i> Previous</span>
    @else
      <a class="hs-pag-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">
        <i class="bi bi-arrow-left"></i> Previous
      </a>
    @endif

    <span class="hs-pag-info">
      Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
    </span>

    @if ($paginator->hasMorePages())
      <a class="hs-pag-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">
        Next <i class="bi bi-arrow-right"></i>
      </a>
    @else
      <span class="hs-pag-btn disabled">Next <i class="bi bi-arrow-right"></i></span>
    @endif
  </div>

  {{-- Desktop: full pagination --}}
  <div class="hs-pag-desktop">

    <span class="hs-pag-summary">
      Showing <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
      of <strong>{{ $paginator->total() }}</strong> results
    </span>

    <div class="hs-pag-controls">
      {{-- Previous --}}
      @if ($paginator->onFirstPage())
        <span class="hs-pag-item disabled" aria-disabled="true" title="Previous">
          <i class="bi bi-chevron-left"></i>
        </span>
      @else
        <a class="hs-pag-item" href="{{ $paginator->previousPageUrl() }}" rel="prev" title="Previous">
          <i class="bi bi-chevron-left"></i>
        </a>
      @endif

      {{-- Page numbers --}}
      @foreach ($elements as $element)
        {{-- Dots separator --}}
        @if (is_string($element))
          <span class="hs-pag-item dots">{{ $element }}</span>
        @endif

        {{-- Page links --}}
        @if (is_array($element))
          @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
              <span class="hs-pag-item active" aria-current="page">{{ $page }}</span>
            @else
              <a class="hs-pag-item" href="{{ $url }}">{{ $page }}</a>
            @endif
          @endforeach
        @endif
      @endforeach

      {{-- Next --}}
      @if ($paginator->hasMorePages())
        <a class="hs-pag-item" href="{{ $paginator->nextPageUrl() }}" rel="next" title="Next">
          <i class="bi bi-chevron-right"></i>
        </a>
      @else
        <span class="hs-pag-item disabled" aria-disabled="true" title="Next">
          <i class="bi bi-chevron-right"></i>
        </span>
      @endif
    </div>

  </div>

</nav>

<style>
/* {{ config('app.name') }} Custom Pagination */
.hs-pagination { margin-top: 28px; }

/* Mobile */
.hs-pag-mobile {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}
@media (min-width: 640px) { .hs-pag-mobile { display: none; } }

.hs-pag-info { font-size: .82rem; color: #64748b; }

/* Desktop */
.hs-pag-desktop {
  display: none;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
}
@media (min-width: 640px) { .hs-pag-desktop { display: flex; } }

.hs-pag-summary {
  font-size: .82rem;
  color: #64748b;
  white-space: nowrap;
}
.hs-pag-summary strong { color: #334155; }

/* Controls */
.hs-pag-controls {
  display: flex;
  align-items: center;
  gap: 4px;
  flex-wrap: wrap;
}

/* Individual item */
.hs-pag-item,
.hs-pag-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 38px;
  height: 38px;
  padding: 0 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: .85rem;
  font-weight: 500;
  color: #475569;
  background: #fff;
  text-decoration: none !important;
  transition: all .18s ease;
  cursor: pointer;
  user-select: none;
  line-height: 1;
  gap: 5px;
}
.hs-pag-item:hover:not(.active):not(.disabled):not(.dots),
.hs-pag-btn:hover:not(.disabled) {
  background: #eff6ff;
  border-color: #1f85de;
  color: #1f85de;
}
.hs-pag-item.active {
  background: #1f85de;
  border-color: #1f85de;
  color: #fff;
  font-weight: 700;
  cursor: default;
  box-shadow: 0 2px 8px rgba(31,133,222,.35);
}
.hs-pag-item.disabled,
.hs-pag-btn.disabled {
  background: #f8fafc;
  border-color: #f1f5f9;
  color: #cbd5e1;
  cursor: not-allowed;
  pointer-events: none;
}
.hs-pag-item.dots {
  border-color: transparent;
  background: transparent;
  color: #94a3b8;
  cursor: default;
  min-width: 24px;
  padding: 0 4px;
}

/* Chevron icons inside items */
.hs-pag-item i, .hs-pag-btn i {
  font-size: .9rem;
  line-height: 1;
}
</style>
@endif
