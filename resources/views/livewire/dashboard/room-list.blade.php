<div wire:poll.5s>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Left main pane (cols 1-3) --}}
        <div class="lg:col-span-3">
            {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">Room Availability</h1>
        <p class="text-xs text-[var(--text-muted)] mt-1">Real-time status board and reservations panel.</p>
    </div>

    {{-- KPI Counters --}}
    <div class="grid grid-cols-4 md:grid-cols-4 gap-6 mb-8">
        {{-- Total Rooms (All) --}}
        <div wire:click="toggleStatusFilter('')" 
             class="rounded border p-5 flex items-center justify-between shadow-sm transition-all duration-200 cursor-pointer hover:border-[var(--text-primary)] hover:scale-[1.01] active:scale-[0.99] select-none
             {{ $filterStatus === '' ? 'border-[var(--text-primary)] bg-[var(--bg-secondary)] ring-1 ring-[var(--text-primary)]' : 'border-[var(--border-color)] bg-[var(--bg-card)]' }}">
            <div>
                <span class="text-2xl font-bold text-[var(--text-primary)] font-mono tracking-tight">{{ $totalRoomsCount }}</span>
                <p class="text-sm font-medium text-[var(--text-muted)] mt-1">Total Rooms</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--bg-secondary)] flex items-center justify-center text-[var(--text-primary)] border border-[var(--border-color)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25" />
                </svg>
            </div>
        </div>

        {{-- Available --}}
        <div wire:click="toggleStatusFilter('available')" 
             class="rounded border p-5 flex items-center justify-between shadow-sm transition-all duration-200 cursor-pointer hover:border-[var(--success)] hover:scale-[1.01] active:scale-[0.99] select-none
             {{ $filterStatus === 'available' ? 'border-[var(--success)] bg-[var(--success-bg)] ring-1 ring-[var(--success)]' : 'border-[var(--border-color)] bg-[var(--bg-card)]' }}">
            <div>
                <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight">{{ $availableRoomsCount }}</span>
                <p class="text-sm font-medium text-[var(--text-muted)] mt-1">Available</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--success-bg)] flex items-center justify-center text-[var(--success)] border border-[var(--border-color)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Reserved --}}
        <div wire:click="toggleStatusFilter('reserved')" 
             class="rounded border p-5 flex items-center justify-between shadow-sm transition-all duration-200 cursor-pointer hover:border-[var(--warning)] hover:scale-[1.01] active:scale-[0.99] select-none
             {{ $filterStatus === 'reserved' ? 'border-[var(--warning)] bg-[var(--warning-bg)] ring-1 ring-[var(--warning)]' : 'border-[var(--border-color)] bg-[var(--bg-card)]' }}">
            <div>
                <span class="text-2xl font-bold text-[var(--warning)] font-mono tracking-tight">{{ $reservedRoomsCount }}</span>
                <p class="text-sm font-medium text-[var(--text-muted)] mt-1">Reserved</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--warning-bg)] flex items-center justify-center text-[var(--warning)] border border-[var(--border-color)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
        </div>

        {{-- Occupied --}}
        <div wire:click="toggleStatusFilter('occupied')" 
             class="rounded border p-5 flex items-center justify-between shadow-sm transition-all duration-200 cursor-pointer hover:border-[var(--danger)] hover:scale-[1.01] active:scale-[0.99] select-none
             {{ $filterStatus === 'occupied' ? 'border-[var(--danger)] bg-[var(--danger-bg)] ring-1 ring-[var(--danger)]' : 'border-[var(--border-color)] bg-[var(--bg-card)]' }}">
            <div>
                <span class="text-2xl font-bold text-[var(--danger)] font-mono tracking-tight">{{ $occupiedRoomsCount }}</span>
                <p class="text-sm font-medium text-[var(--text-muted)] mt-1">Occupied</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--danger-bg)] flex items-center justify-center text-[var(--danger)] border border-[var(--border-color)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Filter / Search Bar --}}
    <div class="flex flex-wrap items-center gap-4 mb-6">
        {{-- Room Type --}}
        <div>
            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Room Type</label>
            <select wire:model.live="filterType" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none cursor-pointer transition-all">
                <option value="">All Types</option>
                @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Bed Size Filter --}}
        <div>
            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Bed Size</label>
            <select wire:model.live="filterBedType" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none cursor-pointer transition-all">
                <option value="">Any Bed Size</option>
                <option value="Single">Single</option>
                <option value="Twin">Twin</option>
                <option value="Double">Double</option>
                <option value="Queen">Queen</option>
                <option value="King">King</option>
                <option value="Super King">Super King</option>
            </select>
        </div>

        {{-- Breakfast Filter --}}
        <div>
            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Breakfast</label>
            <select wire:model.live="filterBreakfast" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none cursor-pointer transition-all">
                <option value="">Either</option>
                <option value="1">Included</option>
                <option value="0">Not Included</option>
            </select>
        </div>
    </div>

    {{-- Room Card Grid Grouped by Floor --}}
    @php
        $groupedRooms = $rooms->groupBy('floor')->sortKeys();
    @endphp

    @forelse($groupedRooms as $floor => $floorRooms)
        @php
            $sortedFloorRooms = $floorRooms->sortBy('room_number');
        @endphp
        <section class="mb-10">
            {{-- Floor header --}}
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded bg-[var(--text-primary)] text-[var(--bg-card)] flex flex-col items-center justify-center">
                    <span class="text-[8px] font-bold uppercase tracking-wide leading-none">LT</span>
                    <span class="text-lg font-bold leading-none">{{ $floor }}</span>
                </div>
                <h2 class="text-xl font-bold text-[var(--text-primary)]">Lantai {{ $floor }}</h2>
            </div>

            {{-- Room grid --}}
            <div class="grid grid-cols-5 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-3">
                @foreach($sortedFloorRooms as $room)
                    @php
                        $typeName = strtoupper(substr($room->roomType->name, 0, 3));
                        $typeBadgeClass = 'bg-[var(--info-bg)] text-[var(--info)]';
                        if ($typeName === 'DEL') {
                            $typeBadgeClass = 'bg-[var(--warning-bg)] text-[var(--warning)]';
                        } elseif ($typeName === 'SUI') {
                            $typeBadgeClass = 'bg-[var(--success-bg)] text-[var(--success)]';
                        }

                        $cardClass = 'bg-[var(--bg-card)] border-[var(--border-color)] text-[var(--text-secondary)] hover:bg-[var(--bg-card-hover)]';
                        $numberClass = 'text-[var(--text-primary)]';
                        $statusClass = 'text-[var(--text-muted)]';
                        $statusLabelText = 'VAKANT';
                        $showRedDot = false;

                        if ($room->status === 'available') {
                            $cardClass = 'bg-[var(--bg-card)] border-[var(--border-color)] text-[var(--text-secondary)] hover:bg-[var(--bg-card-hover)]';
                            $numberClass = 'text-[var(--text-primary)]';
                            $statusClass = 'text-[var(--text-muted)]';
                            $statusLabelText = 'VAKANT';
                        } elseif ($room->status === 'occupied') {
                            $cardClass = 'bg-[var(--danger-bg)] border-[var(--border-color)] text-[var(--danger)] hover:bg-[var(--danger-bg)]/80';
                            $numberClass = 'text-[var(--danger)]';
                            $statusClass = 'text-[var(--danger)]/80';
                            $statusLabelText = $room->activeBooking?->guest?->name ? strtoupper($room->activeBooking->guest->name) : 'OCCUPIED';
                            $showRedDot = true;
                        } elseif ($room->status === 'reserved') {
                            $cardClass = 'bg-[var(--warning-bg)] border-[var(--border-color)] text-[var(--warning)] hover:bg-[var(--warning-bg)]/80';
                            $numberClass = 'text-[var(--warning)]';
                            $statusClass = 'text-[var(--warning)]/80';
                            $statusLabelText = 'RESERVED';
                        } else {
                            $cardClass = 'bg-[var(--bg-secondary)] border-[var(--border-color)] text-[var(--text-secondary)] hover:bg-[#eae9e4]';
                            $numberClass = 'text-[var(--text-primary)]';
                            $statusClass = 'text-[var(--text-muted)]';
                            $statusLabelText = strtoupper($room->status);
                        }
                    @endphp
                    
                    <div wire:click="selectRoom({{ $room->id }})" 
                          class="relative w-full border rounded shadow-sm p-3 flex flex-col items-center justify-center gap-1 text-center min-h-[95px] transition-all duration-150 cursor-pointer {{ $cardClass }} {{ $selectedRoomId === $room->id ? 'ring-1 ring-[#111111] border-transparent' : '' }}"
                          title="Room {{ $room->room_number }} ({{ $room->roomType->name }}) - Capacity: {{ $room->roomType->capacity }} people. AC, Wi-Fi, TV included.">
                        
                        {{-- Type badge --}}
                        <span class="absolute top-2 left-2 text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $typeBadgeClass }}">
                            {{ $typeName }}
                        </span>

                        {{-- Occupied dot --}}
                        @if($showRedDot)
                            <span class="absolute top-2 right-2 w-1.5 h-1.5 rounded-full bg-[#9f2f2d]"></span>
                        @endif

                        {{-- Room number --}}
                        <span class="text-lg font-bold {{ $numberClass }} mt-3">
                            {{ $room->room_number }}
                        </span>

                        {{-- Status / Occupant label --}}
                        <span class="text-xs font-medium truncate w-full px-1 {{ $statusClass }}">
                            {{ $statusLabelText }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-12 text-center col-span-full shadow-sm">
            <svg class="h-8 w-8 mx-auto text-[var(--text-muted)] mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <p class="text-[var(--text-secondary)] text-sm font-semibold">No rooms match your filter criteria.</p>
        </div>
    @endforelse

    @if($totalPages > 1)
        <div class="mt-8 p-4 rounded border border-[var(--border-color)] bg-[var(--bg-card)] flex items-center justify-between shadow-sm select-none">
            <div class="text-xs text-[var(--text-secondary)]">
                Showing page <span class="font-bold text-[var(--text-primary)]">{{ $currentPage }}</span> of <span class="font-bold text-[var(--text-primary)]">{{ $totalPages }}</span> (<span class="font-bold text-[var(--text-primary)] font-mono">{{ $totalItems }}</span> floors total)
            </div>
            
            <div class="flex items-center gap-1">
                {{-- Previous button --}}
                @if($currentPage == 1)
                    <button disabled class="rounded border border-[var(--border-color)] bg-[var(--bg-secondary)] px-3 py-1.5 text-xs font-bold text-[var(--text-muted)] cursor-not-allowed select-none">
                        &laquo; Prev
                    </button>
                @else
                    <button type="button" wire:click="previousPage" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-xs font-bold text-[var(--text-primary)] hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]">
                        &laquo; Prev
                    </button>
                @endif

                {{-- Page numbers with ellipsis --}}
                @php
                    $onEachSide = 1; // Show current, 1 left, 1 right
                    $pages = [];
                    
                    if ($totalPages <= 5) {
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $pages[] = $i;
                        }
                    } else {
                        // Always include page 1
                        $pages[] = 1;
                        
                        $start = max(2, $currentPage - $onEachSide);
                        $end = min($totalPages - 1, $currentPage + $onEachSide);
                        
                        if ($start > 2) {
                            $pages[] = '...';
                        }
                        
                        for ($i = $start; $i <= $end; $i++) {
                            $pages[] = $i;
                        }
                        
                        if ($end < $totalPages - 1) {
                            $pages[] = '...';
                        }
                        
                        // Always include last page
                        $pages[] = $totalPages;
                    }
                @endphp

                @foreach($pages as $p)
                    @if($p === '...')
                        <span class="px-2 text-xs font-bold text-[var(--text-muted)]">...</span>
                    @elseif($p == $currentPage)
                        <span class="rounded bg-[var(--text-primary)] px-3 py-1.5 text-xs font-bold text-[var(--bg-card)] border border-[var(--text-primary)]">{{ $p }}</span>
                    @else
                        <button type="button" wire:click="gotoPage({{ $p }})" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-xs font-bold text-[var(--text-primary)] hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]">
                            {{ $p }}
                        </button>
                    @endif
                @endforeach

                {{-- Next button --}}
                @if($currentPage == $totalPages)
                    <button disabled class="rounded border border-[var(--border-color)] bg-[var(--bg-secondary)] px-3 py-1.5 text-xs font-bold text-[var(--text-muted)] cursor-not-allowed select-none">
                        Next &raquo;
                    </button>
                @else
                    <button type="button" wire:click="nextPage({{ $totalPages }})" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-xs font-bold text-[var(--text-primary)] hover:bg-[var(--bg-secondary)] transition-colors active:scale-[0.98]">
                        Next &raquo;
                    </button>
                @endif
            </div>
        </div>
    @endif
        </div>

        {{-- Right sidebar pane (col 4) --}}
        <div class="lg:col-span-1">
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 shadow-sm lg:sticky lg:top-24 flex flex-col lg:max-h-[calc(100vh-120px)] h-auto overflow-hidden">
                <div class="shrink-0 border-b border-[var(--border-color)] pb-3 flex items-center justify-between select-none">
                    <h3 class="text-sm font-semibold text-[var(--text-primary)] flex items-center gap-2">
                        <span>Cart Selection</span>
                        <span class="inline-flex items-center rounded-full bg-[var(--accent-primary)]/10 px-2 py-0.5 text-[10px] font-bold text-[var(--accent-primary)] font-mono">
                            {{ count($cartItems) }} {{ count($cartItems) === 1 ? 'Room' : 'Rooms' }}
                        </span>
                    </h3>
                    @if(count($cartItems) > 0)
                        <button type="button" wire:click="clearCart" class="text-[10px] text-[var(--danger)] hover:underline uppercase font-bold">Clear</button>
                    @endif
                </div>

                @if(count($cartItems) > 0)
                    <div class="flex-1 overflow-y-auto min-h-0 pr-1 my-4 space-y-4">
                        @foreach($cartItems as $item)
                            @php
                                $roomObj = $item['room'];
                                $typeName = strtoupper(substr($roomObj->roomType->name, 0, 3));
                                $typeBadgeClass = 'bg-[var(--info-bg)] text-[var(--info)]';
                                if ($typeName === 'DEL') {
                                    $typeBadgeClass = 'bg-[var(--warning-bg)] text-[var(--warning)]';
                                } elseif ($typeName === 'SUI') {
                                    $typeBadgeClass = 'bg-[var(--success-bg)] text-[var(--success)]';
                                }
                            @endphp
                            <div class="p-3 border border-[var(--border-color)] rounded bg-[var(--bg-primary)] hover:border-[var(--text-primary)] cursor-pointer transition-colors relative group"
                                 wire:click="selectRoom({{ $roomObj->id }})">
                                
                                {{-- Remove item button --}}
                                <button type="button" wire:click.stop="removeFromCart({{ $roomObj->id }})" 
                                        class="absolute top-2 right-2 text-[var(--text-muted)] hover:text-[#9f2f2d] p-1 rounded hover:bg-[var(--bg-secondary)]" 
                                        title="Remove from cart">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $typeBadgeClass }}">
                                        {{ $typeName }}
                                    </span>
                                    <span class="text-sm font-bold text-[var(--text-primary)]">Room {{ $roomObj->room_number }}</span>
                                </div>

                                <div class="flex justify-between items-center text-xs text-[var(--text-secondary)] font-medium mb-1">
                                    <span>Room Rate</span>
                                    <span class="font-mono">Rp {{ number_format($item['room_price']) }}</span>
                                </div>

                                @if(count($item['extras']) > 0)
                                    <div class="border-t border-[var(--border-color)]/60 my-1 pt-1.5">
                                        <span class="text-xs font-medium text-[var(--text-muted)] block mb-1">Extras</span>
                                        @foreach($item['extras'] as $extra)
                                            <div class="flex justify-between items-center text-[11px] text-[var(--text-secondary)]">
                                                <span>• {{ $extra->name }}</span>
                                                <span class="font-mono">Rp {{ number_format((float)$extra->price) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="border-t border-[var(--border-color)]/60 mt-1.5 pt-1.5 flex justify-between items-center text-xs font-bold text-[var(--text-primary)]">
                                    <span>Subtotal</span>
                                    <span class="font-mono">Rp {{ number_format($item['total_cost']) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="shrink-0 border-t border-[var(--border-color)] pt-4 space-y-3">
                        <div class="flex justify-between items-center text-sm font-bold text-[var(--text-primary)]">
                            <span>Grand Total</span>
                            <span class="text-base font-mono text-[var(--accent-primary)] font-bold">Rp {{ number_format($grandTotal) }}</span>
                        </div>

                        <a href="{{ route('booking.create') }}" 
                           class="block w-full text-center bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white font-semibold py-3 rounded text-xs transition-all cursor-pointer shadow-sm active:scale-[0.98]">
                            Confirm & Register
                        </a>
                    </div>
                @else
                    <div class="flex-1 flex flex-col justify-center items-center py-12 text-center text-[var(--text-muted)] text-xs">
                        <svg class="h-8 w-8 mx-auto mb-2 opacity-50 text-[var(--text-muted)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                        <p class="font-bold">Your cart is empty.</p>
                        <p class="mt-1 text-[10px]">Select an available room and click "Add to Cart" to start.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── ACTION MODAL DIALOGS ── --}}
    @if($selectedRoom)
        @php
            $typeName = strtoupper(substr($selectedRoom->roomType->name, 0, 3));
            $typeBadgeClass = 'bg-[var(--info-bg)] text-[var(--info)]';
            if ($typeName === 'DEL') {
                $typeBadgeClass = 'bg-[var(--warning-bg)] text-[var(--warning)]';
            } elseif ($typeName === 'SUI') {
                $typeBadgeClass = 'bg-[var(--success-bg)] text-[var(--success)]';
            }

            $statusLabel = 'Vacant';
            $statusBadgeClass = 'bg-[var(--success-bg)] text-[var(--success)]';
            $isVacant = false;

            if ($selectedRoom->status === 'available') {
                $statusLabel = 'Vacant';
                $statusBadgeClass = 'bg-[var(--success-bg)] text-[var(--success)]';
                $isVacant = true;
            } elseif ($selectedRoom->status === 'occupied') {
                $statusLabel = 'Occupied';
                $statusBadgeClass = 'bg-[var(--danger-bg)] text-[var(--danger)]';
            } elseif ($selectedRoom->status === 'reserved') {
                $statusLabel = 'Reserved';
                $statusBadgeClass = 'bg-[var(--warning-bg)] text-[var(--warning)]';
            } else {
                $statusLabel = strtoupper($selectedRoom->status);
                $statusBadgeClass = 'bg-[var(--bg-secondary)] text-[var(--text-secondary)]';
            }
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             wire:click="$set('selectedRoomId', null)"
             x-data="{}"
             x-on:keydown.escape.window="$wire.set('selectedRoomId', null)">
             
             <div class="relative w-full max-w-sm rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg text-[var(--text-secondary)] mx-auto"
                  wire:click.stop="">
                  
                  {{-- Header row --}}
                  <div class="flex items-center justify-between mb-2">
                      <div class="flex items-center gap-2">
                          <span class="inline-flex items-center rounded-[var(--radius-sm)] px-1.5 py-0.5 text-xs font-semibold {{ $typeBadgeClass }}">
                              {{ $typeName }}
                          </span>
                          <span class="text-xl font-bold text-[var(--text-primary)]">Room {{ $selectedRoom->room_number }}</span>
                      </div>
                      <button wire:click="$set('selectedRoomId', null)" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors p-1" aria-label="Close">
                          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                      </button>
                  </div>

                  {{-- Divider --}}
                  <div class="border-t border-[var(--border-color)] my-4"></div>

                  {{-- Info rows --}}
                  <div class="space-y-4 text-xs">
                      <div class="flex justify-between items-center">
                          <span class="text-[var(--text-muted)] font-medium">Floor</span>
                          <span class="text-[var(--text-primary)] font-bold">Lantai {{ $selectedRoom->floor }}</span>
                      </div>

                      <div class="flex justify-between items-center">
                          <span class="text-[var(--text-muted)] font-medium">Room Type</span>
                          <span class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold uppercase {{ $typeBadgeClass }}">
                              {{ $selectedRoom->roomType->name }}
                          </span>
                      </div>

                      <div class="flex justify-between items-center">
                          <span class="text-[var(--text-muted)] font-medium">Status</span>
                          <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold {{ $statusBadgeClass }}">
                              {{ $statusLabel }}
                          </span>
                      </div>

                      <div class="flex justify-between items-center">
                          <span class="text-[var(--text-muted)] font-medium">Bed Size</span>
                          <span class="text-[var(--text-primary)] font-bold">{{ $selectedRoom->roomType->bed_type ?: 'N/A' }}</span>
                      </div>

                      <div class="flex justify-between items-center">
                          <span class="text-[var(--text-muted)] font-medium">Breakfast</span>
                          @if($selectedRoom->roomType->has_breakfast)
                              <span class="inline-flex items-center rounded bg-[var(--success-bg)] px-2 py-0.5 text-[10px] font-bold uppercase text-[var(--success)]">Included</span>
                          @else
                              <span class="inline-flex items-center rounded bg-[var(--bg-secondary)] px-2 py-0.5 text-[10px] font-bold uppercase text-[var(--text-muted)]">Not Included</span>
                          @endif
                      </div>

                      <div class="flex justify-between items-center">
                          <span class="text-[var(--text-muted)] font-medium">Max Occupancy</span>
                          <span class="text-[var(--text-primary)] font-bold">{{ $selectedRoom->roomType->capacity }} Pax</span>
                      </div>

                      @if($selectedRoom->status === 'occupied' || $selectedRoom->status === 'reserved')
                          <div class="flex justify-between items-center">
                              <span class="text-[var(--text-muted)] font-medium">Occupant</span>
                              <span class="text-[var(--warning)] font-bold uppercase tracking-wide">
                                  {{ $selectedRoom->activeBooking?->guest?->name ?: 'N/A' }}
                              </span>
                          </div>
                          @if($selectedRoom->activeBooking)
                              <div class="flex justify-between items-center">
                                  <span class="text-[var(--text-muted)] font-medium">Check-In</span>
                                  <span class="text-[var(--text-primary)] font-bold">
                                      {{ \Carbon\Carbon::parse($selectedRoom->activeBooking->check_in_date)->format('d M Y') }}
                                  </span>
                              </div>
                              <div class="flex justify-between items-center">
                                  <span class="text-[var(--text-muted)] font-medium">Check-Out</span>
                                  <span class="text-[var(--text-primary)] font-bold">
                                      {{ \Carbon\Carbon::parse($selectedRoom->activeBooking->check_out_date)->format('d M Y') }}
                                  </span>
                              </div>
                          @endif
                      @endif

                      <div class="flex flex-col gap-1.5 pt-1">
                          <span class="text-[var(--text-muted)] font-medium">Extras (Optional)</span>
                          <div class="space-y-2 max-h-40 overflow-y-auto border border-[var(--border-color)] p-3 rounded bg-[var(--bg-primary)]">
                              @php
                                  $incAmenities = $selectedRoom->roomType->included_amenities ?? [];
                                  $roomHasBreakfast = $selectedRoom->roomType->has_breakfast;
                              @endphp
                              @forelse($services as $service)
                                  @php
                                      $isExcluded = false;
                                      if (in_array($service->id, $incAmenities)) {
                                          $isExcluded = true;
                                      }
                                      if ($roomHasBreakfast && $service->type === 'f_and_b' && str_contains(strtolower($service->name), 'breakfast')) {
                                          $isExcluded = true;
                                      }
                                  @endphp
                                  @if(!$isExcluded)
                                      <div class="flex items-center justify-between select-none cursor-pointer">
                                          <div class="flex items-center gap-2">
                                              <input type="checkbox" id="extra-item-{{ $service->id }}" value="{{ $service->id }}" wire:model="modalExtras" class="rounded border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-primary)] focus:ring-0 h-4 w-4 cursor-pointer">
                                              <label class="text-xs text-[var(--text-secondary)] font-medium cursor-pointer" for="extra-item-{{ $service->id }}">
                                                  {{ $service->name }}
                                                  @if($service->description)
                                                      <span class="text-[10px] text-[var(--text-muted)] block font-normal">{{ $service->description }}</span>
                                                  @endif
                                              </label>
                                          </div>
                                          <span class="text-xs font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format((float)$service->price) }}</span>
                                      </div>
                                  @endif
                              @empty
                                  <p class="text-xs text-[var(--text-muted)] text-center py-2">No extras available.</p>
                              @endforelse
                          </div>
                      </div>
                  </div>

                  {{-- Divider --}}
                  <div class="border-t border-[var(--border-color)] my-4"></div>

                  {{-- Footer --}}
                  @if($isVacant)
                      @if($isCartEditMode)
                          <div class="flex gap-2">
                              <button type="button" wire:click="removeFromCart" 
                                      class="flex-1 text-center bg-[#9f2f2d] hover:bg-[#9f2f2d]/80 text-white font-semibold py-2.5 rounded text-xs transition-all cursor-pointer">
                                  Remove
                              </button>
                              <button type="button" wire:click="updateCart" 
                                      class="flex-1 text-center bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white font-semibold py-2.5 rounded text-xs transition-all cursor-pointer">
                                  Update Cart
                              </button>
                          </div>
                      @else
                          <button type="button" wire:click="addToCart" 
                                  class="block w-full text-center bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white font-semibold py-2.5 rounded text-xs transition-all cursor-pointer">
                              Add to Cart
                          </button>
                      @endif
                  @else
                      <button disabled class="w-full text-center bg-[var(--bg-secondary)] text-[var(--text-muted)] font-semibold py-2.5 rounded text-xs cursor-not-allowed border border-[var(--border-color)]">
                          Not Available
                      </button>
                  @endif
             </div>
        </div>
    @endif
</div>
