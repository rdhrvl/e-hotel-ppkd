<div>
    {{-- Search Bar --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 mb-8 shadow-sm">
        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Search Guests</label>
        <input type="text" wire:model.live.debounce.300ms="search" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="Search by name, email, phone, or identity document...">
    </div>

    {{-- Guests List Table --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                        <th class="p-4">Name</th>
                        <th class="p-4">Identity Number</th>
                        <th class="p-4">Contact Info</th>
                        <th class="p-4">Address</th>
                        <th class="p-4 text-center">Total Bookings</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                    @forelse($guests as $guest)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                            <td class="p-4 font-bold text-[var(--text-primary)]">{{ $guest->name }}</td>
                            <td class="p-4 font-mono text-xs text-[var(--info)]">{{ $guest->identity_number }}</td>
                            <td class="p-4">
                                <div class="text-xs font-semibold text-[var(--text-primary)]">{{ $guest->phone ?? '-' }}</div>
                                <div class="text-[11px] text-[var(--text-muted)] mt-0.5">{{ $guest->email ?? '-' }}</div>
                            </td>
                            <td class="p-4 text-xs text-[var(--text-muted)] max-w-xs truncate" title="{{ $guest->address }}">{{ $guest->address ?? '-' }}</td>
                            <td class="p-4 text-center">
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
    </div>
</div>
