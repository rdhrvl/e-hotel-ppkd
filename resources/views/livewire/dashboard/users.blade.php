<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h3 class="text-lg font-bold text-[var(--text-primary)] tracking-tight">User Management</h3>
            <p class="text-xs text-[var(--text-muted)] mt-1">Manage staff accounts and roles</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative w-full sm:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[var(--text-muted)]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] pl-9 pr-4 py-2 text-sm text-[var(--text-primary)] placeholder-[#8e8d89] focus:border-[#111111] focus:outline-none transition-all" placeholder="Search name, email, phone…">
            </div>
            <button class="inline-flex items-center gap-1.5 rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-all cursor-pointer" wire:click="openAddModal" id="btn-add-user">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Add User
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] shadow-sm overflow-hidden">
        @if($users->isEmpty())
            <div class="text-center py-12 px-4 space-y-3">
                <svg class="h-12 w-12 mx-auto text-[var(--text-muted)]/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                <p class="text-[var(--text-muted)] font-medium">No users found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[var(--border-color)] bg-[var(--bg-primary)] text-[10px] font-bold uppercase tracking-wider text-[var(--text-muted)] sticky top-0 z-10">
                            <th class="p-4">User</th>
                            <th class="p-4">Phone</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4 text-right">Actions</th>
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
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded flex items-center justify-center font-bold text-xs uppercase tracking-wider bg-[#{{ $bgColor }}] text-[#{{ $textColor }}]">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-[var(--text-primary)] flex items-center gap-1.5">
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                    <span class="inline-flex rounded bg-[var(--success-bg)] px-1.5 py-0.5 text-[9px] font-bold text-[var(--success)] border border-[var(--border-color)]">You</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] text-[var(--text-muted)] mt-0.5">{{ $user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-mono text-xs text-[var(--text-muted)]">{{ $user->phone }}</td>
                                <td class="p-4 text-[var(--text-secondary)]">{{ $user->email }}</td>
                                <td class="p-4">
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
                                    <span class="inline-flex rounded px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $badgeClasses[$slug] ?? $badgeClasses['default'] }}">
                                        {{ $user->role?->name ?? 'No Role' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
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
        @endif
    </div>

    {{-- ── ADD USER MODAL ── --}}
    @if($showAddModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeAddModal">
            <div class="w-full max-w-md rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-5">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Add New User</h3>
                </div>

                <form wire:submit.prevent="createUser" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Full Name</label>
                        <input type="text" wire:model="name" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="e.g. John Doe" autofocus>
                        @error('name') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Phone Number</label>
                            <input type="text" wire:model="phone" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="08xxxxxxxxxx">
                            @error('phone') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Email Address</label>
                            <input type="email" wire:model="email" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="user@example.com">
                            @error('email') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Role</label>
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
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Password</label>
                            <input type="password" wire:model="password" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="Min. 8 chars">
                            @error('password') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="Repeat password">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeAddModal">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove>Create User</span>
                            <span wire:loading>Creating…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── EDIT USER MODAL ── --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeEditModal">
            <div class="w-full max-w-md rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-5">
                <div class="flex items-center gap-2.5 border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5 text-[var(--info)]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <h3 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wider">Edit User</h3>
                </div>

                <form wire:submit.prevent="updateUser" class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Full Name</label>
                        <input type="text" wire:model="editName" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                        @error('editName') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Phone Number</label>
                            <input type="text" wire:model="editPhone" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                            @error('editPhone') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Email Address</label>
                            <input type="email" wire:model="editEmail" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all">
                            @error('editEmail') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">Role</label>
                        <select wire:model="editRoleId" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all cursor-pointer">
                            <option value="">- Select role -</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('editRoleId') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-[var(--text-muted)] mb-1.5 uppercase tracking-wider">New Password <span class="text-[9px] text-[var(--text-muted)] normal-case font-medium ml-1">(leave blank to keep current)</span></label>
                        <input type="password" wire:model="editPassword" class="w-full rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-sm text-[var(--text-primary)] focus:border-[#111111] focus:outline-none transition-all" placeholder="••••••••">
                        @error('editPassword') <span class="text-xs text-[var(--danger)] mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                        <button type="button" class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeEditModal">Cancel</button>
                        <button type="submit" class="rounded bg-[var(--text-primary)] hover:bg-[var(--text-secondary)] px-4 py-2 text-xs font-semibold text-[var(--bg-card)] transition-colors" wire:loading.attr="disabled">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs" wire:click.self="closeDeleteModal">
            <div class="w-full max-w-sm rounded bg-[var(--bg-card)] border border-[var(--border-color)] p-6 shadow-lg space-y-4">
                <div class="flex items-center gap-2 text-[var(--danger)] border-b border-[var(--border-color)] pb-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    <h3 class="text-sm font-bold uppercase tracking-wider">Confirm Delete</h3>
                </div>
                <p class="text-xs text-[var(--text-secondary)] leading-relaxed font-semibold">
                    Are you sure you want to delete <strong class="text-[var(--text-primary)] font-bold">{{ $deletingUserName }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3 border-t border-[var(--border-color)] pt-4 mt-2">
                    <button class="rounded border border-[var(--border-color)] bg-[var(--bg-card)] px-4 py-2 text-xs font-semibold text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] transition-colors" wire:click="closeDeleteModal">Cancel</button>
                    <button class="rounded bg-[#9f2f2d] hover:bg-[#9f2f2d]/80 px-4 py-2 text-xs font-semibold text-white transition-colors cursor-pointer" wire:click="deleteUser" wire:loading.attr="disabled">
                        <span wire:loading.remove>Delete User</span>
                        <span wire:loading>Deleting…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
