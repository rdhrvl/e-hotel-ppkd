<div>
    {{-- ── Search & Filter Controls ── --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 mb-8 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-1 flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[280px]">
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Search Guest</label>
                <input type="text" wire:model.live="search" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="Search by name or ID document...">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Status</label>
                <select wire:model.live="filterStatus" class="block rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none min-w-[160px] cursor-pointer transition-all">
                    <option value="">All Reservations</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="checked_in">Checked In</option>
                    <option value="checked_out">Checked Out</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ── Bookings List Table ── --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                        <th class="p-4">Code</th>
                        <th class="p-4">Guest</th>
                        <th class="p-4">ID Document</th>
                        <th class="p-4">Room</th>
                        <th class="p-4">Stay Dates</th>
                        <th class="p-4 text-center">Nights</th>
                        <th class="p-4">Total Bill</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                            <td class="p-4 font-mono text-xs font-bold text-[var(--info)]">{{ $booking->booking_code }}</td>
                            <td class="p-4">
                                <div class="font-bold text-[var(--text-primary)]">{{ $booking->guest->name }}</div>
                                <div class="text-[10px] text-[var(--text-muted)] mt-0.5 font-medium">Guests: {{ $booking->number_of_guests }}</div>
                            </td>
                            <td class="p-4">
                                <span class="font-mono text-[var(--text-muted)]">{{ $booking->guest->identity_number }}</span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-[var(--text-primary)]">Room {{ $booking->room->room_number }}</div>
                                <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $booking->room->roomType->name }}</div>
                            </td>
                            <td class="p-4 text-[11px] leading-relaxed">
                                <div>In: <span class="font-semibold text-[var(--text-primary)]">{{ $booking->check_in_date->format('d M Y') }}</span></div>
                                <div class="mt-0.5">Out: <span class="font-semibold text-[var(--text-primary)]">{{ $booking->check_out_date->format('d M Y') }}</span></div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex rounded bg-[var(--bg-secondary)] px-2.5 py-0.5 text-[10px] font-semibold text-[var(--text-secondary)] border border-[var(--border-color)] font-mono">{{ $booking->nights }} nights</span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format($booking->guestBill->total_amount) }}</div>
                                <div class="text-[10px] font-semibold mt-0.5 {{ $booking->guestBill->status === 'paid' ? 'text-[var(--success)]' : 'text-[var(--danger)]' }}">
                                    {{ ucfirst($booking->guestBill->status) }}
                                </div>
                            </td>
                            <td class="p-4">
                                @if($booking->status === 'pending')
                                    <span class="inline-flex rounded bg-[var(--bg-secondary)] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[var(--text-secondary)] border border-[var(--border-color)]">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="inline-flex rounded bg-[var(--info-bg)] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[var(--info)] border border-[var(--border-color)]">Confirmed</span>
                                @elseif($booking->status === 'checked_in')
                                    <span class="inline-flex rounded bg-[var(--success-bg)] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[var(--success)] border border-[var(--border-color)]">Checked In</span>
                                @elseif($booking->status === 'checked_out')
                                    <span class="inline-flex rounded bg-[var(--bg-secondary)]/60 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[var(--text-muted)] border border-[var(--border-color)]">Checked Out</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex rounded bg-[var(--danger-bg)] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[var(--danger)] border border-[var(--border-color)]">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($booking->status === 'confirmed')
                                        <button wire:click="openCheckInModal({{ $booking->id }})" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--bg-card)] transition-colors cursor-pointer active:scale-[0.98]">
                                            Check In
                                        </button>
                                        <button onclick="confirm('Are you sure you want to cancel this reservation?') || event.stopImmediatePropagation()" wire:click="cancelBooking({{ $booking->id }})" class="rounded border border-[var(--border-color)] hover:bg-[var(--danger-bg)] hover:text-[var(--danger)] px-3 py-1.5 text-xs font-semibold text-[var(--text-secondary)] transition-colors cursor-pointer">
                                            Cancel
                                        </button>
                                    @elseif($booking->status === 'checked_in')
                                        <button wire:click="openCheckOutModal({{ $booking->id }})" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--bg-card)] transition-colors cursor-pointer active:scale-[0.98]">
                                            Check Out
                                        </button>
                                    @endif

                                    @if($booking->status !== 'cancelled')
                                        <a href="{{ route('bookings.invoice', $booking->id) }}" target="_blank" class="rounded border border-[var(--border-color)] hover:bg-[var(--bg-secondary)] p-1.5 text-[var(--text-secondary)] transition-colors" title="Print Invoice">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.821l.5.5a2.25 2.25 0 003.18 0l1.838-1.838a2.25 2.25 0 000-3.18l-.5-.5m-2.94 2.94l-.707.707a3 3 0 11-4.243-4.243l1.828-1.829A3 3 0 018.586 5.5L8 6.086m1.88 2.286l.707-.707a3 3 0 00-1.828-5.184 3 3 0 00-1.828.829l-1.83 1.83" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-[var(--text-muted)] font-medium">
                                No reservations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Check In Modal ── --}}
    @if($showCheckInModal && $checkInBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
            <div class="fixed inset-0" wire:click="closeCheckInModal"></div>
            <div class="relative w-full max-w-md rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Guest Check In</h3>
                    <button wire:click="closeCheckInModal" class="text-[var(--text-muted)] hover:text-[var(--text-primary)]">&times;</button>
                </div>
                <form wire:submit.prevent="checkInGuest" class="space-y-4">
                    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-4 text-xs text-[var(--text-secondary)] space-y-2.5">
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Guest:</strong> <span class="font-bold text-[var(--text-primary)]">{{ $checkInBooking->guest->name }}</span></p>
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Identity Document:</strong> <span class="font-mono text-[var(--text-primary)]">{{ $checkInBooking->guest->identity_number }}</span></p>
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Stay:</strong> <span class="text-[var(--text-primary)] font-semibold">{{ $checkInBooking->check_in_date->format('d M Y') }} to {{ $checkInBooking->check_out_date->format('d M Y') }} ({{ $checkInBooking->nights }} Nights)</span></p>
                        <p class="flex justify-between border-t border-[var(--border-color)] pt-2"><strong class="text-[var(--text-muted)]">Room Charge:</strong> <span class="font-bold text-[var(--success)] font-mono">Rp {{ number_format($checkInBooking->room->effective_price * $checkInBooking->nights) }}</span></p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Security Deposit (Rp)</label>
                        <input type="number" wire:model="depositAmount" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" required>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeCheckInModal" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors">Confirm Check-In</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Check Out Modal ── --}}
    @if($showCheckOutModal && $checkOutBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
            <div class="fixed inset-0" wire:click="closeCheckOutModal"></div>
            <div class="relative w-full max-w-lg rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Guest Checkout & Settlement</h3>
                    <button wire:click="closeCheckOutModal" class="text-[var(--text-muted)] hover:text-[var(--text-primary)]">&times;</button>
                </div>
                <form wire:submit.prevent="checkOutGuest" class="space-y-4">
                    <div class="text-xs text-[var(--text-secondary)] space-y-1.5 bg-[var(--bg-primary)] rounded p-3 border border-[var(--border-color)]">
                        <p class="flex justify-between"><span>Guest Name:</span> <strong class="text-[var(--text-primary)]">{{ $checkOutBooking->guest->name }}</strong></p>
                        <p class="flex justify-between"><span>Stay Details:</span> <span class="text-[var(--text-secondary)] font-semibold">{{ $checkOutBooking->check_in_date->format('d M Y') }} - {{ $checkOutBooking->check_out_date->format('d M Y') }} ({{ $checkOutBooking->nights }} Nights)</span></p>
                    </div>

                    <h4 class="text-[10px] font-bold text-[var(--text-muted)] uppercase tracking-wider border-b border-[var(--border-color)] pb-1.5">Stay Invoice Statement</h4>

                    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-4 text-xs space-y-3">
                        <div class="flex justify-between">
                            <span class="text-[var(--text-muted)]">Room Charges:</span>
                            <span class="font-semibold text-[var(--text-primary)] font-mono">Rp {{ number_format((float)$checkOutBooking->guestBill->total_room_charges) }}</span>
                        </div>

                        @if($checkOutBooking->bookingItems->count() > 0)
                            <div class="border-t border-dashed border-[var(--border-color)] pt-2">
                                <span class="text-[9px] text-[var(--text-muted)] font-bold uppercase tracking-wider block mb-1">Extra Charges</span>
                                @foreach($checkOutBooking->bookingItems as $item)
                                    <div class="flex justify-between text-[var(--text-secondary)]">
                                        <span>{{ $item->service->name }} (x{{ $item->quantity }}):</span>
                                        <span class="font-mono">Rp {{ number_format($item->subtotal) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex justify-between border-t border-[var(--border-color)] pt-2 font-bold">
                            <span class="text-[var(--text-muted)]">Grand Total:</span>
                            <span class="text-[var(--text-primary)] font-mono">Rp {{ number_format($checkOutBooking->guestBill->total_amount) }}</span>
                        </div>

                        <div class="flex justify-between text-[var(--success)] font-semibold">
                            <span>Paid Deposit:</span>
                            <span class="font-mono">- Rp {{ number_format((float)$checkOutBooking->guestBill->deposit_amount) }}</span>
                        </div>

                        @php
                            $bill = $checkOutBooking->guestBill;
                            $grandTotal = $bill->total_room_charges + $bill->total_extra_charges;
                            $netAmountDue = $grandTotal - $bill->deposit_amount - $bill->paid_amount;
                        @endphp

                        <div class="flex justify-between border-t border-[var(--border-color)] pt-2.5 text-xs font-bold text-[var(--text-primary)]">
                            <span>Net Balance Due:</span>
                            <span class="text-[var(--text-primary)] font-mono">Rp {{ number_format(max(0, $netAmountDue)) }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Payment Method</label>
                        <select wire:model="paymentMethod" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer" required>
                            <option value="cash">Cash Payment</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 items-center">
                        <a href="{{ route('bookings.invoice', $checkOutBooking->id) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] px-3.5 py-2 text-xs font-semibold text-[var(--text-secondary)] mr-auto transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.821l.5.5a2.25 2.25 0 003.18 0l1.838-1.838a2.25 2.25 0 000-3.18l-.5-.5m-2.94 2.94l-.707.707a3 3 0 11-4.243-4.243l1.828-1.829A3 3 0 018.586 5.5L8 6.086m1.88 2.286l.707-.707a3 3 0 00-1.828-5.184 3 3 0 00-1.828.829l-1.83 1.83" />
                            </svg>
                            Print Invoice
                        </a>
                        <button type="button" wire:click="closeCheckOutModal" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors">Confirm & Settle</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
