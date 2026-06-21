<div x-data="{ 
    drawerOpen: @entangle('showDrawer'),
    toasts: [],
    shownAlerts: JSON.parse(localStorage.getItem('shown_alerts') || '[]'),
    playChime(isUrgent) {
        try {
            let ctx = new (window.AudioContext || window.webkitAudioContext)();
            let osc = ctx.createOscillator();
            let gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            if (isUrgent) {
                // High-priority beep chime
                osc.type = 'sine';
                osc.frequency.setValueAtTime(784, ctx.currentTime); // G5
                gain.gain.setValueAtTime(0.1, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
                
                setTimeout(() => {
                    let ctx2 = new (window.AudioContext || window.webkitAudioContext)();
                    let osc2 = ctx2.createOscillator();
                    let gain2 = ctx2.createGain();
                    osc2.connect(gain2);
                    gain2.connect(ctx2.destination);
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(880, ctx2.currentTime); // A5
                    gain2.gain.setValueAtTime(0.1, ctx2.currentTime);
                    gain2.gain.exponentialRampToValueAtTime(0.01, ctx2.currentTime + 0.2);
                    osc2.start();
                    osc2.stop(ctx2.currentTime + 0.2);
                }, 150);
            } else {
                // Pleasant low-key chime
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(523, ctx.currentTime); // C5
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                osc.start();
                osc.stop(ctx.currentTime + 0.4);
            }
        } catch (e) {
            console.error('Audio chime error:', e);
        }
    }
}" 
x-on:new-alert-received.window="
    let data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
    
    // Check if this alert was already popped up to the user on this device
    if (data.id && shownAlerts.includes(data.id)) {
        return;
    }
    
    // Mark as shown in localStorage (limit cache size to 100 entries)
    if (data.id) {
        shownAlerts.push(data.id);
        if (shownAlerts.length > 100) {
            shownAlerts.shift();
        }
        localStorage.setItem('shown_alerts', JSON.stringify(shownAlerts));
    }

    toasts.push({
        id: Date.now() + Math.random(),
        message: data.message,
        priority: data.priority,
        is_urgent: data.is_urgent,
        url: data.url
    });
    if (data.trigger_sound) {
        playChime(data.is_urgent);
    }
"
wire:poll.10s="fetchNotifications"
class="relative">

    {{-- Bell Icon Button --}}
    <button wire:click="toggleDrawer" class="relative border border-[var(--border-color)] p-2 text-[var(--text-primary)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] transition-all duration-100 cursor-pointer rounded-md shadow-sm">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-[var(--danger)] text-[9px] font-bold text-white border-2 border-[var(--bg-card)]">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Slide-out Drawer Panel --}}
    <div x-show="drawerOpen" class="fixed inset-0 z-50 overflow-hidden" style="display: none;" x-description="Notification log drawer">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-xs transition-opacity" @click="drawerOpen = false"></div>
        
        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div x-show="drawerOpen" 
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-300" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transform transition ease-in-out duration-300 sm:duration-300" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full" 
                 class="w-screen max-w-md bg-[var(--bg-card)] border-l border-[var(--border-color)] flex flex-col shadow-2xl">
                
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-[var(--border-color)] flex items-center justify-between bg-[var(--bg-card)]">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-semibold text-[var(--text-primary)]">Alert History</h2>
                        @if($unreadCount > 0)
                            <span class="rounded-full bg-[var(--danger-bg)] px-2 py-0.5 text-[10px] font-bold text-[var(--danger)] border border-[var(--border-color)]">
                                {{ $unreadCount }} New
                            </span>
                        @endif
                    </div>
                    <button @click="drawerOpen = false" class="text-xl text-[var(--text-muted)] hover:text-[var(--text-primary)] transition-colors">&times;</button>
                </div>

                {{-- Action Panel --}}
                <div class="px-6 py-3 bg-[var(--bg-primary)]/50 border-b border-[var(--border-color)] flex items-center justify-between text-xs">
                    <span class="text-[var(--text-muted)] font-medium">Showing latest alerts</span>
                    @if($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="font-bold text-[var(--text-primary)] hover:underline">Mark all read</button>
                    @endif
                </div>

                {{-- Notification List --}}
                <div class="flex-1 overflow-y-auto py-2 space-y-2">
                    @forelse($notificationsList as $item)
                        <div class="relative p-4 mx-3 transition-all duration-150 flex gap-4 border rounded {{ $item['priority'] === 'high' ? 'border-[var(--danger)]/30 bg-[var(--danger-bg)]/40' : ($item['priority'] === 'medium' ? 'border-[var(--warning)]/30 bg-[var(--warning-bg)]/40' : 'border-[var(--info)]/30 bg-[var(--info-bg)]/40') }} {{ $item['read_at'] ? 'opacity-70' : 'font-semibold' }} hover:bg-[var(--bg-secondary)] shadow-sm">
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-[10px] uppercase font-bold tracking-wide 
                                        {{ $item['priority'] === 'high' ? 'text-[var(--danger)]' : ($item['priority'] === 'medium' ? 'text-[var(--warning)]' : 'text-[var(--info)]') }}">
                                        {{ $item['priority'] }} Priority
                                    </span>
                                    <span class="text-[10px] text-[var(--text-muted)]">{{ $item['time_ago'] }}</span>
                                </div>
                                <p class="text-xs text-[var(--text-primary)] leading-relaxed mb-2">{{ $item['message'] }}</p>
                                
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-[10px] text-[var(--text-muted)]">By {{ $item['actor_name'] }}</span>
                                    
                                    <div class="flex gap-2">
                                        @if(!$item['read_at'])
                                            <button wire:click="markAsRead('{{ $item['id'] }}')" class="text-[10px] text-[var(--text-primary)] hover:underline font-bold">
                                                Mark Read
                                            </button>
                                        @endif
                                        @if($item['action_url'])
                                            <a href="{{ $item['action_url'] }}" wire:click="markAsRead('{{ $item['id'] }}')" class="text-[10px] text-blue-600 hover:underline font-bold flex items-center gap-0.5">
                                                View <span>&rarr;</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Unread Dot --}}
                            @if(!$item['read_at'])
                                <div class="flex-shrink-0 flex items-center justify-center">
                                    <span class="h-2 w-2 rounded-full bg-[var(--danger)]"></span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center text-[var(--text-muted)] text-xs font-semibold">
                            No notifications received yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- In-App Push Toast Container --}}
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        <template x-for="(toast, index) in toasts" :key="toast.id">
            <div 
                x-init="setTimeout(() => { toasts = toasts.filter(t => t.id !== toast.id) }, 6000)"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-y-2 opacity-0 scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="pointer-events-auto flex w-full flex-col rounded-lg border-2 bg-[var(--bg-card)] shadow-2xl p-4 transition-all duration-300"
                :class="{
                    'border-[var(--danger)] shadow-red-500/10': toast.priority === 'high',
                    'border-[var(--warning)] shadow-orange-500/10': toast.priority === 'medium',
                    'border-[var(--info)] shadow-blue-500/10': toast.priority === 'low'
                }"
            >
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <template x-if="toast.priority === 'high'">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-red-100 text-red-600 text-sm font-bold border border-red-200">⚠️</span>
                        </template>
                        <template x-if="toast.priority === 'medium'">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600 text-sm font-bold border border-orange-200">🔔</span>
                        </template>
                        <template x-if="toast.priority === 'low'">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-blue-100 text-blue-600 text-sm font-bold border border-blue-200">ℹ️</span>
                        </template>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold"
                                  :class="{
                                      'text-[var(--danger)]': toast.priority === 'high',
                                      'text-[var(--warning)]': toast.priority === 'medium',
                                      'text-[var(--info)]': toast.priority === 'low'
                                  }"
                                  x-text="toast.priority + ' Priority'">
                            </span>
                            <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-xs text-[var(--text-muted)] hover:text-[var(--text-primary)]">&times;</button>
                        </div>
                        <p class="text-xs font-bold text-[var(--text-primary)] leading-tight" x-text="toast.message"></p>
                    </div>
                </div>

                <div class="mt-3 flex justify-end gap-2 border-t border-[var(--border-color)] pt-3 text-[10px] font-bold">
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="px-2 py-1 text-[var(--text-muted)] hover:text-[var(--text-primary)]">Dismiss</button>
                    <template x-if="toast.url">
                        <a :href="toast.url" class="rounded bg-[var(--text-primary)] px-2 py-1 text-[var(--bg-card)] hover:bg-[var(--text-secondary)] transition-colors">Action</a>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
