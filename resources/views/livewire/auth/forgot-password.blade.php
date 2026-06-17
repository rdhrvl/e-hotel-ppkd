<div>
    @if($sent)
        <div style="text-align: center; padding: 24px 0;">
            <svg width="56" height="56" fill="none" stroke="#00b894" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 16px; display: block;">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h2 class="mb-2" style="font-size: 1.25rem; color: #00b894;">Check your inbox!</h2>
            <p style="font-size: 0.85rem; line-height: 1.6;">
                We've sent a password reset link to<br>
                <strong style="color: var(--text-heading);">{{ $email }}</strong>
            </p>
            <p class="mt-4" style="font-size: 0.8rem; color: var(--text-muted);">
                Didn't receive it? Check your spam folder or
                <button wire:click="sendResetLink" type="button" style="background:none; border:none; color: var(--accent-primary); cursor:pointer; font-size: 0.8rem; font-weight:600; padding:0;">
                    resend
                </button>.
            </p>
        </div>
    @else
        <h2 class="mb-2" style="font-size: 1.5rem; text-align: center;">Forgot Password</h2>
        <p class="mb-6" style="text-align: center; font-size: 0.85rem; color: var(--text-muted);">
            Enter your email and we'll send a reset link.
        </p>

        <form wire:submit.prevent="sendResetLink">
            <div class="form-group">
                <label class="form-label" for="fp-email">Email Address</label>
                <input type="email" id="fp-email" wire:model="email" class="form-input"
                       placeholder="you@example.com" autofocus>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full mt-2" wire:loading.attr="disabled">
                <span wire:loading.remove>Send Reset Link</span>
                <span wire:loading>Sending...</span>
            </button>
        </form>
    @endif

    <div class="mt-6" style="text-align: center;">
        <a href="{{ route('login') }}" wire:navigate style="font-size: 0.85rem; font-weight: 500; color: var(--accent-primary); text-decoration: none;">
            ← Back to Sign In
        </a>
    </div>
</div>
