<div wire:poll.5s>
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[var(--text-primary)] tracking-tight">Food &amp; Beverage</h1>
        <p class="text-sm text-[var(--text-muted)] mt-1">Manage room service orders and the restaurant menu.</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b border-[var(--border-color)] flex gap-0 mb-6 select-none">
        <button wire:click="$set('activeTab', 'orders')"
            class="pb-3 border-b-2 text-sm font-medium transition-all cursor-pointer px-1 mr-6 {{ $activeTab === 'orders' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)]' : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
            Orders Queue
        </button>
        <button wire:click="$set('activeTab', 'menu')"
            class="pb-3 border-b-2 text-sm font-medium transition-all cursor-pointer px-1 mr-6 {{ $activeTab === 'menu' ? 'border-[var(--accent-primary)] text-[var(--accent-primary)]' : 'border-transparent text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
            Manage Menu
        </button>
    </div>

    {{-- Orders Tab --}}
    @if ($activeTab === 'orders')
        <div class="space-y-5">
            {{-- Top bar --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-[var(--text-secondary)]">Queue</label>
                    <select wire:model.live="ordersFilter"
                        class="border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all cursor-pointer">
                        <option value="active">Active Orders</option>
                        <option value="completed">Completed Orders</option>
                        <option value="all">All Orders</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[var(--danger-bg)] border border-[var(--danger)]/20">
                        <span
                            class="text-xs font-semibold text-[var(--danger)] font-mono">{{ $stats['processed'] }}</span>
                        <span class="text-xs text-[var(--danger)]/80">Incoming</span>
                    </div>
                    <div
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[var(--warning-bg)] border border-[var(--warning)]/20">
                        <span
                            class="text-xs font-semibold text-[var(--warning)] font-mono">{{ $stats['preparing'] }}</span>
                        <span class="text-xs text-[var(--warning)]/80">Preparing</span>
                    </div>
                    <div
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[var(--info-bg)] border border-[var(--info)]/20">
                        <span
                            class="text-xs font-semibold text-[var(--info)] font-mono">{{ $stats['delivered'] }}</span>
                        <span class="text-xs text-[var(--info)]/80">Delivering</span>
                    </div>
                </div>
            </div>

            {{-- Orders grid --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse($orders as $order)
                    @php
                        $statusBadge = [
                            'processed' => 'bg-[var(--danger-bg)] text-[var(--danger)] border-[var(--danger)]/20',
                            'preparing' => 'bg-[var(--warning-bg)] text-[var(--warning)] border-[var(--warning)]/20',
                            'delivered' => 'bg-[var(--info-bg)] text-[var(--info)] border-[var(--info)]/20',
                            'completed' => 'bg-[var(--success-bg)] text-[var(--success)] border-[var(--success)]/20',
                        ];
                        $statusText = [
                            'processed' => 'Incoming',
                            'preparing' => 'Preparing',
                            'delivered' => 'Delivering',
                            'completed' => 'Completed',
                        ];
                        $actionText = [
                            'processed' => 'Start Preparing',
                            'preparing' => 'Ship Order',
                            'delivered' => 'Mark Completed',
                        ];
                    @endphp
                    <div
                        class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius)] overflow-hidden flex flex-col">
                        {{-- Card header --}}
                        <div
                            class="px-4 py-3 bg-[var(--bg-secondary)] border-b border-[var(--border-color)] flex items-center justify-between">
                            <div>
                                <span class="text-sm font-bold text-[var(--text-primary)]">Room
                                    {{ $order->booking->room->room_number }}</span>
                                <p class="text-xs text-[var(--text-muted)] mt-0.5">Order #{{ $order->id }} &bull;
                                    {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold border {{ $statusBadge[$order->status] ?? 'bg-[var(--bg-secondary)] text-[var(--text-muted)]' }}">
                                {{ $statusText[$order->status] ?? $order->status }}
                            </span>
                        </div>
                        {{-- Card body --}}
                        <div class="flex-1 p-4 space-y-3">
                            <p class="text-sm text-[var(--text-primary)]">
                                <span class="text-[var(--text-muted)] text-xs">Guest: </span>
                                <span class="font-semibold">{{ $order->booking->guest->name }}</span>
                            </p>
                            <div class="rounded-[var(--radius-sm)] overflow-hidden border border-[var(--border-color)]">
                                @foreach ($order->items as $item)
                                    <div
                                        class="flex items-center justify-between px-3 py-2 text-xs border-b border-[var(--border-color)] last:border-b-0 bg-[var(--bg-secondary)]">
                                        <span
                                            class="font-medium text-[var(--text-primary)]">{{ $item->quantity }}&times;
                                            {{ $item->service->name }}</span>
                                        <span class="font-mono text-[var(--text-muted)]">Rp
                                            {{ number_format((float) $item->price * $item->quantity) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Card footer --}}
                        <div
                            class="px-4 py-3 border-t border-[var(--border-color)] bg-[var(--bg-secondary)] flex items-center justify-between">
                            <div>
                                <p class="text-xs text-[var(--text-muted)]">Total</p>
                                <p class="text-sm font-bold font-mono text-[var(--text-primary)]">Rp
                                    {{ number_format((float) $order->total_price) }}</p>
                            </div>
                            @if ($order->status !== 'completed')
                                <button wire:click="advanceOrderStatus({{ $order->id }})"
                                    wire:loading.attr="disabled"
                                    class="bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-3 py-1.5 text-xs font-semibold transition-colors cursor-pointer disabled:opacity-60">
                                    {{ $actionText[$order->status] ?? 'Next Stage' }}
                                </button>
                            @else
                                <span class="text-xs font-semibold text-[var(--success)] flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Order Finished
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 text-center bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius)]">
                        <svg class="h-10 w-10 mx-auto text-[var(--text-muted)]/40 mb-3" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        <p class="text-sm font-medium text-[var(--text-muted)]">No orders in the queue right now.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- Menu Management Tab --}}
    @if ($activeTab === 'menu')
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
            {{-- Right content --}}
            <div class="space-y-5 lg:col-span-3">
                {{-- Top bar --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-semibold text-[var(--text-primary)]">
                            {{ $categoryFilter ? $categoryFilter : 'All Items' }}</h2>
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[var(--bg-secondary)] text-[var(--text-muted)] border border-[var(--border-color)] font-mono">{{ $menuItems->count() }}</span>
                    </div>
                    <button wire:click="openAddModal"
                        class="bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">
                        Add Menu Item
                    </button>
                </div>
                {{-- Menu items grid --}}
                @if ($menuItems->count() > 0)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach ($menuItems as $item)
                            <div
                                class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius)] overflow-hidden hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)] transition-all flex flex-col relative">
                                @if (!$item->is_active)
                                    <div class="absolute z-10 top-2 right-2">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-[var(--danger-bg)] text-[var(--danger)] border border-[var(--danger)]/20">Inactive</span>
                                    </div>
                                @endif
                                @if ($item->image_path)
                                    <img src="{{ $item->image_path }}" alt="{{ $item->name }}"
                                        class="object-cover w-full h-32">
                                @else
                                    <div class="h-32 w-full bg-[var(--bg-secondary)] flex items-center justify-center">
                                        <svg class="h-10 w-10 text-[var(--text-muted)]/40" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-1.5-.75M3 16.5v-2.25A2.25 2.25 0 015.25 12h13.5A2.25 2.25 0 0121 14.25v2.25" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex flex-col flex-1 gap-2 p-4">
                                    <h4 class="font-semibold text-sm text-[var(--text-primary)]">{{ $item->name }}
                                    </h4>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[var(--bg-secondary)] text-[var(--text-muted)] border border-[var(--border-color)]">{{ $item->category }}</span>
                                        <span class="text-sm font-bold font-mono text-[var(--accent-primary)]">Rp
                                            {{ number_format((float) $item->price) }}</span>
                                    </div>
                                    @if ($item->description)
                                        <p class="text-xs text-[var(--text-muted)] line-clamp-2">
                                            {{ $item->description }}</p>
                                    @endif
                                </div>
                                <div
                                    class="px-4 py-3 border-t border-[var(--border-color)] bg-[var(--bg-secondary)] flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $item->id }})"
                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:bg-[var(--bg-card)] transition-colors cursor-pointer"
                                        title="Edit">
                                        <svg class="h-3.5 w-3.5 text-[var(--text-muted)]" fill="none"
                                            stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})"
                                        class="p-1.5 rounded-[var(--radius-sm)] border border-[var(--border-color)] hover:bg-[var(--danger-bg)] hover:border-[var(--danger)]/30 transition-colors cursor-pointer"
                                        title="Delete">
                                        <svg class="h-3.5 w-3.5 text-[var(--text-muted)]" fill="none"
                                            stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6" />
                                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="py-16 text-center bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius)]">
                        <svg class="h-10 w-10 mx-auto text-[var(--text-muted)]/40 mb-3" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-1.5-.75M3 16.5v-2.25A2.25 2.25 0 015.25 12h13.5A2.25 2.25 0 0121 14.25v2.25" />
                        </svg>
                        <p class="text-sm font-medium text-[var(--text-muted)]">No menu items found.</p>
                    </div>
                @endif
            </div>
            {{-- Left sidebar --}}
            <div class="space-y-5 lg:col-span-1">

                {{-- Card 1: Category Directory --}}
                <div
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius)] overflow-hidden">
                    <div
                        class="px-4 py-3 border-b border-[var(--border-color)] bg-[var(--bg-secondary)] flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-[var(--text-primary)]">Categories</h3>
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-[var(--bg-secondary)] text-[var(--text-muted)] border border-[var(--border-color)]">{{ count($categories) }}</span>
                    </div>
                    <div class="py-1">
                        {{-- All Items --}}
                        <button wire:click="$set('categoryFilter', '')"
                            class="group/row w-full flex items-center gap-2 py-2.5 pr-4 text-sm transition-all duration-150 {{ $categoryFilter === '' ? 'pl-[14px] border-l-2 border-[var(--accent-primary)] bg-[var(--accent-primary)]/8 text-[var(--text-primary)] font-semibold' : 'pl-4 border-l-2 border-transparent text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)]' }}">
                            <span class="flex-1 text-left">All Items</span>
                            <span
                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold transition-colors {{ $categoryFilter === '' ? 'bg-[var(--accent-primary)] text-white' : 'bg-[var(--bg-secondary)] text-[var(--text-muted)] border border-[var(--border-color)] group-hover/row:bg-[var(--border-color)] group-hover/row:text-[var(--text-secondary)]' }}">
                                {{ array_sum(array_values($categoryCounts)) }}
                            </span>
                        </button>

                        <div class="mx-4 my-1 border-t border-[var(--border-color)]"></div>

                        {{-- Per-category rows --}}
                        @foreach ($categories as $cat)
                            <div
                                class="group/row relative flex items-center py-2.5 pr-3 text-sm transition-all duration-150 cursor-pointer {{ $categoryFilter === $cat ? 'pl-[14px] border-l-2 border-[var(--accent-primary)] bg-[var(--accent-primary)]/8 text-[var(--text-primary)] font-semibold' : 'pl-4 border-l-2 border-transparent text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)]' }}">
                                <button wire:click="$set('categoryFilter', '{{ $cat }}')"
                                    class="flex-1 min-w-0 pr-2 text-left truncate">{{ $cat }}</button>

                                {{-- Counter: slides left on hover to make room for actions --}}
                                <span
                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0 transition-all duration-200 translate-x-0 group-hover/row:-translate-x-[52px] {{ $categoryFilter === $cat ? 'bg-[var(--accent-primary)] text-white' : 'bg-[var(--bg-secondary)] text-[var(--text-muted)] border border-[var(--border-color)] group-hover/row:bg-[var(--border-color)] group-hover/row:text-[var(--text-secondary)]' }}">
                                    {{ $categoryCounts[$cat] ?? 0 }}
                                </span>

                                {{-- Actions: slide in from right, overlap above counter space --}}
                                <div
                                    class="absolute right-3 flex items-center gap-0.5 opacity-0 translate-x-2 group-hover/row:opacity-100 group-hover/row:translate-x-0 transition-all duration-200">
                                    <button wire:click.stop="openEditCategoryModal('{{ $cat }}')"
                                        class="p-1 rounded hover:bg-[var(--bg-card)] text-[var(--text-muted)] hover:text-[var(--accent-primary)] transition-colors"
                                        title="Rename">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                    </button>
                                    <button wire:click.stop="openDeleteCategoryModal('{{ $cat }}')"
                                        class="p-1 rounded hover:bg-[var(--danger-bg)] text-[var(--text-muted)] hover:text-[var(--danger)] transition-colors"
                                        title="Delete">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Card 2: Add Category --}}
                <div
                    class="bg-[var(--bg-card)] border border-[var(--border-color)] rounded-[var(--radius)] overflow-hidden">
                    <div class="px-4 py-3 border-b border-[var(--border-color)] bg-[var(--bg-secondary)]">
                        <h3 class="text-sm font-semibold text-[var(--text-primary)]">New Category</h3>
                    </div>
                    <div class="p-4">
                        <form wire:submit.prevent="addCategory" class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Category
                                    name</label>
                                <input type="text" wire:model="newCategory" placeholder="e.g. Soup"
                                    class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all">
                                @error('newCategory')
                                    <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">
                                Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Add Menu Item Modal --}}
    @if ($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeAddModal"></div>
            <div
                class="relative w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)]">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4 mb-4">
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Add New Menu Item</h3>
                    <button wire:click="closeAddModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl leading-none">&times;</button>
                </div>
                <form wire:submit.prevent="createMenuItem" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Item Name</label>
                        <input type="text" wire:model="name" placeholder="e.g. Nasi Goreng Kampung"
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                            required>
                        @error('name')
                            <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Price
                                (Rp)</label>
                            <input type="number" wire:model="price" placeholder="e.g. 45000"
                                class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                                required>
                            @error('price')
                                <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Category</label>
                            <select wire:model="category"
                                class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all cursor-pointer">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label
                            class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Description</label>
                        <textarea wire:model="description" rows="2" placeholder="Enter short menu description..."
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Image Path / URL
                            (Optional)</label>
                        <input type="text" wire:model="imagePath" placeholder="e.g. /img/menu/nasi-goreng.jpg"
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="isActive" wire:model="isActive"
                            class="rounded border-[var(--border-color)]">
                        <label for="isActive"
                            class="text-sm font-medium text-[var(--text-secondary)] cursor-pointer">Available /
                            Active</label>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeAddModal"
                            class="border border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors">Cancel</button>
                        <button type="submit"
                            class="bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">Save
                            Item</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit Menu Item Modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeEditModal"></div>
            <div
                class="relative w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)]">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4 mb-4">
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Edit Menu Item</h3>
                    <button wire:click="closeEditModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl leading-none">&times;</button>
                </div>
                <form wire:submit.prevent="updateMenuItem" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Item Name</label>
                        <input type="text" wire:model="editName"
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                            required>
                        @error('editName')
                            <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Price
                                (Rp)</label>
                            <input type="number" wire:model="editPrice"
                                class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                                required>
                            @error('editPrice')
                                <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label
                                class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Category</label>
                            <select wire:model="editCategory"
                                class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all cursor-pointer">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('editCategory')
                                <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label
                            class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Description</label>
                        <textarea wire:model="editDescription" rows="2"
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Image Path / URL
                            (Optional)</label>
                        <input type="text" wire:model="editImagePath"
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="editIsActive" wire:model="editIsActive"
                            class="rounded border-[var(--border-color)]">
                        <label for="editIsActive"
                            class="text-sm font-medium text-[var(--text-secondary)] cursor-pointer">Available /
                            Active</label>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeEditModal"
                            class="border border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors">Cancel</button>
                        <button type="submit"
                            class="bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">Update
                            Item</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Menu Item Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeDeleteModal"></div>
            <div
                class="relative w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)]">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4 mb-4">
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Delete Menu Item</h3>
                    <button wire:click="closeDeleteModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl leading-none">&times;</button>
                </div>
                <div class="space-y-4">
                    <p class="text-sm text-[var(--text-primary)]">Are you sure you want to delete <strong
                            class="font-bold">"{{ $deletingMenuItemName }}"</strong>? This action cannot be undone.
                    </p>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeDeleteModal"
                            class="border border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors">Cancel</button>
                        <button type="button" wire:click="deleteMenuItem"
                            class="bg-[var(--danger)] hover:bg-[var(--danger)]/90 text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">Delete
                            Item</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Edit Category Modal --}}
    @if ($showEditCategoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeEditCategoryModal"></div>
            <div
                class="relative w-full max-w-sm rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)]">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4 mb-4">
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Rename Category</h3>
                    <button wire:click="closeEditCategoryModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl leading-none">&times;</button>
                </div>
                <form wire:submit.prevent="updateCategory" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Category
                            name</label>
                        <input type="text" wire:model="editCategoryName"
                            class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] rounded-[var(--radius-sm)] px-3 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all"
                            required>
                        @error('editCategoryName')
                            <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeEditCategoryModal"
                            class="border border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors">Cancel</button>
                        <button type="submit"
                            class="bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Category Modal --}}
    @if ($showDeleteCategoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeDeleteCategoryModal"></div>
            <div
                class="relative w-full max-w-sm rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-[var(--shadow-lg)]">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4 mb-4">
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Delete Category</h3>
                    <button wire:click="closeDeleteCategoryModal"
                        class="text-[var(--text-muted)] hover:text-[var(--text-primary)] text-xl leading-none">&times;</button>
                </div>
                <div class="space-y-4">
                    @if ($deletingCategoryItemCount > 0)
                        <div
                            class="rounded-[var(--radius-sm)] bg-[var(--danger-bg)] border border-[var(--danger)]/20 px-4 py-3 text-sm text-[var(--danger)]">
                            Cannot delete <strong>"{{ $deletingCategory }}"</strong> — it has
                            {{ $deletingCategoryItemCount }} menu item(s) assigned. Reassign or remove them first.
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="button" wire:click="closeDeleteCategoryModal"
                                class="bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">Close</button>
                        </div>
                    @else
                        <p class="text-sm text-[var(--text-primary)]">Are you sure you want to delete <strong
                                class="font-semibold">"{{ $deletingCategory }}"</strong>? This cannot be undone.</p>
                        <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                            <button type="button" wire:click="closeDeleteCategoryModal"
                                class="border border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors">Cancel</button>
                            <button type="button" wire:click="deleteCategory"
                                class="bg-[var(--danger)] hover:bg-[var(--danger)]/90 text-white rounded-[var(--radius-sm)] px-4 py-2 text-sm font-semibold transition-colors cursor-pointer">Delete</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
