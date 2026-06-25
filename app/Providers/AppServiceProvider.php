<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $permissions = [
            'view_dashboard',
            'view_rooms', 'create_rooms', 'edit_rooms', 'delete_rooms',
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'view_services', 'create_services', 'edit_services', 'delete_services',
            'view_audit_logs',
            'view_room_availability',
            'view_booking',
            'view_bookings', 'create_bookings', 'edit_bookings', 'delete_bookings',
            'view_guest_bills', 'create_guest_bills', 'edit_guest_bills', 'delete_guest_bills',
            'view_guests', 'create_guests', 'edit_guests', 'delete_guests',
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
            'view_housekeeping', 'create_housekeeping', 'edit_housekeeping', 'delete_housekeeping',
            'view_fnb', 'create_fnb', 'edit_fnb', 'delete_fnb'
        ];

        // Default permission mappings when permissions are not explicitly customized in database (null)
        $defaultRolePermissions = [
            'superadmin' => [
                'view_dashboard',
                'view_rooms', 'create_rooms', 'edit_rooms', 'delete_rooms',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_services', 'create_services', 'edit_services', 'delete_services',
                'view_audit_logs',
                'view_room_availability',
                'view_booking',
                'view_bookings', 'create_bookings', 'edit_bookings', 'delete_bookings',
                'view_guest_bills', 'create_guest_bills', 'edit_guest_bills', 'delete_guest_bills',
                'view_guests', 'create_guests', 'edit_guests', 'delete_guests',
                'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
                'view_housekeeping', 'create_housekeeping', 'edit_housekeeping', 'delete_housekeeping',
                'view_fnb', 'create_fnb', 'edit_fnb', 'delete_fnb'
            ],
            'admin' => [
                'view_dashboard',
                'view_rooms', 'create_rooms', 'edit_rooms', 'delete_rooms',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'view_services', 'create_services', 'edit_services', 'delete_services',
                'view_audit_logs',
                'view_room_availability',
                'view_booking',
                'view_bookings', 'create_bookings', 'edit_bookings', 'delete_bookings',
                'view_guest_bills', 'create_guest_bills', 'edit_guest_bills', 'delete_guest_bills',
                'view_guests', 'create_guests', 'edit_guests', 'delete_guests',
                'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
                'view_housekeeping', 'create_housekeeping', 'edit_housekeeping', 'delete_housekeeping',
                'view_fnb', 'create_fnb', 'edit_fnb', 'delete_fnb'
            ],
            'front_desk' => [
                'view_dashboard',
                'view_room_availability',
                'view_booking',
                'view_bookings', 'create_bookings', 'edit_bookings', 'delete_bookings',
                'view_guest_bills', 'create_guest_bills', 'edit_guest_bills', 'delete_guest_bills',
                'view_guests', 'create_guests', 'edit_guests', 'delete_guests',
                'view_payments', 'create_payments', 'edit_payments', 'delete_payments'
            ],
            'housekeeping' => [
                'view_dashboard',
                'view_housekeeping', 'create_housekeeping', 'edit_housekeeping', 'delete_housekeeping'
            ],
            'fnb' => [
                'view_dashboard',
                'view_fnb', 'create_fnb', 'edit_fnb', 'delete_fnb'
            ]
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission, $defaultRolePermissions) {
                if ($user->isSuperAdmin()) {
                    return true;
                }

                $role = $user->role;
                if (!$role) {
                    return false;
                }

                // If permissions are defined, check them dynamically
                if (is_array($role->permissions)) {
                    return in_array($permission, $role->permissions, true);
                }

                // Fallback to legacy mapping based on slug
                $fallback = $defaultRolePermissions[$role->slug] ?? [];
                return in_array($permission, $fallback, true);
            });
        }

        // Keep legacy gates for backward compatibility, referencing the dynamic gates
        Gate::define('isAdmin', function (User $user) {
            return Gate::allows('view_users', $user);
        });
        Gate::define('accessFrontDesk', function (User $user) {
            return Gate::allows('view_bookings', $user);
        });
        Gate::define('accessHousekeeping', function (User $user) {
            return Gate::allows('view_housekeeping', $user);
        });
        Gate::define('accessFnb', function (User $user) {
            return Gate::allows('view_fnb', $user);
        });
    }
}
