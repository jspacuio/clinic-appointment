@if ($paginator->hasPages())
    <div class="pagination">
        <span>Showing {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }}</span>
        <div>
            @if ($paginator->onFirstPage())
                <button type="button" class="disabled" disabled>&lsaquo;</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span>{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button type="button" class="active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>
            @else
                <button type="button" class="disabled" disabled>&rsaquo;</button>
            @endif
        </div>
    </div>
@else
    <div class="pagination">
        <span>Showing {{ $paginator->total() }} {{ Str::plural('record', $paginator->total()) }}</span>
    </div>
@endif
