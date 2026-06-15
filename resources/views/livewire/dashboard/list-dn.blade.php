<div>
    <div class="mb-6 flex justify-between items-center" style="gap: 16px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px; position: relative;">
            <input type="text" wire:model.live="search" class="form-input" placeholder="Search by DN, origin, or destination..." style="padding-left: 40px;">
            <span style="position: absolute; left: 14px; top: 12px; font-size: 1.1rem; pointer-events: none; color: var(--text-muted);">🔍</span>
        </div>

        <div style="display: flex; gap: 8px;">
            <select wire:model.live="statusFilter" class="form-input" style="width: auto; padding: 10px 16px;">
                <option value="all">All Active Statuses</option>
                <option value="active">Active (Pending)</option>
                <option value="in_transit">In Transit</option>
            </select>
        </div>
    </div>

    <!-- Active DNs List -->
    @if($deliveryNotes->isEmpty())
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <span style="font-size: 3rem; display: block; margin-bottom: 16px;">🚛</span>
            <h4 style="margin-bottom: 8px;">No Active Delivery Notes</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">You do not have any active shipments assigned right now.</p>
        </div>
    @else
        <div class="flex flex-col gap-4">
            @foreach($deliveryNotes as $dn)
                <div class="card flex justify-between items-center" style="gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <div class="flex items-center gap-3">
                            <h4 style="margin: 0; font-size: 1.1rem; color: var(--text-primary);">{{ $dn->dn_number }}</h4>
                            <span class="badge {{ $dn->status === 'active' ? 'badge-active' : 'badge-transit' }}">
                                {{ $dn->status === 'active' ? 'Active' : 'In Transit' }}
                            </span>
                        </div>
                        <div class="mt-2" style="font-size: 0.9rem;">
                            <strong>Route:</strong> {{ $dn->origin }} ➔ {{ $dn->destination }}
                        </div>
                        <div class="mt-1" style="font-size: 0.8rem; color: var(--text-muted);">
                            {{ $dn->items_count }} items • Created {{ $dn->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; align-items: center;">
                        @if($dn->status === 'active')
                            <button wire:click="startTransit({{ $dn->id }})" class="btn btn-primary btn-sm" style="padding: 8px 16px; font-size: 0.8rem;">
                                Start Transit
                            </button>
                        @elseif($dn->status === 'in_transit')
                            <button wire:click="completeTransit({{ $dn->id }})" class="btn btn-success btn-sm" style="padding: 8px 16px; font-size: 0.8rem; color: #fff;">
                                Mark Completed
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $deliveryNotes->links() }}
        </div>
    @endif
</div>
