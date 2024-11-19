<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $temporary_transactions->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="#" data-page="{{ $temporary_transactions->currentPage() - 1 }}">Previous</a>
        </li>

        @for ($i = 1; $i <= $temporary_transactions->lastPage(); $i++)
            <li class="page-item {{ $i == $temporary_transactions->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ $temporary_transactions->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="#" data-page="{{ $temporary_transactions->currentPage() + 1 }}">Next</a>
        </li>
    </ul>
</nav>