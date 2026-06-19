<div>
    {{-- ── Header & Filter Controls ── --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 mb-8 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Filter Status</label>
            <select wire:model.live="filterStatus" class="block rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-3.5 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none min-w-[160px] cursor-pointer transition-all">
                <option value="">All Tasks</option>
                <option value="scheduled">Scheduled</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Tasks List --}}
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-2">Today's Schedule</h3>

            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                                <th class="p-4">Room</th>
                                <th class="p-4">Assigned To</th>
                                <th class="p-4">Schedule Date</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                            @forelse($tasks as $task)
                                <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                                    <td class="p-4">
                                        <div class="font-bold text-[var(--text-primary)]">Room {{ $task->room->room_number }}</div>
                                        <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $task->room->roomType->name }}</div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold text-[var(--text-primary)]">{{ $task->staff->name }}</div>
                                    </td>
                                    <td class="p-4 text-[var(--text-muted)] font-mono text-xs">
                                        {{ Carbon\Carbon::parse($task->schedule_date)->format('d M Y') }}
                                    </td>
                                    <td class="p-4">
                                        @if($task->status === 'scheduled')
                                            <span class="inline-flex rounded bg-[var(--info-bg)] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[var(--info)] border border-[var(--border-color)]">Scheduled</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="inline-flex rounded bg-[var(--warning-bg)] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[var(--warning)] border border-[var(--border-color)]">In Progress</span>
                                        @elseif($task->status === 'completed')
                                            <span class="inline-flex rounded bg-[var(--success-bg)] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[var(--success)] border border-[var(--border-color)]">Completed</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        @if($task->status !== 'completed')
                                            <div class="flex items-center justify-end gap-1.5">
                                                @if($task->status === 'scheduled')
                                                    <button wire:click="updateTaskStatus({{ $task->id }}, 'in_progress')" class="rounded bg-[#956400] hover:bg-[#956400]/85 px-3 py-1.5 text-xs font-semibold text-white transition-colors">
                                                        Start Clean
                                                    </button>
                                                @elseif($task->status === 'in_progress')
                                                    <button wire:click="updateTaskStatus({{ $task->id }}, 'completed')" class="rounded bg-[#346538] hover:bg-[#346538]/85 px-3 py-1.5 text-xs font-semibold text-white transition-colors">
                                                        Mark Done
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-[var(--text-muted)] font-semibold italic">Finished</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-[var(--text-muted)] font-medium">
                                        No tasks scheduled.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Rooms Needing Cleaning Panel --}}
        <div class="space-y-6">
            <h3 class="text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider mb-2">Rooms Needing Action</h3>
            
            <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] p-5 space-y-4 shadow-sm">
                @forelse($unassignedRooms as $room)
                    <div class="flex items-center justify-between p-3 rounded border border-[var(--border-color)] bg-[var(--bg-primary)] hover:bg-[var(--bg-card-hover)] transition-colors">
                        <div>
                            <span class="font-bold text-[var(--text-primary)]">Room {{ $room->room_number }}</span>
                            <span class="inline-flex rounded bg-[var(--danger-bg)] px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-[var(--danger)] border border-[var(--border-color)] ml-2">{{ $room->status }}</span>
                            <p class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $room->roomType->name }}</p>
                        </div>
                        <button wire:click="openAssignModal({{ $room->id }})" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--bg-card)] transition-colors">
                            Assign
                        </button>
                    </div>
                @empty
                    <p class="text-xs text-[var(--text-muted)] text-center py-4">All dirty/maintenance rooms have tasks assigned.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Assign Task Modal --}}
    @if($showAssignModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
            <div class="fixed inset-0" wire:click="closeAssignModal"></div>
            <div class="relative w-full max-w-md rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center justify-between border-b border-[var(--border-color)] pb-4 mb-4">
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Assign Housekeeping Task</h3>
                    <button wire:click="closeAssignModal" class="text-[var(--text-muted)] hover:text-[var(--text-primary)]">&times;</button>
                </div>
                <form wire:submit.prevent="assignTask" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Select Staff</label>
                        <select wire:model="selectedStaffId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer" required>
                            @foreach($housekeepingStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Schedule Date</label>
                        <input type="date" wire:model="scheduleDate" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" required>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4">
                        <button type="button" wire:click="closeAssignModal" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors">Assign Staff</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
