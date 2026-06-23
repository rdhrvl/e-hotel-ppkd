<div>
    {{-- ── Search & Filter Controls ── --}}
    <div class="flex flex-wrap items-center gap-4 mb-6">
        <div class="flex-1 min-w-[280px]">
            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Search Guest</label>
            <input type="text" wire:model.live="search"
                class="w-full rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                placeholder="Search by name or ID document...">
        </div>
        <div>
            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Status</label>
            <select wire:model.live="filterStatus"
                class="block rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none min-w-[160px] cursor-pointer transition-all">
                <option value="">All Reservations</option>
                <option value="confirmed">Confirmed</option>
                <option value="checked_in">Checked In</option>
                <option value="checked_out">Checked Out</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    {{-- ── Bookings List Table ── --}}
    <div
        class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] overflow-hidden transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] sticky top-0 z-10 select-none">
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors"
                            wire:click="sortBy('booking_code')">
                            Code @if ($sortField === 'booking_code')
                                {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                            @endif
                        </th>
                        <th
                            class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">
                            Guest</th>
                        <th
                            class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">
                            ID Document</th>
                        <th
                            class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">
                            Room</th>
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors"
                            wire:click="sortBy('check_in_date')">
                            Stay Dates @if ($sortField === 'check_in_date')
                                {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                            @endif
                        </th>
                        <th
                            class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-center">
                            Nights</th>
                        <th
                            class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">
                            Total Bill</th>
                        <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors"
                            wire:click="sortBy('status')">
                            Status @if ($sortField === 'status')
                                {{ $sortDirection === 'asc' ? '▲' : '▼' }}
                            @endif
                        </th>
                        <th
                            class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                            <td
                                class="px-4 py-3.5 border-b border-[var(--border-color)] font-mono text-xs font-bold text-[var(--accent-primary)]">
                                {{ $booking->booking_code }}</td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                <div class="font-bold text-[var(--text-primary)]">{{ $booking->guest->name }}</div>
                                <div class="text-[10px] text-[var(--text-muted)] mt-0.5 font-medium">Guests:
                                    {{ $booking->number_of_guests }}</div>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                <span
                                    class="font-mono text-[var(--text-muted)]">{{ $booking->guest->identity_number }}</span>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                <div class="font-bold text-[var(--text-primary)]">Room {{ $booking->room->room_number }}
                                </div>
                                <div class="text-[10px] text-[var(--text-muted)] mt-0.5">
                                    {{ $booking->room->roomType->name }}</div>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-[11px] leading-relaxed">
                                <div>In: <span
                                        class="font-semibold text-[var(--text-primary)]">{{ $booking->check_in_date->format('d M Y') }}</span>
                                </div>
                                <div class="mt-0.5">Out: <span
                                        class="font-semibold text-[var(--text-primary)]">{{ $booking->check_out_date->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-center">
                                <span
                                    class="inline-flex rounded-md bg-[var(--bg-secondary)] px-2.5 py-0.5 text-[10px] font-semibold text-[var(--text-secondary)] border border-[var(--border-color)] font-mono">{{ $booking->nights }}
                                    nights</span>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                <div class="font-bold text-[var(--text-primary)] font-mono">Rp
                                    {{ number_format($booking->guestBill->total_amount) }}</div>
                                <div
                                    class="text-[10px] font-semibold mt-0.5 {{ $booking->guestBill->status === 'paid' ? 'text-[var(--success)]' : 'text-[var(--danger)]' }}">
                                    {{ ucfirst($booking->guestBill->status) }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                @if ($booking->status === 'pending')
                                    <span
                                        class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--bg-secondary)] text-[var(--text-secondary)]">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span
                                        class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--warning-bg)] text-[var(--warning)]">Confirmed</span>
                                @elseif($booking->status === 'checked_in')
                                    <span
                                        class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--success-bg)] text-[var(--success)]">Checked
                                        In</span>
                                @elseif($booking->status === 'checked_out')
                                    <span
                                        class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--bg-secondary)] text-[var(--text-muted)]">Checked
                                        Out</span>
                                @elseif($booking->status === 'cancelled')
                                    <span
                                        class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--danger-bg)] text-[var(--danger)]">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($booking->status === 'confirmed')
                                        @php
                                            $preCheckInTask = \App\Models\HousekeepingTask::where(
                                                'room_id',
                                                $booking->room_id,
                                            )
                                                ->where('created_at', '>=', $booking->created_at)
                                                ->latest()
                                                ->first();
                                        @endphp
                                        @if (!$preCheckInTask)
                                            <button wire:click="requestPreCheckInInspection({{ $booking->id }})"
                                                class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-3 py-1.5 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]">
                                                Request Prep Inspection
                                            </button>
                                        @elseif(in_array($preCheckInTask->status, ['scheduled', 'in_progress']))
                                            <span
                                                class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-1.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--warning-bg)] text-[var(--warning)]">
                                                Prep Inspecting...
                                            </span>
                                        @elseif($preCheckInTask->status === 'completed')
                                            @if ($booking->room->status === 'ready')
                                                <button wire:click="openCheckInModal({{ $booking->id }})"
                                                    class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-3 py-1.5 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]">
                                                    Check In
                                                </button>
                                            @elseif($booking->room->status === 'maintenance')
                                                <span
                                                    class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-1.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--danger-bg)] text-[var(--danger)]">
                                                    Needs Maintenance
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-1.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--bg-secondary)] text-[var(--text-muted)]">
                                                    Waiting for Ready Status
                                                </span>
                                            @endif
                                        @endif
                                        <button
                                            onclick="confirm('Are you sure you want to cancel this reservation?') || event.stopImmediatePropagation()"
                                            wire:click="cancelBooking({{ $booking->id }})"
                                            class="rounded-md border border-[var(--border-color)] hover:bg-[var(--danger-bg)] hover:text-[var(--danger)] hover:border-[var(--danger)]/30 px-3 py-1.5 text-xs font-semibold text-[var(--text-secondary)] transition-all cursor-pointer">
                                            Cancel
                                        </button>
                                    @elseif($booking->status === 'checked_in')
                                        <button wire:click="openOrderFoodModal({{ $booking->id }})"
                                            class="rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] hover:bg-[var(--bg-secondary)]/80 text-[var(--text-primary)] px-3 py-1.5 text-xs font-semibold transition-all cursor-pointer active:scale-[0.98]">
                                            Order Food
                                        </button>
                                        <button wire:click="openCheckOutModal({{ $booking->id }})"
                                            class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-3 py-1.5 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]">
                                            Check Out
                                        </button>
                                    @elseif($booking->status === 'checked_out' && $booking->room->status === 'cleaning')
                                        <button wire:click="openReviewModal({{ $booking->id }})"
                                            class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-3 py-1.5 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]">
                                            Review HK Report & Release
                                        </button>
                                    @endif

                                    @if ($booking->status !== 'cancelled')
                                        {{-- Print Confirmation / Invoice --}}
                                        <a href="{{ route('bookings.invoice', $booking->id) }}" target="_blank"
                                            class="rounded-md border border-[var(--border-color)] hover:bg-[var(--bg-secondary)] p-1.5 text-[var(--text-secondary)] transition-all"
                                            title="Print Confirmation / Invoice">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </a>
                                        {{-- Print Registration Card --}}
                                        <a href="{{ route('bookings.registration-form', $booking->id) }}"
                                            target="_blank"
                                            class="rounded-md border border-[var(--border-color)] hover:bg-[var(--bg-secondary)] p-1.5 text-[var(--text-secondary)] transition-all"
                                            title="Print Registration Card">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
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
        @if ($bookings->hasPages())
            <div class="p-4 border-t border-[var(--border-color)] bg-[var(--bg-primary)]/30">
                {{ $bookings->links('livewire.dashboard.pagination') }}
            </div>
        @endif
    </div>

    {{-- ── Check In Modal ── --}}
    @if ($showCheckInModal && $checkInBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeCheckInModal"></div>
            <div
                class="relative w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)] space-y-5">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <h3 class="text-base font-bold text-[var(--text-primary)]">Guest Check In</h3>
                    <button wire:click="closeCheckInModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl">&times;</button>
                </div>
                <form wire:submit.prevent="checkInGuest" class="space-y-5">
                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] p-4 text-xs text-[var(--text-secondary)] space-y-3">
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Guest:</strong> <span
                                class="font-bold text-[var(--text-primary)]">{{ $checkInBooking->guest->name }}</span>
                        </p>
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Identity
                                Document:</strong> <span
                                class="font-mono text-[var(--text-primary)]">{{ $checkInBooking->guest->identity_number }}</span>
                        </p>
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Stay:</strong> <span
                                class="text-[var(--text-primary)] font-semibold">{{ $checkInBooking->check_in_date->format('d M Y') }}
                                to {{ $checkInBooking->check_out_date->format('d M Y') }}
                                ({{ $checkInBooking->nights }} Nights)</span></p>
                        <p class="flex justify-between border-t border-[var(--border-color)] pt-3"><strong
                                class="text-[var(--text-muted)]">Room Charge:</strong> <span
                                class="font-bold text-[var(--success)] font-mono">Rp
                                {{ number_format($checkInBooking->room->effective_price * $checkInBooking->nights) }}</span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Security Deposit
                            (Rp)</label>
                        <input type="number" wire:model="depositAmount"
                            class="w-full rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                            required>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 items-center">
                        <a href="{{ route('bookings.registration-form', $checkInBooking->id) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] px-3.5 py-2 text-xs font-semibold text-[var(--text-secondary)] mr-auto transition-all">
                            Print Registration Form
                        </a>
                        <button type="button" wire:click="closeCheckInModal"
                            class="rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-all">Cancel</button>
                        <button type="submit"
                            class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-all">Confirm
                            Check-In</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Check Out Modal ── --}}
    @if ($showCheckOutModal && $checkOutBooking)
        @php
            $isTaskIncomplete = !$latestTask || in_array($latestTask->status, ['scheduled', 'in_progress']);
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeCheckOutModal"></div>
            <div
                class="relative w-full max-w-lg rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)] space-y-5">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <h3 class="text-base font-bold text-[var(--text-primary)]">Guest Checkout & Settlement</h3>
                    <button wire:click="closeCheckOutModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl">&times;</button>
                </div>
                <form wire:submit.prevent="checkOutGuest" class="space-y-5">
                    <div
                        class="text-xs text-[var(--text-secondary)] space-y-2 bg-[var(--bg-secondary)] rounded-md p-3.5 border border-[var(--border-color)]">
                        <p class="flex justify-between"><span>Guest Name:</span> <strong
                                class="text-[var(--text-primary)]">{{ $checkOutBooking->guest->name }}</strong></p>
                        <p class="flex justify-between"><span>Stay Details:</span> <span
                                class="text-[var(--text-secondary)] font-semibold">{{ $checkOutBooking->check_in_date->format('d M Y') }}
                                - {{ $checkOutBooking->check_out_date->format('d M Y') }}
                                ({{ $checkOutBooking->nights }} Nights)</span></p>
                    </div>

                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] p-4 text-xs space-y-3">
                        <div class="flex justify-between">
                            <span class="text-[var(--text-muted)]">Room Charges:</span>
                            <span class="font-semibold text-[var(--text-primary)] font-mono">Rp
                                {{ number_format((float) $checkOutBooking->guestBill->total_room_charges) }}</span>
                        </div>

                        @if ($checkOutBooking->bookingItems->count() > 0)
                            <div class="border-t border-dashed border-[var(--border-color)] pt-2 space-y-1">
                                <span class="text-sm font-medium text-[var(--text-secondary)] block">Extra
                                    Charges</span>
                                @foreach ($checkOutBooking->bookingItems as $item)
                                    <div class="flex justify-between text-[var(--text-secondary)]">
                                        <span class="truncate max-w-[240px]">{{ $item->service->name }}
                                            ({{ $item->notes ?: 'x' . $item->quantity }})
                                            :</span>
                                        <span class="font-mono">Rp
                                            {{ number_format($item->price * $item->quantity) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex justify-between border-t border-[var(--border-color)] pt-2 font-bold">
                            <span class="text-[var(--text-muted)]">Grand Total:</span>
                            <span class="text-[var(--text-primary)] font-mono">Rp
                                {{ number_format($checkOutBooking->guestBill->total_room_charges + $checkOutBooking->guestBill->total_extra_charges) }}</span>
                        </div>

                        @php
                            $upfrontPayment = $checkOutBooking->payments->first(function ($p) use ($checkOutBooking) {
                                return $p->created_at <= $checkOutBooking->created_at->addMinutes(5);
                            });
                            $upfrontAmount = $upfrontPayment ? (float) $upfrontPayment->amount : 0.0;
                            $otherPrepaid = (float) $checkOutBooking->guestBill->deposit_amount - $upfrontAmount;
                        @endphp

                        @if ($upfrontAmount > 0)
                            <div class="flex justify-between text-[var(--success)] font-semibold">
                                <span>Upfront Payment:</span>
                                <span class="font-mono">- Rp {{ number_format($upfrontAmount) }}</span>
                            </div>
                        @endif

                        @if ($otherPrepaid > 0)
                            <div class="flex justify-between text-[var(--success)] font-semibold">
                                <span>Check-In Deposit:</span>
                                <span class="font-mono">- Rp {{ number_format($otherPrepaid) }}</span>
                            </div>
                        @endif

                        @php
                            $bill = $checkOutBooking->guestBill;
                            $grandTotal = $bill->total_room_charges + $bill->total_extra_charges;
                            $netAmountDue = $grandTotal - $bill->deposit_amount - $bill->paid_amount;
                        @endphp

                        <div
                            class="flex justify-between border-t border-[var(--border-color)] pt-2.5 text-xs font-bold text-[var(--text-primary)] bg-[var(--bg-secondary)]">
                            <span>Net Balance Due:</span>
                            <span class="text-[var(--text-primary)] font-mono text-sm">Rp
                                {{ number_format(max(0, $netAmountDue)) }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1.5">Payment
                            Method</label>
                        <select wire:model="paymentMethod"
                            class="w-full rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all cursor-pointer"
                            required>
                            <option value="cash">Cash Payment</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 items-center">
                        <a href="{{ route('bookings.invoice', $checkOutBooking->id) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] px-3.5 py-2 text-xs font-semibold text-[var(--text-secondary)] mr-auto transition-all">
                            Print Invoice
                        </a>
                        <button type="button" wire:click="closeCheckOutModal"
                            class="rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-all">Cancel</button>
                        <button type="submit"
                            class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-all">Confirm
                            & Settle</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Review HK Report Modal ── --}}
    @if ($showReviewModal && $reviewBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeReviewModal"></div>
            <div
                class="relative w-full max-w-lg rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)] space-y-5">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <h3 class="text-base font-bold text-[var(--text-primary)]">Review Housekeeping Report & Release
                        Room</h3>
                    <button wire:click="closeReviewModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl">&times;</button>
                </div>
                <div class="space-y-4">
                    <div
                        class="text-xs text-[var(--text-secondary)] space-y-1.5 bg-[var(--bg-secondary)] rounded-md p-3.5 border border-[var(--border-color)]">
                        <p class="flex justify-between"><span>Room:</span> <strong
                                class="text-[var(--text-primary)]">Room
                                {{ $reviewBooking->room->room_number }}</strong></p>
                        <p class="flex justify-between"><span>Checked Out Guest:</span> <strong
                                class="text-[var(--text-primary)]">{{ $reviewBooking->guest->name }}</strong></p>
                    </div>

                    {{-- Housekeeping Report --}}
                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)]/40 p-4 text-xs space-y-3">
                        <span class="text-sm font-medium text-[var(--text-secondary)] block">Housekeeping Cleaning
                            Report</span>
                        @if ($reviewTask)
                            <p class="text-[var(--text-primary)] font-semibold">Notes:
                                {{ $reviewTask->room->notes ?: 'No special notes entered.' }}</p>
                            <p class="text-[10px] text-[var(--text-muted)]">Inspected by:
                                {{ $reviewTask->staff->name }} on {{ $reviewTask->updated_at->format('d M Y H:i') }}
                            </p>
                        @else
                            <p class="text-xs text-[var(--text-muted)] italic">No cleaning task logged.</p>
                        @endif
                    </div>

                    {{-- Reported Issues & Extra Charges --}}
                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)]/40 p-4 text-xs space-y-3">
                        <span class="text-sm font-medium text-[var(--text-secondary)] block">Fines & Missing Items
                            Reported by HK</span>
                        <div class="space-y-2 max-h-[120px] overflow-y-auto">
                            @forelse($reviewIssues as $issue)
                                <div class="flex items-start gap-1.5 text-[var(--danger)] font-semibold text-[11px]">
                                    <span>⚠️</span>
                                    <span>{{ $issue->message }} ({{ $issue->created_at->diffForHumans() }})</span>
                                </div>
                            @empty
                                <p class="text-xs text-[var(--text-muted)] italic">No issues reported.</p>
                            @endforelse
                        </div>

                        {{-- Apply Fine Form --}}
                        <div
                            class="rounded-md border border-[var(--danger)]/20 bg-[var(--danger-bg)]/30 p-3.5 space-y-3 mt-2">
                            <span class="text-sm font-medium text-[var(--text-secondary)] block">Add Damage / Missing
                                Charge to Settle Invoice</span>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <input type="text" wire:model="damageDescription"
                                        placeholder="Description (e.g. Towel)"
                                        class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-[11px] text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:outline-none transition-all">
                                </div>
                                <div>
                                    <input type="number" wire:model="damageAmount" placeholder="Amount (Rp)"
                                        class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-[11px] text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:outline-none transition-all">
                                </div>
                            </div>
                            <button type="button" wire:click.prevent="applyDamageCharge"
                                class="w-full rounded bg-[var(--danger)] hover:bg-[var(--danger)]/90 text-white font-semibold py-1.5 text-xs transition-all cursor-pointer">
                                Add Fine to Invoice
                            </button>
                        </div>
                    </div>

                    {{-- Invoice Summary --}}
                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] p-4 text-xs space-y-2">
                        <span class="text-sm font-medium text-[var(--text-secondary)] block">Settlement Summary</span>
                        <div class="flex justify-between">
                            <span>Room Charges:</span>
                            <span class="font-mono">Rp
                                {{ number_format((float) $reviewBooking->guestBill->total_room_charges) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Extra Charges (Fines):</span>
                            <span class="font-mono text-[var(--danger)]">Rp
                                {{ number_format((float) $reviewBooking->guestBill->total_extra_charges) }}</span>
                        </div>
                        <div class="flex justify-between font-bold border-t border-[var(--border-color)] pt-1.5">
                            <span>Grand Total:</span>
                            <span class="font-mono">Rp
                                {{ number_format($reviewBooking->guestBill->total_room_charges + $reviewBooking->guestBill->total_extra_charges) }}</span>
                        </div>
                    </div>

                    {{-- Release options --}}
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeReviewModal"
                            class="rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-all">Cancel</button>
                        <button type="button" wire:click="releaseRoom('maintenance')"
                            class="rounded-md bg-[var(--warning)] hover:bg-[var(--warning)]/90 text-white px-4 py-2 text-xs font-semibold transition-all">Release
                            to Maintenance</button>
                        <button type="button" wire:click="releaseRoom('available')"
                            class="rounded-md bg-[var(--success)] hover:bg-[var(--success)]/90 text-white px-4 py-2 text-xs font-semibold transition-all">Release
                            as Available</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Order Food Modal ── --}}
    @if ($showOrderFoodModal && $orderBooking)
        <div class="fixed inset-0 z-50 flex items-center justify-center gap-4 p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeOrderFoodModal"></div>
            <div
                class="relative w-full max-w-lg rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)] space-y-5">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <h3 class="text-base font-bold text-[var(--text-primary)]">Order Room Service Food & Beverage</h3>
                    <button wire:click="closeOrderFoodModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl">&times;</button>
                </div>
                <div class="space-y-4">
                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] p-4 text-xs text-[var(--text-secondary)] space-y-2">
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Room:</strong> <span
                                class="font-bold text-[var(--text-primary)]">Room
                                {{ $orderBooking->room->room_number }}
                                ({{ $orderBooking->room->roomType->name }})</span></p>
                        <p class="flex justify-between"><strong class="text-[var(--text-muted)]">Guest:</strong> <span
                                class="font-bold text-[var(--text-primary)]">{{ $orderBooking->guest->name }}</span>
                        </p>
                    </div>

                    {{--  Existing Orders & Current Status --}}
                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-primary)]/40 p-4 text-xs space-y-3">
                        <span class="text-sm font-medium text-[var(--text-secondary)] block">Order History &amp;
                            Status</span>
                        <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1 custom-scrollbar">
                            @forelse($orderBooking->foodOrders->sortByDesc('created_at') as $order)
                                <div
                                    class="rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-card)] p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono font-bold text-[var(--accent-primary)]">Order
                                            #{{ $order->id }}</span>
                                        @php
                                            $statusStyles = [
                                                'processed' =>
                                                    'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border-[var(--border-color)]',
                                                'preparing' =>
                                                    'bg-[var(--warning-bg)] text-[var(--warning)] border-[var(--border-color)]',
                                                'delivered' =>
                                                    'bg-[var(--success-bg)] text-[var(--success)] border-[var(--border-color)]',
                                                'completed' =>
                                                    'bg-[var(--success-bg)] text-[var(--success)] border-[var(--border-color)]',
                                            ];
                                            $statusClass =
                                                $statusStyles[$order->status] ??
                                                'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border-[var(--border-color)]';
                                        @endphp
                                        <span
                                            class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-[10px] font-semibold border {{ $statusClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div class="space-y-1">
                                        @foreach ($order->items as $item)
                                            <div class="flex justify-between text-[var(--text-secondary)]">
                                                <span class="truncate max-w-[260px]">{{ $item->quantity }}x
                                                    {{ $item->service->name }}</span>
                                                <span class="font-mono">Rp
                                                    {{ number_format($item->price * $item->quantity) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div
                                        class="flex justify-between border-t border-dashed border-[var(--border-color)] pt-1.5 font-bold text-[var(--text-primary)]">
                                        <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                        <span class="font-mono">Rp
                                            {{ number_format((float) $order->total_price) }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-[var(--text-muted)] italic">No orders placed yet for this
                                    booking.</p>
                            @endforelse
                        </div>
                    </div>

                    @php
                        $categories = $fnbServices->pluck('category')->unique()->filter()->sort()->values();
                    @endphp

                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Category</label>
                        <select wire:model.live="selectedFoodCategory"
                            class="w-full rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2.5 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all cursor-pointer">
                            <option value="">— Select a category —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($selectedFoodCategory)
                        @php
                            $filteredServices = $fnbServices->where('category', $selectedFoodCategory)->values();
                        @endphp
                        <div class="max-h-[260px] overflow-y-auto space-y-2 pr-1 custom-scrollbar">
                            @forelse($filteredServices as $service)
                                <div
                                    class="flex items-center justify-between p-3 rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-secondary)]/50">
                                    <div class="flex items-center gap-3">
                                        @if ($service->image_path)
                                            <img src="{{ $service->image_path }}"
                                                class="h-10 w-10 object-cover rounded-[var(--radius-sm)] border border-[var(--border-color)]"
                                                alt="{{ $service->name }}">
                                        @else
                                            <div
                                                class="h-10 w-10 bg-[var(--bg-secondary)] border border-[var(--border-color)] rounded-[var(--radius-sm)] flex items-center justify-center text-[var(--text-muted)]">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 20.488l-.015.01A48.111 48.111 0 0112 21c-2.576 0-5.071-.228-7.5-.668m15 0a48.108 48.108 0 01-7.5.668" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-[var(--text-primary)]">
                                                {{ $service->name }}</p>
                                            <p class="text-xs text-[var(--text-muted)] font-mono">Rp
                                                {{ number_format((float) $service->price) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            wire:click="updateFoodQuantity({{ $service->id }}, -1)"
                                            class="w-7 h-7 border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] text-sm font-bold flex items-center justify-center rounded-[var(--radius-sm)] cursor-pointer select-none transition-colors">&minus;</button>
                                        <span
                                            class="w-8 text-center text-sm font-bold font-mono text-[var(--text-primary)]">{{ $orderItems[$service->id] ?? 0 }}</span>
                                        <button type="button"
                                            wire:click="updateFoodQuantity({{ $service->id }}, 1)"
                                            class="w-7 h-7 border border-[var(--border-color)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] text-sm font-bold flex items-center justify-center rounded-[var(--radius-sm)] cursor-pointer select-none transition-colors">&plus;</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-sm text-[var(--text-muted)] py-4">No items in this category.
                                </p>
                            @endforelse
                        </div>
                    @endif

                    {{-- Running Total --}}
                    @php
                        $runningTotal = 0;
                        foreach ($fnbServices as $service) {
                            $runningTotal += ($orderItems[$service->id] ?? 0) * (float) $service->price;
                        }
                    @endphp

                    <div
                        class="rounded-md border border-[var(--border-color)] bg-[var(--bg-secondary)] p-4 flex justify-between items-center text-xs font-bold">
                        <span class="text-sm font-medium text-[var(--text-secondary)]">Running Total:</span>
                        <span class="text-sm font-mono text-[var(--text-primary)]">Rp
                            {{ number_format($runningTotal) }}</span>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeOrderFoodModal"
                            class="rounded-md border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-all">Cancel</button>
                        <button type="button" wire:click="confirmFoodOrder"
                            @if ($runningTotal <= 0) disabled class="rounded-md bg-[var(--accent-primary)] opacity-50 cursor-not-allowed px-4 py-2 text-xs font-semibold text-white transition-all" @else class="rounded-md bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]" @endif>
                            Confirm Order
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="rounded-md w-full max-w-md border border-[var(--border-color)] bg-[var(--bg-primary)]/40 p-4 text-xs space-y-3">
                <span class="text-sm font-medium text-[var(--text-secondary)] block">Order History &amp; Status</span>
                <div class="space-y-2 max-h-[200px] overflow-y-auto pr-1 custom-scrollbar">
                    @forelse($orderBooking->foodOrders->sortByDesc('created_at') as $order)
                        <div
                            class="rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-card)] p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-bold text-[var(--accent-primary)]">Order
                                    #{{ $order->id }}</span>
                                @php
                                    $statusStyles = [
                                        'processed' =>
                                            'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border-[var(--border-color)]',
                                        'preparing' =>
                                            'bg-[var(--warning-bg)] text-[var(--warning)] border-[var(--border-color)]',
                                        'delivered' =>
                                            'bg-[var(--success-bg)] text-[var(--success)] border-[var(--border-color)]',
                                        'completed' =>
                                            'bg-[var(--success-bg)] text-[var(--success)] border-[var(--border-color)]',
                                    ];
                                    $statusClass =
                                        $statusStyles[$order->status] ??
                                        'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border-[var(--border-color)]';
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-[10px] font-semibold border {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                @foreach ($order->items as $item)
                                    <div class="flex justify-between text-[var(--text-secondary)]">
                                        <span class="truncate max-w-[260px]">{{ $item->quantity }}x
                                            {{ $item->service->name }}</span>
                                        <span class="font-mono">Rp
                                            {{ number_format($item->price * $item->quantity) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div
                                class="flex justify-between border-t border-dashed border-[var(--border-color)] pt-1.5 font-bold text-[var(--text-primary)]">
                                <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                                <span class="font-mono">Rp {{ number_format((float) $order->total_price) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-[var(--text-muted)] italic">No orders placed yet for this booking.</p>
                    @endforelse
                </div>
            </div>
        </div>

    @endif
</div>
