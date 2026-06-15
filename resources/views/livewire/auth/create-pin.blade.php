<div>
    <h2 class="mb-2" style="font-size: 1.5rem; text-align: center;">Set Up PIN</h2>
    <p class="mb-6" style="text-align: center; font-size: 0.85rem; color: var(--text-muted);">Configure a 6-digit secure transaction PIN</p>

    <form wire:submit.prevent="createPin">
        <div class="form-group">
            <label class="form-label" for="pin">New 6-Digit PIN</label>
            <input type="password" id="pin" wire:model="pin" class="form-input" placeholder="••••••" maxlength="6" pattern="[0-9]*" inputmode="numeric" style="text-align: center; font-size: 1.5rem; letter-spacing: 8px;" autofocus>
            @error('pin') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="pin_confirmation">Confirm PIN</label>
            <input type="password" id="pin_confirmation" wire:model="pin_confirmation" class="form-input" placeholder="••••••" maxlength="6" pattern="[0-9]*" inputmode="numeric" style="text-align: center; font-size: 1.5rem; letter-spacing: 8px;">
            @error('pin_confirmation') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full mt-4" wire:loading.attr="disabled">
            <span wire:loading.remove>Save & Continue</span>
            <span wire:loading>Saving...</span>
        </button>
    </form>
</div>
