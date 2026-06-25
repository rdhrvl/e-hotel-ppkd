<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 border-b border-[var(--border-color)] pb-5">
        <div>
            <h3 class="text-lg font-bold text-[var(--text-primary)] tracking-tight">Staff &amp; Permissions Registry</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Manage staff user accounts, dynamic roles, and page view authorization permissions.</p>
        </div>
        
        {{-- Tabs --}}
        <div class="flex border border-[var(--border-color)] bg-[var(--bg-secondary)] rounded-md p-1 items-center select-none flex-shrink-0">
            <button wire:click="setTab('staff')"
                class="px-4 py-1.5 text-xs font-semibold rounded-[var(--radius-sm)] transition-all cursor-pointer {{ $activeTab === 'staff' ? 'bg-[var(--bg-card)] text-[var(--text-primary)] shadow-sm' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
                Staff Accounts
            </button>
            <button wire:click="setTab('roles')"
                class="px-4 py-1.5 text-xs font-semibold rounded-[var(--radius-sm)] transition-all cursor-pointer {{ $activeTab === 'roles' ? 'bg-[var(--bg-card)] text-[var(--text-primary)] shadow-sm' : 'text-[var(--text-secondary)] hover:text-[var(--text-primary)]' }}">
                Roles &amp; Permissions
            </button>
        </div>
    </div>

    @if ($activeTab === 'staff')
        {{-- ── Staff Tab Content ── --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[var(--text-muted)]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </span>
                <input type="text" wire:model="search" wire:input.debounce.300ms="$set('search', $event.target.value)" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] pl-9 pr-4 py-2 text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)] focus:border-[var(--accent-primary)] focus:shadow-[0_0_0_3px_rgba(37,99,235,0.1)] focus:outline-none transition-all" placeholder="Search name, email, phone…">
            </div>
            <button class="inline-flex items-center gap-1.5 rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]" wire:click="openAddModal" id="btn-add-user">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Add User
            </button>
        </div>

        {{-- Table --}}
        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
            @if($users->isEmpty())
                <div class="text-center py-12 px-4 space-y-3">
                    <svg class="h-12 w-12 mx-auto text-[var(--text-muted)]/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                    <p class="text-[var(--text-muted)] font-medium">No user accounts found.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="sticky top-0 z-10 select-none">
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('name')">
                                    Staff Member @if($sortField === 'name') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('phone')">
                                    Phone @if($sortField === 'phone') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] cursor-pointer hover:bg-[var(--bg-card-hover)] transition-colors" wire:click="sortBy('email')">
                                    Email @if($sortField === 'email') {{$sortDirection === 'asc' ? '▲' : '▼'}} @endif
                                </th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Assigned Role</th>
                                <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                            @foreach($users as $user)
                                @php
                                    $colors = ['e1f3fe', 'edf3ec', 'fbf3db', 'fdebec'];
                                    $textColors = ['1f6c9f', '346538', '956400', '9f2f2d'];
                                    $index = crc32($user->name) % count($colors);
                                    $bgColor = $colors[$index];
                                    $textColor = $textColors[$index];
                                @endphp
                                <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                                    <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                        <div class="flex items-center gap-3 font-sans">
                                            <div class="w-8 h-8 rounded flex items-center justify-center font-bold text-xs bg-[#{{ $bgColor }}] text-[#{{ $textColor }}]">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-[var(--text-primary)] flex items-center gap-1.5">
                                                    {{ $user->name }}
                                                    @if($user->id === auth()->id())
                                                        <span class="inline-flex items-center rounded-[var(--radius-sm)] px-1.5 py-0.5 text-[10px] font-semibold border border-[var(--border-color)] bg-[var(--success-bg)] text-[var(--success)] font-sans font-bold">Active</span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $user->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 border-b border-[var(--border-color)] font-mono text-[var(--text-muted)]">{{ $user->phone }}</td>
                                    <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-[var(--text-secondary)]">{{ $user->email }}</td>
                                    <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                        @php
                                            $slug = $user->role?->slug ?? 'default';
                                            $badgeClasses = [
                                                'superadmin' => 'bg-[var(--warning-bg)] text-[var(--warning)] border border-[var(--border-color)]',
                                                'admin' => 'bg-[var(--info-bg)] text-[var(--info)] border border-[var(--border-color)]',
                                                'front_desk' => 'bg-[var(--success-bg)] text-[var(--success)] border border-[var(--border-color)]',
                                                'housekeeping' => 'bg-[var(--info-bg)] text-[var(--info)] border border-[var(--border-color)]',
                                                'default' => 'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border border-[var(--border-color)]',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border {{ $badgeClasses[$slug] ?? $badgeClasses['default'] }}">
                                            {{ $user->role?->name ?? 'No Role' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-right">
                                        <div class="flex items-center justify-end gap-2 select-none">
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors cursor-pointer" wire:click="openEditModal({{ $user->id }})" title="Edit user">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--danger-bg)] hover:text-[var(--danger)] transition-colors cursor-pointer" wire:click="confirmDelete({{ $user->id }})" title="Delete user">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="p-4 border-t border-[var(--border-color)] bg-[var(--bg-primary)]/30">
                        {{ $users->links('livewire.dashboard.pagination') }}
                    </div>
                @endif
            @endif
        </div>
    @else
        {{-- ── Roles & Permissions Tab Content ── --}}
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <h4 class="text-sm font-bold text-[var(--text-primary)]">Defined Roles Registry</h4>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">List of system and custom roles with dynamic checkboxed page access configuration.</p>
            </div>
            <button class="inline-flex items-center gap-1.5 rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-all cursor-pointer active:scale-[0.98]" wire:click="openAddRoleModal" id="btn-add-role">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Create Role
            </button>
        </div>

        {{-- Roles Table --}}
        <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="sticky top-0 z-10 select-none">
                            <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Role Name</th>
                            <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] font-mono">Slug</th>
                            <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Users Count</th>
                            <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)]">Authorized View Permissions</th>
                            <th class="px-4 py-3 text-xs font-semibold text-[var(--text-muted)] bg-[var(--bg-secondary)] border-b-2 border-[var(--border-color)] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-color)] text-xs text-[var(--text-secondary)]">
                        @foreach($roles as $role)
                            <tr class="hover:bg-[var(--bg-card-hover)] transition-colors even:bg-[var(--bg-primary)]/50">
                                <td class="px-4 py-3.5 border-b border-[var(--border-color)]">
                                    <div class="font-bold text-[var(--text-primary)] font-sans">{{ $role->name }}</div>
                                </td>
                                <td class="px-4 py-3.5 border-b border-[var(--border-color)] font-mono text-[var(--text-muted)]">{{ $role->slug }}</td>
                                <td class="px-4 py-3.5 border-b border-[var(--border-color)] font-semibold text-[var(--text-primary)]">
                                    <span class="inline-flex rounded-full bg-[var(--bg-secondary)] px-2.5 py-0.5 text-xs text-[var(--text-secondary)] border border-[var(--border-color)] font-mono">
                                        {{ $role->users_count }} staff
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 border-b border-[var(--border-color)] max-w-xs md:max-w-md">
                                    <div class="flex flex-wrap gap-1.5 font-sans">
                                        @php
                                            $rolePerms = $role->permissions ?? [];
                                            // Group granted permissions by page key
                                            $grouped = [];
                                            foreach ($rolePerms as $perm) {
                                                // Skip legacy / internal keys
                                                if ($perm === 'view_booking') continue;
                                                // Extract action + page: "view_rooms" → action=view, page=rooms
                                                $parts = explode('_', $perm, 2);
                                                if (count($parts) !== 2) continue;
                                                [$action, $pageKey] = $parts;
                                                $grouped[$pageKey][] = $action;
                                            }
                                        @endphp

                                        @if(empty($grouped))
                                            <span class="text-xs text-[var(--text-muted)] italic">No permissions (Locked)</span>
                                        @else
                                            @foreach($grouped as $pageKey => $grantedActions)
                                                @php
                                                    $pageInfo   = $pagesList[$pageKey] ?? null;
                                                    $pageName   = $pageInfo['name'] ?? ucfirst($pageKey);
                                                    $allActions = array_keys($pageInfo['actions'] ?? []);
                                                    $isAll      = !array_diff($allActions, $grantedActions) && count($allActions) > 1;
                                                    if ($isAll) {
                                                        $label = 'All';
                                                    } else {
                                                        $label = implode(', ', array_map('ucfirst', $grantedActions));
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center rounded-[var(--radius-sm)] px-2 py-0.5 text-[10px] font-semibold border {{ $isAll ? 'bg-[var(--info-bg)] text-[var(--info)] border-[var(--info)]/30' : 'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border-[var(--border-color)]' }}">
                                                    {{ $pageName }}: {{ $label }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 border-b border-[var(--border-color)] text-right">
                                    <div class="flex items-center justify-end gap-2 select-none">
                                        <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] transition-colors cursor-pointer" wire:click="openEditRoleModal({{ $role->id }})" title="Edit role & permissions">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if(!in_array($role->slug, ['superadmin', 'admin', 'front_desk', 'housekeeping', 'fnb'], true))
                                            <button class="p-1.5 rounded text-[var(--text-muted)] hover:bg-[var(--danger-bg)] hover:text-[var(--danger)] transition-colors cursor-pointer" wire:click="confirmDeleteRole({{ $role->id }})" title="Delete role">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ── ADD USER MODAL ── --}}
    @if($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click.self="closeAddModal">
            <div class="w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-5">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Add New Staff Account</h3>
                </div>

                <form wire:submit.prevent="createUser" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Full Name</label>
                        <input type="text" wire:model="name" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="e.g. John Doe" autofocus>
                        @error('name') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Phone Number</label>
                            <input type="text" wire:model="phone" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="08xxxxxxxxxx">
                            @error('phone') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Email Address</label>
                            <input type="email" wire:model="email" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="user@example.com">
                            @error('email') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Assigned Role</label>
                        <select wire:model="roleId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                            <option value="">- Select role -</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roleId') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Password</label>
                            <input type="password" wire:model="password" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="Min. 8 chars">
                            @error('password') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="Repeat password">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeAddModal">Cancel</button>
                        <button type="submit" class="rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove>Create Account</span>
                            <span wire:loading>Creating…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── EDIT USER MODAL ── --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click.self="closeEditModal">
            <div class="w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-5">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Edit Staff Account</h3>
                </div>

                <form wire:submit.prevent="updateUser" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Full Name</label>
                        <input type="text" wire:model="editName" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                        @error('editName') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Phone Number</label>
                            <input type="text" wire:model="editPhone" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                            @error('editPhone') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Email Address</label>
                            <input type="email" wire:model="editEmail" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                            @error('editEmail') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Assigned Role</label>
                        <select wire:model="editRoleId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                            <option value="">- Select role -</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('editRoleId') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">New Password <span class="text-xs text-[var(--text-muted)] font-normal ml-1">(leave blank to keep current)</span></label>
                        <input type="password" wire:model="editPassword" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="••••••••">
                        @error('editPassword') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeEditModal">Cancel</button>
                        <button type="submit" class="rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove>Save Changes</span>
                            <span wire:loading>Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── DELETE CONFIRM MODAL ── --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click.self="closeDeleteModal">
            <div class="w-full max-w-sm rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center gap-2 text-[var(--danger)] border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Confirm Delete</h3>
                </div>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed font-semibold">
                    Are you sure you want to delete <strong class="text-[var(--text-primary)] font-bold">{{ $deletingUserName }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                    <button class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeDeleteModal">Cancel</button>
                    <button class="rounded bg-[var(--danger)] hover:bg-[var(--danger)]/90 px-4 py-2 text-xs font-semibold text-white transition-colors cursor-pointer" wire:click="deleteUser" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete Account</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ── ROLE MODAL ── --}}
    @if($showRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click.self="closeRoleModal">
            <div class="w-full max-w-2xl rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg flex flex-col max-h-[90vh]">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-3 flex-shrink-0">
                    <svg class="h-5 w-5 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">
                        {{ $editingRoleId ? 'Edit Role & Permissions' : 'Create New Role' }}
                    </h3>
                </div>
 
                <form wire:submit.prevent="saveRole" class="flex flex-col flex-1 overflow-hidden min-h-0 space-y-4 pt-4">
                    {{-- Scrollable content wrapper --}}
                    <div class="flex-1 overflow-y-auto pr-1 space-y-4 min-h-0 custom-scrollbar">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Role Name</label>
                                <input type="text" wire:model="roleName" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="e.g. Supervisor">
                                @error('roleName') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-[var(--text-secondary)] mb-1.5 block">Role Slug (Unique Key)</label>
                                <input type="text" wire:model="roleSlug" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all font-mono" placeholder="e.g. supervisor">
                                @error('roleSlug') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
 
                        <div class="space-y-3 border-t border-[var(--border-color)] pt-3">
                            <span class="text-sm font-bold text-[var(--text-primary)] block">Access Control Configuration</span>
                            <span class="text-xs text-[var(--text-muted)] block">Configure authorized CRUD operations for this role per page.</span>
                            
                            <div class="overflow-x-auto border border-[var(--border-color)] rounded-lg bg-[var(--bg-secondary)]/35 mt-3 select-none">
                                <table class="w-full border-collapse text-left text-xs">
                                    <thead>
                                        <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)]">
                                            <th class="px-4 py-3 font-bold text-[var(--text-primary)]">Page / Feature</th>
                                            <th class="px-3 py-3 font-bold text-center text-[var(--text-primary)] w-20">View</th>
                                            <th class="px-3 py-3 font-bold text-center text-[var(--text-primary)] w-20">Create</th>
                                            <th class="px-3 py-3 font-bold text-center text-[var(--text-primary)] w-20">Edit</th>
                                            <th class="px-3 py-3 font-bold text-center text-[var(--text-primary)] w-20">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                                        @foreach($pagesList as $pageKey => $pageInfo)
                                            <tr class="hover:bg-[var(--bg-card-hover)] transition-colors">
                                                <td class="px-4 py-3.5 font-bold text-[var(--text-primary)]">
                                                    {{ $pageInfo['name'] }}
                                                </td>
                                                <td class="px-3 py-3.5 text-center">
                                                    @if(isset($pageInfo['actions']['view']))
                                                        <input type="checkbox" wire:model="rolePermissions.view_{{ $pageKey }}" class="h-4 w-4 text-[var(--accent-primary)] focus:ring-[var(--accent-primary)] border-[var(--border-color)] rounded cursor-pointer">
                                                    @else
                                                        <span class="text-[var(--text-muted)] font-semibold">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3.5 text-center">
                                                    @if(isset($pageInfo['actions']['create']))
                                                        <input type="checkbox" wire:model="rolePermissions.create_{{ $pageKey }}" class="h-4 w-4 text-[var(--accent-primary)] focus:ring-[var(--accent-primary)] border-[var(--border-color)] rounded cursor-pointer">
                                                    @else
                                                        <span class="text-[var(--text-muted)] font-semibold">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3.5 text-center">
                                                    @if(isset($pageInfo['actions']['edit']))
                                                        <input type="checkbox" wire:model="rolePermissions.edit_{{ $pageKey }}" class="h-4 w-4 text-[var(--accent-primary)] focus:ring-[var(--accent-primary)] border-[var(--border-color)] rounded cursor-pointer">
                                                    @else
                                                        <span class="text-[var(--text-muted)] font-semibold">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3.5 text-center">
                                                    @if(isset($pageInfo['actions']['delete']))
                                                        <input type="checkbox" wire:model="rolePermissions.delete_{{ $pageKey }}" class="h-4 w-4 text-[var(--accent-primary)] focus:ring-[var(--accent-primary)] border-[var(--border-color)] rounded cursor-pointer">
                                                    @else
                                                        <span class="text-[var(--text-muted)] font-semibold">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Fixed Modal Footer --}}
                    <div class="flex-shrink-0 border-t border-[var(--border-color)] pt-4 flex justify-end gap-3 items-center">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeRoleModal">Cancel</button>
                        <button type="submit" class="rounded-[var(--radius-sm)] bg-[var(--accent-primary)] hover:bg-[var(--accent-primary-hover)] px-4 py-2 text-xs font-semibold text-white transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove>Save Role</span>
                            <span wire:loading>Saving…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── DELETE ROLE CONFIRM MODAL ── --}}
    @if($showDeleteRoleModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" wire:click.self="closeDeleteRoleModal">
            <div class="w-full max-w-sm rounded-[var(--radius-lg)] bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center gap-2 text-[var(--danger)] border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <h3 class="text-base font-semibold text-[var(--text-primary)]">Confirm Delete Role</h3>
                </div>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed font-semibold">
                    Are you sure you want to delete the role <strong class="text-[var(--text-primary)] font-bold">{{ $deletingRoleName }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                    <button class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeDeleteRoleModal">Cancel</button>
                    <button class="rounded bg-[var(--danger)] hover:bg-[var(--danger)]/90 px-4 py-2 text-xs font-semibold text-white transition-colors cursor-pointer" wire:click="deleteRole" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete Role</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
