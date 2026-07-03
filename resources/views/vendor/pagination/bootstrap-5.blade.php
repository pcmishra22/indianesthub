@if ($paginator->hasPages())
    <nav class="pgn-wrap" role="navigation" aria-label="Pagination Navigation">

        {{-- Mobile: simple prev / next --}}
        <div class="pgn-mobile">
            @if ($paginator->onFirstPage())
                <span class="pgn-btn pgn-disabled">&lsaquo; @lang('pagination.previous')</span>
            @else
                <a class="pgn-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo; @lang('pagination.previous')</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="pgn-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next') &rsaquo;</a>
            @else
                <span class="pgn-btn pgn-disabled">@lang('pagination.next') &rsaquo;</span>
            @endif
        </div>

        {{-- Desktop: summary + full pagination --}}
        <div class="pgn-desktop">
            <p class="pgn-summary">
                {!! __('Showing') !!}
                <strong>{{ $paginator->firstItem() }}</strong>
                {!! __('to') !!}
                <strong>{{ $paginator->lastItem() }}</strong>
                {!! __('of') !!}
                <strong>{{ $paginator->total() }}</strong>
                {!! __('results') !!}
            </p>

            <ul class="pgn-list">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="pgn-item pgn-disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span aria-hidden="true">&lsaquo;</span>
                    </li>
                @else
                    <li class="pgn-item">
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="pgn-item pgn-dots" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="pgn-item pgn-active" aria-current="page"><span>{{ $page }}</span></li>
                            @else
                                <li class="pgn-item"><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="pgn-item">
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="pgn-item pgn-disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span aria-hidden="true">&rsaquo;</span>
                    </li>
                @endif
            </ul>
        </div>

    </nav>

    {{--
        Self-contained styles: AdminKit's bundled CSS does not include Bootstrap's
        .pagination / .page-item / .page-link component styles, so relying on
        those classes alone renders as a raw bulleted <ul><li> list. Everything
        below is scoped under .pgn-wrap so it can't leak into or be broken by
        any other page/theme CSS, and doesn't depend on any external framework.
    --}}
    <style>
        .pgn-wrap { margin-top: 16px; }

        .pgn-mobile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        @media (min-width: 640px) { .pgn-mobile { display: none; } }

        .pgn-desktop {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        @media (min-width: 640px) { .pgn-desktop { display: flex; } }

        .pgn-summary { font-size: .82rem; color: #6c757d; margin: 0; white-space: nowrap; }
        .pgn-summary strong { color: #344767; font-weight: 600; }

        .pgn-list {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pgn-item { list-style: none; }
        .pgn-item a,
        .pgn-item span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 500;
            line-height: 1;
            color: #495057;
            background: #fff;
            text-decoration: none;
            cursor: pointer;
            user-select: none;
        }
        .pgn-item a:hover {
            background: #eef4ff;
            border-color: #635bff;
            color: #635bff;
        }
        .pgn-item.pgn-active span {
            background: #635bff;
            border-color: #635bff;
            color: #fff;
            font-weight: 700;
            cursor: default;
        }
        .pgn-item.pgn-disabled span {
            background: #f8f9fa;
            border-color: #f1f1f1;
            color: #ced4da;
            cursor: not-allowed;
        }
        .pgn-item.pgn-dots span {
            border-color: transparent;
            background: transparent;
            color: #adb5bd;
            cursor: default;
            min-width: 20px;
            padding: 0 2px;
        }

        .pgn-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 14px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: .8rem;
            font-weight: 500;
            color: #495057;
            background: #fff;
            text-decoration: none;
        }
        .pgn-btn.pgn-disabled { color: #ced4da; cursor: not-allowed; }
    </style>
@endif
