@if ($paginator->hasPages())
<nav class="d-flex justify-content-center mt-4">
    <ul class="pagination apple-pagination">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link" aria-hidden="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            </li>
        @endif

    </ul>
</nav>

<style>
.apple-pagination .page-item {
    margin: 0 4px;
}

.apple-pagination .page-link {
    border-radius: 12px;
    border: 1px solid #e6e8ef;
    padding: 8px 14px;
    font-weight: 600;
    color: #111;
    background: #fff;
    transition: 0.2s ease;
}

.apple-pagination .page-link:hover {
    background: #f5f5f7;
    color: #000;
    transform: translateY(-1px);
}

.apple-pagination .page-item.active .page-link {
    background: #111;
    color: #fff;
    border-color: #111;
    font-weight: 700;
}

.apple-pagination .page-item.disabled .page-link {
    background: #f1f1f1;
    color: #9ca3af;
    cursor: not-allowed;
    border-color: #e5e7eb;
}
</style>
@endif
