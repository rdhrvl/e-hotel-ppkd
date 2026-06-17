<div>
    <style>
        .service-type-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .type-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .type-extra_bed { background: rgba(108,92,231,0.15); color: var(--accent-primary); }
        .type-f_and_b   { background: rgba(0,184,148,0.15);  color: var(--accent-success); }
        .type-laundry   { background: rgba(116,185,255,0.15); color: #74b9ff; }
        .type-general   { background: rgba(160,160,184,0.15); color: var(--text-body); }

        /* reuse admin-header, panel-card, data-table, modal, btn from user management styles */
        .admin-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
        .admin-header h3 { font-size:1.1rem; font-weight:700; color:var(--text-heading); }
        .search-box { position:relative; flex:1; min-width:200px; max-width:320px; }
        .search-box input { width:100%; padding:10px 10px 10px 38px; background:var(--bg-input); border:1px solid var(--border-color); border-radius:10px; color:var(--text-heading); font-size:0.85rem; font-family:'Inter',sans-serif; transition:border-color 0.2s; }
        .search-box input:focus { outline:none; border-color:var(--accent-primary); }
        .search-box svg { position:absolute; left:10px; top:50%; transform:translateY(-50%); opacity:0.4; }
        .panel-card { background:var(--bg-card); border:1px solid var(--border-color); border-radius:16px; overflow:hidden; }
        .data-table { width:100%; border-collapse:collapse; }
        .data-table th { padding:12px 16px; text-align:left; font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:var(--text-body); border-bottom:1px solid var(--border-color); }
        .data-table td { padding:14px 16px; font-size:0.85rem; border-bottom:1px solid rgba(45,45,68,0.5); color:var(--text-body); vertical-align:middle; }
        .data-table tr:hover td { background:rgba(108,92,231,0.03); }
        .btn-icon { background:none; border:none; cursor:pointer; padding:6px; border-radius:8px; transition:all 0.2s; display:inline-flex; align-items:center; }
        .btn-icon:hover { background:rgba(108,92,231,0.1); }
        .btn-icon.danger:hover { background:rgba(225,112,85,0.1); color:var(--accent-danger); }
        .modal-backdrop { position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); z-index:200; display:flex; align-items:center; justify-content:center; padding:20px; }
        .modal-box { background:var(--bg-card); border:1px solid var(--border-color); border-radius:20px; padding:32px; width:100%; max-width:480px; box-shadow:0 24px 64px rgba(0,0,0,0.5); animation:modalIn 0.25s cubic-bezier(.4,0,.2,1); }
        @keyframes modalIn { from { transform:scale(0.95); opacity:0; } to { transform:scale(1); opacity:1; } }
        .modal-title { font-size:1.1rem; font-weight:700; color:var(--text-heading); margin-bottom:20px; display:flex; align-items:center; gap:10px; }
        .modal-actions { display:flex; gap:10px; margin-top:24px; }
        .modal-actions .btn { flex:1; }
        .form-group { margin-bottom:16px; }
        .form-label { display:block; font-size:0.8rem; font-weight:600; color:var(--text-body); margin-bottom:6px; letter-spacing:0.3px; }
        .form-input { width:100%; padding:10px 14px; background:var(--bg-input); border:1px solid var(--border-color); border-radius:10px; color:var(--text-heading); font-size:0.875rem; font-family:'Inter',sans-serif; transition:border-color 0.2s; }
        .form-input:focus { outline:none; border-color:var(--accent-primary); }
        .form-error { display:block; font-size:0.75rem; color:var(--accent-danger); margin-top:4px; }
        .btn { padding:10px 20px; border-radius:10px; font-size:0.85rem; font-weight:600; cursor:pointer; border:none; transition:all 0.2s; font-family:'Inter',sans-serif; display:inline-flex; align-items:center; justify-content:center; gap:6px; }
        .btn-primary { background:linear-gradient(135deg, #6c5ce7, #a29bfe); color:#fff; }
        .btn-primary:hover { opacity:0.9; transform:translateY(-1px); }
        .btn-primary:disabled { opacity:0.6; transform:none; cursor:not-allowed; }
        .btn-ghost { background:transparent; border:1px solid var(--border-color); color:var(--text-body); }
        .btn-ghost:hover { border-color:var(--accent-primary); color:var(--accent-primary); }
        .btn-danger { background:linear-gradient(135deg, #e17055, #d63031); color:#fff; }
        .empty-state { text-align:center; padding:60px 20px; color:var(--text-body); }
        .empty-state svg { margin:0 auto 16px; display:block; opacity:0.3; }
    </style>

    {{-- Header --}}
    <div class="admin-header">
        <div>
            <h3>Service Management</h3>
            <p style="font-size:0.8rem; margin-top:2px;">Manage hotel services and pricing</p>
        </div>
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <div class="search-box">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search services…">
            </div>
            <button class="btn btn-primary" wire:click="openAddModal" id="btn-add-service">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Service
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="panel-card">
        @if($services->isEmpty())
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                <p>No services found.</p>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Type</th>
                        <th>Price / unit</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        @php
                            $icons = ['extra_bed'=>'🛏️','f_and_b'=>'🍽️','laundry'=>'👕','general'=>'⭐'];
                            $bgMap = ['extra_bed'=>'rgba(108,92,231,0.12)','f_and_b'=>'rgba(0,184,148,0.12)','laundry'=>'rgba(116,185,255,0.12)','general'=>'rgba(160,160,184,0.1)'];
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div class="service-type-icon" style="background:{{ $bgMap[$service->type] ?? $bgMap['general'] }};">
                                        {{ $icons[$service->type] ?? '⭐' }}
                                    </div>
                                    <span style="font-weight:600; color:var(--text-heading);">{{ $service->name }}</span>
                                </div>
                            </td>
                            <td><span class="type-badge type-{{ $service->type }}">{{ $serviceTypes[$service->type] ?? $service->type }}</span></td>
                            <td style="font-weight:600; color:var(--text-heading);">Rp {{ number_format((float)$service->price, 0, ',', '.') }}</td>
                            <td style="text-align:right;">
                                <button class="btn-icon" wire:click="openEditModal({{ $service->id }})" title="Edit service">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button class="btn-icon danger" wire:click="confirmDelete({{ $service->id }})" title="Delete service">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- ── ADD SERVICE MODAL ── --}}
    @if($showAddModal)
        <div class="modal-backdrop" wire:click.self="closeAddModal">
            <div class="modal-box">
                <div class="modal-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M12 12v4M10 14h4"/></svg>
                    Add New Service
                </div>
                <form wire:submit.prevent="createService">
                    <div class="form-group">
                        <label class="form-label">Service Name</label>
                        <input type="text" wire:model="name" class="form-input" placeholder="e.g. Breakfast Buffet" autofocus>
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select wire:model="type" class="form-input">
                                @foreach($serviceTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Price (Rp)</label>
                            <input type="number" wire:model="price" class="form-input" placeholder="50000" min="0">
                            @error('price') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-ghost" wire:click="closeAddModal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Add Service</span>
                            <span wire:loading>Adding…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── EDIT SERVICE MODAL ── --}}
    @if($showEditModal)
        <div class="modal-backdrop" wire:click.self="closeEditModal">
            <div class="modal-box">
                <div class="modal-title">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Service
                </div>
                <form wire:submit.prevent="updateService">
                    <div class="form-group">
                        <label class="form-label">Service Name</label>
                        <input type="text" wire:model="editName" class="form-input">
                        @error('editName') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select wire:model="editType" class="form-input">
                                @foreach($serviceTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('editType') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Price (Rp)</label>
                            <input type="number" wire:model="editPrice" class="form-input" min="0">
                            @error('editPrice') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-ghost" wire:click="closeEditModal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Save Changes</span>
                            <span wire:loading>Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── DELETE CONFIRM ── --}}
    @if($showDeleteModal)
        <div class="modal-backdrop" wire:click.self="closeDeleteModal">
            <div class="modal-box" style="max-width:400px;">
                <div class="modal-title" style="color:var(--accent-danger);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Confirm Delete
                </div>
                <p style="font-size:0.9rem; line-height:1.6;">
                    Are you sure you want to delete <strong style="color:var(--text-heading);">{{ $deletingServiceName }}</strong>?
                    This action cannot be undone.
                </p>
                <div class="modal-actions">
                    <button class="btn btn-ghost" wire:click="closeDeleteModal">Cancel</button>
                    <button class="btn btn-danger" wire:click="deleteService" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
