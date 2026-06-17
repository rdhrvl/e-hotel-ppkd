<div>
    <style>
        .cfg-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        @media (max-width: 768px) { .cfg-grid { grid-template-columns: 1fr; } }

        .cfg-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
        }
        .cfg-card-header {
            padding: 20px 24px 0;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
        }
        .cfg-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--text-heading); }

        /* Inline add form */
        .add-form { padding: 0 24px 24px; }
        .add-form .form-group { margin-bottom: 12px; }
        .add-form .form-label { display:block; font-size:0.78rem; font-weight:600; color:var(--text-body); margin-bottom:5px; }
        .add-form .form-input { width:100%; padding:9px 12px; background:var(--bg-input); border:1px solid var(--border-color); border-radius:9px; color:var(--text-heading); font-size:0.85rem; font-family:'Inter',sans-serif; transition:border-color 0.2s; }
        .add-form .form-input:focus { outline:none; border-color:var(--accent-primary); }
        .add-form .form-error { display:block; font-size:0.72rem; color:var(--accent-danger); margin-top:3px; }

        /* Table */
        .cfg-table { width:100%; border-collapse:collapse; }
        .cfg-table th { padding:10px 16px; text-align:left; font-size:0.68rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:var(--text-body); border-bottom:1px solid var(--border-color); }
        .cfg-table td { padding:13px 16px; font-size:0.83rem; border-bottom:1px solid rgba(45,45,68,0.4); color:var(--text-body); vertical-align:middle; }
        .cfg-table tr:last-child td { border-bottom: none; }
        .cfg-table tr:hover td { background:rgba(108,92,231,0.03); }

        /* Buttons */
        .btn { padding:9px 18px; border-radius:9px; font-size:0.83rem; font-weight:600; cursor:pointer; border:none; transition:all 0.2s; font-family:'Inter',sans-serif; display:inline-flex; align-items:center; justify-content:center; gap:6px; }
        .btn-primary { background:linear-gradient(135deg, #6c5ce7, #a29bfe); color:#fff; }
        .btn-primary:hover { opacity:0.9; }
        .btn-primary:disabled { opacity:0.6; cursor:not-allowed; }
        .btn-ghost { background:transparent; border:1px solid var(--border-color); color:var(--text-body); }
        .btn-ghost:hover { border-color:var(--accent-primary); color:var(--accent-primary); }
        .btn-danger { background:linear-gradient(135deg, #e17055, #d63031); color:#fff; }
        .btn-icon { background:none; border:none; cursor:pointer; padding:5px; border-radius:7px; transition:all 0.2s; display:inline-flex; align-items:center; color:var(--text-body); }
        .btn-icon:hover { background:rgba(108,92,231,0.1); color:var(--accent-primary); }
        .btn-icon.danger:hover { background:rgba(225,112,85,0.1); color:var(--accent-danger); }

        /* Status badges */
        .status-pill { display:inline-block; padding:2px 9px; border-radius:20px; font-size:0.68rem; font-weight:600; text-transform:uppercase; letter-spacing:0.4px; }
        .pill-available { background:rgba(0,184,148,0.12); color:var(--accent-success); }
        .pill-booked { background:rgba(108,92,231,0.12); color:var(--accent-primary); }
        .pill-occupied { background:rgba(253,203,110,0.12); color:var(--accent-warning); }
        .pill-clean { background:rgba(0,184,148,0.12); color:var(--accent-success); }
        .pill-dirty { background:rgba(225,112,85,0.12); color:var(--accent-danger); }
        .pill-maintenance { background:rgba(253,203,110,0.12); color:var(--accent-warning); }

        /* Modal */
        .modal-backdrop { position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); z-index:200; display:flex; align-items:center; justify-content:center; padding:20px; }
        .modal-box { background:var(--bg-card); border:1px solid var(--border-color); border-radius:20px; padding:28px; width:100%; max-width:440px; box-shadow:0 24px 64px rgba(0,0,0,0.5); animation:mIn 0.22s cubic-bezier(.4,0,.2,1); }
        @keyframes mIn { from { transform:scale(0.95); opacity:0; } to { transform:scale(1); opacity:1; } }
        .modal-title { font-size:1rem; font-weight:700; color:var(--text-heading); margin-bottom:18px; display:flex; align-items:center; gap:9px; }
        .modal-title.danger { color:var(--accent-danger); }
        .modal-actions { display:flex; gap:10px; margin-top:20px; }
        .modal-actions .btn { flex:1; }
        .modal-form-group { margin-bottom:13px; }
        .modal-label { display:block; font-size:0.78rem; font-weight:600; color:var(--text-body); margin-bottom:5px; }
        .modal-input { width:100%; padding:9px 12px; background:var(--bg-input); border:1px solid var(--border-color); border-radius:9px; color:var(--text-heading); font-size:0.85rem; font-family:'Inter',sans-serif; transition:border-color 0.2s; }
        .modal-input:focus { outline:none; border-color:var(--accent-primary); }
        .modal-form-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .empty-row td { text-align:center; padding:32px; color:var(--text-body); opacity:0.5; }
    </style>

    <div class="cfg-grid">

        {{-- ═══════════════════════════════════════
             LEFT: ROOMS
        ═══════════════════════════════════════ --}}
        <div>
            {{-- Add Room Form --}}
            <div class="cfg-card mb-6" style="margin-bottom:20px;">
                <div class="cfg-card-header">
                    <h3>Add New Room</h3>
                </div>
                <div class="add-form">
                    <form wire:submit.prevent="addRoom">
                        <div class="form-group">
                            <label class="form-label" for="roomNumber">Room Number</label>
                            <input type="text" id="roomNumber" wire:model="roomNumber" class="form-input" placeholder="e.g. 106, 501">
                            @error('roomNumber') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="roomTypeId">Room Type</label>
                            <select id="roomTypeId" wire:model="roomTypeId" class="form-input">
                                <option value="">— Select type —</option>
                                @foreach($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} (Rp {{ number_format($type->price_per_night) }}/night)</option>
                                @endforeach
                            </select>
                            @error('roomTypeId') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="priceOverride">Custom Price Override <span style="font-weight:400; opacity:0.6;">(optional)</span></label>
                            <input type="number" id="priceOverride" wire:model="priceOverride" class="form-input" placeholder="Leave blank to use type price">
                            @error('priceOverride') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Add Room</span>
                            <span wire:loading>Adding…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Rooms Table --}}
            <div class="cfg-card">
                <div class="cfg-card-header">
                    <h3>Hotel Rooms</h3>
                    <span style="font-size:0.8rem; color:var(--text-body);">{{ $rooms->count() }} rooms</span>
                </div>
                <table class="cfg-table">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr>
                                <td>
                                    <span style="font-weight:700; color:var(--text-heading);">{{ $room->room_number }}</span>
                                    @if($room->price_per_night)
                                        <span style="font-size:0.7rem; color:var(--accent-warning); display:block;">Custom price</span>
                                    @endif
                                </td>
                                <td>{{ $room->roomType->name }}</td>
                                <td>
                                    <span class="status-pill pill-{{ $room->booking_status }}">{{ ucfirst($room->booking_status) }}</span>
                                    <span class="status-pill pill-{{ $room->cleaning_status }}" style="margin-left:4px;">{{ ucfirst($room->cleaning_status) }}</span>
                                </td>
                                <td style="text-align:right;">
                                    <button class="btn-icon" wire:click="openEditRoomModal({{ $room->id }})" title="Edit">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button class="btn-icon danger" wire:click="confirmDeleteRoom({{ $room->id }})" title="Delete">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-row">No rooms configured yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             RIGHT: ROOM TYPES
        ═══════════════════════════════════════ --}}
        <div>
            {{-- Add Room Type Form --}}
            <div class="cfg-card" style="margin-bottom:20px;">
                <div class="cfg-card-header">
                    <h3>Add Room Type</h3>
                </div>
                <div class="add-form">
                    <form wire:submit.prevent="addRoomType">
                        <div class="form-group">
                            <label class="form-label" for="typeName">Type Name</label>
                            <input type="text" id="typeName" wire:model="typeName" class="form-input" placeholder="e.g. Presidential Suite">
                            @error('typeName') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="typePrice">Base Price / Night (Rp)</label>
                            <input type="number" id="typePrice" wire:model="typePrice" class="form-input" placeholder="150000">
                            @error('typePrice') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="typeDescription">Description</label>
                            <textarea id="typeDescription" wire:model="typeDescription" class="form-input" style="min-height:70px; resize:vertical; font-family:inherit;" placeholder="Brief amenities description…"></textarea>
                            @error('typeDescription') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Create Type</span>
                            <span wire:loading>Creating…</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Room Types Table --}}
            <div class="cfg-card">
                <div class="cfg-card-header">
                    <h3>Room Types</h3>
                    <span style="font-size:0.8rem; color:var(--text-body);">{{ $roomTypes->count() }} types</span>
                </div>
                <table class="cfg-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Base Price</th>
                            <th>Rooms</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roomTypes as $type)
                            <tr>
                                <td>
                                    <div style="font-weight:600; color:var(--text-heading);">{{ $type->name }}</div>
                                    @if($type->description)
                                        <div style="font-size:0.72rem; margin-top:3px; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $type->description }}</div>
                                    @endif
                                </td>
                                <td style="font-weight:600; color:var(--text-heading); white-space:nowrap;">Rp {{ number_format($type->price_per_night) }}</td>
                                <td style="font-weight:600; color:var(--text-heading);">{{ $type->rooms_count }}</td>
                                <td style="text-align:right;">
                                    <button class="btn-icon" wire:click="openEditTypeModal({{ $type->id }})" title="Edit">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button class="btn-icon danger" wire:click="confirmDeleteRoomType({{ $type->id }})" title="Delete">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty-row">No room types yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════ MODALS ═══════ --}}

    {{-- Edit Room Modal --}}
    @if($showEditRoomModal)
        <div class="modal-backdrop" wire:click.self="closeEditRoomModal">
            <div class="modal-box">
                <div class="modal-title">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Room
                </div>
                <form wire:submit.prevent="updateRoom">
                    <div class="modal-form-row">
                        <div class="modal-form-group">
                            <label class="modal-label">Room Number</label>
                            <input type="text" wire:model="editRoomNumber" class="modal-input">
                            @error('editRoomNumber') <span style="font-size:0.72rem; color:var(--accent-danger);">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-form-group">
                            <label class="modal-label">Custom Price (opt.)</label>
                            <input type="number" wire:model="editPriceOverride" class="modal-input" placeholder="Leave blank = type default">
                            @error('editPriceOverride') <span style="font-size:0.72rem; color:var(--accent-danger);">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Room Type</label>
                        <select wire:model="editRoomTypeId" class="modal-input">
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('editRoomTypeId') <span style="font-size:0.72rem; color:var(--accent-danger);">{{ $message }}</span> @enderror
                    </div>
                    <div class="modal-form-row">
                        <div class="modal-form-group">
                            <label class="modal-label">Booking Status</label>
                            <select wire:model="editBookingStatus" class="modal-input">
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                                <option value="occupied">Occupied</option>
                            </select>
                        </div>
                        <div class="modal-form-group">
                            <label class="modal-label">Cleaning Status</label>
                            <select wire:model="editCleaningStatus" class="modal-input">
                                <option value="clean">Clean</option>
                                <option value="dirty">Dirty</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-ghost" wire:click="closeEditRoomModal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
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
        <div class="modal-backdrop" wire:click.self="closeDeleteRoomModal">
            <div class="modal-box">
                <div class="modal-title danger">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Delete Room {{ $deletingRoomNumber }}?
                </div>
                <p style="font-size:0.88rem; line-height:1.6;">This will permanently remove Room <strong style="color:var(--text-heading);">{{ $deletingRoomNumber }}</strong> from the system.</p>
                <div class="modal-actions">
                    <button class="btn btn-ghost" wire:click="closeDeleteRoomModal">Cancel</button>
                    <button class="btn btn-danger" wire:click="deleteRoom({{ $deletingRoomId }})" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete Room</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Room Type Modal --}}
    @if($showEditTypeModal)
        <div class="modal-backdrop" wire:click.self="closeEditTypeModal">
            <div class="modal-box">
                <div class="modal-title">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Room Type
                </div>
                <form wire:submit.prevent="updateRoomType">
                    <div class="modal-form-group">
                        <label class="modal-label">Type Name</label>
                        <input type="text" wire:model="editTypeName" class="modal-input">
                        @error('editTypeName') <span style="font-size:0.72rem; color:var(--accent-danger);">{{ $message }}</span> @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Price / Night (Rp)</label>
                        <input type="number" wire:model="editTypePrice" class="modal-input" min="0">
                        @error('editTypePrice') <span style="font-size:0.72rem; color:var(--accent-danger);">{{ $message }}</span> @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label">Description</label>
                        <textarea wire:model="editTypeDescription" class="modal-input" style="min-height:70px; resize:vertical; font-family:inherit;"></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-ghost" wire:click="closeEditTypeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
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
        <div class="modal-backdrop" wire:click.self="closeDeleteTypeModal">
            <div class="modal-box">
                <div class="modal-title danger">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Delete Room Type?
                </div>
                <p style="font-size:0.88rem; line-height:1.6;">Delete <strong style="color:var(--text-heading);">{{ $deletingTypeName }}</strong>? This action cannot be undone.</p>
                <div class="modal-actions">
                    <button class="btn btn-ghost" wire:click="closeDeleteTypeModal">Cancel</button>
                    <button class="btn btn-danger" wire:click="deleteRoomType({{ $deletingTypeId }})" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
