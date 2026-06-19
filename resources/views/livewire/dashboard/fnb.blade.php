<div>
    {{-- Page Header --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">Food & Beverage Service</h1>
            <p class="text-xs text-[var(--text-muted)] mt-1">Manage room service orders, restaurant menus, and buffet check-ins.</p>
        </div>
        <div class="rounded bg-[var(--info-bg)] px-4 py-2 border border-[var(--border-color)] text-[var(--info)] text-[10px] font-bold uppercase tracking-wider">
            F&B Control Panel
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Menu items directory --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[var(--border-color)] flex items-center justify-between">
                    <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">F&B Menu Directory</h3>
                    <span class="inline-flex rounded bg-[var(--bg-secondary)] px-2.5 py-0.5 text-xs font-semibold text-[var(--text-muted)] border border-[var(--border-color)] font-mono">{{ $menuItems->count() }} items</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($menuItems as $item)
                            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-4 hover:bg-[var(--bg-card-hover)] transition-all flex justify-between items-start gap-4">
                                <div class="space-y-1">
                                    <h4 class="font-bold text-[var(--text-primary)] text-xs">{{ $item->name }}</h4>
                                    <span class="inline-flex rounded bg-[var(--bg-card)] px-2 py-0.5 text-[9px] font-bold text-[var(--text-muted)] uppercase tracking-wider border border-[var(--border-color)]">
                                        {{ str_replace('_', ' ', $item->type) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono font-bold text-[var(--info)] text-xs block">Rp {{ number_format($item->price) }}</span>
                                    <span class="text-[9px] text-[var(--text-muted)] block mt-0.5">Base price</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-8 text-center text-[var(--text-muted)] font-medium">No Food & Beverage services seeded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Kitchen queue logs --}}
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[var(--border-color)]">
                    <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">Active Room Service Orders</h3>
                </div>
                <div class="p-6 text-center text-[var(--text-muted)] py-12">
                    <svg class="h-10 w-10 mx-auto text-[var(--text-muted)]/60 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <p class="text-xs font-semibold">No active kitchen orders at the moment.</p>
                </div>
            </div>
        </div>

        {{-- Side stats / stubs --}}
        <div class="space-y-6">
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">F&B Quick Stats</h3>
                <div class="space-y-3">
                    <div class="rounded bg-[var(--bg-primary)] p-4 border border-[var(--border-color)] flex justify-between items-center">
                        <span class="text-[10px] text-[var(--text-muted)] font-bold uppercase tracking-wider">Breakfast Target today</span>
                        <span class="text-sm font-bold text-[var(--text-primary)] font-mono">18 Pax</span>
                    </div>
                    <div class="rounded bg-[var(--bg-primary)] p-4 border border-[var(--border-color)] flex justify-between items-center">
                        <span class="text-[10px] text-[var(--text-muted)] font-bold uppercase tracking-wider">Room service requests</span>
                        <span class="text-sm font-bold text-[var(--text-primary)] font-mono">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
