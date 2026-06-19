<div>
    {{-- Search Bar --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 mb-8 shadow-sm">
        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Search Payments</label>
        <input type="text" wire:model.live.debounce.300ms="search" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="Search by guest name, method, or status...">
    </div>

    {{-- Payments List Table --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                        <th class="p-4">Date & Time</th>
                        <th class="p-4">Guest</th>
                        <th class="p-4">Room</th>
                        <th class="p-4">Method</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                            <td class="p-4 font-mono text-xs text-[var(--text-muted)]">
                                {{ $payment->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-4 font-bold text-[var(--text-primary)]">
                                {{ $payment->booking->guest->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-[var(--text-secondary)]">
                                Room {{ $payment->booking->room->room_number ?? 'N/A' }}
                            </td>
                            <td class="p-4">
                                <span class="inline-flex rounded bg-[var(--bg-secondary)] px-2.5 py-0.5 text-xs font-semibold text-[var(--text-secondary)] border border-[var(--border-color)] capitalize">
                                    {{ str_replace('_', ' ', $payment->method) }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($payment->status === 'paid' || $payment->status === 'confirmed')
                                    <span class="inline-flex rounded bg-[var(--success-bg)] px-2 py-0.5 text-xs font-semibold uppercase tracking-wider text-[var(--success)] border border-[var(--border-color)]">Paid</span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex rounded bg-[var(--warning-bg)] px-2 py-0.5 text-xs font-semibold uppercase tracking-wider text-[var(--warning)] border border-[var(--border-color)]">Pending</span>
                                @else
                                    <span class="inline-flex rounded bg-[var(--danger-bg)] px-2 py-0.5 text-xs font-semibold uppercase tracking-wider text-[var(--danger)] border border-[var(--border-color)]">Failed</span>
                                @endif
                            </td>
                            <td class="p-4 text-right font-bold text-[var(--text-primary)] font-mono">
                                Rp {{ number_format((float)$payment->amount) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[var(--text-muted)] font-medium">
                                No payment records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
