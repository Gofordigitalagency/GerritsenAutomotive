@if ($paginator->hasPages())
    <nav class="adm-pagination" role="navigation" aria-label="Paginering">
        {{-- Vorige --}}
        @if ($paginator->onFirstPage())
            <span class="adm-pagination-btn is-disabled" aria-disabled="true">&lsaquo;</span>
        @else
            <a class="adm-pagination-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Vorige">&lsaquo;</a>
        @endif

        {{-- Paginanummers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="adm-pagination-dots">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="adm-pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="adm-pagination-btn" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Volgende --}}
        @if ($paginator->hasMorePages())
            <a class="adm-pagination-btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Volgende">&rsaquo;</a>
        @else
            <span class="adm-pagination-btn is-disabled" aria-disabled="true">&rsaquo;</span>
        @endif
    </nav>
@endif
