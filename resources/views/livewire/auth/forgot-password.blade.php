<div>
    <h2 class="mb-2" style="font-size: 1.5rem; text-align: center;">Reset Password</h2>
    <p class="mb-6" style="text-align: center; font-size: 0.85rem; color: var(--text-muted);">Enter your email address to receive a password reset link</p>

    <form wire:submit.prevent="sendResetLink">
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input type="email" id="email" wire:model="email" class="form-input" placeholder="e.g., driver@company.com" autofocus>
            @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full mt-4" wire:loading.attr="disabled">
            <span wire:loading.remove>Send Link</span>
            <span wire:loading>Sending...</span>
        </button>

        <div style="text-align: center;" class="mt-6">
            <a href="{{ route('login') }}" style="font-size: 0.85rem; font-weight: 500;">Back to Login</a>
        </div>
    </form>
</div>
