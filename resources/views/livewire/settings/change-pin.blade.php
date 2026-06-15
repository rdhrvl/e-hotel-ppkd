<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h3 class="mb-2" style="font-size: 1.25rem;">Update Transaction PIN</h3>
    <p class="mb-6" style="font-size: 0.85rem; color: var(--text-muted);">Enter your current password to verify identity, then enter your new 6-digit PIN code.</p>

    <form wire:submit.prevent="updatePin">
        <div class="form-group">
            <label class="form-label" for="current_password">Current Password</label>
            <input type="password" id="current_password" wire:model="current_password" class="form-input" placeholder="••••••••">
            @error('current_password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="pin">New 6-Digit PIN</label>
            <input type="password" id="pin" wire:model="pin" class="form-input" placeholder="••••••" maxlength="6" pattern="[0-9]*" inputmode="numeric" style="text-align: center; font-size: 1.5rem; letter-spacing: 8px;">
            @error('pin') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="pin_confirmation">Confirm PIN</label>
            <input type="password" id="pin_confirmation" wire:model="pin_confirmation" class="form-input" placeholder="••••••" maxlength="6" pattern="[0-9]*" inputmode="numeric" style="text-align: center; font-size: 1.5rem; letter-spacing: 8px;">
            @error('pin_confirmation') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-between mt-6" style="gap: 12px;">
            <a href="{{ route('settings') }}" class="btn btn-secondary" style="flex: 1;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex: 1;" wire:loading.attr="disabled">
                <span wire:loading.remove>Update PIN</span>
                <span wire:loading>Updating...</span>
            </button>
        </div>
    </form>
</div>
