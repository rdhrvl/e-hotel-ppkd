<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('User Management')]
class Users extends Component
{
    use WithPagination;

    // List filters
    public string $search = '';

    // Add User modal
    public bool $showAddModal = false;
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $roleId = null;

    // Edit User modal
    public bool $showEditModal = false;
    public ?int $editingUserId = null;
    public string $editName = '';
    public string $editPhone = '';
    public string $editEmail = '';
    public string $editPassword = '';
    public ?int $editRoleId = null;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingUserId = null;
    public string $deletingUserName = '';

    // Roles & Permissions tab
    public string $activeTab = 'staff';
    public bool $showRoleModal = false;
    public ?int $editingRoleId = null;
    public string $roleName = '';
    public string $roleSlug = '';
    public array $rolePermissions = [];

    // Delete role confirmation
    public bool $showDeleteRoleModal = false;
    public ?int $deletingRoleId = null;
    public string $deletingRoleName = '';

    public array $pagesList = [
        'dashboard' => [
            'name' => 'Dashboard',
            'actions' => ['view' => 'View']
        ],
        'room_availability' => [
            'name' => 'Room Availability',
            'actions' => ['view' => 'View']
        ],
        'rooms' => [
            'name' => 'Rooms',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'bookings' => [
            'name' => 'Bookings',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'guests' => [
            'name' => 'Guests',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'housekeeping' => [
            'name' => 'Housekeeping',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'payments' => [
            'name' => 'Payments',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'fnb' => [
            'name' => 'Food & Beverage',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'users' => [
            'name' => 'Staff Accounts',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'services' => [
            'name' => 'Extra Services',
            'actions' => ['view' => 'View', 'create' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete']
        ],
        'audit_logs' => [
            'name' => 'Audit Logs',
            'actions' => ['view' => 'View']
        ],
    ];

    public array $allPermissions = [];

    public function boot(): void
    {
        $this->allPermissions = [];
        foreach ($this->pagesList as $pageKey => $pageInfo) {
            foreach ($pageInfo['actions'] as $action => $actionLabel) {
                $permissionKey = "{$action}_{$pageKey}";
                $this->allPermissions[$permissionKey] = "{$pageInfo['name']}: {$actionLabel}";
            }
        }
        $this->allPermissions['view_booking'] = 'Bookings: Create (Legacy)';
    }

    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        // Only admins and superadmins can manage users
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $usersQuery = User::with('role')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $users = $usersQuery->paginate(10);

        if ($this->getPage() > $users->lastPage()) {
            $this->setPage(max(1, $users->lastPage()));
            $users = $usersQuery->paginate(10);
        }

        $roles = Role::orderBy('name')->get();

        return view('livewire.dashboard.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    // ── Add User ─────────────────────────────────────────────────────

    public function openAddModal(): void
    {
        $this->reset(['name', 'phone', 'email', 'password', 'password_confirmation', 'roleId']);
        $this->showAddModal = true;
        $this->resetErrorBag();
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->resetErrorBag();
    }

    public function createUser(): void
    {
        $this->validate([
            'name'                  => 'required|string|max:255',
            'phone'                 => 'required|string|max:20|unique:users,phone',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'roleId'                => 'required|exists:roles,id',
        ]);

        User::create([
            'name'     => $this->name,
            'phone'    => $this->phone,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'role_id'  => $this->roleId,
        ]);

        session()->flash('success', "User \"{$this->name}\" created successfully.");
        $this->closeAddModal();
    }

    // ── Edit User ─────────────────────────────────────────────────────

    public function openEditModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->editName = $user->name;
        $this->editPhone = $user->phone;
        $this->editEmail = $user->email;
        $this->editRoleId = $user->role_id;
        $this->editPassword = '';
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingUserId = null;
        $this->resetErrorBag();
    }

    public function updateUser(): void
    {
        $this->validate([
            'editName'     => 'required|string|max:255',
            'editPhone'    => "required|string|max:20|unique:users,phone,{$this->editingUserId}",
            'editEmail'    => "required|email|unique:users,email,{$this->editingUserId}",
            'editPassword' => 'nullable|min:8',
            'editRoleId'   => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($this->editingUserId);

        $data = [
            'name'    => $this->editName,
            'phone'   => $this->editPhone,
            'email'   => $this->editEmail,
            'role_id' => $this->editRoleId,
        ];

        if ($this->editPassword !== '') {
            $data['password'] = Hash::make($this->editPassword);
        }

        $user->update($data);

        session()->flash('success', "User \"{$user->name}\" updated successfully.");
        $this->closeEditModal();
    }

    // ── Delete User ─────────────────────────────────────────────────────

    public function confirmDelete(int $userId): void
    {
        // Prevent self-deletion
        if ($userId === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user = User::findOrFail($userId);
        $this->deletingUserId = $userId;
        $this->deletingUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingUserId = null;
    }

    public function deleteUser(): void
    {
        if ($this->deletingUserId === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->closeDeleteModal();
            return;
        }

        $user = User::findOrFail($this->deletingUserId);
        $name = $user->name;
        $user->delete();

        session()->flash('success', "User \"{$name}\" deleted.");
        $this->closeDeleteModal();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
    }

    // ── Role & Permissions Operations ───────────────────────────────────

    public function openAddRoleModal(): void
    {
        $this->reset(['editingRoleId', 'roleName', 'roleSlug']);
        $this->rolePermissions = [];
        foreach ($this->pagesList as $pageKey => $pageInfo) {
            foreach (array_keys($pageInfo['actions']) as $action) {
                $permissionKey = "{$action}_{$pageKey}";
                $this->rolePermissions[$permissionKey] = false;
            }
        }
        $this->showRoleModal = true;
        $this->resetErrorBag();
    }
 
    public function openEditRoleModal(int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $roleId;
        $this->roleName = $role->name;
        $this->roleSlug = $role->slug;
        
        $this->rolePermissions = [];
        foreach ($this->pagesList as $pageKey => $pageInfo) {
            foreach (array_keys($pageInfo['actions']) as $action) {
                $permissionKey = "{$action}_{$pageKey}";
                $this->rolePermissions[$permissionKey] = in_array($permissionKey, $role->permissions ?? [], true);
            }
        }
        
        $this->showRoleModal = true;
        $this->resetErrorBag();
    }
 
    public function closeRoleModal(): void
    {
        $this->showRoleModal = false;
        $this->resetErrorBag();
    }
 
    public function saveRole(): void
    {
        $this->validate([
            'roleName' => 'required|string|max:255',
            'roleSlug' => 'required|string|max:255|unique:roles,slug,' . ($this->editingRoleId ?? 'NULL'),
        ]);
 
        // Filter permissions dictionary for truthy checkboxes
        $selectedPermissions = [];
        foreach ($this->rolePermissions as $perm => $checked) {
            if ($checked) {
                $selectedPermissions[] = $perm;
            }
        }
 
        // Add view_booking automatically if create_bookings is selected
        if (in_array('create_bookings', $selectedPermissions, true) && !in_array('view_booking', $selectedPermissions, true)) {
            $selectedPermissions[] = 'view_booking';
        }
 
        if ($this->editingRoleId) {
            $role = Role::findOrFail($this->editingRoleId);
            $role->update([
                'name' => $this->roleName,
                'slug' => $this->roleSlug,
                'permissions' => $selectedPermissions,
            ]);
            session()->flash('success', "Role \"{$role->name}\" updated successfully.");
        } else {
            $role = Role::create([
                'name' => $this->roleName,
                'slug' => $this->roleSlug,
                'permissions' => $selectedPermissions,
            ]);
            session()->flash('success', "Role \"{$role->name}\" created successfully.");
        }
 
        $this->closeRoleModal();
    }

    public function confirmDeleteRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        if ($role->users()->count() > 0) {
            session()->flash('error', "Cannot delete role \"{$role->name}\" because it is assigned to staff users.");
            return;
        }

        if (in_array($role->slug, ['superadmin', 'admin', 'front_desk', 'housekeeping', 'fnb'], true)) {
            session()->flash('error', "System roles like \"{$role->name}\" cannot be deleted.");
            return;
        }

        $this->deletingRoleId = $roleId;
        $this->deletingRoleName = $role->name;
        $this->showDeleteRoleModal = true;
    }

    public function closeDeleteRoleModal(): void
    {
        $this->showDeleteRoleModal = false;
        $this->deletingRoleId = null;
    }

    public function deleteRole(): void
    {
        $role = Role::findOrFail($this->deletingRoleId);
        $name = $role->name;
        $role->delete();

        session()->flash('success', "Role \"{$name}\" deleted successfully.");
        $this->closeDeleteRoleModal();
    }
}
