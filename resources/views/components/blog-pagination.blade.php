@if ($posts->hasPages())
    <div class="col-12 wow slideInUp" data-wow-delay="0.1s">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-lg m-0 flex-wrap">
                {{-- Previous --}}
                <li class="page-item {{ $posts->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $posts->previousPageUrl() ?? '#' }}" aria-label="Previous">
                        <span aria-hidden="true"><i class="bi bi-arrow-left"></i></span>
                    </a>
                </li>

                {{-- First Page --}}
                @if ($posts->currentPage() > 3)
                    <li class="page-item"><a class="page-link" href="{{ $posts->url(1) }}">1</a>
                    </li>
                    @if ($posts->currentPage() > 4)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif
                @endif

                {{-- Page Links (centered 5 max) --}}
                @php
                    $start = max($posts->currentPage() - 2, 1);
                    $end = min($posts->currentPage() + 2, $posts->lastPage());
                @endphp
                @for ($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $posts->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $posts->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Last Page --}}
                @if ($posts->currentPage() < $posts->lastPage() - 2)
                    @if ($posts->currentPage() < $posts->lastPage() - 3)
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    @endif
                    <li class="page-item"><a class="page-link"
                            href="{{ $posts->url($posts->lastPage()) }}">{{ $posts->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next --}}
                <li class="page-item {{ !$posts->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $posts->nextPageUrl() ?? '#' }}" aria-label="Next">
                        <span aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif
