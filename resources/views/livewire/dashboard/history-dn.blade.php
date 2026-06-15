<div>
    <div class="mb-6 flex justify-between items-center" style="gap: 16px; flex-wrap: wrap;">
        <!-- Search bar -->
        <div style="flex: 1; min-width: 250px; position: relative;">
            <input type="text" wire:model.live="search" class="form-input" placeholder="Search history by DN, origin..." style="padding-left: 40px;">
            <span style="position: absolute; left: 14px; top: 12px; font-size: 1.1rem; pointer-events: none; color: var(--text-muted);">🔍</span>
        </div>

        <!-- Date filters -->
        <div class="flex items-center gap-2" style="flex-wrap: wrap;">
            <input type="date" wire:model.live="dateFrom" class="form-input" style="width: auto; max-width: 140px; padding: 10px;" placeholder="From">
            <span style="color: var(--text-muted);">to</span>
            <input type="date" wire:model.live="dateTo" class="form-input" style="width: auto; max-width: 140px; padding: 10px;" placeholder="To">
        </div>
    </div>

    <!-- History List -->
    @if($deliveryNotes->isEmpty())
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <span style="font-size: 3rem; display: block; margin-bottom: 16px;">📂</span>
            <h4 style="margin-bottom: 8px;">No Completed Deliveries</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Your completed delivery records will appear here.</p>
        </div>
    @else
        <div class="flex flex-col gap-4">
            @foreach($deliveryNotes as $dn)
                <div class="card flex justify-between items-center" style="gap: 16px; flex-wrap: wrap;">
                    <div>
                        <div class="flex items-center gap-3">
                            <h4 style="margin: 0; font-size: 1.1rem; color: var(--text-primary);">{{ $dn->dn_number }}</h4>
                            <span class="badge {{ $dn->status === 'completed' ? 'badge-completed' : 'badge-cancelled' }}">
                                {{ ucfirst($dn->status) }}
                            </span>
                        </div>
                        <div class="mt-2" style="font-size: 0.9rem;">
                            <strong>Route:</strong> {{ $dn->origin }} ➔ {{ $dn->destination }}
                        </div>
                        <div class="mt-1" style="font-size: 0.8rem; color: var(--text-muted);">
                            {{ $dn->items_count }} items • Completed {{ $dn->completed_at ? $dn->completed_at->format('M d, Y H:i') : '' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $deliveryNotes->links() }}
        </div>
    @endif
</div>
