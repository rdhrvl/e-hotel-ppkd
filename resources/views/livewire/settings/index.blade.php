<div>
    <div class="mb-6">
        <h3 style="font-size: 1.5rem; color: var(--text-primary);">Account Settings</h3>
        <p style="font-size: 0.875rem; color: var(--text-muted);">Manage your credentials and upload digital stamp / signature files.</p>
    </div>

    <div class="settings-list">
        <!-- Change Password -->
        <a href="{{ route('settings.password') }}" class="settings-item">
            <div class="settings-info">
                <div class="settings-icon">🔑</div>
                <div>
                    <h4 style="margin: 0; font-size: 1rem; color: var(--text-primary);">Update Password</h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--text-muted);">Change your login password code</p>
                </div>
            </div>
            <span style="color: var(--text-muted);">➔</span>
        </a>

        <!-- Change PIN -->
        <a href="{{ route('settings.pin') }}" class="settings-item">
            <div class="settings-info">
                <div class="settings-icon">🔒</div>
                <div>
                    <h4 style="margin: 0; font-size: 1rem; color: var(--text-primary);">Update Transaction PIN</h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--text-muted);">Reset your 6-digit transaction PIN code</p>
                </div>
            </div>
            <span style="color: var(--text-muted);">➔</span>
        </a>

        <!-- Upload E-Signature -->
        <a href="{{ route('settings.upload-sign') }}" class="settings-item">
            <div class="settings-info">
                <div class="settings-icon">🖋️</div>
                <div>
                    <h4 style="margin: 0; font-size: 1rem; color: var(--text-primary);">Digital Signature (E-Sign)</h4>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--text-muted);">Upload or update your electronic signature image</p>
                </div>
            </div>
            <span style="color: var(--text-muted);">➔</span>
        </a>

        <!-- Upload E-Stamp (Warehouse only) -->
        @if(auth()->user()->isWarehouse())
            <a href="{{ route('settings.upload-stamp') }}" class="settings-item">
                <div class="settings-info">
                    <div class="settings-icon">stamp</div>
                    <div>
                        <h4 style="margin: 0; font-size: 1rem; color: var(--text-primary);">Digital Stamp (E-Stamp)</h4>
                        <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--text-muted);">Upload your warehouse verification stamp file</p>
                    </div>
                </div>
                <span style="color: var(--text-muted);">➔</span>
            </a>
        @endif
    </div>
</div>
