<div>
    {{-- Search Filter --}}
    <div class="mb-6">
        <input type="text" wire:model.live="search" class="w-full rounded-[var(--radius-sm)] border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2.5 text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all" placeholder="Search by action, user name, or entity type...">
    </div>

    {{-- Audit Logs list --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-[var(--border-color)] bg-[var(--bg-secondary)] text-xs font-semibold text-[var(--text-muted)] sticky top-0 z-10">
                        <th class="p-4">Timestamp</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Action</th>
                        <th class="p-4">Entity</th>
                        <th class="p-4">Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[var(--bg-card-hover)] transition-colors align-top even:bg-[var(--bg-primary)]/50">
                            <td class="p-4 font-mono text-xs text-[var(--text-muted)]">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-[var(--text-primary)]">{{ $log->user->name ?? 'System' }}</div>
                                <div class="text-xs text-[var(--text-muted)] mt-0.5">{{ $log->user->email ?? '' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex rounded bg-[var(--info-bg)] px-2.5 py-0.5 text-xs font-semibold text-[var(--info)] border border-[var(--border-color)]">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4 font-mono text-xs text-[var(--text-muted)]">
                                @if($log->entity_type)
                                    <div class="font-bold text-[var(--text-primary)]">{{ class_basename($log->entity_type) }}</div>
                                    <div class="text-[10px] text-[var(--text-muted)] mt-0.5">ID: {{ $log->entity_id }}</div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4">
                                @if($log->new_value)
                                    <div class="space-y-1.5 max-w-md">
                                        @if($log->old_value)
                                            <div class="text-[11px] rounded bg-[var(--bg-primary)] p-3 border border-[var(--border-color)] font-mono text-[var(--danger)] line-through">
                                                {{ json_encode($log->old_value, JSON_PRETTY_PRINT) }}
                                            </div>
                                        @endif
                                        <div class="text-[11px] rounded bg-[var(--bg-primary)] p-3 border border-[var(--border-color)] font-mono text-[var(--success)]">
                                            {{ json_encode($log->new_value, JSON_PRETTY_PRINT) }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[var(--text-muted)] text-xs italic">No value snapshots</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[var(--text-muted)] font-medium">
                                No audit logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
