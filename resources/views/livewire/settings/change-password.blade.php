<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h3 class="mb-2" style="font-size: 1.25rem;">Change Password</h3>
    <p class="mb-6" style="font-size: 0.85rem; color: var(--text-muted);">Ensure your account stays secure by updating your password regularly.</p>

    <form wire:submit.prevent="updatePassword">
        <div class="form-group">
            <label class="form-label" for="current_password">Current Password</label>
            <input type="password" id="current_password" wire:model="current_password" class="form-input" placeholder="••••••••">
            @error('current_password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
            <input type="password" id="password" wire:model="password" class="form-input" placeholder="••••••••">
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation" class="form-input" placeholder="••••••••">
            @error('password_confirmation') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-between mt-6" style="gap: 12px;">
            <a href="{{ route('settings') }}" class="btn btn-secondary" style="flex: 1;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex: 1;" wire:loading.attr="disabled">
                <span wire:loading.remove>Update Password</span>
                <span wire:loading>Updating...</span>
            </button>
        </div>
    </form>
</div>
