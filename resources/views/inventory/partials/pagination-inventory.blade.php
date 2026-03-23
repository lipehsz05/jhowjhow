@if ($produtos->hasPages())
    <div class="pagination-wrapper mt-4 d-flex justify-content-center">
        <ul class="pagination mb-0">
            <li class="page-item {{ $produtos->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link js-inventory-page" href="#" data-page="{{ $produtos->onFirstPage() ? 1 : $produtos->currentPage() - 1 }}" aria-label="Anterior">
                    &laquo;
                </a>
            </li>
            @for ($i = 1; $i <= $produtos->lastPage(); $i++)
                <li class="page-item {{ $produtos->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link js-inventory-page" href="#" data-page="{{ $i }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $produtos->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link js-inventory-page" href="#" data-page="{{ $produtos->hasMorePages() ? $produtos->currentPage() + 1 : $produtos->currentPage() }}" aria-label="Próximo">
                    &raquo;
                </a>
            </li>
        </ul>
    </div>
@endif
