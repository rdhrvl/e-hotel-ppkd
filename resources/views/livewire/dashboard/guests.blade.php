<div>
    {{-- Search Bar --}}
    <div class="mb-6">
        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Search Guests</label>
        <input type="text" wire:model.live.debounce.300ms="search" class="w-full rounded-[var(--radius)] border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all" placeholder="Search by name, email, phone, or identity document...">
    </div>

    {{-- Guests List Table --}}
    <div class="rounded-[var(--radius)] border border-[var(--border-color)] bg-[var(--bg-card)] shadow-[var(--shadow)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] sticky top-0 z-10 select-none">
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('name')">
                            Name @if($sortField === 'name') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('identity_number')">
                            Identity Number @if($sortField === 'identity_number') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Contact Info</th>
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Address</th>
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-center">Total Bookings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-sm text-[var(--text-secondary)]">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)] font-bold text-[var(--text-primary)]">{{ $guest->name }}</td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)] font-mono text-xs text-[var(--info)]">{{ $guest->identity_number }}</td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                <div class="text-xs font-semibold text-[var(--text-primary)]">{{ $guest->phone ?? '-' }}</div>
                                <div class="text-[11px] text-[var(--text-muted)] mt-0.5">{{ $guest->email ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-[var(--text-muted)] max-w-xs truncate" title="{{ $guest->address }}">{{ $guest->address ?? '-' }}</td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-center">
                                <span class="inline-flex rounded bg-[var(--info-bg)] px-2.5 py-0.5 text-xs font-semibold text-[var(--info)] border border-[var(--border-color)] font-mono">{{ $guest->bookings_count }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[var(--text-muted)] font-medium">
                                No guest profiles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($guests->hasPages())
            <div class="p-4 border-t border-[var(--border-color)] bg-[var(--bg-primary)]/30">
                {{ $guests->links('livewire.dashboard.pagination') }}
            </div>
        @endif
    </div>
</div>
