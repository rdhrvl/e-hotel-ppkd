<div>
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Cleaning Queue --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Rooms to Clean</span>
                    <span class="text-2xl font-bold text-[var(--info)] font-mono tracking-tight mt-1.5 inline-block">{{ $cleaningCount }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--info-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--info)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Awaiting cleaning</span>
            </div>
        </div>

        {{-- Ready --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Ready for Check-In</span>
                    <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight mt-1.5 inline-block">{{ $readyCount }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--success-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--success)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Cleaned &amp; inspected</span>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Maintenance</span>
                    <span class="text-2xl font-bold text-[var(--danger)] font-mono tracking-tight mt-1.5 inline-block">{{ $maintenanceCount }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--danger-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--danger)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Rooms out of service</span>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Tasks In Progress</span>
                    <span class="text-2xl font-bold text-[var(--warning)] font-mono tracking-tight mt-1.5 inline-block">{{ $inProgressCount }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--warning-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--warning)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-semibold font-mono">{{ $scheduledCount }} scheduled · {{ $completedCount }} done today</span>
            </div>
        </div>
    </div>

    {{-- Open Tasks Table --}}
    <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
        <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
            <h3 class="text-sm font-bold text-[var(--text-primary)]">Open Cleaning Tasks</h3>
            <a href="{{ route('housekeeping') }}" class="text-xs font-medium text-[var(--accent-primary)] hover:underline border border-[var(--border-color)] bg-[var(--bg-secondary)] px-2.5 py-1 rounded-[var(--radius-sm)]">Go to Housekeeping</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)]">
                        <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Room</th>
                        <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Type</th>
                        <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Assigned To</th>
                        <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Schedule</th>
                        <th class="p-3 text-xs font-semibold text-[var(--text-muted)] text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                    @forelse($openTasks as $task)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                            <td class="p-3 font-bold text-[var(--text-primary)]">Rm {{ $task->room->room_number }}</td>
                            <td class="p-3 text-[var(--text-muted)]">{{ $task->room->roomType->name ?? '—' }}</td>
                            <td class="p-3 font-semibold text-[var(--text-primary)]">{{ $task->staff->name ?? 'Unassigned' }}</td>
                            <td class="p-3 text-[var(--text-muted)] font-mono text-[11px]">{{ \Illuminate\Support\Carbon::parse($task->schedule_date)->format('d M Y') }}</td>
                            <td class="p-3 text-right">
                                @php $sColor = $task->status === 'in_progress' ? 'bg-[var(--warning-bg)] text-[var(--warning)]' : 'bg-[var(--info-bg)] text-[var(--info)]'; @endphp
                                <span class="inline-flex rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] {{ $sColor }}">{{ str_replace('_', ' ', $task->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-[var(--text-muted)] font-medium">No open tasks. All caught up.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
