{{-- @if ($paginator->hasPages()) --}}
<div class="pagination">
    
    <div class="pagination-info">
        Menampilkan
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
        dari {{ $paginator->total() }} data
    </div>

    <div class="pagination-controls">

        {{-- PREV --}}
        @if ($paginator->onFirstPage())
            <button class="page-btn" disabled>‹</button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">
                <button class="page-btn">‹</button>
            </a>
        @endif

        {{-- LOGIC 5 BUTTON --}}
        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $start = max($current - 2, 1);
            $end = min($start + 4, $last);

            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
        @endphp

        @for ($i = $start; $i <= $end; $i++)
            <a href="{{ $paginator->url($i) }}">
                <button class="page-btn {{ $current == $i ? 'active' : '' }}">
                    {{ $i }}
                </button>
            </a>
        @endfor

        {{-- NEXT --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">
                <button class="page-btn">›</button>
            </a>
        @else
            <button class="page-btn" disabled>›</button>
        @endif

    </div>
</div>
{{-- @endif --}}
