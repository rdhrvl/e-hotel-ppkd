<div>
    {{-- ── 1. KPI Counters ── --}}
    <div class="grid grid-cols-3 gap-4 mb-6" style="grid-template-columns: repeat(4, 1fr); gap: 16px;">
        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $rooms->count() }}</div>
                <div class="stat-label">Total Rooms</div>
            </div>
            <div class="stat-icon icon-blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            </div>
        </div>

        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $rooms->where('booking_status', 'available')->count() }}</div>
                <div class="stat-label">Available</div>
            </div>
            <div class="stat-icon icon-green">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>
            </div>
        </div>

        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $rooms->where('booking_status', 'booked')->count() }}</div>
                <div class="stat-label">Reserved</div>
            </div>
            <div class="stat-icon" style="background: rgba(116, 185, 255, 0.15); color: var(--info);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $rooms->where('booking_status', 'occupied')->count() }}</div>
                <div class="stat-label">Occupied</div>
            </div>
            <div class="stat-icon icon-orange">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75"/></svg>
            </div>
        </div>
    </div>

    {{-- ── 2. Filters Row ── --}}
    <div class="card mb-6" style="padding: 16px 20px;">
        <div class="flex items-center justify-between" style="gap: 16px; flex-wrap: wrap;">
            <div class="flex items-center gap-3" style="flex-wrap: wrap;">
                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">Room Type</label>
                    <select wire:model.live="filterType" class="form-input" style="padding: 8px 12px; width: 160px;">
                        <option value="">All Types</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">Booking Status</label>
                    <select wire:model.live="filterStatus" class="form-input" style="padding: 8px 12px; width: 160px;">
                        <option value="">All Statuses</option>
                        <option value="available">Available</option>
                        <option value="booked">Reserved</option>
                        <option value="occupied">Occupied</option>
                    </select>
                </div>

                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 0.75rem;">Cleaning Status</label>
                    <select wire:model.live="filterCleaning" class="form-input" style="padding: 8px 12px; width: 160px;">
                        <option value="">All Housekeeping</option>
                        <option value="clean">Clean</option>
                        <option value="dirty">Dirty</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            @if(auth()->user()->isAdmin() || auth()->user()->isFrontDesk())
                <a href="{{ route('bookings') }}" class="btn btn-secondary" style="padding: 8px 16px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Manage Bookings
                </a>
            @endif
        </div>
    </div>

    {{-- ── 3. Rooms Grid ── --}}
    <div class="grid grid-cols-3 gap-4" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px;">
        @forelse($rooms as $room)
            @php
                $borderClass = '';
                $badgeClass = '';
                $statusLabel = 'Available';
                
                if ($room->booking_status === 'available') {
                    $borderClass = 'border-left: 4px solid var(--success);';
                    $badgeClass = 'badge-completed';
                    $statusLabel = 'Available';
                } elseif ($room->booking_status === 'booked') {
                    $borderClass = 'border-left: 4px solid var(--info);';
                    $badgeClass = 'badge-active';
                    $statusLabel = 'Reserved';
                } elseif ($room->booking_status === 'occupied') {
                    $borderClass = 'border-left: 4px solid var(--accent-primary);';
                    $badgeClass = 'badge-cancelled';
                    $statusLabel = 'Occupied';
                }
            @endphp
            <div class="card flex flex-col justify-between" style="{{ $borderClass }} padding: 20px; min-height: 200px;">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">Room {{ $room->room_number }}</span>
                        <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                    </div>

                    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px; font-weight: 500;">
                        {{ $room->roomType->name }} — Rp {{ number_format($room->effective_price) }}/night
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        @if($room->cleaning_status === 'clean')
                            <span class="badge badge-completed" style="text-transform: capitalize; background: rgba(0, 184, 148, 0.08); padding: 2px 8px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 4px; display: inline;"><path d="M5 13l4 4L19 7"/></svg>
                                Clean
                            </span>
                        @elseif($room->cleaning_status === 'dirty')
                            <span class="badge badge-cancelled" style="text-transform: capitalize; background: rgba(225, 112, 85, 0.08); padding: 2px 8px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 4px; display: inline;"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Dirty
                            </span>
                        @else
                            <span class="badge badge-transit" style="text-transform: capitalize; background: rgba(253, 203, 110, 0.08); padding: 2px 8px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right: 4px; display: inline;"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                                Maintenance
                            </span>
                        @endif
                    </div>
                </div>

                {{-- ── Action Buttons based on Role ── --}}
                <div class="mt-4 flex items-center justify-between gap-2 border-top pt-3" style="border-top: 1px solid var(--border-color); width: 100%;">
                    @if(auth()->user()->isHousekeeping() || auth()->user()->isAdmin())
                        {{-- Housekeeping Controls --}}
                        <div class="flex gap-1 justify-between w-full">
                            <button wire:click="updateCleaningStatus({{ $room->id }}, 'clean')" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.75rem; border-color: rgba(0,184,148,0.3); color: var(--success); flex: 1;">
                                Clean
                            </button>
                            <button wire:click="updateCleaningStatus({{ $room->id }}, 'dirty')" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.75rem; border-color: rgba(225,112,85,0.3); color: var(--danger); flex: 1;">
                                Dirty
                            </button>
                            <button wire:click="updateCleaningStatus({{ $room->id }}, 'maintenance')" class="btn btn-secondary" style="padding: 6px 10px; font-size: 0.75rem; border-color: rgba(253,203,110,0.3); color: var(--warning); flex: 1;">
                                Maint.
                            </button>
                        </div>
                    @else
                        {{-- Front Desk / Admin Controls --}}
                        @if($room->booking_status === 'available')
                            <button wire:click="openBookingModal({{ $room->id }})" class="btn btn-primary w-full" style="padding: 8px 16px; font-size: 0.8rem;" {{ $room->cleaning_status === 'maintenance' ? 'disabled' : '' }}>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                Reserve
                            </button>
                        @elseif($room->booking_status === 'booked')
                            <button wire:click="openCheckInModal({{ $room->id }})" class="btn btn-primary w-full" style="padding: 8px 16px; font-size: 0.8rem; background: linear-gradient(135deg, var(--info), #4ba3e3);">
                                Check In
                            </button>
                        @elseif($room->booking_status === 'occupied')
                            <div class="flex gap-2 w-full">
                                <button wire:click="openAddServiceModal({{ $room->id }})" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.8rem; flex: 1;" title="Charge extra stay services">
                                    + Charge
                                </button>
                                <button wire:click="openCheckOutModal({{ $room->id }})" class="btn btn-primary" style="padding: 8px 12px; font-size: 0.8rem; background: linear-gradient(135deg, var(--accent-primary), #4b3ebd); flex: 1;">
                                    Check Out
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="card w-full text-center" style="grid-column: 1 / -1; padding: 40px;">
                No rooms found matching filters.
            </div>
        @endforelse
    </div>

    {{-- ── 4. Booking Modal ── --}}
    @if($showBookingModal)
        <div class="modal-backdrop">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Reserve Room {{ \App\Models\Room::find($selectedRoomId)?->room_number }}</h3>
                    <button wire:click="closeBookingModal" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.25rem;">&times;</button>
                </div>
                <form wire:submit.prevent="bookRoom">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="guestName">Guest Name</label>
                            <input type="text" id="guestName" wire:model="guestName" class="form-input" placeholder="e.g. Sarah Connor" required>
                            @error('guestName') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="guestId">Guest ID (KTP / Passport)</label>
                            <input type="text" id="guestId" wire:model="guestId" class="form-input" placeholder="e.g. 3173092801980001" required>
                            @error('guestId') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3" style="grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="checkInDate">Check-In</label>
                                <input type="date" id="checkInDate" wire:model.live="checkInDate" class="form-input" required>
                                @error('checkInDate') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label" for="checkOutDate">Check-Out</label>
                                <input type="date" id="checkOutDate" wire:model.live="checkOutDate" class="form-input" required>
                                @error('checkOutDate') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="numberGuests">Guests Count</label>
                            <input type="number" id="numberGuests" wire:model="numberGuests" class="form-input" min="1" max="10" required>
                            @error('numberGuests') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Add Initial Stay Services</label>
                            <div class="flex flex-col gap-2" style="background: var(--bg-input); padding: 12px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                                @foreach($services as $service)
                                    <label class="flex items-center gap-2" style="font-size: 0.85rem; cursor: pointer; color: var(--text-secondary);">
                                        <input type="checkbox" wire:model="bookingServices" value="{{ $service->id }}" style="accent-color: var(--accent-primary);">
                                        {{ $service->name }} (Rp {{ number_format($service->price) }})
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeBookingModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Booking</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── 5. Check In Modal ── --}}
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
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">Deposit is fully refundable or deductible on final bill check-out.</p>
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

    {{-- ── 6. Check Out Modal ── --}}
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
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-right: 4px;"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            Print Receipt
                        </a>
                        <button type="button" wire:click="closeCheckOutModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--accent-primary), #4b3ebd);">Confirm Checkout & Pay</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── 7. Add Service Charge Modal ── --}}
    @if($showAddServiceModal && $addServiceBooking)
        <div class="modal-backdrop">
            <div class="modal-content" style="max-width: 460px;">
                <div class="modal-header">
                    <h3>Add Service Charge — Room {{ $addServiceBooking->room->room_number }}</h3>
                    <button wire:click="closeAddServiceModal" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.25rem;">&times;</button>
                </div>
                <form wire:submit.prevent="addServiceCharge">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="selectedServiceId">Select Service</label>
                            <select id="selectedServiceId" wire:model="selectedServiceId" class="form-input" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }} (Rp {{ number_format($service->price) }})</option>
                                @endforeach
                            </select>
                            @error('selectedServiceId') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="serviceQuantity">Quantity</label>
                            <input type="number" id="serviceQuantity" wire:model="serviceQuantity" class="form-input" min="1" required>
                            @error('serviceQuantity') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="serviceNotes">Notes (Optional)</label>
                            <input type="text" id="serviceNotes" wire:model="serviceNotes" class="form-input" placeholder="e.g. Added extra pillows/towels">
                            @error('serviceNotes') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeAddServiceModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Charge Guest Bill</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
