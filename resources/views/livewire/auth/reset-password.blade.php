<div>
    <h2 class="mb-2 text-xl font-bold text-center text-[var(--text-primary)]">Reset Password</h2>
    <p class="mb-6 text-center text-xs text-[var(--text-muted)]">
        Enter your new password below.
    </p>

    <form wire:submit.prevent="resetPassword" class="space-y-5">
        <div>
            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="rp-email">Email Address</label>
            <input type="email" id="rp-email" wire:model="email" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-secondary)] px-4 py-2.5 text-sm text-[var(--text-muted)] focus:outline-none cursor-not-allowed opacity-75"
                   placeholder="you@example.com" readonly>
            @error('email') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="rp-password">New Password</label>
            <input type="password" id="rp-password" wire:model="password" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2.5 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all"
                   placeholder="Min. 8 characters" autofocus>
            @error('password') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="rp-confirm">Confirm New Password</label>
            <input type="password" id="rp-confirm" wire:model="password_confirmation" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2.5 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all"
                   placeholder="Repeat your new password">
        </div>

        <button type="submit" class="w-full rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2.5 text-sm font-semibold text-[var(--bg-card)] transition-all duration-150 active:scale-[0.98] mt-2" wire:loading.attr="disabled">
            <span wire:loading.remove>Reset Password</span>
            <span wire:loading class="inline-flex items-center gap-2 justify-center">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Resetting...
            </span>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" wire:navigate class="text-xs font-semibold text-[var(--text-primary)] hover:underline">
            &larr; Back to Sign In
        </a>
    </div>
</div>
