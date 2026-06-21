@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between py-3 select-none">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[var(--text-muted)] bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded cursor-not-allowed select-none">
                    Previous
                </span>
            @else
                <button type="button" wire:click="previousPage('page')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[var(--text-primary)] bg-[var(--bg-card)] border border-[var(--border-color)] rounded hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]">
                    Previous
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('page')" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[var(--text-primary)] bg-[var(--bg-card)] border border-[var(--border-color)] rounded hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]">
                    Next
                </button>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-bold text-[var(--text-muted)] bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded cursor-not-allowed select-none">
                    Next
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-[var(--text-secondary)]">
                    Showing
                    <span class="font-bold text-[var(--text-primary)]">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="font-bold text-[var(--text-primary)]">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="font-bold text-[var(--text-primary)] font-mono">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded border border-[var(--border-color)] overflow-hidden">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Previous">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-r border-[var(--border-color)] cursor-not-allowed select-none" aria-hidden="true">
                                &laquo; Prev
                            </span>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('page')" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--text-primary)] bg-[var(--bg-card)] border-r border-[var(--border-color)] hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]" aria-label="Previous">
                            &laquo; Prev
                        </button>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-r border-[var(--border-color)] select-none">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--bg-card)] bg-[var(--text-primary)] border-r border-[var(--border-color)] select-none">{{ $page }}</span>
                                    </span>
                                @else
                                    <button type="button" wire:click="gotoPage({{ $page }}, 'page')" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--text-primary)] bg-[var(--bg-card)] border-r border-[var(--border-color)] hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]" aria-label="Go to page {{ $page }}">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('page')" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--text-primary)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]" aria-label="Next">
                            Next &raquo;
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="Next">
                            <span class="relative inline-flex items-center px-3 py-2 text-xs font-bold text-[var(--text-muted)] bg-[var(--bg-secondary)] cursor-not-allowed select-none" aria-hidden="true">
                                Next &raquo;
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
