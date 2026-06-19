<div>
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-6 shadow-sm max-w-2xl">
        <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-6 border-b border-[var(--border-color)] pb-3">System Configuration</h3>
        
        <div class="space-y-6 text-xs text-[var(--text-secondary)]">
            <div>
                <span class="block font-bold text-[var(--text-primary)]">General Information</span>
                <p class="text-[10px] text-[var(--text-muted)] mt-1">Hotel Management System core controls.</p>
                
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Hotel Brand Name</label>
                        <input type="text" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-secondary)] px-4 py-2 text-xs text-[var(--text-muted)] focus:outline-none opacity-80 cursor-not-allowed" value="Grand Central & Resort Hotel" disabled>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Currency Code</label>
                        <input type="text" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-secondary)] px-4 py-2 text-xs text-[var(--text-muted)] focus:outline-none opacity-80 cursor-not-allowed" value="IDR (Rp)" disabled>
                    </div>
                </div>
            </div>

            <div class="border-t border-[var(--border-color)] pt-6">
                <span class="block font-bold text-[var(--text-primary)]">System Status</span>
                <div class="mt-3 flex items-center gap-3 bg-[var(--bg-primary)] rounded border border-[var(--border-color)] p-4 w-fit">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#346538] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#346538]"></span>
                    </span>
                    <span class="text-xs font-medium text-[var(--text-primary)]">All services running correctly</span>
                </div>
            </div>
        </div>
    </div>
</div>
