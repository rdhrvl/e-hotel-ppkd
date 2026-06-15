<div>
    <div class="mb-6 flex justify-between items-center" style="gap: 16px; flex-wrap: wrap;">
        <!-- Filter tabs -->
        <div style="display: flex; gap: 8px;">
            <select wire:model.live="filterType" class="form-input" style="width: auto; padding: 10px 16px;">
                <option value="all">All Alerts</option>
                <option value="delivery_checkpoint">Deliveries</option>
                <option value="upload_success">Uploads</option>
                <option value="password_change">Password Updates</option>
                <option value="pin_change">PIN Updates</option>
            </select>
        </div>

        <div>
            <button wire:click="markAllAsRead" class="btn btn-secondary btn-sm" style="padding: 8px 16px; font-size: 0.8rem;">
                Mark All as Read
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    @if($notifications->isEmpty())
        <div class="card" style="text-align: center; padding: 60px 20px;">
            <span style="font-size: 3rem; display: block; margin-bottom: 16px;">🔔</span>
            <h4 style="margin-bottom: 8px;">All caught up!</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem;">You do not have any notifications at the moment.</p>
        </div>
    @else
        <div class="flex flex-col gap-3">
            @foreach($notifications as $notif)
                <div class="notification-card {{ !$notif->is_read ? 'unread' : '' }} type-{{ $notif->type }} flex justify-between items-start" style="gap: 16px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <span style="font-size: 1.25rem; margin-top: 2px;">
                            @if($notif->type === 'delivery_checkpoint') 🚚
                            @elseif($notif->type === 'upload_success') 📁
                            @elseif($notif->type === 'password_change') 🔑
                            @elseif($notif->type === 'pin_change') 🔒
                            @else 🔔
                            @endif
                        </span>
                        <div>
                            <h4 style="margin: 0; font-size: 0.95rem; font-weight: {{ !$notif->is_read ? '700' : '600' }}; color: var(--text-primary);">
                                {{ $notif->title }}
                            </h4>
                            <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: var(--text-secondary);">
                                {{ $notif->message }}
                            </p>
                            <span class="notification-time mt-1 block">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    @if(!$notif->is_read)
                        <button wire:click="markAsRead({{ $notif->id }})" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 0.75rem; border-color: rgba(255,255,255,0.1);">
                            Mark Read
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
