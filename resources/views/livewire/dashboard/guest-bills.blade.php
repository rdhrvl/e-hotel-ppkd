<div>
    {{-- ── Search Controls ── --}}
    <div class="card mb-6" style="padding: 16px 20px;">
        <div class="flex items-center gap-3">
            <div style="flex: 1;">
                <label class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">Search Guest Bill</label>
                <input type="text" wire:model.live="search" class="form-input" placeholder="Search by guest name or ID document..." style="padding: 8px 12px;">
            </div>
        </div>
    </div>

    @if($selectedBookingId)
        @php
            $activeBooking = \App\Models\Booking::find($selectedBookingId);
        @endphp
        {{-- ── Charge Extra Service Panel ── --}}
        <div class="card mb-6" style="border: 1px solid rgba(108,92,231,0.3); background: rgba(108,92,231,0.02);">
            <div class="flex items-center justify-between mb-4">
                <h3 style="font-size: 1.1rem; margin: 0; color: var(--text-primary);">Charge Extra Service to {{ $activeBooking->guest_name }} (Room {{ $activeBooking->room->room_number }})</h3>
                <button wire:click="$set('selectedBookingId', null)" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cancel</button>
            </div>
            
            <form wire:submit.prevent="addServiceCharge" class="flex gap-4 items-end" style="flex-wrap: wrap;">
                <div class="form-group" style="flex: 2; min-width: 200px; margin-bottom: 0;">
                    <label class="form-label" for="selectedServiceId">Select Service</label>
                    <select id="selectedServiceId" wire:model="selectedServiceId" class="form-input" style="padding: 10px 14px;" required>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} (Rp {{ number_format($service->price) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="flex: 1; min-width: 100px; margin-bottom: 0;">
                    <label class="form-label" for="serviceQuantity">Quantity</label>
                    <input type="number" id="serviceQuantity" wire:model="serviceQuantity" class="form-input" style="padding: 10px 14px;" min="1" required>
                </div>

                <div class="form-group" style="flex: 2; min-width: 200px; margin-bottom: 0;">
                    <label class="form-label" for="serviceNotes">Notes / Description (Optional)</label>
                    <input type="text" id="serviceNotes" wire:model="serviceNotes" class="form-input" style="padding: 10px 14px;" placeholder="e.g. Deliver to Room {{ $activeBooking->room->room_number }}">
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 11px 24px;">Add Charge</button>
            </form>
        </div>
    @endif

    {{-- ── Bills List ── --}}
    <div class="flex flex-col gap-4">
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
            <div class="card" style="padding: 24px;">
                <div class="flex justify-between items-start mb-4 pb-3" style="border-bottom: 1px solid var(--border-color); flex-wrap: wrap; gap: 12px;">
                    <div>
                        <div class="flex items-center gap-3">
                            <h3 style="font-size: 1.15rem; margin: 0; color: var(--text-primary);">{{ $booking->guest_name }}</h3>
                            <span class="badge" style="background: rgba(255,255,255,0.05); color: var(--text-secondary);">Room {{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</span>
                            @if($booking->status === 'checked_in')
                                <span class="badge badge-transit" style="background: rgba(253, 203, 110, 0.15); color: var(--warning);">In-House</span>
                            @else
                                <span class="badge badge-completed" style="background: rgba(0, 184, 148, 0.15); color: var(--success);">Checked Out</span>
                            @endif
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
                            Stay period: {{ $booking->check_in_date->format('d M Y') }} to {{ $booking->check_out_date->format('d M Y') }} ({{ $booking->nights }} nights) • ID: {{ $booking->guest_id }}
                        </div>
                    </div>

                    <div class="flex gap-2">
                        @if($booking->status === 'checked_in')
                            <button wire:click="selectBookingForService({{ $booking->id }})" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.8rem;">
                                + Charge Service
                            </button>
                        @endif
                        <a href="{{ route('bookings.invoice', $booking->id) }}" target="_blank" class="btn btn-secondary" style="padding: 8px 12px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-right: 4px;"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Invoice
                        </a>
                    </div>
                </div>

                {{-- Billing Itemization Grid --}}
                <div class="grid grid-cols-3 gap-6" style="grid-template-columns: 2fr 1fr; gap: 24px;">
                    <div>
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 12px;">Itemized Charges</h4>
                        <div class="table-responsive">
                            <table class="table" style="font-size: 0.85rem;">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Qty/Nights</th>
                                        <th>Rate</th>
                                        <th style="text-align: right;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Room Charge ({{ $booking->room->roomType->name }})</td>
                                        <td>{{ $booking->nights }}</td>
                                        <td>Rp {{ number_format($booking->room->effective_price) }}</td>
                                        <td style="text-align: right; font-weight: 500; color: var(--text-primary);">Rp {{ number_format($roomCost) }}</td>
                                    </tr>
                                    @forelse($booking->bookingItems as $item)
                                        <tr>
                                            <td>
                                                <div>{{ $item->service->name }}</div>
                                                @if($item->notes)
                                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->notes }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format((float)$item->price) }}</td>
                                            <td style="text-align: right; font-weight: 500; color: var(--text-primary);">Rp {{ number_format($item->subtotal) }}</td>
                                        </tr>
                                    @empty
                                        {{-- No extras --}}
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 20px; font-size: 0.85rem; align-self: start;">
                        <h4 style="font-size: 0.85rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Bill Summary</h4>
                        
                        <div class="flex justify-between mb-2">
                            <span>Room Cost:</span>
                            <span style="font-weight: 500; color: var(--text-primary);">Rp {{ number_format($roomCost) }}</span>
                        </div>

                        <div class="flex justify-between mb-2">
                            <span>Extra Charges:</span>
                            <span style="font-weight: 500; color: var(--text-primary);">Rp {{ number_format($extraCost) }}</span>
                        </div>

                        <div class="flex justify-between mb-2 border-top pt-2" style="border-top: 1px solid var(--border-color); font-weight: 600; color: var(--text-primary); font-size: 0.95rem;">
                            <span>Grand Total:</span>
                            <span>Rp {{ number_format($grandTotal) }}</span>
                        </div>

                        <div class="flex justify-between mb-2" style="color: var(--success);">
                            <span>Paid Deposit:</span>
                            <span>- Rp {{ number_format($deposit) }}</span>
                        </div>

                        @if($paid > 0)
                            <div class="flex justify-between mb-2" style="color: var(--success);">
                                <span>Payments Settled:</span>
                                <span>- Rp {{ number_format($paid) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between border-top pt-2 mt-2" style="border-top: 2px solid var(--border-color); font-size: 1.05rem; font-weight: 700; color: var(--text-primary);">
                            <span>Balance Due:</span>
                            <span>Rp {{ number_format(max(0, $balance)) }}</span>
                        </div>

                        <div class="mt-4 pt-2 border-top text-center" style="border-top: 1px dashed var(--border-color);">
                            <span class="badge {{ $bill->status === 'paid' ? 'badge-completed' : 'badge-cancelled' }}" style="padding: 6px 12px;">
                                Bill Status: {{ ucfirst($bill->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center" style="padding: 40px; color: var(--text-muted);">
                No active or completed guest bills found.
            </div>
        @endforelse
    </div>
</div>
