<div>
    <h2 class="mb-1 text-xl font-bold text-center text-[var(--text-primary)]">Welcome Back</h2>
    <p class="mb-6 text-center text-xs text-[var(--text-muted)]">Sign in to access your dashboard</p>

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="email">Email Address</label>
            <input type="email" id="email" name="email" wire:model="email" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2.5 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="e.g., admin@example.com" autofocus required>
            @error('email') 
                <span class="text-xs text-[var(--danger)] mt-1.5 flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    {{ $message }}
                </span> 
            @enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider" for="password">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" wire:model="password" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] pl-4 pr-10 py-2.5 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="••••••••" required>
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-[var(--text-muted)] hover:text-[var(--text-primary)] focus:outline-none" aria-label="Toggle password visibility">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-3.95-3.95l-2.5-2.5m-2.52-2.52L9 9m3 3a3 3 0 103 3" />
                    </svg>
                </button>
            </div>
            @error('password') 
                <span class="text-xs text-[var(--danger)] mt-1.5 flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    {{ $message }}
                </span> 
            @enderror
        </div>

        <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 text-[var(--text-secondary)] cursor-pointer select-none">
                <input type="checkbox" id="remember" name="remember" wire:model="remember" class="rounded text-[var(--text-primary)] focus:ring-0 bg-[var(--bg-card)] border-[var(--border-color)]">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" wire:navigate class="font-semibold text-[var(--text-primary)] hover:underline">Forgot Password?</a>
        </div>

        <button type="submit" class="w-full rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2.5 text-sm font-semibold text-[var(--bg-card)] transition-all duration-150 active:scale-[0.98]" wire:loading.attr="disabled">
            <span wire:loading.remove>Sign In</span>
            <span wire:loading class="inline-flex items-center gap-2 justify-center">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Signing in...
            </span>
        </button>
    </form>
</div>
