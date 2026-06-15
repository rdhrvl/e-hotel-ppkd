<div>
    <h2 class="mb-2" style="font-size: 1.5rem; text-align: center;">Sign In</h2>
    <p class="mb-6" style="text-align: center; font-size: 0.85rem; color: var(--text-muted);">Access your delivery dashboard</p>

    <form wire:submit.prevent="login">
        <div class="form-group">
            <label class="form-label" for="phone">Phone Number</label>
            <input type="text" id="phone" wire:model="phone" class="form-input" placeholder="e.g., 081234567890" autofocus>
            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" wire:model="password" class="form-input" placeholder="••••••••">
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2" style="font-size: 0.85rem; cursor: pointer; user-select: none;">
                <input type="checkbox" wire:model="remember" style="accent-color: var(--accent-primary);">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" style="font-size: 0.85rem; font-weight: 500;">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled">
            <span wire:loading.remove>Sign In</span>
            <span wire:loading>Authenticating...</span>
        </button>
    </form>
</div>
