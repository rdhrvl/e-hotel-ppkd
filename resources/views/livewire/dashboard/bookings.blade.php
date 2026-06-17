<div>
    {{-- ── Search & Filter Controls ── --}}
    <div class="card mb-6" style="padding: 16px 20px;">
        <div class="flex items-center justify-between" style="gap: 16px; flex-wrap: wrap;">
            <div class="flex items-center gap-3" style="flex: 1; min-width: 280px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">Search Guest</label>
                    <input type="text" wire:model.live="search" class="form-input" placeholder="Search by name or ID document..." style="padding: 8px 12px;">
                </div>

                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">Status</label>
                    <select wire:model.live="filterStatus" class="form-input" style="padding: 8px 12px; width: 160px;">
                        <option value="">All Reservations</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="checked_in">Checked In</option>
                        <option value="checked_out">Checked Out</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Bookings List Table ── --}}
    <div class="card p-0" style="overflow: hidden;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>ID Document</th>
                        <th>Room</th>
                        <th>Dates</th>
                        <th>Nights</th>
                        <th>Total Bill</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $booking->guest_name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Guests: {{ $booking->number_of_guests }}</div>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-size: 0.85rem;">{{ $booking->guest_id }}</span>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: var(--text-primary);">Room {{ $booking->room->room_number }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $booking->room->roomType->name }}</div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;">In: {{ $booking->check_in_date->format('d M Y') }}</div>
                                <div style="font-size: 0.85rem;">Out: {{ $booking->check_out_date->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge badge-active" style="background: rgba(108,92,231,0.08); padding: 4px 8px;">{{ $booking->nights }} nights</span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">
                                    Rp {{ number_format($booking->guestBill->total_amount) }}
                                </div>
                                <div style="font-size: 0.75rem; color: {{ $booking->guestBill->status === 'paid' ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ ucfirst($booking->guestBill->status) }}
                                </div>
                            </td>
                            <td>
                                @if($booking->status === 'confirmed')
                                    <span class="badge badge-active" style="background: rgba(116, 185, 255, 0.15); color: var(--info);">Confirmed</span>
                                @elseif($booking->status === 'checked_in')
                                    <span class="badge badge-transit" style="background: rgba(253, 203, 110, 0.15); color: var(--warning);">Checked In</span>
                                @elseif($booking->status === 'checked_out')
                                    <span class="badge badge-completed" style="background: rgba(0, 184, 148, 0.15); color: var(--success);">Checked Out</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge badge-cancelled" style="background: rgba(225, 112, 85, 0.15); color: var(--danger);">Cancelled</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div class="flex gap-2 justify-end">
                                    @if($booking->status === 'confirmed')
                                        <button wire:click="openCheckInModal({{ $booking->id }})" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; color: var(--success); border-color: rgba(0,184,148,0.3);">
                                            Check In
                                        </button>
                                        <button onclick="confirm('Are you sure you want to cancel this reservation?') || event.stopImmediatePropagation()" wire:click="cancelBooking({{ $booking->id }})" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; color: var(--danger); border-color: rgba(225,112,85,0.3);">
                                            Cancel
                                        </button>
                                    @elseif($booking->status === 'checked_in')
                                        <button wire:click="openCheckOutModal({{ $booking->id }})" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; color: var(--accent-primary); border-color: rgba(108,92,231,0.3);">
                                            Check Out
                                        </button>
                                    @endif

                                    @if($booking->status !== 'cancelled')
                                        <a href="{{ route('bookings.invoice', $booking->id) }}" target="_blank" class="btn btn-secondary" style="padding: 6px 10px;" title="Print Invoice">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:block;"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 32px; color: var(--text-muted);">
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
        <div class="modal-backdrop">
            <div class="modal-content" style="max-width: 460px;">
                <div class="modal-header">
                    <h3>Guest Check In — Room {{ $checkInBooking->room->room_number }}</h3>
                    <button wire:click="closeCheckInModal" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.25rem;">&times;</button>
                </div>
                <form wire:submit.prevent="checkInGuest">
                    <div class="modal-body">
                        <div style="background: rgba(108,92,231,0.06); padding: 16px; border-radius: var(--radius); margin-bottom: 20px; font-size: 0.85rem; border: 1px solid rgba(108,92,231,0.15);">
                            <div class="mb-1"><strong>Guest:</strong> {{ $checkInBooking->guest_name }}</div>
                            <div class="mb-1"><strong>ID Document:</strong> {{ $checkInBooking->guest_id }}</div>
                            <div class="mb-1"><strong>Stay Dates:</strong> {{ $checkInBooking->check_in_date->format('d M Y') }} to {{ $checkInBooking->check_out_date->format('d M Y') }} ({{ $checkInBooking->nights }} Nights)</div>
                            <div><strong>Room Cost:</strong> Rp {{ number_format($checkInBooking->room->effective_price * $checkInBooking->nights) }}</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="depositAmount">Security Deposit Amount (Rp)</label>
                            <input type="number" id="depositAmount" wire:model="depositAmount" class="form-input" placeholder="e.g. 50000" required>
                            @error('depositAmount') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeCheckInModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--info), #4ba3e3);">Confirm Check-In</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Check Out Modal ── --}}
    @if($showCheckOutModal && $checkOutBooking)
        <div class="modal-backdrop">
            <div class="modal-content" style="max-width: 500px;">
                <div class="modal-header">
                    <h3>Guest Check Out — Room {{ $checkOutBooking->room->room_number }}</h3>
                    <button wire:click="closeCheckOutModal" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.25rem;">&times;</button>
                </div>
                <form wire:submit.prevent="checkOutGuest">
                    <div class="modal-body" style="padding: 20px 24px;">
                        <div style="font-size: 0.85rem; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-bottom: 16px;">
                            <div class="mb-1"><strong>Guest Name:</strong> {{ $checkOutBooking->guest_name }}</div>
                            <div class="mb-1"><strong>Stay:</strong> {{ $checkOutBooking->check_in_date->format('d M Y') }} — {{ $checkOutBooking->check_out_date->format('d M Y') }} ({{ $checkOutBooking->nights }} Nights)</div>
                        </div>

                        <h4 style="font-size: 0.95rem; margin-bottom: 8px;">Stay Summary & Billing</h4>
                        
                        <div style="background: var(--bg-input); border-radius: var(--radius-sm); border: 1px solid var(--border-color); padding: 12px; font-size: 0.85rem; margin-bottom: 16px;">
                            <div class="flex justify-between mb-2">
                                <span>Room Charge ({{ $checkOutBooking->nights }} Nights):</span>
                                <span>Rp {{ number_format((float)$checkOutBooking->guestBill->total_room_charges) }}</span>
                            </div>

                            @if($checkOutBooking->bookingItems->count() > 0)
                                <div style="border-top: 1px dashed var(--border-color); padding-top: 8px; margin-top: 8px; margin-bottom: 8px;">
                                    <strong>Extra Stay Services Charged:</strong>
                                </div>
                                @foreach($checkOutBooking->bookingItems as $item)
                                    <div class="flex justify-between mb-1" style="font-size: 0.8rem; color: var(--text-secondary);">
                                        <span>{{ $item->service->name }} (x{{ $item->quantity }}):</span>
                                        <span>Rp {{ number_format($item->subtotal) }}</span>
                                    </div>
                                @endforeach
                            @endif

                            <div class="flex justify-between border-top pt-2" style="border-top: 1px solid var(--border-color); font-weight: 600; color: var(--text-primary); margin-top: 8px;">
                                <span>Grand Total:</span>
                                <span>Rp {{ number_format($checkOutBooking->guestBill->total_amount) }}</span>
                            </div>

                            <div class="flex justify-between mt-1" style="color: var(--success);">
                                <span>Paid Security Deposit:</span>
                                <span>- Rp {{ number_format((float)$checkOutBooking->guestBill->deposit_amount) }}</span>
                            </div>

                            @php
                                $bill = $checkOutBooking->guestBill;
                                $grandTotal = $bill->total_room_charges + $bill->total_extra_charges;
                                $netAmountDue = $grandTotal - $bill->deposit_amount - $bill->paid_amount;
                            @endphp

                            <div class="flex justify-between border-top pt-2 mt-2" style="border-top: 2px solid var(--border-color); font-size: 1rem; font-weight: 700; color: var(--text-primary);">
                                <span>Balance to Pay:</span>
                                <span>Rp {{ number_format(max(0, $netAmountDue)) }}</span>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label" for="paymentMethod">Payment Method</label>
                            <select id="paymentMethod" wire:model="paymentMethod" class="form-input" required>
                                <option value="cash">Cash (Manual Offline)</option>
                                <option value="bank_transfer">Bank Transfer (Manual Confirmed)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('bookings.invoice', $checkOutBooking->id) }}" target="_blank" class="btn btn-secondary" style="margin-right: auto; padding: 10px 16px;">
                            Print Receipt
                        </a>
                        <button type="button" wire:click="closeCheckOutModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--accent-primary), #4b3ebd);">Confirm Checkout & Pay</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
