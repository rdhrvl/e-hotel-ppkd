<div>
    {{-- ── Search Controls ── --}}
    <div class="mb-6">
        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Search Guest Bill</label>
        <input type="text" wire:model.live="search" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all" placeholder="Search by guest name or ID document...">
    </div>

    @if($selectedBookingId)
        @php
            $activeBooking = \App\Models\Booking::find($selectedBookingId);
        @endphp
        {{-- ── Charge Extra Service Panel ── --}}
        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-6 mb-8 shadow-sm">
            <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-base font-semibold text-[var(--text-primary)]">Charge Extra Service to {{ $activeBooking->guest->name }} (Room {{ $activeBooking->room->room_number }})</h3>
                <button wire:click="$set('selectedBookingId', null)" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--text-secondary)] transition-colors">Cancel</button>
            </div>

            <form wire:submit.prevent="addServiceCharge" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Select Service</label>
                    <select wire:model="selectedServiceId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} (Rp {{ number_format($service->price) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Quantity</label>
                    <input type="number" wire:model="serviceQuantity" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all" min="1" required>
                </div>

                <div>
                    <button type="submit" class="w-full rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white px-4 py-2.5 text-xs font-semibold transition-all duration-150 active:scale-[0.98]">Add Charge</button>
                </div>
            </form>
        </div>
    @endif

    {{-- ── Bills List ── --}}
    <div class="space-y-6">
        @forelse($bookings as $booking)
            @php
                $bill = $booking->guestBill;
                $roomCost = $bill->total_room_charges;
                $extraCost = $bill->total_extra_charges;
                $deposit = $bill->deposit_amount;
                $paid = $bill->paid_amount;
                $grandTotal = $roomCost + $extraCost;
                $balance = $grandTotal - $deposit - $paid;
            @endphp
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-6 shadow-sm space-y-6 transition-all duration-250">
                <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-[var(--border-color)] pb-4 gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-sm font-bold text-[var(--text-primary)]">{{ $booking->guest->name }}</h3>
                            <span class="inline-flex rounded bg-[var(--bg-secondary)] px-2.5 py-0.5 text-[10px] font-semibold text-[var(--text-secondary)]">Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</span>
                            @if($booking->status === 'checked_in')
                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--warning-bg)] text-[var(--warning)]">In-House</span>
                            @else
                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--success-bg)] text-[var(--success)]">Checked Out</span>
                            @endif
                        </div>
                        <p class="text-xs text-[var(--text-muted)] mt-1.5 font-medium">
                            Stay: {{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }} ({{ $booking->nights }} nights) &bull; ID: <span class="font-mono text-[var(--text-primary)]">{{ $booking->guest->identity_number }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($booking->status === 'checked_in')
                            <button wire:click="selectBookingForService({{ $booking->id }})" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] transition-all">
                                + Charge Service
                            </button>
                        @endif
                        <a href="{{ route('bookings.invoice', $booking->id) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white px-4 py-2 text-xs font-semibold transition-all duration-150 active:scale-[0.98]">
                            Print Invoice
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <span class="text-sm font-medium text-[var(--text-secondary)] mb-3 block">Itemized Charges</span>
                        <div class="overflow-x-auto rounded border border-[var(--border-color)]">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)]">
                                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Description</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-center">Qty/Nights</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Rate</th>
                                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                                    <tr class="hover:bg-[var(--bg-card-hover)]">
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">Room Charge ({{ $booking->room->roomType->name }})</td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-center font-mono text-[var(--text-primary)]">{{ $booking->nights }}</td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] font-mono">Rp {{ number_format($booking->room->effective_price) }}</td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-right font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format($roomCost) }}</td>
                                    </tr>
                                    @foreach($booking->bookingItems as $item)
                                        <tr class="hover:bg-[var(--bg-card-hover)]">
                                            <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                                <div class="font-bold text-[var(--text-primary)]">{{ $item->service->name }}</div>
                                                @if($item->notes)
                                                    <span class="text-[9px] text-[var(--text-muted)] italic block mt-0.5">{{ $item->notes }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-center font-mono text-[var(--text-primary)]">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] font-mono">Rp {{ number_format((float)$item->price) }}</td>
                                            <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-right font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format($item->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-5 text-xs space-y-4 self-start">
                        <span class="text-sm font-medium text-[var(--text-secondary)] pb-2 block border-b border-[var(--border-color)]">Bill Summary</span>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex justify-between">
                                <span class="text-[var(--text-muted)]">Room Cost:</span>
                                <span class="font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format($roomCost) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-[var(--text-muted)]">Extra Charges:</span>
                                <span class="font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format($extraCost) }}</span>
                            </div>

                            <div class="flex justify-between border-t border-[var(--border-color)] pt-2 font-bold text-[var(--text-primary)] text-xs">
                                <span>Grand Total:</span>
                                <span class="font-mono">Rp {{ number_format($grandTotal) }}</span>
                            </div>

                            @php
                                $upfrontPayment = $booking->payments->first(function($p) use ($booking) {
                                    return $p->created_at <= $booking->created_at->addMinutes(5);
                                });
                                $upfrontAmount = $upfrontPayment ? (float)$upfrontPayment->amount : 0.0;
                                $otherPrepaid = (float)$deposit - $upfrontAmount;
                            @endphp

                            @if($upfrontAmount > 0)
                                <div class="flex justify-between text-[var(--success)] font-bold">
                                    <span>Upfront Payment:</span>
                                    <span class="font-mono">- Rp {{ number_format($upfrontAmount) }}</span>
                                </div>
                            @endif

                            @if($otherPrepaid > 0)
                                <div class="flex justify-between text-[var(--success)] font-bold">
                                    <span>Paid Deposit:</span>
                                    <span class="font-mono">- Rp {{ number_format($otherPrepaid) }}</span>
                                </div>
                            @endif

                            @if($paid > 0)
                                <div class="flex justify-between text-[var(--success)] font-bold">
                                    <span>Payments Settled:</span>
                                    <span class="font-mono">- Rp {{ number_format($paid) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between border-t border-[var(--border-color)] pt-2 mt-2 text-xs font-bold text-[var(--text-primary)]">
                                <span>Balance Due:</span>
                                <span class="text-[var(--danger)] font-mono">Rp {{ number_format(max(0, $balance)) }}</span>
                            </div>
                        </div>

                        <div class="border-t border-[var(--border-color)] pt-4 text-center">
                            <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] {{ $bill->status === 'paid' ? 'bg-[var(--success-bg)] text-[var(--success)]' : 'bg-[var(--danger-bg)] text-[var(--danger)]' }}">
                                {{ ucfirst($bill->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-12 text-center shadow-sm">
                <p class="text-[var(--text-muted)] font-medium">No active or completed guest bills found.</p>
            </div>
        @endforelse
    </div>
</div>
