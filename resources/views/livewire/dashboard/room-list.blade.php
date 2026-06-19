<div>
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">Room Availability</h1>
        <p class="text-xs text-[var(--text-muted)] mt-1">Real-time status board and reservations panel.</p>
    </div>

    {{-- KPI Counters --}}
    <div class="grid grid-cols-4 md:grid-cols-4 gap-6 mb-8">
        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 flex items-center justify-between shadow-sm transition-all duration-300">
            <div>
                <span class="text-2xl font-bold text-[var(--text-primary)] font-mono tracking-tight">{{ $rooms->count() }}</span>
                <p class="text-[10px] font-bold text-[var(--text-muted)] mt-1 uppercase tracking-wider">Total Rooms</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--bg-secondary)] flex items-center justify-center text-[var(--text-primary)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25" />
                </svg>
            </div>
        </div>

        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 flex items-center justify-between shadow-sm transition-all duration-300">
            <div>
                <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight">{{ $rooms->where('status', 'available')->count() }}</span>
                <p class="text-[10px] font-bold text-[var(--text-muted)] mt-1 uppercase tracking-wider">Available</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--success-bg)] flex items-center justify-center text-[var(--success)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 flex items-center justify-between shadow-sm transition-all duration-300">
            <div>
                <span class="text-2xl font-bold text-[var(--warning)] font-mono tracking-tight">{{ $rooms->where('status', 'reserved')->count() }}</span>
                <p class="text-[10px] font-bold text-[var(--text-muted)] mt-1 uppercase tracking-wider">Reserved</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--warning-bg)] flex items-center justify-center text-[var(--warning)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
        </div>

        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 flex items-center justify-between shadow-sm transition-all duration-300">
            <div>
                <span class="text-2xl font-bold text-[var(--danger)] font-mono tracking-tight">{{ $rooms->where('status', 'occupied')->count() }}</span>
                <p class="text-[10px] font-bold text-[var(--text-muted)] mt-1 uppercase tracking-wider">Occupied</p>
            </div>
            <div class="h-8 w-8 rounded bg-[var(--danger-bg)] flex items-center justify-center text-[var(--danger)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Filter / Search Bar --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-6 shadow-sm mb-8">
        <div class="grid grid-cols-4 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Room Type --}}
            <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Room Type</label>
                <select wire:model.live="filterType" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none cursor-pointer transition-all">
                    <option value="">All Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Status</label>
                <select wire:model.live="filterStatus" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none cursor-pointer transition-all">
                    <option value="">All Statuses</option>
                    <option value="available">Available</option>
                    <option value="reserved">Reserved</option>
                    <option value="occupied">Occupied</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            {{-- Bed Size Filter --}}
            <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Bed Size</label>
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
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Breakfast</label>
                <select wire:model.live="filterBreakfast" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none cursor-pointer transition-all">
                    <option value="">Either</option>
                    <option value="1">Included</option>
                    <option value="0">Not Included</option>
                </select>
            </div>
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
                <h2 class="text-xl font-bold text-[var(--text-primary)] uppercase tracking-tight">LANTAI {{ $floor }}</h2>
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
                        <span class="absolute top-2 left-2 text-[8px] font-bold uppercase px-1.5 py-0.5 rounded {{ $typeBadgeClass }}">
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
                        <span class="text-[9px] font-bold uppercase tracking-wider truncate w-full px-1 {{ $statusClass }}">
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

        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs"
             wire:click="$set('selectedRoomId', null)"
             x-data="{}"
             x-on:keydown.escape.window="$wire.set('selectedRoomId', null)">
             
             <div class="relative w-full max-w-sm rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg text-[var(--text-secondary)] mx-auto"
                  wire:click.stop="">
                  
                  {{-- Header row --}}
                  <div class="flex items-center justify-between mb-2">
                      <div class="flex items-center gap-2">
                          <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider {{ $typeBadgeClass }}">
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
                          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $statusBadgeClass }}">
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
                          <span class="text-[var(--text-muted)] font-medium">Description</span>
                          <p class="text-xs text-[var(--text-secondary)] leading-relaxed bg-[var(--bg-primary)] p-3 rounded border border-[var(--border-color)]">
                              {{ $selectedRoom->notes ?: ($selectedRoom->roomType->description ?: 'This room is clean, fully air-conditioned, and features high-speed internet, premium entertainment channels, a study desk, and safe lock access.') }}
                          </p>
                      </div>
                  </div>

                  {{-- Divider --}}
                  <div class="border-t border-[var(--border-color)] my-4"></div>

                  {{-- Footer --}}
                  @if($isVacant)
                      <a href="{{ route('booking.create', ['room_id' => $selectedRoom->id]) }}" 
                         class="block w-full text-center bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white font-semibold py-2.5 rounded text-xs transition-all cursor-pointer">
                          Book This Room
                      </a>
                  @else
                      <button disabled class="w-full text-center bg-[var(--bg-secondary)] text-[var(--text-muted)] font-semibold py-2.5 rounded text-xs cursor-not-allowed border border-[var(--border-color)]">
                          Not Available
                      </button>
                  @endif
             </div>
        </div>
    @endif
</div>
