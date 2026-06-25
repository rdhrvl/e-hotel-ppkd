<div wire:poll.5s>
    {{-- ── Header & Filter Controls ── --}}
    <div class="mb-6">
        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Filter Status</label>
        <select wire:model.live="filterStatus" class="block rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3.5 py-2 text-sm text-[var(--text-primary)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none min-w-[160px] cursor-pointer transition-all">
            <option value="">All Tasks</option>
            <option value="scheduled">Scheduled</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    @php
        /**
         * Helper macro: renders a housekeeping task table section.
         * $sectionTasks  – Collection of HousekeepingTask
         * $guestColumn   – closure(task): string|null — returns guest name if any
         */
    @endphp

    <div class="space-y-8">

        {{-- ── Section 1: Pre Check-In ── --}}
        @if($preCheckInTasks->isNotEmpty() || !$filterStatus)
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wide">Pre Check-In</h3>
                    <span class="inline-flex items-center rounded-full bg-[var(--info-bg)] px-2.5 py-0.5 text-[10px] font-bold text-[var(--info)]">
                        {{ $preCheckInTasks->count() }} task{{ $preCheckInTasks->count() !== 1 ? 's' : '' }}
                    </span>
                    <p class="text-xs text-[var(--text-muted)]">Rooms with a confirmed booking awaiting readiness inspection</p>
                </div>

                <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="sticky top-0 z-10 select-none">
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Room</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Incoming Guest</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Assigned To</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('schedule_date')">
                                        Schedule Date @if($sortField === 'schedule_date') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                    </th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('status')">
                                        Status @if($sortField === 'status') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                                @forelse($preCheckInTasks as $task)
                                    <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50 cursor-pointer" wire:click="openRoomDetailModal({{ $task->room->id }})">
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            <div class="font-bold text-[var(--info)] hover:underline">Room {{ $task->room->room_number }}</div>
                                            <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $task->room->roomType->name }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            @php $guestName = $task->room->currentBooking?->guest?->name; @endphp
                                            @if($guestName)
                                                <div class="font-semibold text-[var(--text-primary)]">{{ $guestName }}</div>
                                                <div class="text-[10px] text-[var(--text-muted)]">
                                                    {{ \Carbon\Carbon::parse($task->room->currentBooking->check_in_date)->format('d M Y') }}
                                                </div>
                                            @else
                                                <span class="text-[var(--text-muted)]">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            <div class="font-semibold text-[var(--text-primary)]">{{ $task->staff->name }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-[var(--text-muted)] font-mono">
                                            {{ \Carbon\Carbon::parse($task->schedule_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            @if($task->status === 'scheduled')
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--info-bg)] text-[var(--info)]">Scheduled</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--warning-bg)] text-[var(--warning)]">In Progress</span>
                                            @elseif($task->status === 'completed')
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--success-bg)] text-[var(--success)]">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-[var(--text-muted)] font-medium">No pre check-in tasks.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Section 2: Pre Check-Out ── --}}
        @if($preCheckOutTasks->isNotEmpty() || !$filterStatus)
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wide">Pre Check-Out</h3>
                    <span class="inline-flex items-center rounded-full bg-[var(--danger-bg)] px-2.5 py-0.5 text-[10px] font-bold text-[var(--danger)]">
                        {{ $preCheckOutTasks->count() }} task{{ $preCheckOutTasks->count() !== 1 ? 's' : '' }}
                    </span>
                    <p class="text-xs text-[var(--text-muted)]">Rooms currently occupied — housekeeping scheduled during or after stay</p>
                </div>

                <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="sticky top-0 z-10 select-none">
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Room</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Current Guest</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Assigned To</th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('schedule_date')">
                                        Schedule Date @if($sortField === 'schedule_date') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                    </th>
                                    <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('status')">
                                        Status @if($sortField === 'status') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                                @forelse($preCheckOutTasks as $task)
                                    <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50 cursor-pointer" wire:click="openRoomDetailModal({{ $task->room->id }})">
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            <div class="font-bold text-[var(--danger)] hover:underline">Room {{ $task->room->room_number }}</div>
                                            <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $task->room->roomType->name }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            @php $guestName = $task->room->activeBooking?->guest?->name ?? $task->room->currentBooking?->guest?->name; @endphp
                                            @if($guestName)
                                                <div class="font-semibold text-[var(--text-primary)]">{{ $guestName }}</div>
                                                @php $checkout = $task->room->activeBooking?->check_out_date ?? $task->room->currentBooking?->check_out_date; @endphp
                                                @if($checkout)
                                                    <div class="text-[10px] text-[var(--text-muted)]">CO: {{ \Carbon\Carbon::parse($checkout)->format('d M Y') }}</div>
                                                @endif
                                            @else
                                                <span class="text-[var(--text-muted)]">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            <div class="font-semibold text-[var(--text-primary)]">{{ $task->staff->name }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-[var(--text-muted)] font-mono">
                                            {{ \Carbon\Carbon::parse($task->schedule_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                            @if($task->status === 'scheduled')
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--info-bg)] text-[var(--info)]">Scheduled</span>
                                            @elseif($task->status === 'in_progress')
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--warning-bg)] text-[var(--warning)]">In Progress</span>
                                            @elseif($task->status === 'completed')
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--success-bg)] text-[var(--success)]">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-[var(--text-muted)] font-medium">No pre check-out tasks.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Section 3: General / Other Tasks ── --}}
        <div>
            <div class="flex items-center gap-3 mb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wide">General Tasks</h3>
                <span class="inline-flex items-center rounded-full bg-[var(--bg-secondary)] px-2.5 py-0.5 text-[10px] font-bold text-[var(--text-secondary)]">
                    {{ $generalTasks->count() }} task{{ $generalTasks->count() !== 1 ? 's' : '' }}
                </span>
                <p class="text-xs text-[var(--text-muted)]">Post-checkout cleaning, maintenance, and general housekeeping</p>
            </div>

            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="sticky top-0 z-10 select-none">
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Room</th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Assigned To</th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('schedule_date')">
                                    Schedule Date @if($sortField === 'schedule_date') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('status')">
                                    Status @if($sortField === 'status') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                            @forelse($generalTasks as $task)
                                <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50 cursor-pointer" wire:click="openRoomDetailModal({{ $task->room->id }})">
                                    <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                        <div class="font-bold text-[var(--text-primary)] hover:underline">Room {{ $task->room->room_number }}</div>
                                        <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $task->room->roomType->name }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                        <div class="font-semibold text-[var(--text-primary)]">{{ $task->staff->name }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)] text-[var(--text-muted)] font-mono">
                                        {{ \Carbon\Carbon::parse($task->schedule_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 text-sm border-b border-[var(--border-color)]">
                                        @if($task->status === 'scheduled')
                                            <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--info-bg)] text-[var(--info)]">Scheduled</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--warning-bg)] text-[var(--warning)]">In Progress</span>
                                        @elseif($task->status === 'completed')
                                            <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] bg-[var(--success-bg)] text-[var(--success)]">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-[var(--text-muted)] font-medium">No general tasks.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination for full list --}}
                @if($tasks->hasPages())
                    <div class="p-4 border-t border-[var(--border-color)] bg-[var(--bg-primary)]/30">
                        {{ $tasks->links('livewire.dashboard.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    {{-- Room Detail Modal --}}
    @if($showRoomDetailModal && $detailRoom)
        @php
            $latestRoomTask = $detailRoom->housekeepingTasks->first();
            $activeBooking  = $detailRoom->activeBooking ?? $detailRoom->currentBooking;
            $taskCompleted  = $latestRoomTask && $latestRoomTask->status === 'completed';
        @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="fixed inset-0" wire:click="closeRoomDetailModal"></div>
            <div class="relative w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-3">
                    <div>
                        <h3 class="text-base font-semibold text-[var(--text-primary)]">Room {{ $detailRoom->room_number }} Details</h3>
                        <p class="text-sm text-[var(--text-secondary)] mt-0.5">{{ $detailRoom->roomType->name }}</p>
                    </div>
                    <button wire:click="closeRoomDetailModal" class="text-[var(--text-muted)] hover:text-[var(--text-primary)] font-bold text-xl">&times;</button>
                </div>

                <div class="space-y-3.5 text-xs text-[var(--text-secondary)]">
                    {{-- Status Indicators --}}
                    <div class="flex justify-between items-center py-1.5 border-b border-[var(--border-color)]">
                        <span>Current Status:</span>
                        <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)]
                            @if($detailRoom->status === 'available') bg-[var(--success-bg)] text-[var(--success)]
                            @elseif($detailRoom->status === 'cleaning') bg-[var(--warning-bg)] text-[var(--warning)]
                            @elseif($detailRoom->status === 'maintenance') bg-red-50 text-red-600
                            @else bg-[var(--info-bg)] text-[var(--info)] @endif">
                            {{ $detailRoom->status === 'available' ? 'Vakant' : ucfirst($detailRoom->status) }}
                        </span>
                    </div>

                    {{-- Active Guest Details --}}
                    @if($activeBooking)
                        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-3 space-y-2">
                            <span class="text-sm font-medium text-[var(--text-secondary)] mb-2 block">
                                {{ in_array($detailRoom->status, ['occupied', 'cleaning']) ? 'Guest (Checked In)' : 'Incoming Guest' }}
                            </span>
                            <div class="space-y-1">
                                <p class="flex justify-between"><span>Guest:</span> <strong class="text-[var(--text-primary)]">{{ $activeBooking->guest->name }}</strong></p>
                                <p class="flex justify-between"><span>Check-In:</span> <span class="font-semibold text-[var(--text-primary)]">{{ \Carbon\Carbon::parse($activeBooking->check_in_date)->format('d M Y') }}</span></p>
                                <p class="flex justify-between"><span>Check-Out:</span> <span class="font-semibold text-[var(--text-primary)]">{{ \Carbon\Carbon::parse($activeBooking->check_out_date)->format('d M Y') }}</span></p>
                            </div>
                        </div>
                    @endif

                    {{-- Cleaning Task Details --}}
                    @if($latestRoomTask)
                        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-3 space-y-2">
                            <span class="text-sm font-medium text-[var(--text-secondary)] mb-2 block">Housekeeping Task</span>
                            <div class="space-y-1">
                                <p class="flex justify-between"><span>Assigned Staff:</span> <strong class="text-[var(--text-primary)]">{{ $latestRoomTask->staff->name }}</strong></p>
                                <p class="flex justify-between"><span>Schedule Date:</span> <span class="font-semibold text-[var(--text-primary)]">{{ \Carbon\Carbon::parse($latestRoomTask->schedule_date)->format('d M Y') }}</span></p>
                                <p class="flex justify-between"><span>Task Status:</span>
                                    <span class="text-xs font-semibold
                                        @if($latestRoomTask->status === 'completed') text-[var(--success)]
                                        @elseif($latestRoomTask->status === 'in_progress') text-[var(--warning)]
                                        @else text-[var(--info)] @endif">
                                        {{ ucfirst(str_replace('_', ' ', $latestRoomTask->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Cleaning Report Input --}}
                    @if(!$taskCompleted)
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Cleaning / Inspection Report Information</label>
                            <textarea wire:model="cleaningNotes" placeholder="Write cleaning description or items replaced/found..." class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-2 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all h-20 resize-none"></textarea>
                        </div>

                        {{-- Integrated issue report inside details modal --}}
                        <div class="space-y-2 border-t border-[var(--border-color)] pt-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" wire:model.live="hasIssue" class="rounded border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-primary)] focus:ring-0 h-4 w-4 cursor-pointer">
                                <span class="text-xs text-[var(--text-primary)] font-semibold">Report an issue/alert with this room</span>
                            </label>

                            @if($hasIssue)
                                <div class="rounded border border-red-500/30 bg-red-50/5 p-3 space-y-3 mt-2">
                                    <div>
                                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Issue Type</label>
                                        <div class="flex items-center gap-4 mt-1">
                                            <label class="inline-flex items-center text-xs text-[var(--text-primary)] font-semibold cursor-pointer">
                                                <input type="radio" wire:model.live="issueType" value="missing_item" class="mr-2">
                                                Missing Item
                                            </label>
                                            <label class="inline-flex items-center text-xs text-[var(--text-primary)] font-semibold cursor-pointer">
                                                <input type="radio" wire:model.live="issueType" value="maintenance" class="mr-2">
                                                Maintenance Required
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Issue Description</label>
                                        <input type="text" wire:model="issueDescription" placeholder="e.g. Towel missing, AC remote broken" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" required>
                                        @error('issueDescription') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Assign Housekeeping Task — hidden when task is completed --}}
                    @if(!$taskCompleted && (!$latestRoomTask || $latestRoomTask->status === 'completed') && $detailRoom->status !== 'available')
                        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-primary)] p-3 space-y-3">
                            <span class="text-sm font-medium text-[var(--text-secondary)] mb-2 block">Assign Housekeeping Task</span>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Select Staff</label>
                                    <select wire:model="selectedStaffId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                                        @foreach($housekeepingStaff as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Schedule Date</label>
                                    <input type="date" wire:model="scheduleDate" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3 py-1.5 text-xs text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                                </div>
                                <button type="button" wire:click="assignTaskFromModal" class="w-full rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-3 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors">
                                    Assign Staff
                                </button>
                                @error('selectedRoomId') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-2 border-t border-[var(--border-color)] pt-4 flex-wrap">
                    <button type="button" wire:click="closeRoomDetailModal" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3.5 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors">Close</button>

                    @if($latestRoomTask && !$taskCompleted)
                        @php
                            $isPreCheckInTask = \App\Models\Booking::where('room_id', $detailRoom->id)->where('status', 'confirmed')->exists();
                        @endphp
                        @if($isPreCheckInTask)
                            <button type="button" wire:click="completeCleaning" class="rounded bg-[#346538] hover:bg-[#346538]/90 px-3.5 py-2 text-xs font-semibold text-white transition-colors">Mark Ready for Check-in</button>
                        @else
                            <button type="button" wire:click="completeCleaning" class="rounded bg-[#346538] hover:bg-[#346538]/90 px-3.5 py-2 text-xs font-semibold text-white transition-colors">Submit Cleaning & Inspection Report</button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
