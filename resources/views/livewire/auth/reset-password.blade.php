<div>
    <h2 class="mb-2" style="font-size: 1.5rem; text-align: center;">Reset Password</h2>
    <p class="mb-6" style="text-align: center; font-size: 0.85rem; color: var(--text-muted);">
        Enter your new password below.
    </p>

    <form wire:submit.prevent="resetPassword">
        <div class="form-group">
            <label class="form-label" for="rp-email">Email Address</label>
            <input type="email" id="rp-email" wire:model="email" class="form-input"
                   placeholder="you@example.com" readonly style="opacity: 0.7; cursor: not-allowed;">
            @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="rp-password">New Password</label>
            <input type="password" id="rp-password" wire:model="password" class="form-input"
                   placeholder="Min. 8 characters" autofocus>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="rp-confirm">Confirm New Password</label>
            <input type="password" id="rp-confirm" wire:model="password_confirmation" class="form-input"
                   placeholder="Repeat your new password">
        </div>

        <button type="submit" class="btn btn-primary w-full mt-2" wire:loading.attr="disabled">
            <span wire:loading.remove>Reset Password</span>
            <span wire:loading>Resetting...</span>
        </button>
    </form>

    <div class="mt-6" style="text-align: center;">
        <a href="{{ route('login') }}" wire:navigate style="font-size: 0.85rem; font-weight: 500; color: var(--accent-primary); text-decoration: none;">
            ← Back to Sign In
        </a>
    </div>
</div>
