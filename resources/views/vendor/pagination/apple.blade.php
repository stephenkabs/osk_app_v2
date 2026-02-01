@if ($paginator->hasPages())
    <nav role="navigation" class="pagination-nav">
        <ul class="aw-pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span>&laquo;</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li class="disabled"><span>&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
<style>
    .aw-pagination-list {
    display: flex;
    gap: 6px;
    justify-content: center;
    padding: 0;
    margin: 0;
    list-style: none;
}

.aw-pagination-list li {
    display: inline-block;
}

.aw-pagination-list a,
.aw-pagination-list span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 13px;
    color: var(--ink);
    background: #f6f7fb;
    border: 1px solid var(--border);
    transition: all 0.2s ease;
    text-decoration: none;
}

.aw-pagination-list a:hover {
    border-color: var(--ring);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--ring) 14%, transparent);
}

.aw-pagination-list .active span {
    background: var(--ring);
    color: #fff;
    border-color: var(--ring);
}

.aw-pagination-list .disabled span {
    opacity: 0.4;
    cursor: not-allowed;
}

</style>
