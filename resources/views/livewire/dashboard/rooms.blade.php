<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- ═══════════════════════════════════════
             LEFT: ROOMS
        ═══════════════════════════════════════ --}}
        <div class="space-y-6">
            {{-- Add Room Form --}}
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[var(--border-color)]">
                    <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">Add New Room</h3>
                </div>
                <div class="p-6">
                    <form wire:submit.prevent="addRoom" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="roomNumber">Room Number</label>
                            <input type="text" id="roomNumber" wire:model="roomNumber" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="e.g. 106, 501">
                            @error('roomNumber') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="roomTypeId">Room Type</label>
                            <select id="roomTypeId" wire:model="roomTypeId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                                <option value="">- Select type -</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} (Rp {{ number_format($type->price_per_night) }}/night)</option>
                                @endforeach
                            </select>
                            @error('roomTypeId') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="priceOverride">Custom Price Override <span class="text-[9px] text-[var(--text-muted)] normal-case font-medium ml-1">(optional)</span></label>
                            <input type="number" id="priceOverride" wire:model="priceOverride" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="Leave blank to use type price">
                            @error('priceOverride') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-all cursor-pointer active:scale-[0.98]" wire:loading.attr="disabled">
                            <span wire:loading.remove>Add Room</span>
                            <span wire:loading>Adding…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Rooms Table --}}
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[var(--border-color)] flex items-center justify-between">
                    <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">Hotel Rooms</h3>
                    <span class="text-xs font-semibold text-[var(--text-muted)]">{{ $rooms->count() }} rooms</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                                <th class="p-4">Room</th>
                                <th class="p-4">Type</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                            @forelse($rooms as $room)
                                <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                                    <td class="p-4">
                                        <span class="font-bold text-[var(--text-primary)] block">{{ $room->room_number }}</span>
                                        @if($room->price_per_night)
                                            <span class="text-[9px] text-[var(--warning)] font-semibold mt-0.5 block">Custom price</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-[var(--text-secondary)]">{{ $room->roomType->name }}</td>
                                    <td class="p-4">
                                        @php
                                            $statusBadges = [
                                                'available' => 'bg-[var(--success-bg)] text-[var(--success)] border border-[var(--border-color)]',
                                                'reserved' => 'bg-[var(--warning-bg)] text-[var(--warning)] border border-[var(--border-color)]',
                                                'occupied' => 'bg-[var(--danger-bg)] text-[var(--danger)] border border-[var(--border-color)]',
                                                'cleaning' => 'bg-[var(--info-bg)] text-[var(--info)] border border-[var(--border-color)]',
                                                'maintenance' => 'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border border-[var(--border-color)]',
                                            ];
                                        @endphp
                                        <span class="inline-flex rounded px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $statusBadges[$room->status] ?? 'bg-[var(--bg-secondary)] text-[var(--text-secondary)]' }}">
                                            {{ $room->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors cursor-pointer" wire:click="openEditRoomModal({{ $room->id }})" title="Edit">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--danger-bg)] hover:text-[var(--danger)] transition-colors cursor-pointer" wire:click="confirmDeleteRoom({{ $room->id }})" title="Delete">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-8 text-center text-[var(--text-muted)] font-medium">No rooms configured yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             RIGHT: ROOM TYPES
        ═══════════════════════════════════════ --}}
        <div class="space-y-6">
            {{-- Add Room Type Form --}}
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[var(--border-color)]">
                    <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">Add Room Type</h3>
                </div>
                <div class="p-6">
                    <form wire:submit.prevent="addRoomType" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="typeName">Type Name</label>
                            <input type="text" id="typeName" wire:model="typeName" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="e.g. Presidential Suite">
                            @error('typeName') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="typePrice">Base Price / Night (Rp)</label>
                            <input type="number" id="typePrice" wire:model="typePrice" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="150000">
                            @error('typePrice') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="typeBedType">Bed Size</label>
                                <select id="typeBedType" wire:model="typeBedType" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                                    <option value="Single">Single</option>
                                    <option value="Twin">Twin</option>
                                    <option value="Double">Double</option>
                                    <option value="Queen">Queen</option>
                                    <option value="King">King</option>
                                    <option value="Super King">Super King</option>
                                </select>
                                @error('typeBedType') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="typeCapacity">Capacity</label>
                                <input type="number" id="typeCapacity" wire:model="typeCapacity" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" min="1">
                                @error('typeCapacity') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex items-center gap-2 py-1 select-none cursor-pointer">
                            <input type="checkbox" id="typeHasBreakfast" wire:model="typeHasBreakfast" class="rounded border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-primary)] focus:ring-0 h-4 w-4 cursor-pointer">
                            <label class="text-[10px] font-bold text-[var(--text-secondary)] uppercase tracking-wider cursor-pointer" for="typeHasBreakfast">Includes Breakfast</label>
                            @error('typeHasBreakfast') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="typeDescription">Description</label>
                            <textarea id="typeDescription" wire:model="typeDescription" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all min-h-[70px] resize-y" placeholder="Brief amenities description…"></textarea>
                            @error('typeDescription') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-all cursor-pointer active:scale-[0.98]" wire:loading.attr="disabled">
                            <span wire:loading.remove>Create Type</span>
                            <span wire:loading>Creating…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Room Types Table --}}
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-[var(--border-color)] flex items-center justify-between">
                    <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">Room Types</h3>
                    <span class="text-xs font-semibold text-[var(--text-muted)]">{{ $roomTypes->count() }} types</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                                <th class="p-4">Type</th>
                                <th class="p-4">Base Price</th>
                                <th class="p-4">Rooms</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                            @forelse($roomTypes as $type)
                                <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                                    <td class="p-4">
                                        <div class="font-bold text-[var(--text-primary)]">{{ $type->name }}</div>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            <span class="inline-flex items-center rounded bg-[var(--bg-secondary)] px-1.5 py-0.5 text-[9px] font-semibold text-[var(--text-secondary)]">
                                                Bed: {{ $type->bed_type ?: 'N/A' }}
                                            </span>
                                            @if($type->has_breakfast)
                                                <span class="inline-flex items-center rounded bg-[var(--success-bg)] px-1.5 py-0.5 text-[9px] font-semibold text-[var(--success)]">
                                                    Breakfast Incl.
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded bg-[var(--bg-secondary)]/50 px-1.5 py-0.5 text-[9px] font-semibold text-[var(--text-muted)]">
                                                    No Breakfast
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center rounded bg-[var(--info-bg)] px-1.5 py-0.5 text-[9px] font-semibold text-[var(--info)]">
                                                Pax: {{ $type->capacity }}
                                            </span>
                                        </div>
                                        @if($type->description)
                                            <div class="text-[11px] text-[var(--text-muted)] mt-1.5 max-w-xs truncate" title="{{ $type->description }}">{{ $type->description }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 font-bold text-[var(--text-primary)] font-mono">Rp {{ number_format($type->price_per_night) }}</td>
                                    <td class="p-4">
                                        <span class="inline-flex rounded bg-[var(--info-bg)] px-2 py-0.5 text-[10px] font-semibold text-[var(--info)] font-mono">{{ $type->rooms_count }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors cursor-pointer" wire:click="openEditTypeModal({{ $type->id }})" title="Edit">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--danger-bg)] hover:text-[var(--danger)] transition-colors cursor-pointer" wire:click="confirmDeleteRoomType({{ $type->id }})" title="Delete">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-8 text-center text-[var(--text-muted)] font-medium">No room types yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════ MODALS ═══════ --}}

    {{-- Edit Room Modal --}}
    @if($showEditRoomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeEditRoomModal">
            <div class="w-full max-w-md rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-5">
                <div class="flex items-center gap-2 border-b border-[var(--border-color)] pb-3">
                    <svg class="h-4 w-4 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Edit Room</h3>
                </div>
                <form wire:submit.prevent="updateRoom" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Room Number</label>
                            <input type="text" wire:model="editRoomNumber" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                            @error('editRoomNumber') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Custom Price (opt.)</label>
                            <input type="number" wire:model="editPriceOverride" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="Type default">
                            @error('editPriceOverride') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Room Type</label>
                        <select wire:model="editRoomTypeId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('editRoomTypeId') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Room Status</label>
                        <select wire:model="editStatus" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="occupied">Occupied</option>
                            <option value="cleaning">Cleaning</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        @error('editStatus') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeEditRoomModal">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors cursor-pointer" wire:loading.attr="disabled">
                            <span wire:loading.remove>Save</span>
                            <span wire:loading>Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Room Confirm --}}
    @if($showDeleteRoomModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeDeleteRoomModal">
            <div class="w-full max-w-sm rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center gap-2 text-[var(--danger)] border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <h3 class="text-sm font-bold uppercase tracking-wider">Delete Room {{ $deletingRoomNumber }}?</h3>
                </div>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">This will permanently remove Room <strong class="text-[var(--text-primary)] font-bold">{{ $deletingRoomNumber }}</strong> from the system.</p>
                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                    <button class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeDeleteRoomModal">Cancel</button>
                    <button class="rounded bg-[#9f2f2d] hover:bg-[#9f2f2d]/80 px-4 py-2 text-xs font-semibold text-white transition-colors cursor-pointer" wire:click="deleteRoom({{ $deletingRoomId }})" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete Room</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Room Type Modal --}}
    @if($showEditTypeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeEditTypeModal">
            <div class="w-full max-w-md rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-5">
                <div class="flex items-center gap-2 border-b border-[var(--border-color)] pb-3">
                    <svg class="h-4 w-4 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Edit Room Type</h3>
                </div>
                <form wire:submit.prevent="updateRoomType" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Type Name</label>
                        <input type="text" wire:model="editTypeName" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                        @error('editTypeName') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Price / Night (Rp)</label>
                        <input type="number" wire:model="editTypePrice" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" min="0">
                        @error('editTypePrice') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Bed Size</label>
                            <select wire:model="editTypeBedType" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                                <option value="Single">Single</option>
                                <option value="Twin">Twin</option>
                                <option value="Double">Double</option>
                                <option value="Queen">Queen</option>
                                <option value="King">King</option>
                                <option value="Super King">Super King</option>
                            </select>
                            @error('editTypeBedType') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Capacity</label>
                            <input type="number" wire:model="editTypeCapacity" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" min="1">
                            @error('editTypeCapacity') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex items-center gap-2 py-1 select-none cursor-pointer">
                        <input type="checkbox" id="editTypeHasBreakfast" wire:model="editTypeHasBreakfast" class="rounded border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-primary)] focus:ring-0 h-4 w-4 cursor-pointer">
                        <label class="text-[10px] font-bold text-[var(--text-secondary)] uppercase tracking-wider cursor-pointer" for="editTypeHasBreakfast">Includes Breakfast</label>
                        @error('editTypeHasBreakfast') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Description</label>
                        <textarea wire:model="editTypeDescription" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all min-h-[70px] resize-y"></textarea>
                        @error('editTypeDescription') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeEditTypeModal">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors cursor-pointer" wire:loading.attr="disabled">
                            <span wire:loading.remove>Save</span>
                            <span wire:loading>Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Room Type Confirm --}}
    @if($showDeleteTypeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeDeleteTypeModal">
            <div class="w-full max-w-sm rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center gap-2 text-[var(--danger)] border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <h3 class="text-sm font-bold uppercase tracking-wider">Delete Room Type?</h3>
                </div>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed">Delete <strong class="text-[var(--text-primary)] font-bold">{{ $deletingTypeName }}</strong>? This action cannot be undone.</p>
                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                    <button class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeDeleteTypeModal">Cancel</button>
                    <button class="rounded bg-[#9f2f2d] hover:bg-[#9f2f2d]/80 px-4 py-2 text-xs font-semibold text-white transition-colors cursor-pointer" wire:click="deleteRoomType({{ $deletingTypeId }})" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
