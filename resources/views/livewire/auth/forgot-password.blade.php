<div>
    @if($sent)
        <div class="text-center py-6">
            <svg class="w-14 h-14 mx-auto mb-4 text-[var(--success)] stroke-current" fill="none" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h2 class="mb-2 text-xl font-bold text-[var(--success)]">Check your inbox!</h2>
            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                We've sent a password reset link to<br>
                <strong class="text-[var(--text-primary)] font-semibold">{{ $email }}</strong>
            </p>
            <p class="mt-4 text-xs text-[var(--text-muted)]">
                Didn't receive it? Check your spam folder or
                <button wire:click="sendResetLink" type="button" class="bg-transparent border-none text-[var(--text-primary)] hover:underline cursor-pointer font-semibold transition-colors">
                    resend
                </button>.
            </p>
        </div>
    @else
        <h2 class="mb-2 text-xl font-bold text-center text-[var(--text-primary)]">Forgot Password</h2>
        <p class="mb-6 text-center text-xs text-[var(--text-muted)]">
            Enter your email and we'll send a reset link.
        </p>

        <form wire:submit.prevent="sendResetLink" class="space-y-5">
            <div>
                <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="fp-email">Email Address</label>
                <input type="email" id="fp-email" wire:model="email" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2.5 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all"
                       placeholder="you@example.com" autofocus>
                @error('email') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2.5 text-sm font-semibold text-[var(--bg-card)] transition-all duration-150 active:scale-[0.98] mt-2" wire:loading.attr="disabled">
                <span wire:loading.remove>Send Reset Link</span>
                <span wire:loading class="inline-flex items-center gap-2 justify-center">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </form>
    @endif

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" wire:navigate class="text-xs font-semibold text-[var(--text-primary)] hover:underline">
            &larr; Back to Sign In
        </a>
    </div>
</div>
