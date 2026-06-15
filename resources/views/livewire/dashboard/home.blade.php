<div>
    <div class="mb-6">
        <h3 style="font-size: 1.5rem; color: var(--text-primary);">Welcome back, {{ auth()->user()->name ?: 'User' }}</h3>
        <p style="font-size: 0.875rem; color: var(--text-muted);">Here is your delivery summary for today</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $this->activeDnCount }}</div>
                <div class="stat-label">Active Delivery Notes</div>
            </div>
            <div class="stat-icon icon-blue">
                🚚
            </div>
        </div>

        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $this->completedDnCount }}</div>
                <div class="stat-label">Completed Deliveries</div>
            </div>
            <div class="stat-icon icon-green">
                ✅
            </div>
        </div>

        <div class="card card-stat">
            <div>
                <div class="stat-number">{{ $this->unreadNotifications }}</div>
                <div class="stat-label">Unread Messages</div>
            </div>
            <div class="stat-icon icon-orange">
                🔔
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <h4 class="mb-4" style="font-size: 1.1rem; color: var(--text-primary);">Quick Actions</h4>
    <div class="grid grid-cols-2 gap-6">
        <a href="{{ route('delivery-notes') }}" class="card flex items-center gap-4" style="text-decoration: none; padding: 20px;">
            <span style="font-size: 2rem;">📦</span>
            <div>
                <h5 style="margin: 0; font-size: 1rem; color: var(--text-primary);">View Active DNs</h5>
                <p style="margin: 4px 0 0 0; font-size: 0.8rem; color: var(--text-muted);">Manage current shipments and checkpoints</p>
            </div>
        </a>

        <a href="{{ route('settings') }}" class="card flex items-center gap-4" style="text-decoration: none; padding: 20px;">
            <span style="font-size: 2rem;">⚙️</span>
            <div>
                <h5 style="margin: 0; font-size: 1rem; color: var(--text-primary);">Account Settings</h5>
                <p style="margin: 4px 0 0 0; font-size: 0.8rem; color: var(--text-muted);">Manage PIN, password, signatures and stamps</p>
            </div>
        </a>
    </div>
</div>
